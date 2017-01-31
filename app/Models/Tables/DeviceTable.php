<?php
include_once FRW_FILES . "Models/Table.php";

class DeviceTable extends Table {

    public function __construct(array $config = array()) {
        parent::__construct(array(
            'displayField' => 'name',
        ));
    }
// Alle velden en hun eigenschappen
    public function getFields() {
        return array(
            'id' => array('int', 6, array('primary' => true)),
            'name' => array('string', 255, array('null' => false)),
        );
    }
}

?>