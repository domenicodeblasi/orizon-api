<?php

require "core/bootstrap.php";
require "routes.php";

require $router->directTo(
    $_SERVER["REQUEST_METHOD"],
    trim($_SERVER["REQUEST_URI"], "/")
);

// require "controllers/countries.php";