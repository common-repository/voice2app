
Vue.config.productionTip = false; //

var mpVoice2AppAla = {};

jQuery(function () {
  mpVoice2AppAla = new MpVoice2AppAla();
  try {
    jQuery('[data-toggle="tooltip"]').tooltip();
  } catch (error) {

  }
});

class MpVoice2AppAla {

  constructor() {
    this.stillProcessing = false;
    this.media_hold_url = [];
    this.stclick();
    this.reg();
    this.getInAni();
  }

  stclick() {
    jQuery(document).on("click", ".close_times_yy", function () {
      jQuery(this).parents(".hide_mgbe").slideUp("slow");
    });
  }

  getInAni() {
    let all = [
      "bounceIn", "bounceInDown", "bounceInLeft", "bounceInRight", "bounceInUp", "fadeIn", "fadeInDown", "fadeInDownBig", "fadeInLeft", "fadeInLeftBig", "fadeInRight", "fadeInRightBig", "fadeInUpBig", "flip", "flipInX", "flipInY", "lightSpeedIn", "rotateIn", "rotateInDownLeft", "rotateInDownRight", "rotateInUpLeft", "rotateInUpRight", "slideInUp", "slideInDown", "slideInLeft", "slideInRight", "rollIn"
    ];
    let rand = Math.floor(Math.random() * Math.floor(27));
    return all[rand];
  }

  /**
   * 
   * @param {id nke button ga emepe modal} one 
   * @param {id nke div modal} two 
   */
  reg(one, two) {
    jQuery(document).on("click", "#" + one, function () {
      jQuery("#" + two).slideDown("slow");
    });
  }

  regc(one, two) {
    jQuery(document).on("click", "." + one, function () {
      jQuery("." + two).slideDown("slow");
    });
  }

  /**
   * 
   * @param {Button text} select_button_text
   * @param {Head Text} select_head_text
   * @param {function} function_gotten called with the result as 1 param e.g. { id : 247, url : https://example.com/upload/image.jpg }
   * @returns {undefined} 
   */
  pick_image_from_media(select_button_text, select_head_text, function_gotten) {
    let frame = wp.media({
      title: select_head_text,
      button: {
        text: select_button_text
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });
    frame.open();
    frame.on('select', function () {
      let attachment = frame.state().get('selection').first().toJSON();
      let img_url = attachment.url;
      let img_att_id = attachment.id;
      let result = {
        id: img_att_id,
        url: img_url
      }
      function_gotten(result);
    });
  }

  s_online(all, what, form_obj, dont_refresh_call_func_with_success = false, success_report = false, file_array = false) {
    let dis = this;
    if (!dis.stillProcessing) {
      let report = new MpVoice2AppReportError(form_obj, true);
      report.showSpin();

      mpVoice2AppServer.send(what, all, function (begin) {

      }, function (data, message) {
//        console.log({data, message});
        report.hideSpin();
        report.hideProg();
        dis.stillProcessing = false;
//        if (success_report !== false) {
        report.showSuccess(message);
//        }
        if (dont_refresh_call_func_with_success === false) {
          report.showSuccess(message);
          window.location = window.location.href;
        } else {
          // console.log("Callin callback");
          setTimeout(function () {
            dont_refresh_call_func_with_success(data, true);
          }, 1000);
        }
      }, function (data, message) {
//        console.log({data,message});
        report.showFailure(message);
        report.hideSpin();
        dis.stillProcessing = false;
        if (message !== false) {
          setTimeout(function () {
            dont_refresh_call_func_with_success(data, false);
          }, 1000);
        }
      }, function (finished) {
        report.hideSpin();
        dis.stillProcessing = false;
      }, function (error) {
        dis.stillProcessing = false;
        report.showFailure("Network Error");
      }, function (progress) {
        report.showProg(progress);
      }, file_array);
  }
  }

  /**
   * 
   * @param {type} all
   * @param {type} what
   * @param {type} dont_refresh_call_func_with_success
   * @param {type} success_report
   * @returns {2 things} success , function to call to close pop up
   */
  s_online_del(all, what, dont_refresh_call_func_with_success = false, success_report = false, returnStatus = false, file_array = false) {
    let dis = this;
    if (!dis.stillProcessing) {
      let report = new MpVoice2AppReportError("delete_this_id");
      let delete_id_modal = jQuery('#response_delte_id');
      delete_id_modal.show();
      report.showSpin();
      mpVoice2AppServer.send(what, all, function (begin) {

      }, function (success, message) {
        // report.showSuccess(success);
        // window.location = window.location.href;
        // dis.stillProcessing = false;

//        console.log({success, message});
        report.hideSpin();
        report.hideProg();
        dis.stillProcessing = false;
//        if (success_report !== false) {
        report.showSuccess(message);
//        }
        setTimeout(function () {
          if (dont_refresh_call_func_with_success === false) {
            report.showSuccess(message);
            window.location = window.location.href;
          } else {
            // console.log("Callin callback");
            setTimeout(function () {
              delete_id_modal.hide();
              setTimeout(function () {
                if (returnStatus === true) {
                  dont_refresh_call_func_with_success(success, function () {
                    report.showSuccess(message);
                    report.hideSpin();
                  }, true);
                } else {
                  dont_refresh_call_func_with_success(success, function () {
                    report.showSuccess(message);
                    report.hideSpin();
                  });
                }
              }, 500);
            }, 1000);
          }
        }, 1000);
        if (success_report === true) {
          return message;
        }
      }, function (failed, message) {
        report.showFailure(failed);
        report.hideSpin();
        console.log({failed,message});
        dis.stillProcessing = false;
        if (returnStatus === true) {
          dont_refresh_call_func_with_success(message, function () {
            
          },false);
        }
      }, function (finished) {
        report.hideSpin();
        dis.stillProcessing = false;
      }, function (error) {
        dis.stillProcessing = false;
        if (success_report === true) {
          return error;
        }
      }, function (progress) {
        report.showProg(progress);
      },file_array);
  }
  }

}





