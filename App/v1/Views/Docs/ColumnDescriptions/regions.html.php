<!DOCTYPE html>
<html>
  <head>
      <title>Joshua Project API - Version 1 Documentation</title>
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
                    <td>The number of least reached living in this region.</td>
                </tr>
                <tr>
                    <td>PercentLR</td>
                    <td>The percentage of least reached in this region.</td>
                </tr>
                <tr>
                    <td>PercentPoplLR</td>
                    <td>The percentage of the population that are least reached in this region.</td>
                </tr>
                <tr>
                    <td>PercentUrbanized</td>
                    <td>The percentage of urbanized living in this region.</td>
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
                    <td>The total number of least reached in this region.</td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
<?php
    include($VIEW_DIRECTORY . '/Partials/footer.html');
    include($VIEW_DIRECTORY . '/Partials/site_wide_footer_js.html');
?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('li.documentation-nav, li.regions-column-desc-nav').addClass('active');
            });
        </script>
</body>
</html>