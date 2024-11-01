<?php
namespace MpVoice2App;
class Email {

  const OPTION_EMAIL_HOST = "mp_server_email_host";
  const OPTION_EMAIL_USERNAME = "mp_server_email_username";
  const OPTION_EMAIL_PASSWORD = "mp_server_email_password";
  const OPTION_EMAIL_PORT = "mp_server_email_port";

  public function __construct() {
    
  }

  public static function send_email($title, $text, $from, $to) {
    $send = self::send_email_external_server_godaddy($title, $text, $from, $to);
//    sendFailure($send);
    return $send;
  }

  public static function sendEmail($subject, $content_html, $content_text, $comming_from_email, $comming_from_username, $emails_to_send_to = []) {
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
//    $mail = new \PHPMailer\PHPMailer;
    try {
//      $mail->SMTPDebug = 2; 
      $mail->isSMTP();
      $mail->Host = get_option(self::OPTION_EMAIL_HOST, "smtp.gmail.com");
      $mail->SMTPAuth = true;
      $mail->Username = get_option(self::OPTION_EMAIL_USERNAME, "");
      $mail->Password = get_option(self::OPTION_EMAIL_PASSWORD, "");
      $mail->SMTPSecure = 'tls';
      $mail->Port = get_option(self::OPTION_EMAIL_PORT, 587);
      //Recipients
      $mail->setFrom($comming_from_email, $comming_from_username);
      foreach ($emails_to_send_to as $email) {
        $mail->addAddress($email, "Name");
      }
      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $content_html;
      $mail->AltBody = $content_text;

      $mail->send();
      return true;
    } catch (\Exception $e) {
//      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//      do_action(self::BAD, $mail->ErrorInfo, "An error occured while Sending your details to your email => {".$mail->ErrorInfo."}");
//      return $mail->ErrorInfo;
    }
  }

  private static function send_email_external_server_godaddy($title, $text, $from, $to) {
    $host = "";
    $uname = "";
    $pass = "";
    $par = MpWrOptions::get_option(MpWrSettings::OPTIONS_EMAIL_SETTINGS, true);
    if ($par) {
      $host = $par[MpWrSettings::OPT_EMAIL_HOST];
      $uname = $par[MpWrSettings::OPT_EMAIL_USERNAME];
      $pass = $par[MpWrSettings::OPT_EMAIL_PASSWORD];
    }
//    $host = "a2plcpnl0029.prod.iad2.secureserver.net";
//    $uname = "admin@pereere.com";
//    $pass = "aaaabbbb";

    $email = new PHPMailer\PHPMailer\PHPMailer();
    //  $email->SMTPDebug = 3;
    $email->isSMTP();
    $email->Host = $host;
    $email->SMTPAuth = true;
    $email->SMTPSecure = true;
    $email->Username = $uname;
    $email->Password = $pass;
    $email->SMTPSecure = 'tls';
    $email->addCC($to);
    $email->Port = 587;
    $email->From = $uname;
    $email->FromName = str_replace(["http://", "https://"], "", MP_WR_SITE_URL);
    $email->addAddress($to, $to);
    $email->isHTML(true);
    $email->Subject = $title;
    $email->Body = $text;
    $email->AltBody = $text;
    $send = $email->send();
    if ($send === true) {
      return true;
    } else {
      return FALSE;
    }
  }

  public static function prepare_htmlr($hi, $text_before_link, $link_text, $link_link, $text_after_link, $text_clossing_wishes, $text_company_name, $poswered_by_site_url, $text_unsubscribe_text = "", $unsubscribe_link = "", $unsubscribe_link_text = "", $pre_header_text = "") {
    return "Testing";
  }

