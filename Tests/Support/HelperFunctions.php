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
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;

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
function createApiKey(array $data = []): string
{
    $db = getDatabaseInstance();
    if (count($data) === 0) {
        $data = [
            'name' => 'Test API',
            'email' => 'newby@testing.com',
            'api_usage' => 'pg-integration-testing',
            'status' => 1
        ];
    }
    $data['api_key'] = generateRandomKey(12);
    $query = "INSERT INTO `md_api_keys` (name, email, api_usage, api_key, status)
                VALUES (:name, :email, :api_usage, :api_key, :status)";
    try {
        $db->executeStatement($query, $data);
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
function deleteApiKey(string $apiKey): void
{
    $db = getDatabaseInstance();
    $query = "DELETE FROM `md_api_keys` WHERE `api_key` = :key";
    try {
        $db->executeStatement($query, ['key'   =>  $apiKey]);
    } catch (PDOException $e) {
        echo "Unable to delete the API Key!";
        die();
    }
}
/**
 * Get an instance of the database.
 *
 * @return Connection The database connection
 */
function getDatabaseInstance(): Connection
{
    return DriverManager::getConnection([
            'driver' => 'pdo_mysql',
            'host' => $_ENV['DB_HOST'],
            'dbname' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'charset' => 'utf8',
    ]);
}
/**
 * Checks if a string is JSON
 *
 * @param string $string the string to check
 *
 * @return boolean  Is it JSON?
 * @author Johnathan Pulos
 */
function isJSON(string $string): bool
{
    json_decode($string);
    return (json_last_error() === JSON_ERROR_NONE);
}
/**
 * Checks if a string is XML
 *
 * @param string $string the string to check
 *
 * @return boolean  Is it XML?
 * @author Johnathan Pulos
 */
function isXML(string $string): bool
{
    return simplexml_load_string($string) !== false;
}
