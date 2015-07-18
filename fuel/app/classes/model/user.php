<?php
namespace Model;

use Fuel\Core\Log;
use Fuel\Core\Validation;
use Model;
use Fuel\Core\DB;
use Auth\Auth;
use Fuel\Core\Fieldset;
use Fuel\Core\Database_Connection;
class User extends \ORM\Model {

	//create table name for user
	protected static $_table_name = 'user';

	//create properties for user
	protected  static  $_properties = array('id', 'username', 'lastname', 'firstname', 'email', 'created_at', 'modified_at', 'group');
	public static function validate($input) {
	
		//check if Form validation created, use instance for retrieve it not use forge
		$val = \Validation::active();
		if ($val) {
			$val = \Validation::forge();
		} else {
			$val = \Validation::instance();
		}
		//$val = Validation::forge();
	
		$val->add_field('username', 'Username để đăng nhập', 'required|min_length[5]|max_length[50]|valid_string[alpha,numeric]');
		$val->add_field('password', 'Password để đăng nhập', 'required|min_length[5]|max_length[50]|valid_string[alpha,numeric]');
		$val->add_field('email', 'Email để đăng nhập', 'required|valid_email');
		$val->add_field('phone', 'Số điện thoại', 'required|min_length[5]|max_length[50]|valid_string[numeric]');
		$val->add_field('u_name', 'Tên ứng viên', 'required|min_length[5]|max_length[50]');
		$val->add_field('u_email', 'Email liên lạc', 'required|valid_email');
		$val->add_field('u_phone', 'Số điện thoại liên lạc', 'required|min_length[5]|max_length[50]|valid_string[numeric]');
		// set custom message for rules
		$val->add_field('u_title', 'Tiêu đề hồ sơ', 'required|min_length[5]|max_length[200]');
		$val->add_field('u_address', 'Địa chỉ', 'required|min_length[5]|max_length[200]');
		$val->set_Message('required', 'Bạn phải nhập :label');
		$val->set_Message('min_length', 'Nhập ít nhất 5 kí tự');
		$val->set_Message('max_length', 'Nhập quá số kí tự cho phép');
		$val->set_Message('valid_email', 'Định dạng không đúng');
		$val->set_Message('valid_string', 'Dữ liệu không đúng yêu cầu');
	
		//print_r($data);
		// create message array
		$_error = array();
		if (! $val->run($input)) {
	
			foreach ($val->error() as $field => $error) {
				// add error message to array for return
				$_error[$field] = array(
						'message' => $error->get_Message()
						
				);
			}
			Validation::_empty($val);
	
			//var_dump($_error);die;
			// return error message
	
			return $_error ;
		} else {
			// return 1 for valid all data
			return true;
		}
	}
   /*
	* method validation for check information input to database
	* @param input data  recieve from post method
	* @return array error of input data
	*/
	public static function validate_user($data) {
		//create input field from data
		$input = array(
				'username' => $data['username'],
				'password' => $data['password'],
				'phone' => $data['phone'],
				
				'u_name' => $data['u_name'],
				'u_email' => $data['u_email'],
				'u_phone' => $data['u_phone'],
				'u_title' => $data['u_title'],
				'u_address' => $data['u_address'],
		);
	
		$result = self::validate($input);
		if (true !== $result) {
			return $result;
		} else {
			return true;
		}
	}
	public static function add_user($data) {
		// try catch for insert
		try {
			$data['password'] = Auth::instance()->hash_password($data['password']);
			// insert query
			$entry = DB::insert('u_user')->columns(array(
					'username',
					'password','password2',
					'email',
					'phone',
					//'lastname',
					//'firstname',
					'created_at',
					'modified_at',
					'group',
					'u_name','u_email','u_phone','u_address','u_title','u_des','u_career','u_location','u_exp','u_image','u_cv','u_type','status'
			))->values(array(
					$data['username'],
					$data['password'],$data['password2'],
					$data['email'],$data['phone'],
					//$data['lastname'],
					//$data['firstname'],					
					$data['created_at'],
					$data['modified_at'],
					$data['group'],
					$data['u_name'],$data['u_email'],$data['u_phone'],$data['u_address'],$data['u_title'],
					$data['u_des'],$data['u_career'],$data['u_location'],$data['u_exp'],$data['u_image'],$data['u_cv'],$data['u_type'],
					_USER_STATUS_NOT_
			) );
				
				
				
				
			$result = $entry->execute();
				
			// Return id of username inserted
			return $result[0];
				
				
		} catch (\Exception $ex) {
			// write error to log
			Log::error($ex->getMessage());
				
			return $ex->getMessage();
		}
	}
	/**
	 * function login
	 * @param unknown $username
	 * @param unknown $password
	 * @return unknown|boolean
	 */
	public static function login($username, $password) {
	
		$rs = self::create_token($username, $password);
		//login success
		if( $rs != 0) {
			return $rs;
		} else {
			return false;
		}
	
	}
	/*
	 * method use to create token for user @use Auth package for create token token have format sha1(\Config::get('simpleauth.login_hash_salt').$this->user['username'].$last_login)
	* @return array data user info
	* called after login or create user success.
	*/
	public static function create_token($username, $password) {
		// use auth login to creat token and insert db
		//throw new \Exception('Test transaction');
		$rs = 0;
		try {
			$password = Auth::instance()->hash_password($password);
			//select comments
			$query = DB::query("SELECT * FROM u_user WHERE username = :username AND password= :password ");
			$query->bind('username', $username);
			$query->bind('password', $password);
			$rs = $query->execute()->as_array();
			
			if (count($rs) > 0) {
				$data = array();				
				//create token
// 				$data ['last_login'] = time();				
				$data['token'] = sha1(time().$rs[0]['id'].$rs[0]['password']);
// 				$data['group'] = Auth::get('group');
// 				return $data;
				return $data;
			} else {
		
				return false;
			}
		} catch (\Exception $ex) {
			Log::error($ex->getMessage());
			return $ex->getMessage();
		}
		
	}
}