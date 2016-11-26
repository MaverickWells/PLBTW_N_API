<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NEWS_MODEL extends CI_Model {
	public function GetNewsCategory()
	{
		$this->db->distinct();
		$this->db->select('category');
		$query = $this->db->get('news');

		return $query->result();
	}

	public function GetNewsSubCategory()
	{
		$this->db->distinct();
		$this->db->select('sub_category');
		$query = $this->db->get('news');

		return $query->result();
	}

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

	public function GetLocationNews($location)
	{
		$query = $this->db->get_where('news', array('location' => $location));
		//var_dump($query->row_array());
		return $query->row_array();
	}

	public function GetCategoryNews($category)
	{
		$query = $this->db->get_where('news', array('category' => $category));
		//var_dump($query->row_array());
		return $query->result();
	}

	public function GetOneCategoryNews($category, $id)
	{
		$query = $this->db->get_where('news', array(
			'category' => $category,
			'id_news' => $id
		));
		//var_dump($query->row_array());
		return $query->row_array();
	}

	public function GetUserPrefNews($category, $sub_category)
	{
		$query = $this->db->get_where('news', array(
			'category' => $category,
			'sub_category' => $sub_category
		));

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
