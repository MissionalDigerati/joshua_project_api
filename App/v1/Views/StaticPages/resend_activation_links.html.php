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
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
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
                <li><a href="/getting_started">Getting Started</a></li>
                <li><a href="/docs/v1/sample_code">Sample Code</a></li>
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown">Documentation <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="/docs/v1/#!/countries">Available API Requests</a></li>
                        <li><a href="/docs/v1/column_descriptions/countries">Country Column Descriptions</a></li>
                        <li><a href="/docs/v1/column_descriptions/people_groups">People Group Column Descriptions</a></li>
                    </ul>
                </li>
                <li><a href="http://www.joshuaproject.net/">Joshua Project</a></li>
                <li><a href="http://www.missionaldigerati.org">Missional Digerati</a></li>
            </ul>
            <div id="get-api-holder">
                <a href="/" class="btn pull-right btn-info"><span class="glyphicon glyphicon-cog"></span> Get an API Key</a>
            </div>
        </div><!--/.nav-collapse -->
    </div>
    <div class="container">
        <div class="page-header">
            <h2>Resend Activation Link</h2>
        </div>
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
    <div class="alert alert-danger">
        <?php
            echo $errors['find_keys'];
        ?>
    </div>
    <?php
}
?>
		<form class="form-horizontal" method="POST" action="/resend_activation_links" role="form">
			<fieldset>
<?php
if ((!empty($errors)) && (in_array('email', $errors))) {
    ?>
                        <div class="form-group has-error">
    <?php
} else {
    ?>
                        <div class="form-group">
    <?php
}
?>
					<label class="control-label col-lg-2" for="input-email">Email <span class="required-field">*</span></label>
					<div class="controls col-lg-10">
<?php
if ((isset($data['email'])) && ($data['email'] != "")) {
    ?>
        <input type="text" name="email" id="input-email" value="<?php echo $data['email']; ?>" class="form-control">
    <?php
} else {
    ?>
        <input type="text" name="email" id="input-email" placeholder="Email" class="form-control">
    <?php
}
if ((!empty($errors)) && (in_array('email', $errors))) {
    ?>
        <span class="help-block">Email is Required!</span>
    <?php
}
?>
					</div>
				</div>
				<div class="form-group">
					<div class="controls col-lg-offset-2 col-lg-10">
						<button type="submit" class="btn btn-primary">Resend</button>
					</div>
				</div>
				<span class="required-field">* = Required</span>
			</fieldset>
		</form>
    </div>
    <div class="container" id="footer">
        <a href="http://www.joshuaproject.net/" target="_blank">Joshua Project</a> is a ministry of the  <a href="http://www.uscwm.org/" target="_blank">U.S. Center for World Mission</a>. API created by <a href="http://www.missionaldigerati.org" target="_blank">Missional Digerati</a>.
    </div>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>