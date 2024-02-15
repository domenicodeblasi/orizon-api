<?php

require "core/Router.php";

$routes = [
    "GET" => [
        "api/trips" => "controllers/trips.php",
    ],
    "POST" => [
        "api/countries" => "controllers/countries.php",
        "api/trips" => "controllers/trips.php",
    ],
    "PUT" => [
        "api/countries" => "controllers/countries.php",
        "api/trips" => "controllers/trips.php",
    ],
    "DELETE" => [
        "api/countries" => "controllers/countries.php",
        "api/trips" => "controllers/trips.php",
    ],
];

$router = new Router($routes);