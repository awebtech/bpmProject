<?php

/**
 * Company website class
 *
 * @version 1.0
 * @author Ilija Studen <ilija.studen@gmail.com>
 */
final class CompanyWebsite {

	/** Name of the cookie / session var where we save session_id **/
	const USER_SESSION_ID_VAR = 'user_session_id';

	/**
	 * Owner company
	 *
	 * @var Company
	 */
	private $company;

	/**
	 * Logged user
	 *
	 * @var User
	 */
	private $logged_user;

	/**
	 * Selected project
	 *
	 * @var Project
	 */
	private $selected_project;

	/**
	 * Init company website environment
	 *
	 * @access public
	 * @param void
	 * @return null
	 * @throws Error
	 */
	function init() {
		if(isset($this) && ($this instanceof CompanyWebsite)) {
			$this->initCompany();
			$this->initLoggedUser();
			$this->initActiveProject();
		} else {
			CompanyWebsite::instance()->init();
		} // if
	} // init

	/**
	 * Init company based on subdomain
	 *
	 * @access public
	 * @param string
	 * @return null
	 * @throws Error
	 */
	private function initCompany() {
		$company = Companies::getOwnerCompany();
		if(!($company instanceof Company)) {
			throw new OwnerCompanyDnxError();
		} // if

		if(!($company->getCreatedBy() instanceof User)) {
			throw new AdministratorDnxError();
		} // if

		$this->setCompany($company);
	} // initCompany

	/**
	 * Init active project, if we have active_project $_GET var
	 *
	 * @access public
	 * @param void
	 * @return null
	 * @throws Error
	 */
	private function initActiveProject() {
		$project_id = array_var($_GET, 'active_project');
		if (empty($project_id)) {
			$this->setProject(null);
		} else {
			$project = Projects::findById($project_id);
			$this->setProject($project);
		} // if
	} // initActiveProject

	/**
	 * This function will use session ID from session or cookie and if presend log user
	 * with that ID. If not it will simply break.
	 *
	 * When this function uses session ID from cookie the whole process will be treated
	 * as new login and users last login time will be set to current time.
	 *
	 * @access public
	 * @param void
	 * @return boolean
	 */
	private function initLoggedUser() {
		$user_id       = Cookie::getValue('id');
		$twisted_token = Cookie::getValue('token');
		$remember      = (boolean) Cookie::getValue('remember', false);

		if(empty($user_id) || empty($twisted_token)) {
			return false; // we don't have a user
		} // if

		$user = Users::findById($user_id);
		if(!($user instanceof User)) {
			return false; // failed to find user
		} // if
		if(!$user->isValidToken($twisted_token)) {
			return false; // failed to validate token
		} // if

		$last_act = $user->getLastActivity();
		if ($last_act) {
			$session_expires = $last_act->advance(SESSION_LIFETIME, false);
		}
		if(!$last_act || $session_expires!=null && DateTimeValueLib::now()->getTimestamp() < $session_expires->getTimestamp()) {
			$this->setLoggedUser($user, $remember, true);
		} else {
			$this->logUserIn($user, $remember);
		} // if
		 
		//$this->selected_project = $user->getPersonalProject();
	} // initLoggedUser

	// ---------------------------------------------------
	//  Utils
	// ---------------------------------------------------

	/**
	 * Log user in
	 *
	 * @access public
	 * @param User $user
	 * @param boolean $remember
	 * @return null
	 */
	function logUserIn(User $user, $remember = false) {
		$user->setLastLogin(DateTimeValueLib::now());

		if(is_null($user->getLastActivity())) {
			$user->setLastVisit(DateTimeValueLib::now());
		} else {
			$user->setLastVisit($user->getLastActivity());
		} // if

		$this->setLoggedUser($user, $remember, true);
	} // logUserIn

	/**
	 * Log out user
	 *
	 * @access public
	 * @param void
	 * @return null
	 */
	function logUserOut() {
		$this->logged_user = null;
		Cookie::unsetValue('id');
		Cookie::unsetValue('token');
		Cookie::unsetValue('remember');
	} // logUserOut

	// ---------------------------------------------------
	//  Getters and setters
	// ---------------------------------------------------

	/**
	 * Get company
	 *
	 * @access public
	 * @param null
	 * @return Company
	 */
	function getCompany() {
		return $this->company;
	} // getCompany

	/**
	 * Set company value
	 *
	 * @access public
	 * @param Company $value
	 * @return null
	 */
	function setCompany(Company $value) {
		$this->company = $value;
	} // setCompany

	/**
	 * Get logged_user
	 *
	 * @access public
	 * @param null
	 * @return User
	 */
	function getLoggedUser() {
		return $this->logged_user;
	} // getLoggedUser

	/**
	 * Set logged_user value
	 *
	 * @access public
	 * @param User $value
	 * @param boolean $remember Remember this user for 2 weeks (configurable)
	 * @param DateTimeValue $set_last_activity_time Set last activity time. This property is turned off in case of feed
	 *   login for instance
	 * @return null
	 * @throws DBQueryError
	 */
	function setLoggedUser(User $user, $remember = false, $set_last_activity_time = true, $set_cookies = true) {
		if($set_last_activity_time) {
			$user->setLastActivity(DateTimeValueLib::now());
			
			// Disable updating user info
			$old_updated_on = $user->getUpdatedOn();
			$user->setUpdatedOn(DateTimeValueLib::now()); 
			$user->setUpdatedOn($old_updated_on);
			
			$user->save();
		} // if

		if ($set_cookies) {
			$expiration = $remember ? REMEMBER_LOGIN_LIFETIME : SESSION_LIFETIME;
	
			Cookie::setValue('id', $user->getId(), $expiration);
			Cookie::setValue('token', $user->getTwistedToken(), $expiration);
	
			if($remember) {
				Cookie::setValue('remember', 1, $expiration);
			} else {
				Cookie::unsetValue('remember');
			} // if
		}

		$this->logged_user = $user;
	} // setLoggedUser

	/**
	 * Get project
	 *
	 * @access public
	 * @param null
	 * @return Project
	 */
	function getProject() {
		return $this->selected_project;
	} // getProject

	/**
	 * Set project value
	 *
	 * @access public
	 * @param Project $value
	 * @return null
	 */
	function setProject($value) {
		if(is_null($value) || ($value instanceof Project)) $this->selected_project = $value;
	} // setProject

	/**
	 * Return single CompanyWebsite instance
	 *
	 * @access public
	 * @param void
	 * @return CompanyWebsite
	 */
	static function instance() {
		static $instance;
		if(!($instance instanceof CompanyWebsite)) {
			$instance = new CompanyWebsite();
		} // if
		return $instance;
	} // instance

} // CompanyWebsite

?>