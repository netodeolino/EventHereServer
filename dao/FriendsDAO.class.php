<?php

include_once 'AbstractDAO.class.php';

class FriendsDAO extends AbstractDAO{
    
    public function delete($idFollower, $idFollowed=0) {
        if($idFollowed == 0) return false;
        $sql = "DELETE FROM friends WHERE followed_id=" . $idFollowed . " and follower_id=" . $idFollower . ";";
        return mysqli_query($this->getConnection(), $sql);
    }
    
    public function deleteByFollow($id){
        $sql = "DELETE FROM friends WHERE followed_id=" . $id . " or follower_id=" . $id . ";";
        return mysqli_query($this->getConnection(), $sql);
    }

    public function findAll() {
        return $this->find();
    }

    public function findById($idFollowed, $idFollower=0) {
        if($idFollower == 0) return null;
        $r = $this->find(" WHERE followed_id=" . $idFollowed . " and follower_id=" . $idFollower . ";");
        return ((count($r)>0) ? $r[0] : null);
    }
    
    public function findFollowers($idFollowed){
        return $this->find(" WHERE followed_id=" . $idFollowed . ";");
    }
    
    public function findFolloweds($idFollower){
        return $this->find(" WHERE follower_id=" . $idFollower . ";");
    }

    public function insert($idFollowed, $idFollower=0) {
        if($idFollower == 0 || ($this->findById($idFollowed, $idFollower) != null)) return false;
        $sql = "INSERT INTO friends VALUES (default, " . $idFollower . ", " . $idFollowed . ");";
        return mysqli_query($this->getConnection(), $sql);
    }

    public function update($idFollowed, $idFollower=0) {
        return false;
    }
    
    private function find($where=null) {
        $sql = "SELECT * FROM friends";
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
