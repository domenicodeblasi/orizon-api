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
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readTrips() {
        $query = "SELECT 
        Trips.id,
        GROUP_CONCAT(Countries.id) AS country_ids, 
        GROUP_CONCAT(Countries.name) AS country_names,
        Trips.available_seats
        FROM Trips
        LEFT JOIN Rel_Trips_Countries ON Rel_Trips_Countries.trip_id = Trips.id
        LEFT JOIN Countries ON Rel_Trips_Countries.country_id = Countries.id
        GROUP BY Trips.id, Trips.available_seats";
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);

    }

    public function readSingleTrip($tripId) {
        $query = "SELECT
        Trips.id,
        GROUP_CONCAT(Countries.id) AS country_ids,
        GROUP_CONCAT(Countries.name) AS country_names,
        Trips.available_seats
        FROM Trips
        LEFT JOIN Rel_Trips_Countries ON Rel_Trips_Countries.trip_id = Trips.id
        LEFT JOIN Countries ON Rel_Trips_Countries.country_id = Countries.id
        WHERE $tripId = Trips.id
        GROUP BY Trips.id, Trips.available_seats";
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function readSingleCountry($id) {
        $query = "SELECT * FROM Countries WHERE id = :id";
        $statement = $this->pdo->prepare($query);
        $statement->execute(["id" => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function filterTripsBy($column, $parameter) {
        $query = "SELECT
        Trips.id,
        GROUP_CONCAT(Countries.id) AS country_ids,
        GROUP_CONCAT(Countries.name) AS country_names,
        Trips.available_seats
        FROM Trips
        LEFT JOIN REL_Trips_Countries ON REL_Trips_Countries.trip_id = Trips.id
        LEFT JOIN Countries ON REL_Trips_Countries.country_id = Countries.id
        WHERE Trips.id IN (
            SELECT trip_id
            FROM REL_Trips_Countries
            WHERE $column = :parameter
        )
        GROUP BY Trips.id;";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(":parameter", $parameter, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    //  .../api/countries - body structure
    //  $body = [
    //      "name" => "Canada"
    //  ]

    //  .../api/trips - body structure
    //  $body = [
    //      "available_seats" => 50
    //      "countries" => [1,2,3]
    //  ]

    public function insertRow($table, $body) {
        $keys = implode(",", array_keys($body));
        $values = implode(",", array_fill(0, count($body), "?"));
        $query = "INSERT INTO $table ($keys) VALUES ($values);";
        $statement = $this->pdo->prepare($query);
        return $statement->execute(array_values($body));   
    }

    public function updateRow($table, $column, $constraint, $model) {
        $query = "UPDATE $table SET $column = :$column WHERE $constraint = :$constraint";
        $statement = $this->pdo->prepare($query);
        $params = [
            $column => $model->$column,
            $constraint => $model->$constraint
        ];
        return $statement->execute($params);
    }

    public function deleteRow($table, $model) {
        $clauses = [];
        $params = [];
        foreach ((array)$model as $key => $value) {
            $clause = "$key = :$key";
            $clauses[] = $clause;
            $params[":$key"] = $value;
        }
        $completeClause = implode(" AND ", $clauses);
        $query = "DELETE FROM $table WHERE $completeClause";
        $statement = $this->pdo->prepare($query);
        return $statement->execute($params);
    }
}