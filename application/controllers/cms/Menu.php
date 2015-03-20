<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/base_controller.php';
include_once 'application/utils/utils.php';
include_once 'application/core/Core_site.php';

class Menu extends BaseController
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->lang->load('auth');
        parent::is_logged_in(['admin']);
    }

    public function index()
    {
        $this->load->model('cms/Menu_model');
        $tree = $this->Menu_model->get_subTree_withPage(Menu_model::ROOT_ID, TRUE, DEFAULT_LANG_CODE);
        $tree = $tree->get_array(1);
        parent::partialViewResult('cms/cms_master', 'cms/menu/index', array('tree' => json_encode($tree)));
    }

    public function addMenuView($pmenu)
    {
        $this->load->model('cms/Menu_model');
        $this->load->model('cms/Lang_model');
        $this->load->model('cms/Template_model');

        $menu = $this->Menu_model->read_menu($pmenu);
        $this->load->view('cms/menu/addMenu', array('pmenu' => $menu, 'langs' => $this->Lang_model->get_langs(), 'templates' => $this->Template_model->get_templates()));
    }

    public function addMenu()
    {
        $this->load->model('cms/Menu_model');

        $type = $this->input->get_post('type', TRUE);
        $is_service = $this->input->get_post('isService', TRUE);

        $id = $this->Menu_model->create_menu(
            $this->input->get_post('pmenu', TRUE),
            $type == 'rel' ? $this->input->get_post('uri', TRUE) : '',
            (empty($is_service) || $is_service == 0) ? 0 : 1,
            $type == 'abs' ? $this->input->get_post('uri', TRUE) : '',
            $this->input->get_post('sName', TRUE),
            $this->input->get_post('data', TRUE),
            $this->input->get_post('template', TRUE),
            $this->input->get_post('date', TRUE),
            $this->input->get_post('sort', TRUE),
            $this->input->get_post('count_elem', TRUE));
        if (!empty($id)) {
            $this->createCacheMenu();
            $menu = $this->Menu_model->read_menu($id);
            echo json_encode(array('title' => $menu->head, 'key' => $menu->id, 'isFolder' => true));
        } else {
            echo "_ERROR_";
        }
    }

    public function editMenuView($id)
    {
        $this->load->model('cms/Menu_model');
        $this->load->model('cms/Lang_model');
        $this->load->model('cms/Template_model');

        $menu = $this->Menu_model->read_menu($id, NULL);
        $this->load->view('cms/menu/editMenu', array('menu' => $menu, 'langs' => $this->Lang_model->get_langs(), 'templates' => $this->Template_model->get_templates()));
    }

    public function editMenu()
    {
        $this->load->model('cms/Menu_model');
        $id = $this->input->get_post('menu', TRUE);
        $type = $this->input->get_post('type', TRUE);

        if ($this->Menu_model->update_menu(
            $id,
            $type == 'rel' ? $this->input->get_post('uri', TRUE) : '',
            $this->input->get_post('isService', TRUE),
            $type == 'abs' ? $this->input->get_post('uri', TRUE) : '',
            $this->input->get_post('sName', TRUE),
            $this->input->get_post('data', TRUE),
            $this->input->get_post('template', TRUE),
            $this->input->get_post('date', TRUE),
            $this->input->get_post('sort', TRUE),
            $this->input->get_post('count_elem', TRUE))
        ) {
            $this->createCacheMenu();
            $menu = $this->Menu_model->read_menu($id);
            echo json_encode(array('title' => $menu->head, 'key' => $menu->id, 'isFolder' => true));
        } else {
            echo "_ERROR_";
        }
    }

    public function deleteMenu($id)
    {
        $this->load->model('cms/Menu_model');

        if ($id != Menu_model::ROOT_ID) {
            $result = $this->Menu_model->delete_menu($id);
            if ($result === TRUE) {
                $this->createCacheMenu();
                echo "_OK_";
            } else {
                echo $result;
            }
        } else {
            echo "Невозможно удалить корневой элемент!";
        }
    }

    public function deletePage($idMenu, $idPage)
    {
        $this->load->model('cms/Menu_model');

        $result = $this->Menu_model->delete_linkMenuPage($idMenu, $idPage);
        if ($result === TRUE) {
            echo "_OK_";
        } else {
            echo $result;
        }
    }

    public function deleteObject($idMenu, $idInst)
    {
        $this->load->model('cms/Menu_model');

        $result = $this->Menu_model->delete_linkMenuInstance($idMenu, $idInst);
        if ($result === TRUE) {
            echo "_OK_";
        } else {
            echo $result;
        }
    }

    public function deleteGallery($idMenu, $idGallery)
    {
        $this->load->model('cms/Menu_model');

        $result = $this->Menu_model->delete_linkMenuGallery($idMenu, $idGallery);
        if ($result === TRUE) {
            echo "_OK_";
        } else {
            echo $result;
        }
    }

    public function addPageView($pmenu)
    {
        $this->load->view('cms/menu/addPage', array('pmenu' => $pmenu));
    }

    public function addObjectView($pmenu)
    {

        $this->load->view('cms/menu/addObject', array('pmenu' => $pmenu));
    }

    public function addGalleryView($pmenu)
    {
        $this->load->view('cms/menu/addGallery', array('pmenu' => $pmenu));
    }

    public function addPage()
    {
        $pages = $this->input->get_post('page', TRUE);

        if (!empty($pages) && is_array($pages)) {
            $this->load->model('cms/Menu_model');

            $result = $this->Menu_model->add_pages($this->input->get_post('pmenu', TRUE), $pages);

            if ($result === TRUE) {
                $data = [];
                $this->load->model('cms/Page_model');
                foreach ($this->Page_model->get_pageNames(NULL, $pages) as $x => $item) {
                    $data[$x] = array('title' => $item->name, 'key' => "p$item->id");
                }
                echo json_encode($data);
                return;
            } else {
                if (stripos($result, 'duplicate') !== FALSE) {
                    echo 'Документ уже был добавлен!';
                }
                return;
            }
        }
        echo 'Ничего не выбрано!';
    }

    public function addObject()
    {
        $objs = $this->input->get_post('objs', TRUE);

        if (!empty($objs) && is_array($objs)) {
            $this->load->model('cms/Menu_model');

            $result = $this->Menu_model->add_instance($this->input->get_post('pmenu', TRUE), $objs);

            if ($result === TRUE) {
                $data = [];

                $this->load->library('doctrinelib');
                $this->_em = $this->doctrinelib->get_entityManager();
                $instances = $this->_em->getRepository('Entities\Instance')->get_view_instances('sewerage', NULL, NULL, new Paginator(), $objs);
                $this->load->model('cms/Page_model');
                $x = 0;
                foreach ($instances as $item) {
                    $data[$x] = array('title' => $item->fields['name']['value'], 'key' => "o$item->id", 'addClass' => 'tree-icon-obj');
                    $x++;
                }
                echo json_encode($data);
                return;
            } else {
                if (stripos($result, 'duplicate') !== FALSE) {
                    echo 'Объект уже был добавлен!';
                }
                return;
            }
        }
        echo 'Ничего не выбрано!';
    }


    public function addGallery()
    {
        $gall = $this->input->get_post('gallery', TRUE);

        if (!empty($gall) && is_array($gall) == 'array') {

            $this->load->model('cms/Menu_model');
            $result = $this->Menu_model->add_gallereis(
                $this->input->get_post('pmenu', TRUE),
                $gall);

            if ($result === TRUE) {
                $data = array();
                $this->load->model('cms/GalleryModel');
                foreach ($this->GalleryModel->getGalleryNames(NULL, $gall) as $x => $item) {
                    $data[$x] = array('title' => $item->Name, 'key' => "g$item->ID");
                }
                echo json_encode($data);
                return;
            } else {
                echo $result;
                return;
            }
        }
        echo 'Ничего не выбрано!';
    }

    public function listPages($name = null)
    {
        $this->load->model('cms/Page_model');
        $data = $this->Page_model->get_pageNames($name);
        $this->load->view('cms/menu/partialPages', array('pages' => $data));
    }

    public function listObjects($pmenu = null, $name = null)
    {
        $this->load->library('doctrinelib');
        $this->_em = $this->doctrinelib->get_entityManager();
        $instances = $this->_em->getRepository('Entities\Instance')->get_view_instances('sewerage', !empty ($name) ? ['name' => $name . '%'] : NULL, ['name'=>'asc']);

        $this->load->model('cms/Menu_model');
        $tree = $this->Menu_model->get_subTree_withPage($pmenu, FALSE, DEFAULT_LANG_CODE);
        $has = array_filter($tree->get_sub_tree(), function ($i) {
            if ($i['key'][0] == 'o') return true;
        });

        $res = [];
        foreach ($has as $item) {
            $res[substr($item['key'], 1, strlen($item['key']))] = '';
        }

        foreach ($instances as $k => $item) {
            if (array_key_exists($item->id, $res)) {
                unset($instances[$k]);
            }
        }
        $this->load->view('cms/menu/partialObjects', ['objs' => $instances]);
    }

    public function listGallereis($name = null)
    {
        $this->load->model('cms/GalleryModel');
        $data = $this->GalleryModel->getGalleryNames($name);
        $this->load->view('cms/menu/partialGallereis', array('gallereis' => $data));
    }

    public function subElements($idMenu)
    {
        $this->load->model('cms/Menu_model');
        $tree = $this->Menu_model->get_subTree_withPage($idMenu, FALSE);
        echo json_encode($tree->get_sub_tree(), JSON_UNESCAPED_UNICODE);
    }

    public function changePriority($idParentMenu, $idMenu, $idToMenu)
    {
        $this->load->model('cms/Menu_model');
        $result = $this->Menu_model->change_priority($idParentMenu, $idMenu, $idToMenu);
        if ($result === TRUE) {
            $this->createCacheMenu();
            echo "_OK_";
        } else {
            echo $result;
        }
    }

    /**
     * Создать кеш из всего дерева меню.
     * Кеш представляет собой xml файл (для более быстрого построения меню на сайте)
     */
    private function createCacheMenu()
    {
        $this->load->model('cms/Menu_model');
        $this->load->model('cms/Lang_model');
        $this->load->library('Xml_writer');
        $this->load->helper('file');

        foreach ($this->Lang_model->get_langs() as $lang) {
            $tree = $this->Menu_model->get_tree($lang->code);
            write_file(APPPATH . "cache/$lang->code" . '_menu.xml', $tree->createXML(0)->getXml(false));
        }
    }

    /**
     *
     */
    public function list_route()
    {
        $core = new Core_site();
        $output = [];
        foreach ($core->get_list_route_menu() as $item) {
            array_push($output, array('name' => $item, 'uri' => $item));
        }
        parent::jsonResult(json_encode($output));
    }

}


