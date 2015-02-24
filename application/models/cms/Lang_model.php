<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/dbMessage.php';
include_once 'application/utils/paginator.php';

class Lang_model extends CI_Model {

	const TableName = 'langs';

	public $last_error;

	private function clean_last_error()
	{
		$last_error = NULL;
	}
	
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	public function isValid($isCreate=TRUE)
	{
		self::clean_last_error();
	
		$this->load->library('form_validation');
	
		if(!$isCreate)
		{
			$this->form_validation->set_rules('id', 'id', 'required');
		}

		return $this->form_validation->run();
	}
	
	public function get_langs(Paginator $paginator=NULL)
	{
		self::clean_last_error();
	
		$this->db->order_by('id', 'acs');
		if($paginator!=NULL)
		{
			$query = $this->db->get(self::TableName, $paginator->getSize(), $paginator->getBeginElement());
			$paginator->setCountRow($this->db->count_all(self::TableName));
			return $query->result();
		}
		
		return $this->db->get(self::TableName)->result();
	}
	
}