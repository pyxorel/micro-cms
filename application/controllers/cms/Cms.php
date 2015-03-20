<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/base_controller.php';

class Cms extends BaseController
{
    /**
     * Обновить каптчу
     */
    function re_create_captcha()
    {
        $this->load->library('MY_Captcha');
        $captcha = $this->my_captcha->create_captcha(143, 46, 1800, [88, 94, 98]);
        echo $captcha['image'];
    }

    /**
     * Отдать файл
     * @param $base64_name - имя файла
     */
    function file_content($base64_name)
    {
        $base64_name = str_replace('=', '', $base64_name);
        $base64_name = base64_decode(strtr($base64_name, '-_.', '+/='));
        $base64_name = str_replace('..', '', $base64_name);
        $base64_name = str_replace('\\', DIRECTORY_SEPARATOR, $base64_name);
        $base64_name = urldecode($base64_name);
        $path_file = APPPATH . 'upload' . DIRECTORY_SEPARATOR . $base64_name;
        $hdr = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : NULL;
        if (file_exists($path_file)) {
            $modified = md5(@filemtime($path_file));
            if ($modified == $hdr) {
                header("HTTP/1.1 304 Not Modified");
                header("Cache-Control: public, max-age=60");
                header("ETag: $modified");
                exit;
            }

            $ext = strtolower(pathinfo($path_file, PATHINFO_EXTENSION));
            if (!empty($ext)) {
                $mimes =& get_mimes();
                if (isset($mimes[$ext])) {
                    $mime = $mimes[$ext][0];
                    // Clean output buffer
                    if (ob_get_level() !== 0 && @ob_end_clean() === FALSE) {
                        @ob_clean();
                    }
                    header('Content-Length: ' . filesize($path_file));
                    header("Content-Type: $mime");
                    header('Expires: 0');
                    $base64_name = preg_replace("/^(.+\\\\)(.+)/", "$2", $base64_name);
                    header('Content-Disposition: inline; filename="' . $base64_name . '";');
                    header("ETag: $modified");
                    header("Cache-Control: public, max-age=60");
                    @readfile($path_file);
                    exit;
                }
            }

            $this->load->helper('download');
            force_download($base64_name, @file_get_contents($path_file), TRUE);
        }
        parent::_404();
    }
}