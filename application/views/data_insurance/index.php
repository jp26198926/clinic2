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
                                            <button id='btn_search' class="btn btn-sm btn-primary" type="button" title='Search' data-toggle='tooltip'>
                                                <i class="ace-icon fa fa-search bigger-110"></i>
                                                Go!
                                            </button>

                                            <button id='btn_asearch' class="btn btn-sm btn-info" type="button" title='Advance Search' data-toggle='tooltip'>
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
                                        <table id="tbl_list" class="table  table-bordered table-hover table-striped table-fixed-header">
                                            <thead class="header">
                                                <tr>
                                                    <th>OPTION</th>
                                                    <th>NAME</th>
                                                    <th>VALUE TYPE</th>
                                                    <th>VALUE</th>
                                                    <th>COMMISSION TYPE</th>
                                                    <th>COMMISSION VALUE</th>
                                                    <th>STATUS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td align='center' colspan='7'>Use search button to display record
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
        $this->load->view('data_insurance/modal_new');
        $this->load->view('data_insurance/modal_modify');
        $this->load->view('data_insurance/modal_info');
        $this->load->view('data_insurance/modal_asearch');

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
                        null, //name
                        null, //value type
                        null, //value
                        null, //commission type
                        null, //commission value
                        null //status
                    ],
                    data: dataSet,
                    "aaSorting": [],
                    "rowCallback": function(row, data, index) {
                        if (data[8] == 1) { //deleted
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
                            columns: [1, 2, 3, 4, 5, 6] //0, 1, 2, 3
                        }
                    },
                    {
                        "extend": "csv",
                        "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to CSV</span>",
                        "className": "btn btn-white btn-primary btn-bold",
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6] //0, 1, 2, 3
                        }
                    },
                    {
                        "extend": "excel",
                        "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
                        "className": "btn btn-white btn-primary btn-bold",
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6] //0, 1, 2, 3
                        }
                    },
                    {
                        "extend": "pdf",
                        "text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
                        "className": "btn btn-white btn-primary btn-bold",
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6] //0, 1, 2, 3
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
                            columns: [1, 2, 3, 4, 5, 6] //0, 1, 2, 3
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
                    value.name,
                    value.value_type,
                    Number(value.value),
                    value.commission_type,
                    Number(value.commission_value),
                    status, id,
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

            $.get("<?= base_url(); ?>data_insurance/search?search=" + search, function(data) {
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
            var value_type_id = $("#txt_value_type_id").val();
            var value = $("#txt_value").val();
            var commission_type_id = $("#txt_commission_type_id").val();
            var commission_value = $("#txt_commission_value").val();
            var status_id = $("#txt_status_asearch").val();

            $(".modal_error, .modal_button").hide();
            $(".modal_waiting").show();

            $.post("<?= base_url(); ?>data_insurance/advance_search", {
                name: name,
                value_type_id: value_type_id,
                value: value,
                commission_type_id: commission_type_id,
                commission_value: commission_value,
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

        //add
        $(document).on("click", "#btn_new", function() {
            $('.txt_field').val('');
            $('.modal_error, .modal_waiting').hide();
            $('#modal_new').modal();

            $('#modal_new').on('shown.bs.modal', function() {
                $('#txt_name').trigger('select', 'focus');
            });
        });

        $(document).on("keypress", ".txt_field", function(e) {
            if (e.which == 13) {
                $("#btn_save").trigger("click");
            }
        });

        $(document).on("click", "#btn_save", function() {

            var name = $("#txt_name").val().trim();
            var value_type_id = $("#txt_value_type_id").val();
            var value = $("#txt_value").val();
            var commission_type_id = $("#txt_commission_type_id").val();
            var commission_value = $("#txt_commission_value").val();

            if (name) {
                $(".modal_error, .modal_button").hide();
                $(".modal_waiting").show();

                $.post("<?= base_url(); ?>data_insurance/add", {
                    name: name,
                    value_type_id: value_type_id,
                    value: value,
                    commission_type_id: commission_type_id,
                    commission_value: commission_value
                }, function(data) {

                    $('.modal_error, .modal_waiting').hide();
                    $('.modal_button').show();

                    if (data.indexOf("<!DOCTYPE html>") > -1) {
                        alert("Error: Session Time-Out, You must login again to continue.");
                        location.reload(true);
                    } else if (data.indexOf("Error: ") > -1) {
                        $("#modal_new .modal_error_msg").text(data);
                        $("#modal_new  .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
                        $("#txt_name").trigger("select", "focus");
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

                $.post("<?= base_url(); ?>data_insurance/search_info_row", {
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
                        $("#txt_name_update").val(data.name);
                        $("#txt_value_type_id_update").val(data.value_type_id);
                        $("#txt_value_update").val(Number(data.value));
                        $("#txt_commission_type_id_update").val(data.commission_type_id);
                        $("#txt_commission_value_update").val(Number(data.commission_value));

                        $("#modal_modify").modal();

                        $("#modal_modify").on("shown.bs.modal", function() {
                            $("#txt_name_update").trigger("select", "focus");
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
            var name = $("#txt_name_update").val();
            var value_type_id = $("#txt_value_type_id_update").val();
            var value_type = $("#txt_value_type_id_update option:selected").text();
            var value = $("#txt_value_update").val();
            var commission_type_id = $("#txt_commission_type_id_update").val();
            var commission_type = $("#txt_commission_type_id_update option:selected").text();
            var commission_value = $("#txt_commission_value_update").val();

            if (id) {
                if (name) {
                    $(".modal_error, .modal_button").hide();
                    $(".modal_waiting").show();

                    $.post("<?= base_url(); ?>data_insurance/update", {
                        id: id,
                        name: name,
                        value_type_id: value_type_id,
                        value: value,
                        commission_type_id: commission_type_id,
                        commission_value: commission_value
                    }, function(data) {

                        $(".modal_error, .modal_waiting").hide();
                        $(".modal_button").show();

                        if (data.indexOf("<!DOCTYPE html>") > -1) {
                            alert("Error: Session Time-Out, You must login again to continue.");
                            location.reload(true);
                        } else if (data.indexOf("Error: ") > -1) {
                            $("#modal_modify .modal_error_msg").text(data);
                            $("#modal_modify  .modal_error").stop(true, true).show().delay(15000).fadeOut(
                                "slow");
                            $("#txt_name_update").trigger("select", "focus");
                        } else {
                            current_data[1] = name;
                            current_data[2] = value_type;
                            current_data[3] = value;
                            current_data[4] = commission_type;
                            current_data[5] = commission_value;
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
                    $("#txt_name_update").trigger("select", "focus");
                }
            } else {
                $("#modal_modify .modal_error_msg").text("Error: Critical Error Encountered!");
                $("#modal_modify .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
                $("#txt_name_update").trigger("select", "focus");
            }
        });

        //info
        $(document).on("click", ".btn_info", function(e) {
            e.preventDefault();

            var id = $(this).attr("id");

            if (id) {
                var table = $("#tbl_list").DataTable();
                current_row = table.row($(this).parents("tr"));
                current_data = current_row.data();

                $.post("<?= base_url(); ?>data_insurance/search_info_row", {
                    id: id
                }, function(data) {

                    if (data.indexOf("<!DOCTYPE html>") > -1) {
                        alert("Error: Session Time-Out, You must login again to continue.");
                        location.reload(true);
                    } else if (data.indexOf("Error: ") > -1) {
                        bootbox.alert(data);
                    } else {
                        data = JSON.parse(data);

                        $("#txt_name_info").val(data.name);
                        $("#txt_value_type_id_info").val(data.value_type_id);
                        $("#txt_value_info").val(Number(data.value));
                        $("#txt_commission_type_id_info").val(data.commission_type_id);
                        $("#txt_commission_value_info").val(Number(data.commission_value));
                        $("#txt_status_info").val(data.status_id);

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
                                $.post("<?= base_url(); ?>data_insurance/delete", {
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
                                            current_data[6] = table_status(
                                                data.status_id,
                                                data.status,
                                                data.created_at,
                                                data.created,
                                                data.deleted_at,
                                                data.deleted,
                                                data.deleted_reason
                                            );
                                            current_data[8] = data.status_id;
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

                        $.post("<?= base_url(); ?>data_insurance/activate", {
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
                                    current_data[6] = table_status(
                                        data.status_id,
                                        data.status,
                                        data.created_at,
                                        data.created,
                                        data.deleted_at,
                                        data.deleted,
                                        data.deleted_reason
                                    );
                                    current_data[8] = data.status_id;
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
