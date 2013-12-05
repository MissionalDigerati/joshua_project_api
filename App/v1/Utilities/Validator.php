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
namespace Utilities;

/**
 * A class that handles validation of the API supplied data
 *
 * @package default
 * @author Johnathan Pulos
 */
class Validator
{
    /**
     * Checks $providedParams to see that the $requiredKeys are set, and throws an error if it is not set
     *
     * @param array $providedParams An array of the given parameters with their values to check
     * @param array $requiredKeys An array of the required keys to check
     * @return void
     * @throws InvalidArgumentException if the param does not exist
     * @access public
     * @author Johnathan Pulos
     **/
    public function providedRequiredParams($providedParams, $requiredKeys)
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
     * Checks the values of a bar seperated string ($barSeperatedString) that they are acceptable ($acceptableValues)
     *
     * @param string $barSeperatedString the bar seperated string to evaluate
     * @param array $acceptableValues an array of values that are acceptable
     * @return void
     * @throws InvalidArgumentException if a value is not acceptable
     * @access public
     * @author Johnathan Pulos
     **/
    public function barSeperatedStringProvidesAcceptableValues($barSeperatedString, $acceptableValues)
    {
        $barSeperatedValues = explode('|', $barSeperatedString);
        foreach ($barSeperatedValues as $barValue) {
            if (in_array(strtolower($barValue), $acceptableValues) === false) {
                throw new \InvalidArgumentException("A bar seperated parameter has the wrong permitted value.");
            }
        }
    }
    /**
     * Checks the values of a bar seperated string ($barSeperatedString) that they are the correct length ($length)
     *
     * @param string $barSeperatedString the bar seperate string to test
     * @param integer $length the length they are expecting
     * @return void
     * @throws InvalidArgumentException if a value is not the correct length
     * @access public
     * @author Johnathan Pulos
     **/
    public function stringLengthValuesBarSeperatedString($barSeperatedString, $length)
    {
        $barSeperatedValues = explode('|', $barSeperatedString);
        foreach ($barSeperatedValues as $barValue) {
            $this->stringLength($barValue, $length);
        }
    }
    /**
     * Validates the given string ($str) is the given length ($length)
     *
     * @param string $str the string to test
     * @param integer $length the required length of the string
     * @return void
     * @throws InvalidArgumentException if the length is incorrect
     * @access public
     * @author Johnathan Pulos
     **/
    public function stringLength($str, $length)
    {
        if (strlen($str) != $length) {
            throw new \InvalidArgumentException("One of your parameters are not the correct length.");
        }
    }
}
