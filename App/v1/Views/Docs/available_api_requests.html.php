<!DOCTYPE html>
<html>
  <head>
      <title>Joshua Project API - Version 1 Documentation</title>
<?php
    include($VIEW_DIRECTORY . '/Partials/site_wide_css_meta.html');
?>
      <link href='/css/hightlight.default.css' media='screen' rel='stylesheet' type='text/css'/>
      <link href='/css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
  </head>
<body>
<?php
    include($VIEW_DIRECTORY . '/Partials/nav.html');
?>
  <div class="container">
    <div class="page-header">
        <h2>Available API Requests <span class="label label-primary pull-right">Version 1</span></h2>
    </div>
    <div id="message-bar" class="swagger-ui-wrap">
        &nbsp;
    </div>
    <div id="swagger-ui-container" class="swagger-ui-wrap">
    </div>
  </div>
<?php
    include($VIEW_DIRECTORY . '/Partials/footer.html');
    include($VIEW_DIRECTORY . '/Partials/site_wide_footer_js.html');
?>
    <script src='/js/jquery.slideto.min.js' type='text/javascript'></script>
    <script src='/js/jquery.wiggle.min.js' type='text/javascript'></script>
    <script src='/js/jquery.ba-bbq.min.js' type='text/javascript'></script>
    <script src='/js/handlebars-1.0.rc.1.js' type='text/javascript'></script>
    <script src='/js/underscore-min.js' type='text/javascript'></script>
    <script src='/js/backbone-min.js' type='text/javascript'></script>
    <script src='/js/swagger.js' type='text/javascript'></script>
    <script src='/js/swagger-ui.js' type='text/javascript'></script>
    <script src='/js/highlight.7.3.pack.js' type='text/javascript'></script>
    <script type="text/javascript">
    $(function () {
        $('li.documentation-nav, li.available-api-requests-nav').addClass('active');
        var currentUrl = location.protocol+'//'+location.hostname+(location.port ? ':'+location.port: '');
        window.swaggerUi = new SwaggerUi({
              discoveryUrl:currentUrl+"/api-docs.json",
              apiKey:"",
              dom_id:"swagger-ui-container",
              supportHeaderParams: false,
              supportedSubmitMethods: ['get'],
              onComplete: function(swaggerApi, swaggerUi) {
                $('pre code').each(function(i, e) {hljs.highlightBlock(e)});
              },
              onFailure: function(data) {
                if(console) {
                      console.error("Unable to Load SwaggerUI");
                  }
              },
              docExpansion: "none"
          });

          window.swaggerUi.load();
    });
    </script>
</body>
</html>
