<?php
include_once FRW_FILES . "Helpers/SQLConverterHelper.php";

//Onderdeel van mijn orm
// Genereert mysql queries
class Query {

    /** @var mysqli */
	protected $_connection = null;

    /** @var Table */
	protected $_table = null;
	
	protected $_type = null;
	
	protected $_finder = array(
		'fields' => null,
		'limit' => -1,
		'order_field' => null,
		'order_type' => 'DESC',
	);

    protected $_upsert = array(
        'fields' => null,
        'values' => null,
    );

    protected $_where = null;
	

	//TODO: call me
	protected $_count;
	
	public function __construct($connection, $table)
    {
		$this->_connection = $connection;
		$this->_table = $table;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function select($fields = array()) {
		$this->_finder['fields'] = $fields;
		$this->_type = 'select';
        return $this;
	}

    /**
     * @param $field
     * @param bool $desc
     * @return $this
     */
    public function order($field, $desc = true) {
		$this->_finder['order_field'] = $field;
		$this->_finder['order_type'] = $desc ? 'DESC' : 'ASC';
		$this->_type = 'select';
        return $this;
	}

    /**
     * @param null $config
     * @return $this
     */
    public function where($config = null) {
		$this->_where = $config;
        return $this;
	}

    public function delete() {
        $this->_type = 'delete';
        return $this;
    }

    public function insert($values) {
        $this->_upsert['values'] = $values;
        $this->_type = 'insert';
        return $this;
    }

    public function upsert($values) {
        $this->_upsert['values'] = $values;
        $this->_type = 'update';
        return $this;
    }
	
	public function execute() {
	    $ret = null;
		switch($this->_type) {
			case 'select':
                $ret = $this->executeSelect();
				break;
            case 'delete':
                $ret = $this->executeDelete();
                break;
            case 'insert':
                $ret = $this->executeInsert();
                break;
            case 'update':
                $ret = $this->executeUpsert();
                break;
		}
		return $ret;
	}

    private function executeSelect() {
		$query = SQLConverterHelper::buildSelect($this->_finder, $this->_table->getTableName());
		$bind_vars = null;
		if($this->_where != null) {
		    $res = SQLConverterHelper::buildWhere($this->_where);
			$where = $res[0];
            $bind_vars = SQLConverterHelper::buildBindVars($res[1]);
			$query .= ' '.$where;
		}
		
		if($this->_finder['order_field'] != null) {
			$order = SQLConverterHelper::buildOrder($this->_finder);
			$query .= ' '.$order;
		}

        if($this->_finder['limit'] != -1) {
            $query .= " LIMIT ".$this->_finder['limit'];
        }
		$stmt = $this->_connection->prepare($query);
        $ret = null;
		if ($stmt) {
            $stmt = self::bindVariables($stmt, $bind_vars);
            $stmt->execute();
            $fields = self::bindResult($stmt);
            $ret = array();

            $i = 0;
            while ($stmt->fetch()) {
                $results[$i] = array();
                $ent = $this->_table->createEntity();
                foreach($fields as $k => $v) {
                    $ent->$k = $v;
                }
                $i++;

                array_push($ret, $ent);
            }
			$stmt->close();
		}
        return $ret;
	}

	private function executeDelete() {
        $query = SQLConverterHelper::buildDelete($this->_table->getTableName());
        if($this->_where == null) {
            return false;
        }

        $res = SQLConverterHelper::buildWhere($this->_where);
        $where = $res[0];
        $bind_vars = SQLConverterHelper::buildBindVars($res[1]);
        $query .= ' '.$where;

        $stmt = $this->_connection->prepare($query);
        $ret = false;
        if ($stmt) {
            $stmt = self::bindVariables($stmt, $bind_vars);
            $stmt->execute();
            if($stmt->affected_rows > 0) {
                $ret = true;
            }
        }
        $stmt->close();
        return $ret;
    }

    private function executeInsert() {
        $insert_q = SQLConverterHelper::buildInsert($this->_table, $this->_upsert['values']);
        $bind_vars = SQLConverterHelper::buildBindVars($insert_q[1]);

        $stmt = $this->_connection->prepare($insert_q[0]);
        $ret = false;
        if ($stmt) {
            $stmt = self::bindVariables($stmt, $bind_vars);
            $stmt->execute();
            if($stmt->affected_rows > 0) {
                $ret = true;
            }
        }
        $stmt->close();
        return $ret;
    }

    private function executeUpsert() {
        $upsert_q = SQLConverterHelper::buildUpsert($this->_table, $this->_upsert['values']);
        $bind_vars = SQLConverterHelper::buildBindVars($upsert_q[1]);
        $stmt = $this->_connection->prepare($upsert_q[0]);
        $ret = false;
        if ($stmt) {
            $stmt = self::bindVariables($stmt, $bind_vars);
            $stmt->execute();
            if($stmt->affected_rows > 0) {
                $ret = true;
            }
        }
        $stmt->close();
        return $ret;
    }


    private static function bindVariables($stmt, $variables) {
        if(!empty($variables)) {
            $types = $variables[0];
            $params = $variables[1];
            $bind_names[] = $types;
            for ($i=0; $i<count($params);$i++)
            {
                $bind_name = 'bind' . $i;
                $$bind_name = $params[$i];
                $bind_names[] = &$$bind_name;
            }
            call_user_func_array(array($stmt,'bind_param'),$bind_names);
        }
        return $stmt;
    }

    private static function bindResult($stmt) {
        $meta = $stmt->result_metadata();
        while ($field = $meta->fetch_field()) {
            $var = $field->name;
            $$var = null;
            $fields[$var] = &$$var;
        }
        call_user_func_array(array($stmt,'bind_result'),$fields);
        return $fields;
    }

}