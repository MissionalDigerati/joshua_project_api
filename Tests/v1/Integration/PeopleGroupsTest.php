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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */
namespace Tests\v1\Integration;

use PHPToolbox\CachedRequest\CachedRequest;
use PHPUnit\Framework\TestCase;

class PeopleGroupsTest extends TestCase
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
        $this->APIKey = createApiKey([]);
    }

    public function tearDown(): void
    {
        $this->cachedRequest->clearCache();
        deleteApiKey($this->APIKey);
    }

    public function testShouldRefuseAccessWithoutAnAPIKey(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array(),
            "up_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }

    public function testShouldRefuseAccessWithoutActiveAPIKey(): void
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 0 WHERE `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey),
            "versioning_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testShouldRefuseAccessWithSuspendedAPIKey(): void
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `api_key` = '" . $this->APIKey . "'");
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey),
            "versioning_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testShouldRefuseAccessWithABadAPIKey(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json?api_key=BADKEY",
            array(),
            "up_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testShouldGetDailyUnreachedInJSON(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey),
            "up_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }

    public function testShouldGetDailyUnreachedInXML(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.xml",
            array('api_key' => $this->APIKey),
            "up_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isXML($response));
    }

    public function testShouldGetDailyUnreachedWithSetMonth(): void
    {
        $expectedMonth = '5';
        $expectedDay = Date('j');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey, 'month' => $expectedMonth, 'day' => $expectedDay),
            "up_month"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedMonth, $decodedResponse[0]['LRofTheDayMonth']);
        $this->assertEquals($expectedDay, $decodedResponse[0]['LRofTheDayDay']);
    }

    public function testShouldGetDailyUnreachedWithSetDay(): void
    {
        $expectedMonth = Date('n');
        $expectedDay = '23';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey, 'day' => $expectedDay),
            "up_day"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedMonth, $decodedResponse[0]['LRofTheDayMonth']);
        $this->assertEquals($expectedDay, $decodedResponse[0]['LRofTheDayDay']);
    }

    public function testShouldGetDailyUnreachedWithSetDayAndMonth(): void
    {
        $expectedMonth = '3';
        $expectedDay = '21';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey, 'day' => $expectedDay, 'month' => $expectedMonth),
            "up_day_and_month"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($expectedMonth, $decodedResponse[0]['LRofTheDayMonth']);
        $this->assertEquals($expectedDay, $decodedResponse[0]['LRofTheDayDay']);
    }

    public function testShowRequestsShouldGive404IfNoValidId(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/a.json",
            array('api_key' => $this->APIKey),
            "wrong_id_request"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Bad Request', $decoded['api']['error']['message']);
        $this->assertEquals('You provided an invalid PeopleID3.', $decoded['api']['error']['details']);
    }

    public function testShowRequestsShouldGetCorrectPeopleGroup(): void
    {
        $expectedID = "12662";
        $expectedCountry = "CB";
        $expectedName = "Khmer";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/12662.json",
            array('api_key' => $this->APIKey, 'country' => 'CB'),
            "show_in_country_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertFalse(empty($decodedResponse));
        $this->assertEquals($expectedID, $decodedResponse[0]['PeopleID3']);
        $this->assertEquals($expectedCountry, $decodedResponse[0]['ROG3']);
        $this->assertEquals($expectedName, $decodedResponse[0]['PeopNameInCountry']);
    }

    public function testShowRequestsShouldGetPeopleGroupsWithResources(): void
    {
        $expectedId = "10572";
        $expectedCountry = "BA";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/" . $expectedId . ".json",
            array('api_key' => $this->APIKey, 'country' => $expectedCountry),
            "show_in_country_gets_resources_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertTrue(isset($decodedResponse[0]['Resources']));
        $this->assertTrue(is_array($decodedResponse[0]['Resources']));
    }

    public function testShowRequestsShouldGetPeopleGroupsWithResourcesWhenIdsOnlyProvided(): void
    {
        $expectedID = "10572";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/" . $expectedID . ".json",
            array('api_key' => $this->APIKey),
            "show_get_proper_resources_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $peopleGroupData) {
            $this->assertTrue(isset($peopleGroupData['Resources']));
            $this->assertTrue(is_array($peopleGroupData['Resources']));
        }
    }

    public function testShowRequestsShouldGetCorrectPeopleGroupsWhenIdsOnlyProvided(): void
    {
        $expectedID = "12662";
        $expectedPeopleGroups = 13;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/" . $expectedID . ".json",
            array('api_key' => $this->APIKey),
            "show_in_country_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertEquals($expectedID, $decodedResponse[0]['PeopleID3']);
        $this->assertEquals($expectedPeopleGroups, count($decodedResponse));
    }

    public function testShowRequestsShould404ErrorIfIdDoesNotExist(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/2292828272736363511516.json",
            array('api_key' => $this->APIKey),
            "show_in_country_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(404, $this->cachedRequest->responseCode);
    }

    public function testIndexRequestsShouldReturn250ByDefault(): void
    {
        $expectedNumberOfResults = 250;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey),
            "all_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertEquals($expectedNumberOfResults, count($decodedResponse));
    }

    public function testIndexRequestsShouldReturnResourcesForAllPeopleGroups(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey),
            "resources_for_all_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        foreach ($decodedResponse as $peopleGroupData) {
            $this->assertTrue(isset($peopleGroupData['Resources']));
            $this->assertTrue(is_array($peopleGroupData['Resources']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPeopleId1(): void
    {
        $expectedPeopleIds = array(17, 23);
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'people_id1' => join("|", $expectedPeopleIds)),
            "filter_by_people_id_1_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['PeopleID1']), $expectedPeopleIds));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByROP1(): void
    {
        $expectedROP = array('A014', 'A010');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'rop1' => join("|", $expectedROP)),
            "filter_by_rop_1_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROP1'], $expectedROP));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByROP1AndPeopleID1(): void
    {
        $expectedROP = 'A014';
        $expectedPeopleID = 23;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'rop1' => $expectedROP, 'people_id1' => $expectedPeopleID),
            "filter_by_rop_1_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertEquals($expectedROP, $peopleGroup['ROP1']);
            $this->assertEquals($expectedPeopleID, intval($peopleGroup['PeopleID1']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPeopleId2(): void
    {
        $expectedPeopleIds = array(117, 115);
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'people_id2' => join("|", $expectedPeopleIds)),
            "filter_by_people_id_2_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['PeopleID2']), $expectedPeopleIds));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByROP2(): void
    {
        $expectedROP = array('C0013', 'C0067');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'rop2' => join("|", $expectedROP)),
            "filter_by_rop_2_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROP2'], $expectedROP));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPeopleId3(): void
    {
        $expectedPeopleIds = array(11722, 19204);
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'people_id3' => join("|", $expectedPeopleIds)),
            "filter_by_people_id_3_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['PeopleID3']), $expectedPeopleIds));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByROP3(): void
    {
        $expectedROP = array(115485, 115409);
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'rop3' => join("|", $expectedROP)),
            "filter_by_rop_3_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['ROP3']), $expectedROP));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByContinents(): void
    {
        $expectedCountries = array('AFR', 'NAR');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'continents' => join("|", $expectedCountries)),
            "filter_by_continents_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROG2'], $expectedCountries));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByRegions(): void
    {
        $expectedRegions = array(3 => 'asia, northeast', 4 => 'asia, south');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'regions' => join("|", array_keys($expectedRegions))),
            "filter_by_regions_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(intval($peopleGroup['RegionCode']), array_keys($expectedRegions)));
            $this->assertTrue(in_array(strtolower($peopleGroup['RegionName']), array_values($expectedRegions)));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByCountries(): void
    {
        $expectedCountries = array('AN', 'BG');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'countries' => join("|", $expectedCountries)),
            "filter_by_countries_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array($peopleGroup['ROG3'], $expectedCountries));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredBy1040Window(): void
    {
        $expected1040Window = 'Y';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'window1040' => $expected1040Window),
            "filter_by_1040_window_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertEquals($expected1040Window, $peopleGroup['Window1040']);
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByLanguages(): void
    {
        $expectedLanguages = array('AKA', 'ALE');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'languages' => join("|", $expectedLanguages)),
            "filter_by_languages_aka_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(strtoupper($peopleGroup['ROL3']), $expectedLanguages));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByAMinAndMaxPopulation(): void
    {
        $expectedMin = 10000;
        $expectedMax = 20000;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'population' => $expectedMin."-".$expectedMax),
            "filter_by_pop_in_range_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedMax, intval($peopleGroup['Population']));
            $this->assertGreaterThanOrEqual($expectedMin, intval($peopleGroup['Population']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByASetPopulation(): void
    {
        $expectedPop = 156000;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'population' => $expectedPop),
            "filter_by_set_pop_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertEquals($expectedPop, intval($peopleGroup['Population']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPrimaryReligions(): void
    {
        $expectedReligions = array(2 => 'buddhism');
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'primary_religions' => join('|', array_keys($expectedReligions))),
            "filter_by_primary_religions_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(strtolower($peopleGroup['PrimaryReligion']), array_values($expectedReligions)));
            $this->assertTrue(in_array($peopleGroup['RLG3'], array_keys($expectedReligions)));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfAdherents(): void
    {
        $expectedPercentMin = 1.0;
        $expectedPercentMax = 6.9;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_adherent' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_adherents_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PercentAdherents']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PercentAdherents']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfEvangelicals(): void
    {
        $expectedPercentMin = 10.0;
        $expectedPercentMax = 20.8;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_evangelical' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_evangelicals_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PercentEvangelical']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PercentEvangelical']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfBuddhists(): void
    {
        $expectedPercentMin = 30.0;
        $expectedPercentMax = 40.9;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_buddhist' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_buddhist_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCBuddhism']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCBuddhism']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfEthnicReligions(): void
    {
        $expectedPercentMin = 1.0;
        $expectedPercentMax = 3.9;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_ethnic_religion' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_ethnic_religions_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCEthnicReligions']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCEthnicReligions']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfHindus(): void
    {
        $expectedPercentMin = 1.0;
        $expectedPercentMax = 30.2;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_hindu' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_hindus_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCHinduism']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCHinduism']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfIslam(): void
    {
        $expectedPercentMin = 30.12;
        $expectedPercentMax = 40.3;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_islam' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_islam_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCIslam']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCIslam']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfNonReligious(): void
    {
        $expectedPercentMin = 40.12;
        $expectedPercentMax = 55.3;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_non_religious' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_non_religious_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCNonReligious']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCNonReligious']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfOtherReligions(): void
    {
        $expectedPercentMin = 3.2;
        $expectedPercentMax = 12.6;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_other_religion' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_other_religions_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCOtherSmall']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCOtherSmall']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPercentageOfUnknownReligions(): void
    {
        $expectedPercentMin = 3.22;
        $expectedPercentMax = 35.67;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_unknown' => $expectedPercentMin . "-" . $expectedPercentMax),
            "filter_by_percent_unknown_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertLessThanOrEqual($expectedPercentMax, floatval($peopleGroup['PCUnknown']));
            $this->assertGreaterThanOrEqual($expectedPercentMin, floatval($peopleGroup['PCUnknown']));
        }
    }

    public function testIndexRequestsShouldReturnUnsupportedPercentageOfAnglicans(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_anglican' => "8.7-40.1"),
            "filter_by_percent_anglican_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse['api']));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertFalse(empty($decodedResponse['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: pc_anglican',
            $decodedResponse['api']['error']['details']
        );
    }

    public function testIndexRequestsShouldReturnUnsupportedPercentageOfIndependents(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_independent' => "8.7-40.1"),
            "filter_by_percent_independent_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse['api']));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertFalse(empty($decodedResponse['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: pc_independent',
            $decodedResponse['api']['error']['details']
        );
    }

    public function testIndexRequestsShouldReturnUnsupportedPercentageOfProtestants(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_protestant' => "55.5-87.7"),
            "filter_by_percent_protestant_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse['api']));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertFalse(empty($decodedResponse['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: pc_protestant',
            $decodedResponse['api']['error']['details']
        );
    }

    public function testIndexRequestsShouldReturnUnsupportedPercentageOfOrthodox(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_orthodox' => "31.4-56.7"),
            "filter_by_percent_orthodox_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse['api']));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertFalse(empty($decodedResponse['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: pc_orthodox',
            $decodedResponse['api']['error']['details']
        );
    }

    public function testIndexRequestsShouldReturnUnsupportedPercentageOfRomanCatholic(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_rcatholic' => "2.34-56.7"),
            "filter_by_percent_orthodox_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse['api']));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertFalse(empty($decodedResponse['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: pc_rcatholic',
            $decodedResponse['api']['error']['details']
        );
    }

    public function testIndexRequestsShouldReturnUnsupportedPercentageOfOtherChristian(): void
    {
        $expectedPercentMin = 1.1;
        $expectedPercentMax = 27.2;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'pc_other_christian' => "1.1-27.2"),
            "filter_by_percent_other_christians_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse['api']));
        $this->assertEquals('error', $decodedResponse['api']['status']);
        $this->assertFalse(empty($decodedResponse['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: pc_other_christian',
            $decodedResponse['api']['error']['details']
        );
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByJPScale(): void
    {
        $expectedJPScales = "1|2|3";
        $expectedJPScalesArray = array(1, 2, 3);
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'jpscale' => $expectedJPScales),
            "filter_by_jp_scale_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertTrue(in_array(floatval($peopleGroup['JPScale']), $expectedJPScalesArray));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByIndigenousStatus(): void
    {
        $expectedIndigenousStatus = 'y';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'indigenous' => $expectedIndigenousStatus),
            "filter_by_indigenous_status_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertEquals($expectedIndigenousStatus, strtolower($peopleGroup['IndigenousCode']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByLeastReached(): void
    {
        $expectedLeastReachedStatus = 'y';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'least_reached' => $expectedLeastReachedStatus),
            "filter_by_least_reached_status_on_index_json"
        );
        $decodedResponse = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decodedResponse));
        foreach ($decodedResponse as $peopleGroup) {
            $this->assertEquals($expectedLeastReachedStatus, strtolower($peopleGroup['LeastReached']));
        }
    }

    public function testIndexRequestsShouldReturnUnsupportedUnengaged(): void
    {
        $expectedUnengagedStatus = 'y';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'unengaged' => $expectedUnengagedStatus),
            "filter_by_unengaged_status_on_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(400, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded['api']));
        $this->assertEquals('error', $decoded['api']['status']);
        $this->assertFalse(empty($decoded['api']['error']));
        $this->assertEquals(
            'Sorry, these parameters are no longer supported: unengaged',
            $decoded['api']['error']['details']
        );
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByFrontier(): void
    {
        $expectedFrontier = "N";
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'is_frontier' => $expectedFrontier),
            "filter_by_is_frontier_on_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $peopleGroup) {
            $this->assertEquals($expectedFrontier, strtoupper($peopleGroup['Frontier']));
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPopulationPGACInRange(): void
    {
        $min = 120000;
        $max = 130000;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'population_pgac' => $min . '-' . $max),
            "filter_by_population_pgac_on_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $peopleGroup) {
            $this->assertGreaterThanOrEqual($min, $peopleGroup['PopulationPGAC']);
            $this->assertLessThanOrEqual($max, $peopleGroup['PopulationPGAC']);
        }
    }

    public function testIndexRequestsShouldReturnPeopleGroupsFilteredByPopulationPGACAtValue(): void
    {
        $value = 120000;
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'population_pgac' => $value),
            "filter_by_population_pgac_at_value_on_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $peopleGroup) {
            $this->assertEquals($value, $peopleGroup['PopulationPGAC']);
        }
    }

    public function testIndexRequestShouldReturnPeopleGroupsFilteredByBibleStatus(): void
    {
        $expected = [0, 5];
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey, 'bible_status' => join('|', $expected)),
            "filter_by_bible_status_on_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $peopleGroup) {
            $this->assertTrue(in_array((int) $peopleGroup["BibleStatus"], $expected));
        }
    }

    public function testDailyUnreachedShouldNotProvideRemovedFields(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey),
            "unreached_no_old_fields"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertFalse(empty($decoded));
        $this->assertFalse(array_key_exists('Top10Ranking', $decoded[0]));
        $this->assertFalse(array_key_exists('RankOverall', $decoded[0]));
        $this->assertFalse(array_key_exists('RankProgress', $decoded[0]));
        $this->assertFalse(array_key_exists('RankPopulation', $decoded[0]));
        $this->assertFalse(array_key_exists('RankLocation', $decoded[0]));
        $this->assertFalse(array_key_exists('RankMinistryTools', $decoded[0]));
        $this->assertFalse(array_key_exists('UNMap', $decoded[0]));
        $this->assertFalse(array_key_exists('LeastReachedBasis', $decoded[0]));
        $this->assertFalse(array_key_exists('GospelRadio', $decoded[0]));
        $this->assertFalse(array_key_exists('Unengaged', $decoded[0]));
        $this->assertFalse(array_key_exists('EthnolinguisticMap', $decoded[0]));
        $this->assertFalse(array_key_exists('MapID', $decoded[0]));
        $this->assertFalse(array_key_exists('PCDblyProfessing', $decoded[0]));
        $this->assertFalse(array_key_exists('PhotoHeight', $decoded[0]));
        $this->assertFalse(array_key_exists('PhotoWidth', $decoded[0]));
        $this->assertFalse(array_key_exists('PopulationPercentUN', $decoded[0]));
        $this->assertFalse(array_key_exists('RaceCode', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL3OfficialLanguage', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL4', $decoded[0]));
    }

    public function testShowRequestsShouldNotProvideRemovedFields(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/12662.json",
            array('api_key' => $this->APIKey),
            "show_old_fields_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertFalse(empty($decoded));
        $this->assertFalse(array_key_exists('Top10Ranking', $decoded[0]));
        $this->assertFalse(array_key_exists('RankOverall', $decoded[0]));
        $this->assertFalse(array_key_exists('RankProgress', $decoded[0]));
        $this->assertFalse(array_key_exists('RankPopulation', $decoded[0]));
        $this->assertFalse(array_key_exists('RankLocation', $decoded[0]));
        $this->assertFalse(array_key_exists('RankMinistryTools', $decoded[0]));
        $this->assertFalse(array_key_exists('UNMap', $decoded[0]));
        $this->assertFalse(array_key_exists('LeastReachedBasis', $decoded[0]));
        $this->assertFalse(array_key_exists('GospelRadio', $decoded[0]));
        $this->assertFalse(array_key_exists('Unengaged', $decoded[0]));
        $this->assertFalse(array_key_exists('EthnolinguisticMap', $decoded[0]));
        $this->assertFalse(array_key_exists('MapID', $decoded[0]));
        $this->assertFalse(array_key_exists('PCDblyProfessing', $decoded[0]));
        $this->assertFalse(array_key_exists('PhotoHeight', $decoded[0]));
        $this->assertFalse(array_key_exists('PhotoWidth', $decoded[0]));
        $this->assertFalse(array_key_exists('PopulationPercentUN', $decoded[0]));
        $this->assertFalse(array_key_exists('RaceCode', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL3OfficialLanguage', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL4', $decoded[0]));
    }

    public function testIndexRequestsShouldNotProvideRemovedFields(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey),
            "index_old_fields_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        $this->assertFalse(array_key_exists('Top10Ranking', $decoded[0]));
        $this->assertFalse(array_key_exists('RankOverall', $decoded[0]));
        $this->assertFalse(array_key_exists('RankPopulation', $decoded[0]));
        $this->assertFalse(array_key_exists('RankProgress', $decoded[0]));
        $this->assertFalse(array_key_exists('RankLocation', $decoded[0]));
        $this->assertFalse(array_key_exists('RankMinistryTools', $decoded[0]));
        $this->assertFalse(array_key_exists('UNMap', $decoded[0]));
        $this->assertFalse(array_key_exists('LeastReachedBasis', $decoded[0]));
        $this->assertFalse(array_key_exists('GospelRadio', $decoded[0]));
        $this->assertFalse(array_key_exists('Unengaged', $decoded[0]));
        $this->assertFalse(array_key_exists('EthnolinguisticMap', $decoded[0]));
        $this->assertFalse(array_key_exists('MapID', $decoded[0]));
        $this->assertFalse(array_key_exists('PCDblyProfessing', $decoded[0]));
        $this->assertFalse(array_key_exists('PhotoHeight', $decoded[0]));
        $this->assertFalse(array_key_exists('PhotoWidth', $decoded[0]));
        $this->assertFalse(array_key_exists('PopulationPercentUN', $decoded[0]));
        $this->assertFalse(array_key_exists('RaceCode', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL3OfficialLanguage', $decoded[0]));
        $this->assertFalse(array_key_exists('ROL4', $decoded[0]));
    }

    public function testIndexShouldFilterByHasJesusFilm(): void
    {
        $expected = 'N';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            ['api_key' => $this->APIKey, 'has_jesus_film' => $expected],
            "filter_by_has_jesus_film_on_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $peopleGroup) {
            $this->assertEquals($expected, $peopleGroup['HasJesusFilm']);
        }
    }

    public function testIndexShouldFilterByHasAudioRecordings(): void
    {
        $expected = 'N';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            ['api_key' => $this->APIKey, 'has_audio' => $expected],
            "filter_by_has_audio_recordings_on_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $peopleGroup) {
            $this->assertEquals($expected, $peopleGroup['HasAudioRecordings']);
        }
    }

    public function testIndexShouldAddProfileTextByDefault(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            ['api_key' => $this->APIKey, 'limit' => 20],
            "index_default_profile_text_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $peopleGroup) {
            $this->assertTrue(array_key_exists('Summary', $peopleGroup));
            $this->assertTrue(array_key_exists('Obstacles', $peopleGroup));
            $this->assertTrue(array_key_exists('HowReach', $peopleGroup));
            $this->assertTrue(array_key_exists('PrayForChurch', $peopleGroup));
            $this->assertTrue(array_key_exists('PrayForPG', $peopleGroup));
        }
    }

    public function testIndexShouldNotAddProfileTextWhenRequested(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            ['api_key' => $this->APIKey, 'limit' => 20, 'include_profile_text' => 'N'],
            "index_no_profile_text_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $peopleGroup) { 
            $this->assertFalse(array_key_exists('Summary', $peopleGroup));
            $this->assertFalse(array_key_exists('Obstacles', $peopleGroup));
            $this->assertFalse(array_key_exists('HowReach', $peopleGroup));
            $this->assertFalse(array_key_exists('PrayForChurch', $peopleGroup));
            $this->assertFalse(array_key_exists('PrayForPG', $peopleGroup));
        }
    }

    public function testIndexShouldAddResourcesByDefault(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            ['api_key' => $this->APIKey, 'limit' => 20],
            "index_default_resources_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $peopleGroup) {
            $this->assertTrue(array_key_exists('Resources', $peopleGroup));
            $this->assertTrue(is_array($peopleGroup['Resources']));
        }
    }

    public function testIndexShouldNotAddResourcesWhenRequested(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            ['api_key' => $this->APIKey, 'limit' => 20, 'include_resources' => 'N'],
            "index_no_resources_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $peopleGroup) {
            $this->assertFalse(array_key_exists('Resources', $peopleGroup));
        }
    }

    public function testDailyUnreachedShouldProvideNewFields(): void
    {
        $expectedPop = 265000;
        $expectedFrontier = 'Y';
        $expectedMapUrl = 'https://joshuaproject.net/assets/media/profiles/maps/m10252_gm.png';
        $expectedMapExpandedUrl = 'https://joshuaproject.net/assets/media/profiles/maps/m10252_gm.pdf';
        $expectedPhotoCCVersionText = 'CC BY-SA 4.0';
        $expectedPhotoCCVersionURL = 'https://creativecommons.org/licenses/by-sa/4.0/';
        $expectedMapCredits = 'People Group location: Joshua Project, Map geography' .
        ': ESRI / GMI. Map design: Joshua Project.';
        $expectedMapCreditsURL = '';
        $expectedMapCopyright = 'N';
        $expectedMapCCVersionText = '';
        $expectedMapCCVersionURL = '';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array('api_key' => $this->APIKey, 'month' => '05', 'day'    =>  '31'),
            "unreached_no_new_fields"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertFalse(empty($decoded));
        $this->assertTrue(array_key_exists('Frontier', $decoded[0]));
        $this->assertTrue(array_key_exists('PopulationPGAC', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapURL', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapExpandedURL', $decoded[0]));
        $this->assertTrue(array_key_exists('PhotoCCVersionText', $decoded[0]));
        $this->assertTrue(array_key_exists('PhotoCCVersionURL', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCredits', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCreditURL', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCopyright', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCCVersionText', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCCVersionURL', $decoded[0]));
        $this->assertEquals($expectedFrontier, $decoded[0]['Frontier']);
        $this->assertEquals($expectedPop, $decoded[0]['PopulationPGAC']);
        $this->assertEquals($expectedMapUrl, $decoded[0]['PeopleGroupMapURL']);
        $this->assertEquals($expectedMapExpandedUrl, $decoded[0]['PeopleGroupMapExpandedURL']);
        $this->assertEquals($expectedPhotoCCVersionText, $decoded[0]['PhotoCCVersionText']);
        $this->assertEquals($expectedPhotoCCVersionURL, $decoded[0]['PhotoCCVersionURL']);
        $this->assertEquals($expectedMapCredits, $decoded[0]['MapCredits']);
        $this->assertEquals($expectedMapCreditsURL, $decoded[0]['MapCreditURL']);
        $this->assertEquals($expectedMapCopyright, $decoded[0]['MapCopyright']);
        $this->assertEquals($expectedMapCCVersionText, $decoded[0]['MapCCVersionText']);
        $this->assertEquals($expectedMapCCVersionURL, $decoded[0]['MapCCVersionURL']);
    }

    public function testShowRequestsShouldProvideNewFields(): void
    {
        $expectedPop = 1948100;
        $expectedFrontier = 'Y';
        $expectedMapAddress = 'm00324_aj.png';
        $expectedMapUrl = 'https://joshuaproject.net/assets/media/profiles/maps/m00324_aj.png';
        $expectedMapExpandedUrl = 'https://joshuaproject.net/assets/media/profiles/maps/m00324_aj.pdf';
        $expectedPhotoCCVersionText = 'CC BY-NC-SA 2.0';
        $expectedPhotoCCVersionURL = 'https://creativecommons.org/licenses/by-nc-sa/2.0/';
        $expectedMapCredits = 'Temo Blumgardt - Wikimedia';
        $expectedMapCreditsURL = 'https://commons.wikimedia.org/wiki/File:Caucasus_ethnic.jpg';
        $expectedMapCopyright = 'N';
        $expectedMapCCVersionText = 'CC0 1.0';
        $expectedMapCCVersionURL = 'https://creativecommons.org/publicdomain/zero/1.0/';
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/11317.json",
            array('api_key' => $this->APIKey, 'country' =>  'AJ'),
            "show_new_fields_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertFalse(empty($decoded));
        $this->assertEquals(1, count($decoded));
        $this->assertTrue(array_key_exists('Frontier', $decoded[0]));
        $this->assertTrue(array_key_exists('MapAddress', $decoded[0]));
        $this->assertTrue(array_key_exists('PopulationPGAC', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapURL', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapExpandedURL', $decoded[0]));
        $this->assertTrue(array_key_exists('PhotoCCVersionText', $decoded[0]));
        $this->assertTrue(array_key_exists('PhotoCCVersionURL', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCredits', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCreditURL', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCopyright', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCCVersionText', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCCVersionURL', $decoded[0]));
        $this->assertEquals($expectedFrontier, $decoded[0]['Frontier']);
        $this->assertEquals($expectedPop, $decoded[0]['PopulationPGAC']);
        $this->assertEquals($expectedMapAddress, $decoded[0]['MapAddress']);
        $this->assertEquals($expectedMapUrl, $decoded[0]['PeopleGroupMapURL']);
        $this->assertEquals($expectedMapExpandedUrl, $decoded[0]['PeopleGroupMapExpandedURL']);
        $this->assertEquals($expectedPhotoCCVersionText, $decoded[0]['PhotoCCVersionText']);
        $this->assertEquals($expectedPhotoCCVersionURL, $decoded[0]['PhotoCCVersionURL']);
        $this->assertEquals($expectedMapCredits, $decoded[0]['MapCredits']);
        $this->assertEquals($expectedMapCreditsURL, $decoded[0]['MapCreditURL']);
        $this->assertEquals($expectedMapCopyright, $decoded[0]['MapCopyright']);
        $this->assertEquals($expectedMapCCVersionText, $decoded[0]['MapCCVersionText']);
        $this->assertEquals($expectedMapCCVersionURL, $decoded[0]['MapCCVersionURL']);
    }

    public function testIndexRequestsShouldProvideNewFields(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array('api_key' => $this->APIKey),
            "index_new_fields_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        $this->assertTrue(array_key_exists('Frontier', $decoded[0]));
        $this->assertTrue(array_key_exists('MapAddress', $decoded[0]));
        $this->assertTrue(array_key_exists('PopulationPGAC', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapURL', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapExpandedURL', $decoded[0]));
        $this->assertTrue(array_key_exists('PhotoCCVersionText', $decoded[0]));
        $this->assertTrue(array_key_exists('PhotoCCVersionURL', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCredits', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCreditURL', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCopyright', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCCVersionText', $decoded[0]));
        $this->assertTrue(array_key_exists('MapCCVersionURL', $decoded[0]));
    }

    public function testMapAddressShouldSendEmptyURL(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/12662.json",
            array('api_key' => $this->APIKey, 'country' =>  'US'),
            "show_map_address_send_empty_url_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertFalse(empty($decoded));
        $this->assertEquals(1, count($decoded));
        $this->assertTrue(array_key_exists('MapAddress', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapURL', $decoded[0]));
        $this->assertTrue(array_key_exists('PeopleGroupMapExpandedURL', $decoded[0]));
        $this->assertEquals('', $decoded[0]['MapAddress']);
        $this->assertEquals('', $decoded[0]['PeopleGroupMapURL']);
        $this->assertEquals('', $decoded[0]['PeopleGroupMapExpandedURL']);
    }

    public function testPeopleGroupPhotoURLShouldSendAnEmptyURLWhenPhotoAddressEmptyOrNull(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/10123.json",
            array('api_key' => $this->APIKey, 'country' =>  'PP'),
            "show_photo_url_send_empty_string_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals($this->cachedRequest->responseCode, 200);
        $this->assertFalse(empty($decoded));
        $this->assertEquals(1, count($decoded));
        $this->assertTrue(array_key_exists('PeopleGroupPhotoURL', $decoded[0]));
        $this->assertEquals('', $decoded[0]['PeopleGroupPhotoURL']);
    }

    public function testUnreachedShouldReplaceProfileTextWithASummary(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array(
                'api_key'   => $this->APIKey,
                'month'     =>  '04',
                'day'       =>  '05'
            ),
            "unreached_replace_profile_text_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        $this->assertFalse(array_key_exists('ProfileText', $decoded[0]));
        $this->assertTrue(array_key_exists('Summary', $decoded[0]));
        $this->assertTrue(str_contains($decoded[0]['Summary'], 'Mongols in Inner Mongolia survive bitter winters'));
    }

    public function testShowShouldReplaceProfileTextWithASummary(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/15642.json",
            array(
                'api_key'       => $this->APIKey,
                'country'       =>  'IS'
            ),
            "show_replace_profile_text_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        $this->assertFalse(array_key_exists('ProfileText', $decoded[0]));
        $this->assertTrue(array_key_exists('Summary', $decoded[0]));
        $this->assertTrue(str_contains($decoded[0]['Summary'], 'Though many Tunisian Jews remain in Tunisia'));
    }

    public function testIndexShouldReplaceProfileTextWithASummary(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array(
                'api_key'       => $this->APIKey,
                'limit'         =>  5
            ),
            "index_replace_profile_text_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $pg) {
            $this->assertFalse(array_key_exists('ProfileText', $pg));
            $this->assertTrue(array_key_exists('Summary', $pg));
        }
    }

    public function testUnreachedShouldProvidePrayerDetails(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/daily_unreached.json",
            array(
                'api_key'   => $this->APIKey,
                'month'     =>  '05',
                'day'       =>  '25'
            ),
            "unreached_with_prayer_details_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $pg) {
            $this->assertTrue(array_key_exists('Obstacles', $pg));
            $this->assertFalse(empty($pg['Obstacles']));
            $this->assertTrue(array_key_exists('HowReach', $pg));
            $this->assertFalse(empty($pg['HowReach']));
            $this->assertTrue(array_key_exists('PrayForChurch', $pg));
            $this->assertTrue(array_key_exists('PrayForPG', $pg));
            $this->assertFalse(empty($pg['PrayForPG']));
        }
    }

    public function testShowShouldProvidePrayerDetails(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups/16180.json",
            array(
                'api_key'       =>  $this->APIKey,
                'country'       =>  'IN'
            ),
            "show_provide_prayer_details_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $pg) {
            $this->assertTrue(array_key_exists('Obstacles', $pg));
            $this->assertFalse(empty($pg['Obstacles']));
            $this->assertTrue(array_key_exists('HowReach', $pg));
            $this->assertFalse(empty($pg['HowReach']));
            $this->assertTrue(array_key_exists('PrayForChurch', $pg));
            $this->assertTrue(array_key_exists('PrayForPG', $pg));
            $this->assertFalse(empty($pg['PrayForPG']));
        }
    }

    public function testIndexShouldProvidePrayerDetails(): void
    {
        $response = $this->cachedRequest->get(
            $this->siteURL . "/" . $this->APIVersion . "/people_groups.json",
            array(
                'api_key'       => $this->APIKey,
                'limit'         =>  5
            ),
            "index_provide_prayer-details_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertFalse(empty($decoded));
        foreach ($decoded as $pg) {
            $this->assertTrue(array_key_exists('Obstacles', $pg));
            $this->assertTrue(array_key_exists('HowReach', $pg));
            $this->assertTrue(array_key_exists('PrayForChurch', $pg));
            $this->assertTrue(array_key_exists('PrayForPG', $pg));
        }
    }

}
