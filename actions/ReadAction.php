<?php

include_once "actions/BaseAction.php";

class ReadAction extends BaseAction {
    public function readAllTrips() {
        header("Access-Controll-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $allTrips = $this->database->readTrips();
        if (is_array($allTrips) && count($allTrips) > 0) {
            header("HTTP/1.0 200 OK");
            include_once "models/Trip.php";

            $response = [
                "status" => 200,
                "data" => []
            ];

            for ($i = 0; $i < count($allTrips); $i++) {
                $currentTrip = $allTrips[$i];

                if ($currentTrip["country_ids"] === NULL || $currentTrip["country_names"] === NULL) {
                    $countryIdsArray = [];
                    $countryNamesArray = [];
                } else {
                    $countryIdsArray = explode(",", $currentTrip["country_ids"]);
                    $countryNamesArray = explode(",", $currentTrip["country_names"]);
                }
                $countryIdsIntArray = array_map("intval", $countryIdsArray);

                $countries = [];

                for ($j = 0; $j < count($countryIdsIntArray); $j++) {
                    $country = [
                        "id" => $countryIdsIntArray[$j],
                        "name" => $countryNamesArray[$j]
                    ];
                    array_push($countries, $country);
                }

                $trip = new Trip(
                    $currentTrip["id"],
                    $countries,
                    $currentTrip["available_seats"]
                );
                array_push($response["data"], $trip);
            }
            echo json_encode($response);
        } else {
            require_once "errors/404.php";
        }
    }

    public function readSingleTrip($countryId) {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $singleTrip = $this->database->readSingleTrip($countryId);

        if (is_array($singleTrip) && count($singleTrip) > 0) {
            header("HTTP/1.0 200 OK");
            include_once "models/Trip.php";

            $response = [
                "status" => 200,
                "data" => []
            ];

            $countries = [];
            if ($singleTrip["country_ids"] === NULL || $singleTrip["country_names"] === NULL) {
                $countryIdsArray = [];
                $countryNamesArray = [];
            } else {
                $countryIdsArray = explode(",", $singleTrip["country_ids"]);
                $countryNamesArray = explode(",", $singleTrip["country_names"]);
            }
            $countryIdsIntArray = array_map("intval", $countryIdsArray);

            for ($i = 0; $i < count($countryIdsIntArray); $i++) {
                $country = [
                    "id" => $countryIdsIntArray[$i],
                    "name" => $countryNamesArray[$i]
                ];
                array_push($countries, $country);
            }

            $trip = new Trip(
                $singleTrip["id"],
                $countries,
                $singleTrip["available_seats"]
            );
            array_push($response["data"], $trip);

            echo json_encode($response);

        } else {
            require_once "errors/404.php";
        }
    }

    public function filterTrips() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $error400 = false;

        if (isset($_GET["available_seats"])) {
            $filteredTrips = $this->database->filterTripsBy("available_seats", $_GET["available_seats"]);
        } elseif (isset($_GET["country_id"])) {
            $filteredTrips = $this->database->filterTripsBy("country_id", $_GET["country_id"]);
        } else {
            $error400 = true;
            require_once "errors/400.php";
        }

        if (!$error400 && empty($filteredTrips)) {
            require_once "errors/404.php";
        } elseif (!$error400 && is_array($filteredTrips) && count($filteredTrips) > 0) {
            header("HTTP/1.0 200 OK");
            include_once "models/Trip.php";

            $response = [
                "status" => 200,
                "data" => []
            ];

            for ($i = 0; $i < count($filteredTrips); $i++) {
                $currentTrip = $filteredTrips[$i];
                $countryIdsArray = explode(",", $currentTrip["country_ids"]);
                $countryIdsIntArray = array_map("intval", $countryIdsArray);
                $countryNamesArray = explode(",", $currentTrip["country_names"]);

                $countries = [];

                for ($j = 0; $j < count($countryIdsIntArray); $j++) {
                    $country = [
                        "id" => $countryIdsIntArray[$j],
                        "name" => $countryNamesArray[$j]
                    ];
                    array_push($countries, $country);
                }
    
                $trip = new Trip(
                    $currentTrip["id"],
                    $countries,
                    $currentTrip["available_seats"]
                );
                array_push($response["data"], $trip);
            }

            echo json_encode($response);
        }
    }
}