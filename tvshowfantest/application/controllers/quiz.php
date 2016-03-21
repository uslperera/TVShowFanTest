<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Quiz
 *
 * @package	tvshowfantest
 * @subpackage	controllers
 * @category	Controller
 * @author	Shamal Perera
 */
class Quiz extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // load breadcrumbs
        $this->load->library('breadcrumbs');
        //load social network share
        $this->load->helper('share');
        //load session
        $this->load->library('session');
        //load quiz model
        $this->load->model('quiz_model');
    }

    public function index($quiz_id) {
        //get details of the quiz
        $dataBag = $this->quiz_model->get_quiz_details($quiz_id);

        $dataBag['quiz_id'] = $quiz_id;
        //get quizzes in a random order
        $dataBag['quizzes'] = $this->quiz_model->get_quizzes_random();

        // add breadcrumbs
        $this->breadcrumbs->push('TVShowFanTest', '/');
        $this->breadcrumbs->push('Quiz', base_url() . '/index.php/Quiz/search/' . $quiz_id);

        $data = array('view' => 'quiz_start', 'dataBag' => $dataBag);

        $this->load->view('template', $data);
    }

    public function start($quiz_id) {
        $this->session->unset_userdata('quiz_answers');

        //get details of the quiz
        $dataBag = $this->quiz_model->get_quiz_details($quiz_id);
        //get questions of the quiz
        $dataBag['questions'] = $this->quiz_model->get_questions($quiz_id);

        $dataBag['quiz_id'] = $quiz_id;

        // add breadcrumbs
        $this->breadcrumbs->push('TVShowFanTest', '/');
        $this->breadcrumbs->push('Quiz', base_url() . '/index.php/Quiz/index/' . $quiz_id);

        $data = array('view' => 'quiz', 'dataBag' => $dataBag);

        $this->load->view('template', $data);
    }

    public function finish() {
        if($this->session->userdata('quiz_answers')){
          redirect('/home/index');
        }
        //get answers selected by the user
        $answers = $this->input->post('answers');
        //if no answers provided direct to home page
        if ($answers == false) {
            redirect('/home/index');
        }
        //set answers in the session
        $this->session->set_userdata('quiz_answers', $answers);
        //get quiz id
        $quiz_id = $this->input->post('quiz');
        //get details of the quiz
        $dataBag = $this->quiz_model->get_quiz_details($quiz_id);
        //get result of the quiz
        $results = $this->quiz_model->get_result($quiz_id, $answers);

        //set score
        $dataBag['score'] = $results['score'];
        //set average score
        $dataBag['average_score'] = $results['avg_score'];
        //set message
        $dataBag['message'] = $results['message'];
        //set quiz id
        $dataBag['quiz_id'] = $quiz_id;
        //get quizzes in a random order
        $dataBag['quizzes'] = $this->quiz_model->get_quizzes_random();

        //add breadcrumbs
        $this->breadcrumbs->push('TVShowFanTest', '/');
        $this->breadcrumbs->push('Results', base_url() . '/index.php/Quiz/finish/');

        $data = array('view' => 'results', 'dataBag' => $dataBag);

        $this->load->view('template', $data);
    }

    public function review($quiz_id) {
        //load answers in the session
        $quiz_answers = $this->session->userdata('quiz_answers');
        //get details of the quiz
        $dataBag = $this->quiz_model->get_quiz_details($quiz_id);
        //get questions with correct answers of the quiz
        $dataBag['questions'] = $this->quiz_model->get_questions_with_correct_choices($quiz_id);
        //set quiz id
        $dataBag['quiz_id'] = $quiz_id;
        //set answers selected by user
        $dataBag['selected_choices'] = $quiz_answers;

        // add breadcrumbs
        $this->breadcrumbs->push('TVShowFanTest', '/');
        $this->breadcrumbs->push('Review', '/iamready/index.php/Quiz/' . $quiz_id);

        $data = array('view' => 'quiz_review', 'dataBag' => $dataBag);

        $this->load->view('template', $data);
    }
}

/* End of file quiz.php */
/* Location: ./application/controllers/quiz.php */
