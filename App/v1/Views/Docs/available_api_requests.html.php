<!DOCTYPE html>
<html>
  <head>
    <title>Joshua Project API - Version 1 Documentation</title>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'site_wide_css_meta.html');
?>
    <link href='/css/swagger-ui.css' media='screen' rel='stylesheet' type='text/css'/>
  </head>
<body>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'nav.html');
?>
  <div class="container">
    <div class="page-header">
        <h2>Available API Requests <span class="label label-primary pull-right">Version 1</span></h2>
    </div>
    <div id="swagger-ui">
    </div>
  </div>
<?php
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'footer.html');
    include($viewDirectory . 'Partials' . DIRECTORY_SEPARATOR . 'site_wide_footer_js.html');
?>
    <script src="/js/swagger-ui-bundle.js" charset="UTF-8"> </script>
    <script src="/js/swagger-initializer.js" charset="UTF-8"> </script>
</body>
</html>
