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
 *     resourcePath="/regions",
 *     basePath="/v1"
 * )
 */
/**
  *
  * @SWG\API(
  *  path="/regions/{id}.{format}",
  *  description="Retrieve the details of a specific Region.",
  *  @SWG\Operations(
  *      @SWG\Operation(
  *          httpMethod="GET",
  *          nickname="regionShow",
  *          summary="Retrieve the details of a specific Region (JSON or XML)",
  *          notes="Retrieve the details of a specific Region by supplying a unique id for the region.  Use the following numbers:<br><ul><li>1 - South Pacific</li><li>2 - Southeast Asia</li><li>3 - Northeast Asia</li><li>4 - South Asia</li><li>5 - Central Asia</li><li>6 - Middle East and North Africa</li><li>7 - East and Southern Africa</li><li>8 - West and Central Africa</li><li>9 - Eastern Europe and Eurasia</li><li>10 - Western Europe</li><li>11 - Central and South America</li><li>12 - North America and Caribbean</li></ul>",
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
  *                  description="The unique id for the region. Use the codes indicated above.",
  *                  paramType="path",
  *                  required="true",
  *                  allowMultiple="false",
  *                  dataType="int"
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
    "/:version/regions/:id\.:format",
    function ($version, $id, $format) use ($app, $db, $appRequest, $useCaching, $cache) {
        $data = array();
        $gotCachedData = false;
        /**
         * Make sure we have an ID, else crash
         *
         * @author Johnathan Pulos
         */
        $regionId = intval(strip_tags($id));
        if ((empty($regionId)) || ($regionId > 12)) {
            $app->render("/errors/404." . $format . ".php");
            exit;
        }
        if ($useCaching === true) {
            /**
             * Check the cache
             *
             * @author Johnathan Pulos
             */
            $cacheKey = md5("RegionShowId_".$regionId);
            $data = $cache->get($cacheKey);
            if ((is_array($data)) && (!empty($data))) {
                $gotCachedData = true;
            }
        }
        if (empty($data)) {
            try {
                $region = new \QueryGenerators\Region(array('id' => $regionId));
                $region->findById();
                $statement = $db->prepare($region->preparedStatement);
                $statement->execute($region->preparedVariables);
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
            echo arrayToXML($data, "regions", "region");
        }
    }
);
