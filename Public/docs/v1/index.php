<!DOCTYPE html>
<html>
  <head>
      <title>Joshua Project API - Version 1 Documentation</title>
<?php
    include('../../partials/site_wide_css_meta.html');
?>
      <link href='css/hightlight.default.css' media='screen' rel='stylesheet' type='text/css'/>
      <link href='css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
  </head>
<body>
<?php
    include('../../partials/nav.html');
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
    include('../../partials/footer.html');
    include('../../partials/site_wide_footer_js.html');
?>
    <script src='lib/jquery.slideto.min.js' type='text/javascript'></script>
    <script src='lib/jquery.wiggle.min.js' type='text/javascript'></script>
    <script src='lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
    <script src='lib/handlebars-1.0.rc.1.js' type='text/javascript'></script>
    <script src='lib/underscore-min.js' type='text/javascript'></script>
    <script src='lib/backbone-min.js' type='text/javascript'></script>
    <script src='lib/swagger.js' type='text/javascript'></script>
    <script src='swagger-ui.js' type='text/javascript'></script>
    <script src='lib/highlight.7.3.pack.js' type='text/javascript'></script>
    <script type="text/javascript">
    $(function () {
        $('li.documentation-nav, li.available-api-requests-nav').addClass('active');
        window.swaggerUi = new SwaggerUi({
              discoveryUrl:"http://"+document.domain+"/docs/v1/api-docs.json",
              apiKey:"",
              dom_id:"swagger-ui-container",
              supportHeaderParams: false,
              supportedSubmitMethods: ['get'],
              onComplete: function(swaggerApi, swaggerUi){
                if(console) {
                      console.log("Loaded SwaggerUI")
                      console.log(swaggerApi);
                      console.log(swaggerUi);
                  }
                $('pre code').each(function(i, e) {hljs.highlightBlock(e)});
              },
              onFailure: function(data) {
                if(console) {
                      console.log("Unable to Load SwaggerUI");
                      console.log(data);
                  }
              },
              docExpansion: "none"
          });

          window.swaggerUi.load();
    });

    </script>
</body>
</html>