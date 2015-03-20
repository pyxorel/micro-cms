<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once 'application/core/Core_site.php';
include_once 'application/utils/base_controller.php';
include_once 'application/utils/utils.php';
include_once 'application/utils/custom_paginator.php';

class Site extends BaseController
{
    function __construct()
    {
        parent::__construct();
        parent::set_lang();
    }

    public function index()
    {
        $this->load->library('smartylib');
        $smarty = $this->smartylib->get_smarty();
        $core = new Core_site();
        $params = $core->get_params_route();
        $uri = $this->uri->uri_string();

        $menu = $core->get_menu_from_route($uri . (isset($params['query']) ? $params['query'] : NULL), $this->lang_code, $params);

        if (!empty($menu)) {
            $docs = $core->get_pages_by_id_menu($menu->id, $this->lang_code, TRUE, FALSE);
            $galleries = $core->get_galleries_by_id_menu($menu->id, $this->lang_code, FALSE);
            $objs = $core->get_objects_by_id_menu($menu->id);
        } else {
            $this->smartylib->_404();
        }

        if ($menu->count_elem > 0) {
            $paginator = new Custom_paginator(isset($params['page']) ? $params['page'] : 1, count($menu->menu), $menu->count_elem);
            if ($menu->sort == 'date') {
                $core->sort_menu_by_date($menu);
            }
            $menu->menu = array_slice($menu->menu, $paginator->skip(), $paginator->take());
            $smarty->assign('paginator_menu', $paginator);
        }

        $cur_menu = $core->get_menu_parent($menu->id, $this->lang_code);
        $smarty->assign('base_url', trim(base_url(), '/'));
        $smarty->assign('docs', $docs);
        $smarty->assign('galleries', $galleries);
        $smarty->assign('current_menu', $cur_menu);
        $smarty->assign('this_menu', $menu);
        $smarty->assign('user_objects', $objs);
        $smarty->assign('params', $params);
        $smarty->display($menu->template);
    }

    /**
     * Переключить язык на сайте
     * @param string $lang_code - языковой код
     * @param null $uri - uri для возврата
     */
    function lang($lang_code = DEFAULT_LANG_CODE, $uri = NULL)
    {
        $this->load->library('Ion_auth');
        $this->ion_auth->set_lang($lang_code);
        $this->load->helper('security');
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
        $return_url = $protocol . $_SERVER['HTTP_HOST'] . base64_decode($uri);
        empty($uri) ? redirect('/') : redirect($return_url);
    }
}
