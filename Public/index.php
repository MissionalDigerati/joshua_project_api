<?php
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
require("../App/Includes/mysqli_database.php");
$mysqliDb = MysqliDatabase::getInstance();
$db = $mysqliDb->getDatabaseInstance();
/**
 * Include common functions
 *
 * @author Johnathan Pulos
 */
require("../App/Includes/common_functions.php");
/**
 * Get the current request to determine which PHP file to load.  Do not load all files, because it can take longer to load.
 *
 * @author Johnathan Pulos
 */
$request = $app->request();
$requestedUrl = $request->getResourceUri();
/**
 * Make sure they only supply supported formats
 *
 * @author Johnathan Pulos
 */
$ext = end(explode('.', $requestedUrl));
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
    require("../App/people_groups.php");
}
/**
 * Now run the Slim Framework rendering
 *
 * @author Johnathan Pulos
 */
$app->run();