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
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="css/styles.css" rel="stylesheet" media="screen">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container">
				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="brand" href="/">Joshua Project API</a>
				<div class="nav-collapse collapse">
					<ul class="nav pull-right">
						<li class="active"><a href="/">Home</a></li>
						<li><a href="/docs/v1/#!/people_groups">Documentation</a></li>
						<li><a href="http://www.joshuaproject.net/">Joshua Project</a></li>
						<li><a href="http://www.missionaldigerati.org">Missional Digerati</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
    <div class="container">
    	<h2>Get My API Key</h2>
		<p>Thank you for requesting an API Key!</p>
<?php
if ((isset($message)) && ($message != '')) {
    ?>
    <div class="alert alert-success">
    <?php
        echo $message;
    ?>
    </div>
    <h3>Your API Key: 
    <?php
        echo $APIKey;
    ?>
    </h3>
    <?php
}
?>
<?php
if ((isset($error)) && ($error != '')) {
    ?>
    <div class="alert alert-error">
    <?php
        echo $error;
    ?>
    </div>
    <?php
}
?>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>