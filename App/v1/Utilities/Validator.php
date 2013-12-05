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
}
