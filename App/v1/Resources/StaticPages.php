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
    function () use ($app, $db, $appRequest, $VIEW_DIRECTORY) {
        $data = $appRequest->get();
        $errors = array();
        if ((isset($data['required_fields'])) && ($data['required_fields'] !="")) {
            $errors = explode("|", $data['required_fields']);
        }
        $app->render(
            'StaticPages/home.html.php',
            array('data' => $data, 'errors' => $errors, 'VIEW_DIRECTORY' => $VIEW_DIRECTORY)
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
    function () use ($app, $db, $appRequest, $DOMAIN_ADDRESS, $VIEW_DIRECTORY) {
        $app->render(
            'StaticPages/getting_started.html.php',
            array('DOMAIN_ADDRESS' => $DOMAIN_ADDRESS, 'VIEW_DIRECTORY' => $VIEW_DIRECTORY)
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
    function () use ($app, $db, $appRequest, $VIEW_DIRECTORY) {
        $APIKey = "";
        $message = "";
        $error = "";
        $getData = $appRequest->get();
        if ($getData['authorize_token'] == '') {
            $error = "Unable to locate your API key.";
        } else {
            try {
                $statement = $db->prepare("SELECT * FROM `md_api_keys` WHERE authorize_token = :authorize_token");
                $statement->execute(array('authorize_token' => $getData['authorize_token']));
                $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $error = "Unable to locate your API key.";
            }
        }
        if ($error == '') {
            try {
                switch ($data[0]['status']) {
                    case 0:
                        $status = 1;
                        $message = "Your API Key has been activated.";
                        $APIKey = $data[0]['api_key'];
                        break;
                    case 1:
                        $status = 1;
                        $message = "Your API Key was already activated.";
                        $APIKey = $data[0]['api_key'];
                        break;
                    case 2:
                        $status = 2;
                        $error = "Your API Key was suspended!";
                        break;
                }
                $statement = $db->prepare(
                    "UPDATE `md_api_keys` SET status = :status WHERE id = :id"
                );
                $statement->execute(array('id' => $data[0]['id'], 'status' => $status));
            } catch (Exception $e) {
                $error = "Unable to update your API Key.";
            }
        }

        $app->render(
            'StaticPages/get_my_api_key.html.php',
            array('message' => $message, 'error' => $error, 'APIKey' => $APIKey, 'VIEW_DIRECTORY' => $VIEW_DIRECTORY)
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
    function () use ($app, $db, $appRequest, $VIEW_DIRECTORY) {
        $app->render('StaticPages/resend_activation_links.html.php', array('VIEW_DIRECTORY' => $VIEW_DIRECTORY));
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
    function () use ($app, $db, $appRequest, $DOMAIN_ADDRESS, $VIEW_DIRECTORY) {
        $errors = array();
        $message = '';
        $formData = $appRequest->post();
        $errors = validatePresenceOf(array("email"), $formData);
        if (empty($errors)) {
            try {
                $statement = $db->prepare("SELECT * FROM `md_api_keys` WHERE email = :email AND status = 0");
                $statement->execute(array('email' => $formData['email']));
                $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                if (empty($data)) {
                    $errors['find_keys'] = "We were unable to locate your pending API keys.";
                } else {
                    Utilities\Mailer::sendAuthorizationLinks($formData['email'], $data, $DOMAIN_ADDRESS, null);
                    $message = "Your activation links have been emailed to you.";
                }
            } catch (Exception $e) {
                $errors['find_keys'] = "We were unable to locate your pending API keys.";
            }
        }
        $app->render(
            'StaticPages/resend_activation_links.html.php',
            array('errors' => $errors, 'data' => $formData, 'message' => $message, 'VIEW_DIRECTORY' => $VIEW_DIRECTORY)
        );
    }
);
