<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once 'application/utils/paginator.php';
include_once 'application/libraries/PhpSimple/HtmlDomParser.php';

class DataSite
{
    public function get_seo_counter($provider)
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('file');
        switch ($provider) {
            case 'yandex':
                return @file_get_contents($this->CI->config->item('analytic_yandex'));
            case 'google':
                return @file_get_contents($this->CI->config->item('analytic_google'));
        }
    }
}

/**
 * Класс содержащий служебные методы (ядро работы с сайтом)
 */
class Core_site
{
    private $CI;
    private $resources;
    private $lang_code;

    function __construct($load_res = FALSE, $lang_code = DEFAULT_LANG_CODE)
    {
        $this->CI = &get_instance();
        if ($load_res) {
            $this->CI->load->model('cms/Resource_model');
            $res = [];
            foreach ($this->CI->Resource_model->get_resources($lang_code) as $item) {
                $res[$item->name] = $item->content;
            }
            $this->resources = $res;
        }
    }

    public function get_resources($name = NULL)
    {
        if (!empty($name)) return $this->resources[$name];
        return $this->resources;
    }

    public static function _remove_base_url_href_src(&$list, $prop = 'content')
    {
        $base_url = base_url();
        foreach ($list as $key => $item) {
            $dom = Sunra\PhpSimple\HtmlDomParser::str_get_html($item[$prop]);
            if (!empty($dom)) {
                foreach ($dom->find('a') as $link) {
                    if (stripos($link->href, $base_url) !== FALSE)
                        $link->href = str_replace($base_url, '', $link->href);
                }
                foreach ($dom->find('img') as $img) {
                    if (stripos($img->src, $base_url) !== FALSE)
                        $img->src = str_replace($base_url, '', $img->src);
                }
                $item[$prop] = $dom->innertext;
                $list[$key] = $item;
            }
        }
    }

    public static function _add_base_url_href_src(&$list, $prop = 'content')
    {
        $base_url = base_url();
        $pattern = '/^(https?:\/\/|mailto:).+/i';
        foreach ($list as $key => $item) {
            $dom = Sunra\PhpSimple\HtmlDomParser::str_get_html($item->$prop);
            if (!empty($dom)) {
                foreach ($dom->find('a') as $link) {
                    if (!preg_match($pattern, $link->href))
                        $link->href = $base_url . trim($link->href, '/');
                }
                foreach ($dom->find('img') as $img) {
                    if (!preg_match($pattern, $img->src)) {
                        $img->src = $base_url . trim($img->src, '/');
                    }
                }
                $item->$prop = $dom->innertext;
                $list[$key] = $item;
            }
        }
    }

    private function sub_tree(&$menu)
    {
        $attr = $menu->attributes();
        $_menu = new stdClass();
        $template = (string)$attr['template'];
        $_menu->name = (string)$attr['name'];
        $_menu->route = (string)$attr['route'];
        $_menu->template = empty($template) ? '_default.tpl' : $template;
        $_menu->s_name = (string)$attr['sName'];
        $_menu->id = (string)$attr['id'];
        $_menu->is_service = filter_var((string)$attr['isService'], FILTER_VALIDATE_BOOLEAN);
        if (isset($menu->menu)) {
            $_menu->menu = [];
            foreach ($menu->menu as $item) {
                $_menu->menu[] = $this->sub_tree($item);
            }

        }
        return $_menu;
    }
	
	private function sub_tree_list(&$menu, &$arr)
    {
		$attr = $menu->attributes();
		$r = (string)$attr['route'];
		if(!empty($r))
			array_push($arr, $r);
        if (isset($menu->menu)) {
            foreach ($menu->menu as $item) {
                $this->sub_tree_list($item,$arr);
            }
        }
        return NULL;
    }

