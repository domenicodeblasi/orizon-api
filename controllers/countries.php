<?php

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {
    echo "READ COUNTRIES";
} elseif ($method === "POST") {
    echo "CREATE COUNTRY";
} elseif ($method === "PUT") {
    echo "UPDATE COUNTRY";
} elseif ($method === "DELETE") {
    echo "DELETE COUNTRY";
}