<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once 'application/utils/dbMessage.php';
include_once 'application/utils/paginator.php';

class Captcha_model extends CI_Model
{

    const TableName = 'captcha';
    const EXP = 600; // 10 минутное ограничение времени жизни каптчи
    //поля
    public $Captcha_id;
    public $Captcha_time;
    public $IP_address;
    public $Word;

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Проверить каптчу
     * @param string $code - введеный пользователем код
     * @param string $ip_address
     * @return boolean -TRUE - код верен
     */
    public function check($code, $ip_address)
    {
        $expiration = time() - self::EXP;

        $this->load->database();
        $this->db->query("DELETE FROM captcha WHERE captcha_time < " . $expiration);

        $sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
        $binds = array($code, $ip_address, $expiration);
        $query = $this->db->query($sql, $binds);
        $row = $query->row();
        return $row->count != 0;

    }

    public function create($captcha_time, $ip_address, $word)
    {
        $model = new self();

        $model->Captcha_time = $captcha_time;
        $model->IP_address = $ip_address;
        $model->Word = $word;

        $this->load->database();

        if (!$this->db->insert(self::TableName, $model)) {
            return FALSE;
        }

        return $this->db->insert_id();
    }

}