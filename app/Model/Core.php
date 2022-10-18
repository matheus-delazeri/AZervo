<?php

namespace App\Model;

use App\AZervo;
use mysqli;

class Core
{
    protected $_connection;
    protected $CONFIG_PATH;
    protected $_config;
    protected $_dbConfig;

    public function __construct()
    {
        $this->CONFIG_PATH = AZervo::getBaseDir() . "/etc/config.json";
        $this->_config = json_decode(file_get_contents($this->CONFIG_PATH), true);
        $this->_dbConfig = $this->_config['db'];
    }

    public function connect()
    {
        $dbConfig = parse_url(getenv("CLEARDB_DATABASE_URL"));
        $dbName = substr($dbConfig['path'], 1);
        $this->_connection = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbName);
        if ($this->_connection->connect_error) {
            die("-- Connection failed: " . $this->_connection->connect_error . "\n");
        }
    }

    public function createTables()
    {
        $this->connect();
        $sqlDir = AZervo::getBaseDir() . "/sql/";
        print_r("-- Creating tables...\n");
        foreach ($this->_dbConfig['tables'] as $table) {
            $sqlFilePath = $sqlDir . $table . ".sql";
            if (is_file($sqlFilePath)) {
                $sqlFile = file_get_contents($sqlFilePath);
                $this->_connection->multi_query($sqlFile);
                print_r("[$table]: OK\n");
            } else {
                print_r("[$table]: SQL file not found in $sqlFilePath\n");
            }
        }
    }

    public function addNewRegister($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $valuesVar = implode(", ", array_map(function () {
            return "?";
        }, array_keys($data)));
        if($query = $this->_connection->prepare("INSERT INTO {$table}({$columns}) VALUES ({$valuesVar})")) {
            $query->bind_param(str_repeat("s", count(array_keys($data))), ...array_values($data));
            $query->execute();
            return $this->_connection->insert_id;
        }

        return false;
    }

    public function updateRegister($table, $data)
    {
       if(isset($data['id'])) {
           $id = $data['id'];
           unset($data['id']);
           $valuesVar = implode(", ", array_map(function ($key) {
               return "$key = ?";
           }, array_keys($data)));
           if($query = $this->_connection->prepare("UPDATE {$table} SET {$valuesVar} WHERE id = $id")) {
               $query->bind_param(str_repeat("s", count(array_keys($data))), ...array_values($data));
               $query->execute();
               return $data['id'];
           }
       }

       return false;
    }

    public function getRegisterById($table, $id)
    {
        $query = $this->_connection->query("SELECT * FROM {$table} WHERE id = '{$id}'");
        if($results = mysqli_fetch_all($query, MYSQLI_ASSOC)) {
            return $results[0];
        }

        return false;
    }

    public function getRegisterByUniqueField($table, $field, $value)
    {
        $valueEscaped = $this->_connection->real_escape_string($value);
        $query = $this->_connection->query("SELECT * FROM {$table} WHERE {$field} = '{$valueEscaped}'");
        if($results = mysqli_fetch_all($query, MYSQLI_ASSOC)) {
            if (sizeof($results)>1) { # Not unique field
                return false;
            }
            return $results[0];
        }

        return false;
    }

}