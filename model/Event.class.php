<?php

include_once 'User.class.php';
include_once 'Location.class.php';
include_once 'EventType.class.php';

class Event {

    private $id;
    private $type;
    private $date;
    private $departure;
    private $arrival;
    private $description;
    private $secret;
    private $organizer;
    private $over;
    private $guests;
    private $users;

    public function Event($id, $type, $date, $departure, $arrival, $description, $secret, $organizer, $over, $guests = [], $users = []) {
        $this->id = $id;
        $this->type = $type;
        $this->date = $date;
        $this->departure = $departure;
        $this->arrival = $arrival;
        $this->description = $description;
        $this->secret = $secret;
        $this->over = $over;
        $this->guests = $guests;
        $this->users = $users;
        $this->organizer = $organizer;
    }
    
    

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getDeparture() {
        return $this->departure;
    }

    public function setDeparture($departure) {
        $this->departure = $departure;
    }

    public function getArrival() {
        return $this->arrival;
    }

    public function setArrival($arrival) {
        $this->arrival = $arrival;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function isSecret() {
        return $this->secret;
    }

    public function setSecret($secret) {
        $this->secret = $secret;
    }

    public function isOver() {
        return $this->over;
    }

    public function setOver($over) {
        $this->over = $over;
    }

    public function getOrganizer() {
        return $this->organizer;
    }

    public function setOrganizer($organizer) {
        $this->organizer = $organizer;
    }

    public function getGuests() {
        return $this->guests;
    }

    public function setGuests($guests) {
        $this->guests = $guests;
    }

    public function getUsers() {
        return $this->users;
    }

    public function setUsers($users) {
        $this->users = $users;
    }

    public function __toString() {
        return "Event{" .
                "id=" . $this->id .
                ", type=" . $this->type->__toString() .
                ", date=" . $this->date .
                ", departure=" . $this->departure->__toString() .
                ", arrival=" . $this->arrival->__toString() .
                ", description='" . $this->description . '\'' .
                ", secret=" . ($this->secret ? "TRUE" : "FALSE") .
                ", organizer=" . $this->organizer->__toString() .
                ", over=" . ($this->over ? "TRUE" : "FALSE") .
                '}';
    }
    
    public static function toMySqlDate($date){
        return $date;
    }
    
    public static function fromMySqlDate($date){
        return $date;
    }
    
    public static function fromArray($m){
        return new Event($m['id'], EventType::fromArray($m['type']), Event::toMySqlDate($m['date']), 
                Location::fromArray($m['departure']), Location::fromArray($m['arrival']), 
                $m['description'], $m['secret'], User::fromArray($m['organizer']), $m['over']);
    }

    public function toArray(){
        return array(
            'id' => $this->id,
            'type' => $this->type->toArray(),
            'date' => Event::fromMySqlDate($this->date),
            'departure' => $this->departure->toArray(),
            'arrival' => $this->arrival->toArray(),
            'description' => $this->description,
            'secret' => ((boolean)$this->secret),
            'organizer' => $this->organizer->toArray(),
            'over' => ((boolean) $this->over)
        );
    }
    
    public function toJson(){
        return json_encode($this->toArray());
    }
}

?>