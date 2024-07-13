<?php
class Database {
    private $connection;

    public function __construct() {
        require_once 'config.php';
        
        $this->connection = mysqli_connect($db_host, $db_username, $db_password, $db_name);

        if(!$this->connection) {
            throw new Exception("Connection failed: ". mysqli_error($this->connection));
        }
    }

    public function query($sql) {
        $result = mysqli_query($this->connection, $sql);
        return $result;
    }

    public function close() {
        mysqli_close($this->connection);
    }
}