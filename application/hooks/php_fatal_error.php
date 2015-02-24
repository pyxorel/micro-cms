<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class PHP_fatal_error
{
    public function set_handler()
    {
        set_error_handler('error_handler');
    }
}

function error_handler($severity, $message, $filename, $lineno)
{

    /*$log = new CI_Log();
    $log->write_log('ERROR', $filename . ' : ' . $lineno. "\t" . $message);

    if (ini_get('error_reporting')<>0)
    {
        $base_url = is_https() ? 'https' : 'http';
        $base_url .= '://'.$_SERVER['HTTP_HOST']
        .str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']) . 'error';
        header('Location: ' . $base_url, true, 302);
        header('DBG: ' . basename($filename) .' : '. $lineno);
        exit;
    }*/
}