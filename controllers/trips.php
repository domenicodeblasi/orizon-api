<?php

$method = $_SERVER["REQUEST_METHOD"];
$uri = trim($_SERVER["REQUEST_URI"], "/");
$uriParts = explode("/", $uri);

$allTripsPattern = "/^api\/trips(\/)?$/";
$singleTripPattern = "/^api\/trips\/[0-9]+$/";
$filterTripPattern = "/^api\/trips\?([a-zA-Z_]+)=([a-zA-Z0-9]+)$/";

switch ($method) {
    case "GET":
        include_once "actions/ReadAction.php";
        $action = new ReadAction("trips", $database);

        if (preg_match($allTripsPattern, $uri)) {
            $action->readAllTrips();
        } elseif (preg_match($singleTripPattern, $uri)) {
            $countryId = end($uriParts);
            $action->readSingleTrip($countryId);
        } elseif (preg_match($filterTripPattern, $uri)) {
            $action->filterTrips();
        } else {
            require_once "errors/400.php";
        }
        break;

    case "POST":
        include_once "actions/CreateAction.php";
        $action = new CreateAction("trips", $database);
        
        if (preg_match($allTripsPattern, $uri)) {
            $action->createTrip();
        } else {
            require_once "errors/400.php";
        }
        break;

    case "PUT":
        include_once "actions/UpdateAction.php";
        $action = new UpdateAction("trips", $database);
        
        if (preg_match($singleTripPattern, $uri)) {
            $action->updateTrip();
        } else {
            require_once "errors/400.php";
        }
        break;

    case "DELETE":
        include_once "actions/DeleteAction.php";
        $action = new DeleteAction("trips", $database);
        
        if (preg_match($singleTripPattern, $uri)) {
            $action->deleteTrip();
        } else {
            require_once "errors/400.php";
        }
        break;
}