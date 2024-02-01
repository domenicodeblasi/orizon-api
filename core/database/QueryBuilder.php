<?php

class QueryBuilder {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function selectAllFrom($table) {
        $query = "SELECT * FROM $table ORDER BY id ASC";
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_CLASS);
    }
}