<?php
/**
 * This file is part of Joshua Project API.
 * 
 * Joshua Project API is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Joshua Project API is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see 
 * <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @copyright Copyright 2013 Missional Digerati
 * 
 */
/**
 * This requires PDO MySQL Support.
 *
 * @author Johnathan Pulos
 */
$DS = DIRECTORY_SEPARATOR;
$bypassExtTest = false;
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
if ($requestedUrl == "/") {
    require(__DIR__."/../App/APIKeyRequestPages.php");
    $bypassExtTest = true;
}
/**
 * Make sure they only supply supported formats
 *
 * @author Johnathan Pulos
 */
$extArray = explode('.', $requestedUrl);
$ext = end($extArray);
if (($bypassExtTest === false) && (!in_array($ext, array('json', 'xml')))) {
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
