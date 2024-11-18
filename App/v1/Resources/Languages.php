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

use QueryGenerators\Language;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Attributes as OA;

// phpcs:disable Generic.Files.LineLength
/**
 *
 * @OA\Get(
 *     tags={"Languages"},
 *     path="/v1/languages/{id}.{format}",
 *     summary="Retrieve the details of a specific language.",
 *     description="Retrieve the details of a specific Language by supplying the language's [3 letter ISO 639-2 Code](http://goo.gl/gbkgo4).",
 *     @OA\Parameter(
 *         name="id",
 *         description="The 3 letter ISO 639-2 Language Code for the Language you want to view. [View all Language Codes](http://goo.gl/gbkgo4)",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(ref="#/components/parameters/APIFormatParameter"),
 *     @OA\Parameter(ref="#/components/parameters/APIKeyParameter"),
 *     @OA\Response(
 *         response="200",
 *         description="The details about the specific language.",
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
    "/{version}/languages/{id}.{format}",
    function (Request $request, Response $response, $args = []): Response {
        /**
         * Make sure we have an ID, else crash
         * This expression ("/\PL/u") removes all non-letter characters
         *
         * @author Johnathan Pulos
         */
        $languageId = preg_replace("/\PL/u", "", strip_tags(strtoupper($args['id'])));
        if ((empty($languageId)) || (strlen($languageId) != 3)) {
            return $this->get('errorResponder')->get(
                400,
                'You provided an invalid language id.',
                $args['format'],
                'Bad Request',
                $response
            );
        }
        try {
            $lang = new Language(['id' => $languageId]);
            $lang->findById();
            $statement = $this->get('db')->prepare($lang->preparedStatement);
            $statement->execute($lang->preparedVariables);
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (empty($data)) {
                return $this->get('errorResponder')->get(
                    404,
                    'The language does not exist for the given id.',
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
                ->write(arrayToXML($data, "languages", "language"));
        }
    }
);
// phpcs:disable Generic.Files.LineLength
/**
 *
 * @OA\Get(
 *     tags={"Languages"},
 *     path="/v1/languages.{format}",
 *     summary="Retrieve the details of a set of languages.",
 *     description="Find all languages that match your filter criteria.",
 *     @OA\Parameter(ref="#/components/parameters/APIFormatParameter"),
 *     @OA\Parameter(ref="#/components/parameters/APIKeyParameter"),
 *     @OA\Parameter(
 *         name="countries",
 *         description="A bar separated list of one or more countries to filter by. Use the 2 letter FIPS 10-4 code. [View all Country Codes](https://goo.gl/yYWY4J)",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="has_audio",
 *         description="A boolean (represented as a string Y or N) that states whether you want languages who have access to audio Bibles.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="has_completed_bible",
 *         description="A boolean (represented as a string Y or N) that states whether you want languages who have access to a completed Bible.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="has_jesus_film",
 *         description="A boolean (represented as a string Y or N) that states whether you want languages who have access to the Jesus Film.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="has_new_testament",
 *         description="A boolean (represented as a string Y or N) that states whether you want languages who have access to the New Testament.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="has_portions",
 *         description="A boolean (represented as a string Y or N) that states whether you want languages who have access to the portions of the Bible.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="ids",
 *         description="A bar separated list of one or more language codes to filter by. Use the 3 letter ISO code.  See <a href='http://goo.gl/EQn1RL' target='_blank'>this chart</a> for the codes.",
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
 *         name="least_reached",
 *         description="A boolean (represented as a string Y or N) that states whether you want languages that are least reached.",
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
 *         name="needs_translation_questionable",
 *         description="A boolean (represented as a string Y or N) that states whether you want Languages whose need for Bible translation is questionable.",
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
 *         name="pc_adherent",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Adherents.(min-max) You can supply just the minimum to get Languages matching that percentage.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_evangelical",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Evangelicals.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
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
 *     @OA\Response(
 *         response="200",
 *         description="The languages filtered by your query.",
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
    "/{version}/languages.{format}",
    function (Request $request, Response $response, $args = []) {
        $params = $request->getQueryParams();
        try {
            $lang = new Language($params);
            $lang->findAllWithFilters();
            $statement = $this->get('db')->prepare($lang->preparedStatement);
            $statement->execute($lang->preparedVariables);
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);
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
                ->write(arrayToXML($data, "languages", "language"));
        }
    }
);
