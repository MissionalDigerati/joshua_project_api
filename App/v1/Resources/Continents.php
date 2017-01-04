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
 *     resourcePath="/continents",
 *     basePath="http://api.joshuaproject.net/v1"
 * )
 */
/**
  *
  * @SWG\API(
  *  path="/continents/{id}.{format}",
  *  description="Retrieve the details of a specific Continent.",
  *  @SWG\Operations(
  *      @SWG\Operation(
  *          httpMethod="GET",
  *          nickname="continentShow",
  *          summary="Retrieve the details of a specific Continent (JSON or XML)",
  *          notes="Retrieve the details of a specific Continent by supplying a three letter ISO Continent code (id).  Use the following codes:<br><ul><li>AFR - Africa</li><li>ASI  - Asia</li><li>AUS - Australia</li><li>EUR - Europe</li><li>NAR - North America</li><li>SOP - Oceania (South Pacific)</li><li>LAM - South America</li></ul>",
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
  *                  description="The 3 letter ISO Continent Code for the Continent you want to view. Use the codes indicated above.",
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
    "/:version/continents/:id\.:format",
    function ($version, $id, $format) use ($app, $db, $appRequest, $useCaching, $cache) {
        $data = array();
        $gotCachedData = false;
        /**
         * Make sure we have an ID, else crash
         *
         * @author Johnathan Pulos
         */
        $continentId = preg_replace("/\PL/u", "", strip_tags($id));
        if ((empty($continentId)) || (strlen($continentId) != 3)) {
            $app->render("/errors/404." . $format . ".php");
            exit;
        }
        if ($useCaching === true) {
            /**
             * Check the cache
             *
             * @author Johnathan Pulos
             */
            $cacheKey = md5("ContinentShowId_".$languageId);
            $data = $cache->get($cacheKey);
            if ((is_array($data)) && (!empty($data))) {
                $gotCachedData = true;
            }
        }
        if (empty($data)) {
            try {
                $continent = new \QueryGenerators\Continent(array('id' => $continentId));
                $continent->findById();
                $statement = $db->prepare($continent->preparedStatement);
                $statement->execute($continent->preparedVariables);
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
            echo arrayToXML($data, "continents", "continent");
        }
    }
);
