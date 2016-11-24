<?php

include_once 'UserDAO.class.php';
include_once 'EventTypeDAO.class.php';
include_once 'LocationDAO.class.php';

class EventDAO extends AbstractDAO{
    
    private $ueDAO;
    private $userDAO;
    private $etDAO;
    private $lDAO;
    
    public function __construct($connection = null) {
        parent::__construct($connection);
        $this->ueDAO = new UserEventDAO($this->currentConnection());
        $this->userDAO = new UserDAO($this->currentConnection());
        $this->etDAO = new EventTypeDAO($this->currentConnection());
        $this->lDAO = new LocationDAO($this->currentConnection());
    }
    
    public function delete($id) {
        return $this->deleteEvent($this->findById($id));
    }
    
    public function deleteEvent($event){
        $this->etDAO->delete($event->getType()->getId());
        $this->lDAO->delete($event->getDeparture()->getId());
        $this->lDAO->delete($event->getArrival()->getId());
        $this->ueDAO->deleteByEvent($event->getId());
        $sql = "DELETE FROM event WHERE id = " . $event->getId() . ";";
        return mysqli_query($this->getConnection(), $sql);
    }
    
    public function deleteByOrganizer($idO){
        $res = true;
        $events = $this->findByOrganizer($idO);
        if($events!=null) {
            foreach ($events as $event){
                $this->deleteEvent($event);
            }
        }
        return $res;
    }



    public function findAll() {
        return $this->find("SELECT * FROM event;");
    }
    
    public function findUnfinished() {
        return $this->find("SELECT * FROM event WHERE date>=now();");
    }
    
    public function findByOrganizer($idO) {
        return $this->find("SELECT * FROM event WHERE organizer_id=".$idO.";");
    }

    public function findById($id) {
        return $this->find("SELECT * FROM event WHERE id=".$id.";", true);
    }
    
    public function findByUser($idU){
        return $this->find("SELECT e.* FROM event e, user_event u WHERE e.date>=now() and e.id=u.event_id and u.user_id=".$idU.";");
    }
    
    public function findByUserConfirmed($idU){
        return $this->find("SELECT e.* FROM event e, user_event u WHERE e.date>=now() and u.confirmed=TRUE and e.id=u.event_id and u.user_id=".$idU.";");
    }
    
    public function findByUserInvited($idU){
        return $this->find("SELECT e.* FROM event e, user_event u WHERE e.date>=now() and u.confirmed=FALSE and e.id=u.event_id and u.user_id=".$idU.";");
    }
    
    public function findByUserHistoric($idU){
        return $this->find("SELECT e.* FROM event e, user_event u WHERE e.date<now() and u.confirmed=TRUE and e.id=u.event_id and u.user_id=".$idU.";");
    }
    
    public function findByType($type){
        return $this->find("SELECT e.* FROM event e, event_type t WHERE e.type_id=t.id and t.type=".$type.";");
    }

    public function insert($event) {
        if ($event == null)
            return false;
        
        $this->etDAO->insert($event->getType());
        $this->lDAO->insert($event->getDeparture());
        $this->lDAO->insert($event->getArrival());
        
        $sql = "INSERT INTO event VALUES (default, ".$event->getType()->getId().", '".$event->getDate().
                "', ".$event->getDeparture()->getId().", ".$event->getArrival()->getId().", '".$event->getDescription().
                "', ".((int)$event->isSecret()).", ".$event->getOrganizer()->getId().", ".((int)$event->isOver()).");";
        
        $res = mysqli_query($this->getConnection(), $sql);
        $event->setId(mysqli_insert_id($this->getConnection()));
        
        foreach ($event->getGuests() as $g) $this->ueDAO->insert ($g->getId(), $event->getId(), false);
        foreach ($event->getUsers() as $u) $this->ueDAO->insert ($u->getId(), $event->getId(), true);
        
        return $res;
    }

    public function update($event) {
        if ($event == null)
            return false;
        
        $this->etDAO->update($event->getType());
        $this->lDAO->update($event->getDeparture());
        $this->lDAO->update($event->getArrival());
        
        $sql = "UPDATE event SET type_id=".$event->getType()->getId().", date='".$event->getDate().
                "', departure_id=".$event->getDeparture()->getId().", arrival_id=".$event->getArrival()->getId().
                ", description='".$event->getDescription()."', secret=".((int)$event->isSecret()).
                ", organizer_id=".$event->getOrganizer()->getId().", over=".((int)$event->isOver()).
                " WHERE id=".$event->getId().";";
    
        foreach ($event->getGuests() as $g) $this->ueDAO->update($g->getId(), $event->getId(), false);
        foreach ($event->getUsers() as $u) $this->ueDAO->update($u->getId(), $event->getId(), true);
        
        return mysqli_query($this->getConnection(), $sql);
    }
    
    private function find($sql, $one = false){
        $res = [];
        $result = mysqli_query($this->getConnection(), $sql);
        while ($row = mysqli_fetch_array($result)) {
            $type = $this->etDAO->findById($row['type_id']);
            $departure = $this->lDAO->findById($row['departure_id']);
            $arrival = $this->lDAO->findById($row['arrival_id']);
            $org = $this->userDAO->findById($row['organizer_id']);
            $res[] = new Event((int)$row['id'], $type, $row['date'], $departure, $arrival, $row['description'], (boolean)$row['secret'], $org, (boolean)$row['over']);
        }
        mysqli_free_result($result);
        if($one) return ((count($res)>0) ? $res[0] : null);
        return $res;
    }

}

?>

