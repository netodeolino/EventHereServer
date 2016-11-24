<?php

include_once 'AbstractDAO.class.php';

class EventTypeDAO extends AbstractDAO {

    public function insert($type) {
        if ($type == null)
            return false;
        $sql = "INSERT INTO event_type VALUES (default, " . $type->getType() . ", '" . $type->getEspecification() . "');";
        $r = $this->put($type, $sql);
        return $r;
    }

    public function update($type) {
        if ($type == null)
            return false;
        $antigo = $this->findById($type->getId());
        if ($antigo == null)
            return false;
        if ($type->getType() == EventType::OTHER) {
            if($antigo->getType()!=EventType::OTHER) return $this->insert ($type);
            $id = $type->getId();
            $res = $this->put($type, "UPDATE event_type SET especification='" . $type->getEspecification() . "' WHERE id=" . $type->getId() . ";");
            $type->setId($id);
            return $res;
        } else {
            $tmp = $this->findByType($type->getType());
            if($antigo->getType()==EventType::OTHER) $this->delete($type->getId());
            $type->setId($tmp->getId());
            $type->setType($tmp->getType());
            $type->setEspecification($tmp->getEspecification());
            return true;
        }
        return false;
    }

    public function delete($id) {
        $sql = "DELETE FROM event_type WHERE id = " . $id . " and type=3;";
        return mysqli_query($this->getConnection(), $sql);
    }

    public function findById($id) {
        return $this->find("id", $id);
    }

    public function findAll() {
        return $this->find(null, null);
    }

    private function findByType($type) {
        if($type == EventType::OTHER) return null;
        return $this->find("type", $type);
    }

    private function findByEspecification($esp) {
        return $this->find("especification", $esp);
    }

    private function find($key, $value) {
        $flag = ($key != null);
        $sql = "SELECT * FROM event_type;";
        if ($flag) {
            if (is_string($value))
                $value = "'" . $value . "'";
            $sql = "SELECT * FROM event_type WHERE " . $key . "=" . $value . ";";
        }
        $type = [];
        $result = mysqli_query($this->getConnection(), $sql);

        while ($consulta = mysqli_fetch_array($result)) {
            $type[] = new EventType((int)$consulta['id'], (int)$consulta['type'], $consulta['especification']);
        }
        mysqli_free_result($result);
        if ($flag)
            return ((count($type) > 0) ? $type[0] : null);
        return $type;
    }

    private function put($type, $sql) {
        $id = 0;
        $flag = false;
        $t = $type->getType();
        if ($t == EventType::OTHER || $t == EventType::RUN || $t == EventType::BIKE || $t == EventType::HIKE) {
            $aux = null;
            if ($t != EventType::OTHER)
                $aux = $this->findByType($t);
            if ($aux != null) {
                $id = $aux->getId();
                $type->setType($aux->getType());
                $type->setEspecification($aux->getEspecification());
            } else {
                mysqli_query($this->getConnection(), $sql);
                $id = mysqli_insert_id($this->getConnection());
                $flag = true;
            }
        }
        $type->setId($id);
        return $flag;
    }

}

?>

