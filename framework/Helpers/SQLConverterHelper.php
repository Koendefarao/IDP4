<?php

// Creert queries
class SQLConverterHelper
{

    // Maak basis van een select sql ccommand
    public static function buildSelect($find_config, $table_name)
    {
        $ret = 'SELECT ';
        if (empty($find_config['fields'])) {
            $ret .= '*'; // Als geen velden zijn geselecteerd haal ze allemaal
        } else {
            $first = true;
            foreach ($find_config['fields'] as $field) {
                $ret .= ($first ? '' : ',') . "`$field`";
                $first = false;
            }
        }
        $ret .= " FROM `$table_name`";
        return $ret;
    }

    //maak where deel van sql command
    public static function buildWhere($where)
    {
        $ret = 'WHERE ';
        $where_vars = array();
        $first = true;
        foreach ($where as $w_key => $w_var) {
            $ret .= $first ? '' : ' AND ';
            $res = self::loopWhere($w_key, $w_var);
            $ret .= $res[0];
            $where_vars = array_merge($where_vars, $res[1]);
            $first = false;
        }
        return array($ret, $where_vars);
    }

    // Maak binnen liggende where delen. voor als je and enz gebruikt
    private static function loopWhere($where_key, $where_var)
    {
        $ret = '';
        $where_vars = array();
        if (gettype($where_var) == 'array') {
            $ret .= '(';
            $first = true;
            foreach ($where_var as $w_key => $w_var) {
                $ret .= $first ? '' : " $where_key ";
                $res = self::loopWhere($w_key, $w_var);
                $ret .= $res[0];
                $where_vars = array_merge($where_vars, $res[1]);
                $first = false;
            }
            $ret .= ')';
        } else {
            $ret .= "$where_key ?";
            array_push($where_vars, $where_var);
        }
        return array($ret, $where_vars);
    }

    // Maka order sql
    public static function buildOrder($find_config)
    {
        return "ORDER BY `$find_config[order_field]` $find_config[order_type]";
    }

    // Bind varibelen. voor tgen sql collectie
    public static function buildBindVars($bind_vars)
    {
        $types = '';
        $variables = array();
        foreach ($bind_vars as $var) {
            switch (gettype($var)) {
                case 'boolean':
                    $types .= 'i';
                    array_push($variables, (int)$var);
                    break;
                case 'integer':
                    $types .= 'i';
                    array_push($variables, (int)$var);
                    break;
                case 'double':
                    $types .= 'd';
                    array_push($variables, (double)$var);
                    break;
                case 'string':
                    $types .= 's';
                    array_push($variables, (string)$var);
                    break;
            }
        }
        return array($types, $variables);
    }

    public static function buildCreateTable($table_name, $fields)
    {
        $ret = "CREATE TABLE IF NOT EXISTS `$table_name` (";
        $first = true;
        foreach ($fields as $key => $properties) {
            $field_q = ($first ? '' : ',') . "`$key`";
            switch ($properties[0]) {
                case 'string':
                    $field_q .= " VARCHAR($properties[1])";
                    break;
                case 'int':
                    $field_q .= " INT($properties[1])";
                    break;
                case 'float':
                    $field_q .= " FLOAT";
                    break;
                case 'text':
                    $field_q .= " TEXT";
                    break;
                case 'date':
                    $field_q .= " DATE";
                    break;
                case 'datetime':
                    $field_q .= " DATETIME";
                    break;
            }
            if(isset($properties[2]['primary']) && $properties[2]['primary'] == true) {
                $field_q .= ' UNSIGNED AUTO_INCREMENT PRIMARY KEY';
            }
            if(isset($properties[2]['null']) && $properties[2]['null'] == false) {
                $field_q .= ' NOT NULL';
            }
            $first = false;
            $ret .= $field_q;
        }
        $ret .= ')';
        return $ret;
    }

    public static function buildDelete($table_name) {
        return "DELETE FROM $table_name";
    }

    public static function buildInsert($table, $values) {
        $fields = array();
        foreach ($table->getFields() as $field => $config) {
            if(empty($config[2]['primary']) || $config[2]['primary'] == false) {
                array_push($fields, $field);
            }
        }

        $insert_vars = array();
        $ret = 'INSERT INTO ' .$table->getTableName() . '(' . implode(',', $fields ). ') VALUES ';
        $first_val = true;
        foreach ($values as $value) {
            $ret .= $first_val ? '(' : ',(';
            $first_val = false;
            $first_fld = true;
            foreach ($fields as $field) {
                array_push($insert_vars, $value->$field);
                $ret .= ($first_fld ? '' : ','). '?';
                $first_fld = false;
            }
            $ret .= ')';
        }
        return array($ret, $insert_vars);
    }

    public static function buildUpsert($table, $values) {
        $fields = array_keys($table->getFields());
        $insert_vars = array();
        $ret = 'INSERT INTO ' .$table->getTableName() . '(' . implode(',', $fields ). ') VALUES ';
        $first_val = true;
        foreach ($values as $value) {
            $ret .= $first_val ? '(' : ',(';
            $first_val = false;
            $first_fld = true;
            foreach ($fields as $field) {
                array_push($insert_vars, $value->$field);
                $ret .= ($first_fld ? '' : ','). '?';
                $first_fld = false;
            }
            $ret .= ')';
        }
        $ret .= ' ON DUPLICATE KEY UPDATE ';
        $first_fld = true;
        foreach ($fields as $field) {
            $ret .= ($first_fld ? '' : ',')."$field = VALUES($field)";
            $first_fld = false;
        }
        return array($ret, $insert_vars);
    }
}

?>