<?php
namespace app\libraries;

abstract class DataSearch extends Database{
    /**
     * @var
     */
    private $_statement;

    /**
     * Create a select statment
     * @param string $entity
     * @param string $columns
     * @return $this
     */
    public function find(string $entity, string $columns = "*") : DataSearch {
        $this->_statement = "SELECT {$columns} FROM {$entity}";
        return $this;
    }

    /**
     * fetch all results
     * @return array|null
     */
    public function search() : ?array {
        try {
            $data = array();
            $statement = parent::getInstance()->getConnection()->prepare($this->_statement);
            $statement->execute();

            $data = $statement->fetchAll();

            return $data;
        } catch (Exception $exception) {
            return null;
        }
    }
}
