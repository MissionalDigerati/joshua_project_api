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
use Dotenv\Dotenv;

/**
 * Set the default time zone
 */
date_default_timezone_set('America/Denver');
$ROOT_DIRECTORY = dirname(__DIR__);
$DS = DIRECTORY_SEPARATOR;
/**
 * The version of the API to test for (ex. v1)
 *
 * @param string
 * @author Johnathan Pulos
 */
$API_VERSION = "v1";
/**
 * Load up the Composer AutoLoader
 *
 * @author Johnathan Pulos
 */
require $ROOT_DIRECTORY . $DS . "Vendor" . $DS . "autoload.php";
/**
 * Load env variables
 *
 * @author Johnathan Pulos
 */
$dotenv = Dotenv::createImmutable($ROOT_DIRECTORY, '.env.testing');
$dotenv->load();
require_once("Tests" . $DS . "Support" . $DS . "HelperFunctions.php");
require_once("App" . $DS . $API_VERSION . $DS . "Includes" . $DS . "CommonFunctions.php");
