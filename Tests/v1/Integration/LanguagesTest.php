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

    public $cachedRequest;

    private $db;

    private $APIKey = '';

    private $APIVersion;

    private $siteURL;

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

    public function tearDown()
    {
        $this->cachedRequest->clearCache();
        deleteApiKey($this->APIKey);
    }

    public function testShowRequestsShouldRefuseAccessWithoutAnAPIKey()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/aar.json",
            array(),
            "aar_up_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }

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

    public function testShowRequestsShouldRefuseAccessWithABadAPIKey()
    {
        $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/aar.json",
            array('api_key' => 'BADKEY'),
            "bad_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }

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

    public function testShowRequestsShouldNotReturnRemovedFields()
    {
        $expectedLanguageCode = 'aar';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/" . $expectedLanguageCode . ".json",
            array('api_key' => $this->APIKey),
            "show_should_not_return_removed_show"
        );
        $decoded = json_decode($response, true);
        $this->assertFalse(array_key_exists('FCBH_ID', $decoded[0]));
        $this->assertFalse(array_key_exists('FourLaws', $decoded[0]));
        $this->assertFalse(array_key_exists('FourLaws_URL', $decoded[0]));
        $this->assertFalse(array_key_exists('GodsStory', $decoded[0]));
        $this->assertFalse(array_key_exists('GRN_URL', $decoded[0]));
        $this->assertFalse(array_key_exists('JF_ID', $decoded[0]));
        $this->assertFalse(array_key_exists('JF_URL', $decoded[0]));
        $this->assertFalse(array_key_exists('JPPopulation', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL3Edition14', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL3Edition14Orig', $decoded[0]));
        $this->assertFalse(array_key_exists('WorldSpeakers', $decoded[0]));
    }

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

    public function testIndexRequestsShouldRefuseAccessWithABadAPIKey()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array('api_key' => 'BADKEY'),
            "index_bad_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }

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

    public function testIndexRequestsShouldNotReturnRemovedFields()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array('api_key' => $this->APIKey),
            "should_not_return_removed_fields_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertFalse(array_key_exists('FCBH_ID', $decoded[0]));
        $this->assertFalse(array_key_exists('FourLaws', $decoded[0]));
        $this->assertFalse(array_key_exists('FourLaws_URL', $decoded[0]));
        $this->assertFalse(array_key_exists('GodsStory', $decoded[0]));
        $this->assertFalse(array_key_exists('GRN_URL', $decoded[0]));
        $this->assertFalse(array_key_exists('JF_ID', $decoded[0]));
        $this->assertFalse(array_key_exists('JF_URL', $decoded[0]));
        $this->assertFalse(array_key_exists('JPPopulation', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL3Edition14', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL3Edition14Orig', $decoded[0]));
        $this->assertFalse(array_key_exists('WorldSpeakers', $decoded[0]));
    }

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

    public function testIndexRequestsShouldReturnLanguagesBasedOnNumberOfPercentAdherent()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'pc_adherent'    =>  '35-61'
            ),
            "should_return_language_based_on_pc_adherent_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertGreaterThanOrEqual(35.00, floatval($lang['PercentAdherents']));
            $this->assertLessThanOrEqual(61.00, floatval($lang['PercentAdherents']));
        }
    }

    public function testIndexRequestsShouldReturnLanguagesBasedOnNumberOfPercentEvangelical()
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array(
                'api_key'           =>  $this->APIKey,
                'pc_evangelical'    =>  '16-40'
            ),
            "should_return_language_based_on_pc_evangelical_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $lang) {
            $this->assertGreaterThanOrEqual(16.00, floatval($lang['PercentEvangelical']));
            $this->assertLessThanOrEqual(40.00, floatval($lang['PercentEvangelical']));
        }
    }

}
