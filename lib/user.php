<?php 
defined ('_PROSPERITY') or die('restricted access'); 

class User{
	var $id;
	var $name;
	var $login_name;
	
	private function User(){
		$this->id = 0;
		$this->name = 'guest';
		$this->login_name='guest';
	}
	
	function &getInstance(){
		static $instance;
		if(! is_object($instance)){
			$instance = new User();
			//check session
			if(intval($_SESSION['_domain']['agent']['id']) > 0){ 
				$instance->id = intval($_SESSION['_domain']['agent']['id']);
				$instance->name = $_SESSION['_domain']['agent']['name'];
				$instance->login_name=$_SESSION['_domain']['agent']['login_name'];
			}
		}
		
		return $instance;
	}
	
	function isGuest(){
		if($this->id == 0)
			return true;
		else
			return false;
	}
	
	function login($credential){
		$db = Factory::getDatabase();
		$login_name = $db->escape($credential['login_name']);
		$password = md5($credential['password']);
		$query = "SELECT * FROM agent WHERE agent_login_name='$login_name' AND agent_password='$password'"; 
		$db->setQuery($query);
		$user = $db->loadObject();
		if($user){
			
			$this->id = intval($user->agent_id);
			$this->login_name = $user->agent_login_name;
			$this->name = $user->agent_name;
			
			
			//save to session
			$_SESSION['_domain']['agent']['id'] = $this->id ;
			$_SESSION['_domain']['agent']['name'] = $this->name ;
			$_SESSION['_domain']['agent']['login_name'] = $this->login_name;
			
			//check for remember me
			$remember_me = Request::getVar('remember_me');
			if(intval($remember_me) == 1)
			{
				//set cookie
				$value = "$this->id,$this->name,$this->login_name";
				setcookie("dotta_agent", $value, time()+(3600*24*7)); // 7 day
			}
			return true;
		}
		
		return false;
		
	}
	
	function logout()
	{
		$this->id = 0;
		$this->login_name='guest';
		$_SESSION['_domain']['agent']['id'] = "" ;
		$_SESSION['_domain']['agent']['login_name'] = "";
	}
	
}
?>