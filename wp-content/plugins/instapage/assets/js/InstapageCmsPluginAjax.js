/* globals  ActiveXObject */
var InstapageCmsPluginAjax = function InstapageCmsPluginAjax() {
  var self = this;

  self.call = function call(method, url, data, success, async) {
    var async = (typeof async === 'undefined') ? true : async;
    var xmlhttp = null;
    var urlAppendix = (url.match(/\?/) === null ? '?' : '&') + (new Date()).getTime();

    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
    }

    xmlhttp.onreadystatechange = function onreadystatechange() {
      if (xmlhttp.readyState === 4 && xmlhttp.status === 200 && typeof success === 'function') {
        success(xmlhttp.response);
      }
    };

    xmlhttp.open(method, url + urlAppendix, async);

    if (method === 'POST') {
      var formData = new FormData();
      formData.append('data', encodeURI(JSON.stringify(data)));
      xmlhttp.send(formData);
    } else {
      xmlhttp.send();
    }
  };

  self.post = function post(url, data, success, async) {
    self.call('POST', url, data, success, async);
  };
};

var iAjax = new InstapageCmsPluginAjax();
window.iAjax = iAjax;
