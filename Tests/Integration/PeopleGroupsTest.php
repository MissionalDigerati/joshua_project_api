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
namespace Tests\Integration;

/**
 * The class for testing integration of the People Groups
 *
 * @package default
 * @author Johnathan Pulos
 */
class PeopleGroupsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The CachedRequest Object
     *
     * @var object
     */
    public $cachedRequest;

    /**
     * Set up the test class
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function setUp()
    {
        $this->cachedRequest = new \PHPToolbox\CachedRequest\CachedRequest;
        $this->cachedRequest->cacheDirectory =
            __DIR__ .
            DIRECTORY_SEPARATOR . ".." .
            DIRECTORY_SEPARATOR . "Support" .
            DIRECTORY_SEPARATOR . "cache" .
            DIRECTORY_SEPARATOR;
    }
    /**
     * Runs at the end of each test
     *
     * @access public
     * @author Johnathan Pulos
     */
    public function tearDown()
    {
        $this->cachedRequest->clearCache();
    }

     /**
      * GET /people_groups/daily_unreached.json 
      * test page is available, and delivers JSON
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShouldGetDailyUnreachedInJSON()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/daily_unreached.json",
            array(),
            "up_json"
        );
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertFalse(isJSON($response));
    }

     /**
      * GET /people_groups/daily_unreached.xml 
      * test page is available, and delivers XML
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShouldGetDailyUnreachedInXML()
    {
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/daily_unreached.xml",
            array(),
            "up_xml"
        );
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertTrue(isXML($response));
    }

    /**
     * A request for Daily Unreached should allow setting the month
     *
     * @access public
     * @author Johnathan Pulos
     */
    public function testShouldGetDailyUnreachedWithSetMonth()
    {
        $expectedMonth = '5';
        $expectedDay = Date('j');
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/daily_unreached.json?month=".$expectedMonth,
            array(),
            "up_month"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedMonth, $decodedResponse[0]['LRofTheDayMonth']);
        $this->assertEquals($expectedDay, $decodedResponse[0]['LRofTheDayDay']);
    }

    /**
     * A request for Daily Unreached should allow setting the day
     *
     * @access public
     * @author Johnathan Pulos
     */
    public function testShouldGetDailyUnreachedWithSetDay()
    {
        $expectedMonth = Date('n');
        $expectedDay = '23';
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/daily_unreached.json?day=".$expectedDay,
            array(),
            "up_day"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedMonth, $decodedResponse[0]['LRofTheDayMonth']);
        $this->assertEquals($expectedDay, $decodedResponse[0]['LRofTheDayDay']);
    }

    /**
     * A request for Daily Unreached should allow setting the day and month
     *
     * @access public
     * @author Johnathan Pulos
     */
    public function testShouldGetDailyUnreachedWithSetDayAndMonth()
    {
        $expectedMonth = '3';
        $expectedDay = '21';
        $response = $this->cachedRequest->get(
            "http://joshua.api.local/people_groups/daily_unreached.json?day=".$expectedDay."&month=".$expectedMonth,
            array(),
            "up_day_and_month"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedMonth, $decodedResponse[0]['LRofTheDayMonth']);
        $this->assertEquals($expectedDay, $decodedResponse[0]['LRofTheDayDay']);
    }
}
