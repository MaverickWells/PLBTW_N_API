<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class USER extends REST_Controller
{
	// function login_get()
    // {
    // 	// $this->load->model('db_model');
    //     $data = $this->db_model->CheckLoginData($this->get('username'), $this->get('password'));
	//
    //     if($data)
    //         $this->response(array('result' => 'True'), 200); // 200 being the HTTP response code
    //     else
    //         $this->response(array('result' => 'Wrong Username or Password'), 200);
    // }

    function login_post()
    {
    	// $this->load->model('db_model');
		if($this->api_model->CheckAPIKEY($this->post('api_key')) > 0){
            if(empty($this->post('username')))
            {
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'login',
                    'http_request_method' => 'POST',
                    'http_code_response' => 400,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                );

                $this->api_model->CreateLog($api_data);

	            $this->response(array('result' => 'No Username'), 400);
            }
            else if(empty($this->post('password')))
            {
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'login',
                    'http_request_method' => 'POST',
                    'http_code_response' => 400,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                );

                $this->api_model->CreateLog($api_data);

	            $this->response(array('result' => 'No Password'), 400);
            }

			$data = $this->db_model->CheckLoginData($this->post('username'), $this->post('password'));

			if($data){
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'login',
                    'http_request_method' => 'POST',
                    'http_code_response' => 200,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                );

                $this->api_model->CreateLog($api_data);

                $user_id = $this->user_model->GetUserID($this->post('username'));
                $user_pref = $this->user_model->GetUserPref($user_id['iduser']);

                $this->response(array(
                    'result' => 'True',
                    'category' => $user_pref['news_category_pref'],
                    'sub_category' => $user_pref['news_sub_category_pref'],
                ), 200); // 200 being the HTTP response code
            }
	        else{
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'login',
                    'http_request_method' => 'POST',
                    'http_code_response' => 401,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                );

                $this->api_model->CreateLog($api_data);

	            $this->response(array('result' => 'Wrong Username or Password'), 401);
            }
		}
		else {
            $api_data = array(
                'api_key' => 'No API KEY',
                'function_request' => 'login',
                'http_request_method' => 'POST',
                'http_code_response' => 401,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s')
            );

            $this->api_model->CreateLog($api_data);

            $this->response(array('result' => 'No API KEY Provided'), 401);
		}
    }

    function user_get()
    {

    }

    function user_post()
    {
		if($this->api_model->CheckAPIKEY($this->post('api_key')) > 0){
			$link = mysqli_connect("localhost", "root", "root", "plbtw");

	        $data = array(
	            'username' => mysqli_real_escape_string($link, $this->input->post('username')),
	            'password' => md5(mysqli_real_escape_string($link, $this->input->post('password'))),
	            'role' => strtolower(mysqli_real_escape_string($link, $this->input->post('roles')))
	        );

			$user_check = $this->user_model->CheckUsername($data['username']);

			if($user_check > 0){
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'user',
                    'http_request_method' => 'POST',
                    'http_code_response' => 403,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array(
					'result' => 'Username Is Registered',
					'affected_rows' => $user_check,
                ), 403);
			}
			else{
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
                            $api_data = array(
                                'api_key' => $this->post('api_key'),
                                'function_request' => 'user',
                                'http_request_method' => 'POST',
                                'http_code_response' => 200,
                                'date' => date('Y-m-d'),
                                'time' => date('H:i:s')
                            );

                            $this->api_model->CreateLog($api_data);

                            $this->response(array(
				                'result' => 'Successful Insertion',
				                'affected_rows' => $api_key_result,
								'api_key' => $sha512,
				                ), REST_Controller::HTTP_OK
				            );
						}
						else{
                            $api_data = array(
                                'api_key' => $this->post('api_key'),
                                'function_request' => 'user',
                                'http_request_method' => 'POST',
                                'http_code_response' => 403,
                                'date' => date('Y-m-d'),
                                'time' => date('H:i:s')
                            );

                            $this->api_model->CreateLog($api_data);

                            $this->response(array(
				                'result' => 'Failed Insertion',
				                'affected_rows' => $api_key_result,
                            ), 403);
				        }
					}
                    elseif ($data['role'] == "user") {
                        $user_pref = array(
                            'iduser' => $this->db->insert_id(),
                            'news_category_pref' => $this->post('category_pref'),
                            'news_sub_category_pref' => $this->post('sub_category_pref'),
                        );

                        $user_pref_result = $this->user_model->CreateUserPref($user_pref);

                        if($user_pref_result > 0){
                            $api_data = array(
                                'api_key' => $this->post('api_key'),
                                'function_request' => 'user',
                                'http_request_method' => 'POST',
                                'http_code_response' => 200,
                                'date' => date('Y-m-d'),
                                'time' => date('H:i:s')
                            );

                            $this->api_model->CreateLog($api_data);

                            $this->response(array(
				                'result' => 'Successful Insertion',
				                'affected_rows' => $user_pref_result,
				                ), REST_Controller::HTTP_OK
				            );
                        }
                        else {
                            $api_data = array(
                                'api_key' => $this->post('api_key'),
                                'function_request' => 'user',
                                'http_request_method' => 'POST',
                                'http_code_response' => 403,
                                'date' => date('Y-m-d'),
                                'time' => date('H:i:s')
                            );

                            $this->api_model->CreateLog($api_data);

                            $this->response(array(
				                'result' => 'Failed Insertion',
				                'affected_rows' => $user_pref_result,
                            ), 403);
                        }
                    }

                    $api_data = array(
                        'api_key' => $this->post('api_key'),
                        'function_request' => 'user',
                        'http_request_method' => 'POST',
                        'http_code_response' => 200,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s')
                    );

                    $this->api_model->CreateLog($api_data);

                    $this->response(array(
		                'result' => 'Successful Insertion',
		                'affected_rows' => $result,
		                ), REST_Controller::HTTP_OK
		            );
		        }
		        else{
                    $api_data = array(
                        'api_key' => $this->post('api_key'),
                        'function_request' => 'user',
                        'http_request_method' => 'POST',
                        'http_code_response' => 403,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s')
                    );

                    $this->api_model->CreateLog($api_data);

                    $this->response(array(
		                'result' => 'Failed Insertion',
		                'affected_rows' => $result,
                    ), 403);
		        }
			}
		}
		else {
            $api_data = array(
                'api_key' => 'No API KEY',
                'function_request' => 'user',
                'http_request_method' => 'POST',
                'http_code_response' => 401,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s')
            );

            $this->api_model->CreateLog($api_data);

            $this->response(array('result' => 'No API KEY Provided'), 401);
		}
    }

    function user_put($iduser){
		if($this->api_model->CheckAPIKEY($this->post('api_key')) > 0){
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
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'user',
                    'http_request_method' => 'PUT',
                    'http_code_response' => 200,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array(
	                'result' => 'Successful Update',
	                'affected_rows' => $result,
	                ), REST_Controller::HTTP_OK
	            );
	        }
	        else{
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'user',
                    'http_request_method' => 'PUT',
                    'http_code_response' => 400,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array(
	                'result' => 'Failed Update',
	                'affected_rows' => $result,
                ), 400);
	        }
		}
		else {
            $api_data = array(
                'api_key' => 'No API KEY',
                'function_request' => 'user',
                'http_request_method' => 'PUT',
                'http_code_response' => 401,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s')
            );

            $this->api_model->CreateLog($api_data);

            $this->response(array('result' => 'No API KEY Provided'), 401);
		}
    }

    function user_delete($iduser){
		if($this->api_model->CheckAPIKEY($this->post('api_key')) > 0){
			$result = $this->user_model->DeleteUser($iduser);

	        if($result > 0){
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'user',
                    'http_request_method' => 'DELETE',
                    'http_code_response' => 200,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array(
	                'result' => 'Successful Delete',
	                'affected_rows' => $result,
	                ), REST_Controller::HTTP_OK
	            );
	        }
	        else{
	            $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'user',
                    'http_request_method' => 'DELETE',
                    'http_code_response' => 404,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s')
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array(
	                'result' => 'Failed Delete',
	                'affected_rows' => $result,
                ), 404);
	        }
		}
		else {
            $api_data = array(
                'api_key' => 'No API KEY',
                'function_request' => 'user',
                'http_request_method' => 'DELETE',
                'http_code_response' => 401,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s')
            );

            $this->api_model->CreateLog($api_data);

            $this->response(array('result' => 'No API KEY Provided'), 401);
		}
    }
}
