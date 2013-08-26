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
    	<h2>Welcome to the Joshua Project API</h2>
        <p>This form will email you all the links to activate any pending API Keys associated with your email.</p>
<?php
if ((isset($message)) && ($message != '')) {
    ?>
    <div class="alert alert-success">
        <?php
            echo $message;
        ?>
    </div>
    <?php
}
?>
<?php
if ((!empty($errors)) && (in_array('find_keys', $errors))) {
    ?>
    <div class="alert alert-error">
        <?php
            echo $errors['find_keys'];
        ?>
    </div>
    <?php
}
?>
		<form class="form-horizontal" method="POST" action="/resend_activation_links">
			<fieldset>
				<legend>Resend Activation Links</legend>
				<div class="control-group">
					<label class="control-label" for="input-email">Email <span class="required-field">*</span></label>
					<div class="controls">
<?php
if ((isset($data['email'])) && ($data['email'] != "")) {
    ?>
        <input type="text" name="email" id="input-email" value="<?php echo $data['email']; ?>">
    <?php
} else {
    ?>
        <input type="text" name="email" id="input-email" placeholder="Email">
    <?php
}
if ((!empty($errors)) && (in_array('email', $errors))) {
    ?>
        <span class="help-inline error">Email is Required!</span>
    <?php
}
?>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button type="submit" class="btn btn-primary">Resend</button>
					</div>
				</div>
				<span class="required-field">* = Required</span>
			</fieldset>
		</form>
    </div>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>