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

/**
 * Convert an array of data to XML.
 *
 * Takes the given data array, and converts it to XML.  It uses the $parentWrap as the first and last element.
 * Each additional child gets the $individualWrap tag.
 *
 * @param   array   $data           The data array to be converted to XML.
 * @param   string  $parentWrap     The parent wrapper tag name (default: items).
 * @param   string  $individualWrap The individual wrapper tag name (default: item).
 * @return  string  An XML string of the given data.
 * @author  Johnathan Pulos
 */
function arrayToXML($data, $parentWrap = "items", $individualWrap = "item")
{
    $xml = new SimpleXMLElement('<api/>');
    $parentTag = $xml->addChild($parentWrap);
    foreach ($data as $item) {
        $individualTag = $parentTag->addChild($individualWrap);
        foreach ($item as $key => $val) {
            addChildXMLElement($individualTag, $key, $val);
        }
    }
    return stripReturns($xml->asXML());
}
/**
 * Recursive function for adding the child XML elements.
 *
 * @param   SimpleXMLElement    $parentElement  A SimpleXMLElement which the children will be appended.
 * @param   string              $childLabel     The name for the child element.
 * @param   mixed               $childVal       The value of the child element.  If in array, we call this method again
 * @return  void
 * @author  Johnathan Pulos
 */
function addChildXMLElement($parentElement, $childLabel, $childVal)
{
    if (is_array($childVal)) {
        $newParentElement = $parentElement->addChild($childLabel);
        foreach ($childVal as $key => $val) {
            if (is_int($key)) {
                /**
                 * Must create a string label
                 *
                 * @author Johnathan Pulos
                 */
                $key = $childLabel . "_" . $key;
            }
            addChildXMLElement($newParentElement, $key, $val);
        }
    } elseif ($childVal) {
        $parentElement->addChild($childLabel, htmlspecialchars(strval($childVal)));
    }
}
/**
 * Get the URL for the site
 *
 * @return string   The site URL
 * @author  Johnathan Pulos
 * @link    https://gist.github.com/ChrisMcKee/1284052
 */
function getSiteURL()
{
    $protocol = "http://";
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
        $protocol = "https://";
    }
    return $protocol . $_SERVER['HTTP_HOST'];
}
/**
 * Returns the present value or the default value.
 *
 * Checks if $variable is true, if so it returns the $variable.  If variable is false/empty/null it returns $default.
 *
 * @param   mixed   $variable The variable to test if empty/null/false.
 * @param   mixed   $default The value to set if empty/null/false.
 * @return  mixed
 * @author  Johnathan Pulos
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
 * Returns the present value if the key exists or the default value.
 *
 * Checks if $variable array has the key $key, if so it returns $variable[$key].  If it does not, it returns $default.
 *
 * @param   array   $providedArray The array to test.
 * @param   string  $key The key to search for.
 * @param   mixed   $default The value to set if the key does not exist.
 * @return  mixed
 * @author  Johnathan Pulos
 */
function returnPresentIfKeyExistsOrDefault($providedArray, $key, $default)
{
    if (array_key_exists($key, $providedArray)) {
        return $providedArray[$key];
    } else {
        return $default;
    }
}
/**
 * Strips the string of carriage returns.
 *
 * @param   string  $str the string to clean.
 * @return  string
 * @author  Johnathan Pulos
 */
function stripReturns($str)
{
    $str = str_replace("\n", '', $str);
    $str = str_replace("\r", '', $str);
    return $str;
}
/**
 * Validates the presence of the requiredFields against the supplied formData.
 *
 * @param   array   $requiredFields The fields that are required to exist.
 * @param   array   $formData       The data passed from the form.
 * @return  array   An array of fields that were invalid.
 * @author  Johnathan Pulos
 **/
function validatePresenceOf($requiredFields, $formData)
{
    $invalidFields = [];
    foreach ($requiredFields as $field) {
        if (!array_key_exists($field, $formData)) {
            array_push($invalidFields, $field);
            continue;
        }
        $fieldParam = $formData[$field];
        if (is_string($fieldParam)) {
            $fieldParam = strip_tags($fieldParam);
        }
        if ((!$fieldParam) || (is_array($fieldParam) && (count($fieldParam) === 0))) {
            array_push($invalidFields, $field);
            continue;
        }
    }
    return $invalidFields;
}
/**
 * Create the redirect url by appending information to the URL.
 *
 * Creates a redirect url with several GET params.  These params include:
 * <ul>
 *  <li><strong>required_fields</strong> - A list of fields that are required and missing.</li>
 *  <li>Any fields provided with the values that were sent.</li>
 * </ul>
 * The URL looks like this: /home?required_fields=name|address&state=CA&zip=91801.
 *
 * @param   string  $redirectURL    The URL without GET params to redirect to.  This is the base URL.
 * @param   array   $formData       The data supplied by the form.
 * @param   array   $invalidFields  An array with the names of all invalid fields.
 * @return  string  The final URL to redirect to including the base URL.
 * @author  Johnathan Pulos
 **/
function generateRedirectURL($redirectURL, array $formData, array $invalidFields)
{
    $validFieldParams = [];
    $validParamsStartSymbol = "?";
    foreach ($formData as $key => $value) {
        if (is_string($value)) {
            $val = urlencode(strip_tags($value));
        } elseif (is_array($value)) {
            // Pipe separate the array values and then url encode them
            $val = implode("|", array_map('urlencode', $value));
        } else {
            continue;
        }
        
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
 * Generates a random key of alphanumerical characters.
 *
 * Using the current time, and md5 hashing, this function creates an alphanumeric string perfect for API keys.
 *
 * @param   integer     $length     The length of the key you need.
 * @return  string The alphanumeric random string.
 * @author  Johnathan Pulos
 **/
function generateRandomKey($length = 10)
{
    /**
     * Gets both seconds and microseconds parts of the time
     **/
    list($usec, $sec) = explode(' ', microtime());
    /**
     * remove the period in $usec
     **/
    $usec = preg_replace('[\.]', '', $usec);
    return substr(md5(date('ymd') . $usec . $sec), 0, $length);
}
