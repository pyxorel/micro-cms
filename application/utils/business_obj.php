<?php
namespace Business_obj;

class Business_obj
{
    public static function parse_form($class, $param)
    {
        $res = [];
        foreach ($class->links as $item) {
            $item->__field->__load();
            $f = $item->__field;

            if (isset($param[$f->name])) {
                $res[$item->id] = $param[$f->name];
            } else {
                $res[$item->id] = '';
            }
        }
        return $res;
    }

}