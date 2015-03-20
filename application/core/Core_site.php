<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once 'application/utils/paginator.php';
include_once 'application/libs/PhpSimple/HtmlDomParser.php';

/**
 * Класс содержащий служебные методы (ядро работы с сайтом)
 */
class Core_site
{
    private $CI;
    private $resources;
    private $lang_code;
    private static $xml;

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

        if (self::$xml == NULL)
            self::$xml = simplexml_load_file("./application/cache/{$lang_code}_menu.xml");
    }

    public function get_resources($name = NULL)
    {
        if (!empty($name)) return $this->resources[$name];
        return $this->resources;
    }

    public function get_params_route()
    {
        $param = $this->CI->input->get();

        if (!empty($param) && !array_key_exists('p', $param)) {
            $s = '?';
            foreach ($param as $k => $p) {
                $s .= $k . '=' . $p;
            }
        } else if (!empty($param)) {
            $page = $param['p'];
            $param['page'] = $page;
        }

        return isset($s) ? $param['query'] = $s : $param;
    }

    public static function _remove_base_url_href_src(&$list, $prop = 'content')
    {
        $base_url = base_url();
        foreach ($list as $key => $item) {
            $dom = Sunra\PhpSimple\HtmlDomParser::str_get_html($item[$prop], TRUE, TRUE, DEFAULT_TARGET_CHARSET, FALSE);
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
            $dom = Sunra\PhpSimple\HtmlDomParser::str_get_html($item->$prop, TRUE, TRUE, DEFAULT_TARGET_CHARSET, FALSE);
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
        $_menu->id = (int)(string)$attr['id'];
        $_menu->is_service = filter_var((string)$attr['isService'], FILTER_VALIDATE_BOOLEAN);
        $_menu->sort = (string)$attr['sort'];
        $_menu->date = (string)$attr['date'];
        $_menu->count_elem = (int)(string)$attr['count_elem'];

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
        $id = (string)$attr['id'];
        if (!empty($r))
            $arr[$id] = $r;
        if (isset($menu->menu)) {
            foreach ($menu->menu as $item) {
                $this->sub_tree_list($item, $arr);
            }
        }
        return NULL;
    }

    public function get_list_route_menu($lang = DEFAULT_LANG_CODE)
    {
        $query = '///menu[@route="/"]';
        $xml = self::$xml;
        $start = isset($xml->xpath($query)[0]) ? $xml->xpath($query)[0] : NULL;
        if (!empty($start)) {
            $arr = [];
            $this->sub_tree_list($start, $arr);
            return array_unique($arr, SORT_REGULAR);
        }
        return NULL;
    }

    public function get_menu_parent($id, $lang = DEFAULT_LANG_CODE)
    {
        if (!empty($id)) {
            $query = '//menu[@id="' . $id . '"]';
        }
        $xml = self::$xml;
        $start = $xml->xpath($query)[0];
        $parent = $start;
        $last_p = null;
        while (NULL != $parent) {
            $attr = $parent->attributes();
            $_menu = new stdClass();
            $template = (string)$attr['template'];
            $_menu->name = (string)$attr['name'];
            $_menu->route = (string)$attr['route'];
            $_menu->template = empty($template) ? '_default.tpl' : $template;
            $_menu->s_name = (string)$attr['sName'];
            $_menu->id = (string)$attr['id'];
            $_menu->is_service = filter_var((string)$attr['isService'], FILTER_VALIDATE_BOOLEAN);
            $_menu->sort = (string)$attr['sort'];
            $_menu->date = (string)$attr['date'];
            $_menu->count_elem = (string)$attr['count_elem'];
            $_menu->child = $last_p;

            $last_p = $_menu;
            $parent = isset ($parent->xpath("..")[0]) ? $parent->xpath("..")[0] : NULL;

            if ($parent->getName() == 'menus') $parent = NULL;
        }

        return $last_p;
    }

    private function get_menu_xpath_query($query, $lang = DEFAULT_LANG_CODE)
    {
        $xml = self::$xml;
        $start = isset($xml->xpath($query)[0]) ? $xml->xpath($query)[0] : NULL;

        if (!empty($start)) {
            $menus = $this->sub_tree($start);
            $parent = $start->xpath('parent::*');
            if (!empty($parent)) {
                $attr = $parent[0]->attributes();
                $_menu = new stdClass();
                $template = (string)$attr['template'];
                $_menu->name = (string)$attr['name'];
                $_menu->route = (string)$attr['route'];
                $_menu->template = empty($template) ? '_default.tpl' : $template;
                $_menu->s_name = (string)$attr['sName'];
                $_menu->id = (int)(string)$attr['id'];
                $_menu->is_service = filter_var((string)$attr['isService'], FILTER_VALIDATE_BOOLEAN);
                $_menu->sort = (string)$attr['sort'];
                $_menu->date = (string)$attr['date'];
                $_menu->count_elem = (int)(string)$attr['count_elem'];
                $menus->parent = $_menu;
            }
            return $menus;
        }
        return NULL;
    }

    /**
     * Получить меню
     * @param string $name служебное название меню, если не указано, то вернуть от корня
     * @return ассоциативный массив из элементов меню
     */
    public function get_menu($name = NULL, $lang = DEFAULT_LANG_CODE, $order = NULL, $limit = NULL, $offset = NULL)
    {
        $query = '/menus/menu/*';
        if (!empty($name)) {
            $query = '//menu[@sName="' . $name . '"]';
        }
        $menu = $this->get_menu_xpath_query($query, $lang);

        if ($order == 'date') {
            $this->sort_menu_by_date($menu);
        }

        if (!empty($limit)) {
            $menu->menu = array_slice($menu->menu, 0, $limit);
        }

        return $menu;
    }

    public function sort_menu_by_date(& $menu)
    {
        Utils::array_sort_by($menu->menu, function ($a, $b) {
            $a_t = strtotime($a->date);
            $b_t = strtotime($b->date);
            if ($a_t == $b_t) {
                return 0;
            }
            return ($a_t > $b_t) ? -1 : 1;
        });
    }

    /**
     * Получить меню исходя из маршрута url
     * @param string $route - маршрут
     * @return NULL - если маршрут не найден, либо меню
     */
    public function get_menu_from_route($route, $lang = DEFAULT_LANG_CODE, & $param = NULL)
    {
        $query = '///menu[@route="/' . $route . '"]';
        $menu = $this->get_menu_xpath_query($query, $lang);
        if (!empty($menu)) return $menu;

        $id_menu = NULL;
        foreach ($this->get_list_route_menu() as $k => $item) {
            if ($item == '/') continue;
            $str = str_replace('/', '\/', $item);
            if (preg_match("/$str/i", $route, $matches)) {
                $id_menu = $k;
                $param = $matches;
                break;
            }
        }

        if (!empty($id_menu)) {
            return $this->get_menu_from_id($id_menu, $lang);
        }
        return NULL;
    }

    /**
     * Получить меню исходя из id
     * @param int $id - идентификатор меню
     * @return NULL - если маршрут не найден, либо меню
     */
    public function get_menu_from_id($id, $lang = DEFAULT_LANG_CODE)
    {
        $query = '///menu[@id="' . $id . '"]';
        return $this->get_menu_xpath_query($query, $lang);
    }

    public function get_menu_from_name($name, $lang = DEFAULT_LANG_CODE)
    {
        $query = '///menu[contains(@name, "' . $name . '")]';
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

    public function get_objects_by_id_menu($idMenu, $name = null)
    {
        $this->CI->load->model('cms/Menu_model');
        $objs = $this->CI->Menu_model->get_instancesByMenuID($idMenu);

        if (!empty($objs)) {
            $this->CI->load->library('doctrinelib');
            $this->CI->_em = $this->CI->doctrinelib->get_entityManager();

            $ids = [];
            foreach ($objs as $item) {
                array_push($ids, $item->id_instance);
            }

            if (!empty($ids)) {
                $instances = $this->CI->_em->getRepository('Entities\Instance')->get_view_instances($name, NULL, NULL, NULL, $ids);
                return $instances;
            }
        }

        return [];
    }

}