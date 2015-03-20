<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');


class MY_Config extends CI_Config
{
    private $ci;

    function __construct()
    {
        parent::__construct();
        $this->ci = &get_instance();
    }

    public function set_item_section($section, $item, $value)
    {
        $this->config[$section][$item] = $value;
    }

    function save($file_name)
    {
        $this->ci->load->helper('file');
        $str = "<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');\n";
        foreach ($this->config[$file_name] as $k => $item) {
            $str .= "\$config['$k'] = '" . addslashes($item) . "';\n";
        }

        $path = APPPATH . 'config' . DIRECTORY_SEPARATOR . $file_name . '.php';

        if (file_exists($path)) {
            write_file($path, $str);
        } else {
            show_error('Config file not exists.');
        }

        return TRUE;
    }
}
