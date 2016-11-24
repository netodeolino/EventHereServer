<?php

include_once 'FriendsDAO.class.php';
include_once 'UserEventDAO.class.php';

class UserDAO extends AbstractDAO {
    
    private $userEventDao;
    private $friendsDao;
    
    public function __construct($connection=null){
        parent::__construct($connection);
        $this->friendsDao = new FriendsDAO($this->currentConnection());
        $this->userEventDao = new UserEventDAO($this->currentConnection());
    }
    
    public function delete($id) {
        $this->friendsDao->deleteByFollow($id);
        $this->userEventDao->deleteByUser($id);
        $sql = "DELETE FROM user WHERE id = " . $id . ";";
        return mysqli_query($this->getConnection(), $sql);
    }

    public function findAll() {
        return $this->find("SELECT * FROM user ORDER BY name;");
    }

    public function findById($id) {
        return $this->find("SELECT * FROM user WHERE id=".$id.";", true);
    }
    
    public function findByName($name){
        return $this->find("SELECT * FROM user WHERE name LIKE '%".$name."%' ORDER BY name;");
    }
    
    public function findByNameOrMail($str){
        return $this->find("SELECT * FROM user WHERE name LIKE '%".$str."%' OR mail LIKE '%".$str."%' ORDER BY name;");
    }
    
    public function findByMail($mail){
        return $this->find("SELECT * FROM user WHERE mail='".$mail."';", true);
    }
    
    public function findByEventConfirmed($idE){
        return $this->find("SELECT u.* FROM user u, user_event e WHERE e.confirmed=TRUE and e.user_id=u.id and e.event_id=".$idE." ORDER BY u.name;");
    }
    
    public function findByEventInvited($idE){
        return $this->find("SELECT u.* FROM user u, user_event e WHERE e.confirmed=FALSE and e.user_id=u.id and e.event_id=".$idE." ORDER BY u.name;");
    }
    
    public function findFriends($idU){
        return $this->find("SELECT u.* FROM user u, friends f WHERE u.id=f.followed_id and f.follower_id=".$idU." ORDER BY u.name;");
    }
    
    public function findFollowers($idU){
        return $this->find("SELECT u.* FROM user u, friends f WHERE u.id=f.follower_id and f.followed_id=".$idU."  ORDER BY u.name;");
    }

    public function insert($user) {
        if ($user == null)
            return false;
        $sql = "INSERT INTO user VALUES (default, '".$user->getName()."', '".$user->getMail()."', '".$user->getPassword()."', '".$user->getDescription()."', ".$user->getGender().", '".$user->getImage()."');";
        $res = mysqli_query($this->getConnection(), $sql);
        $user->setId(mysqli_insert_id($this->getConnection()));
        
        foreach ($user->getFriends() as $f) $this->friendsDao->insert ($f->getId(), $user->getId());
        foreach ($user->getInvited() as $i) $this->userEventDao->insert ($user->getId(), $i->getId(), false);
        foreach ($user->getEvents() as $e) $this->userEventDao->insert ($user->getId(), $e->getId(), true);
        
        return $res;
    }

    public function update($user) {
        if ($user == null)
            return false;
        $sql = "UPDATE user SET name='".$user->getName()."', mail='".$user->getMail()."', password='".$user->getPassword()."', "
                . "description='".$user->getDescription()."', gender=".$user->getGender().", image='".$user->getImage()."' "
                . "WHERE id=" . $user->getId() . ";";
        
        foreach ($user->getInvited() as $i) $this->userEventDao->update($user->getId(), $i->getId(), false);
        foreach ($user->getEvents() as $e) $this->userEventDao->update($user->getId(), $e->getId(), true);
        
        return mysqli_query($this->getConnection(), $sql);
    }
    
    private function find($sql=null, $one=false){
        $res = [];
        $result = mysqli_query($this->getConnection(), $sql);
        while ($row = mysqli_fetch_array($result)) {
            $res[] = new User((int)$row['id'], $row['name'], $row['mail'], $row['password'], $row['description'], (int)$row['gender'], $row['image']);
        }
        mysqli_free_result($result);
        if($one) return ((count($res)>0) ? $res[0] : null);
        return $res;
    }
    

}
