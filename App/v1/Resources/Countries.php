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

use QueryGenerators\Country;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Attributes as OA;

// phpcs:disable Generic.Files.LineLength
/**
 * @OA\Get(
 *     path="/countries/{id}.{format}",
 *     summary="Retrieve the details of a specific country.",
 *     description="Retrieve the details of a specific Country by supplying the country's <a href='https://goo.gl/yYWY4J' target='_blank'>2 letter FIPS 10-4 Code</a> (id).",
 *     @OA\Parameter(
 *         name="api_key",
 *         description="Your Joshua Project API key.",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="id",
 *         description="The 2 letter FIPS 10-4 Country Code for the Country you want to view. [<a href='https://goo.gl/yYWY4J' target='_blank'>View all Country Codes</a>]",
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
    "/{version}/countries/{id}.{format}",
    function (Request $request, Response $response, $args = []): Response {
        /**
         * Make sure we have an ID, else crash
         * This expression ("/\PL/u") removes all non-letter characters
         *
         * @author Johnathan Pulos
         */
        $countryId = preg_replace("/\PL/u", "", strip_tags(strtoupper($args['id'])));
        if (empty($countryId)) {
            return $this->get('errorResponder')->get(
                400,
                'You provided an invalid country id.',
                $args['format'],
                'Bad Request',
                $response
            );
        }
        try {
            $country = new Country(['id' => $countryId]);
            $country->findById();
            $statement = $this->get('db')->prepare($country->preparedStatement);
            $statement->execute($country->preparedVariables);
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (empty($data)) {
                return $this->get('errorResponder')->get(
                    404,
                    'The country does not exist for the given id.',
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
        if ($args['format'] === 'json') {
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode($data));
        } else {
            return $response
                ->withHeader('Content-type', 'text/xml')
                ->write(arrayToXML($data, "countries", "country"));
        }
    }
);

