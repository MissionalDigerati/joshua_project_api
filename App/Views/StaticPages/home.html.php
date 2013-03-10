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

		<form class="form-horizontal" method="POST" action="api_keys">
			<fieldset>
				<legend>Request An API Key</legend>
				<div class="control-group">
					<label class="control-label" for="input-name">Name <span class="required-field">*</span></label>
					<div class="controls">
						<input type="text" name="name" id="input-name" placeholder="Name">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input-email">Email <span class="required-field">*</span></label>
					<div class="controls">
						<input type="text" name="email" id="input-email" placeholder="Email">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="input-usage">How Will You Use the API? <span class="required-field">*</span></label>
					<div class="controls">
						<textarea rows="6" id="input-usage" name="usage"></textarea>
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