<!DOCTYPE html>
<html>
  <head>
      <title>Joshua Project API - Version 1 Documentation</title>
<?php
    include('../../../partials/site_wide_css_meta.html');
?>
  </head>
<body>
<?php
    include('../../../partials/nav.html');
?>>
  <div class="container">
    <div class="page-header">
      <h2>Sample Code</h2>
    </div>
    <p>Here you will find some examples in multiple programming languages of how to implement the Joshua Project API.  If you would like to download these examples,  please <a href="https://github.com/MissionalDigerati/joshua_project_api_sample_code/archive/master.zip">click here</a>.  You will need to get an API key to use these examples.  You can also view the code on <a href="https://github.com/MissionalDigerati/joshua_project_api_sample_code" target="_blank">Github</a>.</p>
    <div id="repo"></div>
  </div>
<?php
    include('../../..//partials/footer.html');
    include('../../..//partials/site_wide_footer_js.html');
?>
<script src='../lib/jquery.repo.js' type='text/javascript'></script>
<script type="text/javascript">
$(function () {
  $('#repo').repo({ user: 'MissionalDigerati', name: 'joshua_project_api_sample_code' });
  $('li.sample-code-nav').addClass('active');
});
</script>
</body>
</html>