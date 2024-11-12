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

use QueryGenerators\Continent;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Attributes as OA;

// phpcs:disable Generic.Files.LineLength
/**
 * @OA\Get(
 *     path="/continents/{id}.{format}",
 *     summary="Get the details about a specific continent.",
 *     description="Retrieve the details of a specific Continent by supplying a three letter ISO Continent code (id).  Use the following codes:
 * **AFR** = Africa
 * **ASI** = Asia
 * **AUS** = Australia
 * **EUR** = Europe
 * **NAR** = North America
 * **SOP** = Oceania (South Pacific)
 * **LAM** = South America",
 *     @OA\Parameter(ref="#/components/parameters/APIKeyParameter"),
 *     @OA\Parameter(
 *         name="id",
 *         description="The 3 letter ISO Continent Code for the Continent you want to view. Use the codes indicated above.",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(ref="#/components/parameters/APIFormatParameter"),
 *     @OA\Response(
 *         response="200",
 *         description="The details about the specific continent.",
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
    "/{version}/continents/{id}.{format}",
    function (Request $request, Response $response, $args = []): Response {
        /**
         * Make sure we have an ID, else crash.
         * This expression ("/\PL/u") removes all non-letter characters
         *
         * @author Johnathan Pulos
         */
        $format = $args['format'];
        $continentId = preg_replace(
            "/\PL/u",
            "",
            strip_tags(
                strtoupper($args['id'])
            )
        );
        if ((empty($continentId)) || (strlen($continentId) != 3)) {
            return $this->get('errorResponder')->get(
                400,
                'You provided an invalid continent id.',
                $format,
                'Bad Request',
                $response
            );
        }
        try {
            $continent = new Continent(['id' => $continentId]);
            $continent->findById();
            $statement = $this->get('db')->prepare($continent->preparedStatement);
            $statement->execute($continent->preparedVariables);
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (empty($data)) {
                return $this->get('errorResponder')->get(
                    404,
                    'The continent does not exist for the given id.',
                    $format,
                    'Not Found',
                    $response
                );
            }
        } catch (Exception $e) {
            return $this->get('errorResponder')->get(
                500,
                $e->getMessage(),
                $format,
                'Internal Server Error',
                $response
            );
        }
        /**
         * Render the final data
         *
         * @author Johnathan Pulos
         */
        if ($format == 'json') {
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode($data));
        } else {
            return $response
                ->withHeader('Content-Type', 'text/xml')
                ->write(arrayToXML($data, "continents", "continent"));
        }
    }
);
