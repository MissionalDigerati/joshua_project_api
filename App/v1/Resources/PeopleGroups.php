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

use QueryGenerators\PeopleGroup;
use QueryGenerators\ProfileText;
use QueryGenerators\Resource;
use QueryGenerators\Unreached;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OpenApi\Attributes as OA;
use Utilities\StringHelper;

// phpcs:disable Generic.Files.LineLength
/**
 *
 * @OA\Get(
 *     tags={"People Groups in Countries (PGIC)"},
 *     path="/v1/people_groups/daily_unreached.{format}",
 *     summary="Retrieve the Unreached of the Day information (JSON or XML)",
 *     description="Retrieve the daily Unreached of the Day people group information. You have two options when retrieving the Unreached of the Day:
 * Get today's Unreached of the Day. This is the default if you do not send parameters.
 * You can specify the month and day parameter to get a specific day of the year. For example, `/daily_unreached.json?month=01&day=31` will get the people group for Jan. 31st. You must provide both parameters!",
 *     @OA\Parameter(ref="#/components/parameters/APIFormatParameter"),
 *     @OA\Parameter(ref="#/components/parameters/APIKeyParameter"),
 *     @OA\Parameter(
 *         name="day",
 *         description="The two digit day that you want to receive the information from. Defaults to the current day.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="month",
 *         description="The two digit month that you want to receive the information from. Defaults to the current month.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="The unreached people group based on your request.",
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
    "/{version}/people_groups/daily_unreached.{format}",
    function (Request $request, Response $response, $args = []): Response {
        /**
         * Get the given parameters, and clean them
         *
         * @author Johnathan Pulos
         */
        $params = $request->getQueryParams();

        if (array_key_exists('month', $params)) {
            $month = returnPresentOrDefault($params['month'], Date('n'));
        } else {
            $month = Date('n');
        }
        if (array_key_exists('day', $params)) {
            $day = returnPresentOrDefault($params['day'], Date('j'));
        } else {
            $day = Date('j');
        }
        try {
            $peopleGroup = new Unreached(
                [
                    'month' => $month,
                    'day'   => $day
                ]
            );
            $peopleGroup->daily();
            $statement = $this->get('db')->prepare($peopleGroup->preparedStatement);
            $statement->execute($peopleGroup->preparedVariables);
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (empty($data)) {
                return $this->get('errorResponder')->get(
                    404,
                    'The people group does not exist for the given month and day.',
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
         * Get the ProfileText (Summary) for each of the people group
         *
         * @return void
         * @author Johnathan Pulos
         */
        foreach ($data as $key => $peopleGroupData) {
            try {
                $profileText = new ProfileText(
                    [
                        'id'        => $peopleGroupData['PeopleID3'],
                        'country'   => $peopleGroupData['ROG3'],
                        'format'    => 'M'
                    ]
                );
                $profileText->findAllByIdAndCountry();
                $statement = $this->get('db')->prepare($profileText->preparedStatement);
                $statement->execute($profileText->preparedVariables);
                $profileData = $statement->fetch(PDO::FETCH_ASSOC);
                if (!$profileData) {
                    throw new Exception('No profile data available.');
                }
                $data[$key]['Summary'] = StringHelper::nullToEmpty($profileData['Summary']);
                $data[$key]['Obstacles'] = StringHelper::nullToEmpty($profileData['Obstacles']);
                $data[$key]['HowReach'] = StringHelper::nullToEmpty($profileData['HowReach']);
                $data[$key]['PrayForChurch'] = StringHelper::nullToEmpty($profileData['PrayForChurch']);
                $data[$key]['PrayForPG'] = StringHelper::nullToEmpty($profileData['PrayForPG']);
            } catch (Exception $e) {
                $data[$key]['Summary'] = '';
                $data[$key]['Obstacles'] = '';
                $data[$key]['HowReach'] = '';
                $data[$key]['PrayForChurch'] = '';
                $data[$key]['PrayForPG'] = '';
            }
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
// phpcs:disable Generic.Files.LineLength
/**
 *
 * @OA\Get(
 *     tags={"People Groups in Countries (PGIC)"},
 *     path="/v1/people_groups/{id}.{format}",
 *     summary="Retrieve the details of a specific people group (JSON or XML)",
 *     description="Retrieve the details of a specific people group around the world or in a specific country.  You can either:
 * Get a summary of all occurances of the people group by supplying the PeopleGroup.id (PeopleID3) only, or
 * Get the details of a specific people group in a specific country by providing the PeopleGroup.id (PeopleID3) and the country's [2 letter FIPS 10-4 Code](https://goo.gl/yYWY4J).",
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
 *         name="country",
 *         description="The country's 2 letter FIPS 10-4 Code specified at [https://goo.gl/yYWY4J](https://goo.gl/yYWY4J).",
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
    "/{version}/people_groups/{id}.{format}",
    function (Request $request, Response $response, $args = []): Response {
        /**
         * Make sure we have an ID, else crash
         *
         * @author Johnathan Pulos
         */
        $peopleId = intval(strip_tags($args['id']));
        $params = $request->getQueryParams();
        if (empty($peopleId)) {
            return $this->get('errorResponder')->get(
                400,
                'You provided an invalid PeopleID3.',
                $args['format'],
                'Bad Request',
                $response
            );
        }
        $country = (array_key_exists('country', $params)) ? $params['country'] : null;
        try {
            if ($country) {
                /**
                 * Get the people group in a specific country
                 *
                 * @author Johnathan Pulos
                 */
                $peopleGroup = new PeopleGroup(['id' => $peopleId, 'country' => $country]);
                $peopleGroup->findByIdAndCountry();
            } else {
                /**
                 * Get all the countries the people group exists in, and some basic stats
                 *
                 * @author Johnathan Pulos
                 */
                $peopleGroup = new PeopleGroup(['id' => $peopleId]);
                $peopleGroup->findById();
            }
            $statement = $this->get('db')->prepare($peopleGroup->preparedStatement);
            $statement->execute($peopleGroup->preparedVariables);
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (empty($data)) {
                return $this->get('errorResponder')->get(
                    404,
                    'The people group does not exist for the given PeopleID3/country.',
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
         * Get the ProfileText for each of the people group
         *
         * @return void
         * @author Johnathan Pulos
         */
        foreach ($data as $key => $peopleGroupData) {
            try {
                $profileText = new ProfileText(
                    [
                        'id' => $peopleGroupData['PeopleID3'],
                        'country' => $peopleGroupData['ROG3'],
                        'format'    => 'M'
                    ]
                );
                $profileText->findAllByIdAndCountry();
                $statement = $this->get('db')->prepare($profileText->preparedStatement);
                $statement->execute($profileText->preparedVariables);
                $profileData = $statement->fetch(PDO::FETCH_ASSOC);
                if (!$profileData) {
                    throw new Exception('No profile data available.');
                }
                $data[$key]['Summary'] = StringHelper::nullToEmpty($profileData['Summary']);
                $data[$key]['Obstacles'] = StringHelper::nullToEmpty($profileData['Obstacles']);
                $data[$key]['HowReach'] = StringHelper::nullToEmpty($profileData['HowReach']);
                $data[$key]['PrayForChurch'] = StringHelper::nullToEmpty($profileData['PrayForChurch']);
                $data[$key]['PrayForPG'] = StringHelper::nullToEmpty($profileData['PrayForPG']);
            } catch (Exception $e) {
                $data[$key]['Summary'] = '';
                $data[$key]['Obstacles'] = '';
                $data[$key]['HowReach'] = '';
                $data[$key]['PrayForChurch'] = '';
                $data[$key]['PrayForPG'] = '';
            }
            try {
                $resource = new Resource(['id' => $peopleGroupData['ROL3']]);
                $resource->findAllByLanguageId();
                $statement = $this->get('db')->prepare($resource->preparedStatement);
                $statement->execute($resource->preparedVariables);
                $data[$key]['Resources'] = $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $data[$key]['Resources'] = [];
            }
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
// phpcs:disable Generic.Files.LineLength
/**
 *
 * @OA\Get(
 *     tags={"People Groups in Countries (PGIC)"},
 *     path="/v1/people_groups.{format}",
 *     summary="Search all people groups with diverse filters (JSON or XML)",
 *     description="Find all people groups that match your filter criteria.",
 *     @OA\Parameter(ref="#/components/parameters/APIFormatParameter"),
 *     @OA\Parameter(ref="#/components/parameters/APIKeyParameter"),
 *     @OA\Parameter(
 *         name="bible_status",
 *         description="A bar separated list of one or more BibleStatus levels. Only accepts the following codes: 0, 1, 2, 3, 4, 5.  The statuses are:
 * **0** = Unspecified
 * **1** = Translation needed
 * **2** = Translation started
 * **3** = Portions
 * **4** = New Testament
 * **5** = Complete Bible",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="continents",
 *         description="A bar separated list of one or more continents to filter by. Use the following codes:
 * **AFR** = Africa
 * **ASI** = Asia
 * **AUS** = Australia
 * **EUR** = Europe
 * **NAR** = North America
 * **SOP** = Oceania (South Pacific)
 * **LAM** = South America",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="countries",
 *         description="A bar separated list of one or more countries to filter by. Use the 2 letter FIPS 10-4 code. [View all Country Codes](https://goo.gl/yYWY4J).",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="has_audio",
 *         description="A boolean (represented as a string Y or N) that states whether you want people groups that have access to an audio recorded Bible.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="has_jesus_film",
 *         description="A boolean (represented as a string Y or N) that states whether you want people groups that have access to the Jesus Film.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="include_profile_text",
 *         description="A boolean (represented as a string Y or N) that states whether you want to include the people group's profile text. (HowReach, Obstacles, PrayForChurch, PrayForPG, Summary) By setting to 'N', it could speed up the request.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             default="Y"
 *        )
 *     ),
 *     @OA\Parameter(
 *         name="include_resources",
 *         description="A boolean (represented as a string Y or N) that states whether you want to include the people group's resources. By setting to 'N', it could speed up the request.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             default="Y"
 *        )
 *     ),
 *     @OA\Parameter(
 *         name="indigenous",
 *         description="A boolean (represented as a string Y or N) that states whether you want people groups that are indigenous.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="is_frontier",
 *         description="A boolean (represented as a string Y or N) that states whether you want people groups that are frontier people groups.",
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
 *         description="A boolean (represented as a string Y or N) that states whether you want people groups that are least reached.",
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
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Adherents.(min-max) You can supply just the minimum to get people groups matching that percentage.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_buddhist",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Buddhist.(min-max) You can supply just the minimum to get people groups matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_ethnic_religion",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Ethnic Religions.(min-max) You can supply just the minimum to get people groups matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_evangelical",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Evangelicals.(min-max) You can supply just the minimum to get people groups matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_hindu",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Hindus.(min-max) You can supply just the minimum to get people groups matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_islam",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Islam.(min-max) You can supply just the minimum to get people groups matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_non_religious",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Non-Religious.(min-max) You can supply just the minimum to get people groups matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_other_religion",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Other Religions.(min-max) You can supply just the minimum to get people groups matching that percentage. Decimals accepted!",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pc_unknown",
 *         description="A dashed seperated range specifying the minimum and maximum percentage of Unkown Religions.(min-max) You can supply just the minimum to get people groups matching that percentage. Decimals accepted!",
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
 *         description="A bar separated list of one or more Joshua Project people group codes to filter by.",
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
 *         name="population_pgac",
 *         description="A dashed seperated range specifying the minimum and maximum population for the people group in all countries (PGAC).(min-max) You can supply just the minimum to get people groups with a population matching that number.",
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
 *         name="regions",
 *         description="A bar separated list of one or more regions to filter by. Use the following numbers:
 * **1** = South Pacific
 * **2** = Southeast Asia
 * **3** = Northeast Asia
 * **4** = South Asia
 * **5** = Central Asia
 * **6** = Middle East and North Africa
 * **7** = East and Southern Africa
 * **8** = West and Central Africa
 * **9** = Eastern Europe and Eurasia
 * **10** = Western Europe
 * **11** = Central and South America
 * **12** = North America and Caribbean",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="rop1",
 *         description="A bar separated list of one or more Registry of People affinity block codes to filter by.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="rop2",
 *         description="A bar separated list of one or more Registry of People people cluster codes to filter by.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="rop3",
 *         description="A bar separated list of one or more Registry of People people group codes to filter by.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="window1040",
 *         description="A boolean (represented as a string Y or N) that states whether you want people groups in the 1040 Window.",
 *         in="query",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="The list of people groups based off your criteria.",
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
    "/{version}/people_groups.{format}",
    function (Request $request, Response $response, $args = []): Response {
        $noLongerSupportedParams = [
            'pc_anglican', 'pc_independent', 'pc_protestant', 'pc_orthodox', 'pc_rcatholic',
            'pc_other_christian', 'unengaged'
        ];
        $params = $request->getQueryParams();
        $requestKeys = array_keys($params);
        $check = array_intersect($requestKeys, $noLongerSupportedParams);
        if (!empty($check)) {
            $unsupported = join(', ', $check);
            return $this->get('errorResponder')->get(
                400,
                "Sorry, these parameters are no longer supported: " . $unsupported,
                $args['format'],
                'Bad Request',
                $response
            );
        }
        try {
            $peopleGroup = new PeopleGroup($params);
            $peopleGroup->findAllWithFilters();
            $statement = $this->get('db')->prepare($peopleGroup->preparedStatement);
            $statement->execute($peopleGroup->preparedVariables);
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
         * Get the ProfileText for each of the people group
         *
         * @return void
         * @author Johnathan Pulos
         */
        $includeProfileText = suppliedParamAsBoolean($params, 'include_profile_text', true);
        $includeResources = suppliedParamAsBoolean($params, 'include_resources', true);
        if ($includeProfileText || $includeResources) {
            foreach ($data as $key => $peopleGroupData) {
                if ($includeProfileText) {
                    try {
                        $profileText = new ProfileText(
                            [
                                'id' => $peopleGroupData['PeopleID3'],
                                'country' => $peopleGroupData['ROG3'],
                                'format'    => 'M'
                            ]
                        );
                        $profileText->findAllByIdAndCountry();
                        $statement = $this->get('db')->prepare($profileText->preparedStatement);
                        $statement->execute($profileText->preparedVariables);
                        $profileData = $statement->fetch(PDO::FETCH_ASSOC);
                        if (!$profileData) {
                            throw new Exception('No profile data available.');
                        }
                        $data[$key]['Summary'] = StringHelper::nullToEmpty($profileData['Summary']);
                        $data[$key]['Obstacles'] = StringHelper::nullToEmpty($profileData['Obstacles']);
                        $data[$key]['HowReach'] = StringHelper::nullToEmpty($profileData['HowReach']);
                        $data[$key]['PrayForChurch'] = StringHelper::nullToEmpty($profileData['PrayForChurch']);
                        $data[$key]['PrayForPG'] = StringHelper::nullToEmpty($profileData['PrayForPG']);
                    } catch (Exception $e) {
                        $data[$key]['Summary'] = '';
                        $data[$key]['Obstacles'] = '';
                        $data[$key]['HowReach'] = '';
                        $data[$key]['PrayForChurch'] = '';
                        $data[$key]['PrayForPG'] = '';
                    }
                }
                if ($includeResources) {
                    try {
                        $resource = new Resource(['id' => $peopleGroupData['ROL3']]);
                        $resource->findAllByLanguageId();
                        $statement = $this->get('db')->prepare($resource->preparedStatement);
                        $statement->execute($resource->preparedVariables);
                        $data[$key]['Resources'] = $statement->fetchAll(PDO::FETCH_ASSOC);
                    } catch (Exception $e) {
                        $data[$key]['Resources'] = [];
                    }
                }
            }
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
