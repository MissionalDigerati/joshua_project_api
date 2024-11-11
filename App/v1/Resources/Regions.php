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

use QueryGenerators\Region;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Attributes as OA;

// phpcs:disable Generic.Files.LineLength
/**
 *
 * @OA\Get(
 *     path="/regions/{id}.{format}",
 *     summary="Retrieve the details of a specific Region (JSON or XML)",
 *     description="Retrieve the details of a specific Region by supplying a unique id for the region.  Use the following numbers:
 *
 * - **1** = South Pacific
 * - **2** = Southeast Asia
 * - **3** = Northeast Asia
 * - **4** = South Asia
 * - **5** = Central Asia
 * - **6** = Middle East and North Africa
 * - **7** = East and Southern Africa
 * - **8** = West and Central Africa
 * - **9** = Eastern Europe and Eurasia
 * - **10** = Western Europe
 * - **11** = Central and South America
 * - **12** = North America and Caribbean",
 *     @OA\Parameter(
 *         name="api_key",
 *         description="Your Joshua Project API key.",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="id",
 *         description="The unique id for the region. Use the codes indicated above.",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="format",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string", enum={"json", "xml"})
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="The details about the specific region.",
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
 *         description="Bad request. Your request is malformed in some way. Check your supplied parameters.",
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
 *         response="401",
 *         description="Unauthorized. You are missing your API key, or it has been suspended.",
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
 *         response="404",
 *         description="Not found. The requested route was not found.",
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
 *         response="500",
 *         description="Internal server error. Please try again later.",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(type="object")
 *         ),
 *         @OA\MediaType(
 *             mediaType="application/xml",
 *             @OA\Schema(type="object")
 *         )
 *     )
 * )
 */
// phpcs:enable Generic.Files.LineLength
$app->get(
    "/{version}/regions/{id}.{format}",
    function (Request $request, Response $response, $args = []): Response {
        /**
         * Make sure we have an ID, else crash
         *
         * @author Johnathan Pulos
         */
        $regionId = intval(strip_tags($args['id']));
        if ((empty($regionId)) || ($regionId > 12)) {
            return $this->get('errorResponder')->get(
                400,
                'You provided an invalid region id.',
                $args['format'],
                'Bad Request',
                $response
            );
        }
        try {
            $region = new Region(['id' => $regionId]);
            $region->findById();
            $statement = $this->get('db')->prepare($region->preparedStatement);
            $statement->execute($region->preparedVariables);
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (empty($data)) {
                return $this->get('errorResponder')->get(
                    404,
                    'The region does not exist for the given id.',
                    $args['format'],
                    'Not Found',
                    $response
                );
            }
        } catch (Exception $e) {
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
         *
         * @author Johnathan Pulos
         */
        if ($args['format'] == 'json') {
            return $response
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($data));
        } else {
            return $response
                ->withHeader('Content-type', 'text/xml')
                ->write(arrayToXML($data, "regions", "region"));
        }
    }
);
