<?php

$method = $_SERVER["REQUEST_METHOD"];
$uri = trim($_SERVER["REQUEST_URI"], "/");
$uriParts = explode("/", $uri);

$allCountriesPattern = "/^api\/countries(\/)?$/";
$singleCountryPattern = "/^api\/countries\/[0-9]+$/";

switch ($method) {
    case "POST":
        include_once "actions/CreateAction.php";
        $action = new CreateAction("countries", $database);

        if (preg_match($allCountriesPattern, $uri)) {
            $action->createCountry();
        } else {
            require_once "errors/400.php";
        }
        break;

    case "PUT":
        include_once "actions/UpdateAction.php";
        $action = new UpdateAction("countries", $database);
        
        if (preg_match($singleCountryPattern, $uri)) {
            $action->updateCountry();
        } else {
            require_once "errors/400.php";
        }
        break;

    case "DELETE":
        include_once "actions/DeleteAction.php";
        $action = new DeleteAction("countries", $database);
        
        if (preg_match($singleCountryPattern, $uri)) {
            $action->deleteCountry();
        } else {
            require_once "errors/400.php";
        }
        break;
}