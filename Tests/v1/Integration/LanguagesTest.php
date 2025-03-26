<?php
declare(strict_types=1);

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

use PHPToolbox\CachedRequest\CachedRequest;
use PHPUnit\Framework\TestCase;

/**
 * The class for testing integration of the Languages
 *
 * @package default
 * @author Johnathan Pulos
 */
class LanguagesTest extends TestCase
{

    public $cachedRequest;

    private $db;

    private $APIKey = '';

    private $APIVersion;

    private $siteURL;

    public function setUp(): void
    {
        $this->APIVersion = $_ENV['api_version'];
        $this->siteURL = $_ENV['site_url'];
        $this->cachedRequest = new CachedRequest();
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

    public function tearDown(): void
    {
        $this->cachedRequest->clearCache();
        deleteApiKey($this->APIKey);
    }

    public function testShowRequestsShouldRefuseAccessWithoutAnAPIKey(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/aar.json",
            array(),
            "aar_up_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }

    public function testShowRequestsShouldRefuseAccessWithoutAnActiveAPIKey(): void
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 0 WHERE `api_key` = '" . $this->APIKey . "'");
        $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/aar.json",
            array('api_key' => $this->APIKey),
            "non_active_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }

    public function testShowRequestsShouldRefuseAccessWithSuspendedAPIKey(): void
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `api_key` = '" . $this->APIKey . "'");
        $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/aar.json",
            array('api_key' => $this->APIKey),
            "suspended_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }

    public function testShowRequestsShouldRefuseAccessWithABadAPIKey(): void
    {
        $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/aar.json",
            array('api_key' => 'BADKEY'),
            "bad_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }

    public function testShowRequestsShouldReturnLanguagesInJSON(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/aar.json",
            array('api_key' => $this->APIKey),
            "show_accessible_in_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }

    public function testShowRequestsShouldReturnLanguagesInXML(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages/aar.xml",
            array('api_key' => $this->APIKey),
            "show_accessible_in_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isXML($response));
    }

    public function testShowRequestsShouldThrowErrorIfBadIdProvided(): void
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

    public function testShowRequestsShouldRetrieveTheCorrectLanguage(): void
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

    public function testShowRequestsShouldNotReturnRemovedFields(): void
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
        $this->assertFalse(array_key_exists('JF_ID', $decoded[0]));
        $this->assertFalse(array_key_exists('JPPopulation', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL3Edition14', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL3Edition14Orig', $decoded[0]));
        $this->assertFalse(array_key_exists('WorldSpeakers', $decoded[0]));
    }

    public function testIndexRequestsShouldRefuseAccessWithoutAnAPIKey(): void
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

    public function testIndexRequestsShouldRefuseAccessWithoutAnActiveAPIKey(): void
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 0 WHERE `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array('api_key' => $this->APIKey),
            "index_non_active_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }

    public function testIndexRequestsShouldRefuseAccessWithSuspendedAPIKey(): void
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array('api_key' => $this->APIKey),
            "index_suspended_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }

    public function testIndexRequestsShouldRefuseAccessWithABadAPIKey(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            array('api_key' => 'BADKEY'),
            "index_bad_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }

    public function testIndexRequestsShouldReturnALanguageInJSON(): void
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

    public function testIndexRequestsShouldNotReturnRemovedFields(): void
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
        $this->assertFalse(array_key_exists('JF_ID', $decoded[0]));
        $this->assertFalse(array_key_exists('JPPopulation', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL3Edition14', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL3Edition14Orig', $decoded[0]));
        $this->assertFalse(array_key_exists('WorldSpeakers', $decoded[0]));
    }

    public function testIndexRequestsShouldReturnALimitOfLanguagesBasedOnTheLimitParameter(): void
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

    public function testIndexRequestsShouldReturnTheCorrectLanguageIds(): void
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

    public function testIndexRequestsShouldReturnErrorIfTheIdIsWrong(): void
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

    public function testIndexRequestsShouldReturnALanguagesWithNewTestaments(): void
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

    public function testIndexRequestsShouldReturnAnErrorIfProvidingTheWrongNewTestamentValue(): void
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

    public function testIndexRequestsShouldReturnLanguagesWithPortionsOfScriptures(): void
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

    public function testIndexRequestsShouldReturnAnErrorIfHasPortionsParmeterIsWrong(): void
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

    public function testIndexRequestsShouldReturnLanguagesWithCompleteScriptures(): void
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

    public function testIndexRequestsShouldReturnAnErrorIfHasCompleteBibleParameterIsWrong(): void
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

    public function testIndexRequestsShouldReturnErrorIfHasQuestionableParameterIsWrong(): void
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

    public function testIndexRequestsShouldReturnLanguagesWithAudioResources(): void
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
            $this->assertEquals('Y', $lang['HasAudioRecordings']);
        }
    }

    public function testIndexRequestsShouldReturnErrorIfHasAudioParameterIsWrong(): void
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

    public function testIndexRequestsShouldReturnLanguagesWithJesusFilm(): void
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
            $this->assertEquals('Y', $lang['HasJesusFilm']);
        }
    }

    public function testIndexRequestsShouldReturnErrorIfHasJesusFilmParameterIsWrong(): void
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

    public function testIndexRequestsShouldReturnLanguagesBasedOnCountries(): void
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

    public function testIndexRequestsShouldReturnLanguagesBasedOnNumberOfPrimaryReligions(): void
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

    public function testIndexRequestsShouldReturnErrorIfPrimaryReligionParameterIsWrong(): void
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

    public function testIndexRequestsShouldReturnLanguagesBasedOnJPScale(): void
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

    public function testIndexRequestsShouldReturnErrorIfJPScaleParameterIsWrong(): void
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

    public function testIndexRequestsShouldReturnLanguagesBasedOnLeastReached(): void
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

    public function testIndexRequestsShouldReturnErrorIfLeastReachedParameterIsWrong(): void
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

    public function testIndexRequestsShouldReturnLanguagesBasedOnNumberOfPercentAdherent(): void
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

    public function testIndexRequestsShouldReturnLanguagesBasedOnNumberOfPercentEvangelical(): void
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

    public function testIndexRequestsShouldReturnInDefaultSortedWay(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            ['api_key'  =>  $this->APIKey, 'limit'  =>  5],
            "should_return_language_in_correct_order_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        $sorted = $decodedResponse;
        usort($sorted, fn ($a, $b) => strcmp($a['Language'], $b['Language']));
        $this->assertEquals($decodedResponse, $sorted);
    }

    public function testIndexRequestsShouldReturnInRequestedOrder(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            [
                'api_key'  =>  $this->APIKey,
                'limit'  =>  5,
                'sort_field'  =>  'ROL3',
                'sort_direction'  =>  'DESC'
            ],
            "should_return_language_in_requested_order_index_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
        $decodedResponse = json_decode($response, true);
        $sorted = $decodedResponse;
        usort($sorted, fn ($a, $b) => strcmp($b['ROL3'], $a['ROL3']));
        $this->assertEquals($decodedResponse, $sorted);
    }

    public function testIndexRequestsShouldThrowErrorIfNonWhitelistedSortField(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            [
                'api_key'  =>  $this->APIKey,
                'limit'  =>  5,
                'sort_field'  =>  'ILLEGAL',
                'sort_direction'  =>  'DESC'
            ],
            "should_throw_error_wrong_sort_field_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(500, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertEquals('Internal Server Error', $decodedResponse['api']['error']['message']);
        $this->assertEquals('The provided value: ILLEGAL is not allowed.', $decodedResponse['api']['error']['details']);
    }

    public function testIndexRequestsShouldThrowErrorIfSortingDirectionIsIncorrect(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/languages.json",
            [
                'api_key'  =>  $this->APIKey,
                'limit'  =>  5,
                'sort_field'  =>  'ROG3',
                'sort_direction'  =>  'ILLEGAL'
            ],
            "should_throw_error_wrong_sort_direction_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(500, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertEquals('Internal Server Error', $decodedResponse['api']['error']['message']);
        $this->assertEquals("Invalid sort direction: ILLEGAL. Allowed values are 'ASC' or 'DESC'.", $decodedResponse['api']['error']['details']);
    }

}
