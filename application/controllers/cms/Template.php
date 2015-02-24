<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/base_controller.php';
include_once 'application/utils/utils.php';

class Template extends BaseController
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
		$this->load->model('cms/Template_model');
        $data = ['templates' => $this->Template_model->get_templates()];
        parent::partialViewResult('cms/cms_master', 'cms/template/index', $data);
    }

    public function createView()
    {
        parent::partialViewResult('cms/cms_master', 'cms/template/create');
    }

    public function create()
    {
        $this->load->model('cms/Template_model');

        if ($this->Template_model->isValid() == FALSE) {
            parent::partialViewResult('cms/cms_master', 'cms/template/create');
        } else {
            if (!$this->Template_model->create_template(
                $this->input->get_post('name', TRUE),
                $this->input->get_post('text'))
            ) {
                $this->form_validation->set_custom_error($this->Template_model->last_error);
                parent::partialViewResult('cms/cms_master', 'cms/template/create');
            } else {
                redirect('cms/template');
            }
        }
    }

    public function editView($name)
    {
        $this->load->model('cms/Template_model');
        $template = $this->Template_model->read_template_ByName($name);
        if(empty($template)) show_404();

        $data = array('template' => $template);
        parent::partialViewResult('cms/cms_master', 'cms/template/edit', $data);
    }

    /**
     * Редактировать шаблон
     */
    public function edit()
    {
        $this->load->model('cms/Template_model');
		
        if ($this->Template_model->isValid(FALSE) == FALSE) {
            parent::partialViewResult('cms/cms_master', 'cms/template/edit');
        } else {
            if (!$this->Template_model->update_template(
                $this->input->get_post('name', TRUE),
				$this->input->get_post('old_name', TRUE),
                $this->input->get_post('text'))
            ) {
				$this->form_validation->set_custom_error($this->Template_model->last_error);
                parent::partialViewResult('cms/cms_master', 'cms/template/edit');
            } else if ($this->input->get_post('ok') !== NULL) {
                redirect('cms/template/editView/' . $this->input->get_post('name', TRUE) . '.tpl');
            } else {
                redirect('cms/template');
            }
        }
    }

    /**
     * Удалить шаблон
     * @param string $name - имя шаблона
     */
    public function delete($name)
    {
        $this->load->model('cms/Template_model');
        $this->Template_model->delete_template($name);
        redirect('cms/template');
    }

}