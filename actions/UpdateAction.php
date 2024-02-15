<?php

include_once "actions/BaseAction.php";

class UpdateAction extends BaseAction {
    public function updateTrip() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $body = json_decode(file_get_contents("php://input"));

        // check if 'available_seats' and 'countries' keys exist
        if (isset($body->available_seats) && isset($body->countries)) {
            include_once "models/Trip.php";
            include_once "models/Rel_Trips_Countries.php";

            // check if trip exists
            $explodedRequestUri = explode("/", $_SERVER["REQUEST_URI"]);
            $tripId = end($explodedRequestUri);
            $existingTrip = $this->database->readSingleTrip($tripId);

            if (!$existingTrip) {
                header("HTTP/1.0 404 Not Found");
                $response = [
                    "status" => 404,
                    "message" => "Trip not found"
                ];
                echo json_encode($response);
                return;
            }

            if ($existingTrip["country_ids"] === NULL) {
                $existingCountriesId = [];
            } else {
                $existingCountriesId = array_map("intval", explode(",", $existingTrip["country_ids"]));
            }

            $availableSeats = (int)$body->available_seats;
            $countries = $body->countries;

            $newTrip = new Trip($tripId, $countries, $availableSeats);

            $areCountriesUpdated = true;
            foreach ($newTrip->countries as $countryId) {

                $singleCountry = $this->database->readSingleCountry($countryId);

                if (!$singleCountry) {
                    header("HTTP/1.0 404 Not Found");
                    $response = [
                        "status" => 404,
                        "message" => "Country not found. One of the country you sent does not exist"
                    ];
                    echo json_encode($response);
                    return;
                }

                $newRelTripsCountries = new Rel_Trips_Countries((int)$tripId, $countryId);

                // check trip_id - country_id correspondance in existing data
                if (!in_array($countryId, $existingCountriesId)) {
                    $body = [
                        "trip_id" => $tripId,
                        "country_id" => $countryId
                    ];
                    $insertCountry = $this->database->insertRow("REL_Trips_Countries", $body);
                    if (!$insertCountry) {
                        $areCountriesUpdated = false;
                        break;
                    }
                } else {
                    continue;
                }   
            }

            $countriesToDelete = array_diff($existingCountriesId, $newTrip->countries);

            $areCountriesDeleted = true;
            foreach ($countriesToDelete as $countryId) {

                $tripToDelete = new Rel_Trips_Countries((int)$tripId, $countryId);

                $deleteCountry = $this->database->deleteRow("REL_Trips_Countries", $tripToDelete);
                if (!$deleteCountry) {
                    $areCountriesDeleted = false;
                    break;
                }
            }

            $areAvailableSeatsUpdated = $this->database->updateRow("Trips", "available_seats", "id", $newTrip);

            if ($areCountriesUpdated && $areAvailableSeatsUpdated && $areCountriesDeleted) {
                header("HTTP/1.0 200 OK");
                $response = [
                    "status" => 200,
                    "message" => "Trip successfully updated"
                ];
                echo json_encode($response);
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

    public function updateCountry() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");

        $body = json_decode(file_get_contents("php://input"));

        // check if 'name' exists
        if (isset($body->name)) {
            include_once "models/Country.php";

            // check if country exists
            $explodedRequestUri = explode("/", $_SERVER["REQUEST_URI"]);
            $countryId = end($explodedRequestUri);
            $existingCountry = $this->database->readSingleCountry($countryId);

            if (!$existingCountry) {
                header("HTTP/1.0 404 Not Found");
                $response = [
                    "status" => 404,
                    "message" => "Country not found"
                ];
                echo json_encode($response);
                return;
            }

            $countryName = $body->name;
            $newCountry = new Country((int)$countryId, $countryName);
            $isCountryUpdated = $this->database->updateRow("Countries", "name", "id", $newCountry);

            if ($isCountryUpdated) {
                header("HTTP/1.0 200 OK");
                $response = [
                    "status" => 200,
                    "message" => "Country successfully updated"
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