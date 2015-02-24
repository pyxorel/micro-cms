<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/core/Core_site.php';
include_once 'application/utils/base_controller.php';

class Site extends BaseController
{

    function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
        parent::set_lang();
    }

    public function index()
    {
        $core = new Core_site();
        $uri = $this->uri->uri_string();
        $menu = $core->get_menu_from_route($uri, $this->lang_code);
        if (!empty($menu)) {
            $docs = $core->get_pages_by_id_menu($menu->id, $this->lang_code, TRUE, FALSE);
            $galleries = $core->get_galleries_by_id_menu($menu->id, $this->lang_code, FALSE);
        }

        $this->load->library('smartylib');
        $smarty = $this->smartylib->get_smarty();

        if (empty($docs) && empty($galleries)) {
            header("HTTP/1.0 404");
            header("HTTP/1.1 404 Not Found");
            header("Status: 404 Not Found");
            $smarty->display('_404.tpl');
            die;
        }

        $cur_menu = $core->get_menu_parent($menu->id, $this->lang_code);
        $smarty->assign('base_url', trim(base_url(), '/'));
        $smarty->assign('docs', $docs);
        $smarty->assign('galleries', $galleries);
        $smarty->assign('current_menu', $cur_menu);
        $smarty->display($menu->template);
    }

    function lang($lang_code = DEFAULT_LANG_CODE, $uri = NULL)
    {
        $this->load->library('Ion_auth');
        $this->ion_auth->set_lang($lang_code);
        $this->load->helper('security');
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
        $return_url = $protocol . $_SERVER['HTTP_HOST'] . base64_decode($uri);
        empty($uri) ? redirect('/') : redirect($return_url);
    }
}
