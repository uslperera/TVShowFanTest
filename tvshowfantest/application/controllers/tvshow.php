<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * TVShow
 *
 * @package	tvshowfantest
 * @subpackage	controllers
 * @category	Controller
 * @author	Shamal Perera
 */
class TVShow extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // load breadcrumbs
        $this->load->library('breadcrumbs');
    }

    public function index($tvshow_id) {
        $dataBag = array();
        
        //get quizzes by tv show
        $this->load->model('quiz_model');
        $dataBag['quizzes'] = $this->quiz_model->get_quizzes_by_tvshow($tvshow_id);
        
        //get tv shows
        $this->load->model('tvshow_model');
        $dataBag['tvshows'] = $this->tvshow_model->get_tvshows();

        // add breadcrumbs
        $this->breadcrumbs->push('TVShowFanTest', '/');
        $this->breadcrumbs->push('TVShows', base_url() . '/index.php/TVShow/index/' . $tvshow_id);

        $data = array('view' => 'tvshow', 'dataBag' => $dataBag);

        $this->load->view('template', $data);
    }
}
/* End of file tvshow.php */
/* Location: ./application/controllers/tvshow.php */