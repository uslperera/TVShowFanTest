<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Session
 *
 * @package	tvshowfantest
 * @subpackage	controllers
 * @category	Controller
 * @author	Shamal Perera
 */
class Session extends Rest_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
    }

    public function post()
    {
        $response = array('status' => '', 'msg' => '');
        $data = json_decode(file_get_contents('php://input'));
        //call create session
        $status = $this->admin_model->create_session($data->username, $data->password);
        //if status is not null
        if ($status) {
            $response['status'] = 'success';
            $response['msg'] = $status['session_key'];
        } else {
            $response['status'] = 'failure';
            $response['msg'] = 'Unauthorized access';
        }
        $this->output->set_output(json_encode($response));
    }

    public function get()
    {
        $headers = $this->input->request_headers();
        //extract session key
        $sessionkey = $headers['Token'];
        //pass session key
        $status = $this->admin_model->get_session($sessionkey);
        //if status is not null
        if ($status) {
            $response['status'] = 'success';
            $response['msg'] = $status['username'];
        } else {
            $response['status'] = 'failure';
            $response['msg'] = 'Unauthorized access';
        }
        $this->output->set_output(json_encode($response));
    }

    public function put()
    {
        //put method is not available
        show_404();
    }

    public function delete()
    {
        $response = array('status' => '', 'msg' => '');
        //extract session key
        $headers = $this->input->request_headers();
        $sessionkey = $headers['Token'];
        //destroy session
        $this->admin_model->destroy_session($sessionkey);
        $response['status'] = 'success';

        $this->output->set_output(json_encode($response));
    }
}
