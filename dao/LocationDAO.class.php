<?php

include_once 'AbstractDAO.class.php';

class LocationDAO extends AbstractDAO {

    public function insert($location) {
        if ($location == null)
            return false;
        $sql = "INSERT INTO location VALUES (default, " . $location->getLatitude() . ", " . $location->getLongitude() . ", '" . $location->getAddress() . "');";
        $res = mysqli_query($this->getConnection(), $sql);
        $location->setId(mysqli_insert_id($this->getConnection()));
        return $res;
    }

    public function update($location) {
        if ($location == null)
            return false;
        $sql = "UPDATE location SET latitude=" . $location->getLatitude() . ", longitude=" . $location->getLongitude() . ", address='" . $location->getAddress() . "' WHERE id=" . $location->getId() . ";";
        return mysqli_query($this->getConnection(), $sql);
    }

    public function delete($id) {
        $sql = "DELETE FROM location WHERE id = " . $id . ";";
        return mysqli_query($this->getConnection(), $sql);
    }

    public function findById($id) {
        return $this->find("id", $id);
    }

    public function findAll() {
        return $this->find();
    }

    private function find($key=null, $value=null) {
        $flag = ($key != null);
        $sql = "SELECT * FROM location;";
        if ($flag) {
            if (is_string($value))
                $value = "'" . $value . "'";
            $sql = "SELECT * FROM location WHERE " . $key . "=" . $value . ";";
        }
        $location = [];
        $result = mysqli_query($this->getConnection(), $sql);

        while ($consulta = mysqli_fetch_array($result)) {
            $location[] = new Location((int)$consulta['id'], (double)$consulta['latitude'], (double)$consulta['longitude'], $consulta['address']);
        }
        mysqli_free_result($result);
        if ($flag)
            return ((count($location) > 0) ? $location[0] : null);
        return $location;
    }
}

?>