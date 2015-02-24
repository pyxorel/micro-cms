<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/dbMessage.php';

class GalleryItemModel extends CI_Model
{

	const TableName = 'gallery_item';
	const TableNameLocs = 'gallery_item_locs';
	const VIEW_GALL_LOCS = 'view_gall_locs';
	//поля
	public $ID;
	public $IDGallery;
	public $URI;
	public $Description;

	public $last_error;

	function GalleryItemModel()
	{
		parent::__construct();
		$this->load->database();
	}
	
	public function isValid($isCreate=TRUE)
	{
		if(!$isCreate)
		{
			$this->form_validation->set_rules('id', 'ID', 'required');
		}

		/*$this->load->model('cms/Lang_model');

		foreach ($this->Lang_model->get_langs() as $lang) {

			$this->form_validation->set_rules("data[$lang->id][img]", 'Заголовок', 'trim|required|max_length[255]|xss_clean');
			$this->form_validation->set_rules("data[$lang->id][desc]", 'Примечание', 'trim|max_length[255]|xss_clean');
		}

		return $this->form_validation->run();*/
		return TRUE;
	}
	
	public function getItems($id_gall, Paginator $paginator=null)
	{
		$result = $this->db->where('IDGallery', $id_gall)->get(self::VIEW_GALL_LOCS)->result();
		$res = [];
		foreach ($result as $item) {
			if (!isset($res[$item->lang_code])) {
				$res[$item->lang_code] = [$item];
			} else {
				array_push($res[$item->lang_code], $item);
			}
		}
		return !empty($lang_code) && !empty($res) ? $res[$lang_code] : $res;
	}
	
	public function readItemByID($id)
	{
		$result = $this->db->get_where(self::VIEW_GALL_LOCS, array('id' => $id))->result();
		$res = [];
		foreach ($result as $item) {
			$res[$item->lang_code] = $item;
		}
		return $res;
	}
	
	public function readItemByName($name)
	{
		$result = $this->db->get_where(self::VIEW_GALL_LOCS, array('name' => $name))->result();
		$res = [];
		foreach ($result as $item) {
			$res[$item->lang_code] = $item;
		}
		return $res;
	}
	
	public function createItem($id, $data)
	{
		$this->db->trans_start();

		if (!$this->db->insert(self::TableName, ['IDGallery' => $id])) {

			$this->db->trans_rollback();
			$this->last_error = $this->db->error()['message'];
			return false;
		}

		$id_item = $this->db->insert_id();

		foreach ($data as $lang => $item) {

			$set_data = ['id_item' => $id_item, 'id_lang' => $lang,  'img' => base64_encode($item['img']), 'description' => $item['desc']];

			if (!$this->db->insert(self::TableNameLocs, $set_data)) {
				$this->db->trans_rollback();
				$this->last_error = $this->db->error()['message'];
				return false;
			}
		}

		$this->db->trans_complete();
		return $id_item;
	}
	
	public function updateItem($id, $data)
	{
		$this->db->trans_start();
		foreach ($data as $lang => $item) {

			$this->db->where('id_item', $id);
			$this->db->where('id_lang', $lang);
			$this->db->set('description', $item['desc']);
			$this->db->set('img', base64_encode($item['img']));

			if (!$this->db->update(self::TableNameLocs)) {
				$this->db->trans_rollback();
				$this->last_error = $this->db->error()['message'];
				return false;
			}
		}

		$this->db->trans_complete();
		return true;
	}
	
	public function deleteItem($id)
	{
		$this->load->database();
		$this->db->where('ID', $id);
	
		if(!$this->db->delete(GalleryItemModel::TableName))
		{
			$this->last_error = $this->db->error()['message'];
			return false;
		}
		return true;
	}
	
	
	
}