<?php
namespace MK\DB;

class DatabaseResult {

    /**
     * Undocumented variable
     *
     * @var \PDOStatement
     */
    public $statement = null;
    private $last_insert_id;

    function __construct($statement, $last_insert_id = false) {
        $this->statement = $statement;
        $this->last_insert_id = $last_insert_id;
    }

    /**
     * Undocumented function
     *
     * @return int
     */
    public function getSelectedRows() {
        if(!is_null($this->statement)) {
            return $this->statement->rowCount();
        } else {
            return 0;
        }
    }

    /**
     * Undocumented function
     *
     * @return mixed
     */
    public function nextRow() {
        if(!is_null($this->statement)) {
            $row = $this->statement->fetch();
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function fetchAll() {
        if(!is_null($this->statement)) {
            return $this->statement->fetchAll();
        } else {
            return false;
        }
    }

    /**
     * Undocumented function
     *
     * @return int
     */
    public function getLastInsertID() {
        return $this->last_insert_id;
    }

}
