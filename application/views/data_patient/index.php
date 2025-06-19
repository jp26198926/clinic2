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

                                            <button id='btn_asearch' class="btn btn-sm btn-info" type="button"
                                                title='Advance Search' data-toggle='tooltip'>
                                                <i class="ace-icon fa fa-search-plus bigger-110"></i>
                                            </button>

                                            <?php
											if ($role_id == 1 || $this->custom_function->module_permission("add", $module_permission)) { //admin or has add permission
												echo "	<button id='btn_new' class='btn btn-sm btn-success' type='button' title='New' data-toggle='tooltip'>
																	<i class='ace-icon fa fa-plus bigger-110'></i>
																</button>";
											}
											?>

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
                                                    <th>CODE</th>
                                                    <th>LASTNAME</th>
                                                    <th>FIRSTNAME</th>
                                                    <th>MIDDLENAME</th>
                                                    <th>GENDER</th>
                                                    <th>CIVIL STATUS</th>
                                                    <th>CONTACT NO.</th>
                                                    <th>ADDRESS</th>
                                                    <th>STATUS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td align='center' colspan='10'>Use search button to display record
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
		$this->load->view('data_patient/modal_new');
		$this->load->view('data_patient/modal_modify');
		$this->load->view('data_patient/modal_info');
		$this->load->view('data_patient/modal_asearch');

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
        option += "<a href='#' id='" + id +
            "' class='btn_info btn btn-xs btn-info text-primary fa fa-search' title='Show Info' data-toggle='tooltip'></a> ";
        $active_inactive = "";

        status_id = parseInt(status_id);

        <?php
			if ($role_id == 1) { //administrator
			?>
        option += "<a href='#' id='" + id +
            "' class='btn_modify btn btn-xs btn-primary fa fa-pencil  ' title='Modify'   data-toggle='tooltip'></a> ";
        option += "<a href='#' id='" + id +
            "' class='btn_activate btn btn-xs btn-warning fa fa-reply  ' title='Activate'   data-toggle='tooltip'></a> ";
        option += "<a href='#' id='" + id +
            "' class='btn_delete btn btn-xs btn-danger fa fa-times-circle  ' title='Delete'   data-toggle='tooltip'></a> ";

        <?php
			} else {
			?>

        switch (status_id) {
            case 1: //deleted
                <?php
						if ($role_id == 1 || $this->custom_function->module_permission("activate", $module_permission)) { //if admin or has permission
						?>
                option += "<a href='#' id='" + id +
                    "' class='btn_activate btn btn-xs btn-warning fa fa-reply  ' title='Activate'   data-toggle='tooltip'></a> ";
                <?php
						} ?>
                break;

            case 2: //active

                <?php
						if ($role_id == 1 || $this->custom_function->module_permission("modify", $module_permission)) { //if admin or has permission
						?>
                option += "<a href='#' id='" + id +
                    "' class='btn_modify btn btn-xs btn-primary fa fa-pencil  ' title='Modify'   data-toggle='tooltip'></a> ";
                <?php
						} ?>

                <?php
						if ($role_id == 1 || $this->custom_function->module_permission("delete", $module_permission)) { //if admin or has permission
						?>
                option += "<a href='#' id='" + id +
                    "' class='btn_delete btn btn-xs btn-danger fa fa-times-circle  ' title='Delete'   data-toggle='tooltip'></a> ";
                <?php
						} ?>


                break;

            case 3: //locked
                break;
        }

        <?php
			}
			?>

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
                    null, //code
                    null, //lastname
                    null, //firstname
                    null, //middlename
                    null, //gender
                    null, //civil status
                    null, //contact no
                    null, //address
                    null //status
                ],
                data: dataSet,
                "aaSorting": [],
                "rowCallback": function(row, data, index) {
                    if (data[11] == 1) { //deleted
                        $('td', row).addClass('danger');
                    } else {
                        $('td', row).removeClass('danger');
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
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9] //0, 1, 2, 3
                    }
                },
                {
                    "extend": "csv",
                    "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to CSV</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9] //0, 1, 2, 3
                    }
                },
                {
                    "extend": "excel",
                    "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9] //0, 1, 2, 3
                    }
                },
                {
                    "extend": "pdf",
                    "text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9] //0, 1, 2, 3
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
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9] //0, 1, 2, 3
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
                value.code,
                value.lastname,
                value.firstname,
                value.middlename,
                value.gender,
                value.civil_status,
                value.contact_no,
                value.address,
                status,
                id,
                status_id
            ]);
        });

        $("#lbl_count").text(result_count);
        reload_table(table_ref, dataSet);
    }

    $(document).ready(function() {
        reload_table("#tbl_list");

        $("#txt_search").val("<?= isset($_GET['search']) ? ($_GET['search']) : '' ?>");
        $("#btn_search").trigger("click");
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

        $.get("<?= base_url(); ?>data_patient/search?search=" + search, function(data) {
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
        var code = $("#txt_code_asearch").val().trim();
        var lastname = $("#txt_lastname_asearch").val().trim();
        var firstname = $("#txt_firstname_asearch").val().trim();
        var middlename = $("#txt_middlename_asearch").val().trim();
        var gender_id = $("#txt_gender_id_asearch").val();
        var civil_id = $("#txt_civil_id_asearch").val();
        var contact_no = $("#txt_contact_no_asearch").val().trim();
        var address = $("#txt_address_asearch").val().trim();
        var status_id = $("#txt_status_asearch").val();

        $(".modal_error, .modal_button").hide();
        $(".modal_waiting").show();

        $.post("<?= base_url(); ?>data_patient/advance_search", {
            code: code,
            lastname: lastname,
            firstname: firstname,
            middlename: middlename,
            gender_id: gender_id,
            civil_id: civil_id,
            contact_no: contact_no,
            address: address,
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
                $("#txt_code_asearch").trigger("select", "focus");
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

    //add
    $(document).on("click", "#btn_new", function() {
        $('.txt_field').val('');
        $('.modal_error, .modal_waiting').hide();
        $('#modal_new').modal();

        $('#modal_new').on('shown.bs.modal', function() {
            $('#txt_lastname').trigger('select', 'focus');
        });
    });

    $(document).on("keypress", ".txt_field", function(e) {
        if (e.which == 13) {
            $("#btn_save").trigger("click");
        }
    });

    $(document).on("click", "#btn_save", function() {

        var lastname = $("#txt_lastname").val();
        var firstname = $("#txt_firstname").val();
        var middlename = $("#txt_middlename").val();
        var gender_id = $("#txt_gender_id").val();
        var civil_id = $("#txt_civil_id").val();
        var contact_no = $("#txt_contact_no").val();
        var address = $("#txt_address").val();

        if (lastname && firstname) {
            $(".modal_error, .modal_button").hide();
            $(".modal_waiting").show();

            $.post("<?= base_url(); ?>data_patient/add", {
                lastname: lastname,
                firstname: firstname,
                middlename: middlename,
                gender_id: gender_id,
                civil_id: civil_id,
                contact_no: contact_no,
                address: address
            }, function(data) {

                $('#modal_new .modal_error, #modal_new .modal_waiting').hide();
                $('#modal_new .modal_button').show();

                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    $("#modal_new .modal_error_msg").text(data);
                    $("#modal_new  .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
                    $("#txt_lastname").trigger("select", "focus");
                } else {
                    if (data) {
                        result_data = JSON.parse(data);
                        populate_table("#tbl_list", result_data);
                    }

                    $("#modal_new").modal("hide");
                    $("[data-toggle='tooltip']").tooltip({
                        html: true
                    });
                }
            });

        } else {
            $("#modal_new .modal_error_msg").text("Error: Fields with * are required!");
            $("#modal_new .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
        }
    });

    //update
    $(document).on("click", ".btn_modify", function(e) {
        e.preventDefault();

        var id = $(this).attr("id");

        if (id) {
            var table = $("#tbl_list").DataTable();
            current_row = table.row($(this).parents("tr"));
            current_data = current_row.data();

            $.post("<?= base_url(); ?>data_patient/search_info_row", {
                id: id
            }, function(data) {

                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    bootbox.alert(data);
                } else {
                    data = JSON.parse(data);

                    $(".hidden_id").val(data.id);
                    $("#txt_lastname_update").val(data.lastname);
                    $("#txt_firstname_update").val(data.firstname);
                    $("#txt_middlename_update").val(data.middlename);
                    $("#txt_gender_id_update").val(data.gender_id);
                    $("#txt_civil_id_update").val(data.civil_id);
                    $("#txt_contact_no_update").val(data.contact_no);
                    $("#txt_address_update").val(data.address);

                    $("#modal_modify").modal();

                    $("#modal_modify").on("shown.bs.modal", function() {
                        $("#txt_lastname_update").trigger("select", "focus");
                    });
                }
            });
        } else {
            bootbox.alert("Error: Critical Error Encountered!");
        }
    });

    $(document).on("keypress", ".txt_field_update", function(e) {
        if (e.which == 13) {
            $("#btn_update").trigger("click");
        }
    });

    $(document).on("click", "#btn_update", function() {
        var id = $(".hidden_id").val();
        var lastname = $("#txt_lastname_update").val();
        var firstname = $("#txt_firstname_update").val();
        var middlename = $("#txt_middlename_update").val();
        var gender_id = $("#txt_gender_id_update").val();
        var gender = $("#txt_gender_id_update option:selected").text();
        var civil_id = $("#txt_civil_id_update").val();
        var civil = $("#txt_civil_id_update option:selected").text();
        var contact_no = $("#txt_contact_no_update").val();
        var address = $("#txt_address_update").val();

        if (id) {
            if (lastname && firstname) {
                $("#modal_modify .modal_error, #modal_modify .modal_button").hide();
                $("#modal_modify .modal_waiting").show();

                $.post("<?= base_url(); ?>data_patient/update", {
                    id: id,
                    lastname: lastname,
                    firstname: firstname,
                    middlename: middlename,
                    gender_id: gender_id,
                    civil_id: civil_id,
                    contact_no: contact_no,
                    address: address
                }, function(data) {

                    $("#modal_modify .modal_error, #modal_modify .modal_waiting").hide();
                    $("#modal_modify .modal_button").show();

                    if (data.indexOf("<!DOCTYPE html>") > -1) {
                        alert("Error: Session Time-Out, You must login again to continue.");
                        location.reload(true);
                    } else if (data.indexOf("Error: ") > -1) {
                        $("#modal_modify .modal_error_msg").text(data);
                        $("#modal_modify  .modal_error").stop(true, true).show().delay(15000).fadeOut(
                            "slow");
                        $("#txt_lastname_update").trigger("select", "focus");
                    } else {
                        //current_data[1] = code
                        current_data[2] = lastname;
                        current_data[3] = firstname;
                        current_data[4] = middlename;
                        current_data[5] = gender;
                        current_data[6] = civil;
                        current_data[7] = contact_no;
                        current_data[8] = address;
                        current_row.data(current_data).invalidate();

                        $("#modal_modify").modal("hide");
                        $("[data-toggle='tooltip']").tooltip({
                            html: true
                        });
                    }
                });

            } else {
                $("#modal_modify .modal_error_msg").text("Error: Fields with * are required!");
                $("#modal_modify .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
                $("#txt_lastname_update").trigger("select", "focus");
            }
        } else {
            $("#modal_modify .modal_error_msg").text("Error: Critical Error Encountered!");
            $("#modal_modify .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
            $("#txt_lastname_update").trigger("select", "focus");
        }
    });

    // Function to load patient transaction history
    function loadPatientHistory(patient_id) {
        // Show loading message
        $("#tbl_patient_history tbody").html('<tr><td colspan="7" align="center">Loading transaction history...</td></tr>');

        $.post("<?= base_url(); ?>data_patient/patient_history", {
            patient_id: patient_id
        }, function(data) {
            if (data.indexOf("<!DOCTYPE html>") > -1) {
                alert("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else {
                let result = JSON.parse(data);
                let tbl = "";

                if (result.success === true) {
                    if (result.records && result.records.length > 0) {
                        $.each(result.records, function(i, record) {
                            tbl += `<tr>
                                        <td align='center'>${record.transaction_no}</td>
                                        <td align='center'>${record.date}</td>
                                        <td align='center'>${record.trans_type}</td>
                                        <td align='center'>${record.doctor == null ? "" : record.doctor}</td>
                                        <td>${record.diagnosis}</td>
                                        <td align='center'>${record.status}</td>
                                        <td align='center'>
                                            <a href='<?= base_url(); ?>current_transaction/view/${record.transaction_id}' target='_blank' class='btn btn-xs btn-info fa fa-forward' title='Show Transaction'></a>
                                        </td>
                                    </tr>`;
                        });
                    } else {
                        tbl = "<tr><td colspan='7' align='center'>No transaction history found</td></tr>";
                    }
                } else {
                    tbl = "<tr><td colspan='7' align='center'>Error loading history: " + (result.error || "Unknown error") + "</td></tr>";
                }

                $("#tbl_patient_history tbody").html(tbl);
            }
        }).fail(function() {
            $("#tbl_patient_history tbody").html('<tr><td colspan="7" align="center">Error loading transaction history</td></tr>');
        });
    }

    //info
    $(document).on("click", ".btn_info", function(e) {
        e.preventDefault();

        var id = $(this).attr("id");

        if (id) {
            var table = $("#tbl_list").DataTable();
            current_row = table.row($(this).parents("tr"));
            current_data = current_row.data();

            $.post("<?= base_url(); ?>data_patient/search_info_row", {
                id: id
            }, function(data) {

                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    bootbox.alert(data);
                } else {
                    data = JSON.parse(data);

                    $("#txt_lastname_info").val(data.lastname);
                    $("#txt_firstname_info").val(data.firstname);
                    $("#txt_middlename_info").val(data.middlename);
                    $("#txt_gender_id_info").val(data.gender_id);
                    $("#txt_civil_id_info").val(data.civil_id);
                    $("#txt_contact_no_info").val(data.contact_no);
                    $("#txt_address_info").val(data.address);
                    $("#txt_status_info").val(data.status_id);

                    // Load patient transaction history
                    loadPatientHistory(id);

                    $("#modal_info").modal();
                }
            });
        } else {
            bootbox.alert("Error: Critical Error Encountered!");
        }
    });

    //delete
    $(document).on("click", ".btn_delete", function() {
        var id = $(this).attr('id');

        if (id) {
            var table = $('#tbl_list').DataTable();
            current_row = table.row($(this).parents('tr'));
            current_data = current_row.data();

            bootbox.prompt({
                title: "Provide Delete Reason!",
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
                            $.post("<?= base_url(); ?>data_patient/delete", {
                                id: id,
                                reason: result
                            }, function(data) {
                                if (data.indexOf("<!DOCTYPE html>") > -1) {
                                    alert(
                                        "Error: Session Time-Out, You must login again to continue."
                                    );
                                    location.reload(true);
                                } else if (data.indexOf("Error: ") > -1) {
                                    bootbox.alert(data);
                                } else {
                                    if (data) {

                                        data = JSON.parse(data);

                                        current_data[0] = table_buttons(id, data.status_id);
                                        current_data[9] = table_status(
                                            data.status_id,
                                            data.status,
                                            data.created_at,
                                            data.created,
                                            data.deleted_at,
                                            data.deleted,
                                            data.deleted_reason
                                        );
                                        current_data[11] = data.status_id;
                                        current_row.data(current_data).invalidate().draw();

                                        $('[data-toggle="tooltip"]').tooltip({
                                            html: true
                                        });
                                    }
                                }
                            });
                        } else {
                            return false;
                        }
                    }

                }
            });

        } else {
            bootbox.alert("Error: Critical Error Encountered!");
        }

    });

    $(document).on('click', '.btn_activate', function(e) {
        e.preventDefault();

        var id = $(this).attr('id');

        if (id) {
            var table = $('#tbl_list').DataTable();
            current_row = table.row($(this).parents('tr'));
            current_data = current_row.data();

            bootbox.confirm("Are you sure you want to ACTIVATE this data?", function(result) {
                if (result) {

                    $.post("<?= base_url(); ?>data_patient/activate", {
                        id: id
                    }, function(data) {

                        if (data.indexOf("<!DOCTYPE html>") > -1) {
                            alert("Error: Session Time-Out, You must login again to continue.");
                            location.reload(true);
                        } else if (data.indexOf("Error: ") > -1) {
                            bootbox.alert(data);
                        } else {
                            if (data) {

                                data = JSON.parse(data);

                                current_data[0] = table_buttons(id, data.status_id);
                                current_data[9] = table_status(
                                    data.status_id,
                                    data.status,
                                    data.created_at,
                                    data.created,
                                    data.deleted_at,
                                    data.deleted,
                                    data.deleted_reason
                                );
                                current_data[11] = data.status_id;
                                current_row.data(current_data).invalidate().draw();

                                $('[data-toggle="tooltip"]').tooltip({
                                    html: true
                                });
                            }
                        }
                    });
                }
            });
        } else {
            alert("Error: Critical Error Encountered!");
        }
    });
    </script>
</body>

</html>
