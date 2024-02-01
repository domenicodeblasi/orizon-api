<?php

class Connection {
    public static function make($config)
    {
        try {
            return new PDO(
                $config["connectionString"] . $config["dbname"],
                $config["username"],
                $config["password"]
            );
        } catch (PDOException $e) {
            die("Could not connect database: " . $e->getMessage());
        }
    }
}