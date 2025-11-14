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

declare(strict_types=1);

namespace Utilities;

use GuzzleHttp\Client;

/**
 * A class for checking the validaty of a Recaptcha 2 token
 *
 * REQUIRES: guzzle
 */
class RecaptchaValidator
{
    /**
     * @param  string $apiKey Your recaptcha api key
     */
    protected string $apiKey;
    /**
     * The HTTP client
     *
     * @var Client
     */
    protected Client $httpClient;
    /**
     * @param string $project Your recaptcha project
     */
    protected string $project;
    /**
     * @param string $siteKey Your recaptcha site key
     */
    protected string $siteKey;
    /**
     * Build the class
     *
     * @param string $apiKey Your recaptcha api key
     * @param string $project Your recaptcha project
     * @param string $siteKey Your recaptcha site key
     *
     * @throws \InvalidArgumentException If either key is missing
     */
    public function __construct(
        string $apiKey,
        Client $client,
        string $project,
        string $siteKey
    ) {
        if (empty($apiKey)) {
            throw new \InvalidArgumentException('Recaptcha API key is required');
        }
        if (empty($project)) {
            throw new \InvalidArgumentException('Recaptcha project is required');
        }
        if (empty($siteKey)) {
            throw new \InvalidArgumentException('Recaptcha site key is required');
        }
        $this->apiKey = $apiKey;
        $this->httpClient = $client;
        $this->project = $project;
        $this->siteKey = $siteKey;
    }

    /**
     * Check if we have a valid token
     *
     * @param  string $token  The provided token
     * @param  float  $score  The minimum score to be valid
     * @return bool           is valid?
     */
    public function isValid(string $token, float $minimumScore = 0.5): bool
    {
        $data = [
            'event' => [
                'expectedAction' => 'verify',
                'siteKey'   =>  $this->siteKey,
                'token'     =>  $token
            ],
        ];
        $client = new Client([
            "base_uri" => "https://recaptchaenterprise.googleapis.com"
        ]);
        $response = $client->post(
            "/v1/projects/{$this->project}/assessments?key={$this->apiKey}",
            [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                ]
            ]
        );
        $results = json_decode($response->getBody()->getContents(), true);
        if (isset($results['tokenProperties']['valid']) && $results['tokenProperties']['valid'] == 1) {
            $riskScore = $results['riskAnalysis']['score'] ?? 0;
            return $riskScore >= $minimumScore;
        }

        return false;
    }
}
