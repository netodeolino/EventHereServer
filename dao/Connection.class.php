<?php

class Connection {

    private $servidorBD;
    private $usuarioBD;
    private $senhaBD;
    private $nomeBD;
    private $conexao;

    //construtor
    public function __construct() {
        
        $this->servidorBD = "localhost";
        $this->usuarioBD = "root";
        $this->senhaBD = "";
        $this->nomeBD = "event_here";
        

        $this->conexao = mysqli_connect($this->servidorBD, $this->usuarioBD, $this->senhaBD, $this->nomeBD) or print (mysql_error());
    }

    //gets
    public function getServidorBD() {
        return $this->servidorBD;
    }

    public function getUsuarioBD() {
        return $this->usuarioBD;
    }

    public function getSenhaBD() {
        return $this->senhaBD;
    }

    public function getNomeBD() {
        return $this->nomeBD;
    }

    public function getConexao() {
        return $this->conexao;
    }

    //sets
    public function setServidorBD($servidorBD) {
        $this->servidorBD = $servidorBD;
    }

    public function setUsuarioBD($usuarioBD) {
        $this->usuarioBD = $usuarioBD;
    }

    public function setSenhaBD($senhaBD) {
        $this->senhaBD = $senhaBD;
    }

    public function setNomeBD($nomeBD) {
        $this->nomeBD = $nomeBD;
    }

    public function setConexao($conexao) {
        $this->conexao = $conexao;
    }

    //outros metodos
    //toString
    public function __toString() {
        $res = "";
        $res .= "Servidor do Banco de Dados: " . $this->servidorBD . "<br>";
        $res .= "Usuario do Banco de Dados: " . $this->usuarioBD . "<br>";
        $res .= "Senha do Banco de Dados: " . $this->senhaBD . "<br>";
        $res .= "Nome do Banco de Dados: " . $this->nomeBD . "<br>";
        $res .= "Conexao: " . $this->conexao . "<br>";
        return $res;
    }

    //fechar conexao
    public function close() {
        mysqli_close($this->conexao);
    }

}

?>
