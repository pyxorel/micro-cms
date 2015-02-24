<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');

class MY_Form_validation extends CI_Form_validation
{

    function __construct()
    {
        parent::__construct();
        $this->CI->lang->load('MY_form_validation');
    }

    public function set_custom_error($val)
    {
        array_push($this->_error_array, $val);
    }

    public function set_custom_field_error($field, $val)
    {
        $this->_field_data[$field]['error'] = $val;
    }

    public function captcha($str)
    {
        $this->CI->load->library('my_captcha');
        return $this->CI->my_captcha->check($str);
    }

}

