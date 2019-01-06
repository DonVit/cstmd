<?php
class Enum {
	public static function getMonths () {
        $months = array(
            1 => "Ianuarie",
            2 => "Februarue",
            3 => "Martie",
            4 => "Aprilie",
            5 => "Mai",
            6 => "Iunie",
            7 => "Iulie",
            8 => "August",
            9 => "Septembrie",
            10 => "Octombrie",
            11 => "Noiembrie",
            12 => "Decembrie"
            );
	    return $months;
	}
	public static function getPopMonths () {
        $months = array(
            1 => "Gerar",
            2 => "Făurar",
            3 => "Mărţişor",
            4 => "Prier",
            5 => "Florar",
            6 => "Cireşar",
            7 => "Cuptor",
            8 => "Gustar",
            9 => "Răpciune",
            10 => "Brumărel",
            11 => "Brumar",
            12 => "Undrea"
            );
	    return $months;
	}
	public static function getDays () {
        $days = array(
            0 => "Duminică",
            1 => "Luni",
            2 => "Marți",
            3 => "Miercuri",
            4 => "Joi",
            5 => "Vineri",
            6 => "Sîmbăta"
            );
	    return $days;
	}
	public static function getSeasons () {
        $seasons = array(
            1 => "Iarna",
            2 => "Primavara",
            3 => "Vara",
            4 => "Toamna"
        );
        return $seasons;
    }
}

?>