mpmwLocal = mpmwLocal[0];
var MpMwData = {
  zz: false,
  cats: mpmwLocal.cats,
  pageUrl: mpmwLocal.thisUrl,
  currentCat: null,
  showNum: 0,
  offsetLeft: 0,
  mobileNavigation: false
}

var MpMwMethods = {
  toggleNavigation() {
//    console.log("Totlge");
    let dis = this;
    this.mobileNavigation = !this.mobileNavigation;
    let left = this.mobileNavigation ? "-200%" : "0%";
    jQuery('.mpmw-mobile-navigation').animate({
//      left : '-200%'
      left: left
    }, 200, function () {
    });
    if (dis.mobileNavigation) {
      jQuery('.mpmw-mobile-navigation-transparent').slideUp('fast');
    } else {
      jQuery('.mpmw-mobile-navigation-transparent').fadeIn('slow');
    }

  },
  checkResize() {
//    console.log("hee");
//    jQuery(".mpmw-mobile-navigation").resize(function () {
//      console.log("move");
//    });
    return true;
  },
  getMoreParentHoverClass() {

  },
  changeShow(child) {
//    console.log("click");
    child.showChildren = !child.showChildren;
  },
  oneLeave() {
    let dis = this;
    let num = dis.showNum;
    setTimeout(function () {
      if (dis.showNum === num) {
        dis.currentCat = null;
      }
    }, 400);
  },
  oneHover(cat = false, c_id = false) {
    let dis = this;
    if (cat !== false) {
      this.currentCat = cat;
    }
    this.showNum++;
    let className = '.mpmw-one-menu-' + c_id;
    console.log({
      className
    });
    try {
      let windowWidth = jQuery(window).width();
      let menuOffset = jQuery(className).offset();
      let menuOffsetLeft = menuOffset.left;

      if (c_id !== false) {
        console.log({diff: (windowWidth - menuOffsetLeft), c_id, 
        element : jQuery(className)
        });
        let diff = windowWidth - menuOffsetLeft;
        let normal = 250;
        if (diff < normal) {
          let to_add = normal - diff;
          this.offsetLeft = menuOffsetLeft - to_add;
        } else {
          this.offsetLeft = menuOffsetLeft;
        }
//      if ((windowWidth - menuOffsetLeft) < 250) {
//        this.offsetLeft = menuOffsetLeft - 250;
//      }else if ((windowWidth - menuOffsetLeft) < 350) {
//        this.offsetLeft = menuOffsetLeft - 350;
//      } 
      }
    } catch (e) {
       console.log({error_offset : e});
  }
//    console.log({
//      windowWidth,
//      menuOffsetLeft,
//      'offsetLeft': dis.offsetLeft
//    });
  }
}



jQuery(function () {
  if (jQuery(".mpmw-id-root-menu").length) {
    var MpMwVue = new Vue({
      el: ".mpmw-id-root-menu", data: MpMwData, methods: MpMwMethods, created: function () {
//        console.log("Noee Created");
        let dis = this;
        this.mobileNavigation = !this.mobileNavigation;
        let left = this.mobileNavigation ? "-200%" : "0%";
        jQuery('.mpmw-mobile-navigation').animate({
//      left : '-200%'
          left: left
        }, 200, function () {
        });
        if (dis.mobileNavigation) {
          jQuery('.mpmw-mobile-navigation-transparent').slideUp('fast');
        } else {
          jQuery('.mpmw-mobile-navigation-transparent').fadeIn('slow');
        }
      }
    });
  }


  jQuery(document).on('resize', ".mpmw-mobile-navigation", function () {
//    console.log("move");
  });

});