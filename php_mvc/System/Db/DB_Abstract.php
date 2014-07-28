<?php

abstract class DB_Abstract {

    protected $_connection = null;
    protected $config = null;

    /**
     * The class constructor
     * @param array $config - an array that contains the data that is necessary
     * for connecting to the db 
     */
    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * Verifies if the required data for the db connection is available
     * @param array $config 
     * @throws Exception if one of the required elements is missing
     */
    protected function _checkRequiredOptions(array $config) {
        // we need at least a dbname
        if (!array_key_exists('host', $config)) {
            throw new Exception("Configuration array must have a key for 'host' for login credentials");
        }

        if (!array_key_exists('dbname', $config)) {
            throw new Exception("Configuration array must have a key for 'dbname' that names the database instance");
        }

        if (!array_key_exists('password', $config)) {
            throw new Exception("Configuration array must have a key for 'password' for login credentials");
        }

        if (!array_key_exists('username', $config)) {
            throw new Exception("Configuration array must have a key for 'username' for login credentials");
        }
    }

    /**
     * Gets the connection array
     * @return PDO object that contains the connection data
     */
    public function getConnection() {
        $this->_connect();
        return $this->_connection;
    }

    /**
     * 
     * @param array $where - contains an array of the operands used in the where clause 
     * @param array $whereOp - contains an array of the operators used in the where clause 
     * @return String - the final where string
     * @throws Exception if the parameters are not correct
     */
    public function _where($where = array(), $whereOp = array()) {
        $operators = array("=", '>', "<", ">=", "<=", "<>");
        $whereString = '';
        $i = 0;
        $numItems = count($where);
        $keys = array_keys($where);
        $opKeys = array_keys($whereOp);
        if (($keys !== range(0, count($where) - 1)) & ($opKeys === range(0, count($whereOp) - 1))) {
            //if the $where array is associative and the $whereOp array is indexed
            foreach ($where as $key => $value) {
                if (in_array($whereOp[$i], $operators)) {
                    $whereString .= $key . $whereOp[$i];
                    if (is_numeric($value)) {
                        $whereString.=$value;
                    } else {
                        $whereString.= "'" . $value . "'";
                    }
                    if (++$i !== $numItems) {
                        $whereString .= ' AND ';
                    }
                } else {
                    throw new Exception("The accepted operators are: =, >, <, >=, <=, <>");
                }
            }
        }
        return $whereString;
    }

    /**
     * The insert statement
     * @param String $table - the name of the table
     * @param array $array_values - the values that need to be inserted
     * if the array is associative, the keys must correspond to the column names in the table
     */
    public function insert($table, array $array_values) {

        $sql = null;
        $keys = array_keys($array_values);
        $values = array_values($array_values);

        if ($keys !== range(0, count($array_values) - 1)) {  //if the array is associative
            $sql = "INSERT INTO " . $table . "(" . implode(",", $keys) . ") " . "VALUES('" . implode("','", $values) . "')";
        } else {
            $sql = "INSERT INTO " . $table . " VALUES('" . implode("','", $values) . "')";
        }
        $this->query($sql);
    }

    /**
     * The update statement
     * @param String $table - the name of the table
     * @param array $array_values - the values that need to be updated
     * the array must be associative
     * @param array $where - contains an array of the operands used in the where clause 
     * @param array $whereOp - contains an array of the operators used in the where clause 
     * @throws Exception if the function parameters do not contain correct data
     */
    public function update($table, array $array_values, $where = '', $whereOp = '') {
        $sql = null;
        $keys = array_keys($array_values);
        if ($keys !== range(0, count($array_values) - 1)) {  //if the array is associative
            $set = '';
            foreach ($array_values as $key => $value) {
                $set = $key . "='" . $value . "'";
            }
            $sql = "UPDATE " . $table . " SET " . $set;
            $whereString = '';
            if (is_array($where)) {       // if where is an array
                $whereString = $this->_where($where, $whereOp);
                $sql .= ' WHERE ' . $whereString; // add the where clause
            } elseif ($where != '') {
                throw new Exception("Update must receive an associative array for the where clause");
            }
        } else {
            throw new Exception("Update must receive an associative array for setting the values");
        }
        $this->query($sql);
    }

