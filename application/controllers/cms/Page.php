<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/base_controller.php';
include_once 'application/utils/paginator.php';
include_once 'application/utils/utils.php';
include_once 'application/core/Core_site.php';

class Page extends BaseController
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->lang->load('auth');
        parent::is_logged_in(['admin']);
    }

    public function index($page = 0, $field = 'name', $order = 'asc', $s_text = NULL)
    {
        $this->load->library('pagination');
        $this->load->model('cms/Page_model');
        $s_text = empty($s_text) ? $this->input->get('s_text', TRUE) : $s_text;

        $paginator = new Paginator($page);
        $data = array('pages' => $this->Page_model->get_pages($paginator, DEFAULT_LANG_CODE, $field, $order, ['head' => $s_text, 'name'=>$s_text]),
            'to' => $order,
            'page' => $page,
            'field' => $field,
            's_text' => $s_text);

        Paginator::initPaginator('cms/page/index', $paginator, $this);
        parent::partialViewResult('cms/cms_master', 'cms/page/index', $data);
    }

    public function createView()
    {
        $this->load->model('cms/Lang_model');
        $this->load->model('cms/Template_model');
        $data = array('langs' => $this->Lang_model->get_langs(), 'templates' => $this->Template_model->get_templates());
        $this->data = $data;
        parent::partialViewResult('cms/cms_master', 'cms/page/create', $data);
    }

    public function create()
    {
        $this->load->model('cms/Page_model');
        $this->load->model('cms/Lang_model');
        $this->load->model('cms/Template_model');

        $data = array('langs' => $this->Lang_model->get_langs(), 'templates' => $this->Template_model->get_templates());
        $this->data = $data;

        $data_content = $this->input->get_post('data', FALSE);
        Core_site::_remove_base_url_href_src($data_content, 'content');

        if ($this->Page_model->isValid() == FALSE) {
            parent::partialViewResult('cms/cms_master', 'cms/page/create', $data);
        } else {
            if (!$this->Page_model->create_page(
                $this->input->get_post('name', TRUE),
                $this->input->get_post('service', TRUE),
                $this->input->get_post('script'),
                $data_content)
            ) {
                parent::partialViewResult('cms/cms_master', 'cms/page/create', $data);
            } else {
                redirect('cms/page');
            }
        }
    }

    public function editView($id)
    {
        $this->load->model('cms/Page_model');
        $this->load->model('cms/Lang_model');
        $this->load->model('cms/Template_model');

        $data_content = $this->Page_model->read_pageByID($id);
        Core_site::_add_base_url_href_src($data_content, 'content');

        $data = array('page' => $data_content, 'langs' => $this->Lang_model->get_langs(), 'templates' => $this->Template_model->get_templates());
        $this->data = $data;
        parent::partialViewResult('cms/cms_master', 'cms/page/edit', $data);
    }

    /**
     * Редактировать страницу
     */
    public function edit()
    {
        $this->load->model('cms/Page_model');
        $this->load->model('cms/Lang_model');
        $this->load->model('cms/Template_model');

        $id = $this->input->get_post('id', TRUE);
        $data = array('page' => $this->Page_model->read_pageByID($id), 'langs' => $this->Lang_model->get_langs(), 'templates' => $this->Template_model->get_templates());
        $this->data = $data;

        $data_content = $this->input->get_post('data', FALSE);
        Core_site::_remove_base_url_href_src($data_content, 'content');

        if ($this->Page_model->isValid(FALSE) == FALSE) {
            parent::partialViewResult('cms/cms_master', 'cms/page/edit', $data);
        } else {
            if (!$this->Page_model->update_page(
                $id,
                $this->input->get_post('name', TRUE),
                $this->input->get_post('service', TRUE),
                $this->input->get_post('script'),
                $data_content
            )
            ) {
                parent::partialViewResult('cms/cms_master', 'cms/page/edit', $data);
            } else if ($this->input->get_post('ok') !== NULL) {
                redirect('cms/page/editView/' . $this->input->get_post('id', TRUE));
            } else {
                redirect('cms/page');
            }
        }
    }

    /**
     * Удалить страницу
     * @param int $id - идентификатор страницы
     */
    public function delete($id)
    {
        $this->load->model('cms/Page_model');
        $this->Page_model->delete_page($id);
        redirect('cms/page');
    }

}