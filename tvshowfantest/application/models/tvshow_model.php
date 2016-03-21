<?php

/**
 * TVShow_model
 *
 * @package	tvshowfantest
 * @subpackage	models
 * @category	Model
 * @author	Shamal Perera
 */
class TVShow_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Returns the list of tv shows ordered by name in ascending order
     *
     * @access	public
     * @param
     * @return	array
     */
    public function get_tvshows() {
        $tvshows = array();

        $this->db->select('id, name');
        $this->db->order_by('name', 'asc');
        $query = $this->db->get('tvshow');

        foreach ($query->result() as $row) {
            $tvshows[$row->id] = $row->name;
        }

        return $tvshows;
    }

    public function _get_tvshows() {
        $tvshows = array();

        $this->db->select('id, name, description');
        $query = $this->db->get('tvshow');

        foreach ($query->result() as $row) {
            $temp = array('id' => $row->id, 'name' => $row->name, 'description' => $row->description);
            array_push($tvshows, $temp);
        }

        return $tvshows;
    }

    public function get_tvshow($tvshow_id) {
        $tvshow = array();

        $this->db->select('id, name, description');
        $this->db->from('tvshow');
        $this->db->where('id', $tvshow_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $tvshow = array('id' => $row->id, 'name' => $row->name, 'description' => $row->description);
        }
        return $tvshow;
    }

    public function set_tvshow($tvshow) {
        $data = array('name' => $tvshow->name, 'description' => $tvshow->description);
        $this->db->where('id', $tvshow->id);
        $this->db->update('tvshow', $data);
    }

    public function add_tvshow($tvshow) {
        $data = array('name' => $tvshow->name, 'description' => $tvshow->description);
        $this->db->insert('tvshow', $data);

        $id = $this->db->insert_id();

        return $id;
    }

    public function delete_tvshow($tvshow_id)
    {
        $this->db->delete('tvshow', array('id' => $tvshow_id));
    }
}

/* End of file tvshow_model.php */
/* Location: ./application/models/tvshow_model.php */
