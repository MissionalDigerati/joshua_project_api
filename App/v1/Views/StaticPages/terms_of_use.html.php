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
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'site_wide_css_meta.html');
?>
  </head>
  <body>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'nav.html');
?>
    <div class="container">
        <div class="page-header">
            <h2>API Terms of Use</h2>
        </div>
        <p>Thank you for using the Joshua Project Data API. By accessing or using this API, you agree to comply with the following terms and conditions. Please read these Terms of Use carefully before using the API.</p>
        <h3>Permitted Use:</h3>
        <p>You are granted a non-exclusive, non-transferable, and revocable license to access and use the Joshua Project data via the API.  Your use of the data is free of charge.</p>
        <h3>Non-Commercial Use:</h3>
        <p>You may not use the Joshua Project data or API for commercial purposes.  Commercial use includes, but is not limited to, the sale of the data, creating commercial products, or using the data to generate revenue.</p>
        <h3>Attribution:</h3>
        <p>When using Joshua Project data, you agree to provide proper attribution by displaying the following acknowledgment: "Data provided by Joshua Project www.joshuaproject.net"</p>
        <p>Ensure that the attribution is clear and visible in your application or project using the data. Joshua Project reserves the right to review the placement and adequacy of the attribution provided by users and request modifications if necessary.</p>
        <h3>No Direct Replication:</h3>
        <p>You may not use the data in a manner that substantially replicates the services or presentation offered by Joshua Project.  Heavily replicating the core functionalities or providing a highly overlapping service using Joshua Project data is prohibited.  Please contact us at <a href="mailto:info@joshuaproject.net">info@joshuaproject.net</a> if you have any questions.</p>
        <h3>API Access Limits:</h3>
        <p>You agree not to abuse the API to the extent that it impacts the performance or availability of the service for other API users.</p>
        <h3>No Warranty:</h3>
        <p>Joshua Project provides the data and API on an "as is" basis without any warranty, express or implied.</p>
        <p>Joshua Project seeks to provide the best available data.  However, users are advised that the data may contain inaccuracies, gaps or errors. Joshua Project makes no guarantees regarding the absolute accuracy or completeness of the data.  The data should not be treated as perfectly precise information.</p>
        <h3>Termination:</h3>
        <p>Joshua Project may terminate access to the API at any time, with or without cause.  Upon termination, you must immediately cease using the API and delete any downloaded or cached data.</p>
        <h3>Changes to Terms:</h3>
        <p>Joshua Project reserves the right to modify or update these Terms of Use at any time.  Users are encouraged to review the terms periodically for changes.</p>
        <h3>Contact Information:</h3>
        <p>For inquiries or permission requests not covered in this Terms of Use, please contact Joshua Project at <a href="mailto:info@joshuaproject.net">info@joshuaproject.net</a></p>
        <p>By using the Joshua Project Data API, you agree to abide by these Terms of Use. If you do not agree with any part of these terms, please do not use the API.</p>
        <p>Thank you for your cooperation and adherence to these terms.</p>
    </div>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'footer.html');
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'site_wide_footer_js.html');
?>

        <script type="text/javascript">
            $(document).ready(function() {
                $('li.terms-of-use').addClass('active');
            });
        </script>
  </body>
</html>
