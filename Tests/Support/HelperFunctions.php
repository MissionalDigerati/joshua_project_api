<?php
/**
 * A list of functions useful for testing
 *
 * @author Johnathan Pulos
 */
/**
 * Checks if a string is JSON
 *
 * @param string $string the string to check
 * @return boolean
 * @author Johnathan Pulos
 */
function isJSON($string) {
	return json_decode($string) != null;
}
?>