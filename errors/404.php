<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("HTTP/1.0 404 Not Found");

$response = [
    "status" => 404,
    "message" => "Path Not Found"
];
echo json_encode($response);