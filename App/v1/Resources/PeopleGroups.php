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
 * @copyright Copyright 2013 Missional Digerati
 * 
 */
use Swagger\Annotations as SWG;
/**
 * @SWG\Resource(
 *     apiVersion="1",
 *     swaggerVersion="1.1",
 *     resourcePath="/people_groups",
 *     basePath="http://jpapi.codingstudio.org/v1"
 * )
 */
/**
 * 
 * @SWG\API(
 *  path="/people_groups/daily_unreached.{format}",
 *  description="Retrieve the Unreached of the Day information.",
 *  @SWG\Operations(
 *      @SWG\Operation(
 *          httpMethod="GET",
 *          nickname="getDailyUnreachedPeopleGroup",
 *          summary="Retrieve the Unreached of the Day information (JSON or XML)",
 *          notes="You have two options when retrieving the Unreached of the Day.  1) Get today's Unreached of the Day.  This is the default if you do not send parameters.
 *          2) You can specify the month and day parameter to get a specific day of the year. For example, /daily_unreached.json?month=01&day=31 will 
 *          get the people group for Jan. 31st.  You must provide both parameters!",
 *          @SWG\Parameters(
 *              @SWG\Parameter(
 *                  name="api_key",
 *                  description="Your Joshua Project API key.",
 *                  paramType="query",
 *                  required="true",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="month",
 *                  description="The two digit month that you want to receive the information from.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="day",
 *                  description="The two digit day that you want to receive the information from.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 * 
 *                  dataType="string"
 *              )
 *          ),
 *          @SWG\ErrorResponses(
 *              @SWG\ErrorResponse(
 *                  code="400",
 *                  reason="Bad request.  Your request is malformed in some way.  Check your supplied parameters."
 *              ),
 *              @SWG\ErrorResponse(
 *                  code="401",
 *                  reason="Unauthorized.  Your missing your API key, or it has been suspended."
 *              ),
 *              @SWG\ErrorResponse(
 *                  code="404",
 *                  reason="Not found.  Your search ended up with no results."
 *              )
 *          )
 *      )
 *  )
 * )
 * 
 */
$app->get(
    "/:version/people_groups/daily_unreached.:format",
    function ($version, $format) use ($app, $db, $appRequest) {
        /**
         * Get the given parameters, and clean them
         *
         * @author Johnathan Pulos
         */
        $month = returnPresentOrDefault($appRequest->params('month'), Date('n'));
        $day = returnPresentOrDefault($appRequest->params('day'), Date('j'));
        try {
            $peopleGroup = new \QueryGenerators\PeopleGroup(array('month' => $month, 'day' => $day));
            $peopleGroup->dailyUnreached();
            $statement = $db->prepare($peopleGroup->preparedStatement);
            $statement->execute($peopleGroup->preparedVariables);
            $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $app->render("/errors/400." . $format . ".php", array('details' => $e->getMessage()));
            exit;
        }
        if (empty($data)) {
            $app->render("/errors/404." . $format . ".php");
            exit;
        }
        /**
         * Get the ProfileText for each of the People Group
         *
         * @return void
         * @author Johnathan Pulos
         */
        foreach ($data as $key => $peopleGroupData) {
            try {
                $profileText = new \QueryGenerators\ProfileText(array('id' => $peopleGroupData['PeopleID3'], 'country' => $peopleGroupData['ROG3']));
                $profileText->findAllByIdAndCountry();
                $statement = $db->prepare($profileText->preparedStatement);
                $statement->execute($profileText->preparedVariables);
                $data[$key]['ProfileText'] = $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $data[$key]['ProfileText'] = '';
            }
        }
        /**
         * Render the final data
         *
         * @author Johnathan Pulos
         */
        if ($format == 'json') {
            echo json_encode($data);
        } else {
            echo arrayToXML($data, "people_groups", "people_group");
        }
    }
);
 /**
  * 
  * @SWG\API(
  *  path="/people_groups/{id}.{format}",
  *  description="Retrieve the details of a People Group around the world or in a specific country.",
  *  @SWG\Operations(
  *      @SWG\Operation(
  *          httpMethod="GET",
  *          nickname="getPeopleGroupByCountry",
  *          summary="Retrieve the details of a specific People Group (JSON or XML)",
  *          notes="Retrieve the details of a specific People Group around the world or in a specific country.  You can either 
  *                 1) get a summary of all occurances of the People Group by supplying the PeopleGroup.id (PeopleID3) only, or 
  *                 2) Get the details of a specific people group in a specific country by providing the PeopleGroup.id (PeopleID3) 
  *                 and the country's 2 letter ISO Code.",
  *          @SWG\Parameters(
  *              @SWG\Parameter(
  *                  name="api_key",
  *                  description="Your Joshua Project API key.",
  *                  paramType="query",
  *                  required="true",
  *                  allowMultiple="false",
  *                  dataType="string"
  *              ),
  *              @SWG\Parameter(
  *                  name="id",
  *                  description="Joshua Project's PeopleID3.",
  *                  paramType="path",
  *                  required="true",
  *                  allowMultiple="false",
  *                  dataType="string"
  *              ),
  *              @SWG\Parameter(
  *                  name="country",
  *                  description="The country's 2 letter ISO Code specified at http://www.joshuaproject.net/global-countries.php.",
  *                  paramType="query",
  *                  required="false",
  *                  allowMultiple="false",
  *                  dataType="string"
  *              )
  *          ),
  *          @SWG\ErrorResponses(
  *              @SWG\ErrorResponse(
  *                  code="400",
  *                  reason="Bad request.  Your request is malformed in some way.  Check your supplied parameters."
  *              ),
  *              @SWG\ErrorResponse(
  *                  code="401",
  *                  reason="Unauthorized.  Your missing your API key, or it has been suspended."
  *              ),
  *              @SWG\ErrorResponse(
  *                  code="404",
  *                  reason="Not found.  Your search ended up with no results."
  *              )
  *          )
  *      )
  *  )
  * )
  * 
  */
