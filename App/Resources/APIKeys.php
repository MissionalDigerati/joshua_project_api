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
        $query = "INSERT INTO md_api_keys (name, email, api_usage, api_key, created)" .
        " VALUES (:name, :email, :usage, :api_key, NOW())";
        try {
            $statement = $db->prepare($query);
            $statement->execute(
                array(
                    'name' => $formData['name'],
                    'email' => $formData['email'],
                    'usage' => $formData['usage'],
                    'api_key' => $newAPIKey
                )
            );
        } catch (PDOException $e) {
            $app->redirect("/?saving_error=true");
        }
        $redirectURL = generateRedirectURL("/", array('api_key' => $newAPIKey), array());
        $app->redirect($redirectURL);
    }
);
