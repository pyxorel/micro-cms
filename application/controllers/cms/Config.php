<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once 'application/utils/base_controller.php';

class Config extends BaseController
{
    function index(){
        $this->config->load('email', TRUE);
        parent::partialViewResult('cms/cms_master', 'cms/config/index', ['config' => ['email'=>$this->config->config['email']]]);
    }


    function save(){
        $this->config->load('email', TRUE);
        $this->load->library('MY_config');
        foreach($this->input->post() as $k=>$item){
            $this->my_config->set_item_section('email', $k, $item);
        }
        $this->my_config->save('email');

        redirect('cms/config');
    }
}