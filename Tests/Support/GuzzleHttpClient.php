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

namespace Tests\Support;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

/**
 * A wrapper class for Guzzle HTTP client to provide a similar interface to the old CachedRequest library
 *
 * @author Johnathan Pulos
 */
class GuzzleHttpClient
{
    /**
     * The Guzzle HTTP client
     *
     * @var Client
     */
    private $client;

    /**
     * The last HTTP response code
     *
     * @var int
     */
    public $responseCode = 0;

    /**
     * The last visited URL
     *
     * @var string
     */
    public $lastVisitedURL = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = new Client([
            'http_errors' => false, // Don't throw exceptions on 4xx/5xx responses
            'allow_redirects' => [
                'max' => 10,
                'strict' => false,
                'referer' => true,
                'track_redirects' => true
            ]
        ]);
    }

    /**
     * Perform a GET request
     *
     * @param string $url The URL to request
     * @param array $params Query parameters
     * @param string $cacheKey Unused - kept for compatibility with old interface
     * @return string The response body
     */
    public function get(string $url, array $params = [], string $cacheKey = ''): string
    {
        try {
            $options = [];
            if (!empty($params)) {
                $options['query'] = $params;
            }

            $response = $this->client->get($url, $options);
            $this->processResponse($response, $url, $params);

            return (string) $response->getBody();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $this->processResponse($response, $url, $params);
                return (string) $response->getBody();
            }
            throw $e;
        }
    }

    /**
     * Perform a POST request
     *
     * @param string $url The URL to request
     * @param array $data POST data
     * @param string $cacheKey Unused - kept for compatibility with old interface
     * @return string The response body
     */
    public function post(string $url, array $data = [], string $cacheKey = ''): string
    {
        try {
            $options = [
                'form_params' => $data
            ];

            $response = $this->client->post($url, $options);
            $this->processResponse($response, $url, []);

            return (string) $response->getBody();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $this->processResponse($response, $url, []);
                return (string) $response->getBody();
            }
            throw $e;
        }
    }

    /**
     * Process the response and extract necessary information
     *
     * @param ResponseInterface $response The HTTP response
     * @param string $url The original URL
     * @param array $params Query parameters
     * @return void
     */
    private function processResponse(ResponseInterface $response, string $url, array $params = []): void
    {
        $this->responseCode = $response->getStatusCode();

        // Check if there was a redirect
        if ($response->hasHeader('X-Guzzle-Redirect-History')) {
            $redirects = $response->getHeader('X-Guzzle-Redirect-History');
            $this->lastVisitedURL = end($redirects);
        } else {
            // Build the full URL with query parameters
            $this->lastVisitedURL = $url;
            if (!empty($params)) {
                $this->lastVisitedURL .= '?' . http_build_query($params);
            }
        }
    }

    /**
     * Clear cache - kept for compatibility with old interface
     * Does nothing in this implementation
     *
     * @return void
     */
    public function clearCache(): void
    {
        // No-op for Guzzle implementation
    }
}
