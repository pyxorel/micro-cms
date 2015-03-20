<?php
include_once 'application/utils/dbMessage.php';
include_once 'application/utils/tree.php';
include_once 'application/models/cms/Page_model.php';
include_once 'application/models/cms/Gallerymodel.php';
include_once 'application/models/cms/Galleryitemmodel.php';

class Menu_model extends CI_Model
{
    const TableName = 'menu';
    const TableNameLocs = 'menu_locs';
    const TableNameTree = 'menu_tree';
    const TableNameMenuInstance = 'menu_instance';
    const TableNameMenuPage = 'menu_document';
    const TableNameMenuGallery = 'menu_gallery';
    const VIEW_MENU_LOCS = 'view_menu_locs';

    const ROOT_ID = 1;

    public $last_error;

    private function clean_last_error()
    {
        $last_error = NULL;
    }

    function __construct()
    {
        $this->load->database();
        parent::__construct();
    }

    public function get_pagesByMenuID($id, $lang_code = null, $includeContent = FALSE, $first=TRUE, Paginator $paginator = NULL)
    {
        $this->db->start_cache();

        if (!$includeContent) {
            $this->db->select('id, name, head, lang_code, id_lang');
        } else {
            $this->db->select('*');
        }

        $this->db->from(Page_model::VIEW_DOC_LOCS);
        $this->db->join(self::TableNameMenuPage, Page_model::VIEW_DOC_LOCS . '.id = menu_document.IDDoc');
        $this->db->where('IDMenu', $id);

        if (!empty($lang_code)) {
            $this->db->where('lang_code', $lang_code);
        }

        $query = $this->db->order_by('name', 'asc');

        if($first){
            return $query->first_row();
        }

        $this->db->stop_cache();

        if ($paginator == NULL) {
            $this->db->flush_cache();
            return $query->get()->result();
        } else {
            $paginator->setCountRow($this->db->count_all_results());
            $result = $this->db->limit($paginator->getSize(), $paginator->getBeginElement())->get()->result();
            $this->db->flush_cache();
            return $result;
        }
    }

	public function get_galleriesByMenuID($id, $lang_code = NULL, $first=TRUE)
    {
        $this->db->select('*');
        $this->db->from(GalleryItemModel::VIEW_GALL_LOCS);
        $this->db->join(self::TableNameMenuGallery, GalleryItemModel::VIEW_GALL_LOCS . '.IDGallery = menu_gallery.IDGallery');
        $query = $this->db->where('IDMenu', $id);

        if (!empty($lang_code)) {

            $this->db->where('lang_code', $lang_code);
        }

        if($first){
            return $query->get()->first_row();
        }

        return $query->get()->result();
    }

    public function get_instancesByMenuID($id)
    {
        $this->db->select('id_instance');
        $this->db->from(self::TableNameMenuInstance);
        $query = $this->db->where('id_menu', $id);

        return $query->get()->result();
    }

    public function get_galleryByMenuID($id)
    {
        $this->load->database();
        $this->db->select('id, name, description');
        $this->db->from(GalleryModel::TableName);
        $this->db->join(self::TableNameMenuGallery, 'galleries.ID = menu_gallery.IDGallery');
        $this->db->where('IDMenu', $id);
        return $this->db->get()->result();
    }

    /**
     * Получить поддерево из пунктов меню + все документы для каждого из меню
     * @param int $id - идентификатор узла от которого необходимо построить под дерево (родитель)
     * @param bool $includeRoot - включить корневой элемент?
     * @return Tree
     */
    public function get_subTree_withPage($id, $includeRoot = FALSE, $lang_code = DEFAULT_LANG_CODE)
    {
        $tree = new Tree();

        if ($includeRoot) {
            $tree->addItem(self::ROOT_ID, 0, self::read_menu(self::ROOT_ID));
        }

        $this->db->select('id, name, head, lang_code, id_lang, sort, date, count_elem, is_service');
        $this->db->from(self::VIEW_MENU_LOCS);
        $this->db->join(self::TableNameTree, 'view_menu_locs.id = menu_tree.IDChild');
        $this->db->where('menu_tree.IDParent', $id);

        if (empty($lang_code)) {
            $query = $this->db->order_by('priority', 'asc')->get();
        } else {
            $this->db->distinct();
            $this->db->where('lang_code', $lang_code);
            $query = $this->db->order_by('priority', 'asc')->get();
        }

        foreach ($query->result() as $item) {
            $tree->addItem($item->id, $includeRoot ? self::ROOT_ID : 0, $item);
        }

        foreach ($this->get_pagesByMenuID($id, NULL, FALSE, FALSE) as $item) {
            $tree->addItem("p$item->id", $includeRoot ? self::ROOT_ID : 0, $item);
        }

        foreach ($this->get_galleryByMenuID($id) as $item)
        {
            $tree->addItem("g$item->id", $includeRoot ? self::ROOT_ID : 0, $item);
        }

        $id_inst = $this->get_instancesByMenuID($id);
        $this->load->library('doctrinelib');
        $this->_em = $this->doctrinelib->get_entityManager();

        $ids = [];
        foreach($id_inst as $item) {
            array_push($ids, $item->id_instance);
        }

        if(!empty($ids)) {
            $instances = $this->_em->getRepository('Entities\Instance')->get_view_instances('sewerage', NULL, NULL, NULL, $ids);

            foreach ($instances as $item) {
                $std_class = new stdClass();
                $std_class->id = $item->id;
                $std_class->name = $item->fields['name']['value'];
                $tree->addItem("o$item->id", $includeRoot ? self::ROOT_ID : 0, $std_class);
            }
        }

        return $tree;
    }

