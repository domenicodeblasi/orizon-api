<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("HTTP/1.0 400 Bad Request");

$response = [
    "status" => 400,
    "message" => "Bad Request"
];
echo json_encode($response);