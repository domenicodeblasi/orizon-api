<?php

class Trip {
    public $id;
    
    // countries array -> countries = [ country_id (int), country_name (str) ]
    public $countries;
    public $available_seats;

    public function __construct($id, array $countries, $available_seats) {
        $this->id = $id;
        $this->countries = $countries;
        $this->available_seats = $available_seats;
    }
}