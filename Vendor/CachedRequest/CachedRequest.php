<?php
/**
 * This file is part of Cached Request.
 * 
 * Cached Request is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Cached Request is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see 
 * <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @copyright Copyright 2012 Missional Digerati
 * 
 */
require_once(__DIR__."/CurlUtility.php");
/**
 * A class for requesting a website, and caching the result for later use.  Speeds up testing for API services.
 *
 * @package default
 * @author Johnathan Pulos
 */
class CachedRequest {
	/**
	 * The directory to store cached files
	 *
	 * @var string
	 * @access public
	 */
	public $cacheDirectory = "";
	/**
	 * Length of time to cache a file in seconds
	 *
	 * @var integer
	 * @access public
	 */
	public $cacheTime = 3600;
	/**
	 * The cURL response code
	 *
	 * @var integer
	 */
	public $responseCode = 0;
	/**
	 * The curlUtility object for requests
	 *
	 * @var object
	 * @access protected
	 */
	protected $curl;
	/**
	 * Construct the class
	 *
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function __construct() {
		$this->setCurlUtilityObject(new CurlUtility());
		$this->cacheDirectory = __DIR__ . "/cache/";
	}
	
	/**
	 * A public interface for a GET request
	 *
	 * @param string $url the url to retrieve
	 * @param array $fields the fields to pass in
	 * @param string $reference the reference used in the request 
	 * @return string
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function get($url, $fields, $reference) {
		return $this->makeRequest('GET', $url, $fields, $reference);
	}
	
	/**
	 * A public interface for a POST request
	 *
	 * @param string $url the url to retrieve
	 * @param array $fields the fields to pass in
	 * @param string $reference the reference used in the request 
	 * @return string
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function post($url, $fields, $reference) {
		return $this->makeRequest('POST', $url, $fields, $reference);
	}

	/**
	 * Clear the cache directory of all files
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function clearCache() {
		$files = glob($this->cacheDirectory . '*');
		foreach($files as $file) {
			if(is_file($file)) {
				unlink($file);
			}
		}
	}
	
	/**
	 * Remove a specific cached file based on its reference
	 *
	 * @param string $reference the reference used in the request
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function clearCachedFileByReference($reference) {
		unlink($this->getCacheFilename($reference));
	}
	
	/**
	 * Get the filename with directory for the cached file based on the reference
	 *
	 * @param string $reference the reference used in the request
	 * @return string
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function getCacheFilename($reference) {
		return $this->cacheDirectory . $this->safeFilename($reference) . '.cache';
	}
	
	/**
	 * Make the server request.  It will either run the curlUtility and create a new cached file, or it will feed back the contents
	 * of the cache file
	 *
	 * @param string $method GET or POST
	 * @param string $url the url to request
	 * @param array $fields an array of fields to send
	 * @param string $reference the reference used in the request
	 * @return string
	 * @access private
	 * @author Johnathan Pulos
	 */
	private function makeRequest($method, $url, $fields, $reference) {
		$contents = '';
		if($this->isCached($reference)) {
			$contents = file_get_contents($this->getCacheFilename($reference));
			$this->responseCode = 200;
		} else{
			$contents = $this->curl->makeRequest($url, $method, $fields);
			$this->writeCacheFile($contents, $reference);
			$this->responseCode = $this->curl->responseCode;
		}
		return $contents;
	}
	
	/**
	 * Write the contents to the cached file
	 *
	 * @param string $contents the contents to write to the file
	 * @param string $reference the reference used in the request
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	private function writeCacheFile($contents, $reference) {
		$fh = fopen($this->getCacheFilename($reference), 'w');
		fwrite($fh, $contents);
		fclose($fh);
	}
	
	/**
	 * Create a method for setting up the curlUtility, that way we can override it with a mock in testing
	 *
	 * @param object $curlUtility the curlUtility object
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	private function setCurlUtilityObject($curlUtility) {
		$this->curl = $curlUtility;
	}
	
	/**
	 * Checks if the file is cached
	 *
	 * @param string $reference the reference used in the request
	 * @return boolean
	 * @access private
	 * @author Johnathan Pulos
	 */
	private function isCached($reference) {
		$filename = $this->getCacheFilename($reference);
		if(file_exists($filename) && (filemtime($filename) + $this->cacheTime >= time())) return true;
		return false;
	}
	
	/**
	 * Helper function to validate filenames
	 *
	 * @param string $filename the filename to check
	 * @return string
	 * @access private
	 * @author Johnathan Pulos
	 */
	private function safeFilename($filename) {
		return preg_replace('/[^0-9a-z\.\_\-]/i','', strtolower($filename));
	}
	
}
?>