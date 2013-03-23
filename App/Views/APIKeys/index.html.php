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
				<a class="brand" href="#">Joshua Project API</a>
				<div class="nav-collapse collapse">
					<ul class="nav pull-right">
						<li><a href="/">Home</a></li>
						<li><a href="http://www.joshuaproject.net/">Joshua Project</a></li>
						<li><a href="http://www.missionaldigerati.org">Missional Digerati</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
    <div class="container">
    	<h2>Existing API Keys</h2>
<?php
if ((isset($data['saving_error'])) && ($data['saving_error'] == "true")) {
    ?>
    <div class="alert alert-error">
        Your request has had an error!  Please try again.
    </div>
    <?php
} else if ((isset($data['saved'])) && ($data['saved'] == "true")) {
    ?>
    <div class="alert alert-success">
        The API Key has been <?php echo $data['key_state']; ?>!
    </div>
    <?php
}
?>
		<table class="table table-bordered table-condensed table-hover">
			<thead>
				<tr>
					<th>Name</th>
					<th>Email</th>
					<th>API Key</th>
					<th>Created</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
<?php 
foreach ($api_keys as $api_key) {
    ?>
    <tr>
        <td><?php echo $api_key['name']; ?></td>
        <td><?php echo $api_key['email']; ?></td>
        <td><?php echo $api_key['api_key']; ?></td>
        <td><?php echo date("M j, Y g:i a", strtotime($api_key['created'])); ?></td>
        <td>
            <form method="post" action="/api_keys/<?php echo $api_key['id']; ?>">
                <input type="hidden" name="_METHOD" value="PUT">
    <?php
    if ($api_key['suspended'] == 1) {
        ?>
                <input type="hidden" value="0" name="suspended">
                <button type="submit" class="btn btn-info btn-mini">Reinstate</button>
        <?php
    } else {
        ?>
                <input type="hidden" value="1" name="suspended">
                <button type="submit" class="btn btn-danger btn-mini">Suspend</button>
    <?php
    }
    ?>
            </form>
        </td>
    </tr>
    <tr>
        <td colspan="5"><?php echo $api_key['api_usage']; ?></td>
    </tr>
    <?php
}
?>
			</tbody>
		</table>
    </div>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>