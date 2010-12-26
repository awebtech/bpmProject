<?php


/**
 * MailContents class
 *
 * @author Carlos Palma <chonwil@gmail.com>
 */
abstract class BaseMailContents extends ProjectDataObjects {

	/**
	 * Column name => Column type map
	 *
	 * @var array
	 * @static
	 */
	static private $columns = array(
    'id' => DATA_TYPE_INTEGER,
    'account_id' => DATA_TYPE_INTEGER, 
    'uid' => DATA_TYPE_STRING,
    'from' => DATA_TYPE_STRING,
    'from_name' => DATA_TYPE_STRING,
    'sent_date' => DATA_TYPE_DATETIME,
	'received_date' => DATA_TYPE_DATETIME,
    'subject' => DATA_TYPE_STRING,
    'has_attachments' => DATA_TYPE_BOOLEAN, 
    'size' => DATA_TYPE_INTEGER, 
    'state' => DATA_TYPE_INTEGER, 
    'is_deleted' => DATA_TYPE_BOOLEAN, 
    'is_shared' => DATA_TYPE_BOOLEAN,
    'is_private' => DATA_TYPE_BOOLEAN,
    'created_on' => DATA_TYPE_DATETIME, 
    'created_by_id' => DATA_TYPE_INTEGER,
	'trashed_on' => DATA_TYPE_DATETIME,
    'trashed_by_id' => DATA_TYPE_INTEGER,
	'imap_folder_name' => DATA_TYPE_STRING,
	'account_email' => DATA_TYPE_STRING,
	'content_file_id' => DATA_TYPE_STRING,
    'archived_on' => DATA_TYPE_DATETIME,
    'archived_by_id' => DATA_TYPE_INTEGER,
	'message_id' => DATA_TYPE_STRING,
	'conversation_id' => DATA_TYPE_STRING,
	'in_reply_to_id' => DATA_TYPE_STRING,
	'sync' => DATA_TYPE_BOOLEAN, 
	);

	/**
	 * Construct
	 *
	 * @return BaseMailContents
	 */
	function __construct() {
		Hook::fire('object_definition', 'MailContent', self::$columns);
		parent::__construct('MailContent', 'mail_contents', true);
	} // __construct

	// -------------------------------------------------------
	//  Description methods
	// -------------------------------------------------------

	/**
	 * Return array of object columns
	 *
	 * @access public
	 * @param void
	 * @return array
	 */
	function getColumns() {
		return array_keys(self::$columns);
	} // getColumns

	/**
	 * Return column type
	 *
	 * @access public
	 * @param string $column_name
	 * @return string
	 */
	function getColumnType($column_name) {
		if(isset(self::$columns[$column_name])) {
			return self::$columns[$column_name];
		} else {
			return DATA_TYPE_STRING;
		} // if
	} // getColumnType

	/**
	 * Return array of PK columns. If only one column is PK returns its name as string
	 *
	 * @access public
	 * @param void
	 * @return array or string
	 */
	function getPkColumns() {
		return 'id';
	} // getPkColumns

	/**
	 * Return name of first auto_incremenent column if it exists
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	function getAutoIncrementColumn() {
		return 'id';
	} // getAutoIncrementColumn

	/**
	 * Return system columns
	 *
	 * @access public
	 * @param void
	 * @return array
	 */
	function getSystemColumns() {
		return array_merge(parent::getSystemColumns(), array(
      	'message_id', 'conversation_id', 'in_reply_to_id', 'account_id', 'uid', 'content_file_id')
		);
	} // getSystemColumns
	
	/**
    * Return external columns
    *
    * @access public
    * @param void
    * @return array
    */
    function getExternalColumns() {
      return array('created_by_id');
    } // getExternalColumns
	
	/**
    * Return report object title columns
    *
    * @access public
    * @param void
    * @return array
    */
    function getReportObjectTitleColumns() {
      return array('subject');
    } // getReportObjectTitleColumns
    
    /**
    * Return report object title
    *
    * @access public
    * @param void
    * @return string
    */
    function getReportObjectTitle($values) {
    	$subject = isset($values['subject']) ? $values['subject'] : ''; 
    	return $subject;
    } // getReportObjectTitle

	// -------------------------------------------------------
	//  Finders
	// -------------------------------------------------------