    /**
     * Получить полное дерево из пунктов меню
     * @return Tree
     */
    public function get_tree($lang_code = DEFAULT_LANG_CODE)
    {
        $tree = new Tree();
        $tree->addItem(self::ROOT_ID, 0, self::read_menu(self::ROOT_ID, $lang_code));

        $list = array(self::ROOT_ID); //начать с корня

        $last = 0;
        while (!empty($list)) {
            $last = array_shift($list);

            $this->db->select('id, name, url, is_service, service_name, priority, head, lang_code, id_lang, template, sort, date, count_elem');
            $this->db->from(self::VIEW_MENU_LOCS);
            $this->db->join(self::TableNameTree, self::VIEW_MENU_LOCS . '.id = menu_tree.IDChild');
            $this->db->where('menu_tree.IDParent', $last);
            $result = $this->db->order_by('priority', 'asc')->get()->result();

            $res = [];
            foreach ($result as $item) {
                if (array_key_exists($item->lang_code, $res)) {
                    array_push($res[$item->lang_code], $item);
                } else {
                    $res[$item->lang_code] = [$item];
                }
            }

            if (!empty($res)) {
                foreach ($res[$lang_code] as $item) {
                    array_push($list, $item->id);
                    $tree->addItem($item->id, $last, $item);
                }
            }
        }

        return $tree;
    }

    public function add_pages($idMenu, $idPages)
    {
        $this->db->trans_start();
        foreach ($idPages as $item) {
            if (!$this->db->insert(self::TableNameMenuPage, array('IDMenu' => $idMenu, 'IDDoc' => $item))) {
                return $this->db->error()['message'];
            }
        }
        $this->db->trans_complete();
        return TRUE;
    }

    public function add_instance($idMenu, $idInsts)
    {
        $this->db->trans_start();
        foreach ($idInsts as $item) {
            if (!$this->db->insert(self::TableNameMenuInstance, array('id_menu' => $idMenu, 'id_instance' => $item))) {
                return $this->db->error()['message'];
            }
        }
        $this->db->trans_complete();
        return TRUE;
    }

    public function add_gallereis($idMenu, $idGallereis)
    {
        $this->db->trans_start();
        foreach ($idGallereis as $item) {
            if (!$this->db->insert(self::TableNameMenuGallery, array('IDMenu' => $idMenu, 'IDGallery' => $item))) {
                return $this->db->error()['message'];
            }
        }
        $this->db->trans_complete();
        return TRUE;
    }

    public function isValid($isCreate = TRUE)
    {
        if (!$isCreate) {
            $this->form_validation->set_rules('id', 'ID', 'required');
        }

        $this->form_validation->set_rules('name', 'Название', 'trim|required|xss_clean');
        $this->form_validation->set_rules('pmenu', 'Родительское меню', 'xss_clean');
        $this->form_validation->set_rules('isServce', 'Служебное меню', 'xss_clean');
        $this->form_validation->set_rules('uri', 'URL адрес', 'trim|xss_celan');
        $this->form_validation->set_rules('ServceName', 'Служебное название', 'trim|alpha_dash|xss_clean');
        return $this->form_validation->run();
    }

    public function create_menu($idParent, $name, $isService, $url, $serviceName, $data, $template, $date=null, $sort= null, $count_elem=null)
    {

        $insert_data = ['name' => $name, 'is_service' => $isService, 'url' => $url, 'service_name' => $serviceName, 'template'=>$template,
        'date'=>$date, 'sort'=>$sort, 'count_elem'=>$count_elem];
        $insert_data['priority'] = $this->db->select_max('priority')->from(self::TableName)->get()->row()->priority + 1;

        $this->db->trans_start();
        if ($this->db->insert(self::TableName, $insert_data)) {
            $id = $this->db->insert_id();

            foreach ($data as $lang => $item) {

                $set_data = ['id_menu' => $id, 'id_lang' => $lang, 'head' => $item['head']];

                if (!$this->db->insert(self::TableNameLocs, $set_data)) {
                    $this->db->trans_rollback();
                    $this->last_error = $this->db->error()['message'];
                    return false;
                }
            }

            if ($this->db->insert(self::TableNameTree, array('IDParent' => $idParent, 'IDChild' => $id))) {
                $this->db->trans_complete();
                return $id;
            } else {
                $this->db->trans_rollback();
                $this->last_error = $this->db->error()['message'];
                return false;
            }
        } else {
            $this->db->trans_rollback();
            $this->last_error = $this->db->error()['message'];
            return false;
        }
    }

