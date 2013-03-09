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
/**
 * Convert an array of data to XML
 *
 * @param array $data The data array to be converted to XML
 * @param string $parentWrap The parent wrapper tag name (default: items)
 * @param string $individualWrap  The individual wrapper tag name (default: item)
 * @return string
 * @access public
 * @author Johnathan Pulos
 */
function arrayToXML($data, $parentWrap = "items", $individualWrap = "item")
{
    $xml = new SimpleXMLElement('<api/>');
    $parentTag = $xml->addChild($parentWrap);
    foreach ($data as $item) {
        $individualTag = $parentTag->addChild($individualWrap);
        foreach ($item as $key => $val) {
            $individualTag->addChild($key, $val);
        }
    }
    return stripReturns($xml->asXML());
}
/**
 * Checks if $variable is false, if so it returns $variable, else it returns $default
 *
 * @param mixed $variable The variable to test if empty
 * @param mixed $default The value to set if empty
 * @return mixed
 * @access public
 * @author Johnathan Pulos
 */
function returnPresentOrDefault($variable, $default)
{
    if ($variable) {
        return $variable;
    } else {
        return $default;
    }
}
/**
 * Strips the string of carriage returns
 *
 * @param string $str the string to clean 
 * @return string
 * @access public
 * @author Johnathan Pulos
 */
function stripReturns($str)
{
    $str = str_replace("\n", '', $str);
    $str = str_replace("\r", '', $str);
    return $str;
}
