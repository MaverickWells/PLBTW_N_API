<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NEWS_MODEL extends CI_Model {
	public function CreateNews($data)
	{
		$this->db->insert('news', $data);

		return $this->db->affected_rows();
	}

	public function GetAllNews()
	{
		$query = $this->db->get('news');

		return $query->result();
	}

	public function GetNews($id)
	{
		$query = $this->db->get_where('news', array('id_news' => $id));
		//var_dump($query->row_array());
		return $query->row_array();
	}

	public function DeleteNews($id)
	{
		$this->db->where('id_news', $id);
        $this->db->delete('news');

		return $this->db->affected_rows();
	}

	public function UpdateNews($data, $idnews)
	{
		$this->db->where('id_news', $idnews);
		$this->db->update('news', $data);

		return $this->db->affected_rows();
	}
}
