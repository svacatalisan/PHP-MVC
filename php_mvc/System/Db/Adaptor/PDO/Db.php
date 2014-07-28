<?php

class Db extends DB_Abstract {

    /**
     * Creates a connection to the database
     * @return if a connection already exists.
     * @throws Exception if the connection isn't successful.
     */
    protected function _connect() {
        // if we already have a PDO object, no need to re-connect.
        if ($this->_connection) {
            return;
        }

        // check for PDO extension
        if (!extension_loaded('pdo')) {
            throw new Exception('The PDO extension is required for this adapter but the extension is not loaded');
        }

        // create PDO connection
        $dsn = 'mysql:host=' . $this->config['host'] . ";dbname=" . $this->config['dbname'];
        try {
            $this->_connection = new PDO(
                            $dsn,
                            $this->config['username'],
                            $this->config['password']
            );
        } catch (PDOException $e) {

            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Verifies if a connection to the database is active
     * @return Boolean true if the connection is active, false otherwise.
     */
    public function isConnected() {
        return ((bool) ($this->_connection instanceof PDO));
    }

    /**
     * Closing the connection
     */
    public function closeConnection() {
        $this->_connection = null;
    }

    /**
     * Prepares an sql statement
     * @param String $sql 
     * @return String - the prepared statement
     */
    public function prepare($sql) {
        $this->_connect();
        $stmt = $this->_connection->prepare($sql);
        return $stmt;
    }

    /**
     * 
     * @param String $sql
     * @param array $bind 
     * Returns true if the query succeeded, false otherwise
     */
    public function query($sql, $bind = array()) {
        try {
            $this->_connect();
            $stmt = $this->_connection->prepare($sql);    //prepare statement
            return $stmt->execute($bind);    //execute query       
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Executes the query
     * @param String $sql
     * @throws Exception if the execution is not successful.
     */
    public function exec($sql) {

        try {
            $this->_connection->exec($sql);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

}

