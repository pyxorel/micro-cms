<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/base_controller.php';
include_once 'application/utils/utils.php';

class Resource extends BaseController
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
        $this->load->model('cms/Resource_model');
        $data = array('resources' => $this->Resource_model->get_resources(DEFAULT_LANG_CODE));
        parent::partialViewResult('cms/cms_master', 'cms/resource/index', $data);
    }

    public function createView()
    {
        $this->load->model('cms/Lang_model');
        $data = array('langs' => $this->Lang_model->get_langs());
        parent::partialViewResult('cms/cms_master', 'cms/resource/create', $data);
    }

    public function create()
    {
        $this->load->model('cms/Resource_model');
        $this->load->model('cms/Lang_model');

        $data = array('langs' => $this->Lang_model->get_langs());

        if ($this->Resource_model->isValid() == FALSE) {
            parent::partialViewResult('cms/cms_master', 'cms/resource/create', $data);
        } else {
            if (!$this->Resource_model->create_resource(
                $this->input->get_post('name', TRUE),
                $this->input->get_post('data', TRUE)
            )
            ) {
                if (strpos($this->Resource_model->last_error, 'Duplicate') !== FALSE)
                    $this->form_validation->set_custom_error('Такой ресурс уже существует.');
                parent::partialViewResult('cms/cms_master', 'cms/resource/create', $data);
            } else {
                redirect('cms/resource/index');
            }
        }
    }

    public function editView($id)
    {
        $this->load->model('cms/Resource_model');
        $this->load->model('cms/Lang_model');

        $data = array('resource' => $this->Resource_model->read_resourceByID($id), 'langs' => $this->Lang_model->get_langs());
        parent::partialViewResult('cms/cms_master', 'cms/resource/edit', $data);
    }


    public function edit()
    {
        $this->load->model('cms/Resource_model');
        $this->load->model('cms/Lang_model');
        $id = $this->input->get_post('id', TRUE);
        $data = array('resource' => $this->Resource_model->read_resourceByID($id), 'langs' => $this->Lang_model->get_langs());

        if ($this->Resource_model->isValid(FALSE) == FALSE) {
            parent::partialViewResult('cms/cms_master', 'cms/resource/edit', $data);

        } else {
            if (!$this->Resource_model->update_resource(
                $id,
                $this->input->get_post('name', TRUE),
                $this->input->get_post('data', TRUE))
            ) {
                if (strpos($this->Resource_model->last_error, 'Duplicate') !== FALSE)
                    $this->form_validation->set_custom_error('Такой ресурс уже существует.');
                parent::partialViewResult('cms/cms_master', 'cms/resource/edit', $data);

            } else if ($this->input->get_post('ok', TRUE) !== NULL) {
                redirect('cms/resource/editView/' . $this->input->get_post('id', TRUE));
            } else {
                redirect('cms/resource/index');
            }
        }
    }

    public function delete($id)
    {
        $this->load->model('cms/Resource_model');
        $this->Resource_model->delete_resource($id);
        redirect('cms/resource/index');
    }

}
