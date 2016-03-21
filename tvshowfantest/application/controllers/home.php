<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Home
 *
 * @package	tvshowfantest
 * @subpackage	controllers
 * @category	Controller
 * @author	Shamal Perera
 */
class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $dataBag = array();

        //get quizzes
        $this->load->model('quiz_model');
        $dataBag['quizzes'] = $this->quiz_model->get_partial_quizzes();

        //get tv shows
        $this->load->model('tvshow_model');
        $dataBag['tvshows'] = $this->tvshow_model->get_tvshows();

        $data = array('view' => 'home', 'dataBag' => $dataBag);

        $this->load->view('template', $data);
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
