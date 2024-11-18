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

namespace Utilities;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Send back an error response from the API
 */
class APIErrorResponder
{
    /**
     * Get the response to send
     *
     * @param int       $code       The HTTP status code
     * @param string    $details    The details for the error
     * @param string    $format     The preferred format (json or xml)
     * @param string    $message    The message to send
     * @param Response  $response   The response message
     *
     * @return Response             The modified response
     */
    public function get(
        int $code,
        string $details,
        string $format,
        string $message,
        Response $response
    ): Response {
        if ($format === 'json') {
            $data = [
                'api' =>    [
                    'status'    =>  'error',
                    'error'     =>  [
                        'code'    =>  $code,
                        'message'  =>  $message,
                        'details' => $details
                    ]
                ]
            ];
            $body = json_encode($data);
            $contentType = 'application/json; charset=UTF-8';
        } else {
            $body = '<api>
                <status>error</status>
                <error>
                    <code>' . $code . '</code>
                    <message>' . $message . '</message>
                    <details>' . $details . '</details>
                </error>
            </api>';
            $contentType = 'application/xml; charset=UTF-8';
        }

        $response->getBody()->write($body);
        return $response
            ->withHeader('Content-Type', $contentType)
            ->withStatus($code);
    }
}
