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
 * Email the Developer an Autorization URL.
 *
 * Sends the authorize URL to the developer so they can get obtain their API Key.
 *
 * @param   string      $email          The email address to send the email to.
 * @param   string      $authorizeUrl   The url for authorizing the email.
 * @param   PHPMailer   $mail           The PHPMailer Object for sending the email.
 * @return  void
 * @author  Johnathan Pulos
 */
function sendAuthorizeToken($email, $authorizeUrl, $mail)
{
    $mail->IsMail();
    $mail->Timeout  = 360;
    $mail->Subject =  'Joshua Project API Key';
    $mail->From = 'api@joshuaproject.net';
    $mail->FromName = 'Joshua Project API';
    $mail->AddAddress($email, '');
    $emailMessage = "Dear Developer,<br>Thank you for your request for a Joshua Project API Key.  Please click the following link to retrieve your key:<br><br>";
    $emailMessage .= "<a href='" . $authorizeUrl . "'>" . $authorizeUrl . "</a><br>Take care, and God Bless.<br>Sincerely,<br>Joshua Project API";
    $mail->Body = $emailMessage;
    $mail->IsHTML(true);
    $mail->Send();
}
/**
 * Retrieve all API tokens.
 *
 * Sends all authorize URLs to the developer so they can get obtain their API Keys.
 *
 * @param   string      $email      The email address to send the email to.
 * @param   array       $apiKeys    All the API keys that have not been activated yet.
 * @param   string      $domain     The domain name for this website.
 * @param   PHPMailer   $mail       The PHPMailer Object for sending the email.
 * @return  void
 * @author  Johnathan Pulos
 */
function sendAuthorizationLinks($email, $apiKeys, $domain, $mail)
{
    $mail->IsMail();
    $mail->Timeout  = 360;
    $mail->Subject =  'Joshua Project API Key';
    $mail->From = 'api@joshuaproject.net';
    $mail->FromName = 'Joshua Project API';
    $mail->AddAddress($email, '');
    $emailMessage = "Dear Developer,<br>You have requested your authorization tokens that have not been activated. ";
    $emailMessage .= "Please click the following links to activate your key:<br><br>";
    foreach ($apiKeys as $apiKey) {
        $authorizationUrl = $domain . "/get_my_api_key?authorize_token=" . $apiKey['authorize_token'];
        $emailMessage .= $apiKey['api_usage'] . "<br><a href='" . $authorizationUrl . "'>" . $authorizationUrl . "</a><br>";
    }
    $emailMessage .= "<br>Take care, and God Bless.<br>Sincerely,<br>Joshua Project API";
    $mail->Body = $emailMessage;
    $mail->IsHTML(true);
    $mail->Send();
}
