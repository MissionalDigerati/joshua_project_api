<?php

/**
 * Joshua Project API - An API for accessing Joshua Project Data.
 *
 * GNU Public License 3.0
 * Copyright (C) 2013  Missional Digerati
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 */

declare(strict_types=1);

use Doctrine\DBAL\Exception as DBALException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Lists all the current API Keys
 *
 * GET /api_keys
 * Available Formats HTML
 *
 * @author Johnathan Pulos
 **/
$app->get(
    "/api_keys",
    function (Request $request, Response $response, $args = []): Response {
        $data = $request->getQueryParams();
        $query = "SELECT * FROM md_api_keys ORDER BY created DESC";
        try {
            $api_keys = $this->get('db')->fetchAllAssociative($query);
        } catch (DBALException $e) {
            error_log("DBALException in /api_keys: " . $e->getMessage());
            exit;
        }
        return $this->get('view')->render(
            $response,
            'APIKeys/index.html.php',
            [
                'api_keys' => $api_keys,
                'data' => $data
            ]
        );
    }
);
/**
 * Sets the suspended attribute on the API Key
 *
 * PUT /api_keys/:id
 * Available Formats HTML
 *
 * @author Johnathan Pulos
 **/
$app->put(
    "/api_keys/{id}",
    function (Request $request, Response $response, $args = []): Response {
        $id = $args['id'];
        $body = $request->getParsedBody();
        $state = $body['state'];
        if (!$state) {
            return $response
            ->withHeader('Location', "/api_keys?saving_error=true")
            ->withStatus(302);
        }
        $query = "UPDATE md_api_keys SET status = :state WHERE id = :id";
        try {
            $this->get('db')->executeStatement(
                $query,
                [
                    'id' => $id,
                    'state' => $state
                ]
            );
        } catch (DBALException $e) {
            error_log("DBALException in /api_keys/{id}: " . $e->getMessage());
            return $response
                ->withHeader('Location', "/api_keys?saving_error=true")
                ->withStatus(302);
        }
        if (intval($state) === 1) {
            $keyState = "activated or reinstated";
        } elseif (intval($state) === 2) {
            $keyState = "suspended";
        }
        return $response
        ->withHeader('Location', "/api_keys?saved=true&key_state=" . $keyState)
        ->withStatus(302);
    }
);
/**
 * Create an API key (We appended /new so it can bypass auth requirement)
 *
 * POST /api_keys/new
 * Available Formats HTML
 *
 * @author Johnathan Pulos
 */
$app->post(
    "/api_keys/new",
    function (Request $request, Response $response, $args = []): Response {
        $formData = $request->getParsedBody();
        $isLocal = ((array_key_exists('IS_LOCAL', $_ENV)) && ($_ENV['IS_LOCAL'] === 'true'));
        /**
         * Validate the recaptcha
         */
        $validator = $this->get('recaptchaValidator');
        if (
            (!$isLocal) &&
            (!$validator->isValid($formData['g-recaptcha-response'], 0.5))
        ) {
            unset($formData['g-recaptcha-response']);
            $redirectURL = generateRedirectURL("/", $formData, ['recaptcha_error']);
            return $response
                ->withHeader('Location', $redirectURL)
                ->withStatus(302);
        }
        unset($formData['g-recaptcha-response']);
        /**
         * Validate the form data
         */
        $required = ["name", "email", "usage", "terms_of_use"];
        if (!array_key_exists('usage', $formData)) {
            $formData['usage'] = [];
        }
        $formData["usage"] = array_map('strtolower', $formData["usage"]);
        $invalid = validatePresenceOf($required, $formData);
        /**
         * Check required fields for provided usage
         */
        $websiteUrl = returnPresentIfKeyExistsOrDefault($formData, 'website_url', '');
        $appleStoreUrl = returnPresentIfKeyExistsOrDefault($formData, 'apple_app_store', '');
        $googlePlayUrl = returnPresentIfKeyExistsOrDefault($formData, 'google_play_store', '');
        $otherPurpose = returnPresentIfKeyExistsOrDefault($formData, 'other_purpose', '');
        if ((in_array('other', $formData['usage'])) && (!$otherPurpose)) {
            $invalid[] = 'other_purpose';
        }
        $redirectURL = generateRedirectURL("/", $formData, $invalid);
        if (!empty($invalid)) {
            return $response
                ->withHeader('Location', $redirectURL)
                ->withStatus(302);
        }
        if ($otherPurpose) {
            array_push($formData['usage'], strtolower($otherPurpose));
        }
        /**
         * Create the API key
         */
        $newAPIKey = generateRandomKey(12);
        $authorizeToken = generateRandomKey(12);
        $usage = join(",", $formData['usage']);
        $apiKeyValues = [
            'name' => $formData['name'],
            'email' => $formData['email'],
            'api_usage' => $usage,
            'api_key' => $newAPIKey,
            'authorize_token' => $authorizeToken,
            'status' => 0,
            'website_url' => $websiteUrl,
            'google_play_store' => $googlePlayUrl,
            'apple_app_store' => $appleStoreUrl,
        ];
        $query = "INSERT INTO md_api_keys (name, email, api_usage, api_key, authorize_token, " .
        "status, website_url, google_play_store, apple_app_store, created) " .
        "VALUES (:name, :email, :api_usage, :api_key, :authorize_token, " .
        ":status, :website_url, :google_play_store, :apple_app_store, NOW())";
        try {
            $this->get('db')->executeStatement($query, $apiKeyValues);
        } catch (DBALException $e) {
            error_log("DBALException in /api_keys/new: " . $e->getMessage());
            return $response
                ->withHeader('Location', "/?saving_error=true")
                ->withStatus(302);
        }
        /**
         * Send the email with the authorization url
         *
         * @author Johnathan Pulos
         */
        $siteURL = getSiteURL();
        $authorizeUrl = $siteURL . "/get_my_api_key?authorize_token=" . $authorizeToken;
        $this->get('mailer')->sendAuthorizeToken($formData['email'], $authorizeUrl);
        $redirectURL = generateRedirectURL("/", ['api_key' => 'true'], []);
        return $response
            ->withHeader('Location', $redirectURL)
            ->withStatus(302);
    }
);
