<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class API_MODEL extends CI_Model {
    public function CheckAPIKEY($api_key)
    {
        $this->db->where('user_api_key', $api_key);
        $this->db->get('api_key');

        return $this->db->affected_rows();
    }
}
