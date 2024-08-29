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
use PHPToolbox\PDODatabase\PDODatabaseConnect;
use PHPUnit\Framework\TestCase;

class TotalsTest extends TestCase
{
    private $cachedRequest;
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

    public function testIndexShouldRefuseAccessWithoutAnAPIKey(): void
    {
        $response = $this->cachedRequest->get(
            "{$this->siteURL}/{$this->APIVersion}/totals.json",
            [],
            "totals_index_json"
        );
        $decoded = json_decode($response, true);
        $this->assertEquals(401, $this->cachedRequest->responseCode);
        $this->assertTrue(!empty($decoded['api']));
        $this->assertTrue(!empty($decoded['api']['error']));
        $this->assertEquals('Unauthorized', $decoded['api']['error']['message']);
        $this->assertEquals('You are missing your API key.', $decoded['api']['error']['details']);
    }
}
