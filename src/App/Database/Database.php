<?php
namespace ASPTest\App\Database;

use \PDO;
use \PDOException;

class Database
{
    private $connection;

    public function __construct() {
        try {
            $this->connection = new PDO("mysql:host=localhost;dbname=asptest", 'root', '');
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getConnection() {
        if ($this->connection instanceof PDO) {
             return $this->connection;
        }
    }

}
