<?php
require_once(__DIR__."/../CachedRequest.php");
require_once(__DIR__."/../curlUtility.php");
/**
 * A test for the CachedRequestt class
 *
 * @package default
 * @author Johnathan Pulos
 */
class CachedRequestTest extends PHPUnit_Framework_TestCase {
	/**
	 * Tests to make sure the class can be initialized, and sets up the class correctly
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testClassShouldSetTheAppropriateVariables() {
		$cachedRequest = new cachedRequest();
		$this->assertFalse($cachedRequest->cacheDirectory == '');
	}
	
	/**
	 * the clearCache() functions should empty the cache directory
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testClearCacheShouldEmptyCacheDirectory() {
		$cachedRequest = new cachedRequest();
		$cachedFile = $cachedRequest->cacheDirectory . "newFile.cache";
		$fh = fopen($cachedFile, 'w');
		fclose($fh);
		$cachedRequest->clearCache();
		$this->assertFalse(file_exists($cachedFile));
	}
	
	/**
	 * the clearCachedFileByReference() functions should remove the file associated to that reference
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testClearCacheFileByReferenceShouldRemoveTheCachedFile() {
		$cachedRequest = new cachedRequest();
		$reference = 'unreachedPeople';
		$cachedFile = $cachedRequest->cacheDirectory . $reference . ".cache";
		$fh = fopen($cachedFile, 'w');
		fclose($fh);
		$cachedRequest->clearCachedFileByReference($reference);
		$this->assertFalse(file_exists($cachedFile));
	}
	
	public function testGetCacheFilenameShouldReturnNameWithReference() {
		$cachedRequest = new cachedRequest();
		$reference = 'unreachedPeople';
		$filename = $cachedRequest->getCacheFilename($reference);
		$present = strpos($filename, $reference);
		$this->assertTrue($present !== false);
	}
	
}
?>