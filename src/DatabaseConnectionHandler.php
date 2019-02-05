<?php
namespace MK\DB;

use \PDO;

class DatabaseConnectionHandler {

    /**
     * Undocumented variable
     *
     * @var PDO
     */
    private $pdo;

    /**
     * Undocumented function
     *
     * @return DatabaseConnectionHandler
     * @throws DatabaseConnectionException
     */
    public static function getInstance($db_server = null, $db_name = null, $db_user = null, $db_password = null) {
        static $instance = null;
        if($instance === null) {
            try {
                $instance = new DatabaseConnectionHandler($db_server, $db_name, $db_user, $db_password);
            } catch (DatabaseConnectionException $e) {
                throw $e;
            }
        }
        return $instance;
    }

    /**
     * Undocumented function
     * @throws DatabaseConnectionException
     */
    private function __construct($db_server, $db_name, $db_user, $db_password) {
        try {
            if($db_server === null || $db_name === null || $db_user === null || $db_password === null) {
                throw new DataBaseConnectionException("MySQL login credentials not found");
            }
            $this->pdo = new PDO('mysql:host='.$db_server.';dbname='.$db_name.';charset=utf8', $db_user, $db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new DatabaseConnectionException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Undocumented function
     *
     * @param string $query
     * @param array $arguments
     * @return DatabaseResult
     * @throws Exception
     */
    public function query($query, $arguments = array()) {
        try {
            $statement = $this->pdo->prepare($query);        
            $statement->execute($arguments);
            $last_insert_id = $this->pdo->lastInsertId();
            $db_result = new DatabaseResult($statement, $last_insert_id);
            return $db_result;
        } catch (\PDOException $e) {
            throw new DatabaseQueryException($e->getMessage(), 0 , $e);
        }
    }

    function __destruct() {
        $this->pdo = null;
    }



}
