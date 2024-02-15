<?php

class Rel_Trips_Countries {
    public $trip_id;
    public $country_id;

    public function __construct($trip_id, $country_id) {
        $this->trip_id = $trip_id;
        $this->country_id = $country_id;
    }
}