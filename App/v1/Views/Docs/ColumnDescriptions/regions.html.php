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
      <h2>Region Column Descriptions <span class="label label-primary pull-right">Version 1</span></h2>
    </div>
    <div id="table-column-descriptions" class="table-responsive">
        <table class='table table-hover table-bordered'>
            <tbody>
                <tr>
                    <td>NbrCountries</td>
                    <td>The number of countries in this region.</td>
                </tr>
                <tr>
                    <td>NbrPGIC</td>
                    <td>The number of people groups living in this region.</td>
                </tr>
                <tr>
                    <td>NbrLR</td>
                    <td>The number of least reached people groups living in this region.</td>
                </tr>
                <tr>
                    <td>PercentLR</td>
                    <td>The percentage of least reached people groups in this region.</td>
                </tr>
                <tr>
                    <td>PercentPoplLR</td>
                    <td>The percentage of the population that live in least reached people groups in this region.</td>
                </tr>
                <tr>
                    <td>RegionCode</td>
                    <td>The unique id for this region. [1-12]</td>
                </tr>
                <tr>
                    <td>RegionName</td>
                    <td>The full name of the region.</td>
                </tr>
                <tr>
                    <td>SumRegion</td>
                    <td>The total population in this region.</td>
                </tr>
                <tr>
                    <td>SumRegionLR</td>
                    <td>The total population living in least reached people groups in this region.</td> 
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
                $('li.documentation-nav, li.regions-column-desc-nav').addClass('active');
            });
        </script>
</body>
</html>
