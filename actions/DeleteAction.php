<?php

include_once "actions/BaseAction.php";

class DeleteAction extends BaseAction {
    public function deleteTrip() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        include_once "models/Trip.php";

        // check if trip exists
        $uriParts = explode("/", $_SERVER["REQUEST_URI"]);
        $tripId = intval(end($uriParts));

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

        $model = [
            "id" => $tripId,
            "available_seats" => $existingTrip["available_seats"]
        ];
        $isTripDeleted = $this->database->deleteRow("Trips", $model);

        if ($isTripDeleted) {
            header("HTTP/1.0 200 OK");
            $response = [
                "status" => 200,
                "message" => "Trip successfully deleted"
            ];
            echo json_encode($response);
        } else {
            require_once "errors/500.php";
        }
    }

    public function deleteCountry() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        include_once "models/Country.php";

        // check if country exists
        $uriParts = explode("/", $_SERVER["REQUEST_URI"]);
        $countryId = intval(end($uriParts));

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

        $country = new Country($countryId, $existingCountry["name"]);
        $isCountryDeleted = $this->database->deleteRow("Countries", $country);

        if ($isCountryDeleted) {
            header("HTTP/1.0 200 OK");
            $response = [
                "status" => 200,
                "message" => "Country successfully deleted"
            ];
            echo json_encode($response);
        } else {
            require_once "errors/500.php";
        }
    }
}