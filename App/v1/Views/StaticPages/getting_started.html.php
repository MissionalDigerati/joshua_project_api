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
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Joshua Project API</title>
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
        <link href="css/styles.css" rel="stylesheet" media="screen">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div class="navbar navbar-default" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Joshua Project API</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="/">Home</a></li>
                    <li><a href="/docs/v1/#!/people_groups">Documentation</a></li>
                    <li><a href="/docs/v1/sample_code">Sample Code</a></li>
                    <li><a href="http://www.joshuaproject.net/">Joshua Project</a></li>
                    <li><a href="http://www.missionaldigerati.org">Missional Digerati</a></li>
                </ul>
                <div id="get-api-holder">
                    <a href="/" class="btn pull-right btn-info"><span class="glyphicon glyphicon-cog"></span> Get an API Key</a>
                </div>
            </div><!--/.nav-collapse -->
        </div>
        <div class="container">
            <div class="row">
                <div id="sidebar" class="col-sm-3">
                    <div class="hidden-print affix" role="complementary">
                        <ul class="nav sidenav">
                            <li class="active"><a href="#overview">Overview</a></li>
                            <li><a href="#javascript">Javascript (JQuery) Example</a></li>
                            <li><a href="#php">PHP Example</a></li>
                            <li><a href="#python">Python Example</a></li>
                            <li><a href="#ruby">Ruby Example</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="page-header">
                        <h2>Getting Started</h2>
                    </div>
                    <h3 id="overview">Overview</h3>
                    <h3 id="javascript">Javascript (JQuery) Example</h3>
                    <h3 id="php">PHP Example</h3>
                    <h3 id="python">Python Example</h3>
                    <h3 id="ruby">Ruby Example</h3>
                </div>
            </div>
        </div>
        <div class="container" id="footer">
            <a href="http://www.joshuaproject.net/" target="_blank">Joshua Project</a> is a ministry of the  <a href="http://www.uscwm.org/" target="_blank">U.S. Center for World Mission</a>. API created by <a href="http://www.missionaldigerati.org" target="_blank">Missional Digerati</a>.  Icons provided by <a href="http://gemicon.net/" target="_blank">Gem Icon</a>.
        </div>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('body').scrollspy({
                  target: '#sidebar',
                  offset: 75
                });

                $window.on('load', function () {
                  $('body').scrollspy('refresh');
                });
            });
        </script>
    </body>
</html>