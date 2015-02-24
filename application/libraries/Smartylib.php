<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');
require_once 'application/core/Core_site.php';
require_once(SMARTY_DIR . 'Smarty.class.php');

class Smartylib
{

    private $_ci;
    private $smarty;
    private $core_site;

    function __construct()
    {
        $this->_ci = &get_instance();

        $this->smarty = new Smarty();
        $this->smarty->template_dir = $this->_ci->config->item('path_template');
        $this->smarty->compile_dir = APPPATH . 'cache' . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR .'compile' . DIRECTORY_SEPARATOR;
        $this->smarty->cache_dir = APPPATH . 'cache' . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR .'cache' . DIRECTORY_SEPARATOR;
        $this->smarty->caching = FALSE;
        $this->smarty->debugging = FALSE;
		$this->smarty->compile_check = false; 

        $this->smarty->registerPlugin("function", "lang_list", [$this, 'function_lang_list']);
        $this->smarty->registerPlugin("function", "lang_current_code", [$this, 'function_lang_current_code']);
        $this->smarty->registerPlugin("function", "menu", [$this, 'function_menu']);
        $this->smarty->registerPlugin("function", "resource", [$this, 'function_resource']);
        $this->smarty->registerPlugin("modifier", "base64", [$this, 'modifier_base64']);
    }

    function get_smarty()
    {
        return $this->smarty;
    }

    function function_lang_list($params, $smarty)
    {
        $smarty->assign($params['out'], $this->_ci->langs_array);
    }

    function function_lang_current_code($params, $smarty)
    {
        return $this->_ci->lang_code;
    }

    function function_menu($params, $smarty)
    {
        if (!isset($params['name'])) {
            trigger_error("assign: missing 'name' parameter");
        }
        $core = new Core_site();
        $smarty->assign($params['out'], $core->get_menu($params['name'], $this->_ci->lang_code));
    }

    function function_resource($params, $smarty)
    {
        if (!isset($params['name'])) {
            trigger_error("assign: missing 'name' parameter");
        }
        if(!isset($this->core_site))
            $this->core_site = new Core_site(TRUE, $this->_ci->lang_code);
        return $this->core_site->get_resources($params['name']);
    }

    function modifier_base64($string)
    {
        return base64_encode($string);
    }
}

