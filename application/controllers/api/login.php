<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class LOGIN extends REST_Controller
{
	function user_get()
    {
    	// $this->load->model('db_model');
        $data = $this->db_model->CheckLoginData($this->get('username'), $this->get('password'));

        if($data)
            $this->response(array('status_code' => '200', 'result' => $data), 200); // 200 being the HTTP response code
        else
            $this->response(array('error' => 'Wrong Username or Password'), 200);
    }

    // function user_post()
    // {
    // 	// $this->load->model('db_model');
    //     $data = $this->db_model->CheckLoginData($this->get('username'), $this->get('password'));

    //     //if($data)
    //         $this->response(array('status' => $this->input->post('username')), 200); // 200 being the HTTP response code
    //     //else
    //        // $this->response(array('error' => 'Wrong Username or Password'), 200);
    // }
}
