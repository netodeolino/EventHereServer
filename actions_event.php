<?php

    include_once 'model/Event.class.php';
    include_once 'dao/EventDAO.class.php';
    include_once 'model/JsonParser.class.php';
    
    //EVENT
    function add_event($json){
        $event = JsonParser::toEvent($json);
        $dao = new EventDAO();
        $res = $dao->insert($event);
        $dao->close();
        $res = "{\"events\":[" . $event->toJson() . "]}";
        return $res;
    }
    
    function update_event($json){
        $event = JsonParser::toEvent($json);
        $dao = new EventDAO();
        $dao->update($event);
        $dao->close();
        $res = "{\"events\":[" . $event->toJson() . "]}";
        return $res;
    }
    
    function remove_event($id){
        $dao = new EventDAO();
        $res = $dao->delete($id);
        $dao->close();
        return "{\"ints\" : [".((int)$res)."]}";
    }
    
    function find_event_by_id($id){
        $dao = new EventDAO();
        $event = $dao->findById($id);
        $dao->close();
        if($event==null) $res = "{\"events\":[]}";
        else $res = "{\"events\":[" . $event->toJson() . "]}";
        return $res;
    }
    
    function find_block_events($pos, $len){
        $dao = new EventDAO();
        $events = find_block($dao->findAll(), $pos, $len);
        $dao->close();
        $res = "{\"events\":". JsonParser::encondeObjectArray($events). "}";
        return $res;
    }
    
    function find_all_events(){
        $dao = new EventDAO();
        $events = $dao->findAll();
        $dao->close();
        $res = "{\"events\":". JsonParser::encondeObjectArray($events). "}";
        return $res;
    }
    
    function update_user_event($idE, $idU, $confirmed){
        $dao = new UserEventDAO();
        $ue = $dao->findById($idU, $idE);
        if($ue == null) $res = $dao->insert ($idU, $idE, ((int)$confirmed));
        else $res = $dao->update ($idU, $idE, ((int)$confirmed));
        $dao->close();
        return "{\"ints\" : [".((int)$res)."]}";
    }
    
    function invite($idE, $idU, $confirmed){
        $dao = new UserEventDAO();
        $ue = $dao->findById($idU, $idE);
        if($ue == null) $res = $dao->insert ($idU, $idE, ((int)$confirmed));
        else $res = 1;
        $dao->close();
        return "{\"ints\" : [".((int)$res)."]}";
    }
    
    function invite_many_people($idE, $idsUsersJson){
        $idUs = JsonParser::decodeSimpleArray($idsUsersJson);
        $res = true;
        foreach($idUs['ints'] as $idU){
            $res = ($res && (invite($idE, $idU, false)));
        }
        return "{\"ints\" : [".((int)$res)."]}";
    }
    
    function find_users_event($idE, $confirmed){
        $dao = new UserDAO();
        if($confirmed) $l = $dao->findByEventConfirmed($idE);
        else $l = $dao->findByEventInvited($idE);
        $dao->close();
        $res = "{\"users\":". JsonParser::encondeObjectArray($l). "}";
        return $res;
    }
    
    function find_block_users_event($idE, $confirmed, $pos, $len){
        $dao = new UserDAO();
        if($confirmed) $l = $dao->findByEventConfirmed($idE);
        else $l = $dao->findByEventInvited($idE);
        $dao->close();
        $res = "{\"users\":". JsonParser::encondeObjectArray(find_block($l, $pos, $len)). "}";
        return $res;
    }

    //user_id=0&distance=0&type=&specification_type=&date=&hour=&latitude=&longitude=
    
    function search_available_to_user($idU, $distance,$type,$specification,$date,$hour,$latitude,$longitude){
        $r = eventsAvailables($idU, findByParams($distance, $type, $specification, $date, $hour, $latitude, $longitude));
        return "{\"events\":". JsonParser::encondeObjectArray($r). "}";
    }
    
    function search_block_available_to_user($pos, $len, $idU, $distance,$type,$specification,$date,$hour,$latitude,$longitude){
        $r = find_block(eventsAvailables($idU, findByParams($distance, $type, $specification, $date, $hour, $latitude, $longitude)),$pos,$len);
        return "{\"events\":". JsonParser::encondeObjectArray($r). "}";
    }
    
    function eventsAvailables($idU, $events){
        $res = [];
        $dao = new UserEventDAO();
        foreach($events as $e){
            $ue = $dao->findById($idU, $e->getId());
            if($ue==null){
                if(!$e->isSecret()) { $res[] = $e;}
            } else {
                if($ue['confirmed']==false) { $res[] = $e;}
            } 
        }
        $dao->close();
        return $res;
    }
    
    function findByParams($distance,$type,$specification,$date,$hour,$latitude,$longitude){
        $dao = new EventDAO();
        $l = $dao->findUnfinished();
        $dao->close();
        $res = [];
        foreach($l as $e){
            if($distance!=0 && $distance!="" && $latitude!="" && $longitude!="") 
                if(!$e->getDeparture()->insideRadius($latitude, $longitude,$distance) && 
                        !$e->getArrival()->insideRadius($latitude, $longitude,$distance)) {continue;}
            if($type!="") {
                if($type!=EventType::toString($e->getType()->getType())) {continue;}
                if($type==EventType::toString(EventType::OTHER) && $specification!=null && 
                        $specification!=$e->getType()->getEspecification()) {continue;}
            }
            if($date!=null && $date!=explode(" ",$e->getDate())[0]) {continue;}
            if($hour!=null && $hour!=explode(" ",$e->getDate())[1]) {continue;}
            $res[] = $e;
        }
        return $res;
    }
    
?>