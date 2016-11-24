<?php

include_once 'Event.class.php';

class JsonParser {
    
    public static function toLocation($json){
        return Location::fromArray(json_decode($json, true));
    }
   
    public static function toLocationArray($json){
        $res = [];
        $l = json_decode($json, true);
        foreach($l as $m){
            $res[] = Location::fromArray($m);
        }
        return $res;
    }
    
    public static function toEventType($json){
        return EventType::fromArray(json_decode($json, true));
    }
    
    public static function toEventTypeArray($json){
        $res = [];
        $l = json_decode($json, true);
        foreach($l as $m){
            $res[] = EventType::fromArray($m);
        }
        return $res;
    }
    
    public static function toUser($json){
        return User::fromArray(json_decode($json, true));
    }
    
    public static function touserArray($json){
        $res = [];
        $l = json_decode($json, true);
        foreach($l as $m){
            $res[] = User::fromArray($m);
        }
        return $res;
    }
    
    public static function toEvent($json){
        return Event::fromArray(json_decode($json, true));
    }
    
    public static function toEventArray($json){
        $res = [];
        $l = json_decode($json, true);
        foreach($l as $m){
            $res[] = Event::fromArray($m);
        }
        return $res;
    }
    
    public static function encondeObjectArray($array){
        $res = "[";
        if($array!=null){
            $tam = count($array);
            if($tam>0) $res .= $array[0]->toJson();
            for($k=1;$k<$tam;$k++) $res .= ", ".$array[$k]->toJson();
        }
        $res .= "]";
        return $res;
    }
    
    public static function encodeSimpleArray($array){
        return json_encode($array);
    }
    
    public static function decodeSimpleArray($json){
        return json_decode($json, true);
    }
    
}
