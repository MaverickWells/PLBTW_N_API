<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class USER_MODEL extends CI_Model {
	public function CreateUser($data)
	{
		$this->db->insert('user', $data);

		return $this->db->affected_rows();
	}

	public function GetAllUser()
	{
		$query = $this->db->get('user');

		return $query->result();
	}

	public function GetUser($id)
	{
		$query = $this->db->get_where('user', array('iduser' => $id));
		//var_dump($query->row_array());
		return $query->row_array();
	}

	public function DeleteUser($id)
	{
		$this->db->where('iduser', $id);
        $this->db->delete('user');

        return $this->db->affected_rows();
	}

	public function UpdateUser($data, $iduser)
	{
		$this->db->where('iduser', $iduser);
		$this->db->update('user', $data);

		return $this->db->affected_rows();
	}

	public function InsertAPIKEY($data)
	{
		$this->db->insert('api_key', $data);

		return $this->db->affected_rows();
	}
}
