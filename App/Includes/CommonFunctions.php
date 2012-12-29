<?php
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
function arrayToXML($data, $parentWrap = "items", $individualWrap = "item") {
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
