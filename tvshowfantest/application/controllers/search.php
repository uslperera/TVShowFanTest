<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Search
 *
 * @package	tvshowfantest
 * @subpackage	controllers
 * @category	Controller
 * @author	Shamal Perera
 */
class Search extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // load breadcrumbs
        $this->load->library('breadcrumbs');
    }

    public function index() {
        $dataBag = array();
        
        //get search query
        $quiz_title = $this->input->post('searchQuery');

        //get quizzes by title
        $this->load->model('quiz_model');
        $dataBag['quizzes'] = $this->quiz_model->get_quizzes_by_title($quiz_title);

        //get tv shows
        $this->load->model('tvshow_model');
        $dataBag['tvshows'] = $this->tvshow_model->get_tvshows();

        //add breadcrumbs
        $this->breadcrumbs->push('TVShowFanTest', '/');
        $this->breadcrumbs->push('Search > "' . $quiz_title . '"', base_url() . '/index.php/Search/index/');

        $data = array('view' => 'search', 'dataBag' => $dataBag);

        $this->load->view('template', $data);
    }
}
/* End of file search.php */
/* Location: ./application/controllers/search.php */