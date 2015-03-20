<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once 'application/utils/utils.php';

/**
 * Класс расширяющий функциональность CI_Controller
 */
class BaseController extends CI_Controller
{
    private $instance;
    private static $_instance;

    function __construct()
    {
        parent::__construct();
        self::$_instance = parent::get_instance();
        $this->instance = parent::get_instance();
    }

    public function connect_db()
    {
        $db_con = $this->instance->load->database('', TRUE);
        if (empty($db_con->conn_id)) {
            log_message('error', 'Error connect DB.');
            $this->error();
        }
        return TRUE;
    }

    /**
     * Установить язык согласно куку, если кука нет использовать HEADER
     * @param string $lang - язык для установки, вручную, игнорируя кук и HEADER
     * @param array $files - файлы локализации для подгрузки
     * @return bool
     */
    public function set_lang($set_lang = NULL, array $files = ['main'])
    {
        $this->connect_db();

        $this->instance->load->model('cms/Lang_model');
        $langs = $this->instance->Lang_model->get_langs();

        foreach ($langs as $l_item) {
            $langs_array[$l_item->code] = $l_item;
        }
        //из кука
        $lang_code = get_cookie('lang_code');
        //из хедера
        if (empty($lang_code)) {
            $header_lang = $this->instance->input->get_request_header('Accept-Language', TRUE);
            foreach (explode(',', $header_lang) as $item) {
                if (!empty($lang_code)) break;

                foreach ($langs_array as $key => $l) {
                    if (stripos($item, $key) === 0) {
                        $lang_code = $key;
                        break;
                    }
                }
            }
        }
        $this->instance->lang_id = 1;

        if (!empty($set_lang)) {
            $lang_code = $set_lang;
        }

        $lang_obj = NULL;

        if (!empty($lang_code)) {
            foreach ($langs as $l_item) {
                if ($l_item->code == $lang_code) {
                    foreach ($files as $item) {
                        $this->instance->lang->load($item, $l_item->label);
                    }
                    $lang_obj = $l_item;
                    $this->instance->lang_id = $l_item->id;
                    break;
                }

            }
        } else {
            foreach ($langs as $l_item) {
                if ($l_item->code == $lang_code) {
                    $lang_obj = $l_item;
                    break;
                }
            }
        }

        if (empty($lang_obj)) {
            $this->instance->lang->load('main', 'russian');
            $this->instance->lang_name = 'russian';
            $this->instance->lang_name_ = 'Русский';
            $this->instance->lang_code = 'ru';
            $this->instance->langs_array = $langs_array;
        } else {
            $this->instance->lang_name = $lang_obj->text;
            $this->instance->lang_name_ = $lang_obj->label;
            $this->instance->lang_code = $lang_code;
            $this->instance->langs_array = $langs_array;
        }
        return TRUE;
    }

    /**
     * Отдать частичное представление
     * @param string masterPage - мастер страница
     * @param page - страница
     * @param data - данные
     * @param int $status_code - статус код
     * @param string $message_header - сообщение в header-e
     */
    public static function partialViewResult($masterPage, $page, $data = null, $status_code = 200, $message_header = '')
    {
        set_status_header($status_code, $message_header);
        $page_content = self::$_instance->load->view($page, $data, true);
        self::$_instance->load->view($masterPage, array('page_content' => $page_content, 'data' => $data));
    }

    /**
     * Отдать json данные
     * @param $data - json данные
     * @param bool $return - сразу вернуть данные в браузер
     */
    public function jsonResult($data, $return = TRUE)
    {
        $this->instance->output->set_header('Cache-Control: no-cache, must-revalidate');
        $this->instance->output->set_header('Content-Type: application/json; charset=utf-8');
        $this->instance->output->set_header('Expires: ' . date('r', time() + (31536000 /*86400*365*/)));
        $this->instance->output->set_output($data);

        if ($return)
            die($this->instance->output->get_output());
    }

    /**
     * Отдать xml данные
     * @param данные для отдачи
     */
    public function xmlResult($data)
    {
        $this->instance->output->set_header('Cache-Control: no-cache, must-revalidate');
        $this->instance->output->set_header('Content-Type: application/xml; charset=utf-8');
        $this->instance->output->set_header('Expires: ' . date('r', time() + (31536000 /*86400*365*/)));
        $this->instance->output->set_output($data);
    }

    /**
     * Страница 404
     * @example в config/routes.php заменить $route['404_override']='default_controller/_404',
     */
    public static function _404()
    {
        show_404();
    }

    public function add_log_message($message, $ext = '', $trace = '', $level = 'error')
    {
        log_message($level, '======================================================');
        log_message($level, $this->instance->uri->uri_string());
        if ($this->instance->input->get() !== FALSE) log_message($level, trim(print_r($this->instance->input->get(), TRUE)));
        if ($this->instance->input->post() !== FALSE) log_message($level, trim(print_r($this->instance->input->post(), TRUE)));
        (!empty($ext)) ? log_message($level, str_replace('@', $ext, $message)) : log_message($level, $message);
        if (!empty($trace)) log_message($level, $trace);
        log_message($level, '======================================================');
    }

    public static function error($status_code = 500, $message = NULL)
    {
        self::partialViewResult('master', '_500', NULL, $status_code, $message);
        die(self::$_instance->output->get_output());
    }

    /**
     * Проверить авторизован ли пользователь, редирект на страницу логина если не авторизован
     * (иcпользует механизм ION Auth)
     * @param array $group - группа в которую должен входить пользователь
     */
    public function is_logged_in($groups = ['members'])
    {
        if (!$this->instance->ion_auth->logged_in()) {
            if ($this->instance->input->is_ajax_request()) {
                $this->instance->output->set_status_header(401);
                $this->instance->output->set_output(base_url($this->instance->config->item('login_uri', 'ion_auth')));
                die($this->instance->output->get_output());
            } else {
                redirect($this->instance->config->item('login_uri', 'ion_auth'));
            }
        }

        foreach ($groups as $group) {
            if ($this->instance->ion_auth->in_group($group)) return;
        }

        redirect($this->instance->config->item('login_uri', 'ion_auth'));
    }
}
