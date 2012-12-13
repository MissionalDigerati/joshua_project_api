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
    foreach($data as $item) {
        $individualTag = $parentTag->addChild($individualWrap);
        foreach($item as $key => $val) {
            $individualTag->addChild($key, $val);
        }
    }
    return $xml->asXML();
}
/**
 * Takes a MySQLi Results Object and converts it to an array of data.
 *
 * @param object $mysqliResults a MySQLi Results Object
 * @return array
 * @access public
 * @author Johnathan Pulos
 */
function resultsToDataArray($mysqliResults) {
    $data = array();
    while ($row = $mysqliResults->fetch_assoc()) {
        array_push($data, $row);
    }
    return $data;
}