<?php

include_once "actions/BaseAction.php";

class CreateAction extends BaseAction {
    public function createTrip() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $body = json_decode(file_get_contents("php://input"));

        if (isset($body->available_seats) && isset($body->countries)) {
            include_once "models/Country.php";
            include_once "models/Trip.php";

            $allTrips = $this->database->selectAllFrom("trips");
            $newTripId = end($allTrips)["id"] + 1;
            $available_seats = (int)$body->available_seats;

            $allCountries = $this->database->selectAllFrom("countries");

            $countries = [];
            for ($i = 0; $i < count($body->countries); $i++) {

                $isCountryIdValid = false; 
                $countryId = $body->countries[$i];

                for ($j = 0; $j < count($allCountries); $j++) {
                    if ($allCountries[$j]["id"] === $countryId) {
                        $isCountryIdValid = true;
                        $countryName = $allCountries[$j]["name"];
                        break;
                    }
                }

                if (!$isCountryIdValid) {
                    require_once "errors/400.php";
                    return;
                }

                $country = new Country($countryId, $countryName);
                array_push($countries, $country);
            }

            $trip = new Trip($newTripId, $countries, $available_seats);
            
            // create a new row in "Trips" and "REL_Trips_Countries" tables
            $tripsTableBody = [
                "id" => $trip->id,
                "available_seats" => $trip->available_seats
            ];

            if ($this->database->insertRow("Trips", $tripsTableBody)) {
                $errorHasOccurred = false;
                for ($i = 0; $i < count($trip->countries); $i++) {
                    $relTripsCountriesTableBody = [
                        "trip_id" => $trip->id,
                        "country_id" => $trip->countries[$i]->id
                    ];
                    if (!$this->database->insertRow("REL_Trips_Countries", $relTripsCountriesTableBody)) {
                        $errorHasOccurred = true;
                        break;
                    }
                }

                if (!$errorHasOccurred) {
                    header("HTTP/1.0 201 Created");
                    $response = [
                        "status" => 201,
                        "message" => "Trip created successfully"
                    ];
                    echo json_encode($response);
                } else {
                    require_once "errors/500.php";
                }
                
            } else {
                require_once "errors/500.php";
            }

        } else {
            header("HTTP/1.0 400 Bad Request");
            $response = [
                "status" => 400,
                "message" => "Bad Request. The body you sent does not have 'available_seats' and/or 'countries' keys"
            ];
            echo json_encode($response);
        }
    }

    public function createCountry() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $body = json_decode(file_get_contents("php://input"));

        if (isset($body->name)) {
            include_once "models/Country.php";
            
            $allCountries = $this->database->selectAllFrom("Countries");
            
            if (count($allCountries) < 1) {
                $newCountryId = 1;
            } else {
                $newCountryId = end($allCountries)["id"] + 1;
            }
            
            $country = new Country($newCountryId, $body->name);

            if ($this->database->insertRow("Countries", ["name" => $country->name])) {
                header("HTTP/1.0 201 Created");
                $response = [
                    "status" => 201,
                    "message" => "Country created successfully"
                ];
                echo json_encode($response);
            } else {
                require_once "errors/500.php";
            }

        } else {
            header("HTTP/1.0 400 Bad Request");
            $response = [
                "status" => 400,
                "message" => "Bad Request. The body you sent does not have a 'name' key"
            ];
            echo json_encode($response);
        }
    }
}