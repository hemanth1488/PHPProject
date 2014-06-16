<?php 
defined ('FACEBOOK_APP') or die('restricted access'); 

class Database{
	var $_query;
	var $_resource;
	
	private function Database(){
		$this->connect();
	}
	
	function &getInstance(){
		static $instance;
		if(!is_object($instance)){
			$instance = new Database();
		}
		return $instance;
	}
	
	function connect(){
		$config = Factory::getConfig();
		$this->_resource = mysql_connect($config->db_host, $config->db_username, $config->db_password);
		if(! $this->_resource) die('Could not connect: ' . mysql_error());
		
		if(! mysql_select_db($config->db_database)) die('Could not connect to database: '.$config->db_database.' ' . mysql_error());
	}
	
	function setQuery($query){
		$this->_query = $query;
	}
	
	//run insert, update, delete
	function query(){
		mysql_query($this->_query);	
	} 
	
	function insert_id(){
		return mysql_insert_id();		
	}
	
	//run select. packing each row into a object, then push to an array
	function loadObjectList(){
		$result = mysql_query($this->_query);
		$array = array();
		while($row = mysql_fetch_object($result)){
			array_push($array, $row);
		}
		
		mysql_free_result($result);
		return $array;
	}
	
	//run select. return first row
	function loadObject(){
		$result = mysql_query($this->_query);
		if($row = mysql_fetch_object($result)){
			return $row;
		}
		
		return FALSE;
	}
	
	
	//run select, return the first column of the first row
	function loadResult(){
		$result = mysql_query($this->_query);
		$row = mysql_fetch_array($result, MYSQL_NUM);
		if($row){
			return $row[0];
		}
		
		mysql_free_result($result);
	}
	
	function escape($str){
		if(get_magic_quotes_gpc()) $str=stripslashes($str);
		return mysql_real_escape_string ($str);
	}
	
	//htmlentities — Convert all applicable characters to HTML entities
	function removeScript($str) {
		return htmlentities($str);
	}
}
?>