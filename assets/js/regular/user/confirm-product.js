
mpereere_local_cp = mpereere_local_cp[0];
var mpreere_cp_data = {
  something_value: 0,
code : "",
  res: {
    success: "",
    error: "",
    sending: false
  }
};
var mpreere_cp_methods = {
  search(event) {
    event.preventDefault();
    let dis = this;
    let all = [
      "mpcp_ajax_nopriv_user_search",
      this.code
    ];
    dis.res.sending = true;
    mpConfirmProductAla.s_online_del(all, mpereere_local_cp.ajax_action, function (success, fun_close, successOrFailue = false) {
      dis.res.sending = false;
      window.location = success;
    }, false, true);
  },

  reset() {
    jQuery(".mp-wr-reset-button-01").trigger("click");
//    console.log("reseting");
  },
  something_clicked() {
    this.something_value = !this.something_value;
  },

};



jQuery(function () {

  let options = "";

  let allTime = [];
  for (let a = 7; a < 24; a++) {
    let lv1Text = "";
    lv1Text = (a < 10) ? "0" + a + ":" : a + ":";
    for (let b = 0; b < 60; b++) {
      let secondPart = (b < 10) ? "0" + b : b;
      let text = lv1Text + secondPart + "";
      let to_convert = (a + "" + secondPart + "");
      let value = parseInt(to_convert);
      allTime[allTime.length] = {
        text,
        value,
        to_convert
      };
    }
  }
//  console.log({
//    allTime
//  });
  for (let a = 0; a < allTime.length; a++) {
    options += '<option value="' + allTime[a].text + '" />';
  }
//  document.getElementById('mpdv-time-datalist').innerHTML = options;

  if (jQuery('.mpereerecp-root-widget').length) {
    new Vue({
      el: ".mpereerecp-root-widget", data: mpreere_cp_data, methods: mpreere_cp_methods,
      created: function () {
      }
    });
  }

  jQuery(document).on('click', '.mpcp-search-button', function () {
    let near = jQuery(this).parent().next('button.mpcp-submit').trigger('click');
//    console.log({near});
//console.log("clicked");
  });
});


