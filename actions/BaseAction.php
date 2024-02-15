<?php

class BaseAction {
    public $table;
    public $database;

    public function __construct($table, $database) {
        $this->table = $table;
        $this->database = $database;
    }
}