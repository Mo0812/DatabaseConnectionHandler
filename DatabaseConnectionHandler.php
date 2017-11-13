<?php

require_once dirname(__FILE__) . '/DataBaseConnection.php';
require_once dirname(__FILE__).'/DatabaseResult.php';

class DatabaseConnectionHandler {

    private $pdo;

    public static function getInstance() {
        static $instance = null;
        if($instance === null) {
            $instance = new DatabaseConnectionHandler();
        }
        return $instance;
    }

    private function __construct() {
        $this->pdo = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    }

    public function query($query, $arguments = array()) {
        $statement = $this->pdo->prepare($query);
        $statement->execute($arguments);
        $last_insert_id = $this->pdo->lastInsertId();
        $db_result = new DatabaseResult($statement, $last_insert_id);
        return $db_result;
    }

    function __destruct() {
        $this->pdo = null;
    }



}
