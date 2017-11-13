<?php

class DatabaseResult {

    public $statement = null;
    private $last_insert_id;

    function __construct($statement, $last_insert_id = false) {
        $this->statement = $statement;
        $this->last_insert_id = $last_insert_id;
    }

    public function getSelectedRows() {
        if(!is_null($this->statement)) {
            return $this->statement->rowCount();
        } else {
            return 0;
        }
    }

    public function nextRow() {
        if(!is_null($this->statement)) {
            $row = $this->statement->fetch();
            return $row;
        } else {
            return false;
        }
    }

    public function fetchAll() {
        if(!is_null($this->statement) && !$this->fetchTrigger) {
            return $this->statement->fetchAll();
        } else {
            return false;
        }
    }

    public function getLastInsertID() {
        return $this->last_insert_id;
    }

}
