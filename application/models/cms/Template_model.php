<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/utils.php';

class Template_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    private static $TEMPLATE_EXISTS = 'Шаблон с таким именем уже существует';
    private static $TEMPLATE_EXT = '.tpl';

    public $last_error;

    private function valid_unique_name($name)
    {
        $name = str_replace('\\', '', $name) . self::$TEMPLATE_EXT;
        foreach ($this->get_templates() as $item) {
            if ($name == $item) {
                return FALSE;
            }
        }
        return true;
    }

    private function clean_last_error()
    {
        $last_error = NULL;
    }

    public function isValid()
    {
        $this->form_validation->set_rules('name', 'Название', 'trim|required|max_length[50]|alpha_dash');
        $this->form_validation->set_rules('old_name', '', 'trim|required|max_length[50]|alpha_dash');
        $this->form_validation->set_rules('text', 'Текст шаблона', 'required');
        return true;
    }

    public function get_templates()
    {
        $files = Utils::list_files($this->config->item('path_template'), TRUE, 'tpl');
        ksort($files, SORT_NATURAL | SORT_FLAG_CASE);
        return $files;
    }

    public function create_template($name, $text)
    {
        $this->clean_last_error();
        if (!$this->valid_unique_name($name)) {
            $this->last_error = self::$TEMPLATE_EXISTS;
            return FALSE;
        }

        return @file_put_contents($this->config->item('path_template') . $name . self::$TEMPLATE_EXT, $text) !== FALSE;
    }

    public function read_template_byName($name)
    {
        if (file_exists($this->config->item('path_template') . $name)) {
            $result[$name] = @file_get_contents($this->config->item('path_template') . $name);
            return $result;
        }
        return NULL;
    }

    public function update_template($name, $old_name, $text)
    {
        $this->clean_last_error();
        if ($old_name != $name) {
            if ($this->valid_unique_name($name) === FALSE) {
                $this->last_error = self::$TEMPLATE_EXISTS;
                return FALSE;
            }
            @unlink($this->config->item('path_template') . $old_name . self::$TEMPLATE_EXT);
        }
        @file_put_contents($this->config->item('path_template') . $name . self::$TEMPLATE_EXT, $text);
        return TRUE;
    }

    public function delete_template($name)
    {
        $this->clean_last_error();
        if (file_exists($this->config->item('path_template') . $name)) {
            @unlink($this->config->item('path_template') . $name);
        }
        return true;
    }
}