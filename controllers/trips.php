<?php

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {
    echo "READ TRIPS";
} elseif ($method === "POST") {
    echo "CREATE TRIP";
} elseif ($method === "PUT") {
    echo "UPDATE TRIP";
} elseif ($method === "DELETE") {
    echo "DELETE TRIP";
}