window.onload = function() {
  var currentUrl = location.protocol+'//'+location.hostname+(location.port ? ':'+location.port: '');
  window.ui = SwaggerUIBundle({
    url: currentUrl+"/api-docs.json",
    dom_id: '#swagger-ui',
    deepLinking: true,
    defaultModelsExpandDepth: -1,
    layout: "BaseLayout",
    requestSnippetsEnabled: true,
    tryItOutEnabled: true,
  });
};
