<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Обертка над капчей кодегнитера
 */
class MY_Captcha
{
    private $CI;
    function __construct()
    {
        $this->CI = & get_instance();
    }

    /**
     * Создать капчу вместе с записью информации о капче в БД
     * @param int|number $img_width - ширина капчи
     * @param int|number $img_height - высота капчи
     * @param int $expiration - время жизни
     * @param array $color
     * @return массив с данными о капче
     */
    public function create_captcha($img_width = 90, $img_height = 40, $expiration = Captcha_model::EXP, $color = [88, 94, 98])
    {
        $this->CI->load->helper('captcha');
        $this->CI->load->helper('string');

        $vals = array(
            'word' => random_string('numeric', 4),
            'word_length' => 4,
            'img_path' => APPPATH . 'captcha' . DIRECTORY_SEPARATOR,
            'img_url' => base_url('application/captcha') . '/',
            'img_width' => $img_width,
            'font_path' => '.' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'texb.ttf',
            'img_height' => $img_height,
            'expiration' => $expiration
        );

        if (!empty($color)) $vals['colors'] = array('background' => array(255, 255, 255), 'border' => $color, 'text' => $color, 'grid' => $color);

        $cap = create_captcha($vals);

        $this->CI->load->model('Captcha_model');
        $this->CI->Captcha_model->create($cap['time'], $this->CI->input->ip_address(), $cap['word']);

        return $cap;
    }

    /**
     * Проверить капчу на верность
     * @param string $code - код введеный пользователем
     * @return bool - TRUE - капча верна
     */
    public function check($code)
    {
        $this->CI->load->model('Captcha_model');
        return $this->CI->Captcha_model->check($code, $this->CI->input->ip_address());
    }

}