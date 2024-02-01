<?php

$config = require "core/config.php";
require "core/database/Connection.php";
require "core/database/QueryBuilder.php";

$database = new QueryBuilder(
    Connection::make($config["database"])
);