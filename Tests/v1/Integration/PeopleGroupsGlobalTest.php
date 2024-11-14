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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

declare(strict_types=1);

namespace Tests\v1\Integration;

use QueryGenerators\PeopleGroupGlobal;
use PHPToolbox\CachedRequest\CachedRequest;
use PHPUnit\Framework\TestCase;

class PeopleGroupsGlobalTest extends TestCase
{
    public $cachedRequest;
    private $db;
    private $APIKey = '';
    private $APIVersion;
    private $limit;
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
        $generator = new PeopleGroupGlobal([]);
        $this->limit = $generator->limit;
    }

    public function tearDown(): void
    {
        $this->cachedRequest->clearCache();
        deleteApiKey($this->APIKey);
    }

    public function testShowShouldRefuseAccessWithoutAnAPIKey(): void
    {
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global/10960.json",
            [],
            "show_refuse_no_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }

    public function testShowShouldRefuseAccessWithoutActiveAPIKey(): void
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 0 WHERE `api_key` = '$this->APIKey'");
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global/10960.json",
            ['api_key' => $this->APIKey],
            "show_inactive_key_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testShowShouldRefuseAccessWithSuspendedAPIKey(): void
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `api_key` = '$this->APIKey'");
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global/10960.json",
            ['api_key' => $this->APIKey],
            "show_suspended_key_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testShowShouldRefuseAccessWithABadAPIKey(): void
    {
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global/10960.json",
            ['api_key' => 'BOGUS-KEY'],
            "show_bogus_key_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testShowShouldReturnCorrectPeopleGroupAsJSON(): void
    {
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global/10960.json",
            ['api_key' => $this->APIKey],
            "show_format_success_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }

    public function testShowShouldReturnCorrectPeopleGroupAsXML(): void
    {
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global/10960.xml",
            ['api_key' => $this->APIKey],
            "show_format_success_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isXML($response));
    }

    public function testShowShouldReturnThePeopleGroup(): void
    {
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global/10960.json",
            ['api_key' => $this->APIKey],
            "show_people_group_success"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded));
        $this->assertEquals(10960, $decoded[0]['PeopleID3']);
        $this->assertEquals('Brao', $decoded[0]['PeopName']);
        $this->assertEquals(20, $decoded[0]['PeopleID1']);
        $this->assertEquals('Southeast Asian Peoples', $decoded[0]['AffinityBloc']);
        $this->assertEquals(239, $decoded[0]['PeopleID2']);
        $this->assertEquals('Mon-Khmer', $decoded[0]['PeopleCluster']);
        $this->assertEquals(101655, $decoded[0]['ROP3']);
        $this->assertEquals(301408, $decoded[0]['ROP25']);
        $this->assertEquals('Brao', $decoded[0]['ROP25Name']);
        $this->assertEquals(4, $decoded[0]['JPScalePGAC']);
        $this->assertEquals(39500, $decoded[0]['PopulationPGAC']);
        $this->assertNull($decoded[0]['LeastReachedPGAC']);
        $this->assertNull($decoded[0]['FrontierPGAC']);
        $this->assertEquals(3, $decoded[0]['CntPGIC']);
        $this->assertEquals(1, $decoded[0]['CntUPG']);
        $this->assertEquals(0, $decoded[0]['CntFPG']);
        $this->assertEquals('LA', $decoded[0]['ROG3Largest']);
        $this->assertEquals('Laos', $decoded[0]['CtryLargest']);
        $this->assertEquals('brb', $decoded[0]['ROL3PGAC']);
        $this->assertEquals('Brao', $decoded[0]['PrimaryLanguagePGAC']);
        $this->assertEquals(4, $decoded[0]['RLG3PGAC']);
        $this->assertEquals('Ethnic Religions', $decoded[0]['PrimaryReligionPGAC']);
        $this->assertEquals(3.651, $decoded[0]['PercentChristianPGAC']);
        $this->assertEquals(2.788, $decoded[0]['PercentEvangelicalPGAC']);
        $this->assertEquals('Partially Reached', $decoded[0]['JPScaleText']);
        $this->assertEquals('https://joshuaproject.net/assets/img/gauge/gauge-4.png', $decoded[0]['JPScaleImageURL']);
        /**
         * By default it should include the country list
         */
        $expected = [
            ['ROG3' => 'CB', 'Ctry' => 'Cambodia', 'Population' => 11000, 'JPScale' => 4],
            ['ROG3' => 'LA', 'Ctry' => 'Laos', 'Population' => 28000, 'JPScale' => 1],
            ['ROG3' => 'VM', 'Ctry' => 'Vietnam', 'Population' => 500, 'JPScale' => 2]
        ];
        $this->assertTrue(isset($decoded[0]['Countries']));
        $this->assertEquals($expected, $decoded[0]['Countries']);
    }

    public function testShowReturnsErrorIfPeopleGroupDoesNotExist(): void
    {
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global/99999999999999.json",
            ['api_key' => $this->APIKey],
            "show_people_group_error"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(404, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded));
        $this->assertEquals('Not Found', $decoded['api']['error']['message']);
        $this->assertEquals('The requested people group does not exist.', $decoded['api']['error']['details']);
    }

    public function testShowShouldAllowTurningOffCountryList(): void
    {
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global/10960.json",
            ['api_key' => $this->APIKey, 'include_country_list' => 'N'],
            "show_people_group_without_list_success"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded));
        $this->assertEquals(10960, $decoded[0]['PeopleID3']);
        $this->assertFalse(isset($decoded[0]['Countries']));
    }

    public function testIndexShouldRefuseAccessWithoutAnAPIKey(): void
    {
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global.json",
            [],
            "index_refuse_no_key_json"
        );
        $this->assertEquals(401, $this->cachedRequest->responseCode);
    }

    public function testIndexShouldRefuseAccessWithoutActiveAPIKey(): void
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 0 WHERE `api_key` = '$this->APIKey'");
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global.json",
            ['api_key' => $this->APIKey],
            "index_inactive_key_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testIndexShouldRefuseAccessWithSuspendedAPIKey(): void
    {
        $this->db->query("UPDATE `md_api_keys` SET status = 2 WHERE `api_key` = '$this->APIKey'");
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global.json",
            ['api_key' => $this->APIKey],
            "index_suspended_key_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testIndexShouldRefuseAccessWithABadAPIKey(): void
    {
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global.json",
            ['api_key' => 'BOGUS-KEY'],
            "index_bogus_key_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('The provided API key is invalid.', $decoded['api']['error']['details']);
    }

    public function testIndexShouldReturnPeopleGroupsAsJSON(): void
    {
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global.json",
            ['api_key' => $this->APIKey],
            "index_format_success_json"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isJSON($response));
    }

    public function testIndexShouldReturnPeopleGroupsAsXML(): void
    {
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global.xml",
            ['api_key' => $this->APIKey],
            "index_format_success_xml"
        );
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(isXML($response));
    }

    public function testIndexShouldReturnTheGroupsWithDefaultLimit(): void
    {
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global.json",
            ['api_key' => $this->APIKey],
            "index_people_groups_success"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded));
        $this->assertEquals($this->limit, count($decoded));
    }

    public function testIndexShouldNotReturnTheCountryListByDefault(): void
    {
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global.json",
            ['api_key' => $this->APIKey, 'limit' => 4],
            "index_pg_no_countries_success"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded));
        foreach ($decoded as $group) {
            $this->assertFalse(isset($group['Countries']));
        }
    }

    public function testIndexShouldReturnTheCountryListIfRequested(): void
    {
        $response = $this->cachedRequest->get(
            "$this->siteURL/$this->APIVersion/people_groups_global.json",
            ['api_key' => $this->APIKey, 'limit' => 4, 'include_country_list' => 'Y'],
            "index_pg_with_countries_success"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(200, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded));
        foreach ($decoded as $group) {
            $this->assertTrue(isset($group['Countries']));
            $this->assertGreaterThan(0, count($group['Countries']));
        }
    }
}