$app->get(
    "/:version/people_groups/:id\.:format",
    function ($version, $id, $format) use ($app, $db, $appRequest, $useCaching, $cache) {
        $data = array();
        /**
         * Make sure we have an ID, else crash
         *
         * @author Johnathan Pulos
         */
        $peopleId = intval(strip_tags($id));
        $country = $appRequest->params('country');
        if (empty($peopleId)) {
            $app->render("/errors/404." . $format . ".php");
            exit;
        }
        /**
         * Determine the data we need to return
         *
         * @author Johnathan Pulos
         */
        if ($country) {
            if ($useCaching === true) {
                /**
                 * Check the cache
                 *
                 * @author Johnathan Pulos
                 */
                $cacheKey = md5("PeopleGroupShowId_".$peopleId."_InCountry_".$country);
                $data = $cache->get($cacheKey);
            }
            if (empty($data)) {
                /**
                 * Get the people group in a specific country
                 *
                 * @author Johnathan Pulos
                 */
                try {
                    $peopleGroup = new \QueryGenerators\PeopleGroup(array('id' => $peopleId, 'country' => $country));
                    $peopleGroup->findByIdAndCountry();
                    $statement = $db->prepare($peopleGroup->preparedStatement);
                    $statement->execute($peopleGroup->preparedVariables);
                    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    $app->render("/errors/400." . $format . ".php", array('details' => $e->getMessage()));
                    exit;
                }
                /**
                 * Get the ProfileText for each of the People Group
                 *
                 * @return void
                 * @author Johnathan Pulos
                 */
                foreach ($data as $key => $peopleGroupData) {
                    try {
                        $profileText = new \QueryGenerators\ProfileText(array('id' => $peopleGroupData['PeopleID3'], 'country' => $peopleGroupData['ROG3']));
                        $profileText->findAllByIdAndCountry();
                        $statement = $db->prepare($profileText->preparedStatement);
                        $statement->execute($profileText->preparedVariables);
                        $data[$key]['ProfileText'] = $statement->fetchAll(PDO::FETCH_ASSOC);
                    } catch (Exception $e) {
                        $data[$key]['ProfileText'] = '';
                    }
                }
            }
        } else {
            if ($useCaching === true) {
                $cacheKey = md5("PeopleGroupShowId_".$peopleId);
                $data = $cache->get($cacheKey);
            }
            if (empty($data)) {
                /**
                 * Get all the countries the people group exists in, and some basic stats
                 *
                 * @author Johnathan Pulos
                 */
                try {
                    $peopleGroup = new \QueryGenerators\PeopleGroup(array('id' => $peopleId));
                    $peopleGroup->findById();
                    $statement = $db->prepare($peopleGroup->preparedStatement);
                    $statement->execute($peopleGroup->preparedVariables);
                    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    $app->render("/errors/400." . $format . ".php", array('details' => $e->getMessage()));
                    exit;
                }
                /**
                 * Get the ProfileText for each of the People Group
                 *
                 * @return void
                 * @author Johnathan Pulos
                 */
                foreach ($data as $key => $peopleGroupData) {
                    try {
                        $profileText = new \QueryGenerators\ProfileText(array('id' => $peopleGroupData['PeopleID3'], 'country' => $peopleGroupData['ROG3']));
                        $profileText->findAllByIdAndCountry();
                        $statement = $db->prepare($profileText->preparedStatement);
                        $statement->execute($profileText->preparedVariables);
                        $data[$key]['ProfileText'] = $statement->fetchAll(PDO::FETCH_ASSOC);
                    } catch (Exception $e) {
                        $data[$key]['ProfileText'] = '';
                    }
                }
            }
        }
        if ($useCaching === true) {
            /**
             * Set the data to the cache using it's cache key, and expire it in 1 day
             *
             * @author Johnathan Pulos
             */
            $cache->set($cacheKey, $data, 86400);
        }
        if (empty($data)) {
            $app->render("/errors/404." . $format . ".php");
            exit;
        }
        /**
         * Render the final data
         *
         * @author Johnathan Pulos
         */
        if ($format == 'json') {
            echo json_encode($data);
        } else {
            echo arrayToXML($data, "people_groups", "people_group");
        }
    }
);
/**
 * 
 * @SWG\API(
 *  path="/people_groups.{format}",
 *  description="Find all People Groups that match your filter criteria.",
 *  @SWG\Operations(
 *      @SWG\Operation(
 *          httpMethod="GET",
 *          nickname="getAllPeopleGroupWithFilters",
 *          summary="Search all People Groups with diverse filters (JSON or XML)",
 *          notes="Retrieve a list of People Groups that match your filter settings.",
 *          @SWG\Parameters(
 *              @SWG\Parameter(
 *                  name="api_key",
 *                  description="Your Joshua Project API key.",
 *                  paramType="query",
 *                  required="true",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="people_id1",
 *                  description="A bar separated list of one or more Joshua Project affinity block codes to filter by. See http://www.joshuaproject.net/definitions.php?term=23.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="rop1",
 *                  description="A bar separated list of one or more Registry of People affinity block codes to filter by. See http://www.joshuaproject.net/definitions.php?term=23.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="people_id2",
 *                  description="A bar separated list of one or more Joshua Project people cluster codes to filter by. See http://www.joshuaproject.net/definitions.php?term=23.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="rop2",
 *                  description="A bar separated list of one or more Registry of People people cluster codes to filter by. See http://www.joshuaproject.net/definitions.php?term=23.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="people_id3",
 *                  description="A bar separated list of one or more Joshua Project people group codes to filter by. See http://www.joshuaproject.net/definitions.php?term=23.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="rop3",
 *                  description="A bar separated list of one or more Registry of People people group codes to filter by. See http://www.joshuaproject.net/definitions.php?term=23.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="continents",
 *                  description="A bar separated list of one or more continents to filter by. Use the following 3 letter Continent code: Africa (use AFR), Asia (use ASI), Australia (use AUS), Europe (use EUR), North America (use NAR), Oceania (use SOP), South America (use LAM)",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="regions",
 *                  description="A bar separated list of one or more regions to filter by. Use the following numbers: South Pacific (use 1), Southeast Asia (use 2), Northeast Asia (use 3), South Asia (use 4), Central Asia (use 5), Middle East and North Africa (use 6), East and Southern Africa (use 7), West and Central Africa (Use 8), Eastern Europe and Eurasia (use 9), Western Europe (use 10), Central and South America (use 11), North America and Caribbean (use 12)",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="countries",
 *                  description="A bar separated list of one or more countries to filter by. Use the 2 letter ISO code.  See http://www.joshuaproject.net/global-countries.php for the codes.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="window1040",
 *                  description="A boolean that states whether you want People Groups in the 1040 Window. (y or n)",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="languages",
 *                  description="A bar separated list of one or more language codes to filter by. Use the 3 letter ISO code.  See http://www.loc.gov/standards/iso639-2/php/code_list.php for the codes.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="population",
 *                  description="A dashed seperated range specifying the minimum and maximum population.(min-max) You can supply just the minimum to get People Groups matching that number.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="primary_religions",
 *                  description="A bar separated list of one or more primary religions to filter by. Use the following numbers: Christianity (use 1), Buddhism (use 2), Ethnic Religions (use 4), Hinduism (use 5), Islam (use 6), Non-Religious (use 7), Other/Small (use 8), Unknown (use 9)",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_adherent",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Adherents.(min-max) You can supply just the minimum to get People Groups matching that percentage.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_evangelical",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Evangelicals.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_buddhist",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Buddhist.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_ethnic_religion",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Ethnic Religions.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_hindu",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Hindus.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_islam",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Islam.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_non_religious",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Non-Religious.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_other_religion",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Other Religions.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_unknown",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Unkown Religions.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_anglican",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Anglicans.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_independent",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Independents.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_protestant",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Protestants.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_orthodox",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Orthodox.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_rcatholic",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Roman Catholic.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_other_christian",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Other Christian Denominations.(min-max) You can supply just the minimum to get People Groups matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="indigenous",
 *                  description="A boolean that states whether you want People Groups that are indigenous. (y or n)",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="least_reached",
 *                  description="A boolean that states whether you want People Groups that are least reached. (y or n)",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="unengaged",
 *                  description="A boolean that states whether you want People Groups that are unengaged. (y or n)",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="jpscale",
 *                  description="A bar separated list of one or more JPScale codes to filter by. Only accepts the following codes: 1.1, 1.2, 2.1, 2.2, 3.1, 3.2.  For more information check out http://joshuaproject.net/progress-scale-definition.php.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="limit",
 *                  description="The maximum results to return. (Defaults to 100)",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="page",
 *                  description="The page of results to display  (Defaults to 1)",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              )
 *          ),
 *          @SWG\ErrorResponses(
 *              @SWG\ErrorResponse(
 *                  code="400",
 *                  reason="Bad request.  Your request is malformed in some way.  Check your supplied parameters."
 *              ),
 *              @SWG\ErrorResponse(
 *                  code="401",
 *                  reason="Unauthorized.  Your missing your API key, or it has been suspended."
 *              ),
 *              @SWG\ErrorResponse(
 *                  code="404",
 *                  reason="Not found.  Your search ended up with no results."
 *              )
 *          )
 *      )
 *  )
 * )
 * 
 */
$app->get(
    "/:version/people_groups\.:format",
    function ($version, $format) use ($app, $db, $appRequest, $useCaching, $cache) {
        $data = array();
        if ($useCaching === true) {
            /**
             * Check the cache
             *
             * @author Johnathan Pulos
             */
            $cacheKey = md5("PeopleGroupIndex");
            $data = $cache->get($cacheKey);
        }
        if (empty($data)) {
            try {
                $peopleGroup = new \QueryGenerators\PeopleGroup($appRequest->params());
                $peopleGroup->findAllWithFilters();
                $statement = $db->prepare($peopleGroup->preparedStatement);
                $statement->execute($peopleGroup->preparedVariables);
                $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $app->render("/errors/400." . $format . ".php", array('details' => $e->getMessage()));
                exit;
            }
            /**
             * Get the ProfileText for each of the People Group
             *
             * @return void
             * @author Johnathan Pulos
             */
            foreach ($data as $key => $peopleGroupData) {
                try {
                    $profileText = new \QueryGenerators\ProfileText(array('id' => $peopleGroupData['PeopleID3'], 'country' => $peopleGroupData['ROG3']));
                    $profileText->findAllByIdAndCountry();
                    $statement = $db->prepare($profileText->preparedStatement);
                    $statement->execute($profileText->preparedVariables);
                    $data[$key]['ProfileText'] = $statement->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    $data[$key]['ProfileText'] = '';
                }
            }
        }
        if ($useCaching === true) {
            /**
             * Set the data to the cache using it's cache key, and expire it in 1 day
             *
             * @author Johnathan Pulos
             */
            $cache->set($cacheKey, $data, 86400);
        }
        /**
         * Render the final data
         *
         * @author Johnathan Pulos
         */
        if ($format == 'json') {
            echo json_encode($data);
        } else {
            echo arrayToXML($data, "people_groups", "people_group");
        }
    }
);
