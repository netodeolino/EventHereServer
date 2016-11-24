<?php

include_once 'AbstractDAO.class.php';

class UserEventDAO extends AbstractDAO{
    
    public function delete($idU, $idE=0) {
        if($idE == 0) return false;
        $sql = "DELETE FROM user_event WHERE user_id=" . $idU . " and event_id=" . $idE . ";";
        return mysqli_query($this->getConnection(), $sql);
    }
    
    public function deleteByUser($idU){
        $sql = "DELETE FROM user_event WHERE user_id=" . $idU . ";";
        return mysqli_query($this->getConnection(), $sql);
    }
    
    
    public function deleteByEvent($idE){
        $sql = "DELETE FROM user_event WHERE event_id=" . $idE . ";";
        return mysqli_query($this->getConnection(), $sql);
    }

    public function findAll() {
        return $this->find();
    }

    public function findById($idU, $idE=0) {
        if($idE == 0) return null;
        $r = $this->find(" WHERE user_id=" . $idU . " and event_id=" . $idE . ";");
        return ((count($r)>0) ? $r[0] : null);
    }
    
    public function findByUser($idU){
        return $this->find(" WHERE user_id=" . $idU . ";");
    }
    
    public function findByUserConfirmed($idU, $ok){
        return $this->find(" WHERE user_id=" . $idU . " and confirmed=".((int)$ok).";");
    }
    
    public function findByEvent($idE){
        return $this->find(" WHERE event_id=" . $idE . ";");
    }
    
    public function findByEventConfirmed($idE, $ok){
        return $this->find(" WHERE event_id=" . $idE . " and confirmed=".((int)$ok).";");
    }

    public function insert($idU, $idE=0, $ok=false) {
        if(($idE==0) || ($this->findById($idU, $idE) != null)) return false;
        $sql = "INSERT INTO user_event VALUES (default, " . $idU . ", " . $idE . ", " . ((int)$ok) . ");";
        return mysqli_query($this->getConnection(), $sql);
    }

    public function update($idU, $idE=0, $ok=false) {
        if($idE == 0) return false;
        $i = ((int)$ok);
        $sql = "UPDATE user_event SET confirmed=" .$i. " WHERE user_id=" . $idU . " and event_id=" . $idE . ";";
        return mysqli_query($this->getConnection(), $sql);
    }
    
    private function find($where=null) {
        $sql = "SELECT * FROM user_event";
        $sql .= (($where==null) ? ";" : $where);
        $ok = [];
        $result = mysqli_query($this->getConnection(), $sql);
        while ($consulta = mysqli_fetch_array($result)) {
            $ok[] = $consulta;
        }
        mysqli_free_result($result);
        return $ok;
    }
}
