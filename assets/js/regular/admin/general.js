"use strict";
mpereere_local_va = mpereere_local_va[0];
var data_mpereere_va_general = {
  post_id: mpereere_local_va.post_id,
  post_title: mpereere_local_va.post_title,
  post_content: mpereere_local_va.post_content,
  v2app_email: mpereere_local_va.user_v2app_email,
  v2app_apikey: mpereere_local_va.user_v2app_apikey,
  link_to_narration: "",
  returned_narration: mpereere_local_va.post_narration,
  something_value: 0,
  is_loading: false
};


var method_mpereere_va_general_xhr = {
  narrateNow() {
    let dis = this;
    let all = [
      "mpva_ajax_narrate",
      this.post_id,
      this.post_title,
      this.post_content,
      this.v2app_email,
      this.v2app_apikey
    ];
    this.is_loading = true;
    console.log("Narrating Started with email = " + this.v2app_email);
    mpVoice2AppAla.s_online_del(all, mpereere_local_va.ajax_action, function (success, fun_close) {
      dis.is_loading = false;
      dis.returned_narration = success;
      console.log("Narrating Ended");
//      dis.reset();
//      fun_close();
//      dis.all = success;
    });
  },
  narrateNow4() {
    console.log("Start narating");
    let my_url = "https://voice-app.azurewebsites.net/api/narration/getnarration";
    let formData = new FormData();
    var json = {
      description: "This is the title of the post",
      password: "Password for the plugin",
      email: "jradams1@bellsouth.net",
      narration: "Testing Plugin information.  Want to be sure that the api for remote narrations works ok."
    };
    json = JSON.stringify(json);

    jQuery.ajax({
      url: my_url,
      type: 'post',
      dataType: 'json',
      contentType: 'application/json',
      success: function (data) {
        console.log({data});
      },
      data: json
    });

  },
  narrateNow2() {
    console.log("Start narating");
    let my_url = "https://voice-app.azurewebsites.net/api/narration/getnarration";
    let formData = new FormData();
    var json = {
      description: "This is the title of the post",
      password: "Password for the plugin",
      email: "jradams1@bellsouth.net",
      narration: "Testing Plugin information.  Want to be sure that the api for remote narrations works ok."
    };
    json = JSON.stringify(json);

    formData.append("json", json);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', my_url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.upload.onprogress = function (e) {

    }
    xhr.onload = function () {
      if (this.status == 200) {
        let res = this.response;
        console.log({res});
      } else {
        console.log("Error: " + xhr.statusText);
      }
    }
    xhr.onerror = function (e) {
      console.log({errortt: e});

    }
    xhr.onabort = function (e) {

    }
    xhr.send(formData);
  },
  saveAll(event) {
    event.preventDefault();
    let dis = this;
    let all = [
      "mpcp_save_all",
      this.all,
    ];
    mpVoice2AppAla.s_online_del(all, mpereere_local_cp.ajax_action, function (success, fun_close) {
      dis.reset();
      fun_close();
      dis.all = success;
    });
  },
}
var method_mpereere_va_general = {

  reset() {
    jQuery(".mp-wr-reset-button-01").trigger("click");
  },
  something_clicked() {
    this.something_value = !this.something_value;
  },
  ...method_mpereere_va_general_xhr
}





jQuery(function () {
//  jQuery(document).on('change', 'select.mp_pr_select_06', function () {
//    let val = jQuery(this).val();
//    data_mpereere_pr_build.sort_by = val;
//  });
// mpMyWidgetAla.regc("mp-mw-btn-modal-open-01", "mp-mw-modal-01");mpereeredv-root-widget
  if (jQuery('#root-mp-voice2app-admin').length) {
    new Vue({
      el: "#root-mp-voice2app-admin",
      data: {
        ...data_mpereere_va_general,
      },
      methods: {
        ...method_mpereere_va_general,
      },
      created: function () {

      },
      computed: {

      }
    });
  }
});





