<?php
/**
 * This file is part of curl Utility.
 * 
 * curl Utility is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * curl Utility is distributed in the hope that it will be useful,
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
require_once(__DIR__."/curlUtility.php");
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
		$this->curl = new curlUtility();
		$this->cacheDirectory = __DIR__ . "/cache/";
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
		return $this->cacheDirectory . $reference . '.cache';
	}
	
}
?>