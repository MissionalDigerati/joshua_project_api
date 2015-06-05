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
 * Set the default time zone
 */
date_default_timezone_set('America/Denver');
$DS = DIRECTORY_SEPARATOR;
/**
 * The URL for the testing server
 *
 * @param string
 * @author Johnathan Pulos
 */
$SITE_URL = $_ENV['site_url'];
/**
 * The version of the API to test for (ex. v1)
 *
 * @param string
 * @author Johnathan Pulos
 */
$API_VERSION = "v1";
require_once("Tests" . $DS . "Support" . $DS . "HelperFunctions.php");
require_once("App" . $DS . $API_VERSION . $DS . "Includes" . $DS . "CommonFunctions.php");
/**
 * Load up the Composer autoloader
 *
 * @author Johnathan Pulos
 */
$vendorDirectory = __DIR__ . $DS . ".." . $DS . "Vendor" . $DS;

require $vendorDirectory . 'autoload.php';

$loader = new \Aura\Autoload\Loader;
$loader->register();
/**
 * Silent the Autoloader so we can see correct errors
 *
 * @author Johnathan Pulos
 */
$loader->setMode(\Aura\Autoload\Loader::MODE_SILENT);
/**
 * Setup the database object
 *
 * @author Johnathan Pulos
 */
$loader->add("JPAPI\DatabaseSettings", __DIR__ . $DS . ".." . $DS . "Config");
/**
 * AutoLoad the Utilities
 *
 * @return void
 * @author Johnathan Pulos
 */
$loader->add("Utilities\Validator", __DIR__ . $DS . ".." . $DS . "App" . $DS . $API_VERSION);
$loader->add("Utilities\Sanitizer", __DIR__ . $DS . ".." . $DS . "App" . $DS . $API_VERSION);
/**
 * AutoLoad the Query Generators
 *
 * @return void
 * @author Johnathan Pulos
 */
$loader->add("QueryGenerators\QueryGenerator", __DIR__ . $DS . ".." . $DS . "App" . $DS . $API_VERSION);
$loader->add("QueryGenerators\PeopleGroup", __DIR__ . $DS . ".." . $DS . "App" . $DS . $API_VERSION);
$loader->add("QueryGenerators\ProfileText", __DIR__ . $DS . ".." . $DS . "App" . $DS . $API_VERSION);
$loader->add("QueryGenerators\Resource", __DIR__ . $DS . ".." . $DS . "App" . $DS . $API_VERSION);
$loader->add("QueryGenerators\Country", __DIR__ . $DS . ".." . $DS . "App" . $DS . $API_VERSION);
$loader->add("QueryGenerators\Language", __DIR__ . $DS . ".." . $DS . "App" . $DS . $API_VERSION);
$loader->add("QueryGenerators\Continent", __DIR__ . $DS . ".." . $DS . "App" . $DS . $API_VERSION);
$loader->add("QueryGenerators\Region", __DIR__ . $DS . ".." . $DS . "App" . $DS . $API_VERSION);
