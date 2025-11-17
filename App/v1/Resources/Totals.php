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

namespace App\v1\Resources;

use Doctrine\DBAL\Exception as DBALException;
use QueryGenerators\Total;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Attributes as OA;

// phpcs:disable Generic.Files.LineLength
/**
 *
 * @OA\Get(
 *     tags={"Totals"},
 *     path="/v1/totals/{id}.{format}",
 *     summary="Retrieve the global total for a specific id (case insensitive) in various formats. (JSON or XML)",
 *     description="Retrieve the global total for a specific id (case insensitive). Look at the [column descriptions](/v1/docs/column_descriptions/totals) for Totals to see all the provided information.",
 *     @OA\Parameter(
 *         name="id",
 *         description="The unique total id (case insensitive).",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(ref="#/components/parameters/APIFormatParameter"),
 *     @OA\Parameter(ref="#/components/parameters/APIKeyParameter"),
 *     @OA\Response(
 *         response="200",
 *         description="The requested totals.",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(type="object")
 *         ),
 *         @OA\MediaType(
 *             mediaType="application/xml",
 *             @OA\Schema(type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         ref="#/components/responses/400ApiResponse"
 *     ),
 *     @OA\Response(
 *         response="401",
 *         ref="#/components/responses/401ApiResponse"
 *     ),
 *     @OA\Response(
 *         response="404",
 *         ref="#/components/responses/404ApiResponse"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         ref="#/components/responses/500ApiResponse"
 *     )
 * )
 */
// phpcs:enable Generic.Files.LineLength
$app->get(
    '/{version}/totals/{id}.{format}',
    function (Request $request, Response $response, $args = []): Response {
        $data = [];
        $id = strip_tags($args['id']);
        try {
            $total = new Total(['id' => $id]);
            $total->findById();
            $data = $this->get('db')->fetchAllAssociative(
                $total->preparedStatement,
                $total->preparedVariables,
                $total->preparedVariableTypes
            );
        } catch (DBALException | \Exception $e) {
            error_log("Error totals show: {$e->getMessage()}");
            return $this->get('errorResponder')->get(
                500,
                $e->getMessage(),
                $args['format'],
                'Internal Server Error',
                $response
            );
        }
        /**
         * Render the final data
         */
        $body = $response->getBody();
        if ($args['format'] == 'json') {
            $json = json_encode($data);
            $body->write($json);
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $xml = arrayToXML($data, "totals", "total");
            $body->write($xml);
            return $response->withHeader('Content-type', 'text/xml');
        }
    }
);

// phpcs:disable Generic.Files.LineLength
/**
 *
 * @OA\Get(
 *     tags={"Totals"},
 *     path="/v1/totals.{format}",
 *     summary="Retrieve global totals in various formats. (JSON or XML)",
 *     description="Retrieve various global totals including total Christian people groups, continents, reagions and many more. Look at the [column descriptions](/v1/docs/column_descriptions/totals) for Totals to see all the provided information.",
 *     @OA\Parameter(ref="#/components/parameters/APIFormatParameter"),
 *     @OA\Parameter(ref="#/components/parameters/APIKeyParameter"),
 *     @OA\Response(
 *         response="200",
 *         description="The totals.",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(type="object")
 *         ),
 *         @OA\MediaType(
 *             mediaType="application/xml",
 *             @OA\Schema(type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response="400",
 *         ref="#/components/responses/400ApiResponse"
 *     ),
 *     @OA\Response(
 *         response="401",
 *         ref="#/components/responses/401ApiResponse"
 *     ),
 *     @OA\Response(
 *         response="404",
 *         ref="#/components/responses/404ApiResponse"
 *     ),
 *     @OA\Response(
 *         response="500",
 *         ref="#/components/responses/500ApiResponse"
 *     )
 * )
 */
// phpcs:enable Generic.Files.LineLength
$app->get(
    '/{version}/totals.{format}',
    function (Request $request, Response $response, $args = []): Response {
        $data = [];
        try {
            $total = new Total([]);
            $total->all();
            $data = $this->get('db')->fetchAllAssociative(
                $total->preparedStatement,
                $total->preparedVariables,
                $total->preparedVariableTypes
            );
        } catch (DBALException | \Exception $e) {
            error_log("Error totals index: {$e->getMessage()}");
            return $this->get('errorResponder')->get(
                500,
                $e->getMessage(),
                $args['format'],
                'Internal Server Error',
                $response
            );
        }
        /**
         * Render the final data
         */
        $body = $response->getBody();
        if ($args['format'] == 'json') {
            $json = json_encode($data);
            $body->write($json);
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $xml = arrayToXML($data, "totals", "total");
            $body->write($xml);
            return $response->withHeader('Content-type', 'text/xml');
        }
    }
);
