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
            <div class="col-sm-8">
                <div class="page-header">
                    <h2>Welcome to the Joshua Project API</h2>
                </div>
                <p>Joshua Project is a research initiative seeking to highlight the ethnic people groups of the world with the fewest followers of Christ. Accurate, regularly updated ethnic people group information is critical for understanding and completing the Great Commission.</p>
            </div>
            <div class="col-sm-4">
<?php
if ((isset($data['api_key'])) && ($data['api_key'] == 'true')) {
    ?>
                <div class="alert alert-success">
                    Thank you!  We made you a shiny new API key.  Before you can retrieve it,  please visit the email you provided and click the link in the email we sent you.  Happy programming!
                </div>
    <?php
}
?>
                <div class="page-header">
                    <h2>Request An API Key</h2>
                </div>
                <p>If you would like to get an API key, please complete the form below and verify your email address.</p>
                <form class="form-horizontal" method="POST" action="api_keys" role="form">
                    <fieldset>
<?php
if ((!empty($errors)) && (in_array('name', $errors))) {
    ?>
                        <div class="form-group has-error">
    <?php
} else {
    ?>
                        <div class="form-group">
    <?php
}
?>
                            <label class="control-label col-lg-4" for="input-name">Name <span class="required-field">*</span></label>
                            <div class="controls col-lg-8">
<?php
if ((isset($data['name'])) && ($data['name'] != "")) {
    ?>
                                <input type="text" name="name" id="input-name"  value="<?php echo $data['name']; ?>" class="form-control">
    <?php
} else {
    ?>
                                <input type="text" name="name" id="input-name" placeholder="Name" class="form-control">
    <?php
}
if ((!empty($errors)) && (in_array('name', $errors))) {
    ?>
                                <span class="help-block">Name is Required!</span>
    <?php
}
?>
                            </div>
                        </div>
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
                            <label class="control-label col-lg-4" for="input-email">Email <span class="required-field">*</span></label>
                            <div class="controls col-lg-8">
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
                            <label class="control-label col-lg-4" for="input-name">Organization</label>
                            <div class="controls col-lg-8">
<?php
if ((isset($data['organization'])) && ($data['organization'] != "")) {
    ?>
                                <input type="text" name="organization" id="input-organization"  value="<?php echo $data['organization']; ?>" class="form-control">
    <?php
} else {
    ?>
                                <input type="text" name="organization" id="input-organization" placeholder="Organization" class="form-control">
    <?php
}
    ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-4" for="input-name">Website</label>
                            <div class="controls col-lg-8">
<?php
if ((isset($data['website'])) && ($data['website'] != "")) {
    ?>
                                <input type="text" name="website" id="input-website"  value="<?php echo $data['website']; ?>" class="form-control">
    <?php
} else {
    ?>
                                <input type="text" name="website" id="input-website" placeholder="Website" class="form-control">
    <?php
}
?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-4" for="input-name">Phone Number</label>
                            <div class="controls col-lg-8">
<?php
if ((isset($data['phone_number'])) && ($data['phone_number'] != "")) {
    ?>
                                <input type="text" name="phone_number" id="input-phone_number"  value="<?php echo $data['phone_number']; ?>" class="form-control">
    <?php
} else {
    ?>
                                <input type="text" name="phone_number" id="input-phone_number" placeholder="Phone Number" class="form-control">
    <?php
}
?>
                            </div>
                        </div>
<?php
if ((!empty($errors)) && (in_array('usage', $errors))) {
    ?>
                        <div class="form-group has-error">
    <?php
} else {
    ?>
                        <div class="form-group">
    <?php
}
?>
                            <label class="control-label col-lg-4" for="input-usage">How Will You Use the API? <span class="required-field">*</span></label>
                            <div class="controls col-lg-8">
<?php
if ((isset($data['usage'])) && ($data['usage'] != "")) {
    ?>
                                <textarea rows="6" id="input-usage" name="usage" class="form-control"><?php echo $data['usage']; ?></textarea>
    <?php
} else {
    ?>
                                <textarea rows="6" id="input-usage" name="usage" class="form-control"></textarea>
    <?php
}
if ((!empty($errors)) && (in_array('usage', $errors))) {
    ?>
                                <span class="help-block">Usage is Required!</span>
    <?php
}
?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="controls col-lg-12">
                                <button type="submit" class="btn btn-primary btn-block">Send request</button><br>
                                <a type="button" class="btn btn-link" href="/resend_activation_links">Resend Activation Links</a>
                            </div>
                        </div>
                        <span class="required-field">* = Required</span>
                    </fieldset>
                </form>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="container" id="footer">
            <a href="http://www.joshuaproject.net/" target="_blank">Joshua Project</a> is a ministry of the  <a href="http://www.uscwm.org/" target="_blank">U.S. Center for World Mission</a>. API created by <a href="http://www.missionaldigerati.org" target="_blank">Missional Digerati</a>.
        </div>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>