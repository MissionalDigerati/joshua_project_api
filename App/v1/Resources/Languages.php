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
 *     resourcePath="/languages",
 *     basePath="http://jpapi.codingstudio.org/v1"
 * )
 */
/**
  * 
  * @SWG\API(
  *  path="/languages/{id}.{format}",
  *  description="Retrieve the details of a specific Language.",
  *  @SWG\Operations(
  *      @SWG\Operation(
  *          httpMethod="GET",
  *          nickname="languageShow",
  *          summary="Retrieve the details of a specific Language (JSON or XML)",
  *          notes="Retrieve the details of a specific Language by supplying the language's 3 letter ISO 639-2 Code (id).",
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
  *                  description="The 3 letter ISO 639-2 Language Code for the Language you want to view. [<a href='http://goo.gl/gbkgo4' target='_blank'>View all Language Codes</a>]",
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
    "/:version/languages/:id\.:format",
    function ($version, $id, $format) use ($app, $db, $appRequest, $useCaching, $cache) {
        $data = array();
        $gotCachedData = false;
        /**
         * Make sure we have an ID, else crash
         * Regex strips numbers from the id
         *
         * @author Johnathan Pulos
         */
        $languageId = preg_replace("/\PL/u", "", strip_tags($id));
        if ((empty($languageId)) || (strlen($languageId) != 3)) {
            $app->render("/errors/404." . $format . ".php");
            exit;
        }
        if ($useCaching === true) {
            /**
             * Check the cache
             *
             * @author Johnathan Pulos
             */
            $cacheKey = md5("LanguageShowId_".$countryId);
            $data = $cache->get($cacheKey);
            if ((is_array($data)) && (!empty($data))) {
                $gotCachedData = true;
            }
        }
        if (empty($data)) {
            try {
                $lang = new \QueryGenerators\Language(array('id' => $languageId));
                $lang->findById();
                $statement = $db->prepare($lang->preparedStatement);
                $statement->execute($lang->preparedVariables);
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
            echo arrayToXML($data, "languages", "language");
        }
    }
);
$app->get(
    "/:version/languages\.:format",
    function ($version, $format) use ($app, $db, $appRequest, $useCaching, $cache) {
        $data = array();
        $gotCachedData = false;
        if ($useCaching === true) {
            /**
             * Check the cache
             *
             * @author Johnathan Pulos
             */
            $cacheKey = md5("LanguageIndexId_".$countryId);
            $data = $cache->get($cacheKey);
            if ((is_array($data)) && (!empty($data))) {
                $gotCachedData = true;
            }
        }
        if (empty($data)) {
            try {
                $lang = new \QueryGenerators\Language($appRequest->params());
                $lang->findAllWithFilters();
                $statement = $db->prepare($lang->preparedStatement);
                $statement->execute($lang->preparedVariables);
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
            echo arrayToXML($data, "languages", "language");
        }
    }
);
