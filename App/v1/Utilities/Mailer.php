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
namespace Utilities;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * A class that handles emailing.
 *
 * @author Johnathan Pulos
 * @package Utilities
 */
class Mailer
{
    /**
     * Our mailer instance
     *
     * @var PHPMailer
     */
    protected $mailer;

    /**
     * Build the mailer class
     *
     * @param string    $host       The SMTP host (default: '')
     * @param string    $username   The SMTP username (default: '')
     * @param string    $password   The SMTP password (default: '')
     * @param integer   $port       The SMTP port (default: 465)
     * @param boolean   $useSMTP    Do you want to use a SMTP server? (default: false)
     */
    public function __construct(
        $host = '',
        $username = '',
        $password = '',
        $port = 465,
        $useSMTP = false
    ) {
        $settings = array(
            'host'      =>  $host,
            'username'  =>  $username,
            'password'  =>  $password,
            'port'      =>  $port,
            'use_smtp'  =>  $useSMTP
        );
        $this->mailer = $this->getMailInstance($settings);
    }

    /**
     * Email the Developer an Autorization URL.
     *
     * Sends the authorize URL to the developer so they can get obtain their API Key.
     *
     * @param   string      $email          The email address to send the email to.
     * @param   string      $authorizeUrl   The url for authorizing the email.
     * @return  void
     * @author  Johnathan Pulos
     */
    public function sendAuthorizeToken($email, $authorizeUrl)
    {
        $this->mailer->Subject = 'Joshua Project API Key';
        $this->mailer->setFrom('info@joshuaproject.net', 'Joshua Project', 0);
        $this->mailer->addAddress($email);
        $emailMessage = "Dear Developer,<br>Thank you for your request for a Joshua Project API Key.";
        $emailMessage .= "Please click the following link to retrieve your key:<br><br>";
        $emailMessage .= "<a href='" . $authorizeUrl . "'>" . $authorizeUrl . "</a><br>";
        $emailMessage .= "Take care, and God Bless.<br>Sincerely,<br>Joshua Project API";
        $this->mailer->Body = $emailMessage;
        $this->mailer->send();
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
    public function sendAuthorizationLinks($email, $apiKeys, $domain)
    {
        $this->mailer->Subject = 'Joshua Project API Key';
        $this->mailer->setFrom('info@joshuaproject.net', 'Joshua Project', 0);
        $this->mailer->addAddress($email);
        $emailMessage = "Dear Developer,<br>";
        $emailMessage .= "You have requested your authorization tokens that have not been activated. ";
        $emailMessage .= "Please click the following links to activate your key:<br><br>";
        foreach ($apiKeys as $apiKey) {
            $authorizationUrl = $domain . "/get_my_api_key?authorize_token=" . $apiKey['authorize_token'];
            $emailMessage .= $apiKey['api_usage'];
            $emailMessage .= ": <a href='" . $authorizationUrl . "'>" . $authorizationUrl . "</a><br><br>";
        }
        $emailMessage .= "Take care, and God Bless.<br>Sincerely,<br>Joshua Project API";
        $this->mailer->Body = $emailMessage;
        $this->mailer->send();
    }

    /**
     * Get the instance of the mailer to send.
     *
     * @return PHPMailer    The PHPMailer instance
     */
    private function getMailInstance($settings)
    {
        $mail = new PHPMailer(true);
        $mail->isHTML(true);
        if (!$settings['use_smtp']) {
            return $mail;
        }
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = $settings['host'];
        $mail->Port = $settings['port'];
        $mail->Username = $settings['username'];
        $mail->Password = $settings['password'];
        return $mail;
    }
}
