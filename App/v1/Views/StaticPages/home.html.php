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
						<li><a href="/docs/v1/">Documentation</a></li>
						<li><a href="http://www.joshuaproject.net/">Joshua Project</a></li>
						<li><a href="http://www.missionaldigerati.org">Missional Digerati</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
    <div class="container">
    	<h2>Welcome to the Joshua Project API</h2>
		<p>This is a new development of <a href="http://www.missionaldigerati.org">Missional Digerati</a>.  If you would like to get an API key, 
			please complete the form below, and verify your email address.</p>
<?php
if ((isset($data['api_key'])) && ($data['api_key'] == 'true')) {
    ?>
    <div class="alert alert-success">
        Thank you!  We made you a shiny new API key.  Before you can retrieve it,  please visit the email you provided and click the link in the email we sent you.  Happy programming!
    </div>
    <?php
}
?>
		<form class="form-horizontal" method="POST" action="api_keys">
			<fieldset>
				<legend>Request An API Key</legend>
				<div class="control-group">
					<label class="control-label" for="input-name">Name <span class="required-field">*</span></label>
					<div class="controls">
<?php
if ((isset($data['name'])) && ($data['name'] != "")) {
    ?>
        <input type="text" name="name" id="input-name"  value="<?php echo $data['name']; ?>">
    <?php
} else {
    ?>
        <input type="text" name="name" id="input-name" placeholder="Name">
    <?php
}
if ((!empty($errors)) && (in_array('name', $errors))) {
    ?>
        <span class="help-inline error">Name is Required!</span>
    <?php
}
?>
					</div>
				</div>
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
					<label class="control-label" for="input-name">Organization</label>
					<div class="controls">
<?php
if ((isset($data['organization'])) && ($data['organization'] != "")) {
    ?>
        <input type="text" name="organization" id="input-organization"  value="<?php echo $data['organization']; ?>">
    <?php
} else {
    ?>
        <input type="text" name="organization" id="input-organization" placeholder="Organization">
    <?php
}
?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input-name">Website</label>
					<div class="controls">
<?php
if ((isset($data['website'])) && ($data['website'] != "")) {
    ?>
        <input type="text" name="website" id="input-website"  value="<?php echo $data['website']; ?>">
    <?php
} else {
    ?>
        <input type="text" name="website" id="input-website" placeholder="Website">
    <?php
}
?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input-name">Phone Number</label>
					<div class="controls">
<?php
if ((isset($data['phone_number'])) && ($data['phone_number'] != "")) {
    ?>
        <input type="text" name="phone_number" id="input-phone_number"  value="<?php echo $data['phone_number']; ?>">
    <?php
} else {
    ?>
        <input type="text" name="phone_number" id="input-phone_number" placeholder="Phone Number">
    <?php
}
?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input-usage">How Will You Use the API? <span class="required-field">*</span></label>
					<div class="controls">
<?php
if ((isset($data['usage'])) && ($data['usage'] != "")) {
    ?>
        <textarea rows="6" id="input-usage" name="usage"><?php echo $data['usage']; ?></textarea>
    <?php
} else {
    ?>
        <textarea rows="6" id="input-usage" name="usage"></textarea>
    <?php
}
if ((!empty($errors)) && (in_array('usage', $errors))) {
    ?>
        <span class="help-inline error">Usage is Required!</span>
    <?php
}
?>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button type="submit" class="btn btn-primary">Send request</button>
                        <a type="button" class="btn btn-link" href="/resend_activation_links">Resend Activation Links</a>
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