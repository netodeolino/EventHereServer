<?php

include_once 'Connection.class.php';

abstract class AbstractDAO {

    private $connection;

    public function __construct($connection = null) {
        if($connection == null) $this->connection = new Connection();
        else $this->connection = $connection;
    }

    public function getConnection() {
        return $this->connection->getConexao();
    }
    
    public function currentConnection(){
        return $this->connection;
    }

    public function close() {
        $this->connection->close();
    }
    
    public abstract function insert($obj);
    
    public abstract function update($obj);
    
    public abstract function delete($id);
    
    public abstract function findById($id);
    
    public abstract function findAll();

}

?>