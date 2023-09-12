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
namespace Tests\v1\Integration;

/**
 * The class for testing integration of the Languages
 *
 * @package default
 * @author Johnathan Pulos
 */
class LanguagesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The CachedRequest Object
     *
     * @var \PHPToolbox\CachedRequest\CachedRequest
     */
    public $cachedRequest;
    /**
     * The PDO database connection object
     *
     * @var \PHPToolbox\PDODatabase\PDODatabaseConnect
     */
    private $db;
    /**
     * The APIKey to access the API
     *
     * @var string
     * @access private
     **/
    private $APIKey = '';
    /**
     * The current API version number
     *
     * @var string
     * @access private
     **/
    private $APIVersion;
    /**
     * The URL for the testing server
     *
     * @var string
     * @access private
     **/
    private $siteURL;
    /**
     * Set up the test class
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     */
    public function setUp()
    {
        global $API_VERSION;
        $this->APIVersion = $API_VERSION;
        global $SITE_URL;
        $this->siteURL = $SITE_URL;
        $this->cachedRequest = new \PHPToolbox\CachedRequest\CachedRequest;
        $this->cachedRequest->cacheDirectory =
            __DIR__ .
            DIRECTORY_SEPARATOR . ".." .
            DIRECTORY_SEPARATOR . ".." .
            DIRECTORY_SEPARATOR . "Support" .
            DIRECTORY_SEPARATOR . "cache" .
            DIRECTORY_SEPARATOR;
        $this->db = getDatabaseInstance();
        $this->APIKey = createApiKey();
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
        deleteApiKey($this->APIKey);
    }
    /**
     * Tests that you can only access page with an API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldRefuseAccessWithoutAnAPIKey()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/aar.json",
            array(),
            "aar_up_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can only access page with a version number
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldRefuseAccessWithoutAVersionNumber()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/languages/aar.json",
            array('api_key' => $this->APIKey),
            "versioning_missing_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertEquals(
            'You are requesting an unavailable API version number.',
            $decoded['api']['error']['details']
        );
        $this->assertEquals('Bad Request', $decoded['api']['error']['message']);
    }
    /**
     * Tests that you can not access page without an active API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldRefuseAccessWithoutAnActiveAPIKey()
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 0 WHERE `api_key` = '" . $this->APIKey . "'");
        $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/aar.json",
            array('api_key' => $this->APIKey),
            "non_active_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can not access page with a suspended API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldRefuseAccessWithSuspendedAPIKey()
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `api_key` = '" . $this->APIKey . "'");
        $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/aar.json",
            array('api_key' => $this->APIKey),
            "suspended_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can only access page with a valid API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldRefuseAccessWithABadAPIKey()
    {
        $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/aar.json",
            array('api_key' => 'BADKEY'),
            "bad_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
     /**
      * GET /languages/[id].json
      * test page is available, and delivers JSON
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowRequestsShouldReturnLanguagesInJSON()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/aar.json",
            array('api_key' => $this->APIKey),
            "show_accessible_in_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }
    /**
      * GET /languages/[id].xml
      * test page is available, and delivers XML
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testShowRequestsShouldReturnLanguagesInXML()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/aar.xml",
            array('api_key' => $this->APIKey),
            "show_accessible_in_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isXML($response));
    }
    /**
     * GET /languages/[id].json
     * test the page throws an error if you send it an invalid id
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldThrowErrorIfBadIdProvided()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/1223.json",
            array('api_key' => $this->APIKey),
            "show_with_bad_id"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $this->assertEquals('You provided an invalid language id.', $decoded['api']['error']['details']);
        $this->assertEquals('Bad Request', $decoded['api']['error']['message']);
    }
    /**
     * GET /languages/[id].json
     * test page returns the language requested
     *
     * @return void
     * @access public
     * @author Johnathan Pulos
     **/
    public function testShowRequestsShouldRetrieveTheCorrectLanguage()
    {
        $expectedLanguageCode = 'aar';
        $expectedLanguage = 'afar';
        $expectedHubCountry = 'ethiopia';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/" . $expectedLanguageCode . ".json",
            array('api_key' => $this->APIKey),
            "show_returns_appropriate_language"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedLanguageCode, strtolower($decodedResponse[0]['ROL3']));
        $this->assertEquals($expectedLanguage, strtolower($decodedResponse[0]['Language']));
        $this->assertEquals($expectedHubCountry, strtolower($decodedResponse[0]['HubCountry']));
    }
    /**
     * Tests that you can only access page with an API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testIndexRequestsShouldRefuseAccessWithoutAnAPIKey()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(),
            "index_lang_up_test_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertEquals('You are missing your API key.', $decoded['api']['error']['details']);
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
    }
    /**
     * Tests that you can only access page with a version number
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testIndexRequestsShouldRefuseAccessWithoutAVersionNumber()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/languages.json",
            array('api_key' => $this->APIKey),
            "index_versioning_missing_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertEquals(
            'You are requesting an unavailable API version number.',
            $decoded['api']['error']['details']
        );
        $this->assertEquals('Bad Request', $decoded['api']['error']['message']);
    }
    /**
     * Tests that you can not access page without an active API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testIndexRequestsShouldRefuseAccessWithoutAnActiveAPIKey()
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 0 WHERE `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array('api_key' => $this->APIKey),
            "index_non_active_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can not access page with a suspended API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testIndexRequestsShouldRefuseAccessWithSuspendedAPIKey()
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array('api_key' => $this->APIKey),
            "index_suspended_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
     * Tests that you can only access page with a valid API Key
     *
     * @return void
     * @author Johnathan Pulos
     **/
    public function testIndexRequestsShouldRefuseAccessWithABadAPIKey()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array('api_key' => 'BADKEY'),
            "index_bad_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }
    /**
      * GET /languages.json
      * Language Index should return the correct data
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnALanguageInJSON()
    {
        $expectedLanguageCount = 250;
        $expectedFirstLanguage = "a'ou";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array('api_key' => $this->APIKey),
            "should_return_language_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        $this->assertTrue(is_array($decodedResponse));
        $this->assertFalse(empty($decodedResponse));
        $this->assertEquals($expectedLanguageCount, count($decodedResponse));
        $this->assertEquals($expectedFirstLanguage, strtolower($decodedResponse[0]['Language']));
    }
    /**
      * GET /languages.json
      * Language Index should return the correct limit
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnALimitOfLanguagesBasedOnTheLimitParameter()
    {
        $expectedLimit = 10;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'   =>  $this->APIKey,
                'limit'     =>  $expectedLimit
            ),
            "should_return_language_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedLimit, count($decodedResponse));
    }
    /**
      * GET /languages.json?ids=bzw|bjf
      * Language Index should return only desired ids
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnTheCorrectLanguageIds()
    {
        $expectedIds = 'bzw|bjf';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'   =>  $this->APIKey,
                'ids'     =>  $expectedIds
            ),
            "should_return_language_by_ids_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertTrue(in_array(strtolower($lang['ROL3']), explode('|', $expectedIds)));
        }
    }
    /**
      * GET /languages.json?ids=bzwp
      * Language Index should return an error if the id is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnErrorIfTheIdIsWrong()
    {
        $expectedIds = 'bzwp';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'   =>  $this->APIKey,
                'ids'     =>  $expectedIds
            ),
            "should_return_language_by_wrong_ids_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(500, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $this->assertEquals('One of your parameters are not the correct length.', $decoded['api']['error']['details']);
    }
    /**
      * GET /languages.json?has_new_testament=y
      * Language Index should return only languages with new testaments
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnALanguagesWithNewTestaments()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'               =>  $this->APIKey,
                'has_new_testament'     =>  'Y'
            ),
            "should_return_language_by_ids_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertNotNull($lang['NTYear']);
        }
    }
    /**
      * GET /languages.json?has_new_testament=y
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnAnErrorIfProvidingTheWrongNewTestamentValue()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'               =>  $this->APIKey,
                'has_new_testament'     =>  'NNN'
            ),
            "should_return_language_by_wrong_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(500, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $this->assertEquals('One of your parameters are not the correct length.', $decoded['api']['error']['details']);
    }
    /**
      * GET /languages.json?has_portions=y
      * Language Index should return only languages with portions of the Bible
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnLanguagesWithPortionsOfScriptures()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'has_portions'      =>  'Y'
            ),
            "should_return_language_with_portions_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertNotNull($lang['PortionsYear']);
        }
    }
    /**
      * GET /languages.json?has_new_testament=ysss
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnAnErrorIfHasPortionsParmeterIsWrong()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'               =>  $this->APIKey,
                'has_portions'          =>  'NNN'
            ),
            "should_return_language_by_has_portions_wrong_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(500, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $this->assertEquals('One of your parameters are not the correct length.', $decoded['api']['error']['details']);
    }
    /**
      * GET /languages.json?has_completed_bible=y
      * Language Index should return only languages with a complete Bible
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnLanguagesWithCompleteScriptures()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'                   =>  $this->APIKey,
                'has_completed_bible'      =>  'Y'
            ),
            "should_return_language_with_complete_bible_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertNotNull($lang['BibleYear']);
        }
    }
    /**
      * GET /languages.json?has_completed_bible=ysss
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnAnErrorIfHasCompleteBibleParameterIsWrong()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'               =>  $this->APIKey,
                'has_completed_bible'   =>  'B'
            ),
            "should_return_language_by_has_completed_wrong_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(500, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $this->assertEquals('A boolean was set with the wrong value.', $decoded['api']['error']['details']);
    }
    /**
      * GET /languages.json?needs_translation_questionable=ysss
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnErrorIfHasQuestionableParameterIsWrong()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'               =>  $this->APIKey,
                'needs_translation_questionable'   =>  'G'
            ),
            "should_return_language_by_has_questionable_wrong_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(500, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $this->assertEquals('A boolean was set with the wrong value.', $decoded['api']['error']['details']);
    }
    /**
      * GET /languages.json?has_audio=y
      * Language Index should return only languages with audio resources
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnLanguagesWithAudioResources()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'     =>  $this->APIKey,
                'has_audio'   =>  'Y'
            ),
            "should_return_language_with_audio_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertEquals('Y', $lang['AudioRecordings']);
        }
    }
    /**
      * GET /languages.json?has_audio=ysss
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnErrorIfHasAudioParameterIsWrong()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'     =>  $this->APIKey,
                'has_audio'   =>  'T'
            ),
            "should_return_language_by_has_audio_wrong_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(500, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $this->assertEquals('A boolean was set with the wrong value.', $decoded['api']['error']['details']);
    }
    /**
      * GET /languages.json?has_jesus_film=y
      * Language Index should return only languages with Jesus Film
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnLanguagesWithJesusFilm()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'has_jesus_film'    =>  'Y'
            ),
            "should_return_language_with_jesus_film_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertEquals('Y', $lang['JF']);
        }
    }
    /**
      * GET /languages.json?has_jesus_film=ysss
      * Language Index should return an error if the value is too long
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnErrorIfHasJesusFilmParameterIsWrong()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'has_jesus_film'    =>  'Q'
            ),
            "should_return_language_by_has_jesus_film_wrong_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(500, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $this->assertEquals('A boolean was set with the wrong value.', $decoded['api']['error']['details']);
    }
    /**
      * GET /languages.json?countries=af|cn
      * Language Index should return only languages based on countries spoken in
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnLanguagesBasedOnCountries()
    {
        $expectedCountries = array('af', 'cn');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'countries'         =>  implode("|", $expectedCountries)
            ),
            "should_return_language_based_on_country_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertTrue(in_array(strtolower($lang['ROG3']), $expectedCountries));
        }
    }
    /**
      * GET /languages.json?primary_religions=6|4
      * Language Index should return only languages with requested primary religions
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnLanguagesBasedOnNumberOfPrimaryReligions()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'primary_religions' =>  '6|4'
            ),
            "should_return_language_based_on_primary_religion_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertTrue(in_array(strtolower($lang['PrimaryReligion']), array('islam', 'ethnic religions')));
        }
    }
    /**
      * GET /languages.json?primary_religions=150
      * Language Index should return an error if the value is wrong
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnErrorIfPrimaryReligionParameterIsWrong()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'primary_religions' =>  '150'
            ),
            "should_return_language_by_primary_religions_wrong_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(500, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $this->assertEquals('One of the provided integers are out of range.', $decoded['api']['error']['details']);
    }
    /**
      * GET /languages.json?jpscale=2-4
      * Language Index should return only languages with requested jpscale
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnLanguagesBasedOnJPScale()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'jpscale'           =>  '2|3'
            ),
            "should_return_language_based_on_jpscale_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertTrue(in_array(floatval($lang['JPScale']), array(2, 3)));
        }
    }
    /**
      * GET /languages.json?jpscale=150
      * Language Index should return an error if the value is wrong
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnErrorIfJPScaleParameterIsWrong()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'jpscale'           =>  '150'
            ),
            "should_return_language_by_jpscale_wrong_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(500, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $this->assertEquals(
            'A bar seperated parameter has the wrong permitted value.',
            $decoded['api']['error']['details']
        );
    }
    /**
      * GET /languages.json?least_reached=n
      * Language Index should return only languages with least reached
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnLanguagesBasedOnLeastReached()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'least_reached'     =>  'n'
            ),
            "should_return_language_based_on_least_reached_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertEquals('N', $lang['LeastReached']);
        }
    }
    /**
      * GET /languages.json?least_reached=MNNN
      * Language Index should return an error if the value is wrong
      *
      * @access public
      * @author Johnathan Pulos
      */
    public function testIndexRequestsShouldReturnErrorIfLeastReachedParameterIsWrong()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'least_reached'     =>  'G'
            ),
            "should_return_language_by_least_reached_wrong_value_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(500, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $this->assertEquals('A boolean was set with the wrong value.', $decoded['api']['error']['details']);
    }
}
