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
                                                    <th>NAME</th>
                                                    <th>UOM</th>
                                                    <th>CATEGORY</th>
                                                    <th>NORMAL AMOUNT</th>
                                                    <th>NORMAL AMOUNT PO</th>
                                                    <th>AFTER OFFICE AMT</th>
                                                    <th>AFTER OFFICE AMT PO</th>
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
        $this->load->view('data_product/modal_new');
        $this->load->view('data_product/modal_modify');
        $this->load->view('data_product/modal_info');
        $this->load->view('data_product/modal_asearch');

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
                    {
                        className: "text-center " //code
                    },
                    null, //name
                    {
                        className: "text-center " //uom
                    },
                    {
                        className: "text-center " //category
                    },
                    {
                        className: "text-center " //amount
                    },
                    {
                        className: "text-center " //amount po
                    },
                    {
                        className: "text-center " //after amount
                    },
                    {
                        className: "text-center " //after amount po
                    },
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
                value.name,
                value.uom_code,
                value.category,
                value.amount,
                value.amount_po,
                value.after_amount,
                value.after_amount_po,
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

        $.get("<?= base_url(); ?>data_product/search?search=" + search, function(data) {
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
        var name = $("#txt_name_asearch").val().trim();
        var uom_id = $("#txt_uom_id_asearch").val();
        var category_id = $("#txt_category_id_asearch").val();
        var amount = $("#txt_amount_asearch").val();
        var amount_po = $("#txt_amount_po_asearch").val();
        var after_amount = $("#txt_after_amount_asearch").val();
        var after_amount_po = $("#txt_after_amount_po_asearch").val();
        var status_id = $("#txt_status_asearch").val();

        $(".modal_error, .modal_button").hide();
        $(".modal_waiting").show();

        $.post("<?= base_url(); ?>data_product/advance_search", {
            code: code,
            name: name,
            uom_id: uom_id,
            category_id: category_id,
            amount: amount,
            amount_po: amount_po,
            after_amount: after_amount,
            after_amount_po: after_amount_po,
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
            $("#txt_code").trigger("select", "focus");
        });
    });

    $(document).on("keypress", ".txt_field", function(e) {
        if (e.which == 13) {
            $("#btn_save").trigger("click");
        }
    });

    $(document).on("click", "#btn_save", function() {

        var code = $("#txt_code").val().trim();
        var name = $("#txt_name").val().trim();
        var uom_id = $("#txt_uom_id").val();
        var category_id = $("#txt_category_id").val();
        var amount = $("#txt_amount").val();
        var amount_po = $("#txt_amount_po").val();
        var after_amount = $("#txt_after_amount").val();
        var after_amount_po = $("#txt_after_amount_po").val();

        if (code && name) {
            $(".modal_error, .modal_button").hide();
            $(".modal_waiting").show();

            $.post("<?= base_url(); ?>data_product/add", {
                code: code,
                name: name,
                uom_id: uom_id,
                category_id: category_id,
                amount: amount,
                amount_po: amount_po,
                after_amount: after_amount,
                after_amount_po: after_amount_po
            }, function(data) {

                $('.modal_error, .modal_waiting').hide();
                $('.modal_button').show();

                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    $("#modal_new .modal_error_msg").text(data);
                    $("#modal_new  .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
                    $("#txt_code").trigger("select", "focus");
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

            $.post("<?= base_url(); ?>data_product/search_info_row", {
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
                    $("#txt_code_update").val(data.code);
                    $("#txt_name_update").val(data.name);
                    $("#txt_uom_id_update").val(data.uom_id);
                    $("#txt_category_id_update").val(data.category_id);
                    $("#txt_amount_update").val(data.amount);
                    $("#txt_amount_po_update").val(data.amount_po);
                    $("#txt_after_amount_update").val(data.after_amount);
                    $("#txt_after_amount_po_update").val(data.after_amount_po);

                    $("#modal_modify").modal();

                    $("#modal_modify").on("shown.bs.modal", function() {
                        $("#txt_code_update").trigger("select", "focus");
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
        var code = $("#txt_code_update").val();
        var name = $("#txt_name_update").val();
        var uom_id = $("#txt_uom_id_update").val();
        var uom = $("#txt_uom_id_update option:selected").text();
        var category_id = $("#txt_category_id_update").val();
        var category = $("#txt_category_id_update option:selected").text();
        var amount = $("#txt_amount_update").val();
        var amount_po = $("#txt_amount_po_update").val();
        var after_amount = $("#txt_after_amount_update").val();
        var after_amount_po = $("#txt_after_amount_po_update").val();

        if (id) {
            if (code && name) {
                $(".modal_error, .modal_button").hide();
                $(".modal_waiting").show();

                $.post("<?= base_url(); ?>data_product/update", {
                    id: id,
                    code: code,
                    name: name,
                    uom_id: uom_id,
                    category_id: category_id,
                    amount: amount,
                    amount_po: amount_po,
                    after_amount: after_amount,
                    after_amount_po: after_amount_po
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
                        $("#txt_code_update").trigger("select", "focus");
                    } else {
                        current_data[1] = code;
                        current_data[2] = name;
                        current_data[3] = uom;
                        current_data[4] = category;
                        current_data[5] = amount;
                        current_data[6] = amount_po;
                        current_data[7] = after_amount;
                        current_data[8] = after_amount_po;
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
                $("#txt_code_update").trigger("select", "focus");
            }
        } else {
            $("#modal_modify .modal_error_msg").text("Error: Critical Error Encountered!");
            $("#modal_modify .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
            $("#txt_code_update").trigger("select", "focus");
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

            $.post("<?= base_url(); ?>data_product/search_info_row", {
                id: id
            }, function(data) {

                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    bootbox.alert(data);
                } else {
                    data = JSON.parse(data);

                    $("#txt_code_info").val(data.code);
                    $("#txt_name_info").val(data.name);
                    $("#txt_uom_id_info").val(data.uom_id);
                    $("#txt_category_id_info").val(data.category_id);
                    $("#txt_amount_info").val(data.amount);
                    $("#txt_amount_po_info").val(data.amount_po);
                    $("#txt_after_amount_info").val(data.after_amount);
                    $("#txt_after_amount_po_info").val(data.after_amount_po);
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
                            $.post("<?= base_url(); ?>data_product/delete", {
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

                    $.post("<?= base_url(); ?>data_product/activate", {
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
