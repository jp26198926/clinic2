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

                                    <div class="pull-right" style="display: flex; align-items: center;">
                                        <div class="input-group" style="margin-right: 5px;">
                                            <input type="text" placeholder="Search ..." class="form-control" id="txt_search" autocomplete="off" style="width: 200px;" />
                                            <span class="input-group-btn">
                                                <button id='btn_search' class='btn btn-sm btn-primary' type='button' title='Search' data-toggle='tooltip'>
                                                    <i class="ace-icon fa fa-search bigger-110"></i> Go!
                                                </button>
                                                <button id='btn_asearch' class='btn btn-sm btn-info' type='button' title='Advanced Search' data-toggle='tooltip'>
                                                    <i class="ace-icon fa fa-search-plus bigger-110"></i>
                                                </button>

												<?php
												if ($role_id == 1 || $this->custom_function->module_permission("add", $module_permission)) { //admin or has add permission
													echo "<button id='btn_header_receive_stock' class='btn btn-sm btn-success' type='button' title='Receive Stock' data-toggle='tooltip' >
																	<i class='ace-icon fa fa-plus-circle bigger-110'></i>
																</button>";
													echo "<button id='btn_header_release_stock' class='btn btn-sm btn-danger' type='button' title='Release Stock' data-toggle='tooltip' >
																	<i class='ace-icon fa fa-minus-circle bigger-110'></i>
																</button>";
													echo "<button id='btn_stock_adjustment' class='btn btn-sm btn-warning' type='button' title='Stock Adjustment' data-toggle='tooltip' >
																	<i class='ace-icon fa fa-cubes bigger-110'></i>
																</button>";
													echo "<button id='btn_stock_transfer' class='btn btn-sm btn-info' type='button' title='Stock Transfer' data-toggle='tooltip' >
																	<i class='ace-icon fa fa-exchange bigger-110'></i>
																</button>";
												}
												?>
												<button id='btn_low_stock' class='btn btn-sm btn-danger' type='button' title='Low Stock Report' data-toggle='tooltip' >
													<i class="ace-icon fa fa-warning bigger-110"></i>
												</button>
                                            </span>
                                        </div>
                                        
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
                                                    <th>PRODUCT</th>
                                                    <th>LOCATION</th>
                                                    <th>ON HAND</th>
                                                    <th>RESERVED</th>
                                                    <th>AVAILABLE</th>
                                                    <th>UOM</th>
                                                    <th>LAST UPDATED</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td align='center' colspan='8'>Use search button to display record
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
        </div><!-- /.main-content -->        <?php
        $this->load->view('inventory_stock/modal_stock_adjustment');
        $this->load->view('inventory_stock/modal_stock_transfer');
        $this->load->view('inventory_stock/modal_info');
        $this->load->view('inventory_stock/modal_asearch');
        $this->load->view('inventory_stock/modal_movements');
        $this->load->view('inventory_stock/modal_low_stock');
        $this->load->view('inventory_stock/modal_manage_reservation'); // Added this line
        $this->load->view('inventory_stock/modal_receive_stock'); ?>
