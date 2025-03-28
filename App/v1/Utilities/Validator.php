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

declare(strict_types=1);

namespace Utilities;

use PHPToolbox\CachedRequest\CurlUtility;

/**
 * A class that handles validation of supplied GET parameters.
 *
 * This class offers a variety of methods for validating GET data passed from a user.
 *
 * @author Johnathan Pulos
 * @package Utilities
 */
class Validator
{
    /**
     * Do the $requiredKeys exist?
     *
     * Checks weather the $providedParams array has the $requiredKeys.  It checks to see if the key exists,
     *  and wether the value is set.  Throws an error if it does not pass these checks.
     *
     * @param   array   $providedParams     An array to check.
     * @param   array   $requiredKeys       An array of keys that are required.
     * @return  void
     * @throws  \InvalidArgumentException   If the required key does not exist.
     * @throws  \InvalidArgumentException   If the keys value is not set.
     * @access public
     * @author Johnathan Pulos
     **/
    public function providedRequiredParams(array $providedParams, array $requiredKeys): void
    {
        foreach ($requiredKeys as $key) {
            if (array_key_exists($key, $providedParams) === false) {
                throw new \InvalidArgumentException("Missing the required parameter " . $key);
            }
            if (!isset($providedParams[$key])) {
                throw new \InvalidArgumentException("The required parameter " . $key . " is not set!");
            }
        }
    }
    /**
     * Validate all values in a piped (barred) string are acceptable.
     *
     * Checks the values of a piped (barred) seperated string that they each value is acceptable.  This method takes
     *  each item in $barSeperatedString, and checks if the key exists in the $acceptableValues.  If not then it will
     *  throw an error.
     *
     * @param   string  $barSeperatedString     The bar seperated string to evaluate.
     * @param   array   $acceptableValues       An array of values that are acceptable.
     * @return  void
     * @throws  \InvalidArgumentException   If the key does not exist in the $acceptableValues array.
     * @access  public
     * @author  Johnathan Pulos
     **/
    public function barSeperatedStringProvidesAcceptableValues(
        string $barSeperatedString,
        array $acceptableValues
    ): void {
        $barSeperatedValues = explode('|', $barSeperatedString);
        foreach ($barSeperatedValues as $barValue) {
            if (in_array(strtolower($barValue), $acceptableValues) === false) {
                throw new \InvalidArgumentException("A bar seperated parameter has the wrong permitted value.");
            }
        }
    }
    /**
     * Validate all values in a piped (barred) string are the correct length.
     *
     * Checks the values of a piped (barred) seperated string that they each value is a set length.  This method takes
     *  each item in $barSeperatedString, and checks if each key is a set $length.  If not then it will throw an error.
     *
     * @param   string  $barSeperatedString     The bar seperated string to test.
     * @param   int     $length                 The length that you want each key to be.
     * @return  void
     * @throws  \InvalidArgumentException   If the key is not equal to $length.
     * @access public
     * @author Johnathan Pulos
     **/
    public function stringLengthValuesBarSeperatedString(string $barSeperatedString, int $length): void
    {
        $barSeperatedValues = explode('|', $barSeperatedString);
        foreach ($barSeperatedValues as $barValue) {
            $this->stringLength($barValue, $length);
        }
    }
    /**
     * Validates a string is a given length.
     *
     * @param   string  $str    The string to test.
     * @param   int     $length The required length of the string.
     * @return  void
     * @throws  \InvalidArgumentException if the length is not equal.
     * @access  public
     * @author  Johnathan Pulos
     **/
    public function stringLength(string $str, int $length): void
    {
        if (strlen($str) != $length) {
            throw new \InvalidArgumentException("One of your parameters are not the correct length.");
        }
    }
    /**
     * Is the given integer withing range?
     *
     * Validates the integer is within the given range. [$start - $end].  You can supply an array of integers to exclude
     * from the comparison.  If the integer is in the $exceptions or out of range, it will throw an error.
     *
     * @param   int         $int        The integer to check.
     * @param   int         $start      The starting integer for the range.
     * @param   int         $end        The ending integer for the range.
     * @param   array       $exceptions An array of integers in the range that should not be accepted.
     * @return  void
     * @throws  \InvalidArgumentException if the integer is not in range.
     * @throws  \InvalidArgumentException if the integer is in the $exceptions array.
     * @access public
     * @author Johnathan Pulos
     **/
    public function integerInRange(int $int, int $start, int $end, $exceptions = []): void
    {
        if ((($int >= $start) && ($int <= $end)) == false) {
            throw new \InvalidArgumentException("One of the provided integers are out of range.");
        }
        if (in_array($int, $exceptions) === true) {
            throw new \InvalidArgumentException("One of the provided integers is not allowed.");
        }
    }
    /**
     * Is the provided string a valid SQL direction?
     *
     * @param string $direction The direction to sort by.
     *
     * @return void
     * @throws \InvalidArgumentException If the direction is not 'ASC' or 'DESC'.
     */
    public function isValidSQLDirection(string $direction): void
    {
        if (!in_array(strtoupper($direction), ['ASC', 'DESC'], true)) {
            throw new \InvalidArgumentException(
                "Invalid sort direction: $direction. Allowed values are 'ASC' or 'DESC'."
            );
        }
    }
    /**
     * Validate the recaptcha response
     *
     * @param string $secret The recaptcha secret
     * @param string $response The recaptcha response
     *
     * @return bool True if the recaptcha is valid
     */
    public function isValidRecaptcha(string $secret, string $response): bool
    {
        if (!$response) {
            return false;
        }

        $curlUtility = new CurlUtility();
        $reply = $curlUtility->makeRequest(
            "https://www.google.com/recaptcha/api/siteverify",
            "GET",
            ['secret' => $secret, 'response' => $response]
        );
        $data = json_decode($reply, true);
        return ($data['success'] === true);
    }
    /**
     * Validate the provided value is in the whitelist
     *
     * @param string $value The value to check
     * @param array $whitelist The whitelist to check against
     *
     * @return void
     * @throws \InvalidArgumentException If the value is not in the whitelist
     */
    public function isWhitelistedValue(string $value, array $whitelist): void
    {
        if (!in_array($value, $whitelist)) {
            throw new \InvalidArgumentException("The provided value: $value is not allowed.");
        }
    }
}