	/**
	 * Do a SELECT query over database with specified arguments
	 *
	 * @access public
	 * @param array $arguments Array of query arguments. Fields:
	 *
	 *  - one - select first row
	 *  - conditions - additional conditions
	 *  - order - order by string
	 *  - offset - limit offset, valid only if limit is present
	 *  - limit
	 *
	 * @return one or MailContents objects
	 * @throws DBQueryError
	 */
	function find($arguments = null) {
		if(isset($this) && instance_of($this, 'MailContents')) {
			return parent::find($arguments);
		} else {
			return MailContents::instance()->find($arguments);
			//$instance =& MailContents::instance();
			//return $instance->find($arguments);
		} // if
	} // find

	/**
	 * Find all records
	 *
	 * @access public
	 * @param array $arguments
	 * @return one or MailContents objects
	 */
	function findAll($arguments = null) {
		if(isset($this) && instance_of($this, 'MailContents')) {
			return parent::findAll($arguments);
		} else {
			return MailContents::instance()->findAll($arguments);
			//$instance =& MailContents::instance();
			//return $instance->findAll($arguments);
		} // if
	} // findAll

	/**
	 * Find one specific record
	 *
	 * @access public
	 * @param array $arguments
	 * @return MailContent
	 */
	function findOne($arguments = null) {
		if(isset($this) && instance_of($this, 'MailContents')) {
			return parent::findOne($arguments);
		} else {
			return MailContents::instance()->findOne($arguments);
			//$instance =& MailContents::instance();
			//return $instance->findOne($arguments);
		} // if
	} // findOne

	/**
	 * Return object by its PK value
	 *
	 * @access public
	 * @param mixed $id
	 * @param boolean $force_reload If true cache will be skipped and data will be loaded from database
	 * @return MailContent
	 */
	function findById($id, $force_reload = false) {
		if(isset($this) && instance_of($this, 'MailContents')) {
			return parent::findById($id, $force_reload);
		} else {
			return MailContents::instance()->findById($id, $force_reload);
			//$instance =& MailContents::instance();
			//return $instance->findById($id, $force_reload);
		} // if
	} // findById

	/**
	 * Return number of rows in this table
	 *
	 * @access public
	 * @param string $conditions Query conditions
	 * @return integer
	 */
	function count($condition = null) {
		if(isset($this) && instance_of($this, 'MailContents')) {
			return parent::count($condition);
		} else {
			return MailContents::instance()->count($condition);
			//$instance =& MailContents::instance();
			//return $instance->count($condition);
		} // if
	} // count

	/**
	 * Delete rows that match specific conditions. If $conditions is NULL all rows from table will be deleted
	 *
	 * @access public
	 * @param string $conditions Query conditions
	 * @return boolean
	 */
	function delete($condition = null) {
		if(isset($this) && instance_of($this, 'MailContents')) {
			return parent::delete($condition);
		} else {
			return MailContents::instance()->delete($condition);
			//$instance =& MailContents::instance();
			//return $instance->delete($condition);
		} // if
	} // delete

	/**
	 * This function will return paginated result. Result is an array where first element is
	 * array of returned object and second populated pagination object that can be used for
	 * obtaining and rendering pagination data using various helpers.
	 *
	 * Items and pagination array vars are indexed with 0 for items and 1 for pagination
	 * because you can't use associative indexing with list() construct
	 *
	 * @access public
	 * @param array $arguments Query argumens (@see find()) Limit and offset are ignored!
	 * @param integer $items_per_page Number of items per page
	 * @param integer $current_page Current page number
	 * @return array
	 */
	function paginate($arguments = null, $items_per_page = 10, $current_page = 1, $count = null) {
		if(isset($this) && instance_of($this, 'MailContents')) {
			return parent::paginate($arguments, $items_per_page, $current_page, $count);
		} else {
			return MailContents::instance()->paginate($arguments, $items_per_page, $current_page, $count);
			//$instance =& MailContents::instance();
			//return $instance->paginate($arguments, $items_per_page, $current_page);
		} // if
	} // paginate

	/**
	 * Return manager instance
	 *
	 * @return MailContents
	 */
	function instance() {
		static $instance;
		if(!instance_of($instance, 'MailContents')) {
			$instance = new MailContents();
		} // if
		return $instance;
	} // instance

} // MailContents

?>