<h1 align="center">Orizon REST API</h1>


## 📋 Table of Contents

- 👀 Overview
- ⚙️ Installation
- 🤌 Usage
- 🛠️ Built with
- ⚖️ License


## 👀 Overview

*Orizon REST API* is a RESTful API created for Orizon, an imaginary travel agency, in order to manage their travel-related data efficiently.


## ⚙️ Installation

To run *Orizon REST API* locally, follow these steps:

1. Clone the repository:
   ```
   git clone https://github.com/domenicodeblasi/orizon-api.git
   ```
   
2. Navigate to the project directory:
   ```
   cd orizon-api
   ```
   
3. Log into MySQL, create a new database and copy database structure from `migrations.sql`:
   
   - Log into MySQL (use `-p` only if you set a MySQL password):
      ```
      mysql.server start
      mysql -u [YOUR_USERNAME] -p
      ```
      
   - Create a new database:
      ```mysql
      CREATE DATABASE [YOUR_DBNAME];
      ```
      and use it:
      ```mysql
      USE [YOUR_DBNAME];
      ```
      
   - Copy the database structure from the `migrations.sql` file and execute it in your MySQL database.

4. Change the configuration file updating your credentials and the database name. You can go to the path `core/config.php` and modify `username`, `password` and `dbname` values:

   ```php
     "database" => [
       "username" => YOUR_USERNAME,
       "password" => YOUR_PASSWORD,
       ...
       "dbname" => YOUR_DBNAME
     ]
   ```
      
5. Start the server, in the terminal write:
   ```
   php -S localhost:8888
   ```
   
6. Open your web browser and visit: `http://localhost:8888`


## 🤌 Usage

Once the server is up and running, you can interact with the API endpoints using tools like Insomnia, Postman or other API testing softwares. Here are all the available endpoints:

| HTTP Method                                           |  Endpoint                                    | Description                                                     | Body (JSON)                                           |
|-------------------------------------------------------|----------------------------------------------|-----------------------------------------------------------------|-------------------------------------------------------|
| <img src="https://img.shields.io/badge/GET-4895ef">   | `api/trips`                                  | Read all trips                                                  |                                                       |
| <img src="https://img.shields.io/badge/GET-4895ef">   | `api/trips/{ID}`                             | Read a single trip                                              |                                                       |
| <img src="https://img.shields.io/badge/GET-4895ef">   | `api/trips?country_id={COUNTRY_ID}`          | Filter and read all trips for a specific country ID             |                                                       |
| <img src="https://img.shields.io/badge/GET-4895ef">   | `api/trips?available_seats={AVAILABLE_SEATS}`| Filter and read all trips based on the number of available seats|                                                       |
| <img src="https://img.shields.io/badge/POST-08a045">  | `api/countries`                              | Create new country                                              | ```{"name": COUNTRY_NAME}```                          |
| <img src="https://img.shields.io/badge/POST-08a045">  | `api/trips`                                  | Create new trip                                                 | ```{"available_seats": AVAILABLE_SEATS, "countries": [COUNTRY_ID, ...]}```|
| <img src="https://img.shields.io/badge/PUT-e88504">   | `api/countries/{ID}`                         | Update an existing country                                      | ```{"name": COUNTRY_NAME}```                          |
| <img src="https://img.shields.io/badge/PUT-e88504">   | `api/trips/{ID}`                             | Update an existing trip                                         | ```{"available_seats": AVAILABLE_SEATS, "countries": [COUNTRY_ID, ...]}```|
| <img src="https://img.shields.io/badge/DELETE-ed2939">| `api/countries/{ID}`                         | Delete an existing country                                      |                                                       |
| <img src="https://img.shields.io/badge/DELETE-ed2939">| `api/trips/{ID}`                             | Delete an existing trip                                         |                                                       |



## 🛠️ Built with

* <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white"></a>
* <a href="https://www.mysql.com/"><img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white"></a>

## ⚖️ License

*Orizon REST API* is MIT licensed, see `LICENSE.md` for more information.
