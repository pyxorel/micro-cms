<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_input extends CI_input
{
    function __construct()
    {
        parent::__construct();
    }

    function is_post()
    {
        return $this->server('REQUEST_METHOD') == 'POST';
    }

    function is_get()
    {
        return $this->server('REQUEST_METHOD') == 'GET';
    }

    function has_file($file='file')
    {
        return $_FILES[$file]['error'] == 0 ? TRUE : FALSE;
    }

    function data_checkbox_inputs($prefix = NULL)
    {
        $result = [];
        foreach ($this->post(NULL, TRUE) as $key => $item) {

            if (empty($prefix)) {
                if (is_array($item) && count($item) == 2) {
                    $result[$key] = $item[0] || $item[1];
                } else {
                    $result[$key] = !!$item[0];
                }
            } else if (stripos($key, $prefix) !== FALSE) {
                if (is_array($item) && count($item) == 2) {
                    $result[$key] = $item[0] || $item[1];
                } else {
                    $result[$key] = !!$item[0];
                }
            }

        }
        return $result;
    }

}