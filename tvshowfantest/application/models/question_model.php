<?php

/**
 * Question_model.
 *
 * @category	Model
 *
 * @author	Shamal Perera
 */
class Question_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Delete question.
     *
     * @param   int question_id
     *
     * @return
     */
    public function delete_question($question_id)
    {
        $this->db->delete('question', array('id' => $question_id));
    }
    
    /**
     * Get questions.
     *
     * @param   int quiz_id
     *
     * @return array
     */
    public function get_questions($quiz_id)
    {
        $questions = array();
        $this->db->select('id, title, image_path');
        $this->db->from('question');
        $this->db->where('quiz_id', $quiz_id);
        $query = $this->db->get();

        foreach ($query->result() as $row) {
            $temp = array('id' => $row->id, 'title' => $row->title, 'image' => $row->image_path);
            array_push($questions, $temp);
        }

        return $questions;
    }
    /**
     * Get question.
     *
     * @param   int question_id
     *
     * @return array
     */
    public function get_question($question_id)
    {
        $question = array();
        $this->db->select('id, title, image_path');
        $this->db->from('question');
        $this->db->where('id', $question_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $question = array('id' => $row->id, 'title' => $row->title, 'image' => $row->image_path);
        }

        return $question;
    }
        /**
     * Set question.
     *
     * @param   object question
     *
     * @return
     */
    public function set_question($question)
    {
        $data = array('title' => $question->title, 'image_path' => $question->image);
        $this->db->where('id', $question->id);
        $this->db->update('question', $data);
    }

    /**
     * Set questions.
     *
     * @param   object questions
     *
     * @return
     */
    public function set_questions($questions)
    {
        $data = array();
        foreach ($questions as $question) {
            array_push($data, array('id' => $question->id, 'title' => $question->title, 'image_path' => $question->image));
        }
        $this->db->update_batch('question', $data, 'id');
    }
    
    /**
     * Add question.
     *
     * @param   object question, int quiz_id
     *
     * @return int
     */
    public function add_question($question, $quiz_id)
    {
        $data = array('title' => $question->title, 'image_path' => $question->image, 'quiz_id' => $quiz_id);
        $this->db->insert('question', $data);

        $id = $this->db->insert_id();

        return $id;
    }
    /**
     * Add questions.
     *
     * @param   array questions, int quiz_id
     *
     * @return int
     */
    public function add_questions($questions, $quiz_id)
    {
        $data = array();
        foreach ($questions as $question) {
            array_push($data, array('title' => $question->title, 'image_path' => $question->image, 'quiz_id' => $quiz_id));
        }
        $this->db->insert_batch('question', $data);
    }

}
/* End of file question_model.php */
/* Location: ./application/models/question_model.php */