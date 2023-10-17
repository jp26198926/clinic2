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
                                        <span id="lbl_transaction_no"
                                            class="badge badge-warning"><?= $transaction_no; ?></span>
                                        <input type="hidden" id="id_update" name="id_update"
                                            value="<?= $transaction_id; ?>" />
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
                                            <input type="text" id="date_update" name="date_update"
                                                value="<?= $record->date; ?>" class="form-control field_update"
                                                autocomplete="off" readonly <?= $disabled_field; ?> />
                                        </div>

                                        <div class="col-md-1 text-right">Status</div>
                                        <div class="col-md-2">
                                            <input type="text" id="status_update" name="status_update"
                                                class="form-control field_update" value="<?= $record->status; ?>"
                                                readonly disabled />
                                        </div>

                                        <div class="col-md-2 text-right">Trans Type <i class="text-danger">*</i></div>
                                        <div class="col-md-3">
                                            <select id="trans_type_id_update" name="trans_type_id_update"
                                                class="chosen-select form-control field_update" <?= $disabled_field; ?>>
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
                                                <select id="patient_id_update" name="patient_id_update"
                                                    class="chosen-select form-control field_update"
                                                    <?= $disabled_field; ?>>
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
                                                    <button id="btn_add_patient" class="btn btn-sm btn-default"
                                                        type="button" <?= $disabled_field; ?>>
                                                        Add New
                                                    </button>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-2 text-right">Pay Method <i class="text-danger">*</i></div>
                                        <div class="col-md-3">
                                            <select id="payment_method_id_update" name="payment_method_id_update"
                                                class="chosen-select form-control field_update" <?= $disabled_field; ?>>
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
                                                <select id="client_id_update" name="client_id_update"
                                                    class="chosen-select form-control field_update"
                                                    <?= $disabled_field; ?>>
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
                                                    <button id="btn_add_client" class="btn btn-sm btn-default"
                                                        type="button" <?= $disabled_field; ?>>
                                                        Add New
                                                    </button>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-2 text-right">Charge To <i class="text-danger">*</i></div>
                                        <div class="col-md-3">
                                            <select id="charging_type_id_update" name="charging_type_id_update"
                                                class="chosen-select form-control field_update" <?= $disabled_field; ?>>
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
                                            <select id="insurance_id_update" name="insurance_id_update"
                                                class="chosen-select form-control field_update" <?= $disabled_field; ?>>
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
                                            <input type="text" id="po_no_update" name="po_no_update"
                                                class="form-control field_update" value="<?= $record->po_no; ?>"
                                                <?= $disabled_field; ?> />
                                        </div>
                                    </div>

                                    <div class="row" style="margin-bottom:1em;">
                                        <div class="col-md-2 text-right">Location</div>
                                        <div class="col-md-2">
                                            <select id="location_id_update" name="location_id_update"
                                                class="chosen-select form-control field_update" disabled >
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
                                            <select id="queue_id_update" name="queue_id_update"
                                                class="chosen-select form-control field_update" disabled>
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
                                            <select id="doctor_id_update" name="doctor_id_update"
                                                class="chosen-select form-control field_update" <?= $disabled_field2; ?>>
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
                                            <textarea
												id="remarks_update"
												name="remarks_update"
                                                class="form-control field_update"
                                                <?= $disabled_field2; ?>
											><?= $record->remarks; ?></textarea>
                                        </div>

										<div class="col-md-2 text-right">Diagnosis</div>
                                        <div class="col-md-3">
											<textarea
												id="diagnosis_update"
												name="diagnosis_update"
                                                class="form-control field_update"
                                                <?= $disabled_field2; ?>

											><?= $record->diagnosis; ?></textarea>
                                        </div>
                                    </div>

                                    <!-- BUTTONS -->
                                    <div class="row" style="margin-bottom:1em;">
                                        <div class="col-md-12 text-center">
                                            <?php
                                            echo "<a href='" . base_url() . "current_transaction' class='btn btn-default'>
                                                    <i class='fa fa-arrow-left fa-fw'></i>
                                                    Back
                                                  </a> ";

                                            if ($transaction_status_id >= 2) { //draft
                                                //echo "<button id='btn_package' class='btn btn-info'>Add Package</button> ";

                                                //if (count($items) > 0) {
                                                if ($transaction_status_id == 2) { //draft
                                                    echo "<a id='btn_confirm' class='btn btn-primary'>
                                                            <i class='fa fa-check fa-fw'></i>
                                                            Confirm
                                                        </a> ";
                                                }

												echo "<a
														id='btn_modify'
                                                        href='" . base_url() . "current_transaction/modify/{$transaction_id}' class='btn btn-warning'
                                                    	>
                                                            <i class='fa fa-pencil fa-fw'></i>
                                                            Modify
                                                        </a> ";

                                                //export to pdf shown
                                                echo "<a
                                                            id='btn_print'
                                                            href='" . base_url() . "current_transaction/print_charges/{$transaction_id}' class='btn btn-warning'
                                                            target='_blank'
                                                          >
                                                            <i class='fa fa-print fa-fw'></i>
                                                            Print
                                                        </a> ";
                                                // }

                                                echo "<button id='btn_send' class='btn btn-info'>
                                                        <i class='fa fa-arrow-right fa-fw'></i>
                                                        Send To
                                                      </button> ";

												echo "<button id='btn_payment' class='btn btn-info'>
														<i class='fa fa-credit-card fa-fw'></i>
														Payment
													 </button> ";

												echo "<button id='btn_lab' class='btn btn-info'>
														<i class='fa fa-flask fa-fw'></i>
														Lab Result
													 </button> ";

												echo "<button id='btn_prescription' class='btn btn-info'>
														<i class='fa fa-glass fa-fw'></i>
														Prescription
													  </button> ";

												echo "<button id='btn_completed' class='btn btn-info'>
														<i class='fa fa-check fa-fw'></i>
														Completed
													  </button> ";

                                                echo "<button id='btn_transaction_cancel' class='btn btn-danger'>
                                                        <i class='fa fa-times fa-fw'></i>
                                                        Cancel
                                                     </button> ";
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <!-- ITEMS -->
                                    <div class="row" style="margin-top:1em;">
                                        <div class="col-md-12">

                                            <table id="tbl_list"
                                                class="table table-bordered table-stripe table-hover table-fixed-header"
                                                style="font-size:90%">
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
                                                            } else if (intval($value->status_id) == 2) {
                                                                //pending
                                                                $tr_class = "info";
                                                                $action = " <span>
                                                                                <button
                                                                                    id='{$value->id}'
                                                                                    class='btn_item_cancel btn btn-danger btn-xs fa fa-times'
                                                                                    title='Delete'
                                                                                    data-toogle='tooltip'
                                                                                ></button>
                                                                            </span>";
                                                            } else if (intval($value->status_id) == 3 || intval($value->status_id) == 4) {
                                                                //draft or Sendback
                                                                $tr_class = "";
                                                                $action = " <i id='{$value->id}' class='btn_item_modify fa fa-pencil text-primary' title='Edit' data-toggle='tooltip'></i>
                                                                                    <i id='{$value->id}' class='btn_item_info fa fa-times text-danger' title='Delete' data-toggle='tooltip'></i>";
                                                            } else if (intval($value->status_id) == 7) {
                                                                //completed
                                                                $tr_class = "success";
                                                                $action = "";
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
                                                            echo "  <td class='text-center'>{$action}</td>";
                                                            echo "<tr>";
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
                                                                <span id='lbl_total_insurance'>
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
                                                                <span
                                                                    id='lbl_total_subtotal'><?= number_format($subtotal_total, 2, '.', ','); ?></span>
                                                            </b>
                                                        </td>
                                                    </tr>
													<tr style='font-size:larger;'>
                                                        <td colspan='7' align='right'><b>TOTAL PAID</b></td>
                                                        <td Colspan='4' align='right'>
                                                            <b>
                                                                <span
                                                                    id='lbl_total_paid'><?= number_format($total_paid, 2, '.', ','); ?></span>
                                                            </b>
                                                        </td>
                                                    </tr>
                                                    <tr style='font-size:x-large;'>
                                                        <td colspan='7' align='right'><b>TOTAL AMOUNT DUE</b></td>
                                                        <td Colspan='4' align='right'>
                                                            <b>
                                                                <span
                                                                    id='lbl_amount_due'><?= number_format($total_amount_due, 2, '.', ','); ?></span>
                                                            </b>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                        </div>
                                    </div>



                                    <!-- <div class="row">
                                        <div class="col-md-12">
                                            <div class="page-header">
                                                <h1>Trail</h1>
                                            </div>
                                            <?php
                                            foreach ($trails as $key => $value) {
                                                echo "<div>{$value->created_at} | {$value->action} | {$value->created} | {$value->remarks} </div>";
                                            }
                                            ?>
                                        </div>
                                    </div> -->
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
    //functions and variables
    let subtotal_amount = 0;
    let subtotal_commission = 0;
    let subtotal_insurance = 0;
    let subtotal_total = 0;
	let total_paid = Number(<?= $total_paid; ?>);
    let total_amount_due = 0;

    const new_entry_field = `   <tr>
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
                                </tr>
                                `;

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

                } else if (parseInt(item.status_id) == 2) {
                    //pending
                    tr_class = "info";
                    action = `<span>
                                <button
                                    id = '${item.id}'
                                    class = 'btn_item_cancel btn btn-danger btn-xs fa fa-times'
                                    title = 'Delete'
                                    data-toogle = 'tooltip'
                                ></button>
                              </span>`;

                } else if (parseInt(item.status_id) == 3 || parseInt(item.status_id) == 4) {
                    //draft or Sendback
                    tr_class = "";
                    action = `<i
                                id='${item.id}'
                                class='btn_item_modify fa fa-pencil text-primary'
                                title='Edit'
                                data-toggle='tooltip'
                            ></i>
                            <i
                                id = '${item.id}'
                                class = 'btn_item_info fa fa-times text-danger'
                                title = 'Delete'
                                data-toggle = 'tooltip'
                            ></i>`;

                } else if (parseInt(item.status_id) == 7) {
                    //completed
                    tr_class = "success";
                    action = "";
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
                                <td class='text-center'>${item.status}</td>
                                <td class='text-center'>${action}</td>
                            <tr>`;

				subtotal_amount += Number(item.amount);
                subtotal_commission += Number(item.commission_amount);
                subtotal_insurance += Number(item.insurance_amount);
                subtotal_total += Number(item.total);
                total_amount_due = subtotal_total;

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
            $.get("<?= base_url(); ?>current_transaction/select_product/" + transaction_id + "/" + product_id,
                function(data) {
                    if (data.indexOf("<!DOCTYPE html>") > -1) {
                        alert("Error: Session Time-Out, You must login again to continue.");
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

    function compute_entry_manual(){
        let qty = Number($("#qty_new").val());
        let price = Number($("#price_new").val());
        let amount = qty * price;
        let commission = Number($("#commission_amount_new").val());
        let insurance = Number($("#insurance_amount_new").val());

        let total = amount - (commission + insurance);

        $("#amount_new").val(amount.toFixed(2));
        $("#total_new").val(total.toFixed(2));
    }

    $(document).on("keyup", "#qty_new, #price_new, #commission_amount_new, #insurance_amount_new", function(e){
        compute_entry_manual();
    });


    $(document).ready(function() {
        $("#id_update").val("<?= $transaction_id; ?>");
        $("#lbl_transaction_no").text("<?= $transaction_no; ?>");
        $("#insurance_id_update").trigger("change");

        <?php if ($transaction_status_id == 2 || $transaction_status_id == 3 ) { ?>
        $("#tbl_list tbody").append(new_entry_field);
        <?php } ?>

        $("#date_update").datepicker({
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

    //AUTO-SAVE
    $(document).on("blur, change", ".field_update", function(e) {
        e.preventDefault();

        let id = $("#id_update").val();
        let fieldname = $(this).attr("id");
        let fieldvalue = $(this).val();
        let fieldtext = $(this).val();

        let tag = $(this).prop('tagName');
        if (tag.toLowerCase() === "select") {
            fieldtext = $('option:selected', this).text()
        }



        if (id && fieldvalue) {
            $.post("<?= base_url(); ?>current_transaction/auto_save", {
                id: id,
                fieldname: fieldname,
                fieldvalue: fieldvalue,
                tag: tag,
                fieldtext: fieldtext
            }, function(data) {

                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    //bootbox.alert(data);
                } else {

                }
            });
        }
    });

    //INSURANCE UPDATE
    $(document).on("change", "#insurance_id_update", function() {
        let id = $(this).val();

        if (Number(id) > 0) {
            $.post("<?= base_url(); ?>data_insurance/search_info_row", {
                id: id
            }, function(data) {
                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    bootbox.alert(data);
                } else {
                    if (data) {
                        let result = JSON.parse(data);

                        $("#hidden_insurance_value_type_id").val(result.value_type_id);
                        $("#hidden_insurance_value").val(result.value);
                        $("#hidden_insurance_commission_type_id").val(result.commission_type_id);
                        $("#hidden_insurance_commission_value").val(result.commission_value);

                        compute_entry();

                    }
                }
            });
        } else {
            $("#hidden_insurance_value_type_id").val(0);
            $("#hidden_insurance_value").val(0);
            $("#hidden_insurance_commission_type_id").val(0);
            $("#hidden_insurance_commission_value").val(0);

            compute_entry();
        }
    });

    //region transaction buttons
	$(document).on("click", "#btn_modify", function(){
		$("#loading").modal();
	});

    $(document).on("click", "#btn_confirm", function() {
        let transaction_id = $("#id_update").val();

        if (transaction_id) {
            bootbox.confirm("Are you sure you want to confirm this transaction?", function(result) {
                if (result) {
                    $.post("<?= base_url(); ?>current_transaction/confirm", {
                        transaction_id: transaction_id
                    }, function(data) {
						location.reload(true);
                    });
                }
            });

        } else {
            bootbox.alert("Error: Critical Error Encountered!");
        }

    });

	$(document).on("click", "#btn_payment", function(){
		let transaction_id = $("#id_update").val();

		//show loading screen
		$("#loading").modal("show");

		//get total amount due
		//get list of payment history entries
		$.post("<?= base_url(); ?>current_transaction/check_payment", {
            transaction_id: transaction_id
        }, function(data) {
			$("#loading").modal("hide");

            if (data.indexOf("<!DOCTYPE html>") > -1) {
                alert("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else {
                let result = JSON.parse(data);

                if (result.success == true) {
					$("#lbl_payment_amount_due").text(result.amount_due.toFixed(2));
					$("#txt_payment_pay").val(result.amount_due.toFixed(2));
					$("#badge_payment_count").text(result.payment_list.length);

					let table_payment = "";

					if (result.payment_list.length > 0) {
						$.each(result.payment_list, function(idx, payment){
							table_payment += (Number(payment.status_id)==1) ? "<tr class='danger'>" : "<tr>";
							table_payment += "	<td align='center'>";
							table_payment += "		<span id='" + payment.id + "' class='btn_payment_print fa fa-fw fa-print text-primary' title='Print'></span>";
							table_payment += "		<span id='" + payment.id + "' class='btn_payment_cancel fa fa-fw fa-trash text-danger' title='Cancel Payment'></span>";
							table_payment += "	</td>";
							table_payment += "	<td align='center'>" + payment.payment_no + "</td>";
							table_payment += "	<td align='center'>" + payment.date + "</td>";
							table_payment += "	<td align='center'>" + payment.payment_type + "</td>";
							table_payment += "	<td align='right'>" + Number(payment.amount).toFixed(2) + "</td>";
							table_payment += "	<td align='center'>" + payment.reference + "</td>";
							table_payment += "	<td align='center'>" + payment.created.toUpperCase() + "</td>"
							table_payment += "	<td align='center'>" + payment.status + "</td>";
							table_payment += "</tr>";
						});
					}else{
						table_payment += "	<tr><td align='center' colspan='8'>No Payment Done Yet</td></tr>";
					}

					$("#tbl_payment_list tbody").html(table_payment);

					//show payment modal window
					$("#modal_payment").modal("show");
                } else {
                    bootbox.alert(result.error);
                }
            }
        });


	});

	$("#txt_payment_tender, #txt_payment_pay").on("keyup", function(){
		if ($(this).val()) {
			let pay_amount = Number($("#txt_payment_pay").val()) || 0;
			let tender_amount = Number($("#txt_payment_tender").val()) || 0;
			let change_amount = 0;

			change_amount = Number(tender_amount) - Number(pay_amount);

			$("#txt_payment_change").val(change_amount.toFixed(2));
		}
	});

	$(document).on("click", "#btn_payment_save", function(){
		let transaction_id = $("#id_update").val();
		let amount_due = Number($("#lbl_payment_amount_due").text()) || 0;
		let date = $("#txt_payment_date").val();
		let payment_type_id = $("#txt_payment_type").val();
		let pay_amount = Number($("#txt_payment_pay").val()) || 0;
		let tender_amount = Number($("#txt_payment_tender").val()) || 0;
		let change_amount = Number($("#txt_payment_change").val()) || 0;
		let reference = $("#txt_payment_reference").val();
		let this_modal = "#modal_payment";

        if (date && payment_type_id && pay_amount > 0.01 && tender_amount > 0.01){

			if (tender_amount >= pay_amount){
				$(this_modal + " .modal_error").hide();
				$(this_modal + " .modal_button").hide();
				$(this_modal + " .modal-body").hide();
				$(this_modal + " .modal_waiting").show();

				$.post("<?= base_url(); ?>current_transaction/save_payment", {
					transaction_id: transaction_id,
					date:date,
					amount_due: amount_due,
					payment_type_id: payment_type_id,
					pay_amount: pay_amount,
					tender_amount: tender_amount,
					change_amount: change_amount,
					reference: reference
				}, function(data) {
					$(this_modal + " .modal_error").hide();
					$(this_modal + " .modal_button").show();
					$(this_modal + " .modal-body").show();
					$(this_modal + " .modal_waiting").hide();

					if (data.indexOf("<!DOCTYPE html>") > -1) {
						alert("Error: Session Time-Out, You must login again to continue.");
						location.reload(true);
					}else{
						let result = JSON.parse(data);

						if (result.success == true) {
							total_paid = Number(result.total_paid);
							$("#lbl_total_paid").text(Number(result.total_paid).toFixed(2));
							$("#lbl_amount_due").text(Number(result.amount_due).toFixed(2));

							$(this_modal).modal("hide");
							$(".txt_payment_field").val("");

							bootbox.alert("Payment Successfully Saved!");

							window.open("<?= base_url(); ?>current_transaction/print_payment/" + result.payment_id,"_blank");
						}else{
							$(this_modal + " .modal_error_msg").text(result.error);
							$(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
						}
					}
				});
			}else{
				$(this_modal + " .modal_error_msg").text("Tender amount is less than the amount to be paid!");
            	$(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
			}

        }else{
            $(this_modal + " .modal_error_msg").text("Error: Fields with red asterisk are required!");
            $(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
        }

	});

	$(document).on('click', '.btn_payment_cancel', function(){
		let transaction_id = $("#id_update").val();
		let payment_id = $(this).attr('id');
		let this_modal = "#modal_payment";

		if (transaction_id && payment_id){
			bootbox.confirm("Are you sure you want to cancel this payment - PMT" + payment_id.padStart(3,"0") + " ?", function(result) {
                if (result) {
					$(this_modal + " .modal_error").hide();
					$(this_modal + " .modal_button").hide();
					$(this_modal + " .modal-body").hide();
					$(this_modal + " .modal_waiting").show();

                    $.post("<?= base_url(); ?>current_transaction/cancel_payment", {
                        transaction_id: transaction_id,
						payment_id: payment_id
                    }, function(data) {
						$(this_modal + " .modal_error").hide();
						$(this_modal + " .modal_button").show();
						$(this_modal + " .modal-body").show();
						$(this_modal + " .modal_waiting").hide();

						if (data.indexOf("<!DOCTYPE html>") > -1) {
							alert("Error: Session Time-Out, You must login again to continue.");
							location.reload(true);
						} else {
							let result = JSON.parse(data);

							if (result.success == true) {
								let table_payment = "";

								if (result.payment_list.length > 0) {
									$.each(result.payment_list, function(idx, payment){
										table_payment += (Number(payment.status_id)==1) ? "<tr class='danger'>" : "<tr>";
										table_payment += "	<td align='center'>";
										table_payment += "		<span id='" + payment.id + "' class='btn_payment_print fa fa-fw fa-print text-primary' title='Print'></span>";
										table_payment += "		<span id='" + payment.id + "' class='btn_payment_cancel fa fa-fw fa-trash text-danger' title='Cancel Payment'></span>";
										table_payment += "	</td>";
										table_payment += "	<td align='center'>" + payment.payment_no + "</td>";
										table_payment += "	<td align='center'>" + payment.date + "</td>";
										table_payment += "	<td align='center'>" + payment.payment_type + "</td>";
										table_payment += "	<td align='right'>" + Number(payment.amount).toFixed(2) + "</td>";
										table_payment += "	<td align='center'>" + payment.reference + "</td>";
										table_payment += "	<td align='center'>" + payment.created.toUpperCase() + "</td>"
										table_payment += "	<td align='center'>" + payment.status + "</td>";
										table_payment += "</tr>";
									});
								}else{
									table_payment += "	<tr><td align='center' colspan='8'>No Payment Done Yet</td></tr>";
								}

								$("#lbl_total_paid").text(Number(result.total_paid).toFixed(2));
								$("#lbl_amount_due").text(Number(result.amount_due).toFixed(2));

								$("#tbl_payment_list tbody").html(table_payment);

							} else {
								bootbox.alert(result.error);
							}
						}
                    });
                }
            });
		}else{
			$(this_modal + " .modal_error_msg").text("Error: Critical Error Encountered!");
			$(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
		}
	});

	$(document).on('click', '.btn_payment_print', function(){
		let id = $(this).attr('id');

		if (id){
			window.open("<?= base_url(); ?>current_transaction/print_payment/" + id,"_blank");
		}else{
			let this_modal = "#modal_payment";
			$(this_modal + " .modal_error_msg").text("Error: Critical Error Encountered!");
			$(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
		}
	});

	$(document).on("click","#btn_send", function(){
		$("#modal_send").modal();
	});

	$(document).on("click","#btn_send_save",function(){
		let location_id = $("#location_id_send").val();
		let location = $("#location_id_send option:selected").text();
		let this_modal = "#modal_send";

		if (location_id){

			$("#location_id_update").val(location_id).trigger("change").trigger("chosen:updated");

			$(this_modal).modal("hide");
			bootbox.alert("Successfully sent to " + location);
		}else{
			$(this_modal + " .modal_error_msg").text("Error: No location selected!");
            $(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
		}

	});
    //endregion transaction buttons


    //QTY CHANGE
    $(document).on("keyup", "#qty_new", function() {
        compute_entry();
    });

    //ITEM SELECT
    $(document).on("change", "#product_id_new, #payment_method_id_update", function(e) {
        e.preventDefault();
        compute_entry();
    });

    //ITEM SAVE
    $(document).on("click", "#item_save", function(e) {
        e.preventDefault();

        let transaction_id = $("#id_update").val();
		let payment_method_id = $("#payment_method_id_update").val();
        let product_id = $("#product_id_new").val();
        let product = $("#product_id_new option:selected").text();
        let uom = $("#uom_new").val();
        let qty = $("#qty_new").val();
        let price = $("#price_new").val();
        let amount = $("#amount_new").val();
        let commission_amount = $("#commission_amount_new").val();
        let insurance_amount = $("#insurance_amount_new").val();
        let total = $("#total_new").val();

        if (transaction_id && product_id) {
			if (payment_method_id){
				$("#loading").modal();

				$.post("<?= base_url(); ?>current_transaction/add_item", {
					transaction_id: transaction_id,
					product_id: product_id,
					product: product,
					uom: uom,
					qty: qty,
					price: price,
					amount: amount,
					commission_amount: commission_amount,
					insurance_amount: insurance_amount,
					total: total
				}, function(data) {
					$("#loading").modal("hide");

					if (data.indexOf("<!DOCTYPE html>") > -1) {
						alert("Error: Session Time-Out, You must login again to continue.");
						location.reload(true);
					} else {
						let result = JSON.parse(data);

						if (result.success == true) {
							$("#tbl_list tbody").html(item_list(result.result));
							$("#tbl_list tbody").append(new_entry_field);
							$("#product_id_new").trigger("select", "focus");

                            $('.chosen-select').chosen({
                                allow_single_deselect: true
                            });

							display_total();

						} else {
							bootbox.alert(result.message);
						}
					}
				});
			}else{
				bootbox.alert("Error: Please select a payment method first!");
            	$("#payment_method_update").trigger("select", "focus");
			}

        } else {
            bootbox.alert("Error: Fields with * are required!");
            $("#product_id_new").trigger("select", "focus");
        }
    });

    $(document).on("keypress", ".field_new", function(e) {
        if (e.which == 13) {
            $("#item_save").trigger("click");
        }
    });

    //ITEM DELETE
    $(document).on("click", ".btn_item_cancel", function(e) {
        e.preventDefault();
        let id = $(this).attr("id");
        let transaction_id = $("#id_update").val();

        if (id && transaction_id) {
            bootbox.prompt({
                title: "Provide Cancel Reason!",
                inputType: 'textarea',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm'
                    }
                },
                callback: function(result) {

                    if (result !== null) {
                        if (result) {
                            $("#loading").modal();

                            $.post("<?= base_url(); ?>current_transaction/item_cancel", {
                                id: id,
                                transaction_id: transaction_id,
                                reason: result
                            }, function(data) {
                                $("#loading").modal("hide");

                                if (data.indexOf("<!DOCTYPE html>") > -1) {
                                    alert(
                                        "Error: Session Time-Out, You must login again to continue."
                                    );
                                    location.reload(true);
                                } else {
                                    let result = JSON.parse(data);

                                    if (result.success == true) {
                                        $("#tbl_list tbody").html(item_list(result.result));
                                        $("#tbl_list tbody").append(new_entry_field);
                                        $("#product_id_new").trigger("select", "focus");

                                        display_total();

                                    } else {
                                        bootbox.alert(result.message);
                                    }
                                }
                            });
                        } else {
                            return false;
                        }
                    }
                }
            });


            // bootbox.confirm("Are you sure you want to cancel this item?", function(result) {
            //     if (result) {
            //         $.post("<?= base_url(); ?>current_transaction/delete_item", {
            //             id: id
            //         }, function(data) {
            //             if (data.indexOf("<!DOCTYPE html>") > -1) {
            //                 alert("Error: Session Time-Out, You must login again to continue.");
            //                 location.reload(true);
            //             } else {
            //                 let result = JSON.parse(data);

            //                 if (result.success == true) {
            //                     $("#tbl_list tbody").html(item_list(result.result));
            //                     $("#tbl_list tbody").append(new_entry_field);
            //                     $("#product_id_new").trigger("select", "focus");

            //                     display_total();

            //                 } else {
            //                     bootbox.alert(result.message);
            //                 }
            //             }
            //         });
            //     }
            // });
        }
    });

    //ITEM MODIFY
    // $(document).on("click", ".btn_item_modify", function(e) {
    //     e.preventDefault();
    //     let id = $(this).attr("id");

    //     if (id) {
    //         $.post("<?= base_url(); ?>current_transaction/item_search_row", {
    //             id: id
    //         }, function(data) {

    //             if (data.indexOf("<!DOCTYPE html>") > -1) {
    //                 alert("Error: Session Time-Out, You must login again to continue.");
    //                 location.reload(true);
    //             } else if (data.indexOf("Error: ") > -1) {
    //                 bootbox.alert(data);
    //             } else {
    //                 var result = JSON.parse(data);

    //                 //set the data to the field
    //                 $.each(result, function(key, val) {
    //                     if ($("#" + key + "_item_update")) {
    //                         $("#" + key + "_item_update").val(val);
    //                     }
    //                 });

    //                 $("#modal_modify").modal();

    //                 $("#modal_modify").on("shown.bs.modal", function() {
    //                     $("#qty_item_update").select().focus();
    //                 });
    //             }
    //         });

    //     } else {
    //         bootbox.alert("Error: Critical Error Encountered!");
    //     }
    // });

    // //ITEM UPDATE
    // $(document).on("keypress", ".field_item_update", function(e) {
    //     if (e.which == 13) {
    //         $("#btn_item_update").trigger("click");
    //     }
    // });

    // $(document).on("click", "#btn_item_update", function(e) {
    //     let transaction_id = $("#transaction_id_item_update").val();
    //     let id = $("#id_item_update").val();

    //     if (transaction_id) {
    //         let qty = $("#qty_item_update").val();
    //         let uom_id = $("#uom_id_item_update").val();
    //         let type_id = $("#type_id_item_update").val();
    //         let description = $("#description_item_update").val();

    //         if (qty > 0 && uom_id && type_id && description) {
    //             $("#modal_modify .modal_error, #modal_modify .modal_button, #modal_modify .modal-body").hide();
    //             $("#modal_modify .modal_waiting").show();

    //             var formData = new FormData($("#frm_item_update")[0]);

    //             $.ajax({
    //                 type: "POST",
    //                 url: "<?= base_url(); ?>current_transaction/item_update",
    //                 data: formData,
    //                 enctype: "multipart/form-data",
    //                 processData: false, // tell jQuery not to process the data
    //                 contentType: false, // tell jQuery not to set contentType
    //                 dataType: "json",
    //                 //encode: true,
    //             }).done(function(data) {

    //                 $('#modal_modify .modal_error, #modal_modify .modal_waiting').hide();
    //                 $('#modal_modify .modal_button, #modal_modify .modal-body').show();

    //                 if (!data.success) {
    //                     $("#modal_modify .modal_error_msg").text(data.errors.error);
    //                     $("#modal_modify  .modal_error").stop(true, true).show().delay(15000).fadeOut(
    //                         "slow");
    //                     $("#qty_item_update").select().focus();
    //                 } else {
    //                     //no error
    //                     if (data) {

    //                         $("#tbl_list tbody").html(item_list(data.result));

    //                         //clear fields
    //                         $(".field_item_update").val("");

    //                         $('[data-toggle="tooltip"]').tooltip({
    //                             html: true
    //                         });
    //                     }

    //                     $("#modal_modify").modal("hide")
    //                 }
    //             }).fail(function(data) {
    //                 alert(
    //                     "Error: Server Error or Session Time-Out!, Please try again or reload the page!"
    //                 );
    //                 $('#modal_modify .modal_error, #modal_modify .modal_waiting').hide();
    //                 $('#modal_modify .modal_button, #modal_modify .modal-body').show();
    //             });

    //             e.preventDefault();

    //         } else {
    //             $("#modal_modify .modal_error_msg").text("Error: Fields with * are required!");
    //             $("#modal_modify .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
    //             $("#qty_item_update").select().focus();
    //         }
    //     } else {
    //         $("#modal_modify .modal_error_msg").text("Error: Critical Error Encountered!");
    //         $("#modal_modify .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
    //         $("#qty_item_update").select().focus();
    //     }

    // });

    // //ITEM INFO
    // $(document).on("click", ".btn_item_info", function(e) {
    //     e.preventDefault();
    //     let id = $(this).attr("id");

    //     if (id) {
    //         $.post("<?= base_url(); ?>current_transaction/item_search_row", {
    //             id: id
    //         }, function(data) {

    //             if (data.indexOf("<!DOCTYPE html>") > -1) {
    //                 alert("Error: Session Time-Out, You must login again to continue.");
    //                 location.reload(true);
    //             } else if (data.indexOf("Error: ") > -1) {
    //                 bootbox.alert(data);
    //             } else {
    //                 var result = JSON.parse(data);

    //                 //set the data to the field
    //                 $.each(result, function(key, val) {
    //                     if ($("#" + key + "_item_info")) {
    //                         $("#" + key + "_item_info").val(val);
    //                     }
    //                 });

    //                 $("#modal_info").modal();

    //                 $("#modal_info").on("shown.bs.modal", function() {
    //                     $("#deleted_reason_item_info").select().focus();
    //                 });
    //             }
    //         });

    //     } else {
    //         bootbox.alert("Error: Critical Error Encountered!");
    //     }
    // });

    // //ITEM CANCEL
    // $(document).on("click", "#btn_item_delete", function(e) {
    //     let transaction_id = $("#transaction_id_item_info").val();
    //     let id = $("#id_item_info").val();
    //     let reason = $("#deleted_reason_item_info").val();

    //     if (transaction_id && id) {
    //         if (reason) {
    //             $("#modal_info .modal_error, #modal_info .modal_button, #modal_info .modal-body").hide();
    //             $("#modal_info .modal_waiting").show();

    //             var formData = new FormData($("#frm_item_info")[0]);

    //             $.ajax({
    //                 type: "POST",
    //                 url: "<?= base_url(); ?>current_transaction/item_delete",
    //                 data: formData,
    //                 enctype: "multipart/form-data",
    //                 processData: false, // tell jQuery not to process the data
    //                 contentType: false, // tell jQuery not to set contentType
    //                 dataType: "json",
    //                 //encode: true,
    //             }).done(function(data) {

    //                 $('#modal_info .modal_error, #modal_info .modal_waiting').hide();
    //                 $('#modal_info .modal_button, #modal_info .modal-body').show();

    //                 if (!data.success) {
    //                     $("#modal_info .modal_error_msg").text(data.errors.error);
    //                     $("#modal_info  .modal_error").stop(true, true).show().delay(15000).fadeOut(
    //                         "slow");
    //                 } else {
    //                     //no error
    //                     if (data) {

    //                         $("#tbl_list tbody").html(item_list(data.result));

    //                         //clear fields
    //                         $(".field_item_info").val("");

    //                         $('[data-toggle="tooltip"]').tooltip({
    //                             html: true
    //                         });
    //                     }

    //                     $("#modal_info").modal("hide")
    //                 }
    //             }).fail(function(data) {
    //                 alert(
    //                     "Error: Server Error or Session Time-Out!, Please try again or reload the page!"
    //                 );
    //                 $('#modal_info .modal_error, #modal_info .modal_waiting').hide();
    //                 $('#modal_info .modal_button, #modal_info .modal-body').show();
    //                 $("#deleted_reason_item_info").select().focus();
    //             });

    //             e.preventDefault();
    //         } else {
    //             $("#modal_info .modal_error_msg").text("Error: Please provide reason!");
    //             $("#modal_info .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
    //             $("#deleted_reason_item_info").select().focus();
    //         }

    //     } else {
    //         $("#modal_info .modal_error_msg").text("Error: Critical Error Encountered!");
    //         $("#modal_info .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
    //         $("#deleted_reason_item_info").select().focus();
    //     }

    // });

    // //cancel transaction
    // //delete
    // $(document).on("click", "#btn_transaction_cancel", function() {
    //     let transaction_id = $("#id_update").val();

    //     if (transaction_id) {
    //         bootbox.prompt({
    //             title: "Provide Cancel Reason!",
    //             inputType: 'textarea',
    //             buttons: {
    //                 cancel: {
    //                     label: '<i class="fa fa-times"></i> Cancel'
    //                 },
    //                 confirm: {
    //                     label: '<i class="fa fa-check"></i> Confirm'
    //                 }
    //             },
    //             callback: function(result) {

    //                 if (result !== null) {
    //                     if (result) {
    //                         $.post("<?= base_url(); ?>current_transaction/cancel", {
    //                             transaction_id: transaction_id,
    //                             reason: result
    //                         }, function(data) {
    //                             if (data.indexOf("<!DOCTYPE html>") > -1) {
    //                                 alert(
    //                                     "Error: Session Time-Out, You must login again to continue."
    //                                 );
    //                                 location.reload(true);
    //                             } else if (data.indexOf("Error: ") > -1) {
    //                                 bootbox.alert(data);
    //                             } else {
    //                                 $("#loading").modal();
    //                                 location.reload();
    //                             }
    //                         });
    //                     } else {
    //                         return false;
    //                     }
    //                 }
    //             }
    //         });

    //     } else {
    //         bootbox.alert("Error: Critical Error Encountered!");
    //     }

    // });

    // //for dept approval
    // $(document).on("click", "#btn_dept_approval", function(e) {
    //     e.preventDefault();
    //     $("#modal_dept_approval").modal();
    // });

    // $(document).on("click", "#btn_dept_approver_send", function(e) {
    //     e.preventDefault();
    //     let this_modal = "#modal_dept_approval";
    //     let transaction_id = $("#id_update").val();
    //     let approver_id = $("#txt_dept_approver").val();
    //     let approver_name = $("#txt_dept_approver option:selected").text();

    //     if (transaction_id) {

    //         if (approver_id) {
    //             $(this_modal + " .modal_error").hide();
    //             $(this_modal + " .modal_button").hide();
    //             $(this_modal + " .modal-body").hide();
    //             $(this_modal + " .modal_waiting").show();

    //             $.post("<?= base_url(); ?>current_transaction/for_dept_approval", {
    //                 transaction_id: transaction_id,
    //                 approver_id: approver_id,
    //                 approver_name: approver_name
    //             }, function(data) {
    //                 $(this_modal + " .modal_button").show();
    //                 $(this_modal + " .modal-body").show();
    //                 $(this_modal + " .modal_waiting").hide();

    //                 if (data.indexOf("<!DOCTYPE html>") > -1) {
    //                     alert("Error: Session Time-Out, You must login again to continue.");
    //                     location.reload(true);
    //                 } else if (data.indexOf("Error: ") > -1) {
    //                     $(this_modal + " .modal_error_msg").text(data);
    //                     $(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut(
    //                         "slow");
    //                 } else {
    //                     $(this_modal).modal("hide");
    //                     $("#loading").modal();
    //                     location.reload();
    //                 }

    //             });
    //         } else {
    //             $(this_modal + " .modal_error_msg").text("Error: Please select approver!");
    //             $(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut(
    //                 "slow");
    //         }
    //     } else {
    //         $(this_modal + " .modal_error_msg").text("Error: Critical Error Encountered!");
    //         $(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut(
    //             "slow");
    //     }

    // });

    // //transfer dept approval
    // $(document).on("click", "#btn_dept_approval_transfer", function(e) {
    //     e.preventDefault();
    //     $("#modal_dept_approval_transfer").modal();
    // });

    // $(document).on("click", "#btn_dept_approver_transfer_send", function(e) {
    //     e.preventDefault();
    //     let this_modal = "#modal_dept_approval_transfer";

    //     let transaction_id = $("#id_update").val();
    //     let approver_id = $("#txt_dept_approver_transfer").val();
    //     let approver_name = $("#txt_dept_approver_transfer option:selected").text();

    //     if (transaction_id) {

    //         if (approver_id) {
    //             $(this_modal + " .modal_error").hide();
    //             $(this_modal + " .modal_button").hide();
    //             $(this_modal + " .modal-body").hide();
    //             $(this_modal + " .modal_waiting").show();

    //             $.post("<?= base_url(); ?>current_transaction/transfer_dept_approver", {
    //                 transaction_id: transaction_id,
    //                 approver_id: approver_id,
    //                 approver_name: approver_name
    //             }, function(data) {
    //                 $(this_modal + " .modal_button").show();
    //                 $(this_modal + " .modal-body").show();
    //                 $(this_modal + " .modal_waiting").hide();

    //                 if (data.indexOf("<!DOCTYPE html>") > -1) {
    //                     alert("Error: Session Time-Out, You must login again to continue.");
    //                     location.reload(true);
    //                 } else if (data.indexOf("Error: ") > -1) {
    //                     $(this_modal + " .modal_error_msg").text(data);
    //                     $(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut(
    //                         "slow");
    //                 } else {
    //                     $(this_modal).modal("hide");
    //                     $("#loading").modal();
    //                     location.reload();
    //                 }

    //             });
    //         } else {
    //             $(this_modal + " .modal_error_msg").text("Error: Please select approver!");
    //             $(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut(
    //                 "slow");
    //         }
    //     } else {
    //         $(this_modal + " .modal_error_msg").text("Error: Critical Error Encountered!");
    //         $(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut(
    //             "slow");
    //     }
    // });

    // //gm approval
    // $(document).on("click", "#btn_gm_approval", function(e) {
    //     e.preventDefault();
    //     $("#modal_gm_approval").modal();
    // });

    // $(document).on("click", "#btn_gm_approver_send", function(e) {
    //     e.preventDefault();
    //     let this_modal = "#modal_gm_approval";
    //     let transaction_id = $("#id_update").val();
    //     let approver_id = $("#txt_gm_approver").val();
    //     let approver_name = $("#txt_gm_approver option:selected").text();

    //     if (transaction_id) {

    //         if (approver_id) {
    //             $(this_modal + " .modal_error").hide();
    //             $(this_modal + " .modal_button").hide();
    //             $(this_modal + " .modal-body").hide();
    //             $(this_modal + " .modal_waiting").show();

    //             $.post("<?= base_url(); ?>current_transaction/for_gm_approval", {
    //                 transaction_id: transaction_id,
    //                 approver_id: approver_id,
    //                 approver_name: approver_name
    //             }, function(data) {
    //                 $(this_modal + " .modal_button").show();
    //                 $(this_modal + " .modal-body").show();
    //                 $(this_modal + " .modal_waiting").hide();

    //                 if (data.indexOf("<!DOCTYPE html>") > -1) {
    //                     alert("Error: Session Time-Out, You must login again to continue.");
    //                     location.reload(true);
    //                 } else if (data.indexOf("Error: ") > -1) {
    //                     $(this_modal + " .modal_error_msg").text(data);
    //                     $(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut(
    //                         "slow");
    //                 } else {
    //                     $(this_modal).modal("hide");
    //                     $("#loading").modal();
    //                     location.reload();
    //                 }

    //             });
    //         } else {
    //             $(this_modal + " .modal_error_msg").text("Error: Please select approver!");
    //             $(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut(
    //                 "slow");
    //         }
    //     } else {
    //         $(this_modal + " .modal_error_msg").text("Error: Critical Error Encountered!");
    //         $(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut(
    //             "slow");
    //     }

    // });

    // //transfer gm approval
    // $(document).on("click", "#btn_gm_approval_transfer", function(e) {
    //     e.preventDefault();
    //     $("#modal_gm_approval_transfer").modal();
    // });

    // $(document).on("click", "#btn_gm_approver_transfer_send", function(e) {
    //     e.preventDefault();
    //     let this_modal = "#modal_gm_approval_transfer";

    //     let transaction_id = $("#id_update").val();
    //     let approver_id = $("#txt_gm_approver_transfer").val();
    //     let approver_name = $("#txt_gm_approver_transfer option:selected").text();

    //     if (transaction_id) {

    //         if (approver_id) {
    //             $(this_modal + " .modal_error").hide();
    //             $(this_modal + " .modal_button").hide();
    //             $(this_modal + " .modal-body").hide();
    //             $(this_modal + " .modal_waiting").show();

    //             $.post("<?= base_url(); ?>current_transaction/transfer_gm_approver", {
    //                 transaction_id: transaction_id,
    //                 approver_id: approver_id,
    //                 approver_name: approver_name
    //             }, function(data) {
    //                 $(this_modal + " .modal_button").show();
    //                 $(this_modal + " .modal-body").show();
    //                 $(this_modal + " .modal_waiting").hide();

    //                 if (data.indexOf("<!DOCTYPE html>") > -1) {
    //                     alert("Error: Session Time-Out, You must login again to continue.");
    //                     location.reload(true);
    //                 } else if (data.indexOf("Error: ") > -1) {
    //                     $(this_modal + " .modal_error_msg").text(data);
    //                     $(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut(
    //                         "slow");
    //                 } else {
    //                     $(this_modal).modal("hide");
    //                     $("#loading").modal();
    //                     location.reload();
    //                 }
    //             });
    //         } else {
    //             $(this_modal + " .modal_error_msg").text("Error: Please select approver!");
    //             $(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut(
    //                 "slow");
    //         }
    //     } else {
    //         $(this_modal + " .modal_error_msg").text("Error: Critical Error Encountered!");
    //         $(this_modal + " .modal_error").stop(true, true).show().delay(15000).fadeOut(
    //             "slow");
    //     }

    // });
    </script>

</body>

</html>
