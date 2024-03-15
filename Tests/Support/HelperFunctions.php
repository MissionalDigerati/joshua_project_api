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
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */
use PHPToolbox\PDODatabase\PDODatabaseConnect;

/**
 * A list of functions useful for testing
 *
 * @author Johnathan Pulos
 */
/**
 * Create an API key to use for testing
 *
 * @param  array  $data The data you want to use. (See the default data below)
 * @return string       The new API key
 */
function createApiKey($data = [])
{
    $db = getDatabaseInstance();
    if (count($data) === 0) {
        $data = array(
            'name' => 'Test API',
            'email' => 'newby@testing.com',
            'resource_used' =>  'testing',
            'api_usage' => 'pg-integration-testing',
            'status' => 1
        );
    }
    $data['api_key'] = generateRandomKey(12);
    $query = "INSERT INTO `md_api_keys` (name, email, api_usage, api_key, resource_used, status)
                VALUES (:name, :email, :api_usage, :api_key, :resource_used, :status)";
    try {
        $statement = $db->prepare($query);
        $statement->execute($data);
    } catch (PDOException $e) {
        echo "Unable to set the API Key!";
        die();
    }
    return $data['api_key'];
}
/**
 * Delete the API key from the database
 *
 * @param  string $apiKey The API key
 * @return void
 */
function deleteApiKey($apiKey)
{
    $db = getDatabaseInstance();
    $query = "DELETE FROM `md_api_keys` WHERE `api_key` = :key";
    try {
        $statement = $db->prepare($query);
        $statement->execute(['key'   =>  $apiKey]);
    } catch (PDOException $e) {
        echo "Unable to delete the API Key!";
        die();
    }
}
/**
 * Get an instance of the database.
 */
function getDatabaseInstance()
{
    $dbSettings = new \stdClass();
    $dbSettings->default = array(
        'host'      =>  $_ENV['DB_HOST'],
        'name'      =>  $_ENV['DB_NAME'],
        'username'  =>  $_ENV['DB_USERNAME'],
        'password'  =>  $_ENV['DB_PASSWORD']
    );
    $pdoDb = PDODatabaseConnect::getInstance();
    $pdoDb->setDatabaseSettings($dbSettings);
    return $pdoDb->getDatabaseInstance();
}
/**
 * Checks if a string is JSON
 *
 * @param string $string the string to check
 * @return boolean
 * @author Johnathan Pulos
 */
function isJSON($string)
{
    return json_decode($string) != null;
}
/**
 * Checks if a string is XML
 *
 * @param string $string the string to check
 * @return boolean
 * @author Johnathan Pulos
 */
function isXML($string)
{
    return simplexml_load_string($string) !== false;
}
