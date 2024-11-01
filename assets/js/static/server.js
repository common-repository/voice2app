
class MpVoice2AppMyServer {

  constructor(dataToSend) {
    try {
      let base = mpereere_local_va[0].ajax_url;
      this.setVariables();
      this.serverUrl = base;
      this.dataToSend = dataToSend;

    } catch (error) {
//      console.log({'myserver_error': error});
    }
  }

  /**
   * 
   * @param {type} what
   * @param {type} dataToSend
   * @param {type} funcBefore
   * @param {type} funcSuccess
   * @param {type} funcFailed
   * @param {type} funcEnd
   * @param {type} funcError
   * @param {type} funcProgress
   * @param {Array} file_array [ ['name','File Obj'], ['name', 'File Obj'] ]
   * @returns {undefined}
   */
  send(what, dataToSend, funcBefore, funcSuccess, funcFailed, funcEnd, funcError, funcProgress, file_array = false) {

    let mythis = this;
    let formData = new FormData();
    
    let toSend = mythis.prepareToSend(dataToSend)
    if (file_array !== false) {
      if (file_array.length > 0) {
        for (let a = 0; a < file_array.length; a++) {
          let name = file_array[a][0];
          formData.append(name, file_array[a][1]);
        }
      }
    }
    formData.append('form_data', toSend);
    formData.append('action', what);
    mythis.do_xhr(formData, function (before) {
      funcBefore();
    }, function (success,message) {
      funcSuccess(success,message);
    }, function (failed,message) {
//      console.log({failed,message});
      if (failed.length < 1) {
        funcFailed("Network Error");
      }
      funcFailed(failed,message);
    }, function (end) {
      funcEnd();
    }, function (error) {
      funcFailed("Network Error");
      //        funcError(error);
    }, function (progress) {
      funcProgress(progress);
    });
//    }, function (reject) {
//      console.log({ "reject": reject });
//      funcFailed(reject);
//    });
  }

  do_xhr(formData, funcBefore, funcSuccess, funcFailed, funcEnd, funcError, funcProgress) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', this.serverUrl, true);
    xhr.withCredentials = true;
    // console.dir(["fd", formData]);
    xhr.upload.onprogress = function (e) {
      if (e.lengthComputable) {
        let percent = (e.loaded / e.total) * 100;
        //        console.log("total = "+e.total+" loaded = "+e.loaded+" percent = "+percent);
        //        var roundedNumber = Math.round(number * 10) / 10;
        percent = Math.round(percent * 10) / 10;
        funcProgress(percent);
      }
    }
    let dis = this;
    xhr.onload = function () {
      if (this.status == 200) {
        let res = this.response;
        let received = new MpVoice2AppReceived(res);
//        console.log({'rext': received.text});
        if (received.isSuccess) {
          funcSuccess(received.text,received.message);
        } else {
          if (received.text !== null) {
            funcFailed(received.text,received.message);
          } else {
            funcFailed("Internal Server Error");
          }
        }
      } else {
        funcFailed("Error: " + xhr.statusText);
      }
    }
    xhr.onerror = function (e) {
//      console.log({errortt: e});
      funcError(e);
    }
    xhr.onabort = function (e) {
      funcError(e);
    }

    xhr.send(formData);
  }

  prepareToSend(dataToSend) {
    var data = {};
    try {
      data[this.VAR_0] = dataToSend[0];
      data[this.VAR_1] = dataToSend[1];
      data[this.VAR_2] = dataToSend[2];
      data[this.VAR_3] = dataToSend[3];
      data[this.VAR_4] = dataToSend[4];
      data[this.VAR_5] = dataToSend[5];
      data[this.VAR_6] = dataToSend[6];
      data[this.VAR_7] = dataToSend[7];
      data[this.VAR_8] = dataToSend[8];
      data[this.VAR_9] = dataToSend[9];
      data[this.VAR_10] = dataToSend[10];
      data[this.VAR_11] = dataToSend[11];
      data[this.VAR_12] = dataToSend[12];
      data[this.VAR_13] = dataToSend[13];
      data[this.VAR_14] = dataToSend[14];
      data[this.VAR_15] = dataToSend[15];
      data[this.VAR_16] = dataToSend[16];
      data[this.VAR_17] = dataToSend[17];
      data[this.VAR_18] = dataToSend[18];
      data[this.VAR_19] = dataToSend[19];
      data[this.VAR_20] = dataToSend[20];


    } catch (e) {
      console.log(["preapare error", e]);
    }

    //    let w = this.S_F_WHAT;
    //    let d = this.S_F_DATA;
    //    let prep = {w : what, d : data};
    let prep = {};

//    prep[this.S_F_WHAT] = what;
    prep[this.S_F_DATA] = data;
//    console.log({prep, data, dataToSend});
    let ss = JSON.stringify(prep);
    return ss;
    //    return prep;
  }

  setVariables() {



    this.VAR_0 = "var_0";
    this.VAR_1 = "var_1";
    this.VAR_2 = "var_2";
    this.VAR_3 = "var_3";
    this.VAR_4 = "var_4";
    this.VAR_5 = "var_5";
    this.VAR_6 = "var_6";
    this.VAR_7 = "var_7";
    this.VAR_8 = "var_8";
    this.VAR_9 = "var_9";
    this.VAR_10 = "var_10";
    this.VAR_11 = "var_11";
    this.VAR_12 = "var_12";
    this.VAR_13 = "var_13";
    this.VAR_14 = "var_14";
    this.VAR_15 = "var_15";
    this.VAR_16 = "var_16";
    this.VAR_17 = "var_17";
    this.VAR_18 = "var_18";
    this.VAR_19 = "var_19";
    this.VAR_20 = "var_20";


    this.S_F_WHAT = "what";
    this.S_F_DATA = "data";
    this.S_F_STATUS = "status";
    this.S_F_SUCCESS = "0";
    this.S_F_FAILURE = "1";
    this.S_F_ERROR = "2";
    this.W_LOGIN = "what_login";


  }


}










