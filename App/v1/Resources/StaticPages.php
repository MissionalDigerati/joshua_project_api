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

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Get the home page
 *
 * GET /
 * Available Formats HTML
 *
 * @author Johnathan Pulos
 */
$app->get(
    "/",
    function (Request $request, Response $response): Response {
        $data = $request->getQueryParams();
        $errors = [];
        if ((isset($data['required_fields'])) && ($data['required_fields'] !== "")) {
            $errors = explode("|", $data['required_fields']);
        }
        return $this->get('view')->render(
            $response,
            'StaticPages/home.html.php',
            ['data' => $data, 'errors' => $errors]
        );
    }
);
/**
 * Get the getting_started tutorial page
 *
 * GET /getting_started
 * Available Formats HTML
 *
 * @author Johnathan Pulos
 */
$app->get(
    "/getting_started",
    function (Request $request, Response $response): Response {
        return $this->get('view')->render(
            $response,
            'StaticPages/getting_started.html.php',
            ['siteURL' => getSiteURL()]
        );
    }
);
/**
 * Retrieve your API Key.  Triggered by a link in an email.  Requires md_api_keys.authorization token to access
 *
 * GET /get_my_api_key?authorize_token=[AUTHORIZATION TOKEN]
 *
 * @author Johnathan Pulos
 */
$app->get(
    "/get_my_api_key",
    function (Request $request, Response $response): Response {
        $APIKey = "";
        $message = "";
        $error = "";
        $params = $request->getQueryParams();
        if ((!array_key_exists('authorize_token', $params)) || ($params['authorize_token'] === '')) {
            $error = "Unable to locate your API key.";
        } else {
            try {
                $statement = $this
                    ->get('db')
                    ->prepare(
                        "SELECT * FROM `md_api_keys` WHERE authorize_token = :authorize_token LIMIT 1"
                    );
                $statement->execute(['authorize_token' => $params['authorize_token']]);
                $data = $statement->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $error = "Unable to locate your API key.";
            }
        }
        if ($error == '') {
            try {
                switch ($data['status']) {
                    case 0:
                        $status = 1;
                        $message = "Your API Key has been activated.";
                        $APIKey = $data['api_key'];
                        break;
                    case 1:
                        $status = 1;
                        $message = "Your API Key was already activated.";
                        $APIKey = $data['api_key'];
                        break;
                    case 2:
                        $status = 2;
                        $error = "Your API Key was suspended!";
                        break;
                }
                $statement = $this->get('db')->prepare(
                    "UPDATE `md_api_keys` SET status = :status WHERE id = :id"
                );
                $statement->execute(['id' => $data['id'], 'status' => $status]);
            } catch (Exception $e) {
                $error = "Unable to update your API Key.";
            }
        }

        return $this->get('view')->render(
            $response,
            'StaticPages/get_my_api_key.html.php',
            [
                'message' => $message,
                'error' => $error,
                'APIKey' => $APIKey
            ]
        );
    }
);
/**
 * Request all the Activation URLS for all my non-active API Keys
 *
 * GET /resend_activation_link
 *
 * @author Johnathan Pulos
 */
$app->get(
    "/resend_activation_links",
    function (Request $request, Response $response): Response {
        return $this->get('view')->render(
            $response,
            'StaticPages/resend_activation_links.html.php',
            []
        );
    }
);
/**
 * Send all the Activation URLS for all my non-active API Keys
 *
 * POST /resend_activation_link
 *
 * @author Johnathan Pulos
 */
$app->post(
    "/resend_activation_links",
    function (Request $request, Response $response): Response {
        $siteURL = getSiteURL();
        $errors = [];
        $message = '';
        $formData = $request->getParsedBody();
        $invalidFields = validatePresenceOf(["email"], $formData);
        if (empty($invalidFields)) {
            try {
                $statement = $this->db->prepare("SELECT * FROM `md_api_keys` WHERE email = :email AND status = 0");
                $statement->execute(['email' => $formData['email']]);
                $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                if (empty($data)) {
                    $errors['find_keys'] = "We were unable to locate your pending API keys.";
                } else {
                    $this->mailer->sendAuthorizationLinks($formData['email'], $data, $siteURL);
                    $message = "Your activation links have been emailed to you.";
                }
            } catch (Exception $e) {
                $errors['find_keys'] = "We were unable to locate your pending API keys.";
            }
        } else {
            $errors['invalid'] = $invalidFields;
        }
        return $this->get('view')->render(
            $response,
            'StaticPages/resend_activation_links.html.php',
            [
                'errors' => $errors,
                'data' => $formData,
                'message' => $message
            ]
        );
    }
);
