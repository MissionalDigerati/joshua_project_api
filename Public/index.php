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
/**
 * Set whether to use Memcached for caching the queries.  Most queries are cached for 1 day.
 *
 * @var boolean
 * @author Johnathan Pulos
 */
$useCaching = false;
$DS = DIRECTORY_SEPARATOR;
$DOMAIN_ADDRESS = $_SERVER['SERVER_NAME'];
if ((substr_compare($DOMAIN_ADDRESS, "http://", 0, 7)) !== 0) {
    $DOMAIN_ADDRESS = "http://" . $DOMAIN_ADDRESS;
}
/**
 * Lets get the version of the API based on the URL (http://joshua.api.local/v12/people_groups/daily_unreached.json?api_key=37e24112caae)
 * It will default to the latest API.  Youu must provide an API Version if you are accessing the data.  The default is only for
 * static pages
 *
 * @author Johnathan Pulos
 */
$pattern = '/([v][1-9]*)/';
preg_match($pattern, $_SERVER['REQUEST_URI'], $matches);
if (empty($matches)) {
    $API_VERSION = "v1";
} else {
    $API_VERSION = $matches[0];
}
$bypassExtTest = false;
if ($useCaching === true) {
    $cache = new Memcached();
    $cache->addServer('localhost', 11211) or die('Memcached not found');
} else {
    $cache = '';
}
/**
 * Get the Slim Framework, and instantiate the class
 *
 * @author Johnathan Pulos
 */
require(__DIR__ . $DS . ".." . $DS . "Slim" . $DS . "Slim.php");
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim(array('templates.path' => "../App/" . $API_VERSION . "/Views/"));
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
 * Autoload the HTTP basic authentication
 *
 * @author Johnathan Pulos
 **/
$loader->add("Slim\Extras\Middleware\HttpBasicAuth", $vendorDirectory . "SlimExtras");
/**
 * Get the current request to determine which PHP file to load.  Do not load all files, because it can take longer to
 * load.
 *
 * @author Johnathan Pulos
 */
$appRequest = $app->request();
$requestedUrl = $appRequest->getResourceUri();
/**
 * Include common functions
 *
 * @author Johnathan Pulos
 */
require(__DIR__."/../App/" . $API_VERSION . "/Includes/CommonFunctions.php");
require(__DIR__."/../App/" . $API_VERSION . "/Includes/EmailFunctions.php");
/**
 * Are we on a static page?
 *
 * @author Johnathan Pulos
 */
$staticPages = array("/", "/get_my_api_key");
if (in_array($requestedUrl, $staticPages)) {
    require(__DIR__."/../App/" . $API_VERSION . "/Resources/StaticPages.php");
    $bypassExtTest = true;
}
/**
 * Are we on a API Key page?
 *
 * @author Johnathan Pulos
 */
if (strpos($requestedUrl, '/api_keys') !== false) {
    /**
     * We need to lock out all PUT and GET requests for api_keys.  These are the admin users.
     *
     * @author Johnathan Pulos
     **/
    if (($appRequest->isGet()) || ($appRequest->isPut())) {
        /**
         * Autoload the Admin settings
         *
         * @author Johnathan Pulos
         */
        $loader->add("JPAPI\AdminSettings", __DIR__ . $DS . ".." . $DS . "Config");
        $adminSettings = new \JPAPI\AdminSettings;
        $app->add(new Slim\Extras\Middleware\HttpBasicAuth($adminSettings->default['username'], $adminSettings->default['password']));
    }
    /**
     * Autoload the PHPMailer
     *
     * @author Johnathan Pulos
     **/
    $loader->add("PHPMailer", $vendorDirectory . "phpmailer");
    require(__DIR__."/../App/" . $API_VERSION . "/Resources/APIKeys.php");
    $bypassExtTest = true;
}
/**
 * We must be on an API Request.  Make sure they only supply supported formats.
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
 * Check if they have a valid API key, else send a 401 error
 *
 * @author Johnathan Pulos
 **/
if ($bypassExtTest === false) {
    $APIKey = strip_tags($appRequest->get('api_key'));
    if ((!isset($APIKey)) || ($APIKey == "")) {
        $app->render("/errors/401." . $ext . ".php");
        exit;
    }
    /**
     * Find the API Key in the database, and validate it
     *
     * @author Johnathan Pulos
     * @todo put a try block here
     **/
    $query = "SELECT * FROM md_api_keys where api_key = :api_key LIMIT 1";
    $statement = $db->prepare($query);
    $statement->execute(array('api_key' => $APIKey));
    $apiKeyData = $statement->fetchAll(PDO::FETCH_ASSOC);
    if (empty($apiKeyData)) {
        $app->render("/errors/401." . $ext . ".php");
        exit;
    }
    if ($apiKeyData[0]['status'] == 0 || $apiKeyData[0]['status'] == 2) {
        /**
         * Pending (0) or Suspended (2)
         *
         * @author Johnathan Pulos
         */
        $app->render("/errors/401." . $ext . ".php");
        exit;
    }
}
/**
 * Are we searching API for People Groups?
 *
 * @author Johnathan Pulos
 */
if (strpos($requestedUrl, 'people_groups') !== false) {
    /**
     * Load the Query Generator for People Groups
     *
     * @author Johnathan Pulos
     */
    $loader->add("QueryGenerators", __DIR__ . $DS . ".." . $DS . "App" . $DS . $API_VERSION);
    require(__DIR__."/../App/" . $API_VERSION . "/Resources/PeopleGroups.php");
}
/**
 * Now run the Slim Framework rendering
 *
 * @author Johnathan Pulos
 */
$app->run();
