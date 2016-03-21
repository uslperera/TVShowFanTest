<?php

/**
 * Quiz_model.
 *
 * @category    Model
 *
 * @author  Shamal Perera
 */
class Quiz_model extends CI_Model
{
    private $random_count = 5;

    public function __construct()
    {
        parent::__construct();
        //load database
        $this->load->database();
    }

    /**
     * Returns the list of quizzes.
     *
     * @param
     *
     * @return array
     */
    public function get_partial_quizzes()
    {
        $quizzes = array();

        $this->db->select('id, title, description, time_allocated, tvshow_id');
        $this->db->from('quiz');
        $this->db->where('is_active', true);
        $query = $this->db->get();

        foreach ($query->result() as $key => $row) {
            $quizzes[$key] = array('id' => $row->id, 'title' => $row->title, 'description' => $row->description, 'time' => $row->time_allocated, 'tvshow' => $row->tvshow_id);
        }

        return $quizzes;
    }

    /**
     * Returns the list of random quizzes that contains $random_count number of values.
     *
     * @param
     *
     * @return array
     */
    public function get_quizzes_random()
    {
        $quizzes = array();

        $this->db->select('id, title, description');
        $this->db->order_by('id', 'rand');
        $this->db->limit($this->random_count);
        $this->db->from('quiz');
        $this->db->where('is_active', true);
        $query = $this->db->get();

        foreach ($query->result() as $key => $row) {
            $quizzes[$key] = array('id' => $row->id, 'title' => $row->title, 'description' => $row->description);
        }

        return $quizzes;
    }

    /**
     * Returns the list of quizzes by tv show.
     *
     * @param   int tvshow_id
     *
     * @return array
     */
    public function get_quizzes_by_tvshow($tvshow_id)
    {
        $quizzes = array();

        $this->db->select('id, title, description');
        $this->db->where('tvshow_id', $tvshow_id);
        $this->db->from('quiz');
        $this->db->where('is_active', true);
        $query = $this->db->get();

        foreach ($query->result() as $key => $row) {
            $quizzes[$key] = array('id' => $row->id, 'title' => $row->title, 'description' => $row->description);
        }

        return $quizzes;
    }

    /**
     * Returns the list of quizzes that similar to the given title.
     *
     * @param   string title
     *
     * @return array
     */
    public function get_quizzes_by_title($title)
    {
        $quizzes = array();

        $this->db->select('id, title, description');
        $this->db->like('title', $title);
        $this->db->from('quiz');
        $this->db->where('is_active', true);
        $query = $this->db->get();

        foreach ($query->result() as $key => $row) {
            $quizzes[$key] = array('id' => $row->id, 'title' => $row->title, 'description' => $row->description);
        }

        return $quizzes;
    }

    /**
     * Returns the title of the quiz.
     *
     * @param   int quiz_id
     *
     * @return string
     */
    public function get_quiz_details($quiz_id)
    {
        $quiz_details = array();

        $this->db->select('quiz.id, quiz.title, description, time_allocated, count(*) as no_of_questions');
        $this->db->from('quiz');
        $this->db->join('question', 'question.quiz_id=quiz.id');
        $this->db->where('quiz.id', $quiz_id);
        $this->db->where('is_active', true);
        $this->db->group_by('title');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $quiz_details = array('quiz_id' => $row->id, 'quiz_title' => $row->title, 'quiz_description' => $row->description, 'quiz_time' => $row->time_allocated, 'question_count' => $row->no_of_questions);
        }

