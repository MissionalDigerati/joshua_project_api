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
    include($PUBLIC_DIRECTORY . '/partials/site_wide_css_meta.html');
?>
  </head>
  <body>
<?php
    include($PUBLIC_DIRECTORY . '/partials/nav.html');
?>
    <div class="container">
        <div class="page-header">
            <h2>Get My API Key</h2>
        </div>
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
    <div class="alert alert-danger">
    <?php
        echo $error;
    ?>
    </div>
    <?php
}
?>
<?php
    include($PUBLIC_DIRECTORY . '/partials/footer.html');
    include($PUBLIC_DIRECTORY . '/partials/site_wide_footer_js.html');
?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('li.home-nav').addClass('active');
            });
        </script>
  </body>
</html>