<?php
use Slim\Http\Request;
use Slim\Http\Response;

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
    function (Request $req, Response $res, $args = []) {
        $viewDirectory = $this->view->getTemplatePath();
        $data = $req->getQueryParams();
        $query = "SELECT * FROM md_api_keys ORDER BY created DESC";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute(array());
            $api_keys = $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e;
            exit;
        }
        return $this->view->render(
            $res,
            'APIKeys/index.html.php',
            array('api_keys' => $api_keys, 'data' => $data, 'viewDirectory' => $viewDirectory)
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
    function (Request $req, Response $res, $args = []) {
        $id = $args['id'];
        $state = $req->getParam('state');
        if (!$state) {
            return $res->withHeader('Location', "/api_keys?saving_error=true");
        }
        $query = "UPDATE md_api_keys SET status = :state WHERE id = :id";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute(
                array(
                    'id' => $id,
                    'state' => $state
                )
            );
        } catch (PDOException $e) {
            return $res->withHeader('Location', "/api_keys?saving_error=true");
        }
        if (intval($state) === 1) {
            $keyState = "activated or reinstated";
        } elseif (intval($state) === 2) {
            $keyState = "suspended";
        }
        return $res
        ->withHeader('Location', "/api_keys?saved=true&key_state=" . $keyState);
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
    function (Request $req, Response $res, $args = []) use ($DOMAIN_ADDRESS) {
        $formData = $req->getParsedBody();
        $invalidFields = validatePresenceOf(array("name", "email", "usage"), $formData);
        $redirectURL = generateRedirectURL("/", $formData, $invalidFields);
        if (!empty($invalidFields)) {
            return $res->withHeader('Location', $redirectURL);
        }
        $newAPIKey = generateRandomKey(12);
        $authorizeToken = generateRandomKey(12);
        $phoneNumber = returnPresentIfKeyExistsOrDefault($formData, 'phone_number', '');
        $organization = returnPresentIfKeyExistsOrDefault($formData, 'organization', '');
        $website = returnPresentIfKeyExistsOrDefault($formData, 'website', '');
        $cleanedPhoneNumber = preg_replace("/[^0-9]/", "", $phoneNumber);
        $apiKeyValues = array(  'name' => $formData['name'],
                                'email' => $formData['email'],
                                'organization' => $organization,
                                'website' => $website,
                                'phone_number' => $cleanedPhoneNumber,
                                'api_usage' => $formData['usage'],
                                'resource_used' =>  'API',
                                'api_key' => $newAPIKey,
                                'authorize_token' => $authorizeToken,
                                'status' => 0
                            );
        $query = "INSERT INTO md_api_keys (name, email, organization, website, phone_number, api_usage, api_key, " .
        "authorize_token, resource_used, status, created) VALUES (:name, :email, :organization, :website, " .
        ":phone_number, :api_usage, :api_key, :authorize_token, :resource_used, :status, NOW())";
        try {
            $statement = $this->db->prepare($query);
            $statement->execute($apiKeyValues);
        } catch (PDOException $e) {
            return $res->withHeader('Location', "/?saving_error=true");
        }
        /**
         * Send the email with the authorization url
         *
         * @author Johnathan Pulos
         */
        $authorizeUrl = $DOMAIN_ADDRESS . "/get_my_api_key?authorize_token=" . $authorizeToken;
        Utilities\Mailer::sendAuthorizeToken($formData['email'], $authorizeUrl);
        $redirectURL = generateRedirectURL("/", array('api_key' => 'true'), array());
        return $res
        ->withHeader('Location', $redirectURL);
    }
);
