
var mpVoice2AppServer = new MpVoice2AppMyServer("");

class MpVoice2AppReportError {

  constructor(parentId, with_class = false) {
    if (with_class === true) {
      this.parent = jQuery(parentId).find(".resp_one_two")
    } else {
      this.parent = jQuery("#" + parentId);
    }
    this.good = this.parent.children(".login-report-success");
    this.bad = this.parent.children(".login-report-failure");
    this.spin = this.parent.children(".mp_submit").children(".mp_submit_spinner");
    this.propParent = this.parent.children(".hose_484_prog");
    this.prog = this.parent.children(".hose_484_prog").children(".hose_839_main_prog");
    //    console.log(this.prog);
    //    console.log(this);
  }

  showProg(prog) { //console.log("prog = "+prog);
    jQuery(this.prog).width(prog + "%");
    jQuery(this.prog).text(prog + "%");
    //    console.log(this.prog);
    jQuery(this.propParent).slideDown("fast");
  }

  hideProg() {
    jQuery(this.propParent).slideUp("slow");
  }

  showSuccess(text) {
    this.good.text(text);
    this.bad.hide();
    this.good.slideDown("slow");
    this.hideSpin();
    this.hideProg();
    let dis = this;
    setTimeout(function () {
      dis.hideAll();
    }, 6000);
  }

  showFailure(text) {
    this.bad.text(text);
    this.good.hide();
    this.bad.slideDown("slow");
    this.hideSpin();
    this.hideProg();
    let dis = this;
    setTimeout(function () {
      dis.hideAll();
    }, 6000);
  }

  hideAll(text) {
    this.bad.hide("slow");
    this.good.hide("slow");
    this.hideSpin();
    this.hideProg();
  }

  showSpin() {
    this.spin.slideDown("slow");
    this.bad.hide("slow");
    this.good.hide("slow");
  }

  hideSpin() {
    this.spin.slideUp("slow");
  }

}

