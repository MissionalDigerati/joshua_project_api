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
 * Checks if $variable has the key $key, if so it returns $variable[$key], else it returns $default
 *
 * @param array $variable The variable to test for key
 * @param string $key The key to search for
 * @param mixed $default The value to set if empty
 * @return mixed
 * @access public
 * @author Johnathan Pulos
 */
function returnPresentIfKeyExistsOrDefault($variable, $key, $default)
{
    if (array_key_exists($key, $variable)) {
        return $variable[$key];
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
/**
 * Validates the presence of the requiredFields against the supplied formData
 *
 * @param array $requiredFields the fields requiring presence of
 * @param array $formData the data passed form the form
 * @return array
 * @access public
 * @author Johnathan Pulos
 **/
function validatePresenceOf(array $requiredFields, array $formData)
{
    $invalidFields = array();
    foreach ($requiredFields as $field) {
        $fieldParam = strip_tags($formData[$field]);
        if (!$fieldParam) {
            array_push($invalidFields, $field);
        }
    }
    return $invalidFields;
}
/**
 * Creates the redirect url based on the $invalidFields parameters, and the data passed $formData
 * It redirects to the $redirectUrl and passes required_fields in GET string if there was an error
 * @example /home?required_fields=name|address&state=CA&zip=91801
 *
 * @param string $redirectURL the url to redirect to
 * @param array $formData the data supplied by the form
 * @param array $invalidFields an array with the names of all invalid fields
 * @return string
 * @author Johnathan Pulos
 **/
function generateRedirectURL($redirectURL, array $formData, array $invalidFields)
{
    $validFieldParams = array();
    $validParamsStartSymbol = "?";
    foreach ($formData as $key => $value) {
        $val = urlencode(strip_tags($value));
        if ($val) {
            array_push($validFieldParams, $key . "=" . $val);
        }
    }
    if (!empty($invalidFields)) {
        $redirectURL .= "?required_fields=" . implode("|", $invalidFields);
        $validParamsStartSymbol = "&";
    }
    if (!empty($validFieldParams)) {
        $redirectURL .= $validParamsStartSymbol . implode("&", $validFieldParams);
    }
    return $redirectURL;
}
/**
 * Generate a random key of alphanumerical characters.  This function:
 *
 * @return void
 * @author Johnathan Pulos
 **/
function generateRandomKey($length = 10)
{
    /**
     * Gets both seconds and microseconds parts of the time
     *
     * @author Johnathan Pulos
     **/
    list($usec, $sec) = explode(' ', microtime());
    /**
     * remove the period in $usec
     *
     * @author Johnathan Pulos
     **/
    $usec = preg_replace('[\.]', '', $usec);
    return substr(md5(date('ymd') . $usec . $sec), 0, $length);
}
