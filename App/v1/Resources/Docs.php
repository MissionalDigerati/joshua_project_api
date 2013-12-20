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
 /**
 * Get the getting_started tutorial page
 *
 * GET /getting_started
 * Available Formats HTML
 * 
 * @author Johnathan Pulos
 */
$app->get(
    "/:version/docs/column_descriptions/countries",
    function ($version) use ($app, $db, $appRequest, $PUBLIC_DIRECTORY) {
        $app->render('Docs/ColumnDescriptions/countries.html.php', array('PUBLIC_DIRECTORY' => $PUBLIC_DIRECTORY));
    }
);
