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
        echo "CREATE COUNTRY";
        break;

    case "PUT":
        include_once "actions/UpdateAction.php";
        $action = new UpdateAction("countries", $database);
        echo "UPDATE COUNTRY";
        break;

    case "DELETE":
        include_once "actions/DeleteAction.php";
        $action = new DeleteAction("countries", $database);
        echo "DELETE COUNTRY";
        break;
}