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
        /* Custom styling for DataTables export buttons */
        .dt-buttons {
            margin-bottom: 10px;
        }
        
        .dt-button {
            margin-right: 5px !important;
            border-radius: 3px !important;
            font-size: 12px !important;
            padding: 5px 12px !important;
        }
        
        .dt-button:hover {
            box-shadow: 0 2px 4px rgba(0,0,0,0.2) !important;
        }
        
        /* Ensure buttons container has proper spacing */
        .dataTables_wrapper .dt-buttons {
            float: right;
            margin: 10px 0;
        }
        
        @media (max-width: 768px) {
            .dataTables_wrapper .dt-buttons {
                float: none;
                text-align: center;
            }
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
                                    </small>
                                </h1>
                            </div><!-- /.page-header -->

                            <div class="row">
                                <div class="col-xs-12">

                                    <!-- Filter Section -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="widget-box">
                                                <div class="widget-header">
                                                    <h4 class="widget-title">Search & Filter</h4>
                                                    <div class="widget-toolbar">
                                                        <a href="#" data-action="collapse">
                                                            <i class="ace-icon fa fa-chevron-up"></i>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="widget-body">
                                                    <div class="widget-main">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <label>Location:</label>
                                                                <select id="filter_location" class="form-control chosen-select">
                                                                    <option value="">All Locations</option>
                                                                    <?php foreach($locations as $location): ?>
                                                                    <option value="<?= $location->id; ?>"><?= $location->location; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label>Search:</label>
                                                                <input type="text" id="search_text" class="form-control" placeholder="Search products, categories, etc...">
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top: 10px;">
                                                            <div class="col-sm-12">
                                                                <button type="button" id="btn_search" class="btn btn-primary">
                                                                    <i class="ace-icon fa fa-search"></i> Search
                                                                </button>
                                                                <button type="button" id="btn_low_stock" class="btn btn-warning">
                                                                    <i class="ace-icon fa fa-exclamation-triangle"></i> Low Stock Report
                                                                </button>
                                                                <?php if($this->cf->module_permission("create", $module_permission)): ?>
                                                                <button type="button" id="btn_receive" class="btn btn-success">
                                                                    <i class="ace-icon fa fa-plus"></i> Receive Stock
                                                                </button>
                                                                <button type="button" id="btn_release" class="btn btn-danger">
                                                                    <i class="ace-icon fa fa-minus"></i> Release Stock
                                                                </button>
                                                                <button type="button" id="btn_transfer" class="btn btn-info">
                                                                    <i class="ace-icon fa fa-exchange"></i> Transfer Stock
                                                                </button>
                                                                <button type="button" id="btn_adjust" class="btn btn-warning">
                                                                    <i class="ace-icon fa fa-cog"></i> Adjust Stock
                                                                </button>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Results Table -->
                                    <div class="table-header">
                                        Stock Levels
                                    </div>

                                    <div>
                                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Product Code</th>
                                                    <th>Product Name</th>
                                                    <th>Category</th>
                                                    <th>UOM</th>
                                                    <th>Location</th>
                                                    <th>On Hand</th>
                                                    <th>Reserved</th>
                                                    <th>Available</th>
                                                    <th>Last Updated</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_body">
                                                <!-- Data will be loaded via AJAX -->
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                            <!-- PAGE CONTENT ENDS -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.page-content -->
            </div>
        </div><!-- /.main-content -->

        <!-- Modals -->
        <!-- Receive Stock Modal -->
        <div class="modal fade" id="modal_receive" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Receive Stock</h4>
                    </div>
                    <div class="modal-body">
                        <form id="form_receive">
                            <div class="form-group">
                                <label>Product:</label>
                                <select id="receive_product_id" name="product_id" class="form-control chosen-select" required>
                                    <option value="">Select Product</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Location:</label>
                                <select id="receive_location_id" name="location_id" class="form-control chosen-select" required>
                                    <option value="">Select Location</option>
                                    <?php foreach($locations as $location): ?>
                                    <option value="<?= $location->id; ?>"><?= $location->location; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Quantity:</label>
                                <input type="number" id="receive_qty" name="qty" class="form-control" min="1" required>
                            </div>
                            <div class="form-group">
                                <label>Unit Cost:</label>
                                <input type="number" id="receive_unit_cost" name="unit_cost" class="form-control" step="0.01" min="0">
                            </div>
                            <div class="form-group">
                                <label>Reference Type:</label>
                                <select id="receive_reference_type" name="reference_type" class="form-control" required>
                                    <option value="PURCHASE">Purchase</option>
                                    <option value="RETURN">Return</option>
                                    <option value="ADJUSTMENT">Adjustment</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Reference ID:</label>
                                <input type="text" id="receive_reference_id" name="reference_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Notes:</label>
                                <textarea id="receive_notes" name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" id="btn_save_receive" class="btn btn-success">Receive Stock</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Release Stock Modal -->
        <div class="modal fade" id="modal_release" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Release Stock</h4>
                    </div>
                    <div class="modal-body">
                        <form id="form_release">
                            <div class="form-group">
                                <label>Product:</label>
                                <select id="release_product_id" name="product_id" class="form-control chosen-select" required>
                                    <option value="">Select Product</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Location:</label>
                                <select id="release_location_id" name="location_id" class="form-control chosen-select" required>
                                    <option value="">Select Location</option>
                                    <?php foreach($locations as $location): ?>
                                    <option value="<?= $location->id; ?>"><?= $location->location; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Available Stock: <span id="available_stock" class="text-info"></span></label>
                                <label>Quantity to Release:</label>
                                <input type="number" id="release_qty" name="qty" class="form-control" min="1" required>
                            </div>
                            <div class="form-group">
                                <label>Reference Type:</label>
                                <select id="release_reference_type" name="reference_type" class="form-control" required>
                                    <option value="SALE">Sale</option>
                                    <option value="RETURN">Return</option>
                                    <option value="ADJUSTMENT">Adjustment</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Reference ID:</label>
                                <input type="text" id="release_reference_id" name="reference_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Notes:</label>
                                <textarea id="release_notes" name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" id="btn_save_release" class="btn btn-danger">Release Stock</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transfer Stock Modal -->
        <div class="modal fade" id="modal_transfer" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Transfer Stock</h4>
                    </div>
                    <div class="modal-body">
                        <form id="form_transfer">
                            <div class="form-group">
                                <label>Product:</label>
                                <select id="transfer_product_id" name="product_id" class="form-control chosen-select" required>
                                    <option value="">Select Product</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>From Location:</label>
                                <select id="transfer_from_location_id" name="from_location_id" class="form-control chosen-select" required>
                                    <option value="">Select Source Location</option>
                                    <?php foreach($locations as $location): ?>
                                    <option value="<?= $location->id; ?>"><?= $location->location; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>To Location:</label>
                                <select id="transfer_to_location_id" name="to_location_id" class="form-control chosen-select" required>
                                    <option value="">Select Destination Location</option>
                                    <?php foreach($locations as $location): ?>
                                    <option value="<?= $location->id; ?>"><?= $location->location; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Available Stock: <span id="transfer_available_stock" class="text-info"></span></label>
                                <label>Quantity to Transfer:</label>
                                <input type="number" id="transfer_qty" name="qty" class="form-control" min="1" required>
                            </div>
                            <div class="form-group">
                                <label>Notes:</label>
                                <textarea id="transfer_notes" name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" id="btn_save_transfer" class="btn btn-info">Transfer Stock</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adjust Stock Modal -->
        <div class="modal fade" id="modal_adjust" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Adjust Stock</h4>
                    </div>
                    <div class="modal-body">
                        <form id="form_adjust">
                            <div class="form-group">
                                <label>Product:</label>
                                <select id="adjust_product_id" name="product_id" class="form-control chosen-select" required>
                                    <option value="">Select Product</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Location:</label>
                                <select id="adjust_location_id" name="location_id" class="form-control chosen-select" required>
                                    <option value="">Select Location</option>
                                    <?php foreach($locations as $location): ?>
                                    <option value="<?= $location->id; ?>"><?= $location->location; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Current Stock: <span id="current_stock" class="text-info"></span></label>
                                <label>New Quantity:</label>
                                <input type="number" id="adjust_new_qty" name="new_qty" class="form-control" min="0" required>
                            </div>
                            <div class="form-group">
                                <label>Reason for Adjustment:</label>
                                <textarea id="adjust_notes" name="notes" class="form-control" rows="3" required></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" id="btn_save_adjust" class="btn btn-warning">Adjust Stock</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
		$this->load->view('template/footer');
		?>
    </div><!-- /.main-container -->

    <?php
	$this->load->view('template/script');
	?>

    <script type="text/javascript">
        var oTable1;

        $(document).ready(function() {
            // Initialize DataTable with export buttons
            oTable1 = $('#dynamic-table').DataTable({
                "aoColumns": [
                    null, null, null, null, null, null, null, null, null,
                    {"bSortable": false}
                ],
                "aaSorting": [],
                "select": {
                    "style": "single"
                },
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'excel',
                        text: '<i class="ace-icon fa fa-file-excel-o"></i> Export to Excel',
                        className: 'btn btn-success btn-sm',
                        title: 'Stock Levels Report - ' + new Date().toLocaleDateString(),
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] // Exclude Actions column
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="ace-icon fa fa-file-pdf-o"></i> Export to PDF',
                        className: 'btn btn-danger btn-sm',
                        title: 'Stock Levels Report',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] // Exclude Actions column
                        },
                        customize: function(doc) {
                            // Set column widths to match the 9 exported columns
                            doc.content[1].table.widths = ['10%', '25%', '15%', '8%', '12%', '10%', '10%', '10%', '10%'];
                            
                            // Adjust font sizes for better fit
                            doc.styles.tableHeader.fontSize = 8;
                            doc.defaultStyle.fontSize = 7;
                            doc.styles.tableHeader.alignment = 'left';
                            
                            // Add header
                            doc.content.splice(0, 0, {
                                text: [
                                    { text: 'Stock Levels Report\n', fontSize: 16, bold: true },
                                    { text: 'Generated on: ' + new Date().toLocaleString() + '\n\n', fontSize: 10 }
                                ],
                                alignment: 'center'
                            });
                            
                            // Ensure proper table structure
                            if (doc.content[1] && doc.content[1].table) {
                                doc.content[1].table.headerRows = 1;
                                doc.content[1].table.keepWithHeaderRows = 1;
                            }
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="ace-icon fa fa-print"></i> Print',
                        className: 'btn btn-info btn-sm',
                        title: 'Stock Levels Report',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] // Exclude Actions column
                        },
                        customize: function(win) {
                            // Add custom CSS for proper printing
                            $(win.document.head).append(
                                '<style type="text/css">' +
                                '@page { ' +
                                    'margin: 1in 0.5in; ' +
                                    'size: A4 landscape; ' +
                                '}' +
                                'body { ' +
                                    'font-family: Arial, sans-serif; ' +
                                    'font-size: 10px; ' +
                                    'line-height: 1.3; ' +
                                    'margin: 0; ' +
                                    'padding: 20px; ' +
                                '}' +
                                'h1 { ' +
                                    'font-size: 18px; ' +
                                    'text-align: center; ' +
                                    'margin-bottom: 10px; ' +
                                    'color: #333; ' +
                                '}' +
                                'table { ' +
                                    'width: 100%; ' +
                                    'border-collapse: collapse; ' +
                                    'margin-top: 20px; ' +
                                    'table-layout: fixed; ' +
                                '}' +
                                'th, td { ' +
                                    'border: 1px solid #ddd; ' +
                                    'padding: 6px 4px; ' +
                                    'text-align: left; ' +
                                    'word-wrap: break-word; ' +
                                    'overflow-wrap: break-word; ' +
                                '}' +
                                'th { ' +
                                    'background-color: #f5f5f5; ' +
                                    'font-weight: bold; ' +
                                    'font-size: 9px; ' +
                                '}' +
                                'td { ' +
                                    'font-size: 8px; ' +
                                '}' +
                                '/* Column widths for auto-fit */' +
                                'th:nth-child(1), td:nth-child(1) { width: 10%; }' +
                                'th:nth-child(2), td:nth-child(2) { width: 25%; }' +
                                'th:nth-child(3), td:nth-child(3) { width: 15%; }' +
                                'th:nth-child(4), td:nth-child(4) { width: 8%; }' +
                                'th:nth-child(5), td:nth-child(5) { width: 12%; }' +
                                'th:nth-child(6), td:nth-child(6) { width: 10%; }' +
                                'th:nth-child(7), td:nth-child(7) { width: 10%; }' +
                                'th:nth-child(8), td:nth-child(8) { width: 10%; }' +
                                'th:nth-child(9), td:nth-child(9) { width: 12%; }' +
                                '.print-info { ' +
                                    'text-align: center; ' +
                                    'font-size: 10px; ' +
                                    'margin-bottom: 15px; ' +
                                    'color: #666; ' +
                                '}' +
                                '</style>'
                            );
                            
                            // Add header with current filters and generation info
                            var location_filter = $("#filter_location option:selected").text();
                            var search_text = $("#search_text").val();
                            var filterInfo = '';
                            
                            if (location_filter && location_filter !== 'All Locations') {
                                filterInfo += 'Location: ' + location_filter + ' | ';
                            }
                            if (search_text) {
                                filterInfo += 'Search: "' + search_text + '" | ';
                            }
                            filterInfo += 'Generated: ' + new Date().toLocaleString();
                            
                            $(win.document.body).prepend(
                                '<div style="text-align: center; margin-bottom: 20px;">' +
                                '<h1>Stock Levels Report</h1>' +
                                '<div class="print-info">' + filterInfo + '</div>' +
                                '</div>'
                            );
                        }
                    }
                ]
            });

            // Load products for dropdowns
            loadProducts();

            // Initial load
            searchStock();

            // Event handlers
            $("#btn_search").click(function() {
                searchStock();
            });

            $("#search_text").keypress(function(e) {
                if (e.which == 13) {
                    searchStock();
                }
            });

            $("#filter_location").change(function() {
                searchStock();
            });

            $("#btn_low_stock").click(function() {
                showLowStockReport();
            });

            // Modal event handlers
            $("#btn_receive").click(function() {
                $("#modal_receive").modal();
            });

            $("#btn_release").click(function() {
                $("#modal_release").modal();
            });

            $("#btn_transfer").click(function() {
                $("#modal_transfer").modal();
            });

            $("#btn_adjust").click(function() {
                $("#modal_adjust").modal();
            });

            // Save handlers
            $("#btn_save_receive").click(function() {
                saveReceiveStock();
            });

            $("#btn_save_release").click(function() {
                saveReleaseStock();
            });

            $("#btn_save_transfer").click(function() {
                saveTransferStock();
            });

            $("#btn_save_adjust").click(function() {
                saveAdjustStock();
            });

            // Product/location change handlers for stock checking
            $("#release_product_id, #release_location_id").change(function() {
                checkAvailableStock('release');
            });

            $("#transfer_product_id, #transfer_from_location_id").change(function() {
                checkAvailableStock('transfer');
            });

            $("#adjust_product_id, #adjust_location_id").change(function() {
                checkCurrentStock();
            });
        });

        function searchStock() {
            var search = $("#search_text").val();
            var location_id = $("#filter_location").val();

            $.get("<?= base_url(); ?>inventory_stock/search", {
                search: search,
                location_id: location_id
            }, function(data) {
                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    toastr.error(data);
                } else {
                    var result = JSON.parse(data);
                    populateTable(result);
                }
            });
        }

        function populateTable(data) {
            oTable1.clear();

            $.each(data, function(i, row) {
                var actions = '<button type="button" class="btn btn-xs btn-info" onclick="viewStockDetails(' + row.product_id + ',' + row.location_id + ')"><i class="ace-icon fa fa-eye"></i></button>';
                
                oTable1.row.add([
                    row.product_code,
                    row.product_name,
                    row.category,
                    row.uom,
                    row.location,
                    parseFloat(row.qty_on_hand).toFixed(2),
                    parseFloat(row.qty_reserved).toFixed(2),
                    parseFloat(row.qty_available).toFixed(2),
                    row.last_updated,
                    actions
                ]);
            });

            oTable1.draw();
            
            // Update export button titles based on current data
            updateExportTitles();
        }
        
        function updateExportTitles() {
            var location_filter = $("#filter_location option:selected").text();
            var search_text = $("#search_text").val();
            var title_suffix = '';
            
            if (location_filter && location_filter !== 'All Locations') {
                title_suffix += ' - ' + location_filter;
            }
            if (search_text) {
                title_suffix += ' - Search: ' + search_text;
            }
            
            // Update button titles
            oTable1.button(0).text('<i class="ace-icon fa fa-file-excel-o"></i> Export to Excel');
            oTable1.button(1).text('<i class="ace-icon fa fa-file-pdf-o"></i> Export to PDF');
        }

        function loadProducts() {
            $.get("<?= base_url(); ?>data_product/search", {}, function(data) {
                if (data.indexOf("Error: ") === -1) {
                    var products = JSON.parse(data);
                    var options = '<option value="">Select Product</option>';
                    
                    $.each(products, function(i, product) {
                        options += '<option value="' + product.id + '">' + product.code + ' - ' + product.name + '</option>';
                    });
                    
                    $("#receive_product_id, #release_product_id, #transfer_product_id, #adjust_product_id").html(options);
                    $(".chosen-select").trigger("chosen:updated");
                }
            });
        }

        function saveReceiveStock() {
            var formData = $("#form_receive").serialize();
            
            $.post("<?= base_url(); ?>inventory_stock/receive_stock", formData, function(data) {
                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    toastr.error(data);
                } else {
                    toastr.success(data);
                    $("#modal_receive").modal('hide');
                    $("#form_receive")[0].reset();
                    searchStock();
                }
            });
        }

        function saveReleaseStock() {
            var formData = $("#form_release").serialize();
            
            $.post("<?= base_url(); ?>inventory_stock/release_stock", formData, function(data) {
                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    toastr.error(data);
                } else {
                    toastr.success(data);
                    $("#modal_release").modal('hide');
                    $("#form_release")[0].reset();
                    searchStock();
                }
            });
        }

        function saveTransferStock() {
            var formData = $("#form_transfer").serialize();
            
            $.post("<?= base_url(); ?>inventory_stock/transfer_stock", formData, function(data) {
                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    toastr.error(data);
                } else {
                    toastr.success(data);
                    $("#modal_transfer").modal('hide');
                    $("#form_transfer")[0].reset();
                    searchStock();
                }
            });
        }

        function saveAdjustStock() {
            var formData = $("#form_adjust").serialize();
            
            $.post("<?= base_url(); ?>inventory_stock/adjust_stock", formData, function(data) {
                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    toastr.error(data);
                } else {
                    toastr.success(data);
                    $("#modal_adjust").modal('hide');
                    $("#form_adjust")[0].reset();
                    searchStock();
                }
            });
        }

        function checkAvailableStock(type) {
            var product_id = $("#" + type + "_product_id").val();
            var location_id = type === 'transfer' ? $("#transfer_from_location_id").val() : $("#" + type + "_location_id").val();
            
            if (product_id && location_id) {
                $.get("<?= base_url(); ?>inventory_stock/get_product_stock_details", {
                    product_id: product_id,
                    location_id: location_id
                }, function(data) {
                    if (data.indexOf("Error: ") === -1) {
                        var result = JSON.parse(data);
                        var available = result.stock ? result.stock.qty_available : 0;
                        
                        if (type === 'transfer') {
                            $("#transfer_available_stock").text(available);
                        } else {
                            $("#available_stock").text(available);
                        }
                    }
                });
            }
        }

        function checkCurrentStock() {
            var product_id = $("#adjust_product_id").val();
            var location_id = $("#adjust_location_id").val();
            
            if (product_id && location_id) {
                $.get("<?= base_url(); ?>inventory_stock/get_product_stock_details", {
                    product_id: product_id,
                    location_id: location_id
                }, function(data) {
                    if (data.indexOf("Error: ") === -1) {
                        var result = JSON.parse(data);
                        var current = result.stock ? result.stock.qty_on_hand : 0;
                        $("#current_stock").text(current);
                        $("#adjust_new_qty").val(current);
                    }
                });
            }
        }

        function showLowStockReport() {
            var location_id = $("#filter_location").val();
            
            $.get("<?= base_url(); ?>inventory_stock/low_stock_report", {
                location_id: location_id
            }, function(data) {
                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    toastr.error(data);
                } else {
                    var result = JSON.parse(data);
                    populateTable(result);
                    toastr.warning("Showing " + result.length + " products with low stock levels");
                }
            });
        }

        function viewStockDetails(product_id, location_id) {
            $.get("<?= base_url(); ?>inventory_stock/get_product_stock_details", {
                product_id: product_id,
                location_id: location_id
            }, function(data) {
                if (data.indexOf("Error: ") === -1) {
                    var result = JSON.parse(data);
                    var movements = result.movements;
                    
                    var html = '<div class="table-responsive"><table class="table table-striped table-sm">';
                    html += '<thead><tr><th>Date</th><th>Type</th><th>Qty</th><th>Reference</th><th>Notes</th></tr></thead><tbody>';
                    
                    $.each(movements, function(i, mov) {
                        html += '<tr>';
                        html += '<td>' + mov.created_at + '</td>';
                        html += '<td><span class="label label-' + getMovementColor(mov.movement_type) + '">' + mov.movement_type + '</span></td>';
                        html += '<td>' + mov.qty + '</td>';
                        html += '<td>' + mov.reference_type + (mov.reference_id ? ' #' + mov.reference_id : '') + '</td>';
                        html += '<td>' + (mov.notes || '') + '</td>';
                        html += '</tr>';
                    });
                    
                    html += '</tbody></table></div>';
                    
                    bootbox.dialog({
                        title: "Stock Movement History",
                        message: html,
                        size: 'large',
                        buttons: {
                            ok: {
                                label: "Close",
                                className: "btn-primary"
                            }
                        }
                    });
                }
            });
        }

        function getMovementColor(type) {
            switch(type) {
                case 'RECEIVE': return 'success';
                case 'RELEASE': return 'danger';
                case 'TRANSFER': return 'info';
                case 'ADJUSTMENT': return 'warning';
                case 'RESERVE': return 'default';
                default: return 'default';
            }
        }
    </script>

</body>

</html>
