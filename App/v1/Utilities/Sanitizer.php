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

/**
 * A class that handles sanitizing of supplied GET parameters.
 *
 * This class offers a variety of methods for cleaning GET data passed from a user.
 *
 * @author Johnathan Pulos
 * @package Utilities
 */
class Sanitizer
{
    /**
     * Clean the given param.
     *
     * Cleans $param according to the given regex which removes unnecessary characters, and html tags.
     *
     * @param   string  $param  The string to clean.
     * @return  string  The cleaned string.
     * @access  public
     * @author  Johnathan Pulos
     **/
    public function cleanParam(string $param): string
    {
        return preg_replace('/[^a-z\d\-|\.]/i', '', strip_tags($param));
    }
    /**
     * Clean all values in an array.
     *
     * iterates over the values of an array, and cleans the values.
     *
     * @param   array   $arr    The array whose values need cleaning.
     * @return  array   The cleaned array.
     * @author  Johnathan Pulos
     **/
    public function cleanArrayValues(array $arr): array
    {
        $cleanedArray = [];
        foreach ($arr as $key => $value) {
            $cleanedArray[$key] = $this->cleanParam(strval($value));
        }
        return $cleanedArray;
    }
}
