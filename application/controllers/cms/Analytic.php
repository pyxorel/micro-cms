<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/base_controller.php';
include_once 'application/utils/utils.php';

class Analytic extends BaseController {

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->lang->load('auth');
        parent::is_logged_in(['admin']);
    }

	public function index()
	{
		$this->load->helper('file');
		$yandex = file_get_contents($this->config->item('analytic_yandex'));
		$google = file_get_contents($this->config->item('analytic_google'));
		parent::partialViewResult('cms/cms_master', 'cms/analytic/index', array("yandex"=>$yandex, "google"=>$google));
	
	}

	public function create()
	{
		$this->load->helper('file');
		write_file($this->config->item('analytic_yandex'), $this->input->get_post('yandex',FALSE));
		write_file($this->config->item('analytic_google'), $this->input->get_post('google',FALSE));
		redirect("cms/analytic");
	}

}
