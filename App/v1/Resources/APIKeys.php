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
    function () use ($app, $db, $appRequest) {
        $data = $appRequest->get();
        $query = "SELECT * FROM md_api_keys ORDER BY created DESC";
        try {
            $statement = $db->prepare($query);
            $statement->execute(array());
            $api_keys = $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e;
            exit;
        }
        $app->render('APIKeys/index.html.php', array('api_keys' => $api_keys, 'data' => $data));
    }
);
/**
 * Sets the suspended attribute on the API Key
 *
 * PUT /api_keys/:id
 * Available Formats HTML
 *
 * @author Johnathan Pulos
 * @todo verify this works with the new status column
 **/
$app->put(
    "/api_keys/:id",
    function ($id) use ($app, $db, $appRequest) {
        $formData = $appRequest->put();
        if (!isset($formData['suspended'])) {
            $app->redirect("/api_keys?saving_error=true");
        }
        $query = "UPDATE md_api_keys SET suspended = :suspended WHERE id = :id";
        try {
            $statement = $db->prepare($query);
            $statement->execute(
                array(
                    'id' => $id,
                    'suspended' => $formData['suspended']
                )
            );
        } catch (PDOException $e) {
            $app->redirect("/api_keys?saving_error=true");
        }
        if ($formData['suspended'] == 0) {
            $keyState = "reinstated";
        } else {
            $keyState = "suspended";
        }
        $app->redirect("/api_keys?saved=true&key_state=" . $keyState);
    }
);
/**
 * Create an API key
 *
 * POST /api_keys
 * Available Formats HTML
 * 
 * @author Johnathan Pulos
 */
$app->post(
    "/api_keys",
    function () use ($app, $db, $appRequest) {
        $formData = $appRequest->post();
        $invalidFields = validatePresenceOf(array("name", "email", "usage"), $formData);
        $redirectURL = generateRedirectURL("/", $formData, $invalidFields);
        if (!empty($invalidFields)) {
            $app->redirect($redirectURL);
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
                                'api_key' => $newAPIKey,
                                'authorize_token' => $authorizeToken,
                                'status' => 0
                            );
        $query = "INSERT INTO md_api_keys (name, email, organization, website, phone_number, api_usage, api_key, authorize_token, status, created)" .
        " VALUES (:name, :email, :organization, :website, :phone_number, :api_usage, :api_key, :authorize_token, :status, NOW())";
        try {
            $statement = $db->prepare($query);
            $statement->execute($apiKeyValues);
        } catch (PDOException $e) {
            $app->redirect("/?saving_error=true");
        }
        /**
         * @todo Need to send email with link to retrieve API Key
         *
         * @author Johnathan Pulos
         */
        $redirectURL = generateRedirectURL("/", array('api_key' => 'true'), array());
        $app->redirect($redirectURL);
    }
);
