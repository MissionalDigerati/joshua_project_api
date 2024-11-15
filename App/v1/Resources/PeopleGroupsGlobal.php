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
 *     tags={"People Groups Across Countries (PGAC)"},
 *     path="/v1/people_groups_global/{id}.{format}",
 *     summary="Retrieve the details of a specific people group across countries. (JSON or XML)",
 *     description="Retrieve the details of a specific people group across the countries they reside.",
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

// phpcs:disable Generic.Files.LineLength
/**
 *
 * @OA\Get(
 *     tags={"People Groups Across Countries (PGAC)"},
 *     path="/v1/people_groups_global.{format}",
 *     summary="Search all people groups across countries based on provided filters. (JSON or XML)",
 *     description="Search through all the various people groups across the countries they reside in.",
 *     @OA\Parameter(ref="#/components/parameters/APIFormatParameter"),
 *     @OA\Parameter(ref="#/components/parameters/APIKeyParameter"),
 *     @OA\Parameter(
 *         name="countries",
 *         description="A bar separated list of one or more countries to filter by. This is the country where the largest portion of the people group reside. Use the 2 letter FIPS 10-4 code. [View all Country Codes](https://goo.gl/yYWY4J).",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="include_country_list",
 *         description="A boolean (represented as a string Y or N) that states whether you want to include a list of countries that they reside in. (Default: Y)",
 *         in="query",
 *         required=false,
 *         @OA\Schema(
 *          type="string",
 *          default="N",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="is_frontier",
 *         description="A boolean (represented as a string Y or N) that states whether you want people groups that as a whole are considered frontier people groups.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="jpscale",
 *         description="A bar separated list of one or more JPScale codes to filter by. Only accepts the following codes: 1, 2, 3, 4, 5.  For more information check out [https://joshuaproject.net/global/progress](https://joshuaproject.net/global/progress).",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="languages",
 *         description="A bar separated list of one or more language codes to filter by. Use the 3 letter ISO code.  See the [code list](http://www.loc.gov/standards/iso639-2/php/code_list.php) for the codes.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="least_reached",
 *         description="A boolean (represented as a string Y or N) that states whether you want people groups that are as a whole considered least reached.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         description="The maximum results to return.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             default=250
 *        )
 *     ),
 *     @OA\Parameter(
 *         name="number_of_countries",
 *         description="A dashed seperated range specifying the minimum and maximum number of countries the people group resides in.(min-max) You can supply just the minimum to get people groups with that matching number of countries.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="number_of_unreached",
 *         description="A dashed seperated range specifying the minimum and maximum number of people groups in country (PGIC) that are considered unreached.(min-max) You can supply just the minimum to get people groups that match that number.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="number_of_frontier",
 *         description="A dashed seperated range specifying the minimum and maximum number of people groups in country (PGIC) that are considered a frontier people group.(min-max) You can supply just the minimum to get people groups that match that number.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         description="The page of results to display.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             default=1
 *        )
 *     ),
 *     @OA\Parameter(
 *         name="pc_christian",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Christians among the people group.(min-max) You can supply just the minimum to get people groups matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_evangelical",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Evangelicals among the people group.(min-max) You can supply just the minimum to get people groups matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="people_id1",
 *         description="A bar separated list of one or more Joshua Project affinity block codes to filter by. See [https://joshuaproject.net/help/definitions#affinity-bloc](https://joshuaproject.net/help/definitions#affinity-bloc).",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="people_id2",
 *         description="A bar separated list of one or more Joshua Project people cluster codes to filter by. See [https://joshuaproject.net/help/definitions#people-cluster](https://joshuaproject.net/help/definitions#people-cluster).",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="people_id3",
 *         description="A bar separated list of one or more ethnicity codes to filter by.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="population",
 *         description="A dashed seperated range specifying the minimum and maximum population.(min-max) You can supply just the minimum to get people groups with a population matching that number.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="primary_religions",
 *         description="A bar separated list of one or more primary religions to filter by. Use the following numbers:
 * **1** = Christianity
 * **2** = Buddhism
 * **4** = Ethnic Religions
 * **5** = Hinduism
 * **6** = Islam
 * **7** = Non-Religious
 * **8** = Other/Small
 * **9** = Unknown",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="rop25",
 *         description="A bar separated list of one or more Ethnic Kinship codes to filter by.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="rop3",
 *         description="A bar separated list of one or more Registry of People IDs to filter by.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
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
    "/{version}/people_groups_global.{format}",
    function (Request $request, Response $response, $args = []): Response {
        $data = [];
        $params = $request->getQueryParams();
        $includeCountryList = (isset($params['include_country_list'])) ? $params['include_country_list'] : 'N';
        try {
            $generator = new PeopleGroupGlobal($params);
            $generator->findAllWithFilters();
            $statement = $this->get('db')->prepare($generator->preparedStatement);
            $statement->execute($generator->preparedVariables);
            $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
            if (strtoupper($includeCountryList) === 'Y') {
                $pgGenerator = new PeopleGroup([]);
                foreach ($data as $key => $value) {
                    $pgGenerator->findCountryList($data[$key]['PeopleID3']);
                    $statementTwo = $this->get('db')->prepare($pgGenerator->preparedStatement);
                    $statementTwo->execute($pgGenerator->preparedVariables);
                    $data[$key]['Countries'] = $statementTwo->fetchAll(\PDO::FETCH_ASSOC);
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
                ->write(arrayToXML($data, "people_groups", "people_group"));
        }
    }
);
