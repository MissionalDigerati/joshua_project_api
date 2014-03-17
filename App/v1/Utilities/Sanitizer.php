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
namespace Utilities;

/**
 * A class that handles sanitizing of the API supplied data
 *
 * @package default
 * @author Johnathan Pulos
 */
class Sanitizer
{
    /**
     * Cleans a param ($param) according to the given regex which removes unnecessary charcters, and html tags
     *
     * @param mixed $param the param to clean
     * @return mixed
     * @author Johnathan Pulos
     **/
    public function cleanParam($param)
    {
        return preg_replace('/[^a-z\d\-|\.]/i', '', strip_tags($param));
    }
    /**
     * Cleans an array's ($arr) values based on the same credentials of cleanParam()
     *
     * @param array $arr the array whose values need cleaning
     * @return array
     * @author Johnathan Pulos
     **/
    public function cleanArrayValues($arr)
    {
        $cleanedArray = array();
        foreach ($arr as $key => $value) {
            $cleanedArray[$key] = $this->cleanParam($value);
        }
        return $cleanedArray;
    }
}
