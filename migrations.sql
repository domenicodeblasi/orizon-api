-- "Countries" table
CREATE TABLE Countries (
    -- unique id for each country
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    -- name of the country, not null
    name VARCHAR(255) NOT NULL
);

-- "Trips" table
CREATE TABLE Trips (
    -- unique id for each trip
    id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    -- number of available seats
    available_seats INT NOT NULL
);

-- "REL_Trips_Countries" table for many-to-many relationship
CREATE TABLE REL_Trips_Countries (
    trip_id INT,
    country_id INT,
    PRIMARY KEY (trip_id, country_id),
    FOREIGN KEY (trip_id) REFERENCES Trips(id) ON DELETE CASCADE,
    FOREIGN KEY (country_id) REFERENCES Countries(id) ON DELETE CASCADE
);