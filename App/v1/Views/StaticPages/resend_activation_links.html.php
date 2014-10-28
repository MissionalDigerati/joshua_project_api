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
<?php
    include($VIEW_DIRECTORY . '/Partials/site_wide_css_meta.html');
?>
  </head>
  <body>
<?php
    include($VIEW_DIRECTORY . '/Partials/nav.html');
?>
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
<?php
    include($VIEW_DIRECTORY . '/Partials/footer.html');
    include($VIEW_DIRECTORY . '/Partials/site_wide_footer_js.html');
?>

        <script type="text/javascript">
            $(document).ready(function() {
                $('li.home-nav').addClass('active');
            });
        </script>
  </body>
</html>