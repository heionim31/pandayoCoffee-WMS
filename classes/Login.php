<?php
	require_once '../config.php';
	require_once 'DBConnection.php';

	class Login extends DBConnection {
		private $settings;

		public function __construct(){
			global $_settings;
			$this->settings = $_settings;

			parent::__construct();
			ini_set('display_error', 1);
		}

		public function __destruct(){
			parent::__destruct();
		}

		public function index(){
			echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
		}

		public function login(){
			extract($_POST);
		
			$stmt = pg_prepare($this->conn, "user_query", "SELECT * from users where username = $1");
			$result = pg_execute($this->conn, "user_query", array($username));
		
			if(pg_num_rows($result) > 0){
				$user = pg_fetch_assoc($result);
				$password_hash = $user['password'];
		
				if(password_verify($password, $password_hash)){
					$userId = $user['id'];
					$logQuery = "SELECT * FROM hr_employee_logs WHERE employeeid = $userId AND log_date = CURRENT_DATE";
					$logResult = pg_query($this->conn, $logQuery);
		
					if(pg_num_rows($logResult) > 0) {
						$log = pg_fetch_assoc($logResult);
		
						// Check if the time_out column has a value
						if($log['time_out'] !== null) {
							return json_encode(array('status'=>'already_timed_out'));
						}
		
						foreach($user as $k => $v){
							if(!is_numeric($k) && $k != 'password'){
								$this->settings->set_userdata($k,$v);
							}
						}
						$this->settings->set_userdata('login_role', 'warehouse_manager');
						return json_encode(array('status'=>'success'));
					} else {
						return json_encode(array('status'=>'attendance_required'));
					}
				}
			} else {
				return json_encode(array('status'=>'no_account'));
			}
		
			return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from users where username = '$username'"));
		}
		
		
		
		
		
		
		
		
		
		
		public function logout(){
			if($this->settings->sess_des()){
				redirect('admin/login.php');
			}
		}
		
	}

	$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
	$auth = new Login();
	switch ($action) {
		case 'login':
			echo $auth->login();
			break;
		case 'logout':
			echo $auth->logout();
			break;
		default:
			echo $auth->index();
			break;
	}
?>