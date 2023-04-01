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
use JPAPI\AdminSettings;
use JPAPI\DatabaseSettings;
use Middleware\APIAuthMiddleware;
use PHPToolbox\PDODatabase\PDODatabaseConnect;
use Slim\Middleware\HttpBasicAuthentication;
use Slim\Views\PhpRenderer;
use Utilities\APIErrorResponder;
use Utilities\Mailer;

$DS = DIRECTORY_SEPARATOR;
$ROOT_DIRECTORY = dirname(__DIR__);

/**
 * Load up the Composer AutoLoader
 *
 * @author Johnathan Pulos
 */
require $ROOT_DIRECTORY . $DS . "Vendor" . $DS . "autoload.php";

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
/**
 * Set our directories
 *
 * @var string
 * @author Johnathan Pulos
 */
$APP_FILES_DIRECTORY = $ROOT_DIRECTORY . $DS . "App" . $DS . $API_VERSION;
$VIEW_DIRECTORY = $APP_FILES_DIRECTORY . $DS . "Views";

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'debug'               => true,
        'whoops.editor'       => 'sublime',
    ]
]);
/**
 * Add several services to our container for easy use
 */
$container = $app->getContainer();
$container['view'] = new PhpRenderer($VIEW_DIRECTORY);
$container['db'] = function () {
    $pdoDb = PDODatabaseConnect::getInstance();
    $pdoDb->setDatabaseSettings(new DatabaseSettings());
    return $pdoDb->getDatabaseInstance();
};
$container['mailer'] = new Mailer();
$container['errorResponder'] = new APIErrorResponder();
/**
 * Setup Middleware
 */
$adminSettings = new AdminSettings();
$authSettings = array(
    'path'          =>  array('/api_keys'),
    'passthrough'   =>  array('/api_keys/new')
);
$authSettings['users'][$adminSettings->default['username']] = $adminSettings->default['password'];
$app->add(new HttpBasicAuthentication($authSettings));

require($APP_FILES_DIRECTORY . $DS . "Middleware" . $DS . "PathBasedTrait.php");
require($APP_FILES_DIRECTORY . $DS . "Middleware" . $DS . "APIAuthMiddleware.php");
$apiAuthMiddlewareSettings = array(
    'passthrough' => array('/v\d+/docs/column_descriptions'),
    'paths'  =>  array(
        '/v\d+/continents',
        '/v\d+/countries',
        '/v\d+/languages',
        '/v\d+/people_groups',
        '/v\d+/regions'
    )
);
$app->add(new APIAuthMiddleware($container['db'], $apiAuthMiddlewareSettings));
/**
 * Include common functions
 *
 * @author Johnathan Pulos
 */
require($APP_FILES_DIRECTORY . $DS . "Includes" . $DS . "CommonFunctions.php");
$siteURL = getSiteURL();
if (strpos($siteURL, 'joshua.api.local') !== false) {
    $GOOGLE_TRACKING_ID = 'UA-49359140-2';
} elseif (strpos($siteURL, 'jpapi.codingstudio.org') !== false) {
    $GOOGLE_TRACKING_ID = 'UA-49359140-1';
} else {
    $GOOGLE_TRACKING_ID = '';
}
/**
 * Include our resources
 *
 * @author Johnathan Pulos
 */
require($APP_FILES_DIRECTORY . $DS . "Resources" . $DS . "StaticPages.php");
require($APP_FILES_DIRECTORY . $DS . "Resources" . $DS . "Docs.php");
require($APP_FILES_DIRECTORY . $DS . "Resources" . $DS . "APIKeys.php");
require($APP_FILES_DIRECTORY . $DS . "Resources" . $DS . "PeopleGroups.php");
require($APP_FILES_DIRECTORY . $DS . "Resources" . $DS . "Countries.php");
require($APP_FILES_DIRECTORY . $DS . "Resources" . $DS . "Languages.php");
require($APP_FILES_DIRECTORY . $DS . "Resources" . $DS . "Continents.php");
require($APP_FILES_DIRECTORY . $DS . "Resources" . $DS . "Regions.php");

/**
 * Now run the Slim Framework rendering
 *
 * @author Johnathan Pulos
 */
$app->run();
