<?php

Class SessionControl {
	
	private $login;
	private $pw;
	private $token;
	private $authenticated;
	private $user_data;
	private $user_id;
	private $_session_id;
	
	/*
	 * @param ConnectionDataInterface $cdi
	 * @param String login
	 * @param String password
	 * @return void
	 */
	public function __construct(ConnectionDataInterface $cdi, $login=null, $pw=null){
		$this->setCDI($cdi);	
		$this->setLogin($login);
		$this->setPw($pw);
		$ip = $_SERVER["REMOTE_ADDR"];
		$this->setIP($ip);
		error_log("SessionControl initiated from {$ip} at " . date('Y-m-d h:i:s'));
	}
	
	/*
	 * @param String login[optional]
	 * @param String password[optional]
	 * @returns 0 SignIn Failed. -1 Account is not activated. Token String on Success 
	 *  
	 */
	public function signin($login=null, $pw=null){
		
		if(!$this->login || !$this->pw){
			if($login == null || $pw == null){
				error_log('returned here, login or pw null');
				return FALSE;
			}
			$this->setLogin($login);
			$this->setPw($pw);
		} 
		$email = $this->getLogin();
		$password = $this->getPw();
		
		$cdi = $this->getCDI();
		$db = new DBServices($cdi);
		$conn = $db->connect();
		$sql = "SELECT * FROM users WHERE email='{$email}' AND password=MD5('{$password}') LIMIT 1";
		$query = $conn->query($sql);
		if (!$query){
			error_log("Error with SQL: '{$sql}'");
			return FALSE;
		}
		$result = $query->fetch_array(MYSQLI_ASSOC);
		$conn->close();
		
		$this->setUserId($result['id']);
		
		if($result == NULL){
			//error_log('returned here, result null');
			return 0;
		}
		
		
		if($result['status'] == 0){
			$_SESSION['cellphone'] = $result['cellphone'];
			return -1;
		}
		
		
		$redis = new Redis();
		$redis->connect('sessioncache.edmip9.0001.use1.cache.amazonaws.com');
		
		$user_id = $result['id'];
		
		$redis->hSet('users:'.$user_id, 'id', $result['id']);
		$redis->hSet('users:'.$user_id, 'user_id', $result['id']);
		$redis->hSet('users:'.$user_id, 'name', $result['name']);
		$redis->hSet('users:'.$user_id, 'email', $result['email']);
		$redis->hSet('users:'.$user_id, 'cellphone', $result['cellphone']);
		$redis->hSet('users:'.$user_id, 'country', $result['country']);
		$redis->hSet('users:'.$user_id, 'profile_picture', $result['profile_picture']);
		$redis->hSet('users:'.$user_id, 'gender', $result['gender']);
		$redis->hSet('users:'.$user_id, 'status', $result['status']);
		$redis->hSet('users:'.$user_id, 'birthday', $result['birthday']);
		
		//$redis->expire('users:'.$user_id, 7200);
		$redis->close();
		if ($this->create() === FALSE){
			// @TODO could be an exception instead
			error_log("Can't create access session token");
			return FALSE; // False if can't create session token
		}
		
		$auth = $this->auth();
		if($auth !== false){
			return 1;
		}
		return 0;
		//return $auth;
	}
	
	private function genToken($string){
		$timestamp = date('Y-m-d h:i:s');
		$token = sha1($timestamp . "4785vcn8cvhnh88590y5vchgh" . $string);
		return $token;
	}
	
	/*
	 * @return Boolean. Returns FALSE on failure or TRUE on success.
	 */ 
	public function create(){
		
		try{
		
			$user_id = $this->getUserId();
			$email = $this->getLogin();
			$ip = $this->getIP();
			$token = $this->genToken($ip);
			
			$jcdi = new JooobzCDI;
			$db = new DBServices($jcdi);
			$conn = $db->connect();
			
			$redis = new Redis();
			$redis->connect('sessioncache.edmip9.0001.use1.cache.amazonaws.com');
			
			$redis->setex($token, 3600, "{$user_id}:{$ip}");
			
			$sql = "INSERT INTO sessions(user_id, email, token, active, ip, created) ";
			$sql .= "VALUES('{$user_id}','{$email}', '{$token}', 1, '{$ip}', now())";
			
			$r = $conn->query($sql);
			
			if($r == FALSE){
				$_SESSION["token"] = FALSE;
				return FALSE;
			} else {
				$_SESSION["token"] = $token;
				$this->setToken($token);
				return TRUE;
			}
		} catch(Exception $e){
			error_log('Exception creating session: '.$e);
			return FALSE;
		}
	}
	
	/*
	 * @param String token[optional] If no token is passed auth check if the user has any open session.
	 * @return TRUE on success or FALSE on failure
	 */
	public function auth($token=false, $auth_only=true){
		
		if(!isset($_SESSION["token"]) ){
			if($token == null){
				error_log("token is null.");
				return FALSE;
			} else {
				$_SESSION["token"] = $token;	
			}
		} else {
			$token = $_SESSION['token'];	
		}
		
		$this->setToken($token);
		$ip = $this->getIP();
		
		try {
			
			$redis = new Redis();
			$redis->connect('sessioncache.edmip9.0001.use1.cache.amazonaws.com');
			
			$val = $redis->get($token);
			error_log('val: '.$val);
			
			if(!$val){
				return FALSE;
			} else {
				$val_arr = explode(':', $val);
				if($ip !== $val_arr[1]){
					error_log('ip invalid '.$ip);
					return FALSE;
				}
				$user_id = $val_arr[0];
				$this->setUserId($user_id);
				
				/*
				if(!$this->update()){
					error_log("updateSession problem, token value: '{$token}', user IP '{$ip}'");
					throw new Exception("updateSession MUST work!");
				}*/
				//$redis->setex($token, 3600, "{$user_id}:{$ip}");
				
				$new_token = $this->genToken($ip);
				
				$redis->delete($token);
				$redis->setex($new_token, 3600, "{$user_id}:{$ip}");
				$_SESSION['token'] = $new_token;
				$_SESSION['user_id'] = $user_id;
				
				$this->setToken($new_token);
				$this->authenticated = TRUE;
				
				return $new_token;
				
				
				// appended part, let's see how persistent sessions work for us
				//$this->authenticated = TRUE;
				//$_SESSION['user_id'] = $user_id;
				//return $token;
			}
		} catch(Exception $e) {
			error_log('Checkpoint 2, e: '.$e);
		}
		
	}

	public function checkSecurityClearance(){
		if(!isset($_SESSION["token"])){
				return FALSE; 
					
		}
		$token = $_SESSION['token'];
		$this->setToken($token);
		$ip = $this->getIP();
		
		try {
			$redis = new Redis();
			$redis->connect('sessioncache.edmip9.0001.use1.cache.amazonaws.com');
			
			$val = $redis->get($token);
			if(!$val){
				return FALSE;
			} else {
				$val_arr = explode(':', $val);
				if($ip !== $val_arr[1]){
					return FALSE;
				}
				$user_id = $val_arr[0];
				$this->setUserId($user_id);
				
				$redis->setex($token, 3600, "{$user_id}:{$ip}");
				
				$this->authenticated = TRUE;
				
				return $token;
				
			}
		} catch(Exception $e) { }
		
		return TRUE;
	}
	
	/*
	 * @param ID id. The Session ID of the record that will be updated.
	 * @return TRUE on success or FALSE on failure
	 */
	//public function update($genNewToken=true){
	public function update(){
		
		$id = $this->getUserId();	
		/// NEW adition
		$ip = $this->getIP();
		$old_token = $this->getToken();
		$token = $this->genToken($ip);
		
		
		$redis = new Redis();
		$redis->connect('sessioncache.edmip9.0001.use1.cache.amazonaws.com');
		
		$val = $redis->get($old_token);
		
		error_log ('old token '. $old_token . ' val: ' . $val);
		error_log('comparison ' . ($id . ':' . $ip));
		
		if($val === ($id . ':' . $ip)){
			$redis->delete($old_token);
			$redis->setex($token, 3600, "{$id}:{$ip}");
			
			$_SESSION['token'] = $token;
			$this->setToken($token);
			
			return TRUE;
		} else {
			return FALSE;
		}
		
	}
	
	/*
	 * @param String token[optional] 
	 * @return TRUE on success or FALSE on failure NOTE: if token is not given then it will check for open sessions
	 * but keep in mind that it still needs a token from someplace or it will return FALSE
	 */
	public function close($token=null){
		
		if(!isset($_SESSION["token"]) ){
			if($token == null){
				return FALSE;
			} 
		} else {
			$token = $_SESSION['token'];
			
		}
		$user_id = $_SESSION['user_id'];
	
		unset($_SESSION['user_id']);
		unset($_SESSION['token']);
		
		$ip = $this->getIP();
		 
		$redis = new Redis();
		$redis->connect('sessioncache.edmip9.0001.use1.cache.amazonaws.com');
		
		$val = $redis->get($token);
		
		error_log('close val: ' . $val);
		error_log('comparision: ' . ($user_id . ':' . $ip));
		
		if($val === ($user_id . ':' . $ip)){
			$redis->delete($token);
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function loadUserData(){
		
		$id = $this->getUserId();
		/*
		$sql =  'SELECT  u.id as id, '; 
        $sql .= 'ui.name as name, ';
        $sql .= 'u.email as email, ';
        $sql .= 'u.status as status, ';
        $sql .= 'ui.birth_dt as birth_dt, ';
        $sql .= 'ui.cellphone as cellphone, ';
		$sql .= 'ui.profile_picture as profile_picture ';
    	$sql .= 'FROM jooobz.users as u, jooobz.users_info as ui WHERE ';
		$sql .= "u.id='{$id}' "; 
    	$sql .= "&& ui.user_id='{$id}' ";
    	*/
    	
    	
    	$sql = "SELECT * FROM users WHERE id='{$id}' LIMIT 1"; 
    	
    	$jcdi = new JooobzCDI;
    	$db = new DBServices($jcdi);
		$conn = $db->connect();
		$query = $conn->query($sql);
		if (!$query){
			throw new Exception("Error with SQL: '{$sql}'");
		}
		$user_data = $query->fetch_array(MYSQLI_ASSOC);
		if($user_data == null)
			return FALSE;
			
		$this->setUserData($user_data);
		
		return TRUE;
		
	}
	
	public function setLogin($login){
		$this->login = $login;
	}
	
	public function getLogin(){
		return $this->login;
	}
	
	public function setPw($pw){
		$this->pw = $pw;	
	}
	
	private function getPw(){
		return $this->pw;
	}
	
	private function setToken($token){
		$this->token = $token;
	}
	
	public function getToken(){
		return $this->token;
	}
	
	private function setIP($ip){
		$this->ip = $ip;
	}
	
	private function getIP(){
		return $this->ip;
	}
	
	private function setCDI($cdi){
		$this->cdi = $cdi;
	}
	
	private function getCDI(){
		return $this->cdi;
	}
	
	private function setUserId($id){
		$this->user_id = $id;
	}
	
	public function getUserId(){
		return $this->user_id;
	}
	
	private function setSessionId($id){
		$this->_session_id = $id;
	}
	
	public function getSessionId(){
		return $this->_session_id;
	}
	
	public function setUserData($user_data){
		$this->user_data = $user_data;
	}
	
	public function getUserData(){
		return $this->user_data;
	}
	public function user($user_data=null){
		if($user_data != null){
			$this->user_data = $user_data;
			return;  
		}
		if(!$this->user_data){
			$this->loadUserData();
		}
		return $this->user_data;
	}
	
	public function isAuth(){
		if(!$this->authenticated){
			return FALSE;
		}
		return TRUE;
	}
}

