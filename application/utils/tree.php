<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Класс для работы с деревом
 */
class Tree
{
    protected $_items = array();
    protected $_childs = array();

    /**
     * Проверка на наличие элемента
     * @param mixed $id
     * @return boolean
     */
    public function itemExists($id)
    {
        return isset($this->_items[$id]);
    }

    /**
     * Получить кол-во элементов дерева.
     * @return integer
     */
    public function getCount()
    {
        return sizeof($this->_items);
    }

    /**
     * Добавление элемента в дерево
     * @param mixed $id
     * @param mixed $parent
     * @param mixed $data
     * @return void
     */
    public function addItem($id, $parent = 0, $data)
    {
        $this->_items[$id] = array(
            'id' => $id,
            'parent' => $parent,
            'data' => $data
        );

        if (!isset($this->_childs[$parent]))
            $this->_childs[$parent] = array();

        /*
         * Cсылка использована преднамеренно, на производительность не влияет,
        * но позволяет изменять элементы, например, при внедрении сортировки
        */
        $this->_childs[$parent][$id] = & $this->_items[$id];
    }

    /**
     * Получить элемент по индификатору
     * @param mixed $id
     * @throws Exception
     * @return array
     */
    public function getItem($id)
    {
        if ($this->itemExists($id))
            return $this->_items[$id];
        else
            throw new Exception('wrong id');
    }

    /**
     * Проверка на наличие дочерних элементов
     * @param mixed $id
     * @return boolean
     */
    public function hasChilds($id)
    {
        return isset($this->_childs[$id]);
    }

    /**
     * Получить дочерние элементы
     * @param mixed $id
     * @return array
     */
    public function getChilds($id)
    {
        if (!$this->hasChilds($id))
            return array();
        return $this->_childs[$id];
    }

    /**
     * Рекурсивное удаление элементов (узел + дочерние)
     * @param mixed $id
     * @return void
     */
    protected function _remove($id)
    {
        /*
         * Получаем дочерние элементы
        */
        $childs = $this->getChilds($id);
        if (!empty($childs)) {
            /*
             * Рекурсивное удаление дочерних элементов
            */
            foreach ($childs as $k => $v) {
                $this->_remove($v['id']);
            }
        }
        /*
         * Удаляем узел элемента
        */
        if (isset($this->_childs[$id]))
            unset($this->_childs[$id]);
        /*
         * Получаем id родительского узла
        */
        $parent = $this->_items[$id]['parent'];
        /*
         * Удаляем из родительского узла ссылку на дочерний
        */
        if (!empty($this->_childs[$parent])) {
            foreach ($this->_childs[$parent] as $key => $item) {
                if ($item['id'] == $id) {
                    unset($this->_childs[$parent][$key]);
                    break;
                }
            }
        }
        /*
         *  Удаляем элемент
        */
        unset($this->_items[$id]);
    }

    /**
     * Удаление узла
     * @param mixed $id
     * @return void
     */
    public function removeItem($id)
    {
        if ($this->itemExists($id))
            $this->_remove($id);
    }

    /**
     * Перемещение узла
     * @param mixed $id
     * @param mixed $newParent
     * @return void
     */
    public function changeParent($id, $newParent)
    {
        if ($this->itemExists($id) && ($this->itemExists($newParent) || $newParent === 0)) {
            $oldParent = $this->_items[$id]['parent'];
            $this->_items[$id]['parent'] = $newParent;
            if (!empty($this->_childs[$oldParent])) {
                foreach ($this->_childs[$oldParent] as $k => $v) {
                    if ($v['id'] === $id) {
                        unset($this->_childs[$oldParent][$k]);
                        break;
                    }
                }
            }
            $this->_childs[$newParent][$id] = & $this->_items[$id];
        }
    }
    //======================================
    /**
     * Тестовый метод отрисовки узла
     * @param mixed $parent
     * @return string
     */
    public function _createGroup($parent)
    {
        $s = '';
        $childs = $this->getChilds($parent);
        foreach ($childs as $k => $v) {
            $s .= '<div style="border:1px solid #000000;padding:5px;margin:3">
					Элемент ' . $v['data']->Name;
            if ($this->hasChilds($v['id'])) {
                $s .= $this->_createGroup($v['id']);
            }
            $s .= '</div>';
        }
        return $s;
    }

    /**
     * Создать JSON для дерева элементов (dynatree)
     * @param int $parent - элемент с которго начать построение
     * @return string в формате JSON
     */
    public function createJSON($parent)
    {
        $s = '';
        $childs = $this->getChilds($parent);

        foreach ($childs as $k => $v) {

            $isFolder = TRUE;
            if ($k[0] == 'p' || $k[0] == 'g') {
                $isFolder = FALSE;
            }

            $s .= ',{"title":"' . ($isFolder ? (isset($v['data']->head) ? $v['data']->head : $v['data']->name) : $v['data']->name) . '",' . '"key":"' . $k . '"' . ($isFolder ? ', "isFolder":"true", "isLazy":"true"' : NULL);

            if ($this->hasChilds($v['id'])) {
                $s .= ', "children":[' . $this->createJSON($v['id']) . ']}';
            } else {
                $s .= '}';
            }
        }
        $s = str_replace("[,{", "[{", $s);
        $s = substr($s, 1, strlen($s) - 1);

        return $s;
    }

    public function createXML($parent, &$xml = NULL, array &$st = array())
    {
        if (empty($xml)) {
            $xml = new Xml_writer();
            $xml->setRootName('menus', NULL);
            $xml->initiate();
        }
		
        $childs = $this->getChilds($parent);
        foreach ($childs as $v) {
            $s = '/';
            foreach ($st as $item) {
					$s .= $item . '/';
            }
			
			if($v['data']->name=='/')
			{
				$s= $v['data']->name;
			}elseif(empty($v['data']->name))
			{
				$s= '';
			}else
			{
				$s .= $v['data']->name;
			}
			
            //не корень
            if ($v['data']->id != 1 && $v['data']->is_service != 1) {
                array_push($st, $v['data']->name);
            }

            $xml->startBranch('menu', array(
                'name' => $v['data']->head,
                'route' => !empty($v['data']->url) ? $v['data']->url : $s,
                'id' => $v['data']->id,
                'isService' => $v['data']->is_service,
                'sName' => $v['data']->service_name,
                'template' => $v['data']->template
            ));

            if ($this->hasChilds($v['id'])) {
                $this->createXML($v['id'], $xml, $st);
            }
            array_pop($st);
            $xml->endBranch();
        }
        return $xml;
    }
}
