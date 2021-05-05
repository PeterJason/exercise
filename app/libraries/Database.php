<?php
namespace app\libraries;
use PDO;

class Database {
    /**
     * @var PDO
     */
    private $_connection;
    /**
     * @var
     */
    protected static $_instance;
    /**
     * @var string
     */
    private $_database = DB_NAME;

    /**
     * Connection to database using PDO
     * Database constructor.
     */
    protected function __construct() {
        try {
            // connect to database
            $this->_connection = new PDO( "sqlite:" . $this->_database, null, null, array(PDO::ATTR_PERSISTENT => true) );
        } catch (Exception $exception) {
            // sqlite3 throws an exception when it is unable to connect
            echo '<p>There was an error connecting to the database!</p>';
        }
    }

    /**
     * Get an instance of the Database
     * @return Database
     */
    public static function getInstance() : Database {
        //if no instance then make one
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     *Magic method clone is empty to prevent duplication of connection
     */
    private function __clone() {}

    /**
     *Magic method wakeup is empty to prevent reestablish any database connections
     */
    private function __wakeup() {}

    /**
     *  Get sqlite connection
     * @return PDO
     */
    public function getConnection() : PDO {
        return $this->_connection;
    }
}
