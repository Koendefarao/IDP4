<?php
include_once FRW_FILES . "Models/Entity.php";
include_once FRW_FILES . 'Loaders/ConfigLoader.php';
include_once FRW_FILES . 'Components/AuthComponent.php';

class HistoryEntity extends Entity
{
    public function set($name, $value) {
        parent::set($name, $value);
    }
}