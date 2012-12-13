<?php
/**
 * Require the Database settings
 *
 * @author Johnathan Pulos
 */
require(dirname(dirname(dirname(__FILE__))) . '/Config/database_settings.php');
/**
 * A Singleton class for handling the database connections
 *
 * @package default
 * @author Johnathan Pulos
 */
class MysqliDatabase {
    /**
     * The mysqli database instance of the class
     *
     * @access private
     * @var object
     */
    private static $mysqliDatabaseInstance;
    /**
     * The database settings
     *
     * @access private
     * @var array
     */
    private $databaseSettings = array();
    /**
     * The instance of the MySQLi database object
     *
     * @access private
     * @var object
     */
    private $mysqli;
    /**
     * Construct the class,  this is set private to create a Singleton Class.  Please use getInstance to create the class
     *
     * @return void
     * @access private
     * @author Johnathan Pulos
     */
    private function __construct() {
        $databaseSettings = new DatabaseSettings;
        $this->databaseSettings = $databaseSettings->default;
    }
    /**
     * Get the instance of this class.
     *
     * @return object
     * @access public
     * @author Johnathan Pulos
     */
    public function getInstance() {
        if(!self::$mysqliDatabaseInstance) { 
            self::$mysqliDatabaseInstance = new MysqliDatabase(); 
        } 
        return self::$mysqliDatabaseInstance;
    }
    /**
     * Get the database instance
     *
     * @return object
     * @access public
     * @author Johnathan Pulos
     */
    public function getDatabaseInstance() {
        if(!$this->mysqli) { 
            $this->mysqli = $this->getConnection();
        } 
        return $this->mysqli;
    }
    /**
     * Make a connection to the database
     *
     * @return database object
     * @access private
     * @author Johnathan Pulos
     */
    private function getConnection() {
        $dbhost = $this->databaseSettings['host'];
        $dbuser = $this->databaseSettings['username'];
        $dbpass = $this->databaseSettings['password'];
        $dbname = $this->databaseSettings['name'];
        $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        return $mysqli;
    }
}
