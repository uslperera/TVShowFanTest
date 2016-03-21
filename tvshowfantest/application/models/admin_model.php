<?php

/**
 * Admin_model.
 *
 * @category	Model
 *
 * @author	Shamal Perera
 */
class Admin_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('string');
        $this->load->library('encrypt');
    }

    public function create_session($username, $password)
    {
        $data = null;
        $this->db->select('*');
        $this->db->from('administrator');
        $this->db->where('username', $username);
        $this->db->where('password', $this->encrypt->sha1($password));
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $data = array('session_key' => random_string('alnum', 64));
            $this->db->update('administrator', $data, array('username' => $username));
        }
        return $data;
    }

    public function is_valid_session($key)
    {
        $this->db->select('*');
        $this->db->from('administrator');
        $this->db->where('session_key', $key);

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }

    public function get_session($key){
      $this->db->select('username');
      $this->db->from('administrator');
      $this->db->where('session_key', $key);
      $query = $this->db->get();

      if ($query->num_rows() > 0) {
          $row = $query->row();
          $data = array('username' => $row->username);
          return $data;
      }
      return null;
    }

    public function destroy_session($key){
        $data = array('session_key' => random_string('alnum', 16));
        $this->db->update('administrator', $data, array('session_key' => $key));
    }

    public function set_password($username, $password){
      $data = array('password' => $this->encrypt->sha1($password));
      $this->db->update('administrator', $data, array('username' => $username));
    }
}
/* End of file admin_model.php */
/* Location: ./application/models/admin_model.php */