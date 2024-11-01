
class MpVoice2AppReceived {

  constructor(data) {
    this.data = data;
    this.isSuccess = false;
    this.text = "";
    this.message = "";
    this.process();
    // console.log(["data", data]);
  }
  process() {
    try {
      let json = JSON.parse(this.data);

      // console.log({ json });
      if (json.status == '0') {
        this.isSuccess = true;
      }

      this.text = json.data;
      this.message = json.message;
    } catch (exception) {
      let dis = this;
      // console.log({ exception, json: dis.data });
    }

  }

}


