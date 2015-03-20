<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Custom_paginator
{
    private $page;
    private $size;
    private $count;

    function  __construct($page = 1, $count=0, $size=10)
    {
        $this->page = $page;
        $this->size = $size;
        $this->count = $count;
    }

    public function skip()
    {
        return ($this->page - 1) * $this->size;
    }

    public function take()
    {
        return $this->size;
    }

    public function get_count()
    {
        return $this->count;
    }

    public function set_count($count)
    {
        $this->count= $count;
    }

    public function last()
    {
        $val = ($this->count < 1) ? 1 : $this->count;
        return  intval($val / $this->size + ((($val % $this->size) > 0) ? 1 : 0));
    }

    public function get_page()
    {
        return $this->page < $this->last() ? $this->page : $this->last();
    }

    public function set_page($page)
    {
        $this->page = $page;
    }

    public function set_size($s)
    {
        $this->size = $s;
    }

    public function get_size()
    {
        return $this->size;
    }

    function left()
    {
        return $this->page > 1;
    }

    function right()
    {
        return $this->page * $this->size < $this->count;
    }

    function next()
    {
        return $this->right() ? $this->page + 1 : $this->last();
    }

    function prev()
    {
        return $this->left() ? $this->page - 1 : 0;
    }

}
