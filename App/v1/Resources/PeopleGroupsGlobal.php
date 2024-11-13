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

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use QueryGenerators\PeopleGroupGlobal;
use QueryGenerators\PeopleGroup;

// phpcs:disable Generic.Files.LineLength
/**
 *
 * @OA\Get(
 *     tags={"People Groups Global"},
 *     path="/v1/people_groups_global/{id}.{format}",
 *     summary="Retrieve the details of a specific People Group across countries. (JSON or XML)",
 *     description="Retrieve the details of a specific People Group across the countries they reside.",
 *     @OA\Parameter(
 *         name="id",
 *         description="Joshua Project's PeopleID3.",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(ref="#/components/parameters/APIFormatParameter"),
 *     @OA\Parameter(ref="#/components/parameters/APIKeyParameter"),
 *     @OA\Parameter(
 *         name="include_country_list",
 *         description="A boolean (represented as a string Y or N) that states whether you want to include a list of countries that they reside in. (Default: Y)",
 *         in="query",
 *         required=false,
 *         @OA\Schema(
 *          type="string",
 *          default="Y",
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="The details of the specified people group.",
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
    "/{version}/people_groups_global/{id}.{format}",
    function (Request $request, Response $response, $args = []): Response {
        $data = [];
        $peopleId = intval(strip_tags($args['id']));
        $params = $request->getQueryParams();
        $includeCountryList = (isset($params['include_country_list'])) ? $params['include_country_list'] : 'Y';
        if (empty($peopleId)) {
            return $this->get('errorResponder')->get(
                400,
                'You provided an invalid PeopleID3.',
                $args['format'],
                'Bad Request',
                $response
            );
        }
        try {
            $generator = new PeopleGroupGlobal(['id' => $peopleId]);
            $generator->findById();
            $statement = $this->get('db')->prepare($generator->preparedStatement);
            $statement->execute($generator->preparedVariables);
            $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
            if (empty($data)) {
                return $this->get('errorResponder')->get(
                    404,
                    'The requested people group does not exist.',
                    $args['format'],
                    'Not Found',
                    $response
                );
            }
            if (strtoupper($includeCountryList) === 'Y') {
                $pgGenerator = new PeopleGroup([]);
                $pgGenerator->findCountryList($peopleId);
                $statementTwo = $this->get('db')->prepare($pgGenerator->preparedStatement);
                $statementTwo->execute($pgGenerator->preparedVariables);
                $countries = $statementTwo->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($data as $key => $value) {
                    $data[$key]['Countries'] = $countries;
                }
            }
        } catch (\Exception $e) {
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
                ->write(arrayToXML($data, "people_groups_global", "people_group"));
        }
    }
);
