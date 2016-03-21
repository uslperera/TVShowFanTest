<?php

/**
 * Choice_model.
 *
 * @category	Model
 *
 * @author	Shamal Perera
 */
class Choice_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Delete choice.
     *
     * @param   int choice_id
     *
     * @return
     */
    public function delete_choice($choice_id)
    {
        $this->db->delete('choice', array('id' => $choice_id));
    }
    /**
     * Get choices.
     *
     * @param   int question_id
     *
     * @return array
     */
    public function get_choices($question_id)
    {
        $choices = array();
        $this->db->select('id, title, is_answer');
        $this->db->from('choice');
        $this->db->where('question_id', $question_id);
        $query = $this->db->get();

        foreach ($query->result() as $row) {
            $temp = array('id' => $row->id, 'title' => $row->title, 'isAnswer' => ($row->is_answer == 1 ? true : false));
            array_push($choices, $temp);
        }

        return $choices;
    }

    /**
     * Get choice.
     *
     * @param   int choice_id
     *
     * @return array
     */
    public function get_choice($choice_id)
    {
        $choice = array();
        $this->db->select('id, title, is_answer');
        $this->db->from('choice');
        $this->db->where('id', $choice_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $choice = array('id' => $row->id, 'title' => $row->title, 'isAnswer' => ($row->is_answer == 1 ? true : false));
        }

        return $choice;
    }

    /**
     * Set choice.
     *
     * @param   object choice
     *
     * @return
     */
    public function set_choice($choice)
    {
        $data = array('title' => $choice->title, 'is_answer' => $choice->isAnswer);
        $this->db->where('id', $choice->id);
        $this->db->update('choice', $data);
    }

    /**
     * Set choices.
     *
     * @param   object choices
     *
     * @return
     */
    public function set_choices($choices)
    {
        $data = array();
        foreach ($choices as $choice) {
            array_push($data, array('id' => $choice->id, 'title' => $choice->title, 'is_answer' => $choice->isAnswer));
        }
        $this->db->update_batch('choice', $data, 'id');
    }
    
    /**
     * Add choice.
     *
     * @param   object choice, int question_id
     *
     * @return int
     */
    public function add_choice($choice, $question_id)
    {
        $data = array('title' => $choice->title, 'is_answer' => $choice->isAnswer, 'question_id' => $question_id);
        $this->db->insert('choice', $data);
    }
    
    /**
     * Add choices.
     *
     * @param   array choices, int question_id
     *
     * @return int
     */
    public function add_choices($choices, $question_id)
    {
        $data = array();
        foreach ($choices as $c) {
            $choice = (object) $c;
            array_push($data, array('title' => $choice->title, 'is_answer' => $choice->isAnswer, 'question_id' => $question_id));
        }
        $this->db->insert_batch('choice', $data);
    }


}
/* End of file choice_model.php */
/* Location: ./application/models/choice_model.php */