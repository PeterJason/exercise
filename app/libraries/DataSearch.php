<?php
namespace app\libraries;

class DataSearch extends Database
{
    /**
     * @var
     */
    private $_statement;

    /**
     * Create a select statement
     * @param string $table
     * @param string $columns
     * @return $this
     */
    public function find(string $table, string $columns = "*") : DataSearch
    {
        $this->_statement = "SELECT {$columns} FROM {$table}";
        return $this;
    }

    /**
     * fetch all results
     * @return array|null
     */
    public function search() : ?array
    {
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
