<?php

/**
 * Created by PhpStorm.
 * User: EgorDm
 * Date: 02-Oct-16
 * Time: 23:12
 */

// Een blok met php die ergens tussen kan worden gezet
class ViewBlock
{

    private $_views = array();
    private $_active = null;

    public function __construct()
    {
    }

    //Opent blok om php op te vangen
    public function start($name)
    {
        $this->_views[$name] = '';
        $this->_active = $name;
        ob_start();
    }

    //Sluit blok om php op te vangen
    public function end()
    {
        if ($this->_active == null) return;
        $buffer = ob_get_clean();
        $this->_views[$this->_active] .= $buffer;
        $this->_active = null;
    }

    public function get($name) {
        return $this->_views[$name];
    }

    public function set($name, $content) {
        $this->_views[$name] = $content;
    }

}