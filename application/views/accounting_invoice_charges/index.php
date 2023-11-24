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
                                        <span id="lbl_count" class="badge badge-warning">0</span>
                                    </small>


                                    <div class="input-group pull-right" style='width: 15em;'>
                                        <input id='txt_search' class="form-control " type="text" placeholder='Search' />
                                        <span class="input-group-btn">
                                            <button id='btn_search' class="btn btn-sm btn-primary" type="button"
                                                title='Search' data-toggle='tooltip'>
                                                <i class="ace-icon fa fa-search bigger-110"></i>
                                                Go!
                                            </button>

                                            <!-- <button id='btn_asearch' class="btn btn-sm btn-info" type="button"
                                                title='Advance Search' data-toggle='tooltip'>
                                                <i class="ace-icon fa fa-search-plus bigger-110"></i>
                                            </button> -->

                                        </span>
                                    </div>
                                </h1>
                            </div><!-- /.page-header -->

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="table-header clearfix">
                                        Result List <span id="lbl_result_info" class="badge badge-warning"></span>
                                        <div class="pull-right" style="padding-right: 0.5em; padding-top: 0.4em;">
                                            <div class="pull-right tableTools-container"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <table id="tbl_list"
                                            class="table  table-bordered table-hover table-striped table-fixed-header">
                                            <thead class="header">
                                                <tr>
                                                    <th>OPTION</th>
                                                    <th>INV #</th>
                                                    <th>DATE</th>
                                                    <th>COMPANY</th>
													<th>PATIENT</th>
													<th>TOTAL</th>
													<th>PAID</th>
													<th>AMOUNT DUE</th>
                                                    <th>STATUS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td align='center' colspan='9'>Use search button to display record
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
		$this->load->view('accounting_invoice_charges/modal_payment');
		// $this->load->view('accounting_invoice_charges/modal_modify');
		// $this->load->view('accounting_invoice_charges/modal_info');
		// $this->load->view('accounting_invoice_charges/modal_asearch');

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
    var current_row;
    var current_data;
    var current_status_id;
    //functions

    function table_buttons(id, status_id = 1) {
        var option = "";
        option += `<a
						href='<?= base_url(); ?>current_transaction/view/${id}'
                        target='_blank'
						id='${id}'
						class='btn btn-xs btn-info text-primary fa fa-search'
						title='View Transaction'
						data-toggle='tooltip'
					></a> `;
        option += `<a
						href='<?= base_url(); ?>current_transaction/print_charges/${id}'
                        target='_blank'
						id='${id}'
						class='btn btn-xs btn-warning text-primary fa fa-print'
						title='Print Invoice'
						data-toggle='tooltip'
					></a> `;
		option += `<a
						id='${id}'
						class='btn_payment btn btn-xs btn-info text-primary fa fa-credit-card'
						title='Add/View Payment'
						data-toggle='tooltip'
					></a> `;
		option += `<a
						id='${id}'
						class='btn_complete btn btn-xs btn-success text-primary fa fa-check'
						title='Close this Invoice'
						data-toggle='tooltip'
					></a> `;


        return option;
    }

    function table_status(status_id, status, created_at, created_by, deleted_at, deleted_by, deleted_reason) {
        status_id = parseInt(status_id);

        switch (status_id) {
            case 1: //deleted
                status = "<span title='" + deleted_at + " BY: " + deleted_by + " REASON: " + deleted_reason +
                    "' data-toggle='tooltip' >" + status + "</span>";
                break;

            case 2: //active
                status = "<span title='" + created_at + " ENCODED: " + created_by + "' data-toggle='tooltip' >" +
                    status + "</span>";
                break;

            default:
                status = status;

        }

        return status;
    }

    function reload_table(table_id, dataSet = []) {
        var myTable =
            $(table_id)
            //.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
            .DataTable({
                destroy: true,
                bAutoWidth: false,
                "aoColumns": [{
                        "bSortable": false,
                        className: "text-center"
                    },
					{ className: "text-center" }, //inv#
					{ className: "text-center" }, //date
					{ className: "text-center" }, //company
					{ className: "text-center" }, //patient
					{ className: "text-center" }, //total
					{ className: "text-center" }, //paid
					{ className: "text-center" }, //due
                    { className: "text-center" } //status
                ],
                data: dataSet,
                "aaSorting": [],
                "rowCallback": function(row, data, index) {
                    if (data[10] == 1) { //deleted
                        $('td', row).removeClass('success').addClass('danger');
					} else if (data[10] == 4){ //completed
						$('td', row).removeClass('danger').addClass('success');
                    } else {
                        $('td', row).removeClass('danger').removeClass('success');
                    }
                }

                //"bProcessing": true,
                //"bServerSide": true,
                //"sAjaxSource": "http://127.0.0.1/table.php"	,

                //,
                //"sScrollY": "200px",
                //"bPaginate": false,

                //"sScrollX": "100%",
                //"sScrollXInner": "120%",
                //"bScrollCollapse": true,
                //Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
                //you may want to wrap the table inside a "div.dataTables_borderWrap" element

                //"iDisplayLength": 50


                // select: {
                // 	style: 'multi'
                // }
            });

        $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

        new $.fn.dataTable.Buttons(myTable, {
            buttons: [
                //   {
                // 	"extend": "colvis",
                // 	"text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
                // 	"className": "btn btn-white btn-primary btn-bold",
                // 	columns: ':not(:first):not(:last)'
                //   },
                {
                    "extend": "copy",
                    "text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    "title": "Copy",
                    "data-toggle": "tooltip",
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8] //0, 1, 2, 3
                    }
                },
                {
                    "extend": "csv",
                    "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to CSV</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    exportOptions: {
						columns: [1, 2, 3, 4, 5, 6, 7, 8] //0, 1, 2, 3
                    }
                },
                {
                    "extend": "excel",
                    "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8] //0, 1, 2, 3
                    }
                },
                {
                    "extend": "pdf",
                    "text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8] //0, 1, 2, 3
                    }
                },
                {
                    "extend": "print",
                    "text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    autoPrint: false,
                    //message: 'This print was produced using the Print button for DataTables',
                    message: "<?= $page_name; ?>",
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8] //0, 1, 2, 3
                    }
                }
            ]
        });
        myTable.buttons().container().appendTo($('.tableTools-container'));
    }

    function populate_table(table_ref, result_data) {
        var dataSet = [];
        var result_count = result_data.length;

        $.each(result_data, function(index, value) {
            var id = value.id;

            var created_by = value.created;
            var created_at = value.created_at;
            var updated_by = value.updated;
            var updated_at = value.updated_at;
            var deleted_by = value.deleted;
            var deleted_at = value.deleted_at;
            var deleted_reason = value.deleted_reason;
            var status_id = parseInt(value.status_id);

            var status = table_status(status_id, value.status, created_at, created_by, deleted_at, deleted_by,
                deleted_reason)
            var option = table_buttons(id, status_id);

            dataSet.push([
				option,
				value.invoice_no,
				value.date,
				value.company,
				value.patient,
				value.total,
				value.total_paid,
				value.amount_due,
				status, id, status_id
			]);
        });

        $("#lbl_count").text(result_count);
        reload_table(table_ref, dataSet);
    }

	function search_row(transaction_id){
		if (transaction_id){
			$("#loading").modal();

			$.post("<?= base_url(); ?>accounting_invoice_charges/search_row",{
				id: transaction_id
			}, function(data) {
				$("#loading").modal("hide");

				if (data.indexOf("<!DOCTYPE html>") > -1) {
					alert("Error: Session Time-Out, You must login again to continue.");
					location.reload(true);
				} else if (data.indexOf("Error: ") > -1) {
					bootbox.alert(data);
				} else {
					if (data) {
						result_data = JSON.parse(data);
						current_data[1] = result_data.invoice_no;
                        current_data[2] = result_data.date;
                        current_data[3] = result_data.company;
						current_data[4] = result_data.patient;
						current_data[5] = result_data.total;
						current_data[6] = result_data.total_paid;
						current_data[7] = result_data.amount_due;
						current_data[8] = result_data.status;
						current_data[10] = result_data.status_id;
                        current_row.data(current_data).invalidate();
					}

					$("[data-toggle='tooltip']").tooltip({
						html: true
					});
				}
			});
		}else{
			bootbox.alert("Please refresh the page!");
		}
	}

	function search_active(){
		$.get("<?= base_url(); ?>accounting_invoice_charges/search_active", function(data) {
            $("#loading").modal("hide");

            if (data.indexOf("<!DOCTYPE html>") > -1) {
                alert("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else if (data.indexOf("Error: ") > -1) {
                bootbox.alert(data);
                $("#txt_search").trigger("select", "focus");
            } else {
                if (data) {
                    result_data = JSON.parse(data);
                    populate_table("#tbl_list", result_data);
                }

                $("[data-toggle='tooltip']").tooltip({
                    html: true
                });
            }
        });
	}

    $(document).ready(function() {
        reload_table("#tbl_list");

		search_active();
        // $("#txt_search").val("<?= isset($_GET['search']) ? ($_GET['search']) : '' ?>");
        // $("#btn_search").trigger("click");
    });

    //search
    $(document).on("keypress", "#txt_search", function(e) {
        if (e.which == 13) {
            $("#btn_search").trigger("click");
        }
    });

    $(document).on("click", "#btn_search", function() {
        var search = $("#txt_search").val();

        $("#loading").modal();

        $.get("<?= base_url(); ?>accounting_invoice_charges/search?search=" + search, function(data) {
            $("#loading").modal("hide");

            if (data.indexOf("<!DOCTYPE html>") > -1) {
                alert("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else if (data.indexOf("Error: ") > -1) {
                bootbox.alert(data);
                $("#txt_search").trigger("select", "focus");
            } else {
                if (data) {
                    result_data = JSON.parse(data);
                    populate_table("#tbl_list", result_data);
                }

                $("[data-toggle='tooltip']").tooltip({
                    html: true
                });
            }
        });
    });

    //advance search
    $(document).on("click", "#btn_asearch", function() {
        $(".field").val("");
        $(".modal_error, .modal_waiting").hide();
        $("#modal_asearch").modal();

        $("#modal_asearch").on("shown.bs.modal", function() {
            $("#txt_name_asearch").trigger("select", "focus");
        });
    });

    $(document).on("keypress", ".txt_field_asearch", function(e) {
        if (e.which == 13) {
            $("#btn_asearch_start").trigger("click");
        }
    });

    $(document).on("click", "#btn_asearch_start", function() {
        var name = $("#txt_name_asearch").val().trim();
        var address = $("#txt_address_asearch").val().trim();
        var phone = $("#txt_phone_asearch").val().trim();
        var status_id = $("#txt_status_asearch").val();

        $(".modal_error, .modal_button").hide();
        $(".modal_waiting").show();

        $.post("<?= base_url(); ?>accounting_invoice_charges/advance_search", {
            name: name,
            address: address,
            phone: phone,
            status_id: status_id
        }, function(data) {

            $(".modal_error, .modal_waiting").hide();
            $(".modal_button").show();

            if (data.indexOf("<!DOCTYPE html>") > -1) {
                alert("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else if (data.indexOf("Error: ") > -1) {
                $("#modal_new .modal_error_msg").text(data);
                $("#modal_new  .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
                $("#txt_name_asearch").trigger("select", "focus");
            } else {
                if (data) {
                    result_data = JSON.parse(data);
                    populate_table("#tbl_list", result_data);
                }

                $("#modal_asearch").modal("hide");
                $("[data-toggle='tooltip']").tooltip({
                    html: true
                });
            }
        });
    });

	$(document).on("click", ".btn_payment", function(){
		let transaction_id = $(this).attr("id");

		if (transaction_id){
			var table = $("#tbl_list").DataTable();
            current_row = table.row($(this).parents("tr"));
            current_data = current_row.data();

			$("#id_update").val(transaction_id);

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
		}else{
			bootbox.alert("Error: Critical Error Encountered!");
		}
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

							//update record in the table
							search_row(transaction_id);
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

								//update record in the table
								search_row(transaction_id);

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

	$(document).on("click", ".btn_complete", function(){
        let transaction_id = $(this).attr("id");

        if (transaction_id){
			var table = $("#tbl_list").DataTable();
			current_row = table.row($(this).parents("tr"));
			current_data = current_row.data();

			bootbox.confirm("Are you sure you want to mark this Invoice as completed?", function(result) {
                if (result) {

					$("#loading").modal();

					$.post("<?= base_url(); ?>accounting_invoice_charges/complete", {transaction_id: transaction_id}, function(data) {
						$("#loading").modal("hide");

						if (data.indexOf("<!DOCTYPE html>") > -1) {
							alert("Error: Session Time-Out, You must login again to continue.");
							location.reload(true);
						}else{
							let result = JSON.parse(data);

							if (result.success == true) {
								current_data[8] = result.data.status;
								current_data[10] = result.data.status_id;
								current_row.data(current_data).invalidate();
								bootbox.alert("Successfully Updated!");
							}else{
								bootbox.alert(result.error);
							}
						}
					});
                }
            });
        }else{
            bootbox.alert("Error: Critical Error Encountered!");
        }
    });


    </script>
</body>

</html>
