<?php

/**
 * ConfigOptions, generated on Mon, 27 Feb 2006 14:00:37 +0100 by
 *
 * @author Marcos Saiz <marcos.saiz@fengoffice.com>
 */
class UserWsConfigOptions extends BaseUserWsConfigOptions {

  	/**
  	 * Cached array of config option values. Nested by: USER / CONFIG OPTION
  	 */
  	protected $config_option_values;
  	
  	protected $config_options = array();
  	
  	function loadConfigOptionsCache(){
  		$options = self::findAll();
  		foreach ($options as $option)
  			$this->config_options[$option->getName()] = $option; 
  	}
  	
	function resetConfigOptionsCache(){
  		$this->config_options = array();
  	}
  	
  	function updateConfigOptionCache($config_option){
  		$this->config_options[$config_option->getName()] = $config_option;
  	}
  	
	/**
	 * Return all options in specific category
	 *
	 * @param UserWsConfigCategory $category
	 * @param boolean $include_system_options Include system options in the result array
	 * @return array
	 */
	static function getOptionsByCategory(UserWsConfigCategory $category, $include_system_options = false) {
		$conditions = $include_system_options ?
		array('`category_name` = ?', $category->getName()) :
		array('`category_name` = ? AND `is_system` = ?', $category->getName(), false);

		$options = self::findAll(array(
        'conditions' => $conditions,
        'order' => '`option_order`'
        )); // findAll
        
        //load options into cache
  		foreach ($options as $option)
  			self::instance()->config_options[$option->getName()] = $option; 
  		return $options;
	} // getOptionsByCategory
	
	/**
	 * Return all options in specific category
	 *
	 * @param UserWsConfigCategory $category
	 * @param boolean $include_system_options Include system options in the result array
	 * @return array
	 */
	static function getOptionsByCategoryName($category_name, $include_system_options = false) {
		$conditions = $include_system_options ?
		array('`category_name` = ?', $category_name) :
		array('`category_name` = ? AND `is_system` = ?', $category_name, false);

		$options = self::findAll(array(
        'conditions' => $conditions,
        'order' => '`option_order`'
        )); // findAll
        
        //load options into cache
  		foreach ($options as $option)
  			self::instance()->config_options[$option->getName()] = $option; 
  		return $options;
	} // getOptionsByCategory

	/**
	 * Return the number of config options in specific category
	 *
	 * @param UserWsConfigCategory $category
	 * @param boolean $include_system_options
	 * @return integer
	 */
	static function countOptionsByCategory(UserWsConfigCategory $category, $include_system_options = false) {
		$conditions = $include_system_options ?
		array('`category_name` = ?', $category->getName()) :
		array('`category_name` = ? AND `is_system` = ?', $category->getName(), false);

		return self::count($conditions);
	} // countOptionsByCategory

	/**
	 * Return value of specific option
	 *
	 * @access public
	 * @param string $name
	 * @param mixed $default Default value that is returned in case of any error
	 * @return null
	 */
	static function getOptionValue($name, $user_id, $default = null) {
		$option = self::instance()->getByNameFromCache($name);
		return $option instanceof UserWsConfigOption ? $option->getUserValue($user_id, 0, $default) : $default;
	} // getOptionValue

	/**
	 * Return value of specific option
	 *
	 * @access public
	 * @param string $name
	 * @param mixed $default Default value that is returned in case of any error
	 * @return null
	 */
	static function getDefaultOptionValue($name, $default = null) {
		$option = self::instance()->getByNameFromCache($name);
		return $option instanceof UserWsConfigOption ? $option->getDefaultValue() : $default;
	} // getOptionValue

	/**
	 * Return config option by name
	 *
	 * @access public
	 * @param string $name
	 * @return UserWsConfigOption
	 */
	function getByNameFromCache($name){
    	if (!array_key_exists($name, $this->config_options)){
    		$this->config_options[$name] = self::getByName($name);
    	}
    	return $this->config_options[$name];
    }
	
	
	/**
	 * Return config option by name
	 *
	 * @access public
	 * @param string $name
	 * @return UserWsConfigOption
	 */
	static function getByName($name) {
		return self::findOne(array(
        'conditions' => array('`name` = ?', $name)
		)); // if
	} // getByName

} // ConfigOptions

?>