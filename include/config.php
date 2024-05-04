<?php
class dbConfig {
    protected $serverName;
    protected $userName;
    protected $password;
    protected $dbName;
    function dbConfig() {
        $this -> serverName = '127.0.0.1';
        $this -> userName = 'root';
        $this -> password = 'root';
        $this -> dbName = 'wsd';
    }
}
?>