	public function get_list_route_menu($lang = DEFAULT_LANG_CODE)
    {
        $query = '///menu[@route="/"]';
		$xml = simplexml_load_file("./application/cache/{$lang}_menu.xml");
		$start = isset($xml->xpath($query)[0]) ? $xml->xpath($query)[0] : NULL;
		if(!empty($start)) {
			$arr=[];
			$this->sub_tree_list($start, $arr);
			return array_unique($arr, SORT_REGULAR);
		}
		return NULL;
    }
	
    public function get_menu_parent($id, $lang = DEFAULT_LANG_CODE){
        if (!empty($id)) {
            $query = '//menu[@id="' . $id . '"]';
        }
        $xml = simplexml_load_file("./application/cache/{$lang}_menu.xml");
        $start = $xml->xpath($query)[0];
        $parent = $start;
        $last_p = null;
        while(NULL != $parent)
        {
            $attr = $parent->attributes();
            $_menu = new stdClass();
            $template = (string)$attr['template'];
            $_menu->name = (string)$attr['name'];
            $_menu->route = (string)$attr['route'];
            $_menu->template = empty($template) ? '_default.tpl' : $template;
            $_menu->s_name = (string)$attr['sName'];
            $_menu->id = (string)$attr['id'];
            $_menu->is_service = filter_var((string)$attr['isService'], FILTER_VALIDATE_BOOLEAN);
            $_menu->child = $last_p;

            $last_p = $_menu;
            $parent = isset ($parent->xpath("..")[0]) ? $parent->xpath("..")[0] : NULL;

            if($parent->getName()=='menus') $parent = NULL;
        }

        return $last_p;
    }

    private function get_menu_xpath_query($query, $lang = DEFAULT_LANG_CODE)
    {
        $xml = simplexml_load_file("./application/cache/{$lang}_menu.xml");
        $start = isset($xml->xpath($query)[0]) ? $xml->xpath($query)[0] : NULL;

        return !empty($start) ? $this->sub_tree($start) : NULL;
    }

    /**
     * Получить меню
     * @param string $name служебное название меню, если не указано, то вернуть от корня
     * @return ассоциативный массив из элементов меню
     */
    public function get_menu($name = NULL, $lang = DEFAULT_LANG_CODE)
    {
        $query = '/menus/menu/*';
        if (!empty($name)) {
            $query = '//menu[@sName="' . $name . '"]';
        }
        return $this->get_menu_xpath_query($query, $lang);
    }

    /**
     * Получить меню исходя из маршрута url
     * @param string $route - маршрут
     * @return FALSE - если маршрут не найден, либо меню
     */
    public function get_menu_from_route($route, $lang = DEFAULT_LANG_CODE)
    {
        $query = '///menu[@route="/' . $route . '"]';
        return $this->get_menu_xpath_query($query, $lang);
    }

    public function get_pages_by_id_menu($idMenu, $lang = DEFAULT_LANG_CODE, $content = FALSE, $first = TRUE)
    {
        $this->CI->load->model('cms/Menu_model');
        $page = $this->CI->Menu_model->get_pagesByMenuID($idMenu, $lang, $content, $first);
        if (is_array($page)) {
            self::_add_base_url_href_src($page);
            $pages_assoc = [];
            foreach ($page as $p) {
                $pages_assoc[$p->name] = $p;
            }
        } else {
            $page = [$page];
            self::_add_base_url_href_src($page);
        }
        return count($page) == 1 && $first ? $page[0] : $pages_assoc;
    }

    public function get_galleries_by_id_menu($idMenu, $lang = DEFAULT_LANG_CODE, $first = TRUE)
    {
        $this->CI->load->model('cms/Menu_model');
        $gall = $this->CI->Menu_model->get_galleriesByMenuID($idMenu, $lang, $first);
        if (is_array($gall)) {
            $assoc = [];
            foreach ($gall as $p) {
                if (!isset($assoc[$p->IDGallery])) $assoc[$p->IDGallery] = [$p];
                else
                    array_push($assoc[$p->IDGallery], $p);
            }
        } else {
            $gall = [$gall];
        }
        return count($gall) == 1 && $first ? $gall[0] : $assoc;
    }

}