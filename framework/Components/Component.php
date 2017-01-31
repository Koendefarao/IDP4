<?php

// COmponent model
abstract class Component {
	public function __construct(array $config = array()) {
	    $this->initiate($config);
	}
	
	public function initiate(array $config = array()) {
	
	}

    public function beforeAction() {

    }

    public function afterAction($view) {

    }
}
?>