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

declare(strict_types=1);

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Middleware\APIAuthMiddleware;
use Middleware\APIStandardsMiddleware;
use Middleware\CachingMiddleware;
use Middleware\GoogleAnalyticsMiddleware;
use PHPToolbox\PDODatabase\PDODatabaseConnect;
use Slim\Factory\AppFactory;
use Tuupola\Middleware\HttpBasicAuthentication;
use Slim\Views\PhpRenderer;
use Utilities\APIErrorResponder;
use Utilities\Mailer;
use Psr\Container\ContainerInterface;

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
 * Load env variables
 *
 * @author Johnathan Pulos
 */
$dotenv = Dotenv::createImmutable($ROOT_DIRECTORY);
$dotenv->load();
/**
 * Lets get the version of the API based on the URL (
 * http://joshua.api.local/v12/people_groups/daily_unreached.json?api_key=KEY
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
$VIEW_DIRECTORY = $APP_FILES_DIRECTORY . $DS . "Views" . $DS;

$containerBuilder = new ContainerBuilder();
$settings = require_once($ROOT_DIRECTORY . $DS . "Bootstrap" . $DS . "Settings.php");
$settings($containerBuilder);

$dependencies = require_once($ROOT_DIRECTORY . $DS . "Bootstrap" . $DS . "Dependencies.php");
$dependencies($containerBuilder, $VIEW_DIRECTORY);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Create the factory
AppFactory::setContainer($container);
$app = AppFactory::create();

if (file_exists($APP_FILES_DIRECTORY)) {
    /**
     * Include common functions
     *
     * @author Johnathan Pulos
     */
    require($APP_FILES_DIRECTORY . $DS . "Includes" . $DS . "CommonFunctions.php");
    $siteURL = getSiteURL();
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
}

// Register middleware
$middleware = require_once($ROOT_DIRECTORY . $DS . "Bootstrap" . $DS . "Middleware.php");
$middleware($app);

/**
 * Now run the Slim Framework rendering
 *
 * @author Johnathan Pulos
 */
$app->run();
