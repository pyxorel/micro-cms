<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/dbMessage.php';

class GalleryModel extends CI_Model {

	const ErrorMessage_UniqueName='Галлерея с таким названием уже существует';
	const TableName = 'galleries';
	//поля 
	public $ID;
	public $Name;
	public $Description;
	
	function __construct()
	{
		parent::__construct();
	}

	public function isValid($isCreate=TRUE)
	{
		if(!$isCreate)
		{
			$this->form_validation->set_rules('id', 'ID', 'required');
		}
		
		$this->form_validation->set_rules('name', 'Название', 'trim|required|alpha_dash');
		$this->form_validation->set_rules('description', 'Описание', 'trim');
		return $this->form_validation->run();
	}

	public function getGallereis(Paginator $paginator)
	{
		$this->load->database();
	
		$query = $this->db->get(GalleryModel::TableName, $paginator->getSize(), $paginator->getBeginElement());
		
		$paginator->setCountRow($this->db->count_all_results());
		return $query->result();
	}
	
	public function getGalleryNames($name=NULL, array $ids=NULL)
	{
		$this->load->database();
		$this->db->select('ID');
		$this->db->select('Name');
		if(!empty($name))
		{
			return $this->db->like('Name', $name, 'after')->get(GalleryModel::TableName)->result();
		}
		else if(!empty($ids))
		{
			return $this->db->where_in('ID', $ids)->get(GalleryModel::TableName)->result();
		}
		else
		{
			return $this->db->get(GalleryModel::TableName)->result();
		}
	}
	
	public function readGalleryByID($id)
	{
		$this->load->database();
	    $gallery = $this->db->get_where(GalleryModel::TableName, array('ID' => $id))->row();
	    if(!empty($gallery))
	    {
	    	return $gallery;
	    }
	    
	    $this->form_validation->set_custom_error(DBMessage::ErrorMessage_ObjNotFound);
	}

	public function readGalleryByName($name)
	{
		$this->load->database();
		$gallery = $this->db->get_where(GalleryModel::TableName, array('Name' => $name))->row();
		if(!empty($gallery))
		{
			return $gallery;
		}
		
		$this->form_validation->set_custom_error(DBMessage::ErrorMessage_ObjNotFound);
	}

	public function createGallery($name,$description)
	{
		$galleryModel= new GalleryModel();

		$galleryModel->Name=$name;
		$galleryModel->Description=$description;
		
		$this->load->database();
			
		if (!$this->db->insert(GalleryModel::TableName,$galleryModel))
		{
			$message=$this->db->_error_message();

			$this->form_validation->set_custom_error(str_replace('@', $message, DBMessage::ErrorMessage_InsertDB));
	
			return false;
		}
			
		return  $this->db->insert_id();
	}

	public function updateGallery($id, $name=null, $description=null)
	{
		$galleryModel= new GalleryModel();

		$galleryModel->Name=$name;
		$galleryModel->Description=$description;
		
		$this->load->database();

		if($galleryModel->Name!=null)
		{
			$this->db->set('Name', $galleryModel->Name);
		}

		$this->db->set('Description', $galleryModel->Description);
		
		$this->db->where('ID', $id);
		
		if(!$this->db->update(GalleryModel::TableName))
		{
			$message=$this->db->_error_message();
		
			$this->form_validation->set_custom_error(str_replace('@', $message, DBMessage::ErrorMessage_UpdateDB));

			return false;
		}
			
		return true;
	}

	public function deleteGallery($id)
	{
		$this->load->database();
		$this->db->where('ID', $id);
		
		if(!$this->db->delete(GalleryModel::TableName))
		{
			$message=$this->db->_error_message();
			$this->form_validation->set_custom_error(str_replace('@', $message, DBMessage::ErrorMessage_DeleteDB));
			return false;
		}
		return true;
	}
	
	public function getDropDownGalleryNames(Paginator $paginator)
	{
		$data = $this->getGallereis($paginator);
	
		$galleries=array('Не выбрано');
	
		foreach ($data as $item)
		{
			$galleries[$item->ID]= $item->Name . " ($item->Description)";
		}
	
		return $galleries;
	}
}



