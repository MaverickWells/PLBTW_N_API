<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class USER extends REST_Controller
{
	function login_get()
    {
    	// $this->load->model('db_model');
        $data = $this->db_model->CheckLoginData($this->get('username'), $this->get('password'));

        if($data)
            $this->response(array('status_code' => '200', 'result' => $data), 200); // 200 being the HTTP response code
        else
            $this->response(array('error' => 'Wrong Username or Password'), 200);
    }

    function login_post()
    {
    	// $this->load->model('db_model');
        $data = $this->db_model->CheckLoginData($this->post('username'), $this->post('password'));

        if($data)
            $this->response(array('status_code' => '200', 'result' => $data), 200); // 200 being the HTTP response code
        else
           $this->response(array('error' => 'Wrong Username or Password'), 200);
    }

    function user_get()
    {

    }

    function user_post()
    {
        $link = mysqli_connect("localhost", "root", "root", "plbtw");

        $data = array(
            'username' => mysqli_real_escape_string($link, $this->input->post('username')),
            'password' => md5(mysqli_real_escape_string($link, $this->input->post('password'))),
            'role' => strtolower(mysqli_real_escape_string($link, $this->input->post('roles')))
        );

        $result = $this->user_model->CreateUser($data);

        if($result > 0){
			if($data['role'] == "developer"){
				$md5 =  md5($data['username'].$data['password']);
				$sha1 = sha1($md5);
				$sha256 = hash('sha256', $sha1);
				$sha512 = hash('sha512', $sha256);

				$api_key_data = array(
					'iduser' => $this->db->insert_id(),
					'user_api_key' => $sha512
			 	);

				$api_key_result = $this->user_model->InsertAPIKEY($api_key_data);

				if($api_key_result > 0){
					$this->response(array(
		                'result' => 'Successful Insertion',
		                'affected_rows' => $api_key_result,
						'api_key' => $sha512,
		                'status_code' => 200
		                ), REST_Controller::HTTP_OK
		            );
				}
				else{
		            $this->response(array(
		                'result' => 'Failed Insertion',
		                'affected_rows' => $result,
		                'status_code' => 200
		                ), REST_Controller::HTTP_OK
		            );
		        }
			}

            $this->response(array(
                'result' => 'Successful Insertion',
                'affected_rows' => $result,
                'status_code' => 200
                ), REST_Controller::HTTP_OK
            );
        }
        else{
            $this->response(array(
                'result' => 'Failed Insertion',
                'affected_rows' => $result,
                'status_code' => 200
                ), REST_Controller::HTTP_OK
            );
        }
    }

    function user_put($iduser){
        $link = mysqli_connect("localhost", "root", "root", "plbtw");

        parse_str(file_get_contents("php://input"),$put_vars);

        $data = array(
            'username' => $put_vars['username']
        );

        if(!empty($put_vars['password'])){
            $data['password'] = md5(mysqli_real_escape_string($link, $put_vars['username']));
        }

        if(!empty($put_vars['roles'])){
            $data['role'] = mysqli_real_escape_string($link, $put_vars['roles']);


        }

        $result = $this->user_model->UpdateUser($data, $iduser);

        if($result > 0){
            $this->response(array(
                'result' => 'Successful Update',
                'affected_rows' => $result,
                'status_code' => 200
                ), REST_Controller::HTTP_OK
            );
        }
        else{
            $this->response(array(
                'result' => 'Failed Update',
                'affected_rows' => $result,
                'status_code' => 200
                ), REST_Controller::HTTP_OK
            );
        }
    }

    function user_delete($iduser){
        $result = $this->user_model->DeleteUser($iduser);

        if($result > 0){
            $this->response(array(
                'result' => 'Successful Delete',
                'affected_rows' => $result,
                'status_code' => 200
                ), REST_Controller::HTTP_OK
            );
        }
        else{
            $this->response(array(
                'result' => 'Failed Delete',
                'affected_rows' => $result,
                'status_code' => 200
                ), REST_Controller::HTTP_OK
            );
        }
    }
}
