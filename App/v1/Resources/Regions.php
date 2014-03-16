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
 *     resourcePath="/regions",
 *     basePath="http://jpapi.codingstudio.org/v1"
 * )
 */
$app->get(
    "/:version/regions/:id\.:format",
    function ($version, $id, $format) use ($app, $db, $appRequest, $useCaching, $cache) {
        $data = array('test' => array('goober' => true));
        $gotCachedData = false;
        if ($useCaching === true) {
            /**
             * Check the cache
             *
             * @author Johnathan Pulos
             */
            $cacheKey = md5("RegionShowId_".$id);
            $data = $cache->get($cacheKey);
            if ((is_array($data)) && (!empty($data))) {
                $gotCachedData = true;
            }
        }
        if (empty($data)) {
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