    /**
     * The delete statement 
     * @param String $table - the name of the table
     * @param array $where - contains an array of the operands used in the where clause 
     * @param array $whereOp  - contains an array of the operators used in the where clause 
     * @throws Exception if the function parameters do not contain correct data
     */
    public function delete($table, $where = '', $whereOp = '') {
        $sql = "DELETE FROM " . $table;
        $whereString = '';
        if (is_array($where)) {       // if where is an array
            $whereString = $this->_where($where, $whereOp);
            $sql .=' WHERE ' . $whereString;  // add the where clause
        } elseif ($where != '') {
            throw new Exception("Update must receive an associative array for the where clause");
        }
        $this->query($sql);
    }

    /**
     * 
     * @param array of columns or String "*" $what - what needs to be selected
     * @param String $table - the name of the table
     * @param array $where - contains an array of the operands used in the where clause
     * @param array $whereOp - contains an array of the operators used in the where clause
     * @param type $groupBy - contains an array with the column names used by group by
     * @param array $orderBy - contains an array with the column names used by order by
     * @param String $orderByType Should be "ASC" or "DESC"
     * @return array containing the result of the query or false if the query isn't successful.
     * @throws Exception if the function parameters do not contain correct data
     */
    public function select($what, $table, $where = '', $whereOp = '', $groupBy = '', $orderBy = '', $orderByType = 'DESC') { //simple select
        $sql = "SELECT ";

        if (is_array($what)) {    // if what is an array
            $values = array_values($what);
            $sql .= implode("','", $values);
        } elseif ($what === '*') {
            $sql .= $what;
        } else {
            throw new Exception("Select must receive an array or '*' for the column names.");
        }
        $sql .= ' FROM ' . $table;
        if (is_array($where)) {       // if where is an array
            $whereString = $this->_where($where, $whereOp);
            $sql .=' WHERE ' . $whereString;  // add the where clause
        } elseif ($where != '') {
            throw new Exception("Select must receive an associative array for the where clause");
        }

        if (is_array($groupBy)) {       // if group by is an array
            $sql .=' GROUP BY ' . implode(",", $groupBy);  // add the groupby clause
        } elseif ($groupBy != '') {
            throw new Exception("Select must receive an array for the groupBy clause");
        }

        if (is_array($orderBy)) {       // if group by is an array
            $sql .=' ORDER BY ' . implode(",", $orderBy);  // add the groupby clause
            if (($orderByType === 'ASC') or ($orderByType === 'DESC')) {
                $sql .=" " . $orderByType;
            } else {
                throw new Exception("Select must receive ASC or DESC as order by type.");
            }
        } elseif ($orderBy != '') {
            throw new Exception("Select must receive an array for the orderBy clause");
        }
        if ($this->exec($sql)===false){
            return false;
        }
        return $this->fetchAll($sql, array());
    }

    /**
     * Fetch all the values from a db result 
     * @param String $sql
     * @param array $bind
     * @return array
     */
    public function fetchAll($sql, $bind = array()) {
        $stmt = $this->prepare($sql, $bind);
        $stmt->execute($bind);
        $result = $stmt->fetchAll();
        return $result;
    }

    /**
     * Fetch one row from a db result 
     * @param String $sql
     * @param array $bind
     * @return array
     */
    public function fetchRow($sql, $bind = array()) {
        $stmt = $this->prepare($sql, $bind);
        $stmt->execute($bind);
        $result = $stmt->fetch();
        return $result;
    }

    /**
     * Fetch one row from a db result 
     * @param String $sql
     * @param array $bind
     * @return array
     */
    public function fetchOne($sql, $bind = array()) {
        $stmt = $this->prepare($sql, $bind);
        $stmt->execute($bind);
        $result = $stmt->fetchColumn(0);
        return $result;
    }

    /**
     * Abstract Methods
     */
    abstract protected function _connect();

    abstract public function isConnected();

    abstract public function closeConnection();

    abstract public function prepare($sql);

    abstract public function query($sql);
}
