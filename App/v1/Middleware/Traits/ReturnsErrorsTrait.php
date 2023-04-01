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
namespace Middleware\Traits;

use Psr\Http\Message\ResponseInterface;

/**
 * This trait handles returning errors based on the provided format.
 */
trait ReturnsErrorsTrait
{
    public function sendError(
        $code,
        $details,
        $format,
        $message,
        ResponseInterface $res
    ) {
        $error = $this->getResponseError($code, $details, $message);
        switch ($format) {
            case 'json':
                $body = json_encode($error);
                $contentType = 'application/json; charset=UTF-8';
                break;
            case 'xml':
                $body = $this->toXML($error);
                $contentType = 'application/xml; charset=UTF-8';
                break;
            default:
                $body = $this->toHTML($error);
                $contentType = 'text/html; charset=UTF-8';
                break;
        }
        $res->getBody()->write($body);
        return $res
            ->withHeader('Content-Type', $contentType)
            ->withStatus($code);
    }

    /**
     * Get a response for the given parameters
     *
     * @param int       $code       The HTTP status code
     * @param string    $details    The details for the message
     * @param string    $message    The message
     * @param string    $status     The status of the request (success or error)
     *
     * @return array                The message
     */
    private function getResponseError($code, $details, $message)
    {
        return array(
            'api'   =>  array(
                'status'        =>  'error',
                'error'         =>  array(
                    'code'      =>  $code,
                    'message'   =>  $message,
                    'details'   =>  $details
                )
            )
        );
    }

    /**
     * Convert the error to HTML
     *
     * @param array $error  The error to convert
     */
    private function toHTML($error)
    {
        $html = '<h1>API Error</h1>';
        $message = $error['api']['error']['message'];
        $details = $error['api']['error']['details'];
        $html .= '<p><strong>' . $message . '</strong>' . $details . '</p>';
        return $html;
    }

    /**
     * Convert the error to XML
     *
     * @param array $error  The error to convert
     */
    private function toXML($error)
    {
        $xml = new \SimpleXMLElement('<api/>');
        $xml->addChild('status', $error['api']['status']);
        $errorNode = $xml->addChild('error');
        $errorNode->addChild('code', $error['api']['error']['code']);
        $errorNode->addChild('message', $error['api']['error']['message']);
        $errorNode->addChild('details', $error['api']['error']['details']);
        return $xml->asXML();
    }
}
