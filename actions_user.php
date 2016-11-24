<?php

    include_once 'model/Event.class.php';
    include_once 'dao/EventDAO.class.php';
    include_once 'model/JsonParser.class.php';
    include_once 'util.php';
    
    //USER
    function add_user($json, $img){
        $user = JsonParser::toUser($json);
        $user->setImage($img);
        $dao = new UserDAO();
        $aux = $dao->findByMail($user->getMail());
        if($aux==null) $dao->insert($user);
        else $user->setId($aux->getId());
        $dao->close();
        $cod = ($aux==null) ? 200 : 101;
        return "{\"ints\" : [".$cod.", ".$user->getId()."]}";
    }
    
    function update_user($json, $img){
        $user = JsonParser::toUser($json);
        $user->setImage($img);
        $dao = new UserDAO();
        $aux = $dao->findByMail($user->getMail());
        $flag = true;
        if($aux==null || $aux->getId()==$user->getId()) $dao->update($user);
        else $flag = false;
        $dao->close();
        $cod = ($flag) ? 200 : 101;
        return "{\"ints\" : [".$cod."]}";
    }

    function remove_user($id){
        $uDao = new UserDAO();
        $aux = $uDao->delete($id);
        $uDao->close();
        $eDao = new EventDAO();
        $res = (($eDao->deleteByOrganizer($id)) && $aux);
        $eDao->close();
        return "{\"ints\" : [".((int)$res)."]}";
    }

    function find_user_by_id($id){
        $dao = new UserDAO();
        $user = $dao->findById($id);
        $dao->close();
        if($user==null) $res = "{\"users\":[]}";
        else $res = "{\"users\":[" . $user->toJson() . "]}";
        return $res;
    }
    
    function find_user_by_mail($mail){
        $dao = new UserDAO();
        $user = $dao->findByMail($mail);
        $dao->close();
        if($user==null) $res = "{\"users\":[]}";
        else $res = "{\"users\":[" . $user->toJson() . "]}";
        return $res;
    }
    
    function find_all_users(){
        $dao = new UserDAO();
        $users = $dao->findAll();
        $dao->close();
        $res = "{\"users\":". JsonParser::encondeObjectArray($users). "}";
        return $res;
    }
    
    function find_block_users($pos, $len){
        $dao = new UserDAO();
        $users = find_block($dao->findAll(), $pos, $len);
        $dao->close();
        $res = "{\"users\":". JsonParser::encondeObjectArray($users). "}";
        return $res;
    }
    
    function add_friend($idU, $idF){
        $dao = new FriendsDAO();
        $res = $dao->insert($idF, $idU);
        $dao->close();
        $cod = ($res) ? 200 : 105;
        return "{\"ints\" : [".$cod."]}";
    }
    
    function remove_friend($idU, $idF){
        $dao = new FriendsDAO();
        $res = $dao->delete($idU,$idF);
        $dao->close();
        return "{\"ints\" : [".((int)$res)."]}";
    }
    
    function find_friends($idU){
        $dao = new UserDAO();
        $users = $dao->findFriends($idU);
        $dao->close();
        $res = "{\"users\":". JsonParser::encondeObjectArray($users). "}";
        return $res;
    }
    
    function find_block_friends($idU, $pos, $len){
        $dao = new UserDAO();
        $users = find_block($dao->findFriends($idU), $pos, $len);
        $dao->close();
        $res = "{\"users\":". JsonParser::encondeObjectArray($users). "}";
        return $res;
    }
    
    function find_all_events_by_user($id){
        $dao = new EventDAO();
        $events = $dao->findByUser($id);
        $dao->close();
        $res = "{\"events\":". JsonParser::encondeObjectArray($events). "}";
        return $res;
    }
    
    function find_block_events_by_user($id, $pos, $len){
        $dao = new EventDAO();
        $events = $dao->findByUser($id);
        $dao->close();
        $res = "{\"events\":". JsonParser::encondeObjectArray(find_block($events,$pos,$len)). "}";
        return $res;
    }
    
    function find_events_confirmed_by_user($id){
        $dao = new EventDAO();
        $events = $dao->findByUserConfirmed($id);
        $dao->close();
        $res = "{\"events\":". JsonParser::encondeObjectArray($events). "}";
        return $res;
    }
    
    function find_block_events_confirmed_by_user($id, $pos, $len){
        $dao = new EventDAO();
        $events = $dao->findByUserConfirmed($id);
        $dao->close();
        $res = "{\"events\":". JsonParser::encondeObjectArray(find_block($events,$pos,$len)). "}";
        return $res;
    }
    
    function find_events_invited_by_user($id){
        $dao = new EventDAO();
        $events = $dao->findByUserInvited($id);
        $dao->close();
        $res = "{\"events\":". JsonParser::encondeObjectArray($events). "}";
        return $res;
    }
    
    function find_block_events_invited_by_user($id, $pos, $len){
        $dao = new EventDAO();
        $events = $dao->findByUserInvited($id);
        $dao->close();
        $res = "{\"events\":". JsonParser::encondeObjectArray(find_block($events,$pos,$len)). "}";
        return $res;
    }
    
    function find_events_historic_by_user($id){
        $dao = new EventDAO();
        $events = $dao->findByUserHistoric($id);
        $dao->close();
        $res = "{\"events\":". JsonParser::encondeObjectArray($events). "}";
        return $res;
    }
    
    function find_block_historic_by_user($id,$pos,$len){
        $dao = new EventDAO();
        $events = $dao->findByUserHistoric($id);
        $dao->close();
        $res = "{\"events\":". JsonParser::encondeObjectArray(find_block($events,$pos,$len)). "}";
        return $res;
    }
    
    function find_by_mail_or_name($str){
        $dao = new UserDAO();
        $users = $dao->findByNameOrMail($str);
        $dao->close();
        $res = "{\"users\":". JsonParser::encondeObjectArray($users). "}";
        return $res;
    }
    
    function find_block_by_mail_or_name($str, $pos, $len){
        $dao = new UserDAO();
        $users = $dao->findByNameOrMail($str);
        $dao->close();
        $res = "{\"users\":". JsonParser::encondeObjectArray(find_block($users,$pos,$len)). "}";
        return $res;
    }
    
    function find_by_login($mail, $password){
        $aux = "";
        $dao = new UserDAO();
        $u = $dao->findByMail($mail);
        $dao->close();
        if($u!=null && $u->getPassword()==$password) $aux = $u->toJson();
        $res = "{\"users\":[" . $aux . "]}";
        return $res;
    }
    
    function retrieve_password($mail){
        $password=generate_string();
        $aux = 0;
        $dao = new UserDAO();
        $u = $dao->findByMail($mail);
        if($u!=null){
            $u->setPassword(md5($password));
            $aux = $dao->update($u);
            send_mail_retrieve_password($mail, $password);
        }
        $dao->close();
        return "{\"ints\" : [".$aux."]}";
    }
    
?>