<?php

/*
  {"users:[{description:black,gender:MALE,id:1,image:,mail:anderson@email.com,name:Anderson,password:123}]}";
  {"{events:[{arrival:{address:calle,latitude:14.0,longitude:12.0},date:Apr 12, 2016 9:38:21 PM,departure:{address:rua,latitude:12.0,longitude:12.0},description:descricao,id:1,idOrganizer:1,secret:true,type:BIKE}]}";
 */

include_once 'Gender.class.php';

class User {

    private $id;
    private $name;
    private $mail;
    private $password;
    private $description;
    private $gender;
    private $image;
    private $friends;
    private $events;
    private $invited;
    private $historic;

    public function __construct($id, $name, $mail, $password, $description, $gender, $image, $friends=[], $events=[], $invited=[], $historic=[]) {
        $this->id = $id;
        $this->name = $name;
        $this->mail = $mail;
        $this->password = $password;
        $this->description = $description;
        $this->gender = $gender;
        $this->image = $image;
        $this->friends = $friends;
        $this->events = $events;
        $this->invited = $invited;
        $this->historic = $historic;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getMail() {
        return $this->mail;
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getGender() {
        return $this->gender;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getFriends() {
        return $this->friends;
    }

    public function setFriends($friends) {
        $this->friends = $friends;
    }

    public function getEvents() {
        return $this->events;
    }

    public function setEvents($events) {
        $this->events = $events;
    }

    public function getInvited() {
        return $this->invited;
    }

    public function setInvited($invited) {
        $this->invited = $invited;
    }
    
    public function getHistoric(){
        return $this->historic;
    }
    
    public function setHistoric($historic){
        $this->historic = $historic;
    }
    
    public function addFriend($f){
        $this->friends[] = $f;
    }
    
    public function addInvited($i){
        $this->invited[] = $i;
    }
    
    public function addEvent($e){
        $this->events[] = $e;
    }

    public function __toString() {
        return "User{" .
                "id=" . $this->id .
                ", name='" . $this->name . '\'' .
                ", mail='" . $this->mail . '\'' .
                ", password='" . $this->password . '\'' .
                ", description='" . $this->description . '\'' .
                ", gender=" . Gender::toString($this->gender) .
                ", image=" . $this->image .
                '}';
    }
    
    public static function fromArray($m){
        return new User($m['id'], $m['name'], $m['mail'], $m['password'], $m['description'], Gender::getGender($m['gender']), $m['image']);
    }
    
    public function toArray(){
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'mail' => $this->mail,
            'password' => $this->password,
            'description' => $this->description,
            'gender' => Gender::toString($this->gender)
        );
    }
    
    public function toJson(){
        $s = json_encode($this->toArray());
        return (substr($s, 0, -1).", \"image\":\"".$this->image."\"}");
    }

}

?>