<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * TVShows
 *
 * @package	tvshowfantest
 * @subpackage	controllers
 * @category	Controller
 * @author	Shamal Perera
 */
class TVShows extends Rest_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tvshow_model');
    }

    public function get()
    {
        $data = null;
        $args = $this->uri->uri_to_assoc(2);

        if ($args['tvshows']) {
            //if tvshow id is specified
          $data = $this->tvshow_model->get_tvshow($args['tvshows']);
        } else {
            //if tvshow id is not specified
          $data = $this->tvshow_model->_get_tvshows();
        }
        $this->output->set_output(json_encode($data));
    }

    public function post()
    {
        $data = json_decode(file_get_contents('php://input'));
        //validate data
        $error = $this->validate($data);
        if ($error) {
            $response = array('status' => 'error', 'msg' => $error);
            $this->output->set_output(json_encode($response));
        } else {
            //add new tvshow
            $tvshow_id = $this->tvshow_model->add_tvshow($data);
            //return newly added tvshow
            $data = $this->tvshow_model->get_tvshow($tvshow_id);
            $this->output->set_output(json_encode($data));
        }
    }

    public function put()
    {
        $data = json_decode(file_get_contents('php://input'));
        //validate data
        $error = $this->validate($data);
        if ($error) {
            $response = array('status' => 'error', 'msg' => $error);
            $this->output->set_output(json_encode($response));
        } else {
            //update tvshow
            $this->tvshow_model->set_tvshow($data);
            //return updated tvshow
            $data = $this->tvshow_model->get_tvshow($data->id);
            $this->output->set_output(json_encode($data));
        }
    }

    public function delete()
    {
        //get tvshow id url api/tvshows/1
        $tvshow_id = $this->uri->segment(3);
        $this->tvshow_model->delete_tvshow($tvshow_id);

        $repsonse = array('status' => 'success');
        $this->output->set_output(json_encode($repsonse));
    }

    private function validate($tvshow)
    {
        //empty checks
        if ($tvshow->name == '') {
            return 'TV Show name cannot be empty';
        }
        if ($tvshow->description == '') {
            return 'TV Show description cannot be empty';
        }
    }
}
