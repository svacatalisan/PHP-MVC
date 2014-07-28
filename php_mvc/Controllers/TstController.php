<?php

class TstController extends BaseController {

    public function __construct() {
        
    }

    public function index() {
        global $config;

        $this->db = new Db($config);
        $table = 'users';
        $values = array('firstname' => 'Iunia', 'lastname' => 'Bujita', 'email' => 'iunia.bujita@softvision.ro', 'password' => sha1('dummypass'));
        $this->db->insert($table, $values);
        $this->db->update($table, array('lastname' => 'Ionescu'), array('iduser' => 122), array("="));
        $this->db->delete($table, array('iduser' => 122), array(">"));
        $result = $this->db->select("*", $table);
        $result = $this->db->isConnected();

        var_dump($this->db->getConnection());
    }

}

?>