<?php $this->load->view('inventory_stock/modal_release_stock'); ?>

        <?php
        $this->load->view('template/footer');
        $this->load->view('template/loading');
        ?>

    </div><!-- /.main-container -->

    <!-- basic scripts -->
    <?php $this->load->view('template/script'); ?>

    <script>
    const base_url = "<?= base_url(); ?>";
    let current_data;
    let current_row;
    let result_data;

    // Function to initialize or reload the main DataTable
    const initialize_or_reload_table = (table_id, dataSet = []) => {
        var myTable = $(table_id).DataTable({
            destroy: true, // Destroy existing instance before reinitializing
            bAutoWidth: false,
            "aoColumns": [
                { "bSortable": false, className: "text-center" }, // OPTION
                null,                                             // PRODUCT
                null,                                             // LOCATION
                { className: "text-right" },                      // ON HAND
                { className: "text-right" },                      // RESERVED
                { className: "text-right" },                      // AVAILABLE
                { className: "text-center" },                     // UOM
                { className: "text-center" }                      // LAST UPDATED
            ],
            "aaSorting": [],
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true, // Enables DataTable default search. Custom search via #txt_search still works.
            "bInfo": true,
            "scrollY": "400px",
            "scrollCollapse": true,
            "columnDefs": [{
                "targets": [0],
                "orderable": false
            }],
            data: dataSet // Pass the data directly
        });

        // Re-initialize buttons
        $('.tableTools-container').empty(); // Clear previous buttons to prevent duplication
        $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
        new $.fn.dataTable.Buttons(myTable, {
            buttons: [
                {
                    "extend": "copy",
                    "text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    "title": "Copy Inventory Stock",
                    "exportOptions": {
                        columns: [1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    "extend": "csv",
                    "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to CSV</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    "title": "InventoryStockExport",
                    "exportOptions": {
                        columns: [1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    "extend": "excel",
                    "text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    "title": "InventoryStockExport",
                    "exportOptions": {
                        columns: [1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    "extend": "pdf",
                    "text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    "title": "InventoryStockExport",
                    "exportOptions": {
                        columns: [1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    "extend": "print",
                    "text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
                    "className": "btn btn-white btn-primary btn-bold",
                    autoPrint: false,
                    message: "Inventory Stock List",
                    "exportOptions": {
                        columns: [1, 2, 3, 4, 5, 6, 7]
                    }
                }
            ]
        });
        myTable.buttons().container().appendTo($('.tableTools-container'));
    };

    $(document).ready(() => {
        initialize_or_reload_table("#tbl_list"); // Initial load

        $('[data-toggle="tooltip"]').tooltip({
            html: true
        });
        
        // Auto-search if search parameter exists (from previous logic in inventory_stock)
        const urlParams = new URLSearchParams(window.location.search);
        const searchParam = urlParams.get('search');
        if (searchParam) {
            $("#txt_search").val(searchParam);
            search(); // Call search directly
        }

        // Delegated event handlers for table action buttons
        $('#tbl_list tbody').on('click', '.action-view-movements', function () {
            const stock_id = $(this).data('stock-id');
            console.log('View Movements button clicked for stock_id:', stock_id);
            if (typeof stock_id !== 'undefined') {
                view_movements(stock_id);
            } else {
                alert('Error: Stock ID not found for movements button.');
                console.error('Error: Stock ID not found for .action-view-movements button.');
            }
        });

        $('#tbl_list tbody').on('click', '.action-view-info', function () {
            const stock_id = $(this).data('stock-id');
            console.log('View Info button clicked for stock_id:', stock_id);
            if (typeof stock_id !== 'undefined') {
                view_info(stock_id);
            } else {
                alert('Error: Stock ID not found for info button.');
                console.error('Error: Stock ID not found for .action-view-info button.');
            }
        });

        $('#tbl_list tbody').on('click', '.action-manage-reservation', function () {
            const stock_id = $(this).data('stock-id');
            console.log('Manage Reservation button clicked for stock_id:', stock_id);
            if (typeof stock_id !== 'undefined') {
                open_manage_reservation_modal(stock_id);
            } else {
                alert('Error: Stock ID not found for manage reservation button.');
                console.error('Error: Stock ID not found for .action-manage-reservation button.');
            }
        });

        // Header button click handlers
        $('#btn_header_receive_stock').on('click', function() {
            open_receive_stock_modal(); // No stock_id, will open generic modal
        });

        $('#btn_header_release_stock').on('click', function() {
            open_release_stock_modal(); // No stock_id, will open generic modal
        });

        // Modal specific button clicks
        // For Manage Reservation Modal
        $("#btn_reserve_stock").click(function() {
            const quantity_to_reserve = $("#txt_reservation_quantity_change").val();
            if (parseFloat(quantity_to_reserve) > 0) {
                process_reservation_change(quantity_to_reserve);
            } else {
                $("#modal_manage_reservation .modal_error_msg").text("Error: Please enter a positive quantity to reserve.");
                $("#modal_manage_reservation .modal_error").show().delay(5000).fadeOut("slow");
            }
        });

        $("#btn_release_stock").click(function() { // This ID is for the button in the reservation modal
            const quantity_to_release = $("#txt_reservation_quantity_change").val();
            const effective_quantity_change = parseFloat(quantity_to_release) > 0 ? -parseFloat(quantity_to_release) : parseFloat(quantity_to_release);
            
            if (effective_quantity_change < 0) {
                process_reservation_change(effective_quantity_change);
            } else {
                $("#modal_manage_reservation .modal_error_msg").text("Error: Please enter a positive quantity to release (it will be treated as a negative change).");
                $("#modal_manage_reservation .modal_error").show().delay(5000).fadeOut("slow");
            }
        });

        // For Receive Stock Modal
        $("#btn_process_receive").click(function() {
            process_stock_receive();
        });

        // For Release Stock Modal
        $("#btn_process_release").click(function() {
            process_stock_release();
        });
    });

    // Search functionality
    $(document).on("keyup", "#txt_search", (e) => {
        if (e.which == 13) {
            search();
        }
    });

    $(document).on("click", "#btn_search", () => {
        search();
    });

    const search = () => {
        const search_term = $("#txt_search").val().trim();
        // Consider adding a loading indicator here
        
        fetch(`${base_url}inventory_stock/search?search=${encodeURIComponent(search_term)}`)
            .then(response => response.text())
            .then(data => {
                // Consider hiding loading indicator here
                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    alert(data);
                    populate_table("#tbl_list", []); // Clear table on error
                } else {
                    try {
                        if (data) {
                            result_data = JSON.parse(data);
                            populate_table("#tbl_list", result_data);
                        } else {
                            populate_table("#tbl_list", []); // Handle empty valid response
                        }
                    } catch (e) {
                        console.error("Error parsing JSON:", e, "Data received:", data);
                        alert("Error processing server response.");
                        populate_table("#tbl_list", []); // Clear table on parse error
                    }
                }
            })
            .catch(error => {
                // Consider hiding loading indicator here
                console.error('Error:', error);
                alert('An error occurred while searching.');
                populate_table("#tbl_list", []); // Clear table on fetch error
            })
            .finally(() => {
                // Reinitialize tooltips after table is populated/redrawn or cleared
                $('[data-toggle="tooltip"]').tooltip({ html: true });
            });
    };

    const populate_table = (table_id, data) => {
        const dataSet = [];
        if (data && data.length > 0) {
            data.forEach(item => {
                const row_data = [
                    table_buttons(item.id), // Modified to use new table_buttons output
                    `<strong>${item.product_code}</strong><br>${item.product_name}`,
                    item.location_name,
                    parseFloat(item.qty_on_hand).toFixed(2),
                    parseFloat(item.qty_reserved).toFixed(2),
                    `<span class="${parseFloat(item.qty_available) <= 10 ? 'text-danger' : ''}">${parseFloat(item.qty_available).toFixed(2)}</span>`,
                    item.uom_name,
                    item.last_updated
                ];
                dataSet.push(row_data);
            });
            $("#lbl_result_info").text(data.length + " record(s) found");
        } else {
            $("#lbl_result_info").text("No records found");
        }
        
        initialize_or_reload_table(table_id, dataSet); // Use the new function to draw table
        
        // Tooltip re-initialization is now handled in search().finally()
    };

    const table_buttons = (id) => {
        // Added data-stock-id attribute and specific classes for delegation
        // Removed inline onclick attributes
        // Removed Receive and Release buttons from table rows
        return `<div class="btn-group">
                    <button class="btn btn-xs btn-info action-view-movements" type="button" title="View Stock Movements" 
                            data-stock-id="${id}"> 
                        <i class="ace-icon fa fa-list bigger-120"></i>
                    </button>
                    <button class="btn btn-xs btn-success action-view-info" type="button" title="View Details" 
                            data-stock-id="${id}">
                        <i class="ace-icon fa fa-eye bigger-120"></i>
                    </button>
                    <button class="btn btn-xs btn-warning action-manage-reservation" type="button" title="Manage Reservation" 
                            data-stock-id="${id}">
                        <i class="ace-icon fa fa-tasks bigger-120"></i>
                    </button>
                </div>`;
    };

    // Stock Adjustment
    $(document).on("click", "#btn_stock_adjustment", () => {
        // Clear fields specifically within this modal
        $('#modal_stock_adjustment .txt_field').val('');
        // Update Chosen selects to reflect cleared values
        $('#modal_stock_adjustment .chosen-select').trigger('chosen:updated');
        
        // Hide any previous messages within this modal
        $('#modal_stock_adjustment .modal_error, #modal_stock_adjustment .modal_waiting').hide();
        
        $('#modal_stock_adjustment').modal('show');
    });

    // Initialize/Update Chosen when the stock adjustment modal is shown
    $('#modal_stock_adjustment').on('shown.bs.modal', function () {
        // 'this' refers to the modal element
        $(this).find('.chosen-select').chosen('destroy').chosen({
            width: "100%", // Make them full width
            allow_single_deselect: true
        });
        
        // Activate (focus) the first Chosen field
        $(this).find('#cmb_product_adj').trigger('chosen:activate');
    });

    $(document).on("click", "#btn_save_adjustment", () => {
        const product_id = $("#cmb_product_adj").val();
        const location_id = $("#cmb_location_adj").val();
        const adjustment_qty = $("#txt_adjustment_qty").val();
        const notes = $("#txt_adjustment_notes").val();
        const movement_date = $("#adj_movement_date").val(); // Get movement date

        if (product_id && location_id && adjustment_qty && movement_date) {
            $(".modal_error, .modal_waiting").hide();
            $(".modal_waiting").show();
            $(".modal_button").hide();

            const formData = {
                product_id: product_id,
                location_id: location_id,
                adjustment_qty: adjustment_qty,
                notes: notes,
                movement_date: movement_date // Pass movement date
            };

            fetch(`${base_url}inventory_stock/stock_adjustment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData)
            })
            .then(response => response.text())
            .then(data => {
                $(".modal_error, .modal_waiting").hide();
                $(".modal_button").show();

                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    $("#modal_stock_adjustment .modal_error_msg").text(data);
                    $("#modal_stock_adjustment .modal_error").show().delay(15000).fadeOut("slow");
                } else {
                    $("#modal_stock_adjustment").modal("hide");
                    alert("Stock adjustment completed successfully!");
                    search(); // Refresh the list
                }
            })
            .catch(error => {
                $(".modal_error, .modal_waiting").hide();
                $(".modal_button").show();
                console.error('Error:', error);
                alert('An error occurred while processing the adjustment.');
            });
        } else {
            $("#modal_stock_adjustment .modal_error_msg").text("Error: All fields with * are required!");
            $("#modal_stock_adjustment .modal_error").show().delay(15000).fadeOut("slow");
        }
    });

    // Stock Transfer
    $(document).on("click", "#btn_stock_transfer", () => {
        // Clear fields specifically within this modal
        $('#modal_stock_transfer .txt_field').val('');
        // Update Chosen selects to reflect cleared values and apply form-control styling if needed
        $('#modal_stock_transfer .chosen-select').trigger('chosen:updated');
        
        // Hide any previous messages within this modal
        $('#modal_stock_transfer .modal_error, #modal_stock_transfer .modal_waiting').hide();
        
        $('#modal_stock_transfer').modal('show'); // Changed to 'show'
    });

    // Initialize/Update Chosen when the stock transfer modal is shown
    $('#modal_stock_transfer').on('shown.bs.modal', function () {
        // 'this' refers to the modal element
        $(this).find('.chosen-select').chosen('destroy').chosen({
            width: "100%", // Make them full width
            allow_single_deselect: true
        });
        
        // Activate (focus) the first Chosen field
        $(this).find('#cmb_product_transfer').trigger('chosen:activate');
    });

    $(document).on("click", "#btn_save_transfer", () => {
        const product_id = $("#cmb_product_transfer").val();
        const from_location_id = $("#cmb_from_location").val();
        const to_location_id = $("#cmb_to_location").val();
        const transfer_qty = $("#txt_transfer_qty").val();
        const notes = $("#txt_transfer_notes").val();
        const movement_date = $("#transfer_movement_date").val(); // Get movement date

        if (product_id && from_location_id && to_location_id && transfer_qty && movement_date) {
            if (from_location_id === to_location_id) {
                $("#modal_stock_transfer .modal_error_msg").text("Error: Source and destination locations cannot be the same!");
                $("#modal_stock_transfer .modal_error").show().delay(15000).fadeOut("slow");
                return;
            }

            $(".modal_error, .modal_waiting").hide();
            $(".modal_waiting").show();
            $(".modal_button").hide();

            const formData = {
                product_id: product_id,
                from_location_id: from_location_id,
                to_location_id: to_location_id,
                transfer_qty: transfer_qty,
                notes: notes,
                movement_date: movement_date // Pass movement date
            };

            fetch(`${base_url}inventory_stock/stock_transfer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData)
            })
            .then(response => response.text())
            .then(data => {
                $(".modal_error, .modal_waiting").hide();
                $(".modal_button").show();

                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    $("#modal_stock_transfer .modal_error_msg").text(data);
                    $("#modal_stock_transfer .modal_error").show().delay(15000).fadeOut("slow");
                } else {
                    $("#modal_stock_transfer").modal("hide");
                    alert("Stock transfer completed successfully!");
                    search(); // Refresh the list
                }
            })
            .catch(error => {
                $(".modal_error, .modal_waiting").hide();
                $(".modal_button").show();
                console.error('Error:', error);
                alert('An error occurred while processing the transfer.');
            });
        } else {
            $("#modal_stock_transfer .modal_error_msg").text("Error: All fields with * are required!");
            $("#modal_stock_transfer .modal_error").show().delay(15000).fadeOut("slow");
        }
    });

    // Advanced Search
    $(document).on("click", "#btn_asearch", () => {
        // Clear fields specifically within this modal
        $('#modal_asearch .txt_field_asearch').val(''); // Use specific class for these fields
        $('#modal_asearch input[type=checkbox]').prop('checked', false);
        // Update Chosen selects to reflect cleared values
        $('#modal_asearch .chosen-select').trigger('chosen:updated');
        
        // Hide any previous messages within this modal
        $('#modal_asearch .modal_error, #modal_asearch .modal_waiting').hide();
        
        $('#modal_asearch').modal('show'); // Changed to 'show'
    });

    // Initialize/Update Chosen when the advanced search modal is shown
    $('#modal_asearch').on('shown.bs.modal', function () {
        // 'this' refers to the modal element
        $(this).find('.chosen-select').chosen('destroy').chosen({
            width: "100%", // Make them full width
            allow_single_deselect: true
        });
        
        // Activate (focus) the first Chosen field
        $(this).find('#cmb_product_asearch').trigger('chosen:activate');
    });

    $(document).on("click", "#btn_search_asearch", () => { // Corrected ID back to btn_search_asearch
        const formData = {
            product_id: $("#cmb_product_asearch").val(),
            location_id: $("#cmb_location_asearch").val(),
            min_qty: $("#txt_min_qty_asearch").val(),
            max_qty: $("#txt_max_qty_asearch").val(),
            low_stock_only: $("#chk_low_stock_only").is(':checked') ? 1 : 0,
            show_zero_stock: $("#chk_zero_stock_only").is(':checked') ? 1 : 0
        };

        $(".modal_error, .modal_waiting").hide();
        $(".modal_waiting").show();
        $(".modal_button").hide();

        fetch(`${base_url}inventory_stock/advance_search`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(formData)
        })
        .then(response => response.text())
        .then(data => {
            $(".modal_error, .modal_waiting").hide();
            $(".modal_button").show();

            if (data.indexOf("<!DOCTYPE html>") > -1) {
                alert("Error: Session Time-Out, You must login again to continue.");
                location.reload(true);
            } else if (data.indexOf("Error: ") > -1) {
                $("#modal_asearch .modal_error_msg").text(data);
                $("#modal_asearch .modal_error").show().delay(15000).fadeOut("slow");
            } else {
                try {
                    const result = JSON.parse(data);
                    populate_table("#tbl_list", result);
                    $("#modal_asearch").modal("hide");
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    alert('An error occurred while processing the search results.');
                }
            }
        })
        .catch(error => {
            $(".modal_error, .modal_waiting").hide();
            $(".modal_button").show();
            console.error('Error:', error);
            alert('An error occurred while searching.');
        });
    });

    // Low Stock Report
    $(document).on("click", "#btn_low_stock", () => {
        $("#txt_threshold_qty").val('10'); // Corrected ID: Default threshold for the correct input field
        // Clear previous results from the table
        $("#tbl_low_stock tbody").html('<tr><td colspan="7" class="text-center"><em>Click "Generate Report" to view low stock items</em></td></tr>');
        $("#lbl_low_stock_count").text("0");
        $("#btn_export_low_stock").hide();
        $('#modal_low_stock .modal_error, #modal_low_stock .modal_waiting').hide(); // Hide errors/waiting specific to this modal
        $('#modal_low_stock').modal('show'); // Ensure modal is shown using .modal('show')
    });

    $(document).on("click", "#btn_generate_low_stock", () => { // Corrected ID to match modal HTML
        const threshold = $("#txt_threshold_qty").val(); // Corrected ID to match modal HTML

        if (!threshold) {
            $("#modal_low_stock .modal_error_msg").text("Error: Please enter a threshold value!");
            $("#modal_low_stock .modal_error").show().delay(15000).fadeOut("slow");
            return;
        }

        // Use modal-specific waiting/error messages
        $("#modal_low_stock .modal_error").hide(); 
        $("#modal_low_stock .modal_waiting").show();
        $("#modal_low_stock .modal_button").hide();

        fetch(`${base_url}inventory_stock/low_stock_report?threshold=${threshold}`)
            .then(response => response.json())
            .then(data => {
                $("#modal_low_stock .modal_error, #modal_low_stock .modal_waiting").hide();
                $("#modal_low_stock .modal_button").show();

                let tbody = '';
                if (data && data.error) {
                    $("#modal_low_stock .modal_error_msg").text(data.error);
                    $("#modal_low_stock .modal_error").show().delay(15000).fadeOut("slow");
                    $("#tbl_low_stock tbody").html('<tr><td colspan="7" class="text-center"><em>Error generating report.</em></td></tr>');
                    $("#lbl_low_stock_count").text("0");
                    $("#btn_export_low_stock").hide();
                } else if (data && data.length > 0) {
                    data.forEach(item => { // Corrected: added parentheses around item
                        tbody += `
                            <tr class="${parseFloat(item.qty_available) <= 0 ? 'danger' : ''}">
                                <td>${item.product_code}</td>
                                <td>${item.product_name}</td>
                                <td>${item.location_name}</td>
                                <td class="text-right">${parseFloat(item.qty_available).toFixed(2)}</td>
                                <td class="text-center">${item.uom_name}</td>
                                <td>${item.category_name || ''}</td>
                                <td class="text-center">${item.last_updated}</td>
                            </tr>
                        `;
                    });
                    $("#tbl_low_stock tbody").html(tbody);
                    $("#lbl_low_stock_count").text(`${data.length} item(s) found below threshold of ${threshold}`);
                    $("#btn_export_low_stock").show();
                } else {
                    $("#tbl_low_stock tbody").html('<tr><td colspan="7" class="text-center"><em>No low stock items found for the given threshold.</em></td></tr>');
                    $("#lbl_low_stock_count").text("0");
                    $("#btn_export_low_stock").hide();
                }
            })
            .catch(error => {
                $("#modal_low_stock .modal_error, #modal_low_stock .modal_waiting").hide();
                $("#modal_low_stock .modal_button").show();
                console.error('Error:', error);
                alert('An error occurred while generating the report.');
            });
    });

    $(document).on("click", "#btn_export_low_stock", () => { // Corrected ID for export button
        const threshold = $("#txt_threshold_qty").val(); // Corrected ID
        if (threshold) {
            window.open(`${base_url}inventory_stock/export_low_stock?threshold=${threshold}`, '_blank');
        } else {
            alert('Please generate the report first!');
        }
    });

    $(document).on("click", "#btn_refresh_movements", () => {
        if (current_stock_id) {
            view_movements(current_stock_id);
        }
    });

    // Store current stock id for refresh functionality
    let current_stock_id = null;

    // Keyboard shortcuts
    $(document).on("keypress", ".txt_field_asearch", (e) => {
        if (e.which == 13) {
            $("#btn_search_asearch").trigger("click"); // Corrected ID back to btn_search_asearch
        }
    });

    $(document).on("keypress", "#txt_threshold_qty", (e) => { // Corrected ID for keypress
        if (e.which == 13) {
            $("#btn_generate_low_stock").trigger("click"); // Corrected ID for trigger
        }
    });

    // View stock movements
    const view_movements = (stock_id) => {
        console.log('view_movements called with id:', stock_id); // Log invocation
        current_stock_id = stock_id; // Store for refresh functionality
        
        // Get stock info first
        fetch(`${base_url}inventory_stock/search_by_row/${stock_id}`)
            .then(response => response.json())
            .then(stockData => {
                console.log("Stock data received for movements modal:", stockData); // Log received data
                // Check if stockData is a valid object and not an error array/object
                if (stockData && typeof stockData === 'object' && !Array.isArray(stockData) && !stockData.error) {
                    const stock = stockData; // Directly use stockData as it's a single object
                    $("#movements_product_name").text(`${stock.product_code} - ${stock.product_name}`);
                    $("#movements_location_name").text(stock.location_name);
                    
                    // Load movements
                    loadStockMovements(stock.product_id, stock.location_id);
                    $("#modal_movements").modal('show');
                } else if (stockData && stockData.error) {
                    alert("Could not load stock details: " + stockData.error);
                } else {
                    alert("Error: Could not retrieve stock details or no data found.");
                }
            })
            .catch(error => {
                console.error('Error fetching stock details for movements modal:', error);
                alert('An error occurred while loading stock movements details.');
            });
    };

    const loadStockMovements = (product_id, location_id) => {
        // Initialize movements table if not already done
        if (!$.fn.DataTable.isDataTable('#tbl_movements')) {
            $('#tbl_movements').DataTable({
                "bPaginate": true,
                "bLengthChange": false,
                "bFilter": false,
                "bInfo": false,
                "bAutoWidth": false,
                "pageLength": 10,
                "order": [[0, "desc"]], // Order by date descending
                "columnDefs": [{
                    "targets": [2],
                    "className": "text-right"
                }]
            });
        }
        
        const movementsTable = $('#tbl_movements').DataTable();
        movementsTable.clear();
        
        fetch(`${base_url}inventory_stock/stock_movements?product_id=${product_id}&location_id=${location_id}`)
            .then(response => response.json())
            .then(movements => {
                if (movements && movements.length > 0) {
                    movements.forEach(movement => {
                        const movementType = movement.movement_type || 'UNKNOWN';
                        const typeClass = movementType === 'ADJUSTMENT' ? 'text-warning' : 
                                         movementType === 'TRANSFER' ? 'text-info' : 'text-primary';
                        
                        const row_data = [
                            movement.created_at || '',
                            `<span class="${typeClass}">${movementType}</span>`,
                            parseFloat(movement.qty || 0).toFixed(2),
                            movement.reference_type || '',
                            movement.from_location_name || '',
                            movement.to_location_name || '',
                            movement.notes || '',
                            movement.created_by_name || ''
                        ];
                        
                        movementsTable.row.add(row_data);
                    });
                }
                
                movementsTable.draw();
            })
            .catch(error => {
                console.error('Error:', error);
                // Add an error row
                movementsTable.row.add([
                    'Error',
                    '<span class="text-danger">ERROR</span>',
                    '0.00',
                    '',
                    '',
                    '',
                    'Error loading movements',
                    ''
                ]);
                movementsTable.draw();
            });
    };

    // View info
    const view_info = (id) => {
        console.log('view_info called with id:', id); // Log invocation
        $('.txt_field_info').val('');
        $('.modal_error, .modal_waiting').hide();
        
        fetch(`${base_url}inventory_stock/search_by_row/${id}`)
            .then(response => response.json())
            .then(responseData => {
                console.log("Stock data received for info modal:", responseData); // Log received data
                // Check if responseData is a valid object and not an error array/object
                if (responseData && typeof responseData === 'object' && !Array.isArray(responseData) && !responseData.error) {
                    const info = responseData; // Directly use responseData as it's a single object
                    
                    // Populate form fields
                    $("#txt_product_code_info").val(info.product_code);
                    $("#txt_product_name_info").val(info.product_name);
                    $("#txt_location_info").val(info.location_name);
                    $("#txt_category_info").val(info.category); 
                    $("#txt_uom_code_info").val(info.uom_code);
                    $("#txt_uom_name_info").val(info.uom_name);
                    $("#txt_qty_on_hand_info").val(parseFloat(info.qty_on_hand).toFixed(2));
                    $("#txt_qty_reserved_info").val(parseFloat(info.qty_reserved).toFixed(2));
                    $("#txt_qty_available_info").val(parseFloat(info.qty_available).toFixed(2));
                    $("#txt_created_at_info").val(info.created_at);
                    $("#txt_last_updated_info").val(info.last_updated);
                    
                    $("#modal_info").modal('show');
                } else if (responseData && responseData.error) {
                    alert("Could not load stock information: " + responseData.error);
                } else {
                    alert("Error: No information found or could not retrieve details.");
                }
            })
            .catch(error => {
                console.error('Error fetching stock details for info modal:', error);
                alert('An error occurred while fetching information.');
            });
    };

    // --- Manage Stock Reservation Modal --- 
    const open_manage_reservation_modal = (stock_id) => {
        $('#form_manage_reservation')[0].reset(); // Clear form fields
        $('#modal_manage_reservation .modal_error, #modal_manage_reservation .modal_waiting').hide();

        fetch(`${base_url}inventory_stock/search_by_row/${stock_id}`)
            .then(response => response.json())
            .then(data => {
                if (data && typeof data === 'object' && !data.error) {
                    $("#txt_reservation_stock_id").val(data.id);
                    $("#txt_reservation_product_id").val(data.product_id);
                    $("#txt_reservation_location_id").val(data.location_id);
                    $("#txt_reservation_product_code").val(data.product_code);
                    $("#txt_reservation_product_name").val(data.product_name);
                    $("#txt_reservation_location_name").val(data.location_name);
                    $("#txt_reservation_current_on_hand").val(parseFloat(data.qty_on_hand).toFixed(2));
                    $("#txt_reservation_current_reserved").val(parseFloat(data.qty_reserved).toFixed(2));
                    $("#txt_reservation_current_available").val(parseFloat(data.qty_available).toFixed(2));
                    
                    $('#modal_manage_reservation').modal('show');
                } else {
                    alert("Error: Could not retrieve stock details for reservation.");
                }
            })
            .catch(error => {
                console.error('Error fetching stock details for reservation modal:', error);
                alert('An error occurred while fetching stock details.');
            });
    };

    const process_reservation_change = (quantity_change) => {
        const stock_id = $("#txt_reservation_stock_id").val();
        const product_id = $("#txt_reservation_product_id").val();
        const location_id = $("#txt_reservation_location_id").val();
        const notes = $("#txt_reservation_notes").val();

        if (!product_id || !location_id || quantity_change === '' || isNaN(parseFloat(quantity_change))) {
            $("#modal_manage_reservation .modal_error_msg").text("Error: Product, Location, and a valid Quantity Change are required.");
            $("#modal_manage_reservation .modal_error").show().delay(5000).fadeOut("slow");
            return;
        }

        const formData = {
            stock_id: stock_id,
            product_id: product_id,
            location_id: location_id,
            quantity_change: parseFloat(quantity_change),
            notes: notes
        };

        $("#modal_manage_reservation .modal_error").hide();
        $("#modal_manage_reservation .modal_waiting_msg").text('Processing reservation change...');
        $("#modal_manage_reservation .modal_waiting").show();
        $("#modal_manage_reservation .modal_button").hide();

        fetch(`${base_url}inventory_stock/manage_reserved_stock_ajax`,
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            $("#modal_manage_reservation .modal_waiting").hide();
            $("#modal_manage_reservation .modal_button").show();
            if (data.success) {
                alert(data.message || "Stock reservation updated successfully!");
                $('#modal_manage_reservation').modal('hide');
                search(); // Refresh the main table
            } else {
                $("#modal_manage_reservation .modal_error_msg").text(data.error || "An unknown error occurred.");
                $("#modal_manage_reservation .modal_error").show().delay(10000).fadeOut("slow");
            }
        })
        .catch(error => {
            $("#modal_manage_reservation .modal_waiting").hide();
            $("#modal_manage_reservation .modal_button").show();
            console.error('Error processing reservation change:', error);
            $("#modal_manage_reservation .modal_error_msg").text("An error occurred while communicating with the server.");
            $("#modal_manage_reservation .modal_error").show().delay(10000).fadeOut("slow");
        });
    };

    $(document).on('click', '#btn_reserve_stock', () => {
        const quantity_to_reserve = $("#txt_reservation_quantity_change").val();
        if (parseFloat(quantity_to_reserve) > 0) {
            process_reservation_change(quantity_to_reserve);
        } else {
            $("#modal_manage_reservation .modal_error_msg").text("Error: Please enter a positive quantity to reserve.");
            $("#modal_manage_reservation .modal_error").show().delay(5000).fadeOut("slow");
        }
    });

    $(document).on('click', '#btn_release_stock', () => {
        const quantity_to_release = $("#txt_reservation_quantity_change").val();
        // Ensure the input is treated as a negative value for releasing if user enters positive
        const effective_quantity_change = parseFloat(quantity_to_release) > 0 ? -parseFloat(quantity_to_release) : parseFloat(quantity_to_release);
        
        if (effective_quantity_change < 0) {
            process_reservation_change(effective_quantity_change);
        } else {
            $("#modal_manage_reservation .modal_error_msg").text("Error: Please enter a positive quantity to release (it will be treated as a negative change).");
            $("#modal_manage_reservation .modal_error").show().delay(5000).fadeOut("slow");
        }
    });

    function open_receive_stock_modal(stock_id) {
		// Clear form first
		$('#form_receive_stock')[0].reset();
		$("#rs_product_id, #rs_location_id, #rs_stock_id").val(''); // Clear hidden fields
		$("#rs_product_name, #rs_location_name, #rs_current_on_hand").val(''); // Clear readonly display fields
		$('#rs_product_select_div').remove(); // Remove product dropdown if it exists
        $('#rs_location_select_div').remove(); // Remove location dropdown if it exists

		if (stock_id) {
			// Fetch stock details to pre-fill if needed
			$.ajax({
				url: base_url + "inventory_stock/search_by_row_ajax/" + stock_id,
				type: "GET",
				dataType: "json",
				success: function(response) {
					if (response.success && response.data) {
						const stock = response.data;
						$("#rs_product_name").val(stock.product_name + ' (' + stock.product_code + ')').show();
						$("#rs_location_name").val(stock.location_name).show();
						$("#rs_current_on_hand").val(stock.qty_on_hand);
						$("#rs_stock_id").val(stock.id);
						$("#rs_product_id").val(stock.product_id);
						$("#rs_location_id").val(stock.location_id);
						$("#rs_quantity").val(''); 
						$("#rs_notes").val(''); 
						$("#modal_receive_stock").modal("show");
					} else {
						toastr.error(response.message || "Error fetching stock details.");
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					toastr.error("AJAX Error: " + textStatus + ": " + errorThrown);
				}
			});
		} else {
			// Generic modal: show product and location dropdowns
			$("#rs_product_name").hide(); // Hide the readonly field
			$("#rs_location_name").hide(); // Hide the readonly field

			// Add Product Select
			const productSelectDiv = $('<div class=\"form-group row\" id=\"rs_product_select_div\"><label for=\"rs_product_select\" class=\"col-sm-4 col-form-label\">Product <span class=\"text-danger\">*</span></label><div class=\"col-sm-8\"><select id=\"rs_product_select\" name=\"rs_product_select\" class=\"form-control chosen-select\" data-placeholder=\"Choose a Product...\"></select></div></div>');
			$("#form_receive_stock").prepend(productSelectDiv);
			
			// Add Location Select
            const locationSelectDiv = $('<div class=\"form-group row\" id=\"rs_location_select_div\"><label for=\"rs_location_select\" class=\"col-sm-4 col-form-label\">Location <span class=\"text-danger\">*</span></label><div class=\"col-sm-8\"><select id=\"rs_location_select\" name=\"rs_location_select\" class=\"form-control chosen-select\" data-placeholder=\"Choose a Location...\"></select></div></div>');
            productSelectDiv.after(locationSelectDiv); // Insert after product select

			// Populate Product Dropdown
			fetch_all_products_for_select('#rs_product_select', () => {
                // Populate Location Dropdown after products are loaded (or independently)
                fetch_all_locations_for_select('#rs_location_select', () => {
                    $('#rs_product_select, #rs_location_select').chosen({ width: "100%", allow_single_deselect: true });
                    $("#modal_receive_stock").modal("show");
                });
            });

            // When product/location changes, update hidden fields and current on hand
            $('#rs_product_select, #rs_location_select').on('change', function() {
                const selectedProductId = $('#rs_product_select').val();
                const selectedLocationId = $('#rs_location_select').val();
                $("#rs_product_id").val(selectedProductId);
                $("#rs_location_id").val(selectedLocationId);
                $("#rs_stock_id").val(''); // Clear stock_id as it's a new/generic entry

                if (selectedProductId && selectedLocationId) {
                    fetch_stock_details_for_modal(selectedProductId, selectedLocationId, '#rs_current_on_hand', null, null, null);
                } else {
                    $("#rs_current_on_hand").val('');
                }
            });
		}
	}

	function open_release_stock_modal(stock_id) {
		// Clear form first
		$('#form_release_stock')[0].reset();
		$("#rls_product_id, #rls_location_id, #rls_stock_id").val('');
		$("#rls_product_name, #rls_location_name, #rls_current_on_hand, #rls_current_reserved, #rls_qty_available_for_release").val('');
        $('#rls_product_select_div').remove(); 
        $('#rls_location_select_div').remove();

		if (stock_id) {
			$.ajax({
				url: base_url + "inventory_stock/search_by_row_ajax/" + stock_id,
				type: "GET",
				dataType: "json",
				success: function(response) {
					if (response.success && response.data) {
						const stock = response.data;
						const qty_available_for_release = parseFloat(stock.qty_on_hand) - parseFloat(stock.qty_reserved);
						$("#rls_product_name").val(stock.product_name + ' (' + stock.product_code + ')').show();
						$("#rls_location_name").val(stock.location_name).show();
						$("#rls_current_on_hand").val(stock.qty_on_hand);
						$("#rls_current_reserved").val(stock.qty_reserved);
						$("#rls_qty_available_for_release").val(qty_available_for_release.toFixed(2));
						$("#rls_stock_id").val(stock.id);
						$("#rls_product_id").val(stock.product_id);
						$("#rls_location_id").val(stock.location_id);
						$("#rls_quantity").val(''); 
						$("#rls_notes").val(''); 
						$("#modal_release_stock").modal("show");
					} else {
						toastr.error(response.message || "Error fetching stock details.");
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					toastr.error("AJAX Error: " + textStatus + ": " + errorThrown);
				}
			});
		} else {
            // Generic modal: show product and location dropdowns
            $("#rls_product_name").hide();
            $("#rls_location_name").hide();

            // Add Product Select
            const productSelectDiv = $('<div class=\"form-group row\" id=\"rls_product_select_div\"><label for=\"rls_product_select\" class=\"col-sm-4 col-form-label\">Product <span class=\"text-danger\">*</span></label><div class=\"col-sm-8\"><select id=\"rls_product_select\" name=\"rls_product_select\" class=\"form-control chosen-select\" data-placeholder=\"Choose a Product...\"></select></div></div>');
            $("#form_release_stock").prepend(productSelectDiv);

            // Add Location Select
            const locationSelectDiv = $('<div class=\"form-group row\" id=\"rls_location_select_div\"><label for=\"rls_location_select\" class=\"col-sm-4 col-form-label\">Location <span class=\"text-danger\">*</span></label><div class=\"col-sm-8\"><select id=\"rls_location_select\" name=\"rls_location_select\" class=\"form-control chosen-select\" data-placeholder=\"Choose a Location...\"></select></div></div>');
            productSelectDiv.after(locationSelectDiv);

            fetch_all_products_for_select('#rls_product_select', () => {
                fetch_all_locations_for_select('#rls_location_select', () => {
                    $('#rls_product_select, #rls_location_select').chosen({ width: "100%", allow_single_deselect: true });
                    $("#modal_release_stock").modal("show");
                });
            });
            
            $('#rls_product_select, #rls_location_select').on('change', function() {
                const selectedProductId = $('#rls_product_select').val();
                const selectedLocationId = $('#rls_location_select').val();
                $("#rls_product_id").val(selectedProductId);
                $("#rls_location_id").val(selectedLocationId);
                $("#rls_stock_id").val(''); 

                if (selectedProductId && selectedLocationId) {
                    fetch_stock_details_for_modal(selectedProductId, selectedLocationId, '#rls_current_on_hand', '#rls_current_reserved', '#rls_qty_available_for_release', '#rls_stock_id');
                } else {
                    $("#rls_current_on_hand, #rls_current_reserved, #rls_qty_available_for_release, #rls_stock_id").val('');
                }
            });
		}
	}

	function fetch_all_products_for_select(select_id, callback) {
        $.ajax({
            url: base_url + 'data_product/search_all_active_ajax', // Assuming this endpoint exists
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data) {
                    const select = $(select_id);
                    select.empty().append('<option value=\"\"></option>'); // Add a placeholder option
                    response.data.forEach(product => {
                        select.append(`<option value=\"${product.id}\">${product.name} (${product.code})</option>`);
                    });
                    select.trigger('chosen:updated');
                    if (callback) callback();
                } else {
                    toastr.error(response.message || 'Could not load products.');
                }
            },
            error: function() {
                toastr.error('Failed to fetch products.');
            }
        });
    }

    function fetch_all_locations_for_select(select_id, callback) {
        $.ajax({
            url: base_url + 'data_location/search_all_active_ajax', // Assuming this endpoint exists
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data) {
                    const select = $(select_id);
                    select.empty().append('<option value=\"\"></option>');
                    response.data.forEach(location => {
                        select.append(`<option value=\"${location.id}\">${location.location}</option>`);
                    });
                    select.trigger('chosen:updated');
                    if (callback) callback();
                } else {
                    toastr.error(response.message || 'Could not load locations.');
                }
            },
            error: function() {
                toastr.error('Failed to fetch locations.');
            }
        });
    }

    function fetch_stock_details_for_modal(product_id, location_id, on_hand_field_id, reserved_field_id, available_field_id, stock_id_field_id) {
        if (!product_id || !location_id) {
            $(on_hand_field_id).val('');
            if(reserved_field_id) $(reserved_field_id).val('');
            if(available_field_id) $(available_field_id).val('');
            if(stock_id_field_id) $(stock_id_field_id).val('');
            return;
        }
        $.ajax({
            url: `${base_url}inventory_stock/get_stock_by_product_location_ajax`,
            type: 'POST', // Changed from GET to POST
            data: { 
                product_id: product_id, 
                location_id: location_id,
                [csrf_name]: csrf_hash // Assuming you have csrf_name and csrf_hash variables available globally for CSRF protection
            },
            dataType: 'json',
            success: function(response) {
                regenerate_csrf(response.csrf_hash); // Regenerate CSRF token
                if (response.success && response.data) {
                    const stock = response.data;
                    $(on_hand_field_id).val(parseFloat(stock.qty_on_hand).toFixed(2));
                    if(reserved_field_id) $(reserved_field_id).val(parseFloat(stock.qty_reserved).toFixed(2));
                    if(available_field_id) {
                        const available = parseFloat(stock.qty_on_hand) - parseFloat(stock.qty_reserved);
                        $(available_field_id).val(available.toFixed(2));
                    }
                    if(stock_id_field_id) $(stock_id_field_id).val(stock.id);
                } else {
                    // No stock record, means 0 on hand, 0 reserved
                    $(on_hand_field_id).val('0.00');
                     if(reserved_field_id) $(reserved_field_id).val('0.00');
                    if(available_field_id) $(available_field_id).val('0.00');
                    if(stock_id_field_id) $(stock_id_field_id).val(''); // No existing stock ID
                    // toastr.info(response.message || 'No stock record found for this product/location. New record will be created if receiving.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) { // Added jqXHR, textStatus, errorThrown for better error logging
                // Attempt to regenerate CSRF if possible from response, though less likely for POST errors of this type
                if(jqXHR.responseJSON && jqXHR.responseJSON.csrf_hash){
                    regenerate_csrf(jqXHR.responseJSON.csrf_hash);
                }
                toastr.error('Failed to fetch stock details. Status: ' + textStatus + ", Error: " + errorThrown);
                $(on_hand_field_id).val('');
                if(reserved_field_id) $(reserved_field_id).val('');
                if(available_field_id) $(available_field_id).val('');
                if(stock_id_field_id) $(stock_id_field_id).val('');
            }
        });
    }

	function process_stock_receive() {
		const productId = $('#rs_product_id').val();
		const locationId = $('#rs_location_id').val();
		const quantity = $('#rs_quantity').val();
		const notes = $('#rs_notes').val();
		const movementDate = $('#rs_movement_date').val(); // Get movement date

		if (!productId || !locationId || !quantity || !movementDate) { // Added movementDate validation
			Swal.fire('Error', 'Product, Location, Quantity, and Movement Date are required.', 'error');
			return;
		}
		if (parseFloat(quantity) <= 0) {
			Swal.fire('Error', 'Quantity must be a positive number.', 'error');
			return;
		}

		// AJAX call to controller
		$.ajax({
			url: base_url + 'inventory_stock/receive_stock_ajax',
			type: 'POST',
			data: {
				product_id: productId,
				location_id: locationId,
				quantity: quantity,
				notes: notes,
				movement_date: movementDate // Pass movement date
			},
			dataType: 'json',
			success: function(response) {
				regenerate_csrf(response.csrf_hash);
				if (response.success) {
					toastr.success(response.message || "Stock received successfully!");
					$("#modal_receive_stock").modal("hide");
					search(); // Refresh main table
					// Optionally, update the specific row if possible without full reload
				} else {
					toastr.error(response.message || "Error receiving stock.");
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				toastr.error("AJAX Error: " + textStatus + ": " + errorThrown);
			}
		});
	}

	function process_stock_release() {
		const stockId = $('#rls_stock_id').val();
		const quantity = $('#rls_quantity').val();
		const notes = $('#rls_notes').val();
		const movementDate = $('#rls_movement_date').val(); // Get movement date

		// Frontend validation
		if (!stockId || !quantity || !movementDate) { // Added movementDate validation
			Swal.fire('Error', 'Stock Item, Quantity, and Movement Date are required.', 'error');
			return;
		}
		if (parseFloat(quantity) <= 0) {
			Swal.fire('Error', 'Quantity must be a positive number.', 'error');
			return;
		}
		// More specific validation if needed, e.g., check against available quantity (can be complex on client-side)

		// AJAX call to controller
		$.ajax({
			url: base_url + 'inventory_stock/release_stock_ajax',
			type: 'POST',
			data: {
				stock_id: stockId,
				quantity: quantity,
				notes: notes,
				movement_date: movementDate // Pass movement date
			},
			dataType: 'json',
			success: function(response) {
				regenerate_csrf(response.csrf_hash);
				if (response.success) {
					toastr.success(response.message || "Stock released successfully!");
					$("#modal_release_stock").modal("hide");
					search(); // Refresh main table
				} else {
					toastr.error(response.message || "Error releasing stock.");
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// Attempt to regenerate CSRF if possible from response
                if(jqXHR.responseJSON && jqXHR.responseJSON.csrf_hash){
                    regenerate_csrf(jqXHR.responseJSON.csrf_hash);
                }
				toastr.error("AJAX Error: " + textStatus + ": " + errorThrown);
			}
		});
	}


</script>

<?php //$this->load->view('inventory_stock/modal_info'); ?> // THIS BLOCK IS REMOVED
<?php //$this->load->view('inventory_stock/modal_movements'); ?>
<?php //$this->load->view('inventory_stock/modal_stock_adjustment'); ?>
<?php //$this->load->view('inventory_stock/modal_stock_transfer'); ?>
<?php //$this->load->view('inventory_stock/modal_asearch'); ?>
<?php //$this->load->view('inventory_stock/modal_low_stock'); ?>
<?php //$this->load->view('inventory_stock/modal_manage_reservation'); ?>
<?php //$this->load->view('inventory_stock/modal_receive_stock'); ?>
<?php //$this->load->view('inventory_stock/modal_release_stock'); ?>

</body>
</html>
