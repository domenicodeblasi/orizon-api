<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("HTTP/1.0 500 Internal Server Error");

$response = [
    "status" => 500,
    "message" => "Internal Server Error"
];
echo json_encode($response);