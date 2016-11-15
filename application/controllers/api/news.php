<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class NEWS extends REST_Controller
{
	// function login_get()
    // {
    // 	// $this->load->model('db_model');
    //     $data = $this->db_model->CheckLoginData($this->get('username'), $this->get('password'));
	//
    //     if($data)
    //         $this->response(array('status_code' => '200', 'result' => $data), 200); // 200 being the HTTP response code
    //     else
    //         $this->response(array('error' => 'Wrong Username or Password'), 200);
    // }
	//
    // function login_post()
    // {
    // 	// $this->load->model('db_model');
    //     $data = $this->db_model->CheckLoginData($this->post('username'), $this->post('password'));
	//
    //     if($data)
    //         $this->response(array('status_code' => '200', 'result' => $data), 200); // 200 being the HTTP response code
    //     else
    //        $this->response(array('error' => 'Wrong Username or Password'), 200);
    // }

    function all_news_get()
    {
	        $data = $this->news_model->GetAllNews();

	        if($data)
	            $this->response(array('status_code' => '200', 'result' => $data), 200); // 200 being the HTTP response code
	        else
	            $this->response(array('error' => 'Wrong Username or Password'), 200);
    }

	function news_get($id)
    {
	        $data = $this->news_model->GetNews($id);

	        if($data)
	            $this->response(array('status_code' => '200', 'result' => $data), 200); // 200 being the HTTP response code
	        else if(empty($id))
	            $this->response(array('error' => 'No news ID provided'), 404);
			else {
				$this->response(array('error' => 'Incorrect ID or Other Error'), 200);
			}
    }

    function news_post()
    {
        $link = mysqli_connect("localhost", "root", "root", "plbtw");

        $data = array(
			'title' => mysqli_real_escape_string($link, $this->input->post('title')),
			'content' => mysqli_real_escape_string($link, $this->input->post('content')),
			'category' => mysqli_real_escape_string($link, $this->input->post('category')),
			'sub_category' => mysqli_real_escape_string($link, $this->input->post('sub_category')),
			'location' => mysqli_real_escape_string($link, $this->input->post('location')),
			'news_web' => mysqli_real_escape_string($link, $this->input->post('news_web')),
			'news_url' => mysqli_real_escape_string($link, $this->input->post('news_url')),
			'keyword' => mysqli_real_escape_string($link, $this->input->post('keyword')),
        );

        $result = $this->news_model->CreateNews($data);

        if($result > 0){
            $this->response(array(
                'result' => 'Successful Insertion',
                'affected_rows' => $result,
				'news_id' => $this->db->insert_id(),
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

    function news_put($idnews){
        $link = mysqli_connect("localhost", "root", "root", "plbtw");

        parse_str(file_get_contents("php://input"),$put_vars);

		$data = array(
			'title' => mysqli_real_escape_string($link, $put_vars['title']),
			'content' => mysqli_real_escape_string($link, $put_vars['content']),
			'category' => mysqli_real_escape_string($link, $put_vars['category']),
			'sub_category' => mysqli_real_escape_string($link, $put_vars['sub_category']),
			'location' => mysqli_real_escape_string($link, $put_vars['location']),
			'news_web' => mysqli_real_escape_string($link, $put_vars['news_web']),
			'news_url' => mysqli_real_escape_string($link, $put_vars['news_url']),
			'keyword' => mysqli_real_escape_string($link, $put_vars['keyword']),
        );

        $result = $this->news_model->UpdateNews($data, $idnews);

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

    function news_delete($idnews){
        $result = $this->news_model->DeleteNews($idnews);

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
