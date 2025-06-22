<?php
//variables
$subtotal_amount = 0;
$subtotal_commission = 0;
$subtotal_insurance = 0;
$subtotal_total = 0;
//$total_paid = $total_paid;
$total_amount_due = $subtotal_total;
?>

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
	
	<style>
	/* Custom styles for enhanced delete confirmation */
	.bootbox-warning .modal-header {
		background-color: #f0ad4e;
		color: white;
		border-bottom: 1px solid #eea236;
	}
	
	.bootbox-warning .modal-header .bootbox-close-button {
		color: white;
		opacity: 0.8;
	}
	
	.bootbox-warning .modal-body .alert-warning {
		border-left: 4px solid #f0ad4e;
		background-color: #fdf6e3;
		border-color: #faebcc;
	}
	
	.bootbox-warning .modal-body .alert-warning h4 {
		color: #8a6d3b;
		margin-top: 0;
	}
	
	.bootbox-warning .modal-body .fa-warning {
		color: #f0ad4e;
		margin-right: 15px;
		margin-top: 5px;
	}
	
	/* Custom confirm dialog styling */
	.bootbox .modal-footer .btn-danger {
		background-color: #d9534f;
		border-color: #d43f3a;
	}
	
	.bootbox .modal-footer .btn-danger:hover {
		background-color: #c9302c;
		border-color: #ac2925;
	}

	/* Delete reason modal styling */
	#modal_delete_reason .modal-header.bg-danger {
		background-color: #d9534f !important;
		color: white;
		border-bottom: 1px solid #d43f3a;
	}

	#modal_delete_reason .modal-header.bg-danger .close {
		color: white;
		opacity: 0.8;
		text-shadow: none;
	}

	#modal_delete_reason .modal-header.bg-danger .close:hover {
		opacity: 1;
	}

	#modal_delete_reason .alert-warning {
		border-left: 4px solid #f0ad4e;
		background-color: #fcf8e3;
		border-color: #faebcc;
	}

	#modal_delete_reason .well {
		background-color: #f8f9fa;
		border: 1px solid #dee2e6;
		border-radius: 4px;
		padding: 15px;
		margin: 10px 0;
	}

	#modal_delete_reason .table-condensed td {
		padding: 5px 8px;
		border: none;
		border-bottom: 1px solid #eee;
	}

	#modal_delete_reason .table-condensed td:first-child {
		width: 120px;
		font-weight: bold;
		color: #555;
	}

	#modal_delete_reason textarea {
		resize: vertical;
		min-height: 80px;
	}

	#modal_delete_reason textarea:focus {
		border-color: #d9534f;
		box-shadow: 0 0 0 0.2rem rgba(217, 83, 79, 0.25);
	}
	</style>

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
                                        View
                                        <span id="lbl_transaction_no" class="badge badge-warning"><?= $transaction_no; ?></span>
                                        <input type="hidden" id="id_update" name="id_update" value="<?= $transaction_id; ?>" />
                                    </small>
                                </h1>
                            </div><!-- /.page-header -->

                            <div class="row" style="padding: 0 2em 2em 2em;">
                                <div class="col-12">
                                    <div class="row" style="margin-bottom:1em;">
                                        <div class="col-md-12">
                                            <span class="label label-lg label-info arrowed-right">
                                                <i class="fa fa-info-circle fa-fw"></i>
                                                Note: All fields are auto-save!
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-bottom:1em;">
                                        <div class="col-md-2 text-right">Date <i class="text-danger">*</i></div>
                                        <div class="col-md-2">
                                            <input type="text" id="date_update" name="date_update" value="<?= $record->date; ?>" class="form-control field_update" autocomplete="off" readonly <?= $disabled_field; ?> />
                                        </div>

                                        <div class="col-md-1 text-right">Status</div>
                                        <div class="col-md-2">
                                            <input type="text" id="status_update" name="status_update" class="form-control field_update" value="<?= $record->status; ?>" readonly disabled />
                                        </div>

                                        <div class="col-md-2 text-right">Trans Type <i class="text-danger">*</i></div>
                                        <div class="col-md-3">
                                            <select id="trans_type_id_update" name="trans_type_id_update" class="chosen-select form-control field_update" <?= $disabled_field; ?>>
                                                <option value="">-- SELECT --</option>
                                                <?php
												foreach ($trans_types as $key => $value) {
													if ($value->id == $record->trans_type_id) {
														echo "<option value='{$value->id}' selected>{$value->trans_type}</option>";
													} else {
														echo "<option value='{$value->id}'>{$value->trans_type}</option>";
													}
												}
												?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-bottom:1em;">
                                        <div class="col-md-2 text-right">Patient <i class="text-danger">*</i></div>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <select id="patient_id_update" name="patient_id_update" class="chosen-select form-control field_update" <?= $disabled_field; ?>>
                                                    <option value="">-- SELECT --</option>
                                                    <?php
													foreach ($patients as $key => $value) {
														if ($value->id == $record->patient_id) {
															echo "<option value='{$value->id}' selected>[{$value->code}] - {$value->patient}</option>";
														} else {
															echo "<option value='{$value->id}'>[{$value->code}] - {$value->patient}</option>";
														}
													}
													?>
                                                </select>
                                                <span class="input-group-btn">
                                                    <button id="btn_add_patient" class="btn btn-sm btn-default" type="button" <?= $disabled_field; ?>>
                                                        Add New
                                                    </button>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-2 text-right">Pay Method <i class="text-danger">*</i></div>
                                        <div class="col-md-3">
                                            <select id="payment_method_id_update" name="payment_method_id_update" class="chosen-select form-control field_update" <?= $disabled_field; ?>>
                                                <option value="">-- SELECT --</option>
                                                <?php
												foreach ($payment_methods as $key => $value) {
													if ($value->id == $record->payment_method_id) {
														echo "<option value='{$value->id}' selected>{$value->payment_method}</option>";
													} else {
														echo "<option value='{$value->id}'>{$value->payment_method}</option>";
													}
												}
												?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-bottom:1em;">
                                        <div class="col-md-2 text-right">Company</div>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <select id="client_id_update" name="client_id_update" class="chosen-select form-control field_update" <?= $disabled_field; ?>>
                                                    <option value="">-- SELECT --</option>
                                                    <?php
													foreach ($clients as $key => $value) {
														if ($value->id == $record->client_id) {
															echo "<option value='{$value->id}' selected>{$value->name}</option>";
														} else {
															echo "<option value='{$value->id}'>{$value->name}</option>";
														}
													}
													?>
                                                </select>
                                                <span class="input-group-btn">
                                                    <button id="btn_add_client" class="btn btn-sm btn-default" type="button" <?= $disabled_field; ?>>
                                                        Add New
                                                    </button>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-2 text-right">Charge To <i class="text-danger">*</i></div>
                                        <div class="col-md-3">
                                            <select id="charging_type_id_update" name="charging_type_id_update" class="chosen-select form-control field_update" <?= $disabled_field; ?>>
                                                <option value="">-- SELECT --</option>
                                                <?php
												foreach ($charging_types as $key => $value) {
													if ($value->id == $record->charging_type_id) {
														echo "<option value='{$value->id}' selected>{$value->charging_type}</option>";
													} else {
														echo "<option value='{$value->id}'>{$value->charging_type}</option>";
													}
												}
												?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-bottom:1em;">
                                        <div class="col-md-2 text-right">Insurance</div>
                                        <div class="col-md-5">
                                            <select id="insurance_id_update" name="insurance_id_update" class="chosen-select form-control field_update" <?= $disabled_field; ?>>
                                                <option value='0'>No Insurance</option>
                                                <?php
												foreach ($insurances as $key => $value) {
													if ($value->id == $record->insurance_id) {
														echo "<option value='{$value->id}' selected>{$value->name}</option>";
													} else {
														echo "<option value='{$value->id}'>{$value->name}</option>";
													}
												}
												?>
                                            </select>
                                            <input type="hidden" id="hidden_insurance_value_type_id" value="1" />
                                            <input type="hidden" id="hidden_insurance_value" value="0" />
                                            <input type="hidden" id="hidden_insurance_commission_type_id" value="1" />
                                            <input type="hidden" id="hidden_insurance_commission_value" value="0" />
                                        </div>

                                        <div class="col-md-2 text-right">P.O</div>
                                        <div class="col-md-3">
                                            <input type="text" id="po_no_update" name="po_no_update" class="form-control field_update" value="<?= $record->po_no; ?>" <?= $disabled_field; ?> />
                                        </div>
                                    </div>

                                    <div class="row" style="margin-bottom:1em;">
                                        <div class="col-md-2 text-right">Location</div>
                                        <div class="col-md-2">
                                            <select id="location_id_update" name="location_id_update" class="chosen-select form-control field_update" disabled>
                                                <option value="">-- SELECT --</option>
                                                <?php
												foreach ($locations as $key => $value) {
													if ($value->id == $record->location_id) {
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
                                            <select id="queue_id_update" name="queue_id_update" class="chosen-select form-control field_update" disabled>
                                                <option value="0">NONE</option>
                                                <?php
												foreach ($queues as $key => $value) {
													if ($value->id == $record->queue_id) {
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
                                            <select id="doctor_id_update" name="doctor_id_update" class="chosen-select form-control field_update" <?= $disabled_field2; ?>>
                                                <option value="0">NONE</option>
                                                <?php
												foreach ($doctors as $key => $value) {
													if ($value->id == $record->doctor_id) {
														echo "<option value='{$value->id}' selected>{$value->name}</option>";
													} else {
														echo "<option value='{$value->id}'>{$value->name}</option>";
													}
												}
												?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row" style="margin-bottom:1em;">
                                        <div class="col-md-2 text-right">Remarks</div>
                                        <div class="col-md-5">
                                            <textarea id="remarks_update" name="remarks_update" class="form-control field_update" <?= $disabled_field2; ?>><?= $record->remarks; ?></textarea>
                                        </div>

                                        <div class="col-md-2 text-right">Diagnosis</div>
                                        <div class="col-md-3">
                                            <textarea id="diagnosis_update" name="diagnosis_update" class="form-control field_update" <?= $disabled_field2; ?>><?= $record->diagnosis; ?></textarea>
                                        </div>
                                    </div>

                                    <!-- BUTTONS -->
                                    <div class="row" style="margin-bottom:1em;">
                                        <div class="col-md-12 text-center">
                                            <?php
											echo "<a href='" . base_url($module) . "' class='btn btn-default' title='Back to List'>
                                                    <i class='fa fa-arrow-left fa-fw'></i>
                                                    Back
                                                  </a> ";

											echo "<button id='btn_history' class='btn btn-default' title='Patient History Record'>
                                                  <i class='fa fa-clock-o fa-fw'></i>
                                                  History
                                                </button> ";

											if ($transaction_status_id >= 2) { //draft

												//modify
												if ($role_id == 1 || $this->custom_function->module_permission("modify", $module_permission)) {
													echo "<a
															id='btn_modify'
															href='" . base_url($module) . "/modify/{$transaction_id}' class='btn btn-warning'
															title='Modify Transaction'
															>
																<i class='fa fa-pencil fa-fw'></i>
																Modify
															</a> ";
												}

												//export to pdf shown
												if ($role_id == 1 || $this->custom_function->module_permission("print", $module_permission)) {
													echo "<a
                                                        id='btn_print'
                                                        href='" . base_url($module) . "/print_charges/{$transaction_id}' class='btn btn-warning'
                                                        target='_blank'
														title='Print Charge Slip'
                                                    	>
                                                        	<i class='fa fa-print fa-fw'></i>
                                                            Print
                                                        </a> ";
												}

												//export insurance invoice
												if ($role_id == 1 || $this->custom_function->module_permission("insurance invoice", $module_permission)) {
													echo "<a
														id='btn_insurance_invoice'
														href='" . base_url($module) . "/print_insurance_invoice/{$transaction_id}' class='btn btn-warning'
														target='_blank'
														title='Print Insurance Invoice'
													  >
														<i class='fa fa-heart fa-fw'></i>
														Insurance Invoice
													</a> ";
												}

												//send to
												if ($role_id == 1 || $this->custom_function->module_permission("send", $module_permission)) {
													echo "<button id='btn_send' class='btn btn-info' title='Transfer to other area'>
															<i class='fa fa-arrow-right fa-fw'></i>
															Send To
														</button> ";
												}

												//payment view
												if ($role_id == 1 || $this->custom_function->module_permission("payment view", $module_permission)) {
													echo "<button id='btn_payment' class='btn btn-info' title='Add / View Payment'>
															<i class='fa fa-credit-card fa-fw'></i>
															Payment
														</button> ";
												}

												//xray
												if ($role_id == 1 || $this->custom_function->module_permission("xray result", $module_permission)) {
													echo "<button id='btn_xray' class='btn btn-info' title='Show X-Ray Result'>
															<i class='fa fa-times-circle fa-fw'></i>
															Xray Result
														</button> ";
												}

												//lab result
												if ($role_id == 1 || $this->custom_function->module_permission("lab result", $module_permission)) {
													echo "<button id='btn_lab' class='btn btn-info' title='Show Laboratory Result'>
															<i class='fa fa-flask fa-fw'></i>
															Lab Result
														</button> ";
												}

												//prescription
												if ($role_id == 1 || $this->custom_function->module_permission("prescription", $module_permission)) {
													echo "<button id='btn_prescription' class='btn btn-info' title='Add / View Prescription' >
															<i class='fa fa-glass fa-fw'></i>
															Prescription
														</button> ";
												}

												//mark as completed
												if ($role_id == 1 || $this->custom_function->module_permission("complete", $module_permission)) {
													echo "<button id='btn_completed' class='btn btn-success' title='Mark this transaction as completed' >
															<i class='fa fa-check fa-fw'></i>
															Completed
														</button> ";
												}

												//cancel transaction
												if ($role_id == 1 || $this->custom_function->module_permission("cancel", $module_permission)) {
													echo "<button id='btn_transaction_cancel' class='btn btn-danger' title='Cancel this transaction' >
															<i class='fa fa-times fa-fw'></i>
															Cancel
														</button> ";
												}
											}
											?>
                                        </div>
                                    </div>

                                    <!-- ITEMS -->
                                    <div class="row" style="margin-top:1em;">
                                        <div class="col-md-12">

                                            <table id="tbl_list" class="table table-bordered table-stripe table-hover table-fixed-header" style="font-size:90%">
                                                <thead class="header">
                                                    <tr>
                                                        <th class="text-center">NO.</th>
                                                        <th class="text-center">SERIES</th>
                                                        <th class="text-center">PRODUCT / SERVICES</th>
                                                        <th class="text-center">CATEGORY</th>
                                                        <th class="text-center">QTY</th>
                                                        <th class="text-center">UOM</th>
                                                        <th class="text-center">UNIT PRICE</th>
                                                        <th class="text-center">AMOUNT</th>
                                                        <th class="text-center">COMMISSION</th>
                                                        <th class="text-center">INSURANCE</th>
                                                        <th class="text-center">TOTAL</th>
                                                        <th class="text-center">STATUS</th>
                                                        <th class="text-center">ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php

													if (count($items) > 0) {
														$i = 0;


														foreach ($items as $key => $value) {
															$i++;
															$series = str_pad($value->id, 5, "0", STR_PAD_LEFT);
															$action = "";
															$tr_class = "";
															$status = $value->status;

															if (intval($value->status_id) == 1) {
																$title = "{$value->deleted} = {$value->deleted_at} = {$value->deleted_reason}";
																$status = "<span title='{$title}' data-toggle='tooltip'>{$value->status}</span>";
															}

															if (intval($value->status_id) == 1) {
																//cancelled
																$tr_class = "danger";
															} else if ($transaction_status_id >= 2 && intval($value->status_id) == 2 || intval($value->status_id) == 3) {

																//pending
																$tr_class = intval($value->status_id) == 2 ? "info" : "warning";
																$action = " <span>";

																if ($role_id == 1 || $this->custom_function->module_permission("item modify", $module_permission)) {
																	$action .= "<i
																					id='{$value->id}'
																					class='btn_item_modify btn btn-xs btn-warning btn-xs fa  fa-pencil'
																					title='Edit'
																					data-toggle='tooltip'
																				></i> ";
																}

																if ($role_id == 1 || $this->custom_function->module_permission("item working", $module_permission)) {
																	$action .= "<i
																					id='{$value->id}'
																					class='btn_item_working btn btn-xs btn-info fa  fa-arrow-right'
																					title='Working'
																					data-toggle='tooltip'
																				></i> ";
																}

																if ($role_id == 1 || $this->custom_function->module_permission("item complete", $module_permission)) {
																	$action .= "<i
																					id='{$value->id}'
																					class='btn_item_completed btn btn-xs btn-success fa fa-check'
																					title='Mark as Completed'
																					data-toggle='tooltip'
																				></i> ";
																}

																if ($role_id == 1 || $this->custom_function->module_permission("item delete", $module_permission)) {
																	$action .= "<i
                                                                                    id='{$value->id}'
                                                                                    class='btn_item_cancel btn btn-xs btn-danger btn-xs fa  fa-times'
                                                                                    title='Delete'
                                                                                    data-toggle='tooltip'
                                                                                ></i> ";
																}

																// Add upload button if product allows upload AND status is PENDING or ONGOING
																if (intval($value->is_allow_upload) === 1 && (intval($value->status_id) == 2 || intval($value->status_id) == 3)) {
																	$action .= "<i
																					id='{$value->id}'
																					class='btn_item_upload btn btn-xs btn-warning fa fa-upload'
																					title='Upload Lab Results'
																					data-toggle='tooltip'
																					data-product-name='{$value->product_name}'
																					data-product-id='{$value->product_id}'
																					data-item-id='{$value->id}'
																				></i> ";
																}

																$action .= "</span>";
															} else if (intval($value->status_id) == 4) {
																//completed
																$tr_class = "success";
																$action = "";

																if ($role_id == 1 || $role_id == 2 || $this->custom_function->module_permission("item delete", $module_permission)) {
																	$action .= "<i
                                                                                    id='{$value->id}'
                                                                                    class='btn_item_cancel btn btn-xs btn-danger btn-xs fa  fa-times'
                                                                                    title='Delete'
                                                                                    data-toggle='tooltip'
                                                                                ></i> ";
																}

																// Note: Upload button not available for completed items

															}

															$subtotal_amount += $value->amount;
															$subtotal_commission += $value->commission_amount;
															$subtotal_insurance += $value->insurance_amount;
															$subtotal_total += $value->total;
															//$total_paid = 0;
															$total_amount_due = $subtotal_total - $total_paid;

															echo "<tr class='{$tr_class}'>";
															echo "  <td class='text-center'>{$i}</td>";
															echo "  <td class='text-center'>{$series}</td>";
															echo "  <td class='text-center'>{$value->product_code} - {$value->product_name}</td>";
															echo "  <td class='text-center'>{$value->category}</td>";
															echo "  <td class='text-center'>{$value->qty}</td>";
															echo "  <td class='text-center'>{$value->uom_code}</td>";
															echo "  <td class='text-right'>{$value->price}</td>";
															echo "  <td class='text-right'>{$value->amount}</td>";
															echo "  <td class='text-right'>{$value->commission_amount}</td>";
															echo "  <td class='text-right'>{$value->insurance_amount}</td>";
															echo "  <td class='text-right'>{$value->total}</td>";
															echo "  <td class='text-center'>{$status}</td>";

															if ($transaction_status_id > 1) {														echo "  <td align='center' class='text-center'>{$action}</td>";
													} else {
														echo "<td></td>";
													}

														echo "</tr>";
													}
													} else {
														echo "  <tr>
                                                                    <td colspan='13' align='center'>No Record</td>
                                                                </tr>";
													}
													?>
                                                </tbody>
                                                <tfoot>
                                                    <tr style='font-size:larger;'>
                                                        <td colspan='7' align='right'><b>Sub-Total</b></td>
                                                        <td align='right'>
                                                            <b>
                                                                <span id='lbl_total_amount'>
                                                                    <?= number_format($subtotal_amount, 2, '.', ','); ?>
                                                                </span>
                                                            </b>
                                                        </td>
                                                        <td align='right'>
                                                            <b>
                                                                <span id='lbl_total_commision'>
                                                                    <?= number_format($subtotal_commission, 2, '.', ','); ?>
                                                                </span>
                                                            </b>
                                                        </td>
                                                        <td align='right'>
                                                            <b>
                                                                <span id='lbl_total_insurance'>
                                                                    <?= number_format($subtotal_insurance, 2, '.', ','); ?>
                                                                </span>
                                                            </b>
                                                        </td>
                                                        <td align='right'>
                                                            <b>
                                                                <span id='lbl_total_subtotal'><?= number_format($subtotal_total, 2, '.', ','); ?>
                                                                </span>
                                                            </b>
                                                        </td>
                                                    </tr>
                                                    <tr style='font-size:larger;'>
                                                        <td colspan='7' align='right'><b>TOTAL PAID</b></td>
                                                        <td Colspan='4' align='right'>
                                                            <b>
                                                                <span id='lbl_total_paid'><?= number_format($total_paid, 2, '.', ','); ?></span>
                                                            </b>
                                                        </td>
                                                    </tr>
                                                    <tr style='font-size:x-large;'>
                                                        <td colspan='7' align='right'><b>TOTAL AMOUNT DUE</b></td>
                                                        <td Colspan='4' align='right'>
                                                            <b>
                                                                <span id='lbl_amount_due'><?= number_format($total_amount_due, 2, '.', ','); ?></span>
                                                            </b>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="page-header">
                                                <h1>Trail</h1>
                                            </div>
                                            <div id="div_trails">
                                                <?php
												foreach ($trails as $key => $value) {
													echo "<div>{$value->created_at} | {$value->action} | {$value->created} | {$value->remarks} </div>";
												}
												?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.row -->

                            <!-- PAGE CONTENT ENDS -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.page-content -->
            </div>
        </div><!-- /.main-content -->

        <?php
		$this->load->view("current_transaction/modal_modify");
		$this->load->view("current_transaction/modal_info");
		$this->load->view("current_transaction/modal_items");
		$this->load->view("current_transaction/modal_payment");
		$this->load->view("current_transaction/modal_send");
		$this->load->view("current_transaction/modal_prescription");
		$this->load->view("current_transaction/modal_xray");
		$this->load->view("current_transaction/modal_lab");
		$this->load->view("current_transaction/modal_history");
		$this->load->view("current_transaction/modal_delete_reason");

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
    $(document).ready(function() {
    //functions and variables
    let subtotal_amount = 0;
    let subtotal_commission = 0;
    let subtotal_insurance = 0;
    let subtotal_total = 0;
    let total_paid = Number(<?= $total_paid; ?>);
    let total_amount_due = 0;

    let new_entry_field = '';

    // Global AJAX error handler
    $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        if (jqXHR.status === 404) {
            toastr.error('Error: Requested resource not found (404)');
        } else if (jqXHR.status === 500) {
            toastr.error('Error: Internal server error (500)');
        } else if (jqXHR.status === 0) {
            toastr.error('Error: Network connection lost');
        } else {
            console.error('AJAX Error:', thrownError);
        }
    });

    // Global AJAX setup for CSRF
    $.ajaxSetup({
        beforeSend: function(xhr, settings) {
            if (settings.type === 'POST' && settings.data && typeof settings.data === 'object') {
                if (!settings.data[csrf_name]) {
                    settings.data[csrf_name] = csrf_hash;
                }
            }
        }
    });

    // Initialize chosen-select
    $('.chosen-select').chosen({
        allow_single_deselect: true,
        width: "100%"
    });

    // Initialize table fixed header
    if (typeof $.fn.fixedHeader === 'function') {
        $("#tbl_list").fixedHeader();
    } else if (typeof $.fn.tableFixedHeader === 'function') {
        $("#tbl_list").tableFixedHeader();
    }

    // Add new entry row if user has permission
    <?php if ($role_id == 1 || $this->custom_function->module_permission("item add", $module_permission)) { ?>
    if (new_entry_field && <?= $transaction_status_id; ?> >= 2) {
        $("#tbl_list tbody").append(new_entry_field);
        // Reinitialize chosen for the new select
        $("#product_id_new").chosen({
            allow_single_deselect: true,
            width: "100%"
        });
    }
    <?php } ?>

    // Field update handlers
    $(document).on('change', '.field_update', function() {
        var field_name = $(this).attr('id').replace('_update', '');
        var field_value = $(this).val();
        var transaction_id = $("#id_update").val();
        
        // Special handling for insurance selection
        if (field_name === 'insurance_id') {
            // Load insurance details when insurance is selected
            if (field_value && field_value !== '0') {
                $.post("<?= base_url($module); ?>/get_insurance_details", {
                    insurance_id: field_value,
                    [csrf_name]: csrf_hash
                }, function(response) {
                    if (response.indexOf("<!DOCTYPE html>") > -1) {
                        toastr.error("Error: Session Time-Out, You must login again to continue.");
                        location.reload(true);
                    } else if (response.indexOf("Error: ") > -1) {
                        bootbox.alert(response);
                    } else {
                        try {
                            var insurance = JSON.parse(response);
                            $("#hidden_insurance_value_type_id").val(insurance.value_type_id || 1);
                            $("#hidden_insurance_value").val(insurance.value || 0);
                            $("#hidden_insurance_commission_type_id").val(insurance.commission_type_id || 1);
                            $("#hidden_insurance_commission_value").val(insurance.commission_value || 0);
                            
                            // Recompute new entry if active
                            if ($("#product_id_new").val()) {
                                compute_entry();
                            }
                        } catch(e) {
                            console.error('Error parsing insurance details:', e);
                        }
                    }
                });
            } else {
                // Reset insurance values when no insurance selected
                $("#hidden_insurance_value_type_id").val(1);
                $("#hidden_insurance_value").val(0);
                $("#hidden_insurance_commission_type_id").val(1);
                $("#hidden_insurance_commission_value").val(0);
                
                // Recompute new entry if active
                if ($("#product_id_new").val()) {
                    compute_entry();
                }
            }
        }
        
        $.post("<?= base_url($module); ?>/update_field", {
            transaction_id: transaction_id,
            field_name: field_name,
            field_value: field_value,
            [csrf_name]: csrf_hash
        }, function(response) {
            if (response.indexOf("<!DOCTYPE html>") > -1) {
                toastr.error("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else if (response.indexOf("Error: ") > -1) {
                bootbox.alert(response);
            } else {
                try {
                    var result = JSON.parse(response);
                    if (result.success !== undefined && !result.success) {
                        toastr.error('Error: ' + (result.error || 'Failed to update field'));
                    }
                    if (result.csrf_hash) {
                        regenerate_csrf(result.csrf_hash);
                    }
                } catch(e) {
                    // Response is probably just "OK" - continue
                }
            }
        }).fail(function() {
            toastr.error('Error: Failed to communicate with server');
        });
    });

    <?php
		if ($role_id == 1 || $this->custom_function->module_permission("item add", $module_permission)) {
	?>
    new_entry_field = `   <tr>
                                    <td colspan="2">New</td>
                                    <td>
                                        <select id="product_id_new" name="product_id_new" class="field_new text-center chosen-select form-control"  >
                                            <option value="">-- SELECT --</option>
                                            <?php
											foreach ($products as $key => $value) {
												echo "<option value='{$value->id}'>{$value->code} - {$value->name}</option>";
											}
											?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="category_new" name="category_new"  class="field_new form-control text-center" disabled />
                                    </td>
                                    <td>
                                        <input type="text" id="qty_new" name="qty_new" value="1" class="field_new form-control text-center numeric" autocomplete="off" />
                                    </td>
                                    <td>
                                        <input type="text" id="uom_new" name="uom_new"   class="field_new form-control text-center" disabled />
                                    </td>
                                    <td>
                                        <input type="text" id="price_new" name="price_new" value="0.00" class="field_new form-control text-right"  />
                                    </td>
                                    <td>
                                        <input type="text" id="amount_new" name="amount_new" value="0.00" class="field_new form-control text-right" disabled />
                                    </td>
                                    <td>
                                        <input type="text" id="commission_amount_new" name="commission_amount_new" value="0.00" class="field_new numeric form-control text-right"  />
                                    </td>
                                    <td>
                                        <input type="text" id="insurance_amount_new" name="insurance_amount_new" value="0.00"  class="field_new numeric form-control text-right"  />
                                    </td>
                                    <td>
                                        <input type="text" id="total_new" name="total_new" value="0.00"  class="field_new form-control text-right" disabled />
                                    </td>
                                    <th>DRAFT</th>
                                    <td>
                                        <button id="item_save" name="item_save" class="btn btn-xs btn-primary text-center">Save</button>
                                    </td>
                                </tr>`;
    <?php
		}
	?>

    function display_total() {

        $("#lbl_total_amount").text(subtotal_amount.toFixed(2));
        $("#lbl_total_commission").text(subtotal_commission.toFixed(2));
        $("#lbl_total_insurance").text(subtotal_insurance.toFixed(2));
        $("#lbl_total_subtotal").text(subtotal_total.toFixed(2));
        $("#lbl_total_paid").text(total_paid.toFixed(2));

        total_amount_due = subtotal_total - total_paid;

        $("#lbl_amount_due").text(total_amount_due.toFixed(2));
    }

    function item_list(items) {
        //let transaction_id = $("#id_update").val();
        let transaction_status_id = <?= $transaction_status_id; ?>;
        let list = ``;

        subtotal_amount = 0;
        subtotal_commission = 0;
        subtotal_insurance = 0;
        subtotal_total = 0;
        // total_paid = 0;
        total_amount_due = 0;

        if (items.length > 0) {
            let i = 0;

            items.forEach((item) => {
                i++;

                let series = item.id.padStart(5, "0");
                let action = "";
                let tr_class = "";
                let status = item.status;

                if (parseInt(item.status_id) == 1) {
                    let title = `${item.deleted} = ${item.deleted_at} = ${item.deleted_reason}`;
                    status = `<span title='${title}' data-toggle='tooltip'>${item.status}</span>`;
                }

                if (parseInt(item.status_id) == 1) {
                    //cancelled
                    tr_class = "danger";

                } else if ((item.status_id) == 2 || parseInt(item.status_id) == 3) {
                    //pending
                    tr_class = parseInt(item.status_id) == 2 ? "info" : "warning";
                    action = `<span>`;

                    <?php
						if ($role_id == 1 || $this->custom_function->module_permission("item modify", $module_permission)) {
						?>
                    action += `<i
										id = '${item.id}'
										class = 'btn_item_modify btn btn-xs btn-warning fa fa-pencil'
										title = 'Edit'
										data-toggle = 'tooltip'
									></i> `;
                    <?php
						}

						if ($role_id == 1 || $this->custom_function->module_permission("item working", $module_permission)) {
					?>
                    action += `<i
										id='${item.id}'
										class='btn_item_working btn btn-xs btn-info fa  fa-arrow-right'
										title='Working'
										data-toggle='tooltip'
									></i> `;
                    <?php
						}

						if ($role_id == 1 || $this->custom_function->module_permission("item complete", $module_permission)) {
					?>
                    action += `<i
										id='${item.id}'
										class='btn_item_completed btn btn-xs btn-success fa fa-check'
										title='Mark as Completed'
										data-toggle='tooltip'
									></i> `;
                    <?php
						}

						if ($role_id == 1 || $this->custom_function->module_permission("item delete", $module_permission)) {
					?>                    action += `<i
                                    id = '${item.id}'
                                    class = 'btn_item_cancel btn btn-xs btn-danger fa fa-times'
                                    title = 'Delete'
                                    data-toggle = 'tooltip'
                                ></i> `;
                    <?php
                        }
                    ?>

                    // Add upload button if product allows upload AND status is PENDING or ONGOING
                    if (parseInt(item.is_allow_upload) === 1 && (parseInt(item.status_id) == 2 || parseInt(item.status_id) == 3)) {
                        action += `<i
                                    id='${item.id}'
                                    class='btn_item_upload btn btn-xs btn-warning fa fa-upload'
                                    title='Upload Lab Results'
                                    data-toggle='tooltip'
                                    data-product-name='${item.product_name}'
                                    data-product-id='${item.product_id}'
                                    data-item-id='${item.id}'
                                ></i> `;
                    }

                    action += `</span>`;

                } else if (parseInt(item.status_id) == 4) {
                    //completed
                    tr_class = "success";
                    action = "";

					<?php
						if ($role_id == 1 || $this->custom_function->module_permission("item delete", $module_permission)) {
					?>
                    action += `<i
                                    id = '${item.id}'
                                    class = 'btn_item_cancel btn btn-xs btn-danger fa fa-times'
                                    title = 'Delete'
                                    data-toggle = 'tooltip'
                                ></i> `;
                    <?php
						}
					?>

                }

                list += `<tr class='${tr_class}'>
                                <td class='text-center'>${i}</td>
                                <td class='text-center'>${series}</td>
                                <td class='text-center'>${item.product_code} - ${item.product_name}</td>
                                <td class='text-center'>${item.category}</td>
                                <td class='text-center'>${item.qty}</td>
                                <td class='text-center'>${item.uom_code}</td>
                                <td class='text-right'>${item.price}</td>
                                <td class='text-right'>${item.amount}</td>
                                <td class='text-right'>${item.commission_amount}</td>
                                <td class='text-right'>${item.insurance_amount}</td>
                                <td class='text-right'>${item.total}</td>
                                <td class='text-center'>${item.status}</td>`;

                <?php if ($transaction_status_id > 1) { ?>
                list += `<td align='center' class='text-center'>${action}</td>`;
                <?php } else { ?>
                list += `<td></td>`;
                <?php } ?>

                list += `</tr>`;

                if (parseInt(item.status_id) > 1) {
                    subtotal_amount += Number(item.amount);
                    subtotal_commission += Number(item.commission_amount);
                    subtotal_insurance += Number(item.insurance_amount);
                    subtotal_total += Number(item.total);
                    //total_amount_due = subtotal_total;
                }
            });
        }

        display_total();

        return list;
    }

    function compute_entry() {
        let transaction_id = $("#id_update").val();
        let product_id = $("#product_id_new").val();
        let qty = Number($("#qty_new").val());
        let insurance_value_type_id = Number($("#hidden_insurance_value_type_id").val());
        let insurance_value = Number($("#hidden_insurance_value").val());
        let insurance_commission_type_id = Number($("#hidden_insurance_commission_type_id").val());
        let insurance_commission_value = Number($("#hidden_insurance_commission_value").val());

        let pay_method_id = Number($("#payment_method_id_update").val());

        if (Number(product_id)) {
            $.get("<?= base_url($module); ?>/select_product/" + transaction_id + "/" + product_id,
                function(data) {
                    if (data.indexOf("<!DOCTYPE html>") > -1) {
                        toastr.error("Error: Session Time-Out, You must login again to continue.");
                        location.reload(true);
                    } else if (data.indexOf("Error: ") > -1) {
                        bootbox.alert(data);
                    } else {
                        let result = JSON.parse(data);

                        let price = 0;

                        switch (Number(pay_method_id)) {
                            case 1: //cash - clinic hours
                                price = Number(result.amount);
                                break;
                            case 2: //po- clinic hours
                                price = Number(result.amount_po);
                                break;
                            case 3: //cash - after clinic hours
                                price = Number(result.after_amount);
                                break;
                            case 4: //po - after clinic hours
                                price = Number(result.after_amount_po);
                                break;

							case 5: //cash with insurance - clinic hours
                                price = Number(result.amount_po);
                                break;
                            case 6: //cash with insurance - after clinic hours
                                price = Number(result.after_amount_po);
                                break;
                        }

                        let amount = qty * price;
                        let insurance_commission_amount = 0;
                        let insurance_amount = 0;

                        //compute commission for insurance
                        if (insurance_commission_type_id == 1) {
                            insurance_commission_amount = amount * (insurance_commission_value / 100);
                        } else {
                            insurance_commission_amount = insurance_commission_value;
                        }

                        // subtract commission from amount
                        let new_amount = amount - insurance_commission_amount;

                        //compute insurance
                        if (insurance_value_type_id == 1) {
                            insurance_amount = new_amount * (insurance_value / 100);
                        } else {
                            insurance_amount = insurance_value;
                        }

                        let total = new_amount - insurance_amount;

                        $("#category_new").val(result.category);
                        $("#uom_new").val(result.uom_code);
                        $("#price_new").val(price.toFixed(2));
                        $("#amount_new").val(amount.toFixed(2));
                        $("#commission_amount_new").val(insurance_commission_amount.toFixed(2));
                        $("#insurance_amount_new").val(insurance_amount.toFixed(2));
                        $("#total_new").val(total.toFixed(2));
                    }
                });
        }
    }

    function compute_entry_manual() {
        let qty = Number($("#qty_new").val());
        let price = Number($("#price_new").val());
        let amount = qty * price;
        let commission = Number($("#commission_amount_new").val());
        let insurance = Number($("#insurance_amount_new").val());

        let total = amount - (commission + insurance);

        $("#amount_new").val(amount.toFixed(2));
        $("#total_new").val(total.toFixed(2));
    }

    function display_logs(logs) {
        let list = "";

        if (logs.length > 0) {

            $.each(logs, (i, value) => {
                list +=
                    `<div>${value.created_at} | ${value.action} | ${value.created} | ${value.remarks} </div>`;
            });

            $("#div_trails").html(list);
        }
    }

    function xray_attachment_list(attachments) {
        let list = ``;
        let transaction_id = $("#id_update").val();
        let base_url = "<?= base_url(); ?>";

        if (attachments.length > 0) {
            list += `<ul>`;

            attachments.forEach((attachment) => {
                list += `<li id='list_${attachment}' style='padding:0.1em;'>
                            <a href='#' id='${attachment}' class='xray_attachment_remove label label-danger arrowed-in arrowed-right' >Remove</a>
                            <a href='${base_url}upload/xray/${transaction_id}/${attachment}' target='_blank'>${attachment}</a>
                         </li>`;
            });

            list += `</ul>`;
        }

        return list;
    }

    // Function to load result sets for digital entry
    function loadResultSets(product_id) {
        if (!product_id) {
            $('#lab_parameters_table tbody').html('<tr><td colspan="4" class="text-center text-muted">No result parameters defined for this service</td></tr>');
            return;
        }
        
        $.post("<?= base_url($module); ?>/lab_get_result_sets", {
            product_id: product_id
        }, function(response) {
            if (response.success && response.result_sets && response.result_sets.length > 0) {
                let tableBody = '';
                response.result_sets.forEach(function(resultSet) {
                    tableBody += `
                        <tr>
                            <td>
                                <input type="hidden" name="result_set_id[]" value="${resultSet.id}">
                                <strong>${resultSet.result_label}</strong>
                                ${resultSet.description ? '<br><small class="text-muted">' + resultSet.description + '</small>' : ''}
                            </td>
                            <td>
                                <input type="text" name="result_value[]" class="form-control" placeholder="Enter result value">
                            </td>
                            <td>
                                <span class="text-muted">${resultSet.unit || ''}</span>
                                ${resultSet.reference ? '<br><small>Ref: ' + resultSet.reference + '</small>' : '<br><small>Ref: N/A</small>'}
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-xs btn-danger delete-result-set" data-result-set-id="${resultSet.id}" title="Delete Parameter">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                $('#lab_parameters_table tbody').html(tableBody);
            } else {
                $('#lab_parameters_table tbody').html(`
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No result parameters defined for this service
                            <br><small>Click "Add Result Set" to add parameters</small>
                        </td>
                    </tr>
                `);
            }
        }, 'json').fail(function() {
            $('#lab_parameters_table tbody').html('<tr><td colspan="4" class="text-center text-danger">Error loading result parameters</td></tr>');
        });
    }

    $(document).on("click", "#btn_add_patient", function() {
        bootbox.alert("Please goto DATA -> PATIENT, to add new patient name.")
    });

    $(document).on("click", "#btn_add_client", function() {
        bootbox.alert("Please goto DATA -> CLIENT, to add new client name.")
    });

    // Main action button handlers
    $(document).on("click", "#btn_history", function() {
        var transaction_id = $("#id_update").val();
        if (transaction_id) {
            $("#modal_history").modal('show');
            // Load patient history data here
        }
    });

    $(document).on("click", "#btn_send", function() {
        var transaction_id = $("#id_update").val();
        if (transaction_id) {
            $("#modal_send").modal('show');
        }
    });

    $(document).on("click", "#btn_payment", function() {
        var transaction_id = $("#id_update").val();
        if (transaction_id) {
            $("#modal_payment").modal('show');
        }
    });

    $(document).on("click", "#btn_xray", function() {
        var transaction_id = $("#id_update").val();
        if (transaction_id) {
            $("#modal_xray").modal('show');
        }
    });

    $(document).on("click", "#btn_prescription", function() {
        var transaction_id = $("#id_update").val();
        if (transaction_id) {
            $("#modal_prescription").modal('show');
        }
    });

    $(document).on("click", "#btn_completed", function() {
        var transaction_id = $("#id_update").val();
        if (transaction_id && confirm('Mark this transaction as completed?')) {
            $.post("<?= base_url($module); ?>/mark_completed", {
                transaction_id: transaction_id,
                [csrf_name]: csrf_hash
            }, function(response) {
                if (response.indexOf("Error: ") > -1) {
                    bootbox.alert(response);
                } else {
                    toastr.success('Transaction marked as completed');
                    location.reload();
                }
            });
        }
    });

    $(document).on("click", "#btn_transaction_cancel", function() {
        var transaction_id = $("#id_update").val();
        if (transaction_id) {
            bootbox.confirm({
                title: "Cancel Transaction",
                message: "Are you sure you want to cancel this transaction?<br><br>" +
                        "<span class='text-warning'><i class='fa fa-warning'></i> This action cannot be undone.</span>",
                buttons: {
                    confirm: {
                        label: '<i class="fa fa-times"></i> Cancel Transaction',
                        className: 'btn-danger'
                    },
                    cancel: {
                        label: '<i class="fa fa-close"></i> Keep Transaction',
                        className: 'btn-default'
                    }
                },
                callback: function (result) {
                    if (result) {
                        $.post("<?= base_url($module); ?>/cancel_transaction", {
                            transaction_id: transaction_id,
                            [csrf_name]: csrf_hash
                        }, function(response) {
                            if (response.indexOf("Error: ") > -1) {
                                bootbox.alert(response);
                            } else {
                                toastr.success('Transaction cancelled successfully');
                                window.location.href = "<?= base_url($module); ?>";
                            }
                        });
                    }
                }
            });
        }
    });

    // Handle lab button click
    $(document).on("click", "#btn_lab", function() {
        bootbox.alert("Laboratory function is managed per item. Please use the upload button next to each item that supports laboratory results.");
    });

    // Function to update the results table display for both types
    function updateLabResultsTable(results) {
        console.log('Updating lab results table with:', results); // Debug log
        
        var tbody = $('#tbl_lab_results tbody');
        tbody.empty();
        
        if (!results || results.length === 0) {
            tbody.append('<tr><td colspan="7" class="text-center text-muted">No laboratory results found</td></tr>');
            return;
        }
        
        $.each(results, function(index, lab) {
            var testDate = lab.test_date ? new Date(lab.test_date).toLocaleDateString() : '';
            var entryDate = lab.created_at ? new Date(lab.created_at).toLocaleDateString() : '';
            
            var filesOrResults = '';
            var actions = '';
            
            if (lab.entry_type === 'digital') {
                // Handle digital entry
                var parameters = [];
                try {
                    parameters = lab.lab_parameters ? JSON.parse(lab.lab_parameters) : [];
                } catch (e) {
                    console.warn('Error parsing lab_parameters:', e);
                    parameters = [];
                }
                var paramCount = parameters.length;
                filesOrResults = '<span class="label label-info">' + paramCount + ' parameters</span>';
                
                actions += '<button class="btn btn-sm btn-info print-digital" data-lab-id="' + lab.id + '" title="Print Results">';
                actions += '<i class="fa fa-print"></i></button> ';
            } else {
                // Handle file upload entry
                var files = [];
                try {
                    files = lab.files ? JSON.parse(lab.files) : [];
                } catch (e) {
                    console.warn('Error parsing files JSON:', e);
                    files = [];
                }
                var fileCount = files.length;
                filesOrResults = '<span class="label label-success">' + fileCount + ' files</span>';
                
                // Add download/view actions for uploaded files
                if (files.length > 0) {
                    $.each(files, function(i, file) {
                        actions += '<button class="btn btn-sm btn-success download-file" data-file="' + file.file_name + '" data-item-id="' + lab.item_id + '" data-transaction-id="' + lab.transaction_id + '" title="Download ' + file.original_name + '">';
                        actions += '<i class="fa fa-download"></i></button> ';
                    });
                }
            }
            
            actions += '<button class="btn btn-sm btn-danger delete-lab" data-lab-id="' + lab.id + '" title="Delete">';
            actions += '<i class="fa fa-trash"></i></button>';
            
            var row = '<tr>' +
                '<td class="text-center">' + testDate + '</td>' +
                '<td>' + (lab.test_name || '') + '</td>' +
                '<td>' + (lab.lab_provider || '') + '</td>' +
                '<td class="text-center">' + (lab.display_type || lab.entry_type || 'Upload') + '</td>' +
                '<td class="text-center">' + filesOrResults + '</td>' +
                '<td class="text-center">' + entryDate + '</td>' +
                '<td class="text-center">' + actions + '</td>' +
                '</tr>';
            
            tbody.append(row);
        });
        
        console.log('Lab results table updated successfully'); // Debug log
    }

    // Function to load lab results by item
    function load_lab_results_by_item(item_id) {
        console.log('Loading lab results for item_id:', item_id); // Debug log
        
        if (!item_id) {
            $('#tbl_lab_results tbody').html('<tr><td colspan="7" class="text-center text-muted">No item selected</td></tr>');
            return;
        }
        
        var transaction_id = $("#id_update").val();
        console.log('Transaction ID:', transaction_id); // Debug log
        
        // Send both item_id and transaction_id to backend for flexibility
        $.post("<?= base_url($module); ?>/lab_list", {
            item_id: item_id,
            transaction_id: transaction_id
        }, function(response) {
            console.log('Lab results response:', response); // Debug log
            
            if (response.success && response.data && response.data.length > 0) {
                updateLabResultsTable(response.data);
            } else {
                console.log('No results found'); // Debug log
                $('#tbl_lab_results tbody').html('<tr><td colspan="7" class="text-center text-muted">No laboratory results found</td></tr>');
            }
        }, 'json').fail(function(xhr, status, error) {
            console.error('AJAX error loading lab results:', xhr.responseText); // Debug log
            $('#tbl_lab_results tbody').html('<tr><td colspan="7" class="text-center text-danger">Error loading laboratory results</td></tr>');
        });
    }

    // Lab Result Functions - Per Item Upload
    $(document).on("click", ".btn_item_upload", function() {
        let item_id = $(this).attr("id");
        let product_name = $(this).data("product-name");
        let product_id = $(this).data("product-id");
        let transaction_id = $("#id_update").val();
        let patient_name = $("#patient_id_update option:selected").text();
        
        // Set modal information
        $("#lab_patient_name").text(patient_name);
        $("#lab_product_info").text("Service: " + product_name);
        $("#lab_item_id").val(item_id);
        $("#lab_transaction_id").val(transaction_id);
        
        // Set digital form values
        $("#digital_item_id").val(item_id);
        $("#digital_transaction_id").val(transaction_id);
        $("#digital_test_name").val(product_name); // Set product name as test name
        $("#digital_performed_by").val("<?php echo $ufname; ?>"); // Set current user as performed by
        
        // Store product_id for loading result sets
        $("#modal_lab").data("product-id", product_id);
        $("#modal_lab").data("current-product-name", product_name);
        
        // Load result sets for digital entry
        loadResultSets(product_id);
        
        // Clear form
        $("#lab_upload_form")[0].reset();
        $("#lab_item_id").val(item_id);
        $("#lab_transaction_id").val(transaction_id);
        
        // Set test name for file upload tab (after form reset)
        $("#lab_test_name").val(product_name);
        
        // Load lab results for this item
        load_lab_results_by_item(item_id);
        
        // Show modal
        $("#modal_lab").modal();
    });

    // Handle file upload form submission
    $(document).on('submit', '#lab_upload_form', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: "<?= base_url($module); ?>/lab_upload",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#btn_upload_lab').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Uploading...');
            },
            success: function(response) {
                console.log('Upload response:', response); // Debug log
                
                if (response.success) {
                    toastr.success('Laboratory results uploaded successfully!');
                    
                    // Store item_id before resetting form
                    var current_item_id = $('#lab_item_id').val();
                    console.log('Current item_id before reset:', current_item_id); // Debug log
                    
                    $('#lab_upload_form')[0].reset();
                    
                    // Restore the item_id and transaction_id after reset
                    $('#lab_item_id').val(current_item_id);
                    $('#lab_transaction_id').val($("#id_update").val());
                    
                    // Set test name again after reset
                    var product_name = $("#modal_lab").data("current-product-name");
                    if (product_name) {
                        $("#lab_test_name").val(product_name);
                    }
                    
                    console.log('About to refresh lab results for item:', current_item_id); // Debug log
                    
                    // Refresh the results list with a small delay to ensure backend has completed
                    setTimeout(function() {
                        load_lab_results_by_item(current_item_id);
                    }, 500);
                } else {
                    toastr.error('Error: ' + (response.error || 'Failed to upload laboratory results'));
                }
            },
            error: function() {
                toastr.error('Error: Failed to communicate with server');
            },
            complete: function() {
                $('#btn_upload_lab').prop('disabled', false).html('<i class="fa fa-upload"></i> Upload Results');
            }
        });
    });

    // New item save handler
    $(document).on('click', '#item_save', function() {
        var transaction_id = $("#id_update").val();
        var product_id = $("#product_id_new").val();
        var qty = $("#qty_new").val();
        var price = $("#price_new").val();
        var commission_amount = $("#commission_amount_new").val();
        var insurance_amount = $("#insurance_amount_new").val();
        
        if (!product_id) {
            toastr.error("Please select a product/service");
            return;
        }
        
        if (!qty || qty <= 0) {
            toastr.error("Please enter a valid quantity");
            return;
        }
        
        $.post("<?= base_url($module); ?>/add_item", {
            transaction_id: transaction_id,
            product_id: product_id,
            qty: qty,
            price: price,
            commission_amount: commission_amount,
            insurance_amount: insurance_amount,
            [csrf_name]: csrf_hash
        }, function(response) {
            if (response.indexOf("<!DOCTYPE html>") > -1) {
                toastr.error("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else if (response.indexOf("Error: ") > -1) {
                bootbox.alert(response);
            } else {
                try {
                    var result = JSON.parse(response);
                    if (result.success) {
                        // Clear form
                        $("#product_id_new").val("").trigger("chosen:updated");
                        $("#category_new, #uom_new").val("");
                        $("#qty_new").val("1");
                        $("#price_new, #amount_new, #commission_amount_new, #insurance_amount_new, #total_new").val("0.00");
                        
                        // Reload page to show updated items
                        location.reload();
                    } else {
                        toastr.error("Error: " + (result.error || "Failed to add item"));
                    }
                    
                    if (result.csrf_hash) {
                        regenerate_csrf(result.csrf_hash);
                    }
                } catch(e) {
                    toastr.error("Error: Invalid response from server");
                }
            }
        });
    });

    // Product selection change handler for new entry
    $(document).on('change', '#product_id_new', function() {
        compute_entry();
    });

    // Quantity and price change handlers for new entry
    $(document).on('change', '#qty_new, #price_new, #commission_amount_new, #insurance_amount_new', function() {
        compute_entry_manual();
    });

    // Numeric input validation
    $(document).on('input', '.numeric', function() {
        var value = $(this).val();
        // Allow only numbers and decimal points
        var numericValue = value.replace(/[^0-9.]/g, '');
        
        // Ensure only one decimal point
        var parts = numericValue.split('.');
        if (parts.length > 2) {
            numericValue = parts[0] + '.' + parts.slice(1).join('');
        }
        
        $(this).val(numericValue);
    });

    // Prevent non-numeric characters in numeric fields
    $(document).on('keypress', '.numeric', function(e) {
        var charCode = (e.which) ? e.which : e.keyCode;
        // Allow: backspace, delete, tab, escape, enter and decimal point
        if (charCode == 46 || charCode == 8 || charCode == 9 || charCode == 27 || charCode == 13 ||
            // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (charCode == 65 && e.ctrlKey === true) ||
            (charCode == 67 && e.ctrlKey === true) ||
            (charCode == 86 && e.ctrlKey === true) ||
            (charCode == 88 && e.ctrlKey === true)) {
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((charCode < 48 || charCode > 57)) {
            e.preventDefault();
        }
    });

    // Item action handlers
    $(document).on('click', '.btn_item_modify', function() {
        var item_id = $(this).attr('id');
        bootbox.alert("Item modification functionality not yet implemented. Item ID: " + item_id);
    });

    $(document).on('click', '.btn_item_working', function() {
        var item_id = $(this).attr('id');
        
        bootbox.confirm({
            title: "Update Item Status",
            message: "Mark this item as 'Working'?",
            buttons: {
                confirm: {
                    label: '<i class="fa fa-arrow-right"></i> Mark as Working',
                    className: 'btn-info'
                },
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel',
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if (result) {
                    $.post("<?= base_url($module); ?>/update_item_status", {
                        item_id: item_id,
                        status: 'working',
                        [csrf_name]: csrf_hash
                    }, function(response) {
                        if (response.indexOf("<!DOCTYPE html>") > -1) {
                            toastr.error("Error: Session Time-Out, You must login again to continue.");
                            location.reload(true);
                        } else if (response.indexOf("Error: ") > -1) {
                            bootbox.alert(response);
                        } else {
                            try {
                                var result = JSON.parse(response);
                                if (result.success) {
                                    toastr.success('Item status updated successfully');
                                    location.reload();
                                } else {
                                    toastr.error('Error: ' + (result.error || 'Failed to update status'));
                                }
                                if (result.csrf_hash) {
                                    regenerate_csrf(result.csrf_hash);
                                }
                            } catch(e) {
                                // If response is just "OK" or simple text, treat as success
                                toastr.success('Item status updated successfully');
                                location.reload();
                            }
                        }
                    });
                }
            }
        });
    });

    $(document).on('click', '.btn_item_completed', function() {
        var item_id = $(this).attr('id');
        
        bootbox.confirm({
            title: "Complete Item",
            message: "Mark this item as 'Completed'?",
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i> Mark as Completed',
                    className: 'btn-success'
                },
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel',
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if (result) {
                    $.post("<?= base_url($module); ?>/update_item_status", {
                        item_id: item_id,
                        status: 'completed',
                        [csrf_name]: csrf_hash
                    }, function(response) {
                        if (response.indexOf("<!DOCTYPE html>") > -1) {
                            toastr.error("Error: Session Time-Out, You must login again to continue.");
                            location.reload(true);
                        } else if (response.indexOf("Error: ") > -1) {
                            bootbox.alert(response);
                        } else {
                            try {
                                var result = JSON.parse(response);
                                if (result.success) {
                                    toastr.success('Item marked as completed successfully');
                                    location.reload();
                                } else {
                                    toastr.error('Error: ' + (result.error || 'Failed to complete item'));
                                }
                                if (result.csrf_hash) {
                                    regenerate_csrf(result.csrf_hash);
                                }
                            } catch(e) {
                                // If response is just "OK" or simple text, treat as success
                                toastr.success('Item marked as completed successfully');
                                location.reload();
                            }
                        }
                    });
                }
            }
        });
    });

    $(document).on('click', '.btn_item_cancel', function() {
        var item_id = $(this).attr('id');
        var $row = $(this).closest('tr');
        
        // Get item information from the table row
        var itemInfo = {
            series: $row.find('td:eq(1)').text().trim(),
            product: $row.find('td:eq(2)').text().trim(),
            category: $row.find('td:eq(3)').text().trim(),
            qty: $row.find('td:eq(4)').text().trim(),
            price: $row.find('td:eq(6)').text().trim(),
            total: $row.find('td:eq(10)').text().trim()
        };
        
        // Populate the delete modal with item information
        var itemInfoHtml = `
            <h5><strong>Item to be deleted:</strong></h5>
            <table class="table table-condensed">
                <tr><td><strong>Series:</strong></td><td>${itemInfo.series}</td></tr>
                <tr><td><strong>Product/Service:</strong></td><td>${itemInfo.product}</td></tr>
                <tr><td><strong>Category:</strong></td><td>${itemInfo.category}</td></tr>
                <tr><td><strong>Quantity:</strong></td><td>${itemInfo.qty}</td></tr>
                <tr><td><strong>Unit Price:</strong></td><td>${itemInfo.price}</td></tr>
                <tr><td><strong>Total:</strong></td><td>${itemInfo.total}</td></tr>
            </table>
        `;
        
        $('#delete_item_info').html(itemInfoHtml);
        $('#delete_reason').val('');
        
        // Store the item_id for later use
        $('#modal_delete_reason').data('item-id', item_id);
        
        // Show the modal
        $('#modal_delete_reason').modal('show');
    });

    // Handle the confirm delete button in the modal
    $(document).on('click', '#btn_confirm_delete', function() {
        var item_id = $('#modal_delete_reason').data('item-id');
        var delete_reason = $('#delete_reason').val().trim();
        
        // Validate that reason is provided
        if (!delete_reason) {
            toastr.error('Please provide a reason for deleting this item.');
            $('#delete_reason').focus();
            return;
        }
        
        if (delete_reason.length < 10) {
            toastr.error('Please provide a more detailed reason (at least 10 characters).');
            $('#delete_reason').focus();
            return;
        }
        
        // Disable the button to prevent double-clicking
        $('#btn_confirm_delete').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
        
        $.post("<?= base_url($module); ?>/cancel_item", {
            item_id: item_id,
            delete_reason: delete_reason,
            [csrf_name]: csrf_hash
        }, function(response) {
            if (response.indexOf("<!DOCTYPE html>") > -1) {
                toastr.error("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else if (response.indexOf("Error: ") > -1) {
                $('#btn_confirm_delete').prop('disabled', false).html('<i class="fa fa-trash"></i> Delete Item');
                bootbox.alert(response);
            } else {
                try {
                    var result = JSON.parse(response);
                    if (result.success) {
                        $('#modal_delete_reason').modal('hide');
                        toastr.success('Item deleted successfully');
                        location.reload();
                    } else {
                        $('#btn_confirm_delete').prop('disabled', false).html('<i class="fa fa-trash"></i> Delete Item');
                        toastr.error('Error: ' + (result.error || 'Failed to delete item'));
                    }
                    if (result.csrf_hash) {
                        regenerate_csrf(result.csrf_hash);
                    }
                } catch(e) {
                    // If response is just "OK" or simple text, treat as success
                    $('#modal_delete_reason').modal('hide');
                    toastr.success('Item deleted successfully');
                    location.reload();
                }
            }
        }).fail(function() {
            $('#btn_confirm_delete').prop('disabled', false).html('<i class="fa fa-trash"></i> Delete Item');
            toastr.error('Network error occurred. Please try again.');
        });
    });

    // Reset modal when hidden
    $('#modal_delete_reason').on('hidden.bs.modal', function() {
        $('#delete_reason').val('');
        $('#delete_item_info').html('');
        $('#btn_confirm_delete').prop('disabled', false).html('<i class="fa fa-trash"></i> Delete Item');
        $(this).removeData('item-id');
    });

    // Event handlers for lab result actions
    $(document).on('click', '.download-file', function() {
        var fileName = $(this).data('file');
        var itemId = $(this).data('item-id');
        var transactionId = $(this).data('transaction-id');
        
        if (fileName && itemId && transactionId) {
            // Create download URL
            var downloadUrl = "<?= base_url('upload/lab_results/'); ?>" + transactionId + "/" + itemId + "/" + fileName;
            
            // Open download in new tab/window
            window.open(downloadUrl, '_blank');
        } else {
            toastr.error("Unable to download file - missing file information");
        }
    });
    
    $(document).on('click', '.delete-lab', function() {
        var labId = $(this).data('lab-id');
        var row = $(this).closest('tr');
        
        bootbox.confirm("Are you sure you want to delete this laboratory result?", function(confirmed) {
            if (confirmed) {
                $.post("<?= base_url($module); ?>/lab_delete", {
                    lab_id: labId
                }, function(response) {
                    if (response.success) {
                        toastr.success(response.message || "Laboratory result deleted successfully");
                        row.fadeOut(function() {
                            $(this).remove();
                        });
                    } else {
                        toastr.error(response.error || "Failed to delete laboratory result");
                    }
                }, 'json').fail(function() {
                    toastr.error("Error occurred while deleting laboratory result");
                });
            }
        });
    });
    
    $(document).on('click', '.print-digital', function() {
        var labId = $(this).data('lab-id');
        // Open print preview for digital lab results
        window.open("<?= base_url($module); ?>/lab_print/" + labId, '_blank', 'width=800,height=600');
    });

    }); // End of $(document).ready()

</script>

</body>

</html>
