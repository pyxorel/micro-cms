<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/base_controller.php';
include_once 'application/utils/paginator.php';
include_once 'application/utils/utils.php';

class Gallery extends BaseController
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->lang->load('auth');
        parent::is_logged_in(['admin']);
    }

    public function index($page = 0)
    {
        $this->load->library('pagination');
        $this->load->model('cms/GalleryModel');

        $paginator = new Paginator($page);

        $data = array('galleries' => $this->GalleryModel->getGallereis($paginator));

        $config['base_url'] = site_url('cms/gallery/index');
        $config['total_rows'] = $paginator->getCountRow();
        $config['per_page'] = $paginator->getSize();

        $config['uri_segment'] = 4;
        $config['cur_tag_open'] = "<li class=\"active\"><a>";
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['last_link'] = 'Последняя';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['first_link'] = 'Первая';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        parent::partialViewResult('cms/cms_master', 'cms/gallery/index', $data);

    }

    public function createView()
    {
        parent::partialViewResult('cms/cms_master', 'cms/gallery/create', null);
    }

    public function addItemView($idGallery)
    {
        $this->load->model('cms/Lang_model');
        $data = array('langs' => $this->Lang_model->get_langs(),
            'gallery' => $idGallery, "tmb_size" => $this->config->item('tmb_size'));

        echo $this->load->view('cms/gallery/addItem', $data, true);
    }

    public function addItem()
    {
        $this->load->model('cms/GalleryItemModel');
        $this->load->model('cms/Lang_model');
        $data = $this->input->get_post('data', TRUE);
        if ($this->GalleryItemModel->isValid() == FALSE) {
            echo 'ERROR';
        } else {
            if ($this->GalleryItemModel->createItem($this->input->get_post('id_gallery', TRUE), $data)) {
                /*$this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->config->item('path_res') . $uri;
                $config['maintain_ratio'] = TRUE;

                if ($this->input->get_post('tmb', TRUE) !== FALSE) {
                    foreach ($this->input->get_post('tmb', TRUE) as $item) {
                        $config['new_image'] = $this->config->item('path_tmb_res') . "tmb_$item" . "_$uri";
                        $parts = preg_split("/[_]+/", $item);
                        $config['width'] = $parts[0];
                        $config['height'] = $parts[1];
                        $this->image_lib->initialize($config);
                        $this->image_lib->resize();
                    }
                }*/
                echo 'OK';
            } else {
                echo 'ERROR';
            }
        }
    }

    public function create()
    {
        $this->load->model('cms/GalleryModel');
        if ($this->GalleryModel->isValid() == FALSE) {
            parent::partialViewResult('cms/cms_master', 'cms/gallery/create', null);
        } else {
            if ($this->GalleryModel->createGallery(
                $this->input->get_post('name', TRUE),
                $this->input->get_post('description', TRUE)
            )
            ) {
                redirect('cms/gallery');
            } else {
                parent::partialViewResult('cms/cms_master', 'cms/gallery/create', null);
            }
        }
    }

    public function editView($id)
    {
        $this->load->model('cms/GalleryModel');
        $this->load->model('cms/GalleryItemModel');

        $gallery = $this->GalleryModel->readGalleryByID($id);
        $imgs = $this->load->view('cms/gallery/partialImg', array('gallery' => $gallery, 'imgs' => $this->GalleryItemModel->getItems($id)), true);
        $data = array('gallery' => $this->GalleryModel->readGalleryByID($id), 'imgs' => $imgs);
        parent::partialViewResult('cms/cms_master', 'cms/gallery/edit', $data);
    }

    public function editItemView($idGallery, $id)
    {
        $this->load->model('cms/GalleryItemModel');
        $this->load->model('cms/Lang_model');
        $data = array('gallery' => $idGallery, 'langs'=>$this->Lang_model->get_langs(),  'item' => $this->GalleryItemModel->readItemByID($id));
        echo $this->load->view('cms/gallery/editItem', $data, true);
    }

    public function editItem()
    {
        $this->load->model('cms/GalleryItemModel');
        $this->load->model('cms/Lang_model');
        $data = $this->input->get_post('data', TRUE);

        if ($this->GalleryItemModel->isValid() == FALSE) {
            echo 'ERROR';
        } else {
            if ($this->GalleryItemModel->updateItem($this->input->get_post('id', TRUE), $data)) {
                echo 'OK';
            } else {
                echo 'ERROR';
            }
        }
    }

    public function edit()
    {
        $this->load->model('cms/GalleryModel');

        if ($this->GalleryModel->isValid(FALSE) == FALSE) {
            parent::partialViewResult('cms/cms_master', 'cms/gallery/edit', null);
        } else {

            if ($this->GalleryModel->updateGallery(
                $this->input->get_post('id', TRUE),
                $this->input->get_post('name', TRUE),
                $this->input->get_post('description', TRUE))
            ) {
                redirect('cms/gallery');
            } else {
                parent::partialViewResult('cms/cms_master', 'cms/gallery/edit', null);
            }
        }
    }

    public function delete($id)
    {
        $this->load->model('cms/GalleryModel');
        if ($this->GalleryModel->deleteGallery($id)) {
            redirect('cms/gallery');
        }
    }

    public function deleteItem($idGallery, $id)
    {
        $this->load->model('cms/GalleryItemModel');
        if ($this->GalleryItemModel->deleteItem($id)) {
            redirect('cms/gallery/editView/' . $idGallery);
        }
    }
}