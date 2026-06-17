window.skriptxAjax = function (options) {
  options.url = skriptxcongen.ajax_url;
  if (!options.data) {
    options.data = {};
  }
  options.data.nonce = skriptxcongen.nonce;
  options.error = function (err) {
    let msg = err?.statusText;
    if(!msg){
        msg = "Something went wrong. Contact administrator";
    }
    alert(msg);
  };
  options.success = function (res) {
    if (res.success) {
      options.onSuccess(res);
      return;
    }
    const msg = res.data;
    if (typeof msg == "string") {
      alert(msg);
      return;
    }
    alert("Something went wrong. Contact administrator");
  };
  jQuery.ajax(options);
};

window.skriptxTruncate = function(text, length = 50) {
    return text.length > length
        ? text.substring(0, length) + '...'
        : text;
}

window.skriptsScheduleStatus = function(id){
  if(id == 1) return 'Queued';
  if(id == 2) return 'Running';
  if(id == 3) return 'Success';
  if(id == 4) return 'Failed';
  return 'Undefined';
}