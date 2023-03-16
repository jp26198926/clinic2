<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title> <?= $app_title; ?> </title>

    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <?php
	$this->load->view('template/style');
	?>



</head>

<body class="no-skin">

    <?php
	$this->load->view('template/header');
	?>

    <div class="main-container ace-save-state" id="main-container">
        <script type="text/javascript">
        try {
            ace.settings.loadState('main-container')
        } catch (e) {}
        </script>

        <?php
		$this->load->view('template/sidebar');
		?>

        <div class="main-content">
            <div class="main-content-inner">

                <div class="page-content">
                    <?php
					$this->load->view('template/ace-settings');
					?>

                    <div class="row">
                        <div id='page_content' class="col-xs-12">
                            <!-- PAGE CONTENT BEGINS -->
                            <div class="page-header">
                                <h1>
                                    <?= ucwords($parent_menu); ?>
                                    <small>
                                        <i class="ace-icon fa fa-angle-double-right"></i>
                                        <?= $page_name; ?>
                                    </small>
                                </h1>
                            </div><!-- /.page-header -->

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="tabbable">
                                        <ul class="nav nav-tabs" id="myTab">
                                            <li class="active">
                                                <a data-toggle="tab" href="#app">
                                                    <i class="blue ace-icon fa fa-check-square-o bigger-120"></i>
                                                    Application
                                                </a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#home">
                                                    <i class="blue ace-icon fa fa-home bigger-120"></i>
                                                    Company
                                                </a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#logo">
                                                    <i class="blue ace-icon fa fa-eye bigger-120"></i>
                                                    Logo
                                                </a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#mail">
                                                    <i class="blue ace-icon fa fa-envelope bigger-120"></i>
                                                    E-mail
                                                </a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#sms">
                                                    <i class="blue ace-icon fa fa-mobile bigger-120"></i>
                                                    SMS
                                                </a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#others">
                                                    <i class="blue ace-icon fa fa-tags bigger-120"></i>
                                                    Others
                                                </a>
                                            </li>
                                        </ul>

                                        <div class="tab-content">
                                            <div id="app" class="tab-pane fade in active">
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">App Code <span
                                                            class="text-danger">*</span></div>
                                                    <div class="col-md-2">
                                                        <input type="text" id="txt_app_code"
                                                            value="<?= $app_details->app_code; ?>"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">App Name <span
                                                            class="text-danger">*</span></div>
                                                    <div class="col-md-6">
                                                        <input type="text" id="txt_app_name"
                                                            value="<?= $app_details->app_name; ?>"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">App Version</div>
                                                    <div class="col-md-6">
                                                        <textarea id="txt_app_version"
                                                            class="form-control"><?= $app_details->app_version; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="row text-center" style="margin-top:1em;">
                                                    <div class="col-md-4 text-right">
                                                    </div>
                                                    <div class="col-md-4 text-left">
                                                        <button id="btn_save_app"
                                                            class="btn btn-sm btn-primary fa fa-check"> Save Changes
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>

                                            <div id="home" class="tab-pane fade in">

                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">Company Code <span
                                                            class="text-danger">*</span></div>
                                                    <div class="col-md-2">
                                                        <input type="text" id="txt_company_code"
                                                            value="<?= $app_details->company_code; ?>"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">Company Name <span
                                                            class="text-danger">*</span></div>
                                                    <div class="col-md-6">
                                                        <input type="text" id="txt_company_name"
                                                            value="<?= $app_details->company_name; ?>"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">Company Address</div>
                                                    <div class="col-md-6">
                                                        <textarea id="txt_company_address"
                                                            class="form-control"><?= $app_details->company_address; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">Contact No.</div>
                                                    <div class="col-md-6">
                                                        <input type="text" id="txt_company_contact"
                                                            value="<?= $app_details->company_contact; ?>"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">Contact Person</div>
                                                    <div class="col-md-6">
                                                        <input type="text" id="txt_contact_person"
                                                            value="<?= $app_details->contact_person; ?>"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="row text-center" style="margin-top:1em;">
                                                    <div class="col-md-4 text-right">
                                                    </div>
                                                    <div class="col-md-4 text-left">
                                                        <button id="btn_save_company"
                                                            class="btn btn-sm btn-primary fa fa-check"> Save Changes
                                                        </button>
                                                        <!-- <button id="btn_test_sms" class="btn btn-sm btn-warning fa fa-send"> Run Test </button> -->
                                                    </div>
                                                </div>

                                            </div>

                                            <div id="logo" class="tab-pane fade">

                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4"></div>
                                                    <div class="col-md-2 text-center">
                                                        <div class="thumbnail">
                                                            <div class="image view view-first">
                                                                <img id="img_company_logo"
                                                                    style="width: 100%; height: 100%; display: block;"
                                                                    src="<?= base_url() . 'assets/images/logo.' . $app_details->company_logo . '?' . time(); ?>"
                                                                    alt="No Logo" />
                                                            </div>

                                                            <?php
															if ($role_id == 1 || $this->custom_function->module_permission("upload", $module_permission)) {
																echo "	<div class='caption text-center'>
																			<form method='post' id='frm_logo' name='frm_logo' >
																				<div class='input-group'>
																					<span class='input-group-btn'>
																						<span class='btn btn-primary btn-file'>
																							<i class='fa fa-upload fa-fw'></i>Change
																							<input type='file' name='file' id='f_logo' required />
																						</span>
																					</span>
																				</div>
																			</form>
																		</div>";
															}
															?>

                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                            <div id="mail" class="tab-pane fade">
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">Email Protocol</div>
                                                    <div class="col-md-2">
                                                        <input type="text" id="txt_email_protocol"
                                                            value="<?= strtoupper($app_details->email_protocol); ?>"
                                                            class="form-control" disabled />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">SMTP Crypto</div>
                                                    <div class="col-md-2">
                                                        <select id="txt_email_crypto" class="form-control">
                                                            <?php
															if (strtolower($app_details->smtp_crypto) === "ssl") {
																echo "<option value='ssl' selected >SSL</option>";
															} else {
																echo "<option value='ssl' >SSL</option>";
															}

															if (strtolower($app_details->smtp_crypto) === "tls") {
																echo "<option value='tls' selected >TLS</option>";
															} else {
																echo "<option value='tls'>TLS</option>";
															}
															?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">SMTP Host</div>
                                                    <div class="col-md-4">
                                                        <input type="text" id="txt_email_host"
                                                            value="<?= $app_details->smtp_host; ?>" class="form-control"
                                                            placeholder="smtp.gmail.com" />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">SMTP User</div>
                                                    <div class="col-md-4 text-right">
                                                        <input type="text" id="txt_email_user"
                                                            value="<?= $app_details->smtp_user; ?>" class="form-control"
                                                            placeholder="support@gmail.com" />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">SMTP Password</div>
                                                    <div class="col-md-4 text-right">
                                                        <input type="password" id="txt_email_pass"
                                                            value="<?= $app_details->smtp_pass; ?>"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">SMTP Port</div>
                                                    <div class="col-md-2 text-right">
                                                        <input type="text" id="txt_email_port"
                                                            value="<?= $app_details->smtp_port; ?>"
                                                            class="numeric form-control" placeholder="465 or 587" />
                                                    </div>
                                                </div>

                                                <div class="row text-center" style="margin-top:1em;">
                                                    <div class="col-md-4 text-right">
                                                    </div>
                                                    <div class="col-md-4 text-left">
                                                        <button id="btn_save_email"
                                                            class="btn btn-sm btn-primary fa fa-check"> Save Changes
                                                        </button>
                                                        <button id="btn_test_email"
                                                            class="btn btn-sm btn-success fa fa-send"> Run Test
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="sms" class="tab-pane fade">
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-12">
                                                        <h5>
                                                            <p>
                                                                We use itextmo API for SMS notification, for more
                                                                details please check this
                                                                <a
                                                                    href="https://www.itexmo.com/Developers/index.php">link</a>.
                                                            </p>
                                                        </h5>
                                                        <p class="alert alert-danger">
                                                            Note: Trial API Code will be automatically expired if no
                                                            transaction for 5 days!
                                                        </p>
                                                    </div>

                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">API Code</div>
                                                    <div class="col-md-4">
                                                        <input type="text" id="txt_sms_api_code"
                                                            value="<?= $app_details->sms_api_code; ?>"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">Registered Mobile No.</div>
                                                    <div class="col-md-4">
                                                        <input type="text" id="txt_sms_mobile_no"
                                                            value="<?= $app_details->sms_mobile_no; ?>"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">Registered Email</div>
                                                    <div class="col-md-4">
                                                        <input type="text" id="txt_sms_email"
                                                            value="<?= $app_details->sms_email; ?>"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">Registered Name</div>
                                                    <div class="col-md-4">
                                                        <input type="text" id="txt_sms_name"
                                                            value="<?= $app_details->sms_name; ?>"
                                                            class="form-control" />
                                                    </div>
                                                </div>

                                                <div class="row text-center" style="margin-top:1em;">
                                                    <div class="col-md-4 text-right">
                                                    </div>
                                                    <div class="col-md-4 text-left">
                                                        <button id="btn_save_sms"
                                                            class="btn btn-sm btn-primary fa fa-check"> Save Changes
                                                        </button>
                                                        <button id="btn_test_sms"
                                                            class="btn btn-sm btn-success fa fa-send"> Run Test
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="others" class="tab-pane fade">
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">Timezone</div>
                                                    <div class="col-md-6">
                                                        <select id="txt_timezone" class="form-control">
                                                            <?php
															foreach ($timezone_list as $key => $row) {
																$id = $row->id;
																$val = $row->zone;

																if ($id === $app_details->timezone_id) {
																	echo "<option value='{$id}' selected >{$val}</option>";
																} else {
																	echo "<option value='{$id}'>{$val}</option>";
																}
															}
															?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">Currency</div>
                                                    <div class="col-md-6">
                                                        <select id="txt_currency" class="form-control">
                                                            <?php
															foreach ($currency_list as $key => $row) {
																$id = $row->id;
																$val = $row->code;
																$val1 = $row->currency;
																$val2 = $row->country;

																if ($id == $app_details->currency_id) {
																	echo "<option value='{$id}' selected >{$val} - {$val2} ({$val1})</option>";
																} else {
																	echo "<option value='{$id}'>{$val} - {$val2} ({$val1})</option>";
																}
															}
															?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">GST (%)</div>
                                                    <div class="col-md-2 text-right">
                                                        <input type="text" id="txt_gst"
                                                            value="<?= $app_details->gst_percent; ?>"
                                                            class="numeric form-control  text-right" value="10.00" />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">Queue Countdown (Seconds)</div>
                                                    <div class="col-md-2 text-right">
                                                        <input type="text" id="txt_countdown"
                                                            value="<?= $app_details->timer_countdown; ?>"
                                                            class="numeric form-control  text-center" value="120" />
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom:0.5em;">
                                                    <div class="col-md-4 text-right">Session Prefix</div>
                                                    <div class="col-md-2 text-right">
                                                        <input type="text" id="txt_session_prefix"
                                                            value="<?= $app_details->session_prefix; ?>"
                                                            class="form-control" />
                                                    </div>
                                                </div>


                                                <div class="row text-center" style="margin-top:1em;">
                                                    <div class="col-md-4 text-right">
                                                    </div>
                                                    <div class="col-md-4 text-left">
                                                        <button id="btn_save_others"
                                                            class="btn btn-sm btn-primary fa fa-check"> Save Changes
                                                        </button>
                                                        <!-- <button id="btn_test_sms" class="btn btn-sm btn-warning fa fa-send"> Run Test </button> -->
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="col-xs-12 text-center">
									<button id="btn_save" class="btn btn-primary fa fa-check "> Save Changes </button>
								</div> -->

                            </div><!-- /.row -->

                            <!-- PAGE CONTENT ENDS -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.page-content -->
            </div>
        </div><!-- /.main-content -->

        <?php
		$this->load->view('maintenance_settings/modal_send_email');
		$this->load->view('maintenance_settings/modal_send_sms');

		$this->load->view('template/footer');
		$this->load->view('template/loading');
		?>

        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
            <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
        </a>
    </div><!-- /.main-container -->

    <!-- basic scripts -->
    <?php
	$this->load->view('template/script');
	?>

    <!-- inline scripts related to this page -->
    <script>
    //start module
    $('#txt_email_message').ace_wysiwyg();

    $(document).on('click', '#btn_save_app', function() {
        var app_code = $("#txt_app_code").val();
        var app_name = $("#txt_app_name").val();
        var app_version = $("#txt_app_version").val();

        $("#loading").modal();

        $.post("<?= base_url(); ?>maintenance_settings/app_save", {
            app_code: app_code,
            app_name: app_name,
            app_version: app_version
        }, function(data) {
            $("#loading").modal('hide');
            if (data.indexOf("<!DOCTYPE html>") > -1) {
                alert("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else if (data.indexOf("Error: ") > -1) {
                bootbox.alert(data);
            } else {
                bootbox.alert("Successfully Saved!");
            }
        });
    });

    $(document).on('click', '#btn_save_company', function() {
        var company_code = $("#txt_company_code").val();
        var company_name = $("#txt_company_name").val();
        var company_address = $("#txt_company_address").val();
        var company_contact = $("#txt_company_contact").val();
        var contact_person = $("#txt_contact_person").val();

        $("#loading").modal();

        $.post("<?= base_url(); ?>maintenance_settings/company_save", {
            company_code: company_code,
            company_name: company_name,
            company_address: company_address,
            company_contact: company_contact,
            contact_person: contact_person,
        }, function(data) {
            $("#loading").modal('hide');
            if (data.indexOf("<!DOCTYPE html>") > -1) {
                alert("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else if (data.indexOf("Error: ") > -1) {
                bootbox.alert(data);
            } else {
                bootbox.alert("Successfully Saved!");
            }
        });
    });

    $(document).on('click', '#btn_save_email', function() {
        var smtp_crypto = $("#txt_email_crypto").val();
        var smtp_host = $("#txt_email_host").val();
        var smtp_user = $("#txt_email_user").val();
        var smtp_pass = $("#txt_email_pass").val();
        var smtp_port = $("#txt_email_port").val();

        $("#loading").modal();

        $.post("<?= base_url(); ?>maintenance_settings/email_save", {
            smtp_crypto: smtp_crypto,
            smtp_host: smtp_host,
            smtp_user: smtp_user,
            smtp_pass: smtp_pass,
            smtp_port: smtp_port,
        }, function(data) {
            $("#loading").modal('hide');
            if (data.indexOf("<!DOCTYPE html>") > -1) {
                alert("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else if (data.indexOf("Error: ") > -1) {
                bootbox.alert(data);
            } else {
                bootbox.alert("Successfully Saved!");
            }
        });
    });

    $(document).on('click', '#btn_test_email', function() {
        $("#modal_send_email").modal();

        $("#modal_send_email").on("shown.bs.modal", function() {
            $(".txt_field_email").val("");
            $(".txt_field_email").html("");
            $("#txt_email_to").focus().select();
        });
    });

    $(document).on('click', '#btn_send_email', function() {
        var to = $('#txt_email_to').val().replace(" ", "");
        var cc = $('#txt_email_cc').val().replace(" ", "");
        var bcc = $('#txt_email_bcc').val().replace(" ", "");
        var subject = $('#txt_email_subject').val().trim();
        var message = $('#txt_email_message').html();

        if (to && subject && message) {
            $(".modal-body, .modal_error, .modal_button").hide();
            $(".modal_waiting").show();

            $.post("<?= base_url(); ?>maintenance_settings/email_send", {
                to: to,
                cc: cc,
                bcc: bcc,
                subject: subject,
                message: message
            }, function(data) {
                $(".modal_error, .modal_waiting").hide();
                $(".modal-body, .modal_button").show();

                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    data = "Error: Unable to send Email";
                    $(".modal_error_msg").text(data);
                    $(".modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
                    $("#txt_email_to").trigger("select", "focus");
                } else {
                    $("#modal_send_email").modal("hide");
                    bootbox.alert("Successfully Send!");
                }
            });
        } else {
            bootbox.alert("Error: Fields with red asterisk (*) are required!");
        }
    });

    $(document).on('click', '#btn_save_sms', function() {
        var api_code = $("#txt_sms_api_code").val();
        var mobile_no = $("#txt_sms_mobile_no").val();
        var email = $("#txt_sms_email").val();
        var name = $("#txt_sms_name").val();

        $("#loading").modal();

        $.post("<?= base_url(); ?>maintenance_settings/sms_save", {
            sms_api_code: api_code,
            sms_mobile_no: mobile_no,
            sms_email: email,
            sms_name: name
        }, function(data) {
            $("#loading").modal('hide');
            if (data.indexOf("<!DOCTYPE html>") > -1) {
                alert("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else if (data.indexOf("Error: ") > -1) {
                bootbox.alert(data);
            } else {
                bootbox.alert("Successfully Saved!");
            }
        });
    });

    $(document).on('click', '#btn_test_sms', function() {
        $("#modal_send_sms").modal();

        $("#modal_send_sms").on("shown.bs.modal", function() {
            $(".txt_field_sms").val("");
            $("#txt_sms_to").focus().select();
        });
    });

    $(document).on('click', '#btn_send_sms', function() {
        var to = $('#txt_sms_to').val().trim().replace(" ", "");
        var message = $('#txt_sms_message').val().trim();

        if (to && message) {
            $(".modal-body, .modal_error, .modal_button").hide();
            $(".modal_waiting").show();

            $.post("<?= base_url(); ?>maintenance_settings/sms_send", {
                to: to,
                message: message
            }, function(data) {
                $(".modal_error, .modal_waiting").hide();
                $(".modal-body, .modal_button").show();

                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    //data = "Error: Unable to send Email";
                    $(".modal_error_msg").text(data);
                    $(".modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
                    $("#txt_sms_to").trigger("select", "focus");
                } else {
                    $("#modal_send_sms").modal("hide");
                    bootbox.alert(data);
                }
            });
        } else {
            bootbox.alert("Error: Fields with red asterisk (*) are required!");
        }
    });

    $(document).on('click', '#btn_save_others', function() {
        var timezone_id = $("#txt_timezone").val();
        var currency_id = $("#txt_currency").val();
        var gst_percent = $("#txt_gst").val();
        var timer_countdown = $("#txt_countdown").val();
        var session_prefix = $("#txt_session_prefix").val();

        $("#loading").modal();

        $.post("<?= base_url(); ?>maintenance_settings/others_save", {
            timezone_id: timezone_id,
            currency_id: currency_id,
            gst_percent: gst_percent,
            timer_countdown: timer_countdown,
            session_prefix: session_prefix
        }, function(data) {
            $("#loading").modal('hide');
            if (data.indexOf("<!DOCTYPE html>") > -1) {
                alert("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else if (data.indexOf("Error: ") > -1) {
                bootbox.alert(data);
            } else {
                bootbox.alert("Successfully Saved!");
            }
        });
    });

    $(document).on('click', '#btn_save', function() {
        var company_code = $("#txt_company_code").val();
        var company_name = $("#txt_company_name").val();
        var company_address = $("#txt_company_address").val();
        var company_contact = $("#txt_company_contact").val();
        var contact_person = $("#txt_contact_person").val();

        var smtp_crypto = $("#txt_email_crypto").val();
        var smtp_host = $("#txt_email_host").val();
        var smtp_user = $("#txt_email_user").val();
        var smtp_pass = $("#txt_email_pass").val();
        var smtp_port = $("#txt_email_port").val();

        var timezone_id = $("#txt_timezone").val();
        var currency_id = $("#txt_currency").val();
        var gst_percent = $("#txt_gst").val();
        var countdown_timer = $("#txt_countdown").val();

        if (company_code && company_name) {
            $("#loading").modal();

            $.post("<?= base_url(); ?>maintenance_settings/save", {
                company_code: company_code,
                company_name: company_name,
                company_address: company_address,
                company_contact: company_contact,
                contact_person: contact_person,
                smtp_crypto: smtp_crypto,
                smtp_host: smtp_host,
                smtp_user: smtp_user,
                smtp_pass: smtp_pass,
                smtp_port: smtp_port,
                timezone_id: timezone_id,
                currency_id: currency_id,
                gst_percent: gst_percent,
                countdown_timer: countdown_timer
            }, function(data) {
                $("#loading").modal('hide');
                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    bootbox.alert(data);
                } else {
                    bootbox.alert("Successfully Saved!");
                }
            });
        } else {
            bootbox.alert("Error: Fields with red asterisk are required!");
        }
    });

    //change logo
    $(document).on('change', '#f_logo', function(e) {
        e.preventDefault();
        var filename = $('#f_logo').val();

        if (filename) {
            var fd = new FormData(document.getElementById("frm_logo"));

            $("#loading").modal();

            $.ajax({
                url: "<?= base_url(); ?>maintenance_settings/change_logo",
                type: "POST",
                data: fd,
                enctype: 'multipart/form-data',
                processData: false, // tell jQuery not to process the data
                contentType: false // tell jQuery not to set contentType
            }).done(function(data) {
                $("#loading").modal("hide");

                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    bootbox.alert(data);
                } else {
                    $('#f_logo').val('');
                    var d = new Date();

                    $("#img_company_logo").attr('src', data + '?' + d.getTime());
                }
            });
            return false;
        } else {
            $("#emp_success_edit").css('display', 'none');
            $("#emp_error_msg_edit").text("Error: Upload problem - No Photo Selected!");
            $("#emp_error_edit").stop(true, true).show().delay(15000).fadeOut("slow");
        }


    });

    //end module
    </script>
</body>

</html>