<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Images
 *
 * @package	tvshowfantest
 * @subpackage	controllers
 * @category	Controller
 * @author	Shamal Perera
 */
class Images extends Rest_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function post()
    {
      //Referred from http://code.tutsplus.com/tutorials/how-to-upload-files-with-codeigniter-and-ajax--net-21684
        $status = '';
        $result = array();
        $file_element_name = 'fileUpload';

        if ($status != 'error') {
            //configurations
            $config['upload_path'] = './assets/img/quiz/';
            $config['allowed_types'] = 'png';
            $config['max_size'] = 1024 * 8;
            $config['encrypt_name'] = true;

            $this->load->library('upload', $config);

            //upload file
            if (!$this->upload->do_upload('file')) {
                $result = array('status' => 'error', 'msg' => $this->upload->display_errors('', ''));
            } else {
                $data = $this->upload->data();
                $result = array('status' => 'success', 'filename' => $data['file_name']);
            }
        }
        echo json_encode($result);
    }

    public function get()
    {
        //get image name /api/images/1.png
        $args = $this->uri->uri_to_assoc(2);

        if ($args['images']) {
            $this->output->set_content_type('image/png');
            //get file
            $file = file_get_contents(base_url().'assets/img/quiz/'.$args['images']);
            $this->output->set_output($file);
        } else {
            show_404();
        }
    }

    public function put()
    {
        //put method is not available
      show_404();
    }

    public function delete()
    {
        //delete method is not available
      show_404();
    }
}
