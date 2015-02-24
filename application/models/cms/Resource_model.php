<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/dbMessage.php';

class Resource_model extends CI_Model
{

    const TableName = 'resources';
    const TableNameLocs = 'resource_locs';
    const VIEW_RES_LOCS = 'view_res_locs';

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

    public function isValid($isCreate = TRUE)
    {
        if (!$isCreate) {
            $this->form_validation->set_rules('id', 'ID', 'required');
        }

        $this->form_validation->set_rules('name', 'Название', 'trim|required|alpha_dash|max_length[255]');

        $this->load->model('cms/Lang_model');

        foreach ($this->Lang_model->get_langs() as $lang) {
            $this->form_validation->set_rules("data[$lang->id][content]", 'Значение', 'trim|required|max_length[1024]');
            $this->form_validation->set_rules("data[$lang->id][description]", 'Примечание', 'trim|max_length[255]');
        }

        return $this->form_validation->run();
    }

    public function get_resources($lang_code = NULL)
    {
        $this->db->order_by('Name', 'acs');

        if (empty($lang_code))
        {
            $query = $this->db->get(self::VIEW_RES_LOCS);
        }
        else {
            $this->db->distinct();
            $this->db->select('*');
            $this->db->where('code', $lang_code);
            $query = $this->db->get(self::VIEW_RES_LOCS);
        }
        return $query->result();
    }

    public function read_resourceByID($id)
    {
        $result = $this->db->get_where(self::VIEW_RES_LOCS, array('id' => $id))->result();
        $res = [];
        foreach ($result as $item) {
            $res[$item->code] = $item;
        }
        return $res;
    }

    public function read_resourceByName($name)
    {
        $result = $this->db->get_where(self::VIEW_RES_LOCS, array('name' => $id))->result();
        $res = [];
        foreach ($result as $item) {
            $res[$item->code] = $item;
        }
        return $res;
    }

    public function create_resource($name, $data)
    {
        $this->clean_last_error();
        $this->db->trans_start();

        if (!$this->db->insert(self::TableName, ['name'=>$name])) {

            $this->db->trans_rollback();
            $this->last_error = $this->db->error()['message'];
            return false;
        }

        $id_res = $this->db->insert_id();

        foreach ($data as $lang => $item) {

            $set_data = ['id_res'=>$id_res, 'id_lang'=>$lang, 'content'=>$item['content'], 'description'=>$item['description']];

            if (!$this->db->insert(self::TableNameLocs, $set_data)) {
                $this->db->trans_rollback();
                $this->last_error = $this->db->error()['message'];
                return false;
            }
        }
        $this->db->trans_complete();
        return true;
    }

    public function update_resource($id, $name = null, $data)
    {
        $this->clean_last_error();
        $this->db->trans_start();

        if ($name != null) {
            $this->db->set('Name', $name);
        }

        $this->db->where('ID', $id);

        if (!$this->db->update(self::TableName)) {

            $this->db->trans_rollback();
            $this->last_error = $this->db->error()['message'];
            return false;
        }

        foreach ($data as $lang => $item) {

            $this->db->where('id_res', $id);
            $this->db->where('id_lang', $lang);
            $this->db->set('content', $item['content']);
            $this->db->set('description', $item['description']);

            if (!$this->db->update(self::TableNameLocs)) {

                $this->db->trans_rollback();
                $this->last_error = $this->db->error()['message'];
                return false;
            }
        }
        $this->db->trans_complete();
        return true;
    }

    public function delete_resource($id)
    {
        $this->clean_last_error();
        $this->db->where('ID', $id);
        if (!$this->db->delete(self::TableName)) {
            $this->last_error = $this->db->error()['message'];
            return false;
        }
        return true;
    }

}