  public static function prepare_html($hi, $text_before_link, $link_text, $link_link, $text_after_link, $text_clossing_wishes, $text_company_name, $poswered_by_site_url, $text_unsubscribe_text = "", $unsubscribe_link = "", $unsubscribe_link_text = "", $pre_header_text = "") {
    $html = '<!doctype html>
      <html>
        <head>
          <meta name="viewport" content="width=device-width">
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <title>Simple Transactional Email</title>
          <style>
          /* -------------------------------------
              INLINED WITH htmlemail.io/inline
          ------------------------------------- */
          /* -------------------------------------
              RESPONSIVE AND MOBILE FRIENDLY STYLES
          ------------------------------------- */
          @media only screen and (max-width: 620px) {
            table[class=body] h1 {
              font-size: 28px !important;
              margin-bottom: 10px !important;
            }
            table[class=body] p,
                  table[class=body] ul,
                  table[class=body] ol,
                  table[class=body] td,
                  table[class=body] span,
                  table[class=body] a {
              font-size: 16px !important;
            }
            table[class=body] .wrapper,
                  table[class=body] .article {
              padding: 10px !important;
            }
            table[class=body] .content {
              padding: 0 !important;
            }
            table[class=body] .container {
              padding: 0 !important;
              width: 100% !important;
            }
            table[class=body] .main {
              border-left-width: 0 !important;
              border-radius: 0 !important;
              border-right-width: 0 !important;
            }
            table[class=body] .btn table {
              width: 100% !important;
            }
            table[class=body] .btn a {
              width: 100% !important;
            }
            table[class=body] .img-responsive {
              height: auto !important;
              max-width: 100% !important;
              width: auto !important;
            }
          }
          /* -------------------------------------
              PRESERVE THESE STYLES IN THE HEAD
          ------------------------------------- */
          @media all {
            .ExternalClass {
              width: 100%;
            }
            .ExternalClass,
                  .ExternalClass p,
                  .ExternalClass span,
                  .ExternalClass font,
                  .ExternalClass td,
                  .ExternalClass div {
              line-height: 100%;
            }
            .apple-link a {
              color: inherit !important;
              font-family: inherit !important;
              font-size: inherit !important;
              font-weight: inherit !important;
              line-height: inherit !important;
              text-decoration: none !important;
            }
            .btn-primary table td:hover {
              background-color: #34495e !important;
            }
            .btn-primary a:hover {
              background-color: #34495e !important;
              border-color: #34495e !important;
            }
          }
          </style>
        </head>
        <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
          <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
            <tr>
              <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
              <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
                <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

                  <!-- START CENTERED WHITE CONTAINER -->
                  <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">' . $pre_header_text . '</span>
                  <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                      <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                          <tr>
                            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                              <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">' . $hi . '</p>
                              <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">' . $text_before_link . '</p>
                              <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
                                <tbody>
                                  <tr>
                                    <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;">
                                      <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                        <tbody>
                                          <tr>
                                            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #3498db; border-radius: 5px; text-align: center;"> <a href="' . $link_link . '" target="_blank" style="display: inline-block; color: #ffffff; background-color: #3498db; border: solid 1px #3498db; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #3498db;">' . $link_text . '</a> </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">' . $text_after_link . '</p>
                              <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">' . $text_clossing_wishes . '</p>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>

                  <!-- END MAIN CONTENT AREA -->
                  </table>

                  <!-- START FOOTER -->
                  <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
                    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                      <tr>
                        <td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                          <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">' . $text_company_name . '</span>
                          <br> ' . $text_unsubscribe_text . '<a href="' . $unsubscribe_link . '" style="text-decoration: underline; color: #999999; font-size: 12px; text-align: center;">' . $unsubscribe_link_text . '</a>.
                        </td>
                      </tr>
                      <tr>
                        <td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                          Powered by <a href="' . $poswered_by_site_url . '" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">' . $poswered_by_site_url . '</a>.
                        </td>
                      </tr>
                    </table>
                  </div>
                  <!-- END FOOTER -->

                <!-- END CENTERED WHITE CONTAINER -->
                </div>
              </td>
              <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
            </tr>
          </table>
        </body>
      </html>
';

    return $html;
  }

  public static function get_email_signup_verification_html($full_link) {
    $html = "
			<h1>Emaiil Verify </h1>
			<a href='" . $full_link . "' >Click Here To Verify Your Email </a>
		 ";
    return $html;
  }

}

?>