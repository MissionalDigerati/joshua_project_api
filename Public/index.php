<?php
/**
 * This requires PDO MySQL Support.
 *
 * @author Johnathan Pulos
 */
/**
 * Get the Slim Framework, and instantiate the class
 *
 * @author Johnathan Pulos
 */
require('../Slim/Slim.php');
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim(array('templates.path' => "../App/Views/"));
/**
 * Setup the database object
 *
 * @author Johnathan Pulos
 */
require("../App/Includes/PDODatabase.php");
$pdoDb = PDODatabase::getInstance();
$db = $pdoDb->getDatabaseInstance();
/**
 * Include common functions
 *
 * @author Johnathan Pulos
 */
require("../App/Includes/CommonFunctions.php");
/**
 * Get the current request to determine which PHP file to load.  Do not load all files, because it can take longer to load.
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
if(!in_array($ext, array('json', 'xml'))) {
    $app->render("/errors/400.xml.php");
    exit;
}
/**
 * Check if the request is for People Groups
 *
 * @author Johnathan Pulos
 */
if (strpos($requestedUrl,'people_groups/') !== false) {
    require("../App/PeopleGroups.php");
}
/**
 * Now run the Slim Framework rendering
 *
 * @author Johnathan Pulos
 */
$app->run();