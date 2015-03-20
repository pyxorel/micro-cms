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
        $this->_childs[$parent][$id] = &$this->_items[$id];
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
            $this->_childs[$newParent][$id] = &$this->_items[$id];
        }
    }

    /**
     * Получить массив из дерева элементов (для dynatree), работает рекурсивно для всех елементов дерева
     * @param int $parent - элемент с которого начать построение
     * @return array
     */
    public function get_array($parent)
    {
        $i = $this->getItem($parent);
        $isFolder = TRUE;
        if ($i['id'][0] == 'p' || $i['id'][0] == 'g' || $i['id'][0] == 'o') {
            $isFolder = FALSE;
        }

        $addClass = '';

        if ($i['id'][0] == 'o') {
            $addClass = 'tree-icon-obj';
        }

        if (isset($i['data']->is_service) && $i['data']->is_service) {
            $addClass = 'tree-icon-service-menu';
        }

        $el = ['title' => ($isFolder ? (isset($i['data']->head) ? $i['data']->head : $i['data']->name) : $i['data']->name . (isset($i['data']->head) ? " ({$i['data']->head})" : NULL)),
            'key' => $i['id'],
            'isFolder' => $isFolder,
            'isLazy' => $isFolder,
            'addClass' => $addClass];

        $childs = $this->getChilds($parent);
        foreach ($childs as $k => $v) {
            if (!isset($el['children'])) $el['children'] = [];
            array_push($el['children'], $this->get_array($v['id'], $el));

        }
        return $el;
    }

    /**
     * Получить поддерево элементов ввиде массива для дерева элементов (для dynatree)
     * @param int $parent - элемент с которого начать построение
     * @return array
     */
    public function get_sub_tree($parent = 0)
    {
        $childs = $this->getChilds($parent);
        $result = [];
        foreach ($childs as $k => $v) {
            $isFolder = TRUE;
            if ($k[0] == 'p' || $k[0] == 'g' || $k[0] == 'o') {
                $isFolder = FALSE;
            }

            $addClass = '';

            if ($k[0] == 'o') {
                $addClass = 'tree-icon-obj';
            }

            if (isset($v['data']->is_service) && $v['data']->is_service) {
                $addClass = 'tree-icon-service-menu';
            }

            array_push($result, ['title' => ($isFolder ? (isset($v['data']->head) ? $v['data']->head : $v['data']->name) : $v['data']->name . (isset($v['data']->head) ? " ({$v['data']->head})" : NULL)),
                'key' => $v['id'],
                'isFolder' => $isFolder,
                'isLazy' => $isFolder,
                'addClass' => $addClass
            ]);
        }
        return $result;
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

            if ($v['data']->name == '/') {
                $s = $v['data']->name;
            } elseif (empty($v['data']->name)) {
                $s = '';
            } else {
                $s .= $v['data']->name;

                if ($v['data']->name[0] == '/') {
                    $s = $v['data']->name;
                }
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
                'template' => $v['data']->template,
                'sort' => empty($v['data']->sort) ? 'priority' : $v['data']->sort,
                'date' => $v['data']->date,
                'count_elem' => empty($v['data']->count_elem) ? 0 : (int)$v['data']->count_elem
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
