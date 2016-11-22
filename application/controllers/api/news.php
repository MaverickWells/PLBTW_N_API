<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class NEWS extends REST_Controller
{
    function all_news_post()
    {
        if($this->api_model->CheckAPIKEY($this->post('api_key')) > 0){
            $data = $this->news_model->GetAllNews();

	        if($data)
            {
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'all news',
                    'http_request_method' => 'POST',
                    'http_code_response' => 200,
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array('result' => $data), 200); // 200 being the HTTP response code
            }
	        else{
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'all news',
                    'http_request_method' => 'POST',
                    'http_code_response' => 404,
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array('result' => 'No News Content'), 404);
            }
		}
		else {
            $api_data = array(
                'api_key' => $this->post('api_key'),
                'function_request' => 'all news',
                'http_request_method' => 'POST',
                'http_code_response' => 401,
            );

            $this->api_model->CreateLog($api_data);

            $this->response(array('result' => 'No API KEY Provided'), 401);
		}
    }

	function news_id_post($id)
    {
        if($this->api_model->CheckAPIKEY($this->post('api_key')) > 0){
            $data = $this->news_model->GetNews($id);

	        if($data)
            {
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'news by id',
                    'http_request_method' => 'POST',
                    'http_code_response' => 200,
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array('result' => $data), 200); // 200 being the HTTP response code
            }
	        else if(empty($id)){
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'news by id',
                    'http_request_method' => 'POST',
                    'http_code_response' => 404,
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array('error' => 'No news ID provided'), 404);
            }
			else {
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'news by id',
                    'http_request_method' => 'POST',
                    'http_code_response' => 404,
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array('error' => 'Incorrect ID or Other Error'), 404);
			}
		}
		else {
            $api_data = array(
                'api_key' => $this->post('api_key'),
                'function_request' => 'news by id',
                'http_request_method' => 'POST',
                'http_code_response' => 401,
            );

            $this->api_model->CreateLog($api_data);

            $this->response(array('result' => 'No API KEY Provided'), 401);
		}
    }

    function news_location_post($location)
    {
        if($this->api_model->CheckAPIKEY($this->post('api_key')) > 0){
	        $data = $this->news_model->GetLocationNews($location);

	        if($data)
            {
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'news by location',
                    'http_request_method' => 'POST',
                    'http_code_response' => 200,
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array('result' => $data), 200); // 200 being the HTTP response code
            }
	        else if(empty($location)){
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'news by location',
                    'http_request_method' => 'POST',
                    'http_code_response' => 404,
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array('error' => 'No news location provided'), 404);
            }
			else {
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'news by location',
                    'http_request_method' => 'POST',
                    'http_code_response' => 404,
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array('error' => 'Incorrect ID or Other Error'), 404);
			}
		}
		else {
            $api_data = array(
                'api_key' => $this->post('api_key'),
                'function_request' => 'news by location',
                'http_request_method' => 'POST',
                'http_code_response' => 401,
            );

            $this->api_model->CreateLog($api_data);

            $this->response(array('result' => 'No API KEY Provided'), 401);
		}
    }

    function news_post()
    {
        if($this->api_model->CheckAPIKEY($this->post('api_key')) > 0){
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
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'news',
                    'http_request_method' => 'POST',
                    'http_code_response' => 200,
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array(
                    'result' => 'Successful Insertion',
                    'affected_rows' => $result,
    				'news_id' => $this->db->insert_id(),
                    ), REST_Controller::HTTP_OK
                );
            }
            else{
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'news',
                    'http_request_method' => 'POST',
                    'http_code_response' => 403,
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array(
                    'result' => 'Failed Insertion',
                    'affected_rows' => $result,
                ), 403);
            }
		}
		else {
            $api_data = array(
                'api_key' => $this->post('api_key'),
                'function_request' => 'news',
                'http_request_method' => 'POST',
                'http_code_response' => 401,
            );

            $this->api_model->CreateLog($api_data);

			$this->response(array('result' => 'No API KEY Provided'), 401);
		}
    }

    function news_put($idnews){
        if($this->api_model->CheckAPIKEY($this->post('api_key')) > 0){
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
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'news',
                    'http_request_method' => 'PUT',
                    'http_code_response' => 200,
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
                    'function_request' => 'news',
                    'http_request_method' => 'PUT',
                    'http_code_response' => 403,
                );

                $this->api_model->CreateLog($api_data);

                $this->response(array(
                    'result' => 'Failed Update',
                    'affected_rows' => $result,
                ), 403);
            }
		}
		else {
            $api_data = array(
                'api_key' => $this->post('api_key'),
                'function_request' => 'news',
                'http_request_method' => 'PUT',
                'http_code_response' => 401,
            );

            $this->api_model->CreateLog($api_data);

			$this->response(array('result' => 'No API KEY Provided'), 401);
		}
    }

    function news_delete($idnews){
        if($this->api_model->CheckAPIKEY($this->post('api_key')) > 0){
            $result = $this->news_model->DeleteNews($idnews);

            if($result > 0){
                $api_data = array(
                    'api_key' => $this->post('api_key'),
                    'function_request' => 'news',
                    'http_request_method' => 'DELETE',
                    'http_code_response' => 200,
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
                    'function_request' => 'news',
                    'http_request_method' => 'DELETE',
                    'http_code_response' => 404,
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
                'api_key' => $this->post('api_key'),
                'function_request' => 'news',
                'http_request_method' => 'DELETE',
                'http_code_response' => 401,
            );

            $this->api_model->CreateLog($api_data);

			$this->response(array('result' => 'No API KEY Provided'), 401);
		}
    }
}
