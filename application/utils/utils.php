<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Разные полезные методы
 */
class Utils
{

    public static function array_group_by(array $arr, callable $key_selector, $name)
    {
        $result = array();
        foreach ($arr as $i) {
            $key = call_user_func($key_selector, ['i' => $i, 'name' => $name]);
            $result[$key][] = $i;
        }
        return $result;
    }

    public static function array_sort_by(array & $arr, callable $func)
    {
        usort($arr, $func);
    }

    public static function array_sort_key_by(array & $arr, callable $func)
    {
        uksort($arr, $func);
    }

    public static function crc32_file($file)
    {
        if (file_exists($file) && is_file($file))
            return crc32(file_get_contents($file));
        return NULL;
    }

    public static function is_json($string)
    {
        return !empty($string) && is_string($string) && is_array(json_decode($string, true)) && json_last_error() == 0;
    }


    /**
     * Получить элемент из массива по конкретному полю и его значению
     * @param $list - список объектов
     * @param $property - свойство
     * @param $val - значение свойства
     * @return mixed NULL - элемент не найден
     */
    public static function get_item_by_value($list, $property, $val)
    {
        foreach ($list as $item) {
            if (isset($item->$property) && strtolower($item->$property) == strtolower($val)) return $item;
        }
        return NULL;
    }

    public static function ru_to_lat($str)
    {
        $rus = array('ё', 'ж', 'ц', 'ч', 'ш', 'щ', 'ю', 'я', 'Ё', 'Ж', 'Ц', 'Ч', 'Ш', 'Щ', 'Ю', 'Я');
        $lat = array('yo', 'zh', 'tc', 'ch', 'sh', 'sh', 'yu', 'ya', 'YO', 'ZH', 'TC', 'CH', 'SH', 'SH', 'YU', 'YA');
        $str = str_replace($rus, $lat, $str);
        $str = Utils::mb_strtr($str,
            "АБВГДЕЗИЙКЛМНОПРСТУФХЪЫЬЭабвгдезийклмнопрстуфхъыьэ",
            "ABVGDEZIJKLMNOPRSTUFH_I_Eabvgdezijklmnoprstufh_i_e");

        return $str;
    }

    private static function mb_strtr($str, $from, $to)
    {
        return str_replace(Utils::mb_str_split($from), Utils::mb_str_split($to), $str);
    }

    private static function mb_str_split($str)
    {
        return preg_split('~~u', $str, null, PREG_SPLIT_NO_EMPTY);;
    }

    public static function make_array($list, $property)
    {
        $result = [];
        foreach ($list as $item) {
            if (isset($item->$property)) array_push($result, $item->$property);
        }

        return $result;
    }

    /**
     * Получить Unix штам времени из типа DateTime MySql
     * @param string $mysqlDate
     * @return number
     */
    public static function getTimeStamp($mysqlDate)
    {
        $parts = preg_split("/[- :]+/", $mysqlDate);
        return mktime($parts[3], $parts[4], $parts[5], $parts[1], $parts[2], $parts[0]);
    }

    /**
     * Склонения месяцев
     * @param string $engMonth - название месяца на английском
     * @return string - русское название месяца
     */
    public static function declination_month_ru($engMonth)
    {
        $engMonth = mb_strtolower($engMonth);

        switch ($engMonth) {
            case 'january':
                return 'Января';
                break;
            case 'february':
                return 'Февраля';
                break;
            case 'march':
                return 'Марта';
                break;
            case 'april':
                return 'Апреля';
                break;
            case 'may':
                return 'Мая';
                break;
            case 'june':
                return 'Июня';
                break;
            case 'july':
                return 'Июля';
                break;
            case 'august':
                return 'Августа';
                break;
            case 'september':
                return 'Сентября';
                break;
            case 'october':
                return 'Октября';
                break;
            case 'november':
                return 'Ноября';
                break;
            case 'december':
                return 'Декабря';
                break;
        }
    }

    /**
     * Получить востановленный путь к фалу созданный elFinderom
     * @param string $url - url адрес к файлу
     * @return string нормализованный путь к файлу
     */
    public static function getNormalizeeFileNameElFinder($url)
    {
        $start = strpos($url, 'target=') + 10;
        $url = substr($url, $start, strlen($url) - $start);
        return base64_decode($url);
    }

    /**
     * Recursively delete a directory
     */
    public static function unlinkRecursive($dir, $deleteSubDir = FALSE, $deleteRootToo = FALSE)
    {
        if (!$dh = @opendir($dir)) {
            return FALSE;
        }
        while (false !== ($obj = readdir($dh))) {
            if ($obj == '.' || $obj == '..') {
                continue;
            }
            $obj = $dir . DIRECTORY_SEPARATOR . $obj;

            if (($deleteSubDir === TRUE || !is_dir($obj)) && !@unlink($obj)) {
                self::unlinkRecursive($obj, true);
            }
        }

        closedir($dh);

        if ($deleteRootToo) {
            @rmdir($dir);
        }

        return TRUE;
    }

    public static function list_dirs($dir_name)
    {
        $dirs = [];
        foreach (scandir($dir_name) as $item) {
            if (is_dir($dir_name . $item) && $item != '.' && $item != '..') {
                array_push($dirs, $item);
            }
        }

        return $dirs;
    }

    public static function list_files($dir_name, $fill_key = FALSE, $ext = NULL)
    {
        $files = [];

        foreach (scandir($dir_name) as $item) {
            if (is_file($dir_name . $item)) {
                if (!empty($ext)) {
                    if (pathinfo($dir_name . $item, PATHINFO_EXTENSION) == $ext) {
                        if (!$fill_key)
                            array_push($files, $item);
                        else {
                            $files[$item] = $item;
                        }
                    }
                } else {
                    if (!$fill_key)
                        array_push($files, $item);
                    else {
                        $files[$item] = $item;
                    }
                }
            }
        }

        return $files;
    }

}