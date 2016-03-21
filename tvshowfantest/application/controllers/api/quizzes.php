<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Quizzes.
 *
 * @category	Controller
 *
 * @author	Shamal Perera
 */
class Quizzes extends Rest_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('quiz_model');
        $this->load->model('question_model');
        $this->load->model('choice_model');
    }

    public function get()
    {
        $data = null;
        $args = $this->uri->uri_to_assoc(2);

        if (count($args) == 1) {
            $data = $this->quizzes($args['quizzes']);
        } elseif (count($args) == 2) {
            $data = $this->questions($args['quizzes'], $args['questions']);
        } elseif (count($args) == 3) {
            $data = $this->choices($args['questions'], $args['choices']);
        }
        if($data==null){
          show_error('Resource not found', 404);
        }
        $this->output->set_output(json_encode($data));
    }

    private function quizzes($quiz_id)
    {
        if ($quiz_id) {
            return $this->quiz_model->get_quiz($quiz_id);
        } else {
            return $this->quiz_model->get_quizzes();
        }
    }

    private function questions($quiz_id, $question_id)
    {
        if ($question_id) {
            return $this->question_model->get_question($question_id);
        } else {
            return $this->question_model->get_questions($quiz_id);
        }
    }

    private function choices($question_id, $choice_id)
    {
        if ($choice_id) {
            return $this->choice_model->get_choice($choice_id);
        } else {
            return $this->choice_model->get_choices($question_id);
        }
    }

    public function put()
    {
        $data = json_decode(file_get_contents('php://input'));
        //validate quiz data
        $error = $this->validate($data);
        if ($error) {
            $response = array('status' => 'error', 'msg' => $error);
            $this->output->set_output(json_encode($response));
        } else {
            //cast $data to an array
            $data = json_decode(file_get_contents('php://input'), true);
            $quiz_id = $data['id'];
            //save quiz data
            $this->quiz_model->set_quiz((object) $data);
            //get existing questions
            $existing_questions = $this->question_model->get_questions($quiz_id);
            //get existing question ids
            $existing_question_ids = array_column($existing_questions, 'id');
            //get input question ids
            $put_question_ids = array_column($data['questions'], 'id');
            //get the difference of two arrays
            //resulting ids will be the questions to be deleted
            $question_ids_to_delete = array_diff($existing_question_ids, $put_question_ids);
            foreach ($question_ids_to_delete as $question_id_to_delete) {
                $this->question_model->delete_question($question_id_to_delete);
            }
            foreach ($data['questions'] as $question) {
                if (array_key_exists('id', $question)) {
                    //existing question
                    $this->question_model->set_question((object) $question);
                    //get existing choices
                    $existing_choices = $this->choice_model->get_choices($question['id']);
                    //get existing choice ids
                    $existing_choice_ids = array_column($existing_choices, 'id');
                    //get input choice ids
                    $put_choice_ids = array_column($question['choices'], 'id');
                    //get the difference of two arrays
                    //resulting ids will be the choices to be deleted
                    $choice_ids_to_delete = array_diff($existing_choice_ids, $put_choice_ids);
                    foreach ($choice_ids_to_delete as $choice_id_to_delete) {
                        $this->choice_model->delete_choice($choice_id_to_delete);
                    }
                    foreach ($question['choices'] as $choice) {
                        if (array_key_exists('id', $choice)) {
                            //exsting choice
                            $this->choice_model->set_choice((object) $choice);
                        } else {
                            //new choice
                            $this->choice_model->add_choice((object) $choice, $question['id']);
                        }
                    }
                } else {
                    //new question
                $question_id = $this->question_model->add_question((object) $question, $quiz_id);
                    if (!empty($question['choices'])) {
                        $this->choice_model->add_choices((object) ($question['choices']), $question_id);
                    }
                }
            }
            $data = $this->quiz_model->get_quiz($quiz_id);
            $this->output->set_output(json_encode($data));
        }
    }

    public function delete()
    {
        //get quiz id url /api/quizzes/1
        $quiz_id = $this->uri->segment(3);
        $this->quiz_model->delete_quiz($quiz_id);

        $repsonse = array('status' => 'success');
        $this->output->set_output(json_encode($repsonse));
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
            //add quiz
            $quiz_id = $this->quiz_model->add_quiz($data);

            foreach ($data->questions as $question) {
                //add questions
                $question_id = $this->question_model->add_question($question, $quiz_id);
                //add choices
                $this->choice_model->add_choices($question->choices, $question_id);
            }
            //get newly added quiz
            $data = $this->quiz_model->get_quiz($quiz_id);

            $this->output->set_output(json_encode($data));
        }
    }

    private function validate($quiz)
    {
        if ($quiz->title == '') {
            return 'Quiz title cannot be empty';
        }
        if ($quiz->time < 60) {
            return 'Minimum number of seconds allowed is 60';
        }
        //check for atleat 5 questions
        if (!property_exists($quiz, 'questions') || count($quiz->questions) < 5) {
            return 'Minimum number of questions allowed is 5';
        }
        foreach ($quiz->questions as $question) {
            if ($question->title == '') {
                return 'Question title cannot be empty';
            }
            //check for atleat 2 choices
            if (!property_exists($question, 'choices') || count($question->choices) < 2) {
                return 'Minimum number of choices allowed is 2';
            }
            $correctAns = 0;
            foreach ($question->choices as $choice) {
                if ($choice->title == '') {
                    return 'Choice title cannot be empty';
                }
                if ($choice->isAnswer) {
                    ++$correctAns;
                }
            }
            if ($correctAns == 0) {
                return 'There must be atleast one correct answer';
            }
        }
    }
}