;(function(global) {

  var getUrl = window.location;
  var url = getUrl .protocol + "//" + getUrl.host;

  var getJSON = function (url, sucesso, erro) {
    var httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", url, true);
    httpRequest.responseType = "json";
    httpRequest.addEventListener("readystatechange", function (event) {
      if (httpRequest.readyState == 4) {
        if (httpRequest.status == 200) {
          if (sucesso) sucesso(httpRequest.response);
        } else {
          if (erro) erro(httpRequest.status, httpRequest.statusText);
        }
      }
    });

    httpRequest.send();
  }

  function appendScriptTag(path) {
    var head = document.getElementsByTagName('head')[0],
    script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = path;
    script.async = true
    head.appendChild(script);
  }

  function loadScript() {
      console.log('loadScript');
      appendScriptTag(url+'/local/chatbot_dialogflow/js/chatbot.js');
      appendScriptTag(url+'/local/chatbot_dialogflow/js/lightslider.js');
  }

  window.onload = loadScript;

  // Create a div
  getJSON(url+'/local/chatbot_dialogflow/api/getHtml.php', function (data) {
    // console.log(data.html);
    // div.innerHTML = '<div id="chatboot_dialogflow-widget-root">'+data.html+'</div>'
    var div = document.createElement('div')
    div.id = 'chatboot_dialogflow-d7lcfheammjct'

    div.className = 'chatboot_dialogflow-embedder-d7lcfheammjct-embeddable'
    div.innerHTML = '<div id="chatboot_dialogflow-widget-root">'+data.html+'</div>'
    document.body.appendChild(div)

  }, function (errorCode, errorText) {
     console.log('Código: ' + errorCode);
     console.log('Mensagem de erro: ' + errorText);
  });

})(this)
