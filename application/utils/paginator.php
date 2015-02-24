<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Пагинация страниц
 *
 */
class Paginator
{
    /**
     * Кол-во элементов на странице
     */
    const SIZE = 20;
    private $count = 0;
    private $size = 0;
    private $page = 0;

    static function init_config(& $config)
    {
        $config['full_tag_close'] = '</ul>';
        $config['full_tag_open'] = '<ul>';
        $config['cur_tag_close'] = '</span></li>';
        $config['cur_tag_open'] = '<li><span>';
        $config['num_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
    }
	
	/**
	 * Инициализировать пагинатор данными (по умолчания настроен на админку cms, с использование скроллера вида: 1...4 5 6...10)
	 * @param unknown $site_url - 
	 * @param unknown $paginator - пагинатор
	 * @param unknown $t - $this
	 * @param array $config - параметры отобржения
	 */
	public static function initPaginator($site_url, $paginator, $t, $config=NULL)
	{

		if($config===NULL)
		{
			$config['uri_segment'] = 4;
			$config['cur_tag_open'] = "<li class=\"active\"><a>";
			$config['cur_tag_close'] = '</a></li>';
			
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			
			$config['last_link'] = /*ceil($paginator->getCountRow()/$paginator->getSize());*/'';
			$config['last_tag_open'] = '';
			$config['last_tag_close'] = '';


            $config['first_link'] = '';//1;
			$config['first_tag_open'] = '';
			$config['first_tag_close'] = '';

			$config['prev_link']=FALSE;
			$config['next_link']=TRUE;
            $config['next_link'] = '&gt;';
            $config['prev_link'] = '&lt;';
		}
		else
		{
			$config=$config;
		}
		
		$config['base_url'] = base_url($site_url);
		$config['total_rows'] = $paginator->getCountRow();
		$config['per_page'] = $paginator->getSize();
		$config['num_links'] = 3;
        $config['reuse_query_string'] = TRUE;
		
		$t->pagination->initialize($config);
	}

    function Paginator($page = 0, $size = self::SIZE)
    {
        $this->size = $size;
        $this->page = $page;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getCountRow()
    {
        return $this->count;
    }

    public function setCountRow($count)
    {
        $this->count = $count;
    }

    public function getBeginElement()
    {
        return $this->page;
    }

}
