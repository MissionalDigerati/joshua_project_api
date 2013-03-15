<!DOCTYPE html>
<html>
  <head>
    <title>Joshua Project API</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/styles.css" rel="stylesheet" media="screen">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
	<div class="navbar navbar-fixed-top">
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
						<li class="active"><a href="#">Home</a></li>
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
			please complete the form below.</p>
<?php
if ((isset($data['api_key'])) && ($data['api_key'] != "")) {
    ?>
    <div class="alert alert-message">
        You got a shiny new API key: <strong><?php echo $data['api_key']; ?></strong>  Happy programming!
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
						<button type="submit" class="btn">Send request</button>
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