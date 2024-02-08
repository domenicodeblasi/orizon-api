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

    public function readSingleTrip($countryId) {
        $query = "SELECT
        Trips.id,
        GROUP_CONCAT(Countries.id) AS country_ids,
        GROUP_CONCAT(Countries.name) AS country_names,
        Trips.available_seats
        FROM Trips
        LEFT JOIN Rel_Trips_Countries ON Rel_Trips_Countries.trip_id = Trips.id
        LEFT JOIN Countries ON Rel_Trips_Countries.country_id = Countries.id
        WHERE $countryId = Trips.id
        GROUP BY Trips.id, Trips.available_seats";
        $statement = $this->pdo->prepare($query);
        $statement->execute();
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
}