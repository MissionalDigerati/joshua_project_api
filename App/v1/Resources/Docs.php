<?php
/**
 * Joshua Project API - An API for accessing Joshua Project Data.
 *
 * GNU Public License 3.0
 * Copyright (C) 2013  Missional Digerati
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 */
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Get the sample code documentation
 *
 * GET /{version}/docs/sample_code
 * Available Formats HTML
 *
 * @author Johnathan Pulos
 */
$app->get(
    "/{version}/docs/sample_code",
    function (Request $req, Response $res, $args = []) {
        $viewDirectory = $this->view->getTemplatePath();
        return $this->view->render(
            $res,
            'Docs/sample_code.html.php',
            array('viewDirectory' => $viewDirectory)
        );
    }
);
/**
 * Get the sample code documentation
 *
 * GET /{version}/docs/available_api_requests
 * Available Formats HTML
 *
 * @author Johnathan Pulos
 */
$app->get(
    "/{version}/docs/available_api_requests",
    function (Request $req, Response $res, $args = []) {
        $viewDirectory = $this->view->getTemplatePath();
        return $this->view->render(
            $res,
            'Docs/available_api_requests.html.php',
            array('viewDirectory' => $viewDirectory)
        );
    }
);
/**
 * Get the column descriptions for the specified type
 *
 * GET /{version}/docs/column_descriptions/{type}
 * Available Formats HTML
 *
 * @author Johnathan Pulos
 */
$app->get(
    "/{version}/docs/column_descriptions/{type}",
    function (Request $req, Response $res, $args = []) {
        $resourceType = $args['type'];
        $allowed = [
            'continents', 'countries', 'languages', 'people_groups', 'regions'
        ];
        if (!in_array($resourceType, $allowed)) {
            return $res->withStatus(404)
                ->withHeader('Content-Type', 'text/html')
                ->write('Page not found');
        }
        $viewDirectory = $this->view->getTemplatePath();
        return $this->view->render(
            $res,
            'Docs/ColumnDescriptions/' . $resourceType . '.html.php',
            array('viewDirectory' => $viewDirectory)
        );
    }
);