        return $quiz_details;
    }

    /**
     * Returns the list of questions for the quiz with choices.
     *
     * @param   int quiz_id
     *
     * @return array
     */
    public function get_questions($quiz_id)
    {
        $this->db->select('question.id as q_id, question.title as q_title, question.image_path as q_image_path, choice.id as c_id, choice.title as c_title, choice.is_answer');
        $this->db->from('question');
        $this->db->join('choice', 'choice.question_id=question.id');
        $this->db->where('question.quiz_id', $quiz_id);
        $this->db->order_by('question.id', 'asc');
        $query = $this->db->get();

        $questions = $this->create_quiz($query);

        return $questions;
    }

    /**
     * Creates the set of questions for the quiz.
     *
     * @param   Query query
     *
     * @return array
     */
    private function create_quiz($query)
    {
        $questions = array();
        $id = 0;
        $answerCount = 0;
        foreach ($query->result() as $row) {

            //if the current question is not the previous question
            //then it is a new question
            if ($id != $row->q_id) {
                $answerCount = 0;
                $id = $row->q_id;

                //get the created question
                $questions[$id] = $this->create_question($row);
            }

            //assign the first choice
            $questions[$id]['choices'][$row->c_id] = $row->c_title;

            //if the choice is the answer for the question
            //to check if the question has multiple correct answers
            if ($row->is_answer == true) {
                ++$answerCount;

                //set if there are more than 1 correct answer
                $questions[$id]['isMultiple'] = ($answerCount > 1) ? true : false;
            }
        }

        return $questions;
    }

    /**
     * Creates a question from the given details.
     *
     * @param   array row
     *
     * @return array
     */
    private function create_question($row)
    {
        return array('id' => $row->q_id, 'title' => $row->q_title, 'image' => $row->q_image_path, 'choices' => array(), 'correctChoices' => array(), 'isMultiple' => false);
    }

    /**
     * Returns the list of questions with correct choices.
     *
     * @param int $quiz_id
     *
     * @return array
     */
    public function get_questions_with_correct_choices($quiz_id)
    {
        $questions = $this->get_questions($quiz_id);

        $this->db->select('question.id as q_id, choice.id as c_id, choice.title as c_title');
        $this->db->from('question');
        $this->db->join('choice', 'choice.question_id=question.id');
        $this->db->where('question.quiz_id', $quiz_id);
        $this->db->where('choice.is_answer', true);
        $this->db->order_by('question.id', 'asc');
        $query = $this->db->get();

        $id = 0;
        foreach ($query->result() as $row) {

            //if the current question is not the previous question
            //then it is a new question
            if ($id != $row->q_id) {
                $id = $row->q_id;
                $questions[$id]['correct_choices'] = array();
            }
            $questions[$id]['correct_choices'][$row->c_id] = $row->c_title;
        }

        return $questions;
    }

    /**
     * Get score for the quiz.
     *
     * @param   array answers, array correct_answers
     *
     * @return int
     */
    private function get_score($answers, $correct_answers)
    {
        $score = 0;
        foreach ($answers as $key => $answer) {

            //if multiple answers are provided
            if (is_array($answer)) {
                $multiple_answers = explode(',', $correct_answers[$key]);

                //if all the answers are correct for the question
                (!$this->is_answers_similar($answer, $multiple_answers)) ?: $score++;
            } elseif ($answer == $correct_answers[$key]) {
                ++$score;
            }
        }

        return $score;
    }

    /**
     * Is all the answers are same.
     *
     * @param array answers, array $multiple_answers
     *
     * @return bool
     */
    private function is_answers_similar($answer, $multiple_answers)
    {
        $isAllCorrect = true;

        //if the length not equals
        if (count($answer) != count($multiple_answers)) {
            echo 'ad';

            return false;
        }
        foreach ($multiple_answers as $multiple_answer) {

            //check if all answers match
            if (!isset($answer[$multiple_answer])) {
                return false;
            }
        }
        if ($isAllCorrect) {
            return true;
        }
    }

    /**
     * Get the result of the quiz.
     *
     * @param   int quiz_id, array answers
     *
     * @return array
     */
    public function get_result($quiz_id, $answers)
    {
        $result = array();

        //get correct answers for the quiz
        $correct_answers = $this->get_correct_answers($quiz_id);

        //get the score
        $score = $this->get_score($answers, $correct_answers);

        //get the message based on the score
        $result['message'] = $this->get_result_message($score, count($correct_answers));

        //set the score
        $result['score'] = $score;

        //get average score of the quiz
        $result['avg_score'] = $this->get_avg_score($quiz_id);

        //update quiz total score and attempts
        $this->update_quiz($quiz_id, $score);

        return $result;
    }

    /**
     * Update quiz total score and number of attempted times.
     *
     * @param   int quiz_id, int score
     */
    private function update_quiz($quiz_id, $score)
    {
        $this->db->where('id', $quiz_id);
        $this->db->set('attempts', 'attempts + 1', false);
        $this->db->set('total_score', "total_score + $score", false);
        $this->db->update('quiz');
    }

    /**
     * Get the average score for the quiz.
     *
     * @param   int quiz_id
     *
     * @return float
     */
    private function get_avg_score($quiz_id)
    {
        $avg_score = 0;
        $this->db->select('total_score, attempts');
        $this->db->from('quiz');
        $this->db->where('id', $quiz_id);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            if ($row->attempts == 0) {
                $avg_score = 0;
            } else {
                $avg_score = round($row->total_score / $row->attempts, 2);
            }
            $avg_score = round($row->total_score / $row->attempts, 2);
        }

        return $avg_score;
    }

    /**
     * Get the message for the result.
     *
     * @param   int score, int count
     *
     * @return string
     */
    private function get_result_message($score, $count)
    {

        //calculate the percentage
        $percentage = $score / $count * 100;
        if ($percentage == 100) {
            $message = 'Congratulations! You are the ULTIMATE fan!';
        } elseif ($score > 5) {
            $message = 'Better luck next time!';
        } else {
            $message = "It must be a while since you've watched the show! Watch a few more episodes and you can be a true fan.";
        }

        return $message;
    }

    /**
     * Get correct answers for the questions in quiz.
     *
     * @param   int quiz_id
     *
     * @return array
     */
    private function get_correct_answers($quiz_id)
    {
        $correct_answers = array();

        $this->db->select('question.id as q_id, choice.id as c_id');
        $this->db->from('question');
        $this->db->join('choice', 'choice.question_id = question.id');
        $this->db->where('question.quiz_id', $quiz_id);
        $this->db->where('choice.is_answer', true);
        $this->db->order_by('question.id', 'asc');
        $query = $this->db->get();

        $id = 0;
        foreach ($query->result() as $row) {

            //if the current question is not the previous question
            //then it is a new question
            if ($id != $row->q_id) {
                $id = $row->q_id;
                $correct_answers[$id] = $row->c_id;
            } else {

                //append correct answers
                $correct_answers[$id] .= ','.$row->c_id;
            }
        }

        return $correct_answers;
    }

    /**
     * Delete quiz.
     *
     * @param   int quiz_id
     *
     * @return
     */
    public function delete_quiz($quiz_id)
    {
        $this->db->delete('quiz', array('id' => $quiz_id));
    }

    /**
     * Get quiz with questions and choices.
     *
     * @param   int quiz_id
     *
     * @return array
     */
    public function get_quiz($quiz_id)
    {
        $quiz = array();
        $this->db->select('quiz.id as quiz_id, quiz.title as quiz_title, quiz.description as description, quiz.time_allocated as time, quiz.tvshow_id as tvshow_id, quiz.is_active as is_active, question.id as question_id, question.title as question_title, question.image_path as image_path, choice.id as choice_id, choice.title as choice_title, choice.is_answer as is_answer');
        $this->db->from('quiz');
        $this->db->join('question', 'question.quiz_id = quiz.id', 'left');
        $this->db->join('choice', 'choice.question_id = question.id', 'left');
        $this->db->where('quiz.id', $quiz_id);
        $this->db->order_by('quiz.id', 'asc');
        $this->db->order_by('question.id', 'asc');
        $this->db->order_by('choice.id', 'asc');

        $query = $this->db->get();
        $quizid = 0;
        $questionid = 0;
        $quiz = null;
        $question = null;
        $pending_question = false;
        //each row returns quiz details, question details with choice details
        foreach ($query->result() as $row) {
            //if a new question
            if ($questionid != $row->question_id) {
                if ($pending_question) {
                    $pending_question = false;
                    array_push($quiz['questions'], $question);
                }
                $pending_question = true;
                $questionid = $row->question_id;
                //set question details
                $question = array('id' => $row->question_id, 'title' => $row->question_title, 'image' => $row->image_path, 'choices' => array());
            }
            //if a new quiz
            if ($quizid != $row->quiz_id) {
                $pending_quiz = true;
                $quizid = $row->quiz_id;
                //set quiz details
                $quiz = array('id' => $row->quiz_id, 'title' => $row->quiz_title, 'description' => $row->description, 'time' => $row->time, 'isActive' => (bool) $row->is_active, 'tvshow' => $row->tvshow_id, 'questions' => array());
            }
            //if question is not null and choice is not null
            if ($question && $row->choice_id != null) {
                //add choice details
                $choice = array('id' => $row->choice_id, 'title' => $row->choice_title, 'isAnswer' => (bool) $row->is_answer);
                array_push($question['choices'], $choice);
            }
        }
        //add last question
        if ($pending_question) {
            array_push($quiz['questions'], $question);
        }

        return $quiz;
    }

    /**
     * Set quiz.
     *
     * @param   object quiz
     *
     * @return
     */
    public function set_quiz($quiz)
    {
        $data = array('title' => $quiz->title, 'description' => $quiz->description, 'tvshow_id' => $quiz->tvshow, 'time_allocated' => $quiz->time, 'is_active' => $quiz->isActive);
        $this->db->where('id', $quiz->id);
        $this->db->update('quiz', $data);
    }

    /**
     * Add quiz.
     *
     * @param   object quiz
     *
     * @return int
     */
    public function add_quiz($quiz)
    {
        $data = array('title' => $quiz->title, 'description' => $quiz->description, 'tvshow_id' => $quiz->tvshow, 'time_allocated' => $quiz->time);
        $this->db->insert('quiz', $data);

        $id = $this->db->insert_id();

        return $id;
    }

    /**
     * Get all quizzes with questions and choices.
     *
     * @param
     *
     * @return array
     */
    public function get_quizzes()
    {
        $quizzes = array();
        $this->db->select('quiz.id as quiz_id, quiz.title as quiz_title, quiz.description as description, quiz.time_allocated as time, quiz.tvshow_id as tvshow_id, quiz.is_active as is_active, question.id as question_id, question.title as question_title, question.image_path as image_path, choice.id as choice_id, choice.title as choice_title, choice.is_answer as is_answer');
        $this->db->from('quiz');
        $this->db->join('question', 'question.quiz_id = quiz.id', 'left');
        $this->db->join('choice', 'choice.question_id = question.id', 'left');
        $this->db->order_by('quiz.id', 'asc');
        $this->db->order_by('question.id', 'asc');
        $this->db->order_by('choice.id', 'asc');

        $query = $this->db->get();
        $quizid = 0;
        $questionid = 0;
        $quiz = null;
        $question = null;
        $pending_quiz = false;
        $pending_question = false;
        //each row returns quiz details, question details with choice details
        foreach ($query->result() as $row) {
            //if a new question
            if ($questionid != $row->question_id) {
                //add the previous question
                if ($pending_question) {
                    $pending_question = false;
                    array_push($quiz['questions'], $question);
                }
                if ($row->question_id != null) {
                    $pending_question = true;
                    $questionid = $row->question_id;
                    //set question details
                    $question = array('id' => $row->question_id, 'title' => $row->question_title, 'image' => $row->image_path, 'choices' => array());
                }
            }
            //if a new quiz
            if ($quizid != $row->quiz_id) {
                //add the previous quiz
                if ($pending_quiz) {
                    $pending_quiz = false;
                    array_push($quizzes, $quiz);
                }
                $pending_quiz = true;
                $quizid = $row->quiz_id;
                //set quiz details
                $quiz = array('id' => $row->quiz_id, 'title' => $row->quiz_title, 'description' => $row->description, 'time' => $row->time, 'isActive' => (bool) $row->is_active, 'tvshow' => $row->tvshow_id, 'questions' => array());
            }
            //if question is not null and choice is not null
            if ($question && $row->choice_id != null) {
                //add choice details
                $choice = array('id' => $row->choice_id, 'title' => $row->choice_title, 'isAnswer' => (bool) $row->is_answer);
                array_push($question['choices'], $choice);
            }
        }
        //add last question
        if ($pending_question) {
            array_push($quiz['questions'], $question);
        }
        //add last quiz
        if ($pending_quiz) {
            array_push($quizzes, $quiz);
        }

        return $quizzes;
    }
}

/* End of file quiz_model.php */

/* Location: ./application/models/quiz_model.php */