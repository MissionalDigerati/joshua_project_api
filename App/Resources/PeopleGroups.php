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
/**
 * Get the daily unreached people group for today.  You can specify a specific date using the following parameters
 * as GET vars.
 * month - two digit month
 * day - two digit day
 * 
 * For example, /people_groups/daily_unreached.json?month=01&day=31 will get the people group for Jan. 31st.
 *
 * GET /people_groups/daily_unreached
 * Available Formats JSON & XML
 * 
 * @api
 * @author Johnathan Pulos
 */
$app->get(
    "/people_groups/daily_unreached.:format",
    function ($format) use ($app, $db, $appRequest) {
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
            $app->render("/errors/400." . $format . ".php");
        }
        if (empty($data)) {
            $app->render("/errors/404." . $format . ".php");
            exit;
        }
        /**
         * Rename the 10_40Window to a XML friendly tagname
         *
         * @author Johnathan Pulos
         */
        foreach ($data as $key => $val) {
            $data[$key]['Window10_40'] = $val['10_40Window'];
            unset($data[$key]['10_40Window']);
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
 * Get the details of a specific People Group.  You can either 1) get a summary of countries that these People Groups live in, or
 * 2) Get the details of a people group in a specific country.  If you do not supply a country param,  you will get the summary.  You
 * must specify the id (PeopleID3), or else you will get an error.  You can also specify the ISO two letter country code, to designate 
 * the specific country you would like information about.
 * @link http://www.joshuaproject.net/global-countries.php
 * 
 * GET /people_groups/[ID]
 * 
 * Available Formats JSON & XML
 *
 * @api
 * @author Johnathan Pulos
 */
$app->get(
    "/people_groups/:id\.:format",
    function ($id, $format) use ($app, $db, $appRequest, $useCaching, $cache) {
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
                    if ($useCaching === true) {
                        /**
                         * Set the data to the cache using it's cache key, and expire it in 1 day
                         *
                         * @author Johnathan Pulos
                         */
                        $cache->set($cacheKey, $data, 86400);
                    }
                } catch (Exception $e) {
                    $app->render("/errors/400." . $format . ".php");
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
                    if ($useCaching === true) {
                        /**
                         * Set the data to the cache using it's cache key, and expire it in 1 day
                         *
                         * @author Johnathan Pulos
                         */
                        $cache->set($cacheKey, $data, 86400);
                    }
                } catch (Exception $e) {
                    $app->render("/errors/400." . $format . ".php");
                }
            }
        }
        if (empty($data)) {
            $app->render("/errors/404." . $format . ".php");
            exit;
        }
        /**
         * Rename the 10_40Window to a XML friendly tagname
         *
         * @author Johnathan Pulos
         */
        foreach ($data as $key => $val) {
            $data[$key]['Window10_40'] = $val['10_40Window'];
            unset($data[$key]['10_40Window']);
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
 * Get all People Groups with filtering.  Available Filters:
 * 
 * 
 * GET /people_groups
 * 
 * Available Formats JSON & XML
 *
 * @api
 * @author Johnathan Pulos
 */
$app->get(
    "/people_groups\.:format",
    function ($format) use ($app, $db, $appRequest, $useCaching, $cache) {
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
                if ($useCaching === true) {
                    /**
                     * Set the data to the cache using it's cache key, and expire it in 1 day
                     *
                     * @author Johnathan Pulos
                     */
                    $cache->set($cacheKey, $data, 86400);
                }
            } catch (Exception $e) {
                $app->render("/errors/400." . $format . ".php");
            }
        }
        /**
         * Rename the 10_40Window to a XML friendly tagname
         *
         * @author Johnathan Pulos
         */
        foreach ($data as $key => $val) {
            $data[$key]['Window10_40'] = $val['10_40Window'];
            unset($data[$key]['10_40Window']);
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
