<?php
include_once FRW_FILES . "Models/Table.php";

class HistoryTable extends Table
{

    public function __construct(array $config = array())
    {
        parent::__construct(array(
            'displayField' => 'name',
        ));
    }

// Alle velden en hun eigenschappen
    public function getFields()
    {
        return array(
            'id' => array('int', 6, array('primary' => true)),
            'created' => array('datetime', 255, array('null' => false)),
            'customer_id' => array('int', 8, array('null' => false)),
            'device_id' => array('int', 8, array('null' => false)),
        );
    }
}

?>