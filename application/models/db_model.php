<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DB_MODEL extends CI_Model {
	public function CheckLoginData($username, $password)
	{
		# code...
		$link = mysqli_connect("localhost", "root", "root", "plbtw");
		$usr = mysqli_real_escape_string($link, $username);
		$pwd = md5(mysqli_real_escape_string($link, $password));

		$login_check = $this->db->get_where('user', array('username' => $usr, 'password' => $pwd));
		if($login_check->num_rows() > 0){
			$rows = $login_check->row();			
			if($usr == $rows->username && $pwd == $rows->password){
				return true;
			}
			else{
				return false;
			}
		}
	}
}
