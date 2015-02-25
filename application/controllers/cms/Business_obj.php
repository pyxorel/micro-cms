<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once 'application/utils/base_controller.php';
include_once 'application/utils/utils.php';
include_once 'application/utils/paginator.php';


class Business_obj extends BaseController
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->lang->load('auth');
        parent::is_logged_in(['admin']);

        $this->load->library('doctrinelib');
        $this->_em = $this->doctrinelib->get_entityManager();
    }

    public function index($page = 0)
    {
        $classes = $this->_em->getRepository('Entities\Common_class')->get_common_classes();
        parent::partialViewResult('cms/cms_master', 'cms/business_obj/index', ['classes' => $classes]);
    }

    public function create_common_class_view()
    {
        parent::partialViewResult('cms/cms_master', 'cms/business_obj/common_class_create', ['fields' => $this->_em->getRepository('Entities\Common_class_field')->get_common_class_fields()]);
    }

    public function create_common_class()
    {
        $this->form_validation->set_rules('name', 'Служебное название', 'trim|required|max_length[50]|alpha_dash');
        $this->form_validation->set_rules('loc_name', 'Название', 'trim|required|max_length[50]');
        if ($this->form_validation->run() === FALSE) {
            parent::partialViewResult('cms/cms_master', 'cms/business_obj/common_class_create');
        } else {
            $rep = $this->_em->getRepository('Entities\Common_class');
            if (!$rep->create_common_class($this->input->get_post('name', TRUE), $this->input->get_post('loc_name', TRUE), $this->input->get_post('fields', TRUE))) {
                $this->form_validation->set_custom_error($rep->last_error);
                parent::partialViewResult('cms/cms_master', 'cms/business_obj/common_class_create');
            } else {
                redirect('cms/business_obj');
            }
        }
    }

    public function edit_common_class_view($id)
    {
        parent::partialViewResult('cms/cms_master', 'cms/business_obj/common_class_edit',
            ['obj' => $this->_em->getRepository('Entities\Common_class')->read_common_class($id),
                'fields' => $this->_em->getRepository('Entities\Common_class_field')->get_common_class_fields()
            ]);
    }

    public function edit_common_class()
    {
        $this->form_validation->set_rules('name', 'Служебное название', 'trim|required|max_length[50]|alpha_dash');
        $this->form_validation->set_rules('loc_name', 'Название', 'trim|required|max_length[50]');

        $id = $this->input->get_post('id', TRUE);

        if ($this->form_validation->run() === FALSE) {
            parent::partialViewResult('cms/cms_master', 'cms/business_obj/common_class_edit',
                [
                    'obj' => $this->_em->getRepository('Entities\Common_class')->read_common_class($id),
                    'fields' => $this->_em->getRepository('Entities\Common_class_field')->get_common_class_fields()
                ]);
        } else {
            $rep = $this->_em->getRepository('Entities\Common_class');
            if (!$rep->update_common_class(
                $id,
                $this->input->get_post('name', TRUE),
                $this->input->get_post('loc_name', TRUE),
                $this->input->get_post('fields'))
            ) {
                $this->form_validation->set_custom_error($rep->last_error);
                parent::partialViewResult('cms/cms_master', 'cms/business_obj/common_class_edit',
                    [
                        'obj' => $this->_em->getRepository('Entities\Common_class')->read_common_class($id),
                        'fields' => $this->_em->getRepository('Entities\Common_class_field')->get_common_class_fields()
                    ]);
            } else {
                redirect('cms/business_obj');
            }
        }
    }

    public function delete_common_class($id)
    {
        $this->_em->getRepository('Entities\Common_class')->delete_common_class($id);
        redirect('cms/business_obj');
    }

    public function delete_common_class_field($id)
    {
        $this->_em->getRepository('Entities\Common_class_field')->delete_common_class_field($id);
        redirect('cms/business_obj/fields');
    }

    public function fields($page = 0)
    {
        $fields = $this->_em->getRepository('Entities\Common_class_field')->get_common_class_fields();
        parent::partialViewResult('cms/cms_master', 'cms/business_obj/fields', ['fields' => $fields]);
    }

    private function assoc_array_class()
    {
        $classes = [];
        foreach ($this->_em->getRepository('Entities\Common_class')->get_common_classes() as $item) {
            $classes[$item->name] = $item->name;
        }
        return $classes;
    }

    public function create_common_class_field_view()
    {
        parent::partialViewResult('cms/cms_master', 'cms/business_obj/common_class_field_create', ['classes' => $this->assoc_array_class()]);
    }

    public function get_part_instances($class, $id_link)
    {
        $instances = $this->_em->getRepository('Entities\Instance')->get_view_instances($class, NULL, ['name' => 'asc']);
        echo $this->load->view('cms/business_obj/partial_instances', ['instances' => $instances, 'id_link' => $id_link], TRUE);
    }

    public function create_common_class_field()
    {
        $this->form_validation->set_rules('name', 'Служебное название', 'trim|required|max_length[50]|alpha_dash');
        $this->form_validation->set_rules('loc_name', 'Название', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('type', 'Тип', 'trim|required');
        $this->form_validation->set_rules('extra', 'Дополнительно', 'trim|max_length[255]');
        $this->form_validation->set_rules('unit', 'Единицы', 'trim|max_length[25]');

        if ($this->form_validation->run() === FALSE) {
            parent::partialViewResult('cms/cms_master', 'cms/business_obj/common_class_field_create', ['classes' => $this->assoc_array_class()]);
        } else {
            $rep = $this->_em->getRepository('Entities\Common_class_field');
            if (!$rep->create_common_class_field(
                $this->input->get_post('name', TRUE),
                $this->input->get_post('loc_name', TRUE),
                $this->input->get_post('type', TRUE),
                $this->input->get_post('extra', TRUE),
                $this->input->get_post('unit', TRUE)
            )
            ) {
                $this->form_validation->set_custom_error($rep->last_error);
                parent::partialViewResult('cms/cms_master', 'cms/business_obj/common_class_field_create');
            } else {
                redirect('cms/business_obj/fields');
            }
        }
    }

    public function edit_common_class_field_view($id)
    {
        parent::partialViewResult('cms/cms_master', 'cms/business_obj/common_class_field_edit',
            ['obj' => $this->_em->getRepository('Entities\Common_class_field')->read_common_class_field($id), 'classes' => $this->assoc_array_class()]);
    }

    public function edit_common_class_field()
    {
        $this->form_validation->set_rules('name', 'Служебное навзание', 'trim|required|max_length[50]|alpha_dash');
        $this->form_validation->set_rules('loc_name', 'Название', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('type', 'Тип', 'trim|required');
        $this->form_validation->set_rules('extra', 'Правило', 'trim|max_length[255]');
        $this->form_validation->set_rules('unit', 'Единицы', 'trim|max_length[25]');

        if ($this->form_validation->run() === FALSE) {
            parent::partialViewResult('cms/cms_master', 'cms/business_obj/common_class_field_edit', ['classes' => $this->assoc_array_class()]);
        } else {
            $rep = $this->_em->getRepository('Entities\Common_class_field');
            if (!$rep->update_common_class_field(
                $this->input->get_post('id', TRUE),
                $this->input->get_post('name', TRUE),
                $this->input->get_post('loc_name', TRUE),
                $this->input->get_post('type', TRUE),
                $this->input->get_post('extra', TRUE),
                $this->input->get_post('unit', TRUE))
            ) {
                $this->form_validation->set_custom_error($rep->last_error);
                parent::partialViewResult('cms/cms_master', 'cms/business_obj/common_class_field_edit');
            } else {
                redirect('cms/business_obj/fields');
            }
        }
    }

    public function instances($page = 0)
    {
        $s_class = $this->input->get('s_class', TRUE);
        $s_text = $this->input->get('s_text', TRUE);

        $fields = [];
        if (!empty($s_text)) {
            foreach (explode(';', $s_text) as $f) {
                $v = explode(':', $f);
                if (isset($v[0]) && isset($v[1]))
                    $fields[$v[0]] = $v[1];
            }
        }

        $instances = $this->_em->getRepository('Entities\Instance')->get_view_instances($s_class, $fields, NULL, new Paginator());
        $classes = $this->_em->getRepository('Entities\Instance')->get_common_classes();
        parent::partialViewResult('cms/cms_master', 'cms/business_obj/instances', ['s_class' => $s_class, 's_text' => $s_text, 'instances' => $instances, 'classes' => $classes, 'class_assoc' => $this->assoc_array_class()]);
    }

    public function create_instance_view($id)
    {
        $class = $this->_em->getRepository('Entities\Common_class')->read_common_class($id);
        parent::partialViewResult('cms/cms_master', 'cms/business_obj/instance_create', ['class' => $class, 'id_class' => $id]);
    }

    /**
     * Парсинг формы создания/редактирования экземпляра класса
     * @return array|mixed
     */
    private function parse_instance_form()
    {
        $fields = $this->input->post('fields');
        $files = $this->input->post('files');
        if (!empty($files)) {
            foreach ($files as $k => $f) {
                $fields[$k] = explode(',', trim($f, ', '));
                $files_base64 = [];
                foreach ($fields[$k] as $item) {
                    array_push($files_base64, base64_encode($item));
                }
                $fields[$k] = json_encode($files_base64, JSON_UNESCAPED_UNICODE);
            }
        }

        $fields = array_map(function ($f) {
            if (is_array($f)) {
                return json_encode($f);
            }
            return $f;
        }, $fields);

        $fields = array_map(function ($f) {
            return preg_replace('/("fake",?)/i', "", $f);
        }, $fields);
        return $fields;
    }

    public function create_instance()
    {
        if (!$this->_em->getRepository('Entities\Instance')->create_instance($this->parse_instance_form())) {
        }
        redirect('cms/business_obj/instances');
    }

    public function edit_instance_view($id)
    {
        parent::partialViewResult('cms/cms_master', 'cms/business_obj/instance_edit',
            ['obj' => $this->_em->getRepository('Entities\Instance_view')->read_view_instance($id)]);
    }

    public function edit_instance()
    {
        if (!$this->_em->getRepository('Entities\Instance')->update_instance($this->input->post('id'), $this->parse_instance_form())) {
        }
        redirect('cms/business_obj/instances');
    }

    public function delete_instance($id)
    {
        $this->_em->getRepository('Entities\Instance')->delete_instance($id);
        redirect('cms/business_obj/instances');
    }
}
