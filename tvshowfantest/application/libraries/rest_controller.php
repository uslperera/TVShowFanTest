<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Rest.
 *
 * @category	Controller
 *
 * @author	Shamal Perera
 */
abstract class Rest_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->output->set_content_type('application/json');
        $this->load->model('admin_model');
    }

    public function _remap($method)
    {
        $actionType = $this->input->server('REQUEST_METHOD');
        $headers = $this->input->request_headers();
        $controller_name = $this->uri->segment(2);
        //check if request is not for session controller post or images controller get
        if (!(($actionType == 'POST' && $controller_name == 'session') || ($actionType == 'GET' && $controller_name == 'images'))) {
            //get session key
            $sessionkey = $headers['Token'];
            //validate session
            $is_valid = $this->admin_model->is_valid_session($sessionkey);
            if (!$is_valid) {
                $this->output->set_status_header('401');

                return;
            }
        }
        if (method_exists($this, $actionType)) {
            call_user_func(array($this, $actionType));
        } else {
            show_404();
        }
    }

    abstract public function get();
    abstract public function post();
    abstract public function put();
    abstract public function delete();
}