    public function read_menu($id, $lang_code = DEFAULT_LANG_CODE)
    {
        $result = $this->db->get_where(self::VIEW_MENU_LOCS, array('id' => $id))->result();
        $res = [];
        foreach ($result as $item) {
            $res[$item->lang_code] = $item;
        }

        return !empty($lang_code) ? $res[$lang_code] : $res;
    }

    public function read_menuByName($name, $lang_code = DEFAULT_LANG_CODE)
    {
        $result = $this->db->get_where(self::VIEW_RES_LOCS, array('name' => $id))->result();
        $res = [];
        foreach ($result as $item) {
            $res[$item->lang_code] = $item;
        }

        return !empty($lang_code) ? $res[$lang_code] : $res;
    }


    public function update_menu($id, $name = null, $isService = null, $url = null, $serviceName = null, $data=null, $template=null, $date=null, $sort= null, $count_elem=null)
    {
        $this->clean_last_error();
        $this->db->trans_start();

        $this->db->set('name', $name);
        $this->db->set('url', $url);
        $this->db->set('is_service', $isService);
        $this->db->set('service_name', $serviceName);
        $this->db->set('template', $template);
        $this->db->set('date', $date);
        $this->db->set('sort', $sort);
        $this->db->set('count_elem', $count_elem);
        $this->db->where('id', $id);

        if (!$this->db->update(self::TableName)) {
            $this->last_error = $this->db->error()['message'];
            return false;
        }

        foreach ($data as $lang => $item) {

            $this->db->where('id_menu', $id);
            $this->db->where('id_lang', $lang);
            $this->db->set('head', $item['head']);

            if (!$this->db->update(self::TableNameLocs)) {

                $this->db->trans_rollback();
                $this->last_error = $this->db->error()['message'];
                return false;
            }
        }

        $this->db->trans_complete();
        return true;
    }

    public function delete_menu($id)
    {

        $list = array($id); //начать с выбранного элемента

        $listDelete = array($id); //список из элементов для удаления снизу вверх

        while (!empty($list)) {
            $last = array_shift($list);

            $query = $this->db->get_where(self::TableNameTree, array('IDParent' => $last));

            foreach ($query->result() as $item) {
                array_push($list, $item->IDChild);
                array_push($listDelete, $item->IDChild);
            }
        }

        $message = '';
        $this->db->trans_start();

        //удалить элементы
        foreach ($listDelete as $item) {

            $this->db->where('id', $item);
            if (!$this->db->delete(self::TableName)) {
                $message .= $this->db->error()['message'];
            }
        }

        if (!empty($message)) {
            return $message;
        }

        $this->db->trans_complete();

        return TRUE;
    }

    public function delete_linkMenuPage($idMenu, $idPage)
    {
        $this->db->where('IDMenu', $idMenu);
        $this->db->where('IDDoc', $idPage);
        if (!$this->db->delete(self::TableNameMenuPage)) {
            return $this->db->error()['message'];
        }
        return TRUE;
    }

    public function delete_linkMenuInstance($idMenu, $idInst)
    {
        $this->db->where('id_menu', $idMenu);
        $this->db->where('id_instance', $idInst);
        if (!$this->db->delete(self::TableNameMenuInstance)) {
            return $this->db->error()['message'];
        }
        return TRUE;
    }

    public function delete_linkMenuGallery($idMenu, $idGallery)
    {
        $this->db->where('IDMenu', $idMenu);
        $this->db->where('IDGallery', $idGallery);
        if (!$this->db->delete(self::TableNameMenuGallery)) {
            return $this->db->error()['message'];
        }
        return TRUE;
    }

    public function change_priority($idParentMenu, $idToMenu, $idSelectMenu)
    {
        $this->db->select('id');
        $this->db->from(self::TableName);
        $this->db->join(self::TableNameTree, 'menu.ID = menu_tree.IDChild');
        $this->db->where('menu_tree.IDParent', $idParentMenu);
        $this->db->order_by('priority', 'asc');

        $children = $this->db->get()->result();
        $res = array();
        foreach ($children as $item) {
            array_push($res, $item->id);
        }
        $children = $res;

        $x = 0;
        foreach ($children as $item) {
            //в начало
            if ($item == $idToMenu && $x == 0) {
                $lkey = array_search($idSelectMenu, $children);
                unset($children[$lkey]);
                array_unshift($children, $idSelectMenu);
                break;
            } else if ($item == $idToMenu && $x > 0) {
                $lkey = array_search($idSelectMenu, $children);
                unset($children[$lkey]);
                $part = array_slice($children, 0, $x);
                array_push($part, $idSelectMenu);
                $children = array_merge($part, array_slice($children, $x, count($children) - $x));
                break;
            }
            $x++;
        }

        $this->db->trans_start();
        $message = '';

        foreach ($children as $key => $item) {
            $this->db->set('Priority', $key);
            $this->db->where('ID', $item);
            if (!$this->db->update(self::TableName)) {
                $message .= $this->db->error()['message'];
            }
        }

        if (!empty($message)) {
            return $message;
        }

        $this->db->trans_complete();

        return TRUE;
    }
}