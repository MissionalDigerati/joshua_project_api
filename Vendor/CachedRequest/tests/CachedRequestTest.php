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
	 * The cachedRequest Object
	 *
	 * @var object
	 * @access private
	 */
	public $cachedRequest;
	
	/**
	 * Setup the testing environment
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function setUp() {
		$this->cachedRequest = new CachedRequest();
	}
	/**
	 * Tests to make sure the class can be initialized, and sets up the class correctly
	 * 
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testClassShouldSetTheAppropriateVariables() {
		$this->assertFalse($this->cachedRequest->cacheDirectory == '');
	}
	
	/**
	 * the clearCache() functions should empty the cache directory
	 *
	 * @covers CachedResult::clearCache
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testClearCacheShouldEmptyCacheDirectory() {
		$cachedFile = $this->cachedRequest->cacheDirectory . "newFile.cache";
		$this->createIfNotExistantFile($cachedFile);
		$this->cachedRequest->clearCache();
		$this->assertFalse(file_exists($cachedFile));
	}
	
	/**
	 * the clearCachedFileByReference() functions should remove the file associated to that reference
	 *
	 * @covers CachedRequest::clearCachedFileByReference
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testClearCacheFileByReferenceShouldRemoveTheCachedFile() {
		$reference = 'unreachedPeople';
		$cachedFile = $this->cachedRequest->cacheDirectory . $reference . ".cache";
		$this->createIfNotExistantFile($cachedFile);
		$this->cachedRequest->clearCachedFileByReference($reference);
		$this->assertFalse(file_exists($cachedFile));
	}
	
	/**
	 * the getCacheFilename() function should return a filename with the reference in it
	 *
	 * @covers CachedResult::getCacheFilename
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testGetCacheFilenameShouldReturnNameWithReference() {
		$reference = 'unreachedPeople';
		$filename = $this->cachedRequest->getCacheFilename($reference);
		$present = strpos($filename, $reference);
		$this->assertTrue($present !== false);
	}
	
	/**
	 * Tests that makeRequest() creates the cache file
	 *
	 * @covers CachedRequest::makeRequest
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testMakeRequestShouldCreateACacheFile() {
		$reference = 'giantSmashingRobots';
		$cachedFile = $this->cachedRequest->cacheDirectory . $reference . ".cache";
		$this->removeIfFileExists($cachedFile);
		$method = new ReflectionMethod('CachedRequest', 'makeRequest');
		$method->setAccessible(TRUE);
		$method->invoke($this->cachedRequest, 'GET', 'http://feeds.feedburner.com/GiantRobotsSmashingIntoOtherGiantRobots', array(), $reference);
		$this->assertTrue(file_exists($cachedFile));
	}
	
	/**
	 * Test that makeRequest() calls the curlUtility class if the cache file is not created
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testMakeRequestShouldGrabTheWebsiteContentIfNoCache() {
		$reference = 'giantSmashingRobotsWithMock';
		$cachedFile = $this->cachedRequest->cacheDirectory . $reference . ".cache";
		$this->removeIfFileExists($cachedFile);
		/**
		 * Setup a Mock for the curlUtility
		 *
		 * @author Johnathan Pulos
		 */
		$curlUtilityMock = $this->getMock('curlUtility', array('makeRequest'));
		$curlUtilityMock->expects($this->once())
										->method('makeRequest')
										->with('http://feeds.feedburner.com/GiantRobotsSmashingIntoOtherGiantRobots', 'GET', array())
										->will($this->returnValue('<html><body><p>I AM A FAKE!</p></body></html>'));
		$method = new ReflectionMethod('CachedRequest', 'setCurlUtilityObject');
		$method->setAccessible(TRUE);
		$method->invoke($this->cachedRequest, $curlUtilityMock);
		$method = new ReflectionMethod('CachedRequest', 'makeRequest');
		$method->setAccessible(TRUE);
		$method->invoke($this->cachedRequest, 'GET', 'http://feeds.feedburner.com/GiantRobotsSmashingIntoOtherGiantRobots', array(), $reference);
	}
	
	/**
	 * Test that makeRequest() does not call the curlUtility class if the cache file exists
	 *
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testMakeRequestShouldNotGrabTheWebsiteContentIfCached() {
		$reference = 'giantSmashingRobotsWithMockCached';
		$cachedFile = $this->cachedRequest->cacheDirectory . $reference . ".cache";
		$this->createIfNotExistantFile($cachedFile);
		/**
		 * Setup a Mock for the curlUtility
		 *
		 * @author Johnathan Pulos
		 */
		$curlUtilityMock = $this->getMock('curlUtility', array('makeRequest'));
		$curlUtilityMock->expects($this->never())
										->method('makeRequest');
		$method = new ReflectionMethod('CachedRequest', 'setCurlUtilityObject');
		$method->setAccessible(TRUE);
		$method->invoke($this->cachedRequest, $curlUtilityMock);
		$method = new ReflectionMethod('CachedRequest', 'makeRequest');
		$method->setAccessible(TRUE);
		$method->invoke($this->cachedRequest, 'GET', 'http://feeds.feedburner.com/GiantRobotsSmashingIntoOtherGiantRobots', array(), $reference);
	}

	/**
	 * Tests that writeCacheFile() writes content to a cache file
	 * 
	 * @covers CachedResult::writeCacheFile
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testWriteCacheFileShouldCreateAndWriteTheCachedFile() {
		$reference = 'giantSmashingRobotsAgain';
		$cachedFile = $this->cachedRequest->cacheDirectory . $reference . ".cache";
		$this->removeIfFileExists($cachedFile);
		$method = new ReflectionMethod('CachedRequest', 'writeCacheFile');
		$method->setAccessible(TRUE);
		$method->invoke($this->cachedRequest, 'Here is some content.', $reference);
		$this->assertTrue(file_exists($cachedFile));
		$this->assertFalse(0 == filesize($cachedFile));
	}
	
	/**
	 * Tests that isCached() responds true if the file exists
	 *
	 * @covers CachedResult::isCached
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testIsCachedShouldReturnTrueIfFileExists() {
		$reference = 'IAmACachedFile';
		$cachedFile = $this->cachedRequest->cacheDirectory . $reference . ".cache";
		$this->createIfNotExistantFile($cachedFile);
		$method = new ReflectionMethod('CachedRequest', 'isCached');
		$method->setAccessible(TRUE);
		$exists = $method->invoke($this->cachedRequest, $reference);
		$this->assertTrue($exists);
	}
	
	/**
	 * Tests that isCached() responds false if the file does not exist
	 *
	 * @covers CachedResult::isCached
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testIsCachedShouldReturnFalseIfNoFileExists() {
		$reference = 'IAmAUnCachedFile';
		$cachedFile = $this->cachedRequest->cacheDirectory . $reference . ".cache";
		$this->removeIfFileExists($cachedFile);
		$method = new ReflectionMethod('CachedRequest', 'isCached');
		$method->setAccessible(TRUE);
		$exists = $method->invoke($this->cachedRequest, $reference);
		$this->assertFalse($exists);
	}
	
	/**
	 * Tests that isCached() responds false if the file has expired
	 *
	 * @covers CachedResult::isCached
	 * @return void
	 * @access public
	 * @author Johnathan Pulos
	 */
	public function testIsCachedShouldReturnFalseIfExpired() {
		$reference = 'IAmAOutdatedCachedFile';
		$this->cachedRequest->cacheTime = 0;
		$cachedFile = $this->cachedRequest->cacheDirectory . $reference . ".cache";
		$this->createIfNotExistantFile($cachedFile);
		sleep(1);
		$method = new ReflectionMethod('CachedRequest', 'isCached');
		$method->setAccessible(TRUE);
		$exists = $method->invoke($this->cachedRequest, $reference);
		$this->assertFalse($exists);
	}
	
	/**
	 * If the file does not exist, then create it
	 *
	 * @param string $file the file to create
	 * @return void
	 * @access private
	 * @author Johnathan Pulos
	 */
	private function createIfNotExistantFile($file) {
		if(!file_exists($file)) {
			$fh = fopen($file, 'w');
			fclose($fh);
		}
	}
	
	/**
	 * If the file exists, then remove it
	 *
	 * @param string $file the file to create
	 * @return void
	 * @access private
	 * @author Johnathan Pulos
	 */
	private function removeIfFileExists($file) {
		if(file_exists($file)) {
			unlink($file);
		}
	}
	
}
?>