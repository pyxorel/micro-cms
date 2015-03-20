<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/dbMessage.php';
include_once 'application/utils/paginator.php';

class Page_model extends CI_Model
{
    const TableName = 'documents';
    const TableNameLocs = 'document_locs';
    const VIEW_DOC_LOCS = 'view_doc_locs';
    const TableNameIndex = 'documents_index';

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public $last_error;

    private function clean_last_error()
    {
        $last_error = NULL;
    }

    public function isValid($isCreate = TRUE)
    {
        if (!$isCreate) {
            $this->form_validation->set_rules('id', 'ID', 'required');
        }

        $this->load->model('cms/Lang_model');
        foreach ($this->Lang_model->get_langs() as $lang) {

            $this->form_validation->set_rules("data[$lang->id][head]", 'Заголовок', 'required|max_length[255]');
            $this->form_validation->set_rules("data[$lang->id][scontent]", 'Краткое описание', 'trim|max_length[1024]');
            $this->form_validation->set_rules("data[$lang->id][content]", 'Содержание', 'required');
            $this->form_validation->set_rules("data[$lang->id][description]", 'Мета-тег «description»', 'trim|max_length[255]');
            $this->form_validation->set_rules("data[$lang->id][keywords]", 'Мета-тег «keywords»', 'trim|max_length[255]');
            $this->form_validation->set_rules("data[$lang->id][img]", 'Изображение', 'trim|max_length[255]');
            $this->form_validation->set_rules("data[$lang->id][img_tmb]", 'Миниатюра', 'trim|max_length[255]');
        }
        $this->form_validation->set_rules('name', 'Название', 'trim|required|max_length[255]|alpha_dash');
        $this->form_validation->set_rules('script', 'Скрипт на странице', 'trim|max_length[4096]');

        return $this->form_validation->run();
    }

    /**
     * Получить страницы (поиск страниц)
     * @param Paginator $paginator - пагинатор
     * @param string $lang_code - языковой код
     * @param string $field_order_by - поле для сортировки
     * @param string $to - направление сортировки
     * @param array $s_text - искомый текст в виде ['field_name'=>'value']
     * @param array|int $ids - список идентификаторов документов
     * @param string $full - применить полнотекстовый поиск (работает только по полю 'content') - сюда передать строку для поиска
     * @param bool $in_boolean_mode - использовать опцию IN BOOLEAN MODE для полнотекстового поиска
     * @return mixed
     */
    public function get_pages(Paginator $paginator, $lang_code = DEFAULT_LANG_CODE, $field_order_by = NULL, $to = NULL, $s_text = NULL, $ids = NULL, $full_text_search = NULL, $in_boolean_mode = TRUE)
    {
        $this->db->start_cache();
        $this->db->from(self::VIEW_DOC_LOCS);
        if (empty($field_order_by))
            $this->db->order_by('name', 'asc');
        else
            $this->db->order_by($field_order_by, empty($to) ? 'asc' : $to);

        if (!empty($s_text)) {
            $x = 0;
            foreach ($s_text as $k => $val) {
                if (!empty($val)) {
                    if ($x == 0)
                        $this->db->like($k, $val);
                    else
                        $this->db->or_like($k, $val);
                }
                $x++;
            }
        }

        if (!empty($ids)) {
            $this->db->where_in('id', $ids);
        }

        if (!empty($full_text_search)) {
            $this->load->library('Morph');
            $q = $this->morph->Words2AllForms($full_text_search);
            $this->db->or_where('match(content_index, content_index_normalize) against ("' . $q . '" ' . ($in_boolean_mode ? 'IN BOOLEAN MODE' : NULL) . ')', NULL, FALSE);
        }

        $query = $this->db->where('lang_code', $lang_code);

        $this->db->stop_cache();
        $paginator->setCountRow($this->db->count_all_results());
        $query = $query->get(self::VIEW_DOC_LOCS, $paginator->getSize(), $paginator->getBeginElement());
        $this->db->flush_cache();
        return $query->result();
    }

