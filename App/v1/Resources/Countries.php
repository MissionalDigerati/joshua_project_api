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
