<?php
function mail_send($mail_msg)
{
    //get reference to a controller object
    $ci = &get_instance();

    //load model
    $ci->load->model('model_settings');

    if ($mail_msg) {
        if ($mail_msg["to"] && $mail_msg["subject"] && $mail_msg["message"]) {

            //get email server details
            try {
                $app_details = $ci->model_settings->app_details();
                if ($app_details) {
                    $company_code = $app_details->company_code;

                    $protocol = $app_details->email_protocol;
                    $smtp_host = $app_details->smtp_host;
                    $smtp_user = $app_details->smtp_user;
                    $smtp_pass = $app_details->smtp_pass;
                    $smtp_port = $app_details->smtp_port;
                    $smtp_crypto = $app_details->smtp_crypto;

                    //$this->load->library('email'); //already loaded in config/autoload

                    $config['protocol'] = $protocol;
                    $config['smtp_host'] = $smtp_host;
                    $config['smtp_user'] = $smtp_user;
                    $config['smtp_pass'] = $smtp_pass;
                    $config['smtp_port'] = $smtp_port;
                    $config['smtp_crypto'] = $smtp_crypto;
                    $config['charset'] = 'iso-8859-1';
                    $config['wordwrap'] = TRUE;
                    $config['mailtype'] = 'html';

                    $ci->email->initialize($config);
                    $ci->email->set_newline("\r\n");

                    $ci->email->from($smtp_user, $company_code);
                    $ci->email->to($mail_msg["to"]);
                    $ci->email->cc(!empty($mail_msg["cc"]) ? $mail_msg["cc"] : "");
                    $ci->email->bcc(!empty($mail_msg["bcc"]) ? $mail_msg["bcc"] : "");

                    $ci->email->subject($mail_msg["subject"]);
                    $ci->email->message($mail_msg["message"]);

                    //$this->email->send();

                    if (!$ci->email->send()) {
                        // Generate error
                        //print_r($this->email);
                        return "Error: Unable to send email!";
                    }
                } else {
                    return "Error: Unable to initialize email!";
                }
            } catch (Exception $ex) {
                return $ex->getMessage();
            }
        } else {
            return "Error: Fields with red asterisk (*) are required!";
        }
    } else {
        return "Error: Critical Error Encountered!";
    }
}