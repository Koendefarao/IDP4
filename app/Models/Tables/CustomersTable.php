<?php 
include_once FRW_FILES . "Models/Table.php";

class CustomersTable extends Table {

    // Wergaveveld is gebruikersnaam
	public function __construct(array $config = array()) {
		parent::__construct(array(
			'displayField' => 'username',
		));
	}

	// Alle velden en hun eigenschappen
	public function getFields() {
		return array(
			'id' => array('int', 6, array('primary' => true)),
			'username' => array('string', 255, array('null' => false)),
            'password' => array('string', 255, array('null' => false)),
            'first_name' => array('string', 255, array('null' => false)),
            'last_name' => array('string', 255, array('null' => false)),
			'email' => array('string', 255, array('null' => false)),
			'city' => array('string', 255, array('null' => false)),
			'address' => array('string', 255, array('null' => false)),
			'postcode' => array('string', 6, array('null' => false)),
            'created' => array('datetime', 255, array('null' => false)),
            'subscription' => array('int', 6, array('null' => false)),
            'iban_nr' => array('string', 18, array('null' => false)),
		);
	}
}

?>