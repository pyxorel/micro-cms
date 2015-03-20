<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');
require_once 'application/core/Core_site.php';
require_once 'application/utils/utils.php';
require_once SMARTY_DIR . 'Smarty.class.php';

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
        $this->smarty->compile_dir = APPPATH . 'cache' . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . 'compile' . DIRECTORY_SEPARATOR;
        $this->smarty->cache_dir = APPPATH . 'cache' . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
        $this->smarty->caching = FALSE;
        $this->smarty->debugging = FALSE;
        $this->smarty->compile_check = TRUE;

        $this->smarty->registerPlugin("function", "lang_list", [$this, 'function_lang_list']);
        $this->smarty->registerPlugin("function", "_call_user_function", [$this, 'function__call_user_function']);
        $this->smarty->registerPlugin("function", "lang_current_code", [$this, 'function_lang_current_code']);
        $this->smarty->registerPlugin("function", "menu", [$this, 'function_menu']);
        $this->smarty->registerPlugin("function", "resource", [$this, 'function_resource']);
        $this->smarty->registerPlugin("function", "get_objects", [$this, 'function_get_objects']);
        $this->smarty->registerPlugin("function", "get_pages", [$this, 'function_get_pages']);
        $this->smarty->registerPlugin("function", "page_not_found", [$this, 'function_page_not_found']);
        $this->smarty->registerPlugin("function", "user_objects_group_by", [$this, 'function_user_objects_group_by']);
        $this->smarty->registerPlugin("modifier", "base64", [$this, 'modifier_base64']);
    }

    function get_smarty()
    {
        return $this->smarty;
    }

    function function_page_not_found($params, $smarty)
    {
        $this->_404();
    }

    function _404()
    {
        header("HTTP/1.0 404");
        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");
        $this->smarty->display('_404.tpl');
        die;
    }

    function function__call_user_function($params, $smarty)
    {
        if (!isset($params['class'])) {
            trigger_error("assign: missing 'class' parameter");
        }
        if (!isset($params['func'])) {
            trigger_error("assign: missing 'func' parameter");
        }
        $classLoader = new Doctrine\Common\ClassLoader('User_modules', APPPATH);
        $classLoader->register();
        $result = call_user_func('User_modules\\' . $params['class'] . '::' . $params['func'], isset($params['params']) ? $params['params'] : NULL );
        $smarty->assign($params['out'], $result);
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

        $order = isset($params['order']) ? $params['order'] : NULL;
        $limit = isset($params['limit']) ? $params['limit'] : NULL;

        $core = new Core_site();
        $smarty->assign($params['out'], $core->get_menu($params['name'], $this->_ci->lang_code, $order, $limit));
    }

    function function_get_pages($params, $smarty)
    {
        $core = new Core_site();
        if (isset($params['id_menu'])) {
            $pages = $core->get_pages_by_id_menu($params['id_menu'], $this->_ci->lang_code, TRUE, FALSE);
            $smarty->assign($params['out'], $pages);
            return;
        }

        if (!isset($params['name'])) {
            trigger_error("assign: missing 'name' parameter");
        }

        $menu = $core->get_menu($params['name'], $this->_ci->lang_code);

        if (!empty($menu)) {
            $pages = $core->get_pages_by_id_menu($menu->id, $this->_ci->lang_code, TRUE, FALSE);

            $smarty->assign($params['out'], $pages);
            return;
        }

        $smarty->assign($params['out'], null);
    }

    function function_resource($params, $smarty)
    {
        if (!isset($params['name'])) {
            trigger_error("assign: missing 'name' parameter");
        }
        if (!isset($this->core_site))
            $this->core_site = new Core_site(TRUE, $this->_ci->lang_code);
        return $this->core_site->get_resources($params['name']);
    }


    function function_get_objects($params, $smarty)
    {
        if (!isset($params['name'])) {
            trigger_error("assign: missing 'name' parameter");
        }

        $in = NULL;
        if (isset($params['in'])) {
            $in = $params['in'];
        }

        $fields = NULL;
        if (isset($params['fields'])) {
            $fields = $params['fields'];
        }

        $this->_ci->load->library('doctrinelib');
        $this->_ci->_em = $this->_ci->doctrinelib->get_entityManager();
        $instances = $this->_ci->_em->getRepository('Entities\Instance')->get_view_instances($params['name'], $fields, NULL, NULL, $in);
        $smarty->assign($params['out'], $instances);
    }

    function function_user_objects_group_by($params, $smarty)
    {
        if (!isset($params['name'])) {
            trigger_error("assign: missing 'name' parameter");
        }
        if (!isset($params['objs'])) {
            trigger_error("assign: missing 'objs' parameter");
        }

        $gr = Utils::array_group_by($params['objs'], function ($p) {
            return $p['i']->fields[$p['name']]['value'];
        }, $params['name']);

        Utils::array_sort_key_by($gr, function ($a, $b) {
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });

        $smarty->assign($params['out'], $gr);
    }

    function modifier_base64($string)
    {
        return base64_encode($string);
    }
}

