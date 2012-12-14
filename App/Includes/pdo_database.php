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
class PDODatabase {
    /**
     * The PDO database instance of the class
     *
     * @access private
     * @var object
     */
    private static $PDODatabaseInstance;
    /**
     * The database settings
     *
     * @access private
     * @var array
     */
    private $databaseSettings = array();
    /**
     * The instance of the PDO database object
     *
     * @access private
     * @var object
     */
    private $PDO;
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
        if(!self::$PDODatabaseInstance) { 
            self::$PDODatabaseInstance = new PDODatabase(); 
        } 
        return self::$PDODatabaseInstance;
    }
    /**
     * Get the database instance
     *
     * @return object
     * @access public
     * @author Johnathan Pulos
     */
    public function getDatabaseInstance() {
        if(!$this->PDO) { 
            $this->PDO = $this->getConnection();
        } 
        return $this->PDO;
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
        try {
            $pdo = new PDO("mysql:host=" . $dbhost . ";dbname=" . $dbname . ";charset=utf8", $dbuser, $dbpass);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }catch(PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $pdo;
    }
}
