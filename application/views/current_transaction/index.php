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
                                        <input type="search" id="txt_search" class="form-control"
                                            value="<?= $search; ?>" />
                                        <span class="input-group-btn">
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
												?>

                                                <button id='btn_new' class='btn btn-sm btn-success' type='button'
                                                    title='New' data-toggle='tooltip'>
                                                    <i class='ace-icon fa fa-plus bigger-110'></i>
                                                </button>

                                                <?php
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
                                                    <th>TRANS NO</th>
                                                    <th>DATE</th>
                                                    <th>TYPE</th>
                                                    <th>PATIENT</th>
                                                    <th>COMPANY</th>
                                                    <th>PAY METHOD</th>
                                                    <th>LOCATION</th>
                                                    <th>DOCTOR</th>
                                                    <th>QUEUE</th>
                                                    <th>STATUS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td align='center' colspan='11'>
                                                        Use search button to display record
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
		$this->load->view("current_transaction/modal_asearch");

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
    var current_row;
    var current_data;
    var current_status_id;
    //functions

    function table_buttons(id, status_id = 1) {
        var option = ``;
        option +=
            `<a href='<?= base_url($module); ?>/view/${id}' class='btn btn-xs btn-info text-primary fa fa-search' title = 'Show Info' data-toggle='tooltip'></a>`;

        return option;
    }

    function table_status(
        status_id, status,
        created_at, created_by,
        deleted_at, deleted_by,
        deleted_reason
    ) {
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
                        className: "text-center" //TRANS NO
                    },
                    {
                        className: "text-center" //DATE
                    },
                    {
                        className: "text-center" //TYPE
                    },
                    {
                        className: "text-center" //PATIENT
                    },
                    {
                        className: "text-center" //CLIENT
                    },
                    {
                        className: "text-center" //PAY METHOD
                    },
                    {
                        className: "text-center" //LOCATION
                    },
                    {
                        className: "text-center" //DOCTOR
                    },
                    {
                        className: "text-center" //QUEUE
                    },
                    {
                        className: "text-center" //STATUS
                    }
                ],
                data: dataSet,
                "aaSorting": [],
                "rowCallback": function(row, data, index) {
                    if (data[12] == 1) { //deleted
                        $('td', row).removeClass('info');
                        $('td', row).removeClass('success');
                        $('td', row).addClass('danger');
                    } else if (data[12] == 3) { //confirm
                        $('td', row).addClass('info');
                        $('td', row).removeClass('danger');
                        $('td', row).removeClass('success');
                    } else if (data[12] == 4) { //completed
                        $('td', row).removeClass('info');
                        $('td', row).removeClass('danger');
                        $('td', row).addClass('success');
                    } else {
                        $('td', row).removeClass('danger');
                        $('td', row).removeClass('success');
                        $('td', row).removeClass('info');
                    }
                }

            });

        $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

        new $.fn.dataTable.Buttons(myTable, {
            buttons: [{
                    "extend": "copy",
                    "text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    "title": "Copy",
                    "data-toggle": "tooltip",
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10] //0, 1, 2, 3
                    }
                },
                {
                    "extend": "csv",
                    "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to CSV</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10] //0, 1, 2, 3
                    }
                },
                {
                    "extend": "excel",
                    "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10] //0, 1, 2, 3
                    }
                },
                {
                    "extend": "pdf",
                    "text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10] //0, 1, 2, 3
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
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10] //0, 1, 2, 3
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



            dataSet.push(
                [
                    option,
                    value.transaction_no,
                    value.date,
                    value.trans_type,
                    value.patient,
                    value.client, //company
                    value.payment_method,
                    value.location,
                    value.doctor,
                    value.queue,
                    status,
                    id,
                    status_id
                ]
            );
        });

        $("#lbl_count").text(result_count);
        reload_table(table_ref, dataSet);
    }

    $(document).ready(function() {
        reload_table("#tbl_list");

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

        var svr = "<?= base_url($module); ?>/search_active";

        if (search) {
            svr = "<?= base_url($module); ?>/search?search=" + search;
        }

        $.get(svr, function(data) {
            $("#loading").modal("hide");

            if (data.indexOf("<!DOCTYPE html>") > -1) {
                alert("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else if (data.indexOf("Error: ") > -1) {
                bootbox.alert(data);
                $("#txt_search").trigger("focus");
            } else {
                if (data) {
                    var result_data = JSON.parse(data);
                    populate_table("#tbl_list", result_data);
                }

                $("[data-toggle='tooltip']").tooltip({
                    html: true
                });
            }
        });
    });

    //advance search
    $(document).on("click", "#btn_asearch", function(e) {
        //$(".field_asearch").val("");
        $("#modal_asearch").modal();

        $("#modal_asearch").on("shown.bs.modal", function() {
            $("#name_asearch").trigger("focus");
        });
    });

    $(document).on("click", "#btn_asearch_start", function(e) {
        $(".modal_error, .modal_button, .modal-body").hide();
        $(".modal_waiting").show();

        var formData = new FormData($("#frm_asearch")[0]);

        $.ajax({
            type: "POST",
            url: "<?= base_url($module); ?>/advance_search",
            data: formData,
            enctype: "multipart/form-data",
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            dataType: "json",
            //encode: true,
        }).done(function(data) {

            $('.modal_error, .modal_waiting').hide();
            $('.modal_button, .modal-body').show();

            if (!data.success) {
                $("#modal_asearch .modal_error_msg").text(data.errors.error);
                $("#modal_asearch  .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
                $("#name").trigger("select", "focus");
            } else {
                //no error
                populate_table("#tbl_list", data.result);

                $("#modal_asearch").modal("hide");
                $("[data-toggle='tooltip']").tooltip({
                    html: true
                });
            }
        }).fail(function(data) {
            alert("Error: Server Error or Session Time-Out!, Please try again or reload the page!");
            $('.modal_error, .modal_waiting').hide();
            $('.modal_button, .modal-body').show();
        });

        e.preventDefault();
    });

    //add
    $(document).on("click", "#btn_new", function() {
        $("#loading").modal();
        window.location = "<?= base_url($module); ?>/new"
    });
    </script>
</body>

</html>
