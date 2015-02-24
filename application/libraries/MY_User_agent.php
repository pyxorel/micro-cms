<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');


class MY_User_agent extends CI_User_agent {

    function __construct()
    {
        parent::__construct();
    }

    public function is_ios()
    {
        return stripos($this->agent, 'darwin')!==FALSE;
    }

}
