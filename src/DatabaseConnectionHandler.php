<?php
namespace MK\DB;

require_once __DIR__.'/../vendor/autoload.php';

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
    public static function getInstance() {
        static $instance = null;
        if($instance === null) {
            try {
                $instance = new DatabaseConnectionHandler();
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
    private function __construct() {
        try {
            $this->pdo = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
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

    /**
     * Undocumented function
     *
     * @deprecated v1.1
     * @param PDOStatement $statement
     * @param int $page
     * @param int $limit
     * @return void
     */
    private function bindLimitParameter(&$statement, $page = null, $limit = null) {
        if(!is_null($page) && !is_null($limit)) {
            $statement->bindValue(':page', $page, PDO::PARAM_INT);
            $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        }
    }

    function __destruct() {
        $this->pdo = null;
    }



}
