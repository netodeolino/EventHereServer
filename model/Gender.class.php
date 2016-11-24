<?php

class Gender {

    const MALE = 0;
    const FEMALE = 1;
    const UNINFORMED = 2;

    public static function getGender($gender) {
        if ($gender == "MALE")
            return Gender::MALE;
        if ($gender == "FEMALE")
            return Gender::FEMALE;
        return Gender::UNINFORMED;
    }

    public static function toString($gender) {
        if ($gender == Gender::MALE)
            return "MALE";
        if ($gender == Gender::FEMALE)
            return "FEMALE";
        return "UNINFORMED";
    }

}

?>