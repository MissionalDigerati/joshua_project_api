<?php
/**
 * This requires PDO MySQL Support.
 *
 * @author Johnathan Pulos
 */
$DS = DIRECTORY_SEPARATOR;
/**
 * Get the Slim Framework, and instantiate the class
 *
 * @author Johnathan Pulos
 */
require(__DIR__ . $DS . ".." . $DS . "Slim" . $DS . "Slim.php");
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim(array('templates.path' => "../App/Views/"));
/**
 * Load up the Aura Auto Loader
 *
 * @author Johnathan Pulos
 */
$vendorDirectory = __DIR__ . $DS . ".." . $DS . "Vendor" . $DS;
$loader = require $vendorDirectory . "Aura.Autoload" . $DS . "scripts" . $DS . "instance.php";
$loader->register();
/**
 * Setup the database object
 *
 * @author Johnathan Pulos
 */
$loader->add("JPAPI\DatabaseSettings", __DIR__ . $DS . ".." . $DS . "Config");
/**
 * Autoload the PDO Database Class
 *
 * @author Johnathan Pulos
 */
$loader->add("PHPToolbox\PDODatabase\PDODatabaseConnect", $vendorDirectory . "PHPToolbox" . $DS . "src");

$pdoDb = \PHPToolbox\PDODatabase\PDODatabaseConnect::getInstance();
$pdoDb->setDatabaseSettings(new \JPAPI\DatabaseSettings);
$db = $pdoDb->getDatabaseInstance();
/**
 * Include common functions
 *
 * @author Johnathan Pulos
 */
require(__DIR__."/../App/Includes/CommonFunctions.php");
/**
 * Get the current request to determine which PHP file to load.  Do not load all files, because it can take longer to
 * load.
 *
 * @author Johnathan Pulos
 */
$appRequest = $app->request();
$requestedUrl = $appRequest->getResourceUri();
/**
 * Make sure they only supply supported formats
 *
 * @author Johnathan Pulos
 */
$extArray = explode('.', $requestedUrl);
$ext = end($extArray);
if (!in_array($ext, array('json', 'xml'))) {
    $app->render("/errors/400.xml.php");
    exit;
}
/**
 * Check if the request is for People Groups
 *
 * @author Johnathan Pulos
 */
if (strpos($requestedUrl, 'people_groups/') !== false) {
    require(__DIR__."/../App/PeopleGroups.php");
}
/**
 * Now run the Slim Framework rendering
 *
 * @author Johnathan Pulos
 */
$app->run();
