<?php 
include_once FRW_FILES . "TableLoader.php";
include_once FRW_FILES . "Models/Query.php";
include_once FRW_FILES . "Helpers/SQLConverterHelper.php";

//Heel belangrijk
abstract class Table {

	protected $_table = "";
	
	protected $_alias = "";
	
	protected $_primaryKey = "";
	
	protected $_displayField = "";
	
	protected $_entityClass = "";

    // Zet alle config variabelen in velden en runt standaard acties
	public function __construct(array $config = array())
    {
		if(!empty($config['table'])) {
			$this->_table = $config['table'];
		} else {
            $this->_table = strtolower(str_replace('Table', '', get_class($this)));
        }
		if(!empty($config['alias'])) {
			$this->_alias = $config['alias'];
		} else {
            $this->_alias = strtolower(str_replace('Table', '', get_class($this)));
        }
		if(!empty($config['primaryKey'])) {
			$this->_primaryKey = $config['primaryKey'];
		} else {
            $this->_primaryKey = 'id';
        }
		if(!empty($config['displayField'])) {
			$this->_displayField = $config['displayField'];
		} else {
            $this->_displayField = 'id';
        }
		if(!empty($config['entityClass'])) {
			$this->_entityClass = $config['entityClass'];
		} else {
            $this->_entityClass = str_replace('Table', '', get_class($this)).'Entity';
        }
		$this->initialize($config);
	}

	//Standaard acties. Kan zel bedenken
	public function initialize(array $config = array()) {
	}

	// Creert tabel in database met standaard velden
	public function create() {
		$connection = TableLoader::connectSQL();
        $query = SQLConverterHelper::buildCreateTable($this->_table, $this->getFields());
        if ($connection->query($query) === TRUE) {
            return true;
        } else {
            echo "Error creating table: " . $connection->error;
            return false;
        }
	}

	//Vraagt een array met alle velden
	public abstract function getFields();

	//Vraagt om tabel name
    public function getTableName() {
        return $this->_table;
    }

    /**
     * @return Entity
     */
    public function createEntity() {
        include_once(APP_ENTITIES . $this->_entityClass .'.php');
        return new $this->_entityClass();
    }


    // Maakt ene nieuwe query die opgevraagd kan worden
	public function query() {
		return new Query(TableLoader::connectSQL(), $this);
	}
	
	public function get() {
	}
	
	

}
?>