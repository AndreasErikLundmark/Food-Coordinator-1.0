<?php

class sqlTableCreator
{
//Setting up a Mysql database with gathered examples. XAMPP configured
//This class file is only ment to run once to create the database and tables
//Excecute by running start.php.

//https://www.wikihow.tech/Prevent-SQL-Injection-in-PHP

private $servername = "localhost";
private $username = "root";
private $password = "";
private $dbname = ""; // Database
private $conn;

  public function __construct($dbname)
  {
    $this->dbname = $dbname;
    $this->connect();
    $this->generateDatabase();
    $this->chooseDB();
    $this->createUsersTable();
    $this->createFoodCardsTable();
    $this->createIngredientsTable();
    $this->createJunctionTable();
    }
// Creating a connection with the Xampp Mysql Server.
    private function connect()
    {
      $this->conn = new mysqli($this->servername, $this->username, $this->password);
      if ($this->conn->connect_error) {
        die("Connection failed: " . $this->conn->connect_error);
      }
    }


//echo "Connected successfully";

// CREATE DATABASE, USE $dbname
private function generateDatabase()
{
  $sql = "CREATE DATABASE IF NOT EXISTS " . $this->dbname;
  if ($this->conn->query($sql) === FALSE) {
    echo "Error creating database: " . $this->conn->error;
  }
}
//UPDATE THE CONNECTION TO POINT TO YOUR DB
private function chooseDB()
{
  $this->conn->select_db($this->dbname);
}

  public function getConnection() {
    return $this->conn;
  }
  public function getDbName() {
    return $this->dbname;
  }

// CREATE A TABLE
private function createUsersTable()
{
  $sql = "CREATE TABLE IF NOT EXISTS users (
user_id INT(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_name VARCHAR(100) UNIQUE NOT NULL,
user_password VARCHAR(100) NOT NULL,
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

)";
//Check Table creation.
  if ($this->conn->query($sql) === TRUE) {
//    echo "Table users created successfully" . "<br>";
  } else {
//    echo "Error creating users table: " . $this->conn->error;
  }
}

  private function createFoodCardsTable()
  {
    $sql2 = "CREATE TABLE IF NOT EXISTS foodCards (
  `foodcard_id` int(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` int(10) UNSIGNED NOT NULL,
  `foodcard_name` VARCHAR(255),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

//Check Table creation.
    if ($this->conn->query($sql2) === TRUE) {
//      echo "Table foodCards created successfully" . "<br>";
    } else {
//      echo "Error creating foodCards table: " . $this->conn->error;
    }
  }

  private function createIngredientsTable()
  {
    $sql3 = "CREATE TABLE IF NOT EXISTS ingredients (
  `ingredient_id` int(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ingredient_metric` VARCHAR(10),
  `ingredient_name` VARCHAR(50)
)";

//Check Table creation.
    if ($this->conn->query($sql3) === TRUE) {
//      echo "Table ingredients created successfully" . "<br>";
    } else {
//      echo "Error creating ingredients table: " . $this->conn->error;
    }
  }

  private function createJunctionTable()
  { $sql4 = "CREATE TABLE IF NOT EXISTS junctionTable (
    `foodcard_id` int(10) UNSIGNED,
  `ingredient_id` int(10) UNSIGNED,
  `quantity` VARCHAR(255),
  PRIMARY KEY (foodcard_id, ingredient_id),
  FOREIGN KEY (foodcard_id) REFERENCES foodCards(`foodcard_id`),
  FOREIGN KEY (ingredient_id) REFERENCES ingredients(ingredient_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

//Check Table creation.
    if ($this->conn->query($sql4) === TRUE) {
//      echo "Table ingredients created successfully" . "<br>";
    } else {
//      echo "Error creating ingredients table: " . $this->conn->error;
    }
  }

public function close() {
  $this->conn->close();
}

}
//$sqlTableCreator = new sqlTableCreator('FoodCoordinator');
?>