    public function get_pageNames($name = NULL, array $ids = NULL, $lang_code = DEFAULT_LANG_CODE)
    {
        $this->db->select('id');
        $this->db->select('name');
        $this->db->select('head');
        $this->db->select('lang_code');

        if (!empty($name)) {
            $result = $this->db->like('name', $name, 'after')->get(self::VIEW_DOC_LOCS)->result();
        } else if (!empty($ids)) {
            $result = $this->db->where_in('id', $ids)->get(self::VIEW_DOC_LOCS)->result();
        } else {
            $result = $this->db->get(self::VIEW_DOC_LOCS)->result();
        }

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

    public function read_pageByID($id)
    {
        $result = $this->db->get_where(self::VIEW_DOC_LOCS, array('id' => $id))->result();
        $res = [];
        foreach ($result as $item) {
            $res[$item->lang_code] = $item;
        }
        return $res;
    }

    public function read_pageByName($name)
    {
        $result = $this->db->get_where(self::VIEW_DOC_LOCS, array('name' => $name))->result();
        $res = [];
        foreach ($result as $item) {
            $res[$item->lang_code] = $item;
        }
        return $res;
    }

    public function create_page($name, $isService, $script, $data)
    {
        $this->clean_last_error();
        $this->db->trans_start();

        if (!$this->db->insert(self::TableName, ['name' => $name, 'is_service' => $isService, 'script' => $script])) {

            $this->db->trans_rollback();
            $this->last_error = $this->db->error()['message'];
            return false;
        }

        $id_doc = $this->db->insert_id();
        $id_loc_doc = [];

        foreach ($data as $lang => $item) {

            $set_data = ['id_doc' => $id_doc, 'id_lang' => $lang, 'content' => $item['content'], 'meta_description' => $item['description'],
                'meta_keywords' => $item['keywords'], 's_content' => $item['scontent'], 'head' => $item['head'], 'img' => base64_encode($item['img']), 'img_tmb' => base64_encode($item['img_tmb'])];

            if (!$this->db->insert(self::TableNameLocs, $set_data)) {
                $this->db->trans_rollback();
                $this->last_error = $this->db->error()['message'];
                return false;
            }
            $id_loc_doc[$this->db->insert_id()] = $item['content'];
        }

        $this->load->library('Morph');

        foreach ($id_loc_doc as $k => $item) {
            $content = strip_tags($item);
            $set_data = ['id_doc_loc' => $k, 'content' => $content, 'content_index' => $this->morph->Words2BaseForm($content)];

            if (!$this->db->insert(self::TableNameIndex, $set_data)) {
                $this->db->trans_rollback();
                $this->last_error = $this->db->error()['message'];
                return false;
            }
        }

        $this->db->trans_complete();
        return $id_doc;
    }

    public function update_page($id, $name = null, $isService = null, $script = null, $data)
    {
        $this->clean_last_error();
        $this->db->trans_start();

        $this->db->set('name', $name);
        $this->db->set('is_service', $isService);
        $this->db->set('script', $script);
        $this->db->where('id', $id);


        if (!$this->db->update(self::TableName)) {
            $this->last_error = $this->db->error()['message'];
            $this->db->trans_rollback();
            return false;
        }

        $ids = [];

        $this->load->library('Morph');

        foreach ($data as $lang => $item) {

            $this->db->where('id_doc', $id);
            $this->db->where('id_lang', $lang);
            $this->db->set('content', $item['content']);
            $this->db->set('meta_description', $item['description']);
            $this->db->set('s_content', $item['scontent']);
            $this->db->set('head', $item['head']);
            $this->db->set('meta_keywords', $item['keywords']);
            $this->db->set('img', base64_encode($item['img']));
            $this->db->set('img_tmb', base64_encode($item['img_tmb']));

            if (!$this->db->update(self::TableNameLocs)) {
                $this->db->trans_rollback();
                $this->last_error = $this->db->error()['message'];
                return false;
            }

            $row = $this->db->get_where(self::TableNameLocs, array('id_doc' => $id, 'id_lang' => $lang))->row();

            $this->db->where('id_doc_loc', $row->id);
            $content = strip_tags($item['content']);
            $this->db->set('content', $content);
            $this->db->set('content_index', $this->morph->Words2BaseForm($content));

            if (!$this->db->update(self::TableNameIndex)) {
                $this->db->trans_rollback();
                $this->last_error = $this->db->error()['message'];
                return false;
            }
        }

        $this->db->trans_complete();
        return true;
    }

    public function delete_page($id)
    {
        $this->db->trans_start();

        $result = $this->db->get_where(self::TableNameLocs, array('id_doc' => $id))->result();
        $ids = [];
        foreach ($result as $item) {
            array_push($ids, $item->id);
        }

        $this->db->where_in('id_doc_loc', $ids);
        if (!$this->db->delete(self::TableNameIndex)) {
            $this->db->trans_rollback();
            $this->last_error = $this->db->error()['message'];
            return false;
        }

        $this->db->where('id', $id);
        if (!$this->db->delete(self::TableName)) {
            $this->db->trans_rollback();
            $this->last_error = $this->db->error()['message'];
            return false;
        }

        $this->db->trans_complete();
        return true;
    }
}