// phpcs:disable Generic.Files.LineLength
/**
 * @OA\Get(
 *     path="/countries.{format}",
 *     summary="Find all countries that match your filter criteria.",
 *     description="Retrieve a list of Countries that match your filter settings.",
 *     @OA\Parameter(
 *         name="api_key",
 *         description="Your Joshua Project API key.",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="bible_complete",
 *         description="A dashed seperated range specifying the minimum and maximum total number of Primary Languages (CntPrimaryLanguages) in the country that have a Bible Status of <strong>Complete Bible</strong>.(min-max) You can supply just the minimum to get a total matching that number.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="bible_new_testament",
 *         description="A dashed seperated range specifying the minimum and maximum total number of Primary Languages (CntPrimaryLanguages) in the country that have a Bible Status of <strong>New Testament</strong>.(min-max) You can supply just the minimum to get a total matching that number.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="bible_portions",
 *         description="A dashed seperated range specifying the minimum and maximum total number of Primary Languages (CntPrimaryLanguages) in the country that have a Bible Status of <strong>Portions</strong>.(min-max) You can supply just the minimum to get a total matching that number.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="continents",
 *         description="A bar separated list of one or more continents to filter by.Use the following codes:<br><ul><li>AFR - Africa</li><li>ASI - Asia</li><li>AUS - Australia</li><li>EUR - Europe</li><li>NAR - North America</li><li>SOP - Oceania (South Pacific)</li><li>LAM - South America</li></ul>",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="cnt_primary_languages",
 *         description="A dashed seperated range specifying the minimum and maximum total number of primary languages.(min-max) You can supply just the minimum to get countries with a total number of primary languages matching that number.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="ids",
 *         description="A bar separated list of one or more FIPS 10-4 Letter Country Codes to filter by. See <a href='https://goo.gl/yYWY4J' target='_blank'>https://goo.gl/yYWY4J</a>.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="jpscale",
 *         description="A bar separated list of one or more JPScale codes to filter by. Only accepts the following codes: 1, 2, 3, 4, 5.  For more information check out <a href='https://joshuaproject.net/global_list/progress' target='_blank'>https://joshuaproject.net/global_list/progress</a>.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         description="The maximum results to return. (Defaults to 250)",
 *         in="query",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             default=250
 *        )
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         description="The page of results to display  (Defaults to 1)",
 *         in="query",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             default=1
 *        )
 *     ),
 *     @OA\Parameter(
 *         name="pc_buddhist",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Buddhist.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_christianity",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Christians.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_ethnic_religion",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Ethnic Religions.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_evangelical",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Evangelicals.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_hindu",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Hindus.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_islam",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Islam.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_non_religious",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Non-Religious.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_other_religion",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Other Religions.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_unknown",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Unkown Religions.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="population",
 *         description="A dashed seperated range specifying the minimum and maximum population.(min-max) You can supply just the minimum to get Countries matching that number.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pop_in_frontier",
 *         description="A dashed seperated range specifying the minimum and maximum population living among frontier people groups.(min-max) You can supply just the minimum to get Countries matching that number.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pop_in_unreached",
 *         description="A dashed seperated range specifying the minimum and maximum population living among the unreached.(min-max) You can supply just the minimum to get Countries matching that number.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="primary_languages",
 *         description="A bar seperated list of ISO 3 Letter Codes.  For more information check out <a href='http://www.loc.gov/standards/iso639-2/php/code_list.php' target='_blank'>http://www.loc.gov/standards/iso639-2/php/code_list.php</a>.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="primary_religions",
 *         description="A bar separated list of one or more primary religions to filter by. Use the following numbers:<br><ul><li>1 - Christianity</li><li>2 - Buddhism</li><li>4 - Ethnic Religions</li><li>5 - Hinduism</li><li>6 - Islam</li><li>7 - Non-Religious</li><li>8 - Other/Small</li><li>9 - Unknown</li></ul>",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="regions",
 *         description="A bar separated list of one or more regions to filter by. Use the following numbers:<br><ul><li>1 - South Pacific</li><li>2 - Southeast Asia</li><li>3 - Northeast Asia</li><li>4 - South Asia</li><li>5 - Central Asia</li><li>6 - Middle East and North Africa</li><li>7 - East and Southern Africa</li><li>8 - West and Central Africa</li><li>9 - Eastern Europe and Eurasia</li><li>10 - Western Europe</li><li>11 - Central and South America</li><li>12 - North America and Caribbean</li></ul>",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="translation_needed",
 *         description="A dashed seperated range specifying the minimum and maximum total number of Primary Languages (CntPrimaryLanguages) in the country that have a Bible Status of <strong>Translation Needed</strong>.(min-max) You can supply just the minimum to get a total matching that number.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="translation_started",
 *         description="A dashed seperated range specifying the minimum and maximum total number of Primary Languages (CntPrimaryLanguages) in the country that have a Bible Status of <strong>Translation Started</strong>.(min-max) You can supply just the minimum to get a total matching that number.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="translation_unspecified",
 *         description="A dashed seperated range specifying the minimum and maximum total number of Primary Languages (CntPrimaryLanguages) in the country that have a Bible Status of <strong>Unspecified</strong>.(min-max) You can supply just the minimum to get a total matching that number.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="window1040",
 *         description="A single letter (y or n) representing a boolean that states whether you want Countries in the 1040 Window.",
 *         in="query",
 *         required=false,
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
    "/{version}/countries.{format}",
    function (Request $request, Response $response, $args = []): Response {
        $noLongerSupportedParams = [
            'pc_anglican', 'pc_independent', 'pc_protestant', 'pc_orthodox', 'pc_rcatholic',
            'pc_other_christian'
        ];
        $params = $request->getQueryParams();
        $requestKeys = array_keys($params);
        $check = array_intersect($requestKeys, $noLongerSupportedParams);
        if (!empty($check)) {
            $unsupported = join(', ', $check);
            return $this->get('errorResponder')->get(
                400,
                'Sorry, these parameters are no longer supported: ' . $unsupported,
                $args['format'],
                'Bad Request',
                $response
            );
        }
        try {
            $country = new Country($params);
            $country->findAllWithFilters();
            $statement = $this->get('db')->prepare($country->preparedStatement);
            $statement->execute($country->preparedVariables);
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
        if ($args['format'] === 'json') {
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode($data));
        } else {
            return $response
                ->withHeader('Content-type', 'text/xml')
                ->write(arrayToXML($data, "countries", "country"));
        }
    }
);
