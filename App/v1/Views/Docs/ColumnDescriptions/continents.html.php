<!DOCTYPE html>
<html>
  <head>
      <title>Joshua Project API - Version 1 Documentation</title>
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
      <h2>Continent Column Descriptions <span class="label label-primary pull-right">Version 1</span></h2>
    </div>
    <div id="table-column-descriptions" class="table-responsive">
        <table class='table table-hover table-bordered'>
            <tbody>
                <tr>
                    <td>Continent</td>
                    <td>The full name of the continent.</td>
                </tr>
                <tr>
                    <td>NbrCountries</td>
                    <td>The number of countries in the continent.</td>
                </tr>
                <tr>
                    <td>NbrPGIC</td>
                    <td>The number of people groups living on the continent.</td>
                </tr>
                <tr>
                    <td>PercentLR</td>
                    <td>The percentage of least reached on the continent.</td>
                </tr>
                <tr>
                    <td>PercentPoplLR</td>
                    <td>The percentage of the population that live in least reached people groups on the continent.</td>
                </tr>
                <tr>
                    <td>ROG2</td>
                    <td>HIS Registry of Geography (ROG) continent code</td>
                </tr>
                <tr>
                    <td>SumContinent</td>
                    <td>The total population on the continent.</td>
                </tr>
                <tr>
                    <td>SumContinentLR</td>
                    <td>The total population living in least reached people groups on this continent.</td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'footer.html');
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'site_wide_footer_js.html');
?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('li.documentation-nav, li.continents-column-desc-nav').addClass('active');
            });
        </script>
</body>
</html>
