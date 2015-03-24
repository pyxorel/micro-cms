<?php
namespace Repositories;

use Doctrine\ORM\EntityRepository;
use Entities;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Business_obj_repository extends EntityRepository
{
    public $last_error;
    private static $UNIQUE_NAME = 'Такое имя уже существует.';

    private function parse_view_instance($objs)
    {
        $inst = new Entities\Instance_obj();
        $inst->fields = [];
        foreach ($objs as $k => $item) {
            $inst->class_name = $item->class_name;
            $inst->id = $item->id;
            $inst->fields[$item->field_name] = [
                'id_field' => $item->id_field,
                'id' => $item->id_value,
                'value' => $item->value,
                'type' => $item->type,
                'unit' => $item->unit,
                'order' => $item->order,
                'extra' => $item->extra,
                'field_loc_name' => $item->field_loc_name];
        };

        return $inst;
    }

    private function getFullSQL($query)
    {
        $sql = $query->getSql();
        $paramsList = $this->getListParamsByDql($query->getDql());
        $paramsArr = $this->getParamsArray($query->getParameters());
        $fullSql = '';
        for ($i = 0; $i < strlen($sql); $i++) {
            if ($sql[$i] == '?') {
                $nameParam = array_shift($paramsList);

                if (is_string($paramsArr[$nameParam])) {
                    $fullSql .= '"' . addslashes($paramsArr[$nameParam]) . '"';
                } elseif (is_array($paramsArr[$nameParam])) {
                    $sqlArr = '';
                    foreach ($paramsArr[$nameParam] as $var) {
                        if (!empty($sqlArr))
                            $sqlArr .= ',';

                        if (is_string($var)) {
                            $sqlArr .= '"' . addslashes($var) . '"';
                        } else
                            $sqlArr .= $var;
                    }
                    $fullSql .= $sqlArr;
                } elseif (is_object($paramsArr[$nameParam])) {
                    switch (get_class($paramsArr[$nameParam])) {
                        case 'DateTime':
                            $fullSql .= "'" . $paramsArr[$nameParam]->format('Y-m-d H:i:s') . "'";
                            break;
                        default:
                            $fullSql .= $paramsArr[$nameParam]->getId();
                    }

                } else
                    $fullSql .= $paramsArr[$nameParam];

            } else {
                $fullSql .= $sql[$i];
            }
        }
        return $fullSql;
    }

    /**
     * Get query params list
     * @return int
     */
    protected function getParamsArray($paramObj)
    {
        $parameters = array();
        foreach ($paramObj as $val) {
            $parameters[$val->getName()] = $val->getValue();
        }
        return $parameters;
    }

    private function getListParamsByDql($dql)
    {
        $parsedDql = preg_split("/:/", $dql);
        $length = count($parsedDql);
        $parmeters = array();
        for ($i = 1; $i < $length; $i++) {
            if (ctype_alpha($parsedDql[$i][0])) {
                $param = (preg_split("/[' ' )]/", $parsedDql[$i]));
                $parmeters[] = $param[0];
            }
        }

        return $parmeters;
    }

    /**
     * @return Entities\Common_class
     */
    public function get_common_classes()
    {
        $qb = $this->_em->createQueryBuilder();

        $query = $qb->select('c')
            ->from('Entities\Common_class', 'c')
            ->orderBy('c.name', 'ASC')->getQuery();

        return $query->getResult();
    }

    /**
     * @return Entities\Common_class_field
     */
    public function get_common_class_fields($ids = NULL)
    {
        $qb = $this->_em->createQueryBuilder();

        if (empty($ids)) {
            $query = $qb->select('c')
                ->from('Entities\Common_class_field', 'c')
                ->orderBy('c.name', 'ASC')->getQuery();
        } else {
            $query = $qb->select('c')
                ->from('Entities\Common_class_field', 'c')
                ->andWhere('c.id IN (:ids)')
                ->setParameter('ids', $ids)
                ->orderBy('c.name', 'ASC')->getQuery();
        }

        return $query->getResult();
    }

    private function get_common_class_fields_by_name($names)
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('c')
            ->from('Entities\Common_class_field', 'c')
            ->andWhere('c.name IN (:ids)')
            ->setParameter('ids', $names)
            ->orderBy('c.name', 'ASC')->getQuery();

        $result = [];
        foreach ($query->getResult() as $item) {
            $result[$item->name] = $item;
        }

        return $result;
    }

    /**
     * @return Entities\Instance
     */
    public function get_instances()
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('c')
            ->from('Entities\Instance', 'c')
            ->getQuery();

        return $query->getResult();
    }

    private function get_operator($value)
    {
        if (preg_match('/([>|<]=?)?(.+)/', $value, $matches) !== FALSE) {
            if (isset($matches[1]) && isset($matches[2]))
                return ['op' => !empty($matches[1]) ? $matches[1] : '=', 'val' => $matches[2]];
        }
        return ['op' => '=', 'val' => $value];
    }

    /**
     * @return Entities\Instance
     */
    public function get_view_instances($class = NULL, $fields = null, $order = null, \Paginator $paginator = NULL, $in_id = NULL)
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('i0.id')
            ->from('Entities\Instance_view', 'i0')->groupBy('i0.id');

        if (!empty($fields)) {
            $f_db = $this->get_common_class_fields_by_name(array_keys($fields));
            $x = 1;
            foreach ($fields as $k => $v) {
                $query = $query->from('Entities\Instance_view', "i$x")->andWhere("i0.id = i$x");

                if ($f_db[$k]->type == 'int' || $f_db[$k]->type == 'decimal') {
                    $op = $this->get_operator($v);
                    $query = $query->andWhere("(CAST(i$x.value as decimal) {$op['op']} :f_value$x AND i$x.field_name=:f_name$x)")->setParameter("f_name$x", $k)->setParameter("f_value$x", $op['val']);
                } else {
                    $query = $query->andWhere("(i$x.value LIKE :f_value$x AND i$x.field_name=:f_name$x)")->setParameter("f_name$x", $k)->setParameter("f_value$x", $v);
                }

                $x++;
            }
        } else {
            $query = $query->from('Entities\Instance_view', "i1")->andWhere('i0.id = i1.id');
        }

        if (!empty($order)) {
            $f_db = $this->get_common_class_fields_by_name(array_keys($order));
            $k = key($order);

            if ($f_db[$k]->type == 'int' || $f_db[$k]->type == 'decimal') {
                $query = $query->andWhere('i0.field_name = :order')->setParameter('order', $k)->
                addSelect('CAST(i0.value as decimal) AS HIDDEN shit')->
                orderBy('shit', current($order));
            } else {
                $query = $query->andWhere('i0.field_name = :order')->setParameter('order', $k)->orderBy('i0.value', current($order));
            }
        }

        if (!empty($class)) {
            $query = $query->andWhere('i0.class_name = :class_name')->setParameter('class_name', $class);
        }

        if (!empty($in_id)) {
            $query = $query->andWhere('i0.id IN (:in_id)')->setParameter('in_id', $in_id);
        }

        if (!empty($paginator)) {
            $stmt = $this->getEntityManager()->getConnection()->prepare('select count(*) from (' . $this->getFullSQL($query->getQuery()) . ') as t1');
            $stmt->execute();
            $paginator->setCountRow((int)$stmt->fetchColumn());
            $query->setFirstResult($paginator->getBeginElement())->setMaxResults($paginator->getSize());
        }

        $ind = [];
        foreach ($query->getQuery()->getResult() as $item) {
            array_push($ind, $item['id']);
        }

        if (empty($ind)) return [];
        $qb = $this->_em->createQueryBuilder();
        $query_in = $qb->select('i, field(i.id,' . implode(',', $ind) . ') as HIDDEN field')->from('Entities\Instance_view', 'i')->andWhere('i.id IN (:ids)')->setParameter('ids', $ind)->orderBy('field');

        $res = [];
        foreach ($query_in->getQuery()->getResult() as $item) {
            if (array_key_exists($item->id, $res)) {
                $res[$item->id]->fields[$item->field_name] = ['id_field' => $item->id_field, 'id' => $item->id_value, 'value' => $item->value, 'type' => $item->type, 'unit' => $item->unit, 'order' => $item->order, 'extra' => $item->extra, 'field_loc_name' => $item->field_loc_name];
            } else {
                $res[$item->id] = new Entities\Instance_obj();
                $res[$item->id]->fields = [];
                $res[$item->id]->class_name = $item->class_name;
                $res[$item->id]->id = $item->id;
                $res[$item->id]->fields[$item->field_name] = ['id_field' => $item->id_field, 'id' => $item->id_value, 'value' => $item->value, 'type' => $item->type, 'unit' => $item->unit, 'order' => $item->order, 'extra' => $item->extra, 'field_loc_name' => $item->field_loc_name];
            }
        }

        return $res;
    }

    /**
     * @return Entities\Common_class
     */
    public function read_common_class($id)
    {
        return $this->_em->find('Entities\common_class', $id);
    }

    /**
     * @return Entities\Common_class
     */
    public function read_common_class_by_name($name)
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('i')
            ->from('Entities\common_class', 'i')
            ->andwhere('i.name = :name')
            ->setParameter('name', $name)
            ->getQuery();
        return $query->getSingleResult();
    }

    /**
     * @return Entities\Instance
     */
    public function read_instance($id)
    {
        return $this->_em->find('Entities\instance', $id);
    }

    /**
     * @return Entities\Instance_obj
     */
    public function read_view_instance($id)
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('i')
            ->from('Entities\Instance_view', 'i')
            ->andwhere('i.id = :id_inst')
            ->setParameter('id_inst', $id)
            ->orderBy('i.order', 'DESC')
            ->getQuery();
        return $this->parse_view_instance($query->getResult());
    }

    /**
     * @return Entities\Common_class_field
     */
    public function read_common_class_field($id)
    {
        return $this->_em->find('Entities\common_class_field', $id);
    }

    public function create_common_class($name, $loc_name, $ids, $rules)
    {
        $obj = new Entities\Common_class();
        $obj->name = $name;
        $obj->loc_name = $loc_name;

        $fields = [];
        foreach ($ids as $i) {
            $fields[$i] = $this->_em->getReference('Entities\Common_class_field', $i);
        }
        $obj->setFields($fields);

        $this->_em->persist($obj);
        $this->_em->getConnection()->beginTransaction();
        try {
            $this->_em->flush();
        } catch (\Exception $e) {
            $this->last_error = $e->getMessage();
            if (stripos($this->last_error, 'duplicate') != FALSE) {
                $this->last_error = self::$UNIQUE_NAME;
            }
            $this->_em->getConnection()->rollback();
            $this->_em->close();
            return FALSE;
        }

        $this->_em->refresh($obj);

        foreach ($obj->links as $l) {
            $id = $l->__field->get_id();
            if (array_key_exists($id, $rules) !== FALSE) {
                $l->rules = $rules[$id];
                $this->_em->persist($obj);
            }
        }

        try {
            $this->_em->flush();
        } catch (\Exception $e) {
            $this->last_error = $e->getMessage();
            $this->_em->getConnection()->rollback();
            $this->_em->close();
            return FALSE;
        }

        $this->_em->getConnection()->commit();
        return TRUE;
    }

    public function create_common_class_field($name, $loc_name, $type, $extra, $unit)
    {
        $obj = new Entities\Common_class_field();
        $obj->name = $name;
        $obj->type = $type;
        $obj->unit = $unit;
        $obj->extra = $extra;
        $obj->loc_name = $loc_name;
        $this->_em->persist($obj);
        try {
            $this->_em->flush();
        } catch (\Exception $e) {
            $this->last_error = $e->getMessage();
            if (stripos($this->last_error, 'duplicate') != FALSE) {
                $this->last_error = self::$UNIQUE_NAME;
            }
            return FALSE;
        }
        return TRUE;
    }

    public function create_instance($ids)
    {
        $obj = new Entities\Instance();
        foreach ($ids as $i => $val) {
            $v = new Entities\Instance_value();
            $v->value = $val;
            $v->link = $this->_em->getReference('Entities\Common_class_field_link', $i);
            $v->instance = $obj;
            $obj->fields->add($v);
        }
        $this->_em->persist($obj);
        try {
            $this->_em->flush();
        } catch (\Exception $e) {
            $this->last_error = $e->getMessage();
            echo $e->getMessage();
            if (stripos($this->last_error, 'duplicate') != FALSE) {
                $this->last_error = self::$UNIQUE_NAME;
            }
            return FALSE;
        }
        return TRUE;
    }

    public function update_common_class($id, $name, $loc_name, $ids)
    {
        $obj = $this->read_common_class($id);
        $obj->name = $name;
        $obj->loc_name = $loc_name;
        $adds = [];
        $class = $this->_em->getReference('Entities\Common_class', $id);
        //add
        if (!empty($ids)) {
            $x = count($ids);
            foreach ($ids as $i) {
                $f_d = $this->read_common_class_field($i);
                if (!$obj->fields->contains($f_d)) {
                    array_push($adds, $i);
                    $l = new Entities\Common_class_field_link();
                    //sort new link
                    foreach ($ids as $_i) {
                        if ($_i == $f_d->get_id()) {
                            $l->order = $x;
                            $x--;
                        }
                    }
                    $l->rules = '';
                    $l->__field = $f_d;
                    $l->common_class = $class;
                    $obj->links->add($l);
                } else {
                    //sort exists link
                    foreach ($obj->links as $l) {
                        $x = count($ids);
                        foreach ($ids as $i) {
                            if ($i == $l->__field->id) {
                                $l->order = $x;
                                break;
                            }
                            $x--;
                        }
                    }
                }
            }

            //remove
            foreach ($obj->fields as $f) {
                if (array_search($f->id, $ids) === FALSE && array_search($f->id, $adds) === FALSE) {
                    if (!empty($f->id)) $obj->fields->removeElement($f);
                }
            }
        }

        $this->_em->persist($obj);
        $this->_em->getConnection()->beginTransaction();
        try {
            $this->_em->flush();
        } catch (\Exception $e) {
            $this->last_error = $e->getMessage();
            if (stripos($this->last_error, 'duplicate') != FALSE) {
                $this->last_error = self::$UNIQUE_NAME;
            }
            $this->_em->getConnection()->rollback();
            $this->_em->close();
            return FALSE;
        }

        $this->_em->refresh($obj);

        //insert links to instances
        if (!empty($adds)) {
            $inst_upd = $this->get_view_instances($obj->name);
            foreach ($inst_upd as $i_u) {
                $inst = $this->_em->getReference('Entities\Instance', $i_u->id);
                foreach ($obj->links as $l) {
                    if (array_search($l->__field->get_id(), $adds) !== FALSE) {
                        $v = new Entities\Instance_value();
                        $v->value = '';
                        $v->link = $this->_em->getReference('Entities\Common_class_field_link', $l->id);
                        $v->instance = $inst;
                        $this->_em->persist($v);
                    }
                }
            }
            try {
                $this->_em->flush();
            } catch (\Exception $e) {
                $this->last_error = $e->getMessage();
                $this->_em->getConnection()->rollback();
                $this->_em->close();
                return FALSE;
            }
        }
        $this->_em->getConnection()->commit();
        return TRUE;
    }

    public function update_instance($id, $ids)
    {
        $obj = $this->read_instance($id);
        foreach ($obj->fields as $item) {
            if (isset($ids[$item->id])) {
                $val = $ids[$item->id];
                $item->value = $val;
            }
        }
        $this->_em->persist($obj);
        try {
            $this->_em->flush();
        } catch (\Exception $e) {
            $this->last_error = $e->getMessage();
            echo $e->getMessage();
            if (stripos($this->last_error, 'duplicate') != FALSE) {
                $this->last_error = self::$UNIQUE_NAME;
            }
            return FALSE;
        }
        return TRUE;
    }


    public function update_common_class_field($id, $name, $loc_name, $type, $extra, $unit)
    {
        $obj = $this->read_common_class_field($id);
        $obj->name = $name;
        $obj->type = $type;
        $obj->unit = $unit;
        $obj->extra = $extra;
        $obj->loc_name = $loc_name;
        $this->_em->persist($obj);
        try {
            $this->_em->flush();
        } catch (\Exception $e) {
            $this->last_error = $e->getMessage();
            if (stripos($this->last_error, 'duplicate') != FALSE) {
                $this->last_error = self::$UNIQUE_NAME;
            }
            return FALSE;
        }
        return TRUE;
    }

    public function delete_common_class($id)
    {
        $obj = $this->read_common_class($id);
        $this->_em->remove($obj);
        try {
            $this->_em->flush();
        } catch (\Exception $e) {
            $this->last_error = $e->getMessage();
            return FALSE;
        }
        return TRUE;
    }

    public function delete_common_class_field($id)
    {
        $obj = $this->read_common_class_field($id);
        $this->_em->remove($obj);
        try {
            $this->_em->flush();
        } catch (\Exception $e) {
            $this->last_error = $e->getMessage();
            return FALSE;
        }
        return TRUE;
    }

    public function delete_instance($id)
    {
        $obj = $this->read_instance($id);
        $this->_em->remove($obj);
        try {
            $this->_em->flush();
        } catch (\Exception $e) {
            $this->last_error = $e->getMessage();
            return FALSE;
        }
        return TRUE;
    }
}