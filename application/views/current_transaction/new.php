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
                                        <i class="ace-icon fa fa-angle-double-right"></i>
                                        New Entry
                                    </small>
                                </h1>
                            </div><!-- /.page-header -->

                            <div class="row" style="padding: 0 2em 2em 2em;">
                                <form id="form_new">
                                    <div class="col-12">
                                        <div class="row" style="margin-bottom:1em;">
                                            <div class="col-md-12">
                                                <span class="label label-lg label-info arrowed-right">
                                                    <i class="fa fa-info-circle fa-fw"></i>
                                                    Note: Fields with red asterisk are required!
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom:1em;">
                                            <div class="col-md-2 text-right">Date <i class="text-danger">*</i></div>
                                            <div class="col-md-2">
                                                <input type="text" id="date_new" name="date_new"
                                                    value="<?= date('Y-m-d'); ?>" class="form-control field_new"
                                                    autocomplete="off" readonly />
                                            </div>

                                            <div class="col-md-1 text-right">Status</div>
                                            <div class="col-md-2">
                                                <input type="text" id="status_new" name="status_new"
                                                    class="form-control field_new" value="UNSAVED" readonly disabled />
                                            </div>

                                            <div class="col-md-2 text-right">Trans Type <i class="text-danger">*</i>
                                            </div>
                                            <div class="col-md-3">
                                                <select id="trans_type_id_new" name="trans_type_id_new"
                                                    class="chosen-select form-control field_new">
                                                    <option value="">-- SELECT --</option>
                                                    <?php
													foreach ($trans_types as $key => $value) {
														echo "<option value='{$value->id}'>{$value->trans_type}</option>";
													}
													?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-bottom:1em;">
                                            <div class="col-md-2 text-right">Patient <i class="text-danger">*</i></div>
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <select id="patient_id_new" name="patient_id_new"
                                                        class="chosen-select form-control field_new">
                                                        <option value="">-- SELECT --</option>
                                                        <?php
														foreach ($patients as $key => $value) {
															echo "<option value='{$value->id}'>[{$value->code}] - {$value->patient}</option>";
														}
														?>
                                                    </select>
                                                    <span class="input-group-btn">
                                                        <button id="btn_add_patient" class="btn btn-sm btn-default"
                                                            type="button">
                                                            Add New
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-2 text-right">Pay Method <i class="text-danger">*</i>
                                            </div>
                                            <div class="col-md-3">
                                                <select id="payment_method_id_new" name="payment_method_id_new"
                                                    class="chosen-select form-control field_new">
                                                    <option value="">-- SELECT --</option>
                                                    <?php
													foreach ($payment_methods as $key => $value) {
														echo "<option value='{$value->id}'>{$value->payment_method}</option>";
													}
													?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-bottom:1em;">
                                            <div class="col-md-2 text-right">Company</div>
                                            <div class="col-md-5">
                                                <div class="input-group">
                                                    <select id="client_id_new" name="client_id_new"
                                                        class="chosen-select form-control field_new">
                                                        <option value="">-- SELECT --</option>
                                                        <?php
														foreach ($clients as $key => $value) {
															echo "<option value='{$value->id}'>{$value->name}</option>";
														}
														?>
                                                    </select>
                                                    <span class="input-group-btn">
                                                        <button id="btn_add_client" class="btn btn-sm btn-default"
                                                            type="button">
                                                            Add New
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-2 text-right">Charge To <i class="text-danger">*</i>
                                            </div>
                                            <div class="col-md-3">
                                                <select id="charging_type_id_new" name="charging_type_id_new"
                                                    class="chosen-select form-control field_new">
                                                    <option value="">-- SELECT --</option>
                                                    <?php
													foreach ($charging_types as $key => $value) {
														echo "<option value='{$value->id}'>{$value->charging_type}</option>";
													}
													?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-bottom:1em;">
                                            <div class="col-md-2 text-right">Insurance</div>
                                            <div class="col-md-5">
                                                <select id="insurance_id_new" name="insurance_id_new"
                                                    class="chosen-select form-control field_new">
                                                    <option value='0'>No Insurance</option>
                                                    <?php
													foreach ($insurances as $key => $value) {
														echo "<option value='{$value->id}'>{$value->name}</option>";
													}
													?>
                                                </select>
                                            </div>

                                            <div class="col-md-2 text-right">P.O</div>
                                            <div class="col-md-3">
                                                <input type="text" id="po_no_new" name="po_no_new"
                                                    class="form-control field_new" value="" />
                                            </div>
                                        </div>

                                        <div class="row" style="margin-bottom:1em;">
                                            <div class="col-md-2 text-right">Location</div>
                                            <div class="col-md-2">
                                                <select id="location_id_new" name="location_id_new"
                                                    class="chosen-select form-control field_new" disabled>
                                                    <option value="">-- SELECT --</option>
                                                    <?php
													foreach ($locations as $key => $value) {
														if ($value->id == 1) { // Reception
															echo "<option value='{$value->id}' selected>{$value->location}</option>";
														} else {
															echo "<option value='{$value->id}'>{$value->location}</option>";
														}
													}
													?>
                                                </select>
                                            </div>

                                            <div class="col-md-1 text-right">Queue</div>
                                            <div class="col-md-2">
                                                <select id="queue_id_new" name="queue_id_new"
                                                    class="chosen-select form-control field_new" disabled>
                                                    <?php
													foreach ($queues as $key => $value) {
														if ($value->id == 1) { //waiting
															echo "<option value='{$value->id}' selected>{$value->queue}</option>";
														} else {
															echo "<option value='{$value->id}'>{$value->queue}</option>";
														}
													}
													?>
                                                </select>
                                            </div>

                                            <div class="col-md-2 text-right">Assigned Doctor</div>
                                            <div class="col-md-3">
                                                <select id="doctor_id_new" name="doctor_id_new"
                                                    class="chosen-select form-control field_new">
                                                    <option value="0">NONE</option>
                                                    <?php
													foreach ($doctors as $key => $value) {
														echo "<option value='{$value->id}'>{$value->name}</option>";
													}
													?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-bottom:1em;">
                                            <div class="col-md-2 text-right">Remarks</div>
                                            <div class="col-md-5">
                                                <textarea id="remarks_new" name="remarks_new"
                                                    class="form-control field_new"></textarea>
                                            </div>

                                            <div class="col-md-2 text-right">Diagnosis</div>
                                            <div class="col-md-3">
                                                <textarea id="diagnosis_new" name="diagnosis_new"
                                                    class="form-control field_new"></textarea>
                                            </div>
                                        </div>

                                        <!-- BUTTONS -->
                                        <div class="row" style="margin-top:2em;">
                                            <div class="col-md-12 text-left">
                                                <?php
												echo "<a href='" . base_url() . "current_transaction' class='btn btn-default'>
														<i class='fa fa-arrow-left fa-fw'></i>
														Back
													</a>
													<a id='btn_confirm' class='btn btn-primary'>
														<i class='fa fa-check fa-fw'></i>
														Confirm
													</a> ";
												?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- /.row -->

                            <!-- PAGE CONTENT ENDS -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.page-content -->
            </div>
        </div><!-- /.main-content -->

        <?php

		$this->load->view("template/footer");
		$this->load->view("template/loading");
		?>

        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
            <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
        </a>
    </div><!-- /.main-container -->

    <!-- basic scripts -->
    <?php
	$this->load->view('template/script');
	?>

    <script>
    //handle flash messages
    <?php
		if ($this->session->flashdata('error')) {
			echo "bootbox.alert('" . $this->session->flashdata('error') . "');";
		}

		if ($this->session->flashdata('message')) {
			echo "bootbox.alert('" . $this->session->flashdata('message') . "');";
		}
		?>
    </script>


    <!-- inline scripts related to this page -->
    <script>
    let log_data = [{
        key: "Date",
        value: $("#date_new").val()
    }];

    //functions
    function write_to_log(key_name, element_id, element_type = "select") {
        // Get the selected value from the element
        let selectedValue = (element_type === "select") ? $(element_id + " option:selected").text() : $(element_id)
            .val();

        // Check if 'item3' exists in the array
        let itemIndex = log_data.findIndex(function(item) {
            return item.key === key_name;
        });

        // If key_name exists, update its value; otherwise, add a new entry
        if (itemIndex !== -1) {
            log_data[itemIndex].value = selectedValue;
        } else {
            log_data.push({
                key: key_name,
                value: selectedValue
            });
        }
    }

    $(document).ready(function() {

        $("#date_new").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true,
            yearRange: "c-100:c+10",
        });

        $('.chosen-select').chosen({
            allow_single_deselect: true
        });
    });

    $(document).on("click", "#btn_add_patient", function() {
        bootbox.alert("Please goto DATA -> PATIENT, to add new patient name.")
    });

    $(document).on("click", "#btn_add_client", function() {
        bootbox.alert("Please goto DATA -> CLIENT, to add new company name.")
    });

    //region transaction buttons
    $(document).on("click", "#btn_confirm", function() {
        let dt = $("#date_new").val();
        let patient_id = $("#patient_id_new").val();
        let trans_type_id = $("#trans_type_id_new").val();
        let payment_method_id = $("#payment_method_id_new").val();
        let charging_type_id = $("#charging_type_id_new").val();

        if (dt && patient_id && trans_type_id && trans_type_id && payment_method_id && charging_type_id) {
            bootbox.confirm("Are you sure you want to confirm this transaction?", function(result) {
                if (result) {
                    $("#loading").modal();

                    // Serialize the form data
                    var formData = $("#form_new").serialize();

                    //append logs
                    for (var i = 0; i < log_data.length; i++) {
                        formData += "&logs[" + log_data[i].key + "]=" + log_data[i].value;
                    }

                    $.post("<?= base_url($module); ?>/save", formData, function(data) {
                        if (data.indexOf("<!DOCTYPE html>") > -1) {
                            alert("Error: Session Time-Out, You must login again to continue.");
                            location.reload(true);
                        } else {
                            let result = JSON.parse(data);

                            if (result.success == true) {
                                window.location = "<?= base_url($module); ?>/view/" + result
                                    .data;
                            } else {
                                $("#loading").modal("hide");
                                bootbox.alert(result.error);
                            }
                        }
                    });
                }
            });
        } else {
            bootbox.alert("Error: All fields with red asterisk are required!");
        }
    });
    //endregion transaction buttons

    //event change
    $(document).on("change", "#date_new", function() {
        write_to_log("Date", "#date_new", "text");
    });
    $(document).on("change", "#trans_type_id_new", function() {
        write_to_log("Trans_Type", "#trans_type_id_new", "select");
    });
    $(document).on("change", "#patient_id_new", function() {
        write_to_log("Patient", "#patient_id_new", "select");
    });
    $(document).on("change", "#payment_method_id_new", function() {
        write_to_log("Pay_Method", "#payment_method_id_new", "select");
    });
    $(document).on("change", "#client_id_new", function() {
        write_to_log("Company", "#client_id_new", "select");
    });
    $(document).on("change", "#charging_type_id_new", function() {
        write_to_log("Charge_To", "#charging_type_id_new", "select");
    });
    $(document).on("change", "#insurance_id_new", function() {
        write_to_log("Insurance", "#insurance_id_new", "select");
    });
    $(document).on("change", "#po_no_new", function() {
        write_to_log("PO_No", "#po_no_new", "text");
    });
    $(document).on("change", "#doctor_id_new", function() {
        write_to_log("Doctor", "#doctor_id_new", "select");
    });
    $(document).on("change", "#remarks_new", function() {
        write_to_log("Remarks", "#remarks_new", "text");
    });
    $(document).on("change", "#diagnosis_new", function() {
        write_to_log("Remarks", "#diagnosis_new", "text");
    });
    //end of change event
    </script>

</body>

</html>
