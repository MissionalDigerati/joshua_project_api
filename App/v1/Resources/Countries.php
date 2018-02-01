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
use Swagger\Annotations as SWG;

/**
 * @SWG\Resource(
 *     apiVersion="1",
 *     swaggerVersion="1.1",
 *     resourcePath="/countries",
 *     basePath="https://api.joshuaproject.net/v1"
 * )
 */
/**
  *
  * @SWG\API(
  *  path="/countries/{id}.{format}",
  *  description="Retrieve the details of a specific Country.",
  *  @SWG\Operations(
  *      @SWG\Operation(
  *          httpMethod="GET",
  *          nickname="countryShow",
  *          summary="Retrieve the details of a specific Country (JSON or XML)",
  *          notes="Retrieve the details of a specific Country by supplying the country's <a href='https://goo.gl/yYWY4J' target='_blank'>2 letter FIPS 10-4 Code</a> (id).",
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
  *                  description="The 2 letter FIPS 10-4 Country Code for the Country you want to view. [<a href='https://goo.gl/yYWY4J' target='_blank'>View all Country Codes</a>]",
  *                  paramType="path",
  *                  required="true",
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
    "/:version/countries/:id\.:format",
    function ($version, $id, $format) use ($app, $db, $appRequest, $useCaching, $cache) {
        $data = array();
        $gotCachedData = false;
        /**
         * Make sure we have an ID, else crash
         *
         * @author Johnathan Pulos
         */
        $countryId = preg_replace("/\PL/u", "", strip_tags($id));
        if (empty($countryId)) {
            $app->render("/errors/404." . $format . ".php");
            exit;
        }
        if ($useCaching === true) {
            /**
             * Check the cache
             *
             * @author Johnathan Pulos
             */
            $cacheKey = md5("CountryShowId_".$countryId);
            $data = $cache->get($cacheKey);
            if ((is_array($data)) && (!empty($data))) {
                $gotCachedData = true;
            }
        }
        if (empty($data)) {
            try {
                $country = new \QueryGenerators\Country(array('id' => $countryId));
                $country->findById();
                $statement = $db->prepare($country->preparedStatement);
                $statement->execute($country->preparedVariables);
                $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $app->render("/errors/400." . $format . ".php", array('details' => $e->getMessage()));
                exit;
            }
        }
        if (($useCaching === true) && ($gotCachedData === false)) {
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
            echo arrayToXML($data, "countries", "country");
        }
    }
);
/**
 *
 * @SWG\API(
 *  path="/countries.{format}",
 *  description="Find all Countries that match your filter criteria.",
 *  @SWG\Operations(
 *      @SWG\Operation(
 *          httpMethod="GET",
 *          nickname="getAllCountryWithFilters",
 *          summary="Search all Countries with diverse filters (JSON or XML)",
 *          notes="Retrieve a list of Countries that match your filter settings.",
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
 *                  name="continents",
 *                  description="A bar separated list of one or more continents to filter by.Use the following codes:<br><ul><li>AFR - Africa</li><li>ASI - Asia</li><li>AUS - Australia</li><li>EUR - Europe</li><li>NAR - North America</li><li>SOP - Oceania (South Pacific)</li><li>LAM - South America</li></ul>",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="ids",
 *                  description="A bar separated list of one or more FIPS 10-4 Letter Country Codes to filter by. See <a href='https://goo.gl/yYWY4J' target='_blank'>https://goo.gl/yYWY4J</a>.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="jpscale",
 *                  description="A bar separated list of one or more JPScale codes to filter by. Only accepts the following codes: 1, 2, 3, 4, 5.  For more information check out <a href='https://joshuaproject.net/global_list/progress' target='_blank'>https://joshuaproject.net/global_list/progress</a>.",
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
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_anglican",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Anglicans.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_buddhist",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Buddhist.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_christianity",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Christians.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_ethnic_religion",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Ethnic Religions.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_evangelical",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Evangelicals.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_hindu",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Hindus.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_independent",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Independents.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_islam",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Islam.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_non_religious",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Non-Religious.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_other_religion",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Other Religions.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_orthodox",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Orthodox.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_other_christian",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Other Christian Denominations.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_protestant",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Protestants.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_rcatholic",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Roman Catholic.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="pc_unknown",
 *                  description="A dashed seperated range specifying the minimum and maximum percentage of Unkown Religions.(min-max) You can supply just the minimum to get Countries matching that percentage. Decimals accepted!",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="population",
 *                  description="A dashed seperated range specifying the minimum and maximum population.(min-max) You can supply just the minimum to get Countries matching that number.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="primary_languages",
 *                  description="A bar seperated list of ISO 3 Letter Codes.  For more information check out <a href='http://www.loc.gov/standards/iso639-2/php/code_list.php' target='_blank'>http://www.loc.gov/standards/iso639-2/php/code_list.php</a>.",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="primary_religions",
 *                  description="A bar separated list of one or more primary religions to filter by. Use the following numbers:<br><ul><li>1 - Christianity</li><li>2 - Buddhism</li><li>4 - Ethnic Religions</li><li>5 - Hinduism</li><li>6 - Islam</li><li>7 - Non-Religious</li><li>8 - Other/Small</li><li>9 - Unknown</li></ul>",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="regions",
 *                  description="A bar separated list of one or more regions to filter by. Use the following numbers:<br><ul><li>1 - South Pacific</li><li>2 - Southeast Asia</li><li>3 - Northeast Asia</li><li>4 - South Asia</li><li>5 - Central Asia</li><li>6 - Middle East and North Africa</li><li>7 - East and Southern Africa</li><li>8 - West and Central Africa</li><li>9 - Eastern Europe and Eurasia</li><li>10 - Western Europe</li><li>11 - Central and South America</li><li>12 - North America and Caribbean</li></ul>",
 *                  paramType="query",
 *                  required="false",
 *                  allowMultiple="false",
 *                  dataType="string"
 *              ),
 *              @SWG\Parameter(
 *                  name="window1040",
 *                  description="A boolean that states whether you want Countries in the 1040 Window. (y or n)",
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
    "/:version/countries\.:format",
    function ($version, $format) use ($app, $db, $appRequest, $useCaching, $cache) {
        $data = array();
        $gotCachedData = false;
        if ($useCaching === true) {
            /**
             * Check the cache
             *
             * @author Johnathan Pulos
             */
            $cacheKey = md5("CountryIndex");
            $data = $cache->get($cacheKey);
            if ((is_array($data)) && (!empty($data))) {
                $gotCachedData = true;
            }
        }
        if (empty($data)) {
            try {
                $country = new \QueryGenerators\Country($appRequest->params());
                $country->findAllWithFilters();
                $statement = $db->prepare($country->preparedStatement);
                $statement->execute($country->preparedVariables);
                $data = $statement->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $app->render("/errors/400." . $format . ".php", array('details' => $e->getMessage()));
                exit;
            }
        }
        if (($useCaching === true) && ($gotCachedData === false)) {
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
            echo arrayToXML($data, "countries", "country");
        }
    }
);
