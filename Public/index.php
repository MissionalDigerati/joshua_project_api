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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */
/**
 * This requires PDO MySQL Support.
 *
 * @author Johnathan Pulos
 */
/**
 * Set the timezone
 */
date_default_timezone_set('America/Denver');
/**
 * Set whether to use Memcached for caching the queries.  Most queries are cached for 1 day.
 *
 * @var boolean
 * @author Johnathan Pulos
 */
$useCaching = false;
$googleDocTitle = '';
$DS = DIRECTORY_SEPARATOR;
$DOMAIN_ADDRESS = $_SERVER['SERVER_NAME'];
if ((substr_compare($DOMAIN_ADDRESS, "http://", 0, 7)) !== 0) {
    $DOMAIN_ADDRESS = "http://" . $DOMAIN_ADDRESS;
}
if (strpos($DOMAIN_ADDRESS, 'joshua.api.local') !== false) {
    $GOOGLE_TRACKING_ID = 'UA-49359140-2';
} elseif (strpos($DOMAIN_ADDRESS, 'jpapi.codingstudio.org') !== false) {
    $GOOGLE_TRACKING_ID = 'UA-49359140-1';
} else {
    $GOOGLE_TRACKING_ID = '';
}
/**
 * Set the Public directory path
 *
 * @var string
 * @author Johnathan Pulos
 */
$PUBLIC_DIRECTORY = dirname(__FILE__);
/**
 * Lets get the version of the API based on the URL (
 * http://joshua.api.local/v12/people_groups/daily_unreached.json?api_key=37e24112caae
 * ) It will default to the latest API.  You must provide an API Version if you are accessing the data.  The default is
 * only for static pages
 *
 * @author Johnathan Pulos
 */
$pattern = '/([v]+[1-9]+)/';
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
 * Set the Template View directory path
 *
 * @var string
 * @author Johnathan Pulos
 */
$VIEW_DIRECTORY = $PUBLIC_DIRECTORY . "/../App/" . $API_VERSION . "/Views/";
/**
 * Load up the Composer AutoLoader
 *
 * @author Johnathan Pulos
 */
$vendorDirectory = __DIR__ . $DS . ".." . $DS . "Vendor" . $DS;

require $vendorDirectory . 'autoload.php';

$app = new \Slim\Slim(array('templates.path' => $VIEW_DIRECTORY));
$settings = new JPAPI\DatabaseSettings();
$pdoDb = \PHPToolbox\PDODatabase\PDODatabaseConnect::getInstance();
$pdoDb->setDatabaseSettings(new \JPAPI\DatabaseSettings);
$db = $pdoDb->getDatabaseInstance();
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
/**
 * Are we on a static page?
 *
 * @author Johnathan Pulos
 */
$staticPages = array("/", "/get_my_api_key", "/resend_activation_links", "/getting_started");
if (in_array($requestedUrl, $staticPages)) {
    require(__DIR__."/../App/" . $API_VERSION . "/Resources/StaticPages.php");
    $bypassExtTest = true;
    $googleDocTitle = "Requesting a Static Page";
}
/**
 * Are we on a documentation page?
 *
 * @author Johnathan Pulos
 */
if (strpos($requestedUrl, '/docs') !== false) {
    require(__DIR__."/../App/" . $API_VERSION . "/Resources/Docs.php");
    $bypassExtTest = true;
    $googleDocTitle = "Requesting Documentation";
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
        $adminSettings = new \JPAPI\AdminSettings;
        $app->add(
            new Slim\Extras\Middleware\HttpBasicAuth(
                $adminSettings->default['username'],
                $adminSettings->default['password']
            )
        );
    }
    require(__DIR__."/../App/" . $API_VERSION . "/Resources/APIKeys.php");
    $bypassExtTest = true;
    $googleDocTitle = "Requesting Admin Area";
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
     * Load the Query Generator for People Groups, ProfileText, and Resources
     *
     * @author Johnathan Pulos
     */
    require(__DIR__."/../App/" . $API_VERSION . "/Resources/PeopleGroups.php");
    $googleDocTitle = "API Request for People Group Data.";
}
/**
 * Are we searching API for Countries?
 *
 * @author Johnathan Pulos
 */
if (strpos($requestedUrl, 'countries') !== false) {
    /**
     * Load the Query Generator for People Groups
     *
     * @author Johnathan Pulos
     */
    require(__DIR__."/../App/" . $API_VERSION . "/Resources/Countries.php");
    $googleDocTitle = "API Request for Country Data.";
}
/**
 * Are we searching API for Languages?
 *
 * @author Johnathan Pulos
 */
if (strpos($requestedUrl, 'languages') !== false) {
    /**
     * Load the Query Generator for Languages
     *
     * @author Johnathan Pulos
     */
    require(__DIR__."/../App/" . $API_VERSION . "/Resources/Languages.php");
    $googleDocTitle = "API Request for Language Data.";
}
/**
 * Are we searching API for Continents?
 *
 * @author Johnathan Pulos
 */
if (strpos($requestedUrl, 'continents') !== false) {
    /**
     * Load the Query Generator for Continents
     *
     * @author Johnathan Pulos
     */
    require(__DIR__."/../App/" . $API_VERSION . "/Resources/Continents.php");
    $googleDocTitle = "API Request for Continent Data.";
}
/**
 * Are we searching API for Regions?
 *
 * @author Johnathan Pulos
 */
if (strpos($requestedUrl, 'regions') !== false) {
    /**
     * Load the Query Generator for Regions
     *
     * @author Johnathan Pulos
     */
    require(__DIR__."/../App/" . $API_VERSION . "/Resources/Regions.php");
    $googleDocTitle = "API Request for Continent Data.";
}
/**
 * Send the request to Google Analytics
 *
 * @author Johnathan Pulos
 */
/**
 * Autoload the Google Analytics Class
 *
 * @author Johnathan Pulos
 */
if ($GOOGLE_TRACKING_ID != '') {
    $googleAnalytics = new \PHPToolbox\GoogleAnalytics\GoogleAnalytics($GOOGLE_TRACKING_ID);
    /**
     * Construct the Payload
     */
    if (isset($_SERVER['REQUEST_URI'])) {
        $dp = $_SERVER['REQUEST_URI'];
    } else {
        $dp = '';
    }
    if ((isset($APIKey)) && ($APIKey != '')) {
        $cid = $APIKey;
    } else {
        $cid = 'Site Visitor';
    }
    $payload = array(
        'cid'   =>  $cid,
        't'     =>  'pageview',
        'dh'    =>  $DOMAIN_ADDRESS,
        'dp'    =>  $dp,
        'dt'    =>  $googleDocTitle
    );
    /**
     * Send the payload
     */
    $googleAnalytics->save($payload);
}
/**
 * Now run the Slim Framework rendering
 *
 * @author Johnathan Pulos
 */
$app->run();
