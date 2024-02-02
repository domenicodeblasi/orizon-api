<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("HTTP/1.0 405 Method Not Allowed");

$response = [
    "status" => 405,
    "message" => "Method Not Allowed"
];
echo json_encode($response);