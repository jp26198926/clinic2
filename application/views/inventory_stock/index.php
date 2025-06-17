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
            margin-left: 5px !important;
            border-radius: 3px !important;
            font-size: 12px !important;
            padding: 5px 12px !important;
            min-width: 32px !important;
        }
        
        .dt-button:hover {
            box-shadow: 0 2px 4px rgba(0,0,0,0.2) !important;
        }

        /* Mobile responsive card layout */
        @media (max-width: 768px) {
            .table-responsive {
                border: none;
            }
            
            #dynamic-table {
                display: none !important;
            }
            
            .mobile-card-container {
                display: block !important;
            }

            /* Make filter section more compact on mobile */
            .widget-box .widget-main {
                padding: 10px;
            }
            
            .widget-box .row {
                margin-left: -5px;
                margin-right: -5px;
            }
            
            .widget-box .row [class*="col-"] {
                padding-left: 5px;
                padding-right: 5px;
            }

            /* Hide export buttons container on mobile since they won't work properly */
            .tableTools-container {
                display: none !important;
            }
            
            .stock-card {
                background: white;
                border: 1px solid #ddd;
                border-radius: 8px;
                margin-bottom: 15px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            
            .stock-card-header {
                background: #f8f9fa;
                padding: 12px 15px;
                border-bottom: 1px solid #ddd;
            }
            
            .stock-card-title {
                font-weight: bold;
                font-size: 16px;
                color: #333;
                margin-bottom: 5px;
            }
            
            .stock-card-subtitle {
                font-size: 14px;
                color: #666;
            }
            
            .stock-card-body {
                padding: 15px;
            }
            
            .stock-info-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 8px 0;
                border-bottom: 1px solid #f0f0f0;
            }
            
            .stock-info-row:last-child {
                border-bottom: none;
            }
            
            .stock-info-label {
                font-weight: 600;
                color: #555;
                font-size: 13px;
            }
            
            .stock-info-value {
                font-size: 13px;
                color: #333;
            }
            
            .stock-quantities {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 10px;
                margin: 10px 0;
                padding: 10px;
                background: #f8f9fa;
                border-radius: 5px;
            }
            
            .qty-item {
                text-align: center;
            }
            
            .qty-label {
                font-size: 11px;
                color: #666;
                font-weight: 600;
                display: block;
            }
            
            .qty-value {
                font-size: 14px;
                font-weight: bold;
                display: block;
                margin-top: 2px;
            }
            
            .qty-value.on-hand {
                color: #28a745;
            }
            
            .qty-value.reserved {
                color: #ffc107;
            }
            
            .qty-value.available {
                color: #007bff;
            }
            
            .stock-card-actions {
                padding: 10px 15px;
                background: #f8f9fa;
                border-top: 1px solid #ddd;
                text-align: center;
            }
            
            .mobile-search-bar {
                margin-bottom: 15px;
            }
            
            .mobile-search-bar input {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 14px;
            }

            /* Mobile action buttons */
            .mobile-action-buttons {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin-bottom: 15px;
            }
            
            .mobile-action-buttons .btn {
                flex: 1;
                min-width: calc(50% - 4px);
                font-size: 12px;
                padding: 8px 10px;
            }
        }

        @media (min-width: 769px) {
            .mobile-card-container {
                display: none !important;
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
                                                            </div>                                            <div class="col-sm-6">
                                                <label>Search:</label>
                                                <input type="text" id="search_text" class="form-control" placeholder="Search products, categories, etc...">
                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top: 10px;">
                                                            <div class="col-sm-12">
                                                                <button type="button" id="btn_search" class="btn btn-sm btn-primary">
                                                                    <i class="ace-icon fa fa-search"></i> Search
                                                                </button>                                                                <button type="button" id="btn_low_stock" class="btn btn-sm btn-warning">
                                                                    <i class="ace-icon fa fa-exclamation-triangle"></i> Low Stock Report
                                                                </button>
                                                                <button type="button" id="btn_expiring_stock" class="btn btn-sm btn-orange">
                                                                    <i class="ace-icon fa fa-clock-o"></i> Expiring Stock
                                                                </button>
                                                                <button type="button" id="btn_expired_stock" class="btn btn-sm btn-danger">
                                                                    <i class="ace-icon fa fa-times-circle"></i> Expired Stock
                                                                </button>
                                                                <button type="button" id="btn_stock_valuation" class="btn btn-sm btn-purple">
                                                                    <i class="ace-icon fa fa-calculator"></i> Stock Valuation
                                                                </button>
                                                                <?php if($this->cf->module_permission("add", $module_permission)): ?>
                                                                <button type="button" id="btn_adjust" class="btn btn-sm btn-warning">
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
                                    <div class="table-header clearfix">
                                        Stock Levels <span id="lbl_result_info" class="badge badge-warning"></span>
                                        <div class="pull-right" style="padding-right: 0.5em; padding-top: 0.4em;">
                                            <div class="pull-right tableTools-container"></div>
                                        </div>
                                    </div>

                                    <!-- Desktop Table View -->
                                    <div class="table-responsive">
                                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product Code</th>
                                                    <th>Product Name</th>
                                                    <th>Category</th>
                                                    <th>UOM</th>
                                                    <th>Location</th>
                                                    <th>On Hand</th>
                                                    <th>Reserved</th>
                                                    <th>Available</th>
                                                    <th>Unit Cost</th>
                                                    <th>Total Value</th>
                                                    <th>Expiration</th>
                                                    <th>Last Updated</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="table_body">
                                                <!-- Data will be loaded via AJAX -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Mobile Card View -->
                                    <div class="mobile-card-container" style="display: none;">
                                        <div class="mobile-action-buttons">
                                            <?php if($this->cf->module_permission("add", $module_permission)): ?>                                            <button type="button" id="mobile_btn_adjust" class="btn btn-sm btn-warning">
                                                <i class="ace-icon fa fa-cog"></i> Adjust
                                            </button>
                                            <button type="button" id="mobile_btn_expiring" class="btn btn-sm btn-orange">
                                                <i class="ace-icon fa fa-clock-o"></i> Expiring
                                            </button>
                                            <button type="button" id="mobile_btn_valuation" class="btn btn-sm btn-purple">
                                                <i class="ace-icon fa fa-calculator"></i> Valuation
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mobile-search-bar">
                                            <input type="text" id="mobile-search" placeholder="Search in results..." class="form-control">
                                        </div>
                                        <div id="mobile-cards-container">
                                            <!-- Mobile cards will be populated here -->
                                        </div>
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
        <!-- Adjust Stock Modal -->
        <div class="modal fade" id="modal_adjust" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Stock Adjustment</h4>
                    </div>
                    <div class="modal-body">
                        <form id="form_adjust">
                            <div class="form-group">
                                <label>Product:</label>
                                <select id="adjust_product_id" name="product_id" class="form-control chosen-select" required>
                                    <option value="">Select Product</option>
                                    <!-- Products will be loaded via AJAX when modal opens -->
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
                                <label>Current Stock: <span id="current_stock" class="text-info font-weight-bold"></span></label>
                            </div>
                            <div class="form-group">
                                <label>Adjustment Type:</label>
                                <select id="adjust_type" name="adjust_type" class="form-control" required>
                                    <option value="">Select Adjustment Type</option>
                                    <option value="ADD">Add Stock (+)</option>
                                    <option value="SUBTRACT">Subtract Stock (-)</option>
                                    <option value="SET">Set Exact Quantity</option>
                                </select>
                            </div>
                            <div class="form-group" id="adjust_qty_group" style="display: none;">
                                <label id="adjust_qty_label">Adjustment Quantity:</label>
                                <input type="number" id="adjust_qty" name="adjust_qty" class="form-control" min="0" step="0.01">
                                <small class="form-text text-muted" id="adjust_qty_help"></small>
                            </div>
                            <div class="form-group" id="new_qty_group" style="display: none;">
                                <label>New Total Quantity:</label>
                                <input type="number" id="adjust_new_qty" name="new_qty" class="form-control" min="0" step="0.01">
                                <small class="form-text text-muted">Enter the exact quantity you want to set</small>
                            </div>
                            <div class="form-group" id="preview_group" style="display: none;">
                                <div class="alert alert-info" id="adjustment_preview">
                                    <!-- Preview will be shown here -->
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Reason for Adjustment:</label>
                                <select id="adjust_reason" name="adjust_reason" class="form-control" required>
                                    <option value="">Select Reason</option>
                                    <option value="DAMAGE">Damaged Items</option>
                                    <option value="EXPIRED">Expired Items</option>
                                    <option value="LOST">Lost/Missing Items</option>
                                    <option value="FOUND">Found Items</option>
                                    <option value="RECOUNT">Physical Recount</option>
                                    <option value="SYSTEM_ERROR">System Error Correction</option>
                                    <option value="RETURN">Supplier Return</option>
                                    <option value="OTHER">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Additional Notes:</label>
                                <textarea id="adjust_notes" name="notes" class="form-control" rows="3" placeholder="Optional additional details about this adjustment..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" id="btn_save_adjust" class="btn btn-warning" disabled>
                            <i class="ace-icon fa fa-cog"></i> Apply Adjustment
                        </button>
                    </div>
                </div>
            </div>
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
        const base_url = "<?= base_url() ?>";
        const currency_symbol = "<?= $currency_symbol ?? '₱' ?>";
        const currency_code = "<?= $currency_code ?? 'PHP' ?>";
        var oTable1;

        $(document).ready(function() {
            // Initialize DataTable with export buttons
            oTable1 = $('#dynamic-table').DataTable({
                "aoColumns": [
                    {"bSortable": false}, // Row count column
                    null, null, null, null, null, null, null, null, null, null, null, null,
                    {"bSortable": false} // Actions column
                ],
                "aaSorting": [],
                "select": {
                    "style": "single"
                },
                "buttons": [
                    {
                        extend: 'excel',
                        text: '<i class="ace-icon fa fa-file-excel-o bigger-110 green"></i> <span class="hidden">Export to Excel</span>',
                        className: 'btn btn-white btn-primary btn-bold',
                        titleAttr: 'Export to Excel',
                        title: 'Stock Levels Report - ' + new Date().toISOString().split('T')[0],
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] // Exclude row count (0) and Actions column (13)
                        }
                    },
                    {
                        text: '<i class="ace-icon fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span>',
                        className: 'btn btn-white btn-primary btn-bold',
                        titleAttr: 'Export to PDF',
                        action: function(e, dt, node, config) {
                            // Get current filter values
                            var search = $("#search_text").val();
                            var location_id = $("#filter_location").val();
                            
                            // Build URL with filters
                            var url = base_url + 'inventory_stock/export_pdf';
                            var params = [];
                            
                            if (search) {
                                params.push('search=' + encodeURIComponent(search));
                            }
                            if (location_id) {
                                params.push('location_id=' + encodeURIComponent(location_id));
                            }
                            
                            if (params.length > 0) {
                                url += '?' + params.join('&');
                            }
                            
                            // Open PDF in new window
                            window.open(url, '_blank');
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="ace-icon fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span>',
                        className: 'btn btn-white btn-primary btn-bold',
                        titleAttr: 'Print Report',
                        title: 'Stock Levels Report',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] // Exclude row count (0) and Actions column (13)
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

            // Append buttons to the table header container
            oTable1.buttons().container().appendTo($('.tableTools-container'));

            // Load products for dropdowns
            loadProducts();

            // Initialize chosen dropdowns
            $('.chosen-select').chosen({
                allow_single_deselect: true,
                no_results_text: "No results match",
                width: "100%"
            });

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

            // Handle chosen dropdown changes
            $("#filter_location").chosen().change(function() {
                searchStock();
            });

            $("#btn_low_stock").click(function() {
                showLowStockReport();
            });

            // Modal event handlers
            $("#btn_adjust").click(function() {
                // Reset form before opening modal
                $("#form_adjust")[0].reset();
                resetAdjustmentForm();
                $("#current_stock").text('');
                
                // Load products
                loadProductsForAdjustment();
                
                $("#modal_adjust").modal();
                setTimeout(function() {
                    $("#modal_adjust .chosen-select").chosen("destroy").chosen({
                        allow_single_deselect: true,
                        no_results_text: "No results match",
                        width: "100%"
                    });
                }, 100);            });

            // Report button handlers
            $("#btn_expiring_stock").click(function() {
                viewExpiringStock();
            });

            $("#btn_expired_stock").click(function() {
                viewExpiredStock();
            });

            $("#btn_stock_valuation").click(function() {
                viewStockValuation();
            });

            // Mobile report button handlers
            $("#mobile_btn_expiring").click(function() {
                viewExpiringStock();
            });

            $("#mobile_btn_valuation").click(function() {
                viewStockValuation();
            });

            // Save handlers
            $("#btn_save_adjust").click(function() {
                saveAdjustStock();
            });

            // Product/location change handlers for stock checking
            $("#adjust_product_id, #adjust_location_id").change(function() {
                checkCurrentStock();
            });

            // Handle chosen dropdown changes for stock checking
            $("#adjust_product_id, #adjust_location_id").chosen().change(function() {
                checkCurrentStock();
            });

            // Stock adjustment event handlers
            $("#adjust_type").change(function() {
                handleAdjustmentTypeChange();
            });

            $("#adjust_qty, #adjust_new_qty").on('input', function() {
                calculateAdjustmentPreview();
            });

            // Mobile search functionality
            $("#mobile-search").on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                
                $('.stock-card').each(function() {
                    var searchData = $(this).data('search');
                    if (searchData.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Mobile action button handlers (same functionality as desktop buttons)
            $("#mobile_btn_adjust").click(function() {
                $("#btn_adjust").click();
            });
        });

        // Date formatting functions for consistent YYYY-MM-DD display
        function formatDate(dateString) {
            if (!dateString) return '';
            
            var date = new Date(dateString);
            if (isNaN(date.getTime())) return dateString;
            
            return date.toISOString().split('T')[0]; // YYYY-MM-DD format
        }

        function formatDateTime(dateTimeString) {
            if (!dateTimeString) return '';
            
            var date = new Date(dateTimeString);
            if (isNaN(date.getTime())) return dateTimeString;
            
            var year = date.getFullYear();
            var month = String(date.getMonth() + 1).padStart(2, '0');
            var day = String(date.getDate()).padStart(2, '0');
            var hours = String(date.getHours()).padStart(2, '0');
            var minutes = String(date.getMinutes()).padStart(2, '0');
            
            return `${year}-${month}-${day} ${hours}:${minutes}`;
        }

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
                var rowNumber = i + 1; // Row count starting from 1
                var actions = '<button type="button" class="btn btn-xs btn-info" onclick="viewStockDetails(' + row.product_id + ',' + row.location_id + ')"><i class="ace-icon fa fa-eye"></i></button>';
                
                // Format expiration date and status
                var expirationDisplay = '';
                if (row.expiration_date) {
                    var expirationClass = '';
                    if (row.expiration_status === 'expired') {
                        expirationClass = 'text-danger';
                        expirationDisplay = '<span class="' + expirationClass + '">' + row.expiration_date_formatted + ' (EXPIRED)</span>';
                    } else if (row.expiration_status === 'expiring_soon') {
                        expirationClass = 'text-warning';
                        expirationDisplay = '<span class="' + expirationClass + '">' + row.expiration_date_formatted + ' (EXPIRING)</span>';
                    } else {
                        expirationDisplay = row.expiration_date_formatted;
                    }
                } else {
                    expirationDisplay = '<span class="text-muted">No expiry</span>';
                }
                
                var unitCost = parseFloat(row.unit_cost || 0).toFixed(2);
                var totalValue = (parseFloat(row.qty_on_hand) * parseFloat(row.unit_cost || 0)).toFixed(2);
                
                oTable1.row.add([
                    rowNumber, // Row count column
                    row.product_code,
                    row.product_name,
                    row.category,
                    row.uom,
                    row.location,
                    parseFloat(row.qty_on_hand).toFixed(2),
                    parseFloat(row.qty_reserved).toFixed(2),
                    parseFloat(row.qty_available).toFixed(2),
                    currency_symbol + unitCost,
                    currency_symbol + totalValue,
                    expirationDisplay,
                    formatDateTime(row.last_updated),
                    actions
                ]);
            });

            oTable1.draw();
            
            // Populate mobile cards
            populateMobileCards(data);
            
            // Update export button titles based on current data
            updateExportTitles();
        }

        function populateMobileCards(data) {
            var container = $('#mobile-cards-container');
            container.empty();

            if (data.length === 0) {
                container.html('<div class="text-center" style="padding: 20px; color: #666;">No stock data found</div>');
                return;
            }            $.each(data, function(i, row) {
                var onHandQty = parseFloat(row.qty_on_hand).toFixed(2);
                var reservedQty = parseFloat(row.qty_reserved).toFixed(2);
                var availableQty = parseFloat(row.qty_available).toFixed(2);
                var unitCost = parseFloat(row.unit_cost || 0).toFixed(2);
                var totalValue = (parseFloat(row.qty_on_hand) * parseFloat(row.unit_cost || 0)).toFixed(2);
                
                // Format expiration info for mobile
                var expirationInfo = '';
                if (row.expiration_date) {
                    var expirationClass = '';
                    var statusText = '';
                    if (row.expiration_status === 'expired') {
                        expirationClass = 'text-danger';
                        statusText = ' (EXPIRED)';
                    } else if (row.expiration_status === 'expiring_soon') {
                        expirationClass = 'text-warning';
                        statusText = ' (EXPIRING SOON)';
                    }
                    expirationInfo = `
                        <div class="stock-info-row">
                            <span class="stock-info-label">Expiration:</span>
                            <span class="stock-info-value ${expirationClass}">${row.expiration_date_formatted}${statusText}</span>
                        </div>`;
                } else {
                    expirationInfo = `
                        <div class="stock-info-row">
                            <span class="stock-info-label">Expiration:</span>
                            <span class="stock-info-value text-muted">No expiry</span>
                        </div>`;
                }
                
                var card = $(`
                    <div class="stock-card" data-search="${row.product_code.toLowerCase()} ${row.product_name.toLowerCase()} ${row.category.toLowerCase()} ${row.location.toLowerCase()}">
                        <div class="stock-card-header">
                            <div class="stock-card-title">${row.product_name}</div>
                            <div class="stock-card-subtitle">${row.product_code} • ${row.category}</div>
                        </div>
                        <div class="stock-card-body">
                            <div class="stock-info-row">
                                <span class="stock-info-label">Location:</span>
                                <span class="stock-info-value">${row.location}</span>
                            </div>
                            <div class="stock-info-row">
                                <span class="stock-info-label">Unit of Measure:</span>
                                <span class="stock-info-value">${row.uom}</span>
                            </div>
                            <div class="stock-quantities">
                                <div class="qty-item">
                                    <span class="qty-label">On Hand</span>
                                    <span class="qty-value on-hand">${onHandQty}</span>
                                </div>
                                <div class="qty-item">
                                    <span class="qty-label">Reserved</span>
                                    <span class="qty-value reserved">${reservedQty}</span>
                                </div>
                                <div class="qty-item">
                                    <span class="qty-label">Available</span>
                                    <span class="qty-value available">${availableQty}</span>
                                </div>
                            </div>
                            <div class="stock-info-row">
                                <span class="stock-info-label">Unit Cost:</span>
                                <span class="stock-info-value">${currency_symbol}${unitCost}</span>
                            </div>
                            <div class="stock-info-row">
                                <span class="stock-info-label">Total Value:</span>
                                <span class="stock-info-value">${currency_symbol}${totalValue}</span>
                            </div>
                            ${expirationInfo}
                            <div class="stock-info-row">
                                <span class="stock-info-label">Last Updated:</span>
                                <span class="stock-info-value">${formatDateTime(row.last_updated)}</span>
                            </div>
                        </div>
                        <div class="stock-card-actions">
                            <button type="button" class="btn btn-sm btn-info" onclick="viewStockDetails(${row.product_id}, ${row.location_id})">
                                <i class="ace-icon fa fa-eye"></i> View Details
                            </button>
                        </div>
                    </div>
                `);
                
                container.append(card);
            });
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
            
            // Update button tooltips using jQuery (since we only show icons now)
            try {
                $(oTable1.button(0).node()).attr('title', 'Export to Excel' + title_suffix);
                $(oTable1.button(1).node()).attr('title', 'Export to PDF' + title_suffix);
                $(oTable1.button(2).node()).attr('title', 'Print Report' + title_suffix);
            } catch (e) {
                // Fallback: update via class selectors if button API fails
                $('.buttons-excel').attr('title', 'Export to Excel' + title_suffix);
                $('.buttons-pdf').attr('title', 'Export to PDF' + title_suffix);
                $('.buttons-print').attr('title', 'Print Report' + title_suffix);
            }
        }

        function loadProducts() {
            $.get("<?= base_url(); ?>data_product/search", {}, function(data) {
                if (data.indexOf("Error: ") === -1) {
                    var products = JSON.parse(data);
                    var options = '<option value="">Select Product</option>';
                    
                    $.each(products, function(i, product) {
                        options += '<option value="' + product.id + '">' + product.code + ' - ' + product.name + '</option>';
                    });
                    
                    $("#adjust_product_id").html(options);
                    
                    // Update chosen dropdowns
                    $("#adjust_product_id").trigger("chosen:updated");
                }
            });
        }

        function saveAdjustStock() {
            // Validate form before submission
            if (!$("#adjust_product_id").val()) {
                toastr.error("Please select a product");
                return;
            }
            if (!$("#adjust_location_id").val()) {
                toastr.error("Please select a location");
                return;
            }
            if (!$("#adjust_type").val()) {
                toastr.error("Please select an adjustment type");
                return;
            }
            if (!$("#adjust_reason").val()) {
                toastr.error("Please select a reason for adjustment");
                return;
            }
            
            var adjustType = $("#adjust_type").val();
            var adjustQty = parseFloat($("#adjust_qty").val()) || 0;
            var newQty = parseFloat($("#adjust_new_qty").val()) || 0;
            
            // Validate quantity based on adjustment type
            if (adjustType === 'SET') {
                if (newQty < 0) {
                    toastr.error("New quantity cannot be negative");
                    return;
                }
            } else {
                if (adjustQty <= 0) {
                    toastr.error("Please enter a valid quantity");
                    return;
                }
            }
            
            // Prepare form data
            var formData = new FormData();
            formData.append('product_id', $("#adjust_product_id").val());
            formData.append('location_id', $("#adjust_location_id").val());
            formData.append('adjust_type', adjustType);
            formData.append('adjust_reason', $("#adjust_reason").val());
            formData.append('notes', $("#adjust_notes").val());
            
            if (adjustType === 'SET') {
                formData.append('new_qty', newQty);
            } else {
                formData.append('adjust_qty', adjustQty);
            }
            
            // Disable submit button to prevent double submission
            $("#btn_save_adjust").prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            
            $.ajax({
                url: "<?= base_url(); ?>inventory_stock/adjust_stock",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.indexOf("<!DOCTYPE html>") > -1) {
                        alert("Error: Session Time-Out, You must login again to continue.");
                        location.reload(true);
                    } else if (data.indexOf("Error: ") > -1) {
                        toastr.error(data);
                    } else {
                        toastr.success(data);
                        $("#modal_adjust").modal('hide');
                        $("#form_adjust")[0].reset();
                        resetAdjustmentForm();
                        searchStock();
                    }
                },
                error: function() {
                    toastr.error("An error occurred while processing the adjustment");
                },
                complete: function() {
                    // Re-enable submit button
                    $("#btn_save_adjust").prop('disabled', false).html('<i class="ace-icon fa fa-cog"></i> Apply Adjustment');
                }
            });
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
                        // Reset adjustment form when stock is loaded
                        resetAdjustmentForm();
                    }
                });
            }
        }

        function handleAdjustmentTypeChange() {
            var adjustType = $("#adjust_type").val();
            
            // Reset form elements
            $("#adjust_qty_group").hide();
            $("#new_qty_group").hide();
            $("#preview_group").hide();
            $("#btn_save_adjust").prop('disabled', true);
            
            // Clear inputs
            $("#adjust_qty").val('');
            $("#adjust_new_qty").val('');
            
            if (adjustType) {
                if (adjustType === 'SET') {
                    $("#new_qty_group").show();
                    $("#adjust_qty_label").text("New Total Quantity:");
                    $("#adjust_qty_help").text("Enter the exact quantity you want to set");
                } else {
                    $("#adjust_qty_group").show();
                    if (adjustType === 'ADD') {
                        $("#adjust_qty_label").text("Quantity to Add:");
                        $("#adjust_qty_help").text("Enter the amount to add to current stock");
                    } else if (adjustType === 'SUBTRACT') {
                        $("#adjust_qty_label").text("Quantity to Subtract:");
                        $("#adjust_qty_help").text("Enter the amount to subtract from current stock");
                    }
                }
                
                // Re-populate new_qty with current stock for SET type
                var currentStock = parseFloat($("#current_stock").text()) || 0;
                if (adjustType === 'SET') {
                    $("#adjust_new_qty").val(currentStock);
                }
                
                calculateAdjustmentPreview();
            }
        }

        function calculateAdjustmentPreview() {
            var adjustType = $("#adjust_type").val();
            var currentStock = parseFloat($("#current_stock").text()) || 0;
            var adjustQty = parseFloat($("#adjust_qty").val()) || 0;
            var newQty = parseFloat($("#adjust_new_qty").val()) || 0;
            
            if (!adjustType) return;
            
            var finalQty = 0;
            var changeQty = 0;
            var isValid = true;
            var errorMsg = '';
            
            if (adjustType === 'ADD') {
                if (adjustQty <= 0) {
                    isValid = false;
                    errorMsg = 'Please enter a positive quantity to add';
                } else {
                    finalQty = currentStock + adjustQty;
                    changeQty = adjustQty;
                }
            } else if (adjustType === 'SUBTRACT') {
                if (adjustQty <= 0) {
                    isValid = false;
                    errorMsg = 'Please enter a positive quantity to subtract';
                } else if (adjustQty > currentStock) {
                    isValid = false;
                    errorMsg = 'Cannot subtract more than current stock (' + currentStock + ')';
                } else {
                    finalQty = currentStock - adjustQty;
                    changeQty = -adjustQty;
                }
            } else if (adjustType === 'SET') {
                if (newQty < 0) {
                    isValid = false;
                    errorMsg = 'New quantity cannot be negative';
                } else {
                    finalQty = newQty;
                    changeQty = newQty - currentStock;
                }
            }
            
            if (isValid) {
                var previewHtml = '<strong>Adjustment Preview:</strong><br>';
                previewHtml += 'Current Stock: <span class="text-info">' + currentStock + '</span><br>';
                previewHtml += 'Change: <span class="text-' + (changeQty >= 0 ? 'success">+' : 'warning">') + changeQty + '</span><br>';
                previewHtml += 'Final Stock: <span class="text-primary"><strong>' + finalQty + '</strong></span>';
                
                $("#adjustment_preview").removeClass('alert-danger').addClass('alert-info').html(previewHtml);
                $("#preview_group").show();
                $("#btn_save_adjust").prop('disabled', false);
            } else {
                $("#adjustment_preview").removeClass('alert-info').addClass('alert-danger').html('<strong>Error:</strong> ' + errorMsg);
                $("#preview_group").show();
                $("#btn_save_adjust").prop('disabled', true);
            }
        }

        function resetAdjustmentForm() {
            $("#adjust_type").val('');
            $("#adjust_qty").val('');
            $("#adjust_new_qty").val('');
            $("#adjust_reason").val('');
            $("#adjust_notes").val('');
            $("#adjust_qty_group").hide();
            $("#new_qty_group").hide();
            $("#preview_group").hide();
            $("#btn_save_adjust").prop('disabled', true);
        }

        function loadProductsForAdjustment() {
            // Load all active products for adjustment modal
            $.get("<?= base_url(); ?>data_product/search", {
                active_only: 1
            }, function(data) {
                if (data.indexOf("Error: ") === -1) {
                    var products = JSON.parse(data);
                    var options = '<option value="">Select Product</option>';
                    
                    $.each(products, function(i, product) {
                        options += '<option value="' + product.id + '">' + product.code + ' - ' + product.name + '</option>';
                    });
                    
                    $("#adjust_product_id").html(options);
                }
            });
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
                    
                    // Show only latest 5 transactions
                    var recentMovements = movements.slice(0, 5);
                    
                    // Desktop table view
                    var desktopHtml = '<div class="movement-history-desktop">';
                    desktopHtml += '<div class="table-responsive"><table class="table table-striped table-sm">';
                    desktopHtml += '<thead><tr>';
                    desktopHtml += '<th>Date</th>';
                    desktopHtml += '<th>Created At</th>';
                    desktopHtml += '<th>Type</th>';
                    desktopHtml += '<th>Qty</th>';
                    desktopHtml += '<th>Transfer From</th>';
                    desktopHtml += '<th>Transfer To</th>';
                    desktopHtml += '<th>Reference</th>';
                    desktopHtml += '<th>Notes</th>';
                    desktopHtml += '</tr></thead><tbody>';
                    
                    $.each(recentMovements, function(i, mov) {
                        var movementDate = formatDate(mov.date || mov.created_at);
                        var transferFrom = mov.transfer_from_location || '-';
                        var transferTo = mov.transfer_to_location || '-';
                        
                        desktopHtml += '<tr>';
                        desktopHtml += '<td>' + movementDate + '</td>';
                        desktopHtml += '<td>' + formatDateTime(mov.created_at) + '</td>';
                        desktopHtml += '<td><span class="label label-' + getMovementColor(mov.movement_type) + '">' + mov.movement_type + '</span></td>';
                        desktopHtml += '<td>' + mov.qty + '</td>';
                        desktopHtml += '<td>' + transferFrom + '</td>';
                        desktopHtml += '<td>' + transferTo + '</td>';
                        desktopHtml += '<td>' + mov.reference_type + (mov.reference_id ? ' #' + mov.reference_id : '') + '</td>';
                        desktopHtml += '<td>' + (mov.notes || '') + '</td>';
                        desktopHtml += '</tr>';
                    });
                    
                    desktopHtml += '</tbody></table></div></div>';
                    
                    // Mobile card view
                    var mobileHtml = '<div class="movement-history-mobile" style="display: none;">';
                    
                    if (recentMovements.length === 0) {
                        mobileHtml += '<div class="text-center" style="padding: 20px; color: #666;">No movement history found</div>';
                    } else {
                        $.each(recentMovements, function(i, mov) {
                            var movementDate = formatDate(mov.date || mov.created_at);
                            var movementTime = formatDateTime(mov.created_at).split(' ')[1] || '';
                            var transferInfo = '';
                            
                            if (mov.movement_type === 'TRANSFER' && mov.transfer_from_location && mov.transfer_to_location) {
                                transferInfo = `
                                    <div class="movement-detail-row">
                                        <span class="movement-detail-label">Transfer:</span>
                                        <span class="movement-detail-value">${mov.transfer_from_location} → ${mov.transfer_to_location}</span>
                                    </div>
                                `;
                            }
                            
                            var notesSection = '';
                            if (mov.notes && mov.notes.trim()) {
                                notesSection = `<div class="movement-detail-notes">Notes: ${mov.notes}</div>`;
                            }
                            
                            mobileHtml += `
                                <div class="movement-detail-card">
                                    <div class="movement-detail-header">
                                        <div class="movement-detail-title">${mov.movement_type}</div>
                                        <span class="movement-detail-badge ${getMovementBadgeClass(mov.movement_type)}">${mov.qty}</span>
                                    </div>
                                    <div class="movement-detail-body">
                                        <div class="movement-detail-row">
                                            <span class="movement-detail-label">Date:</span>
                                            <span class="movement-detail-value">${movementDate}</span>
                                        </div>
                                        <div class="movement-detail-row">
                                            <span class="movement-detail-label">Time:</span>
                                            <span class="movement-detail-value">${movementTime}</span>
                                        </div>
                                        ${transferInfo}
                                        <div class="movement-detail-row">
                                            <span class="movement-detail-label">Reference:</span>
                                            <span class="movement-detail-value">${mov.reference_type} ${mov.reference_id ? '#' + mov.reference_id : ''}</span>
                                        </div>
                                        ${notesSection}
                                    </div>
                                </div>
                            `;
                        });
                    }
                    
                    mobileHtml += '</div>';
                    
                    // Combined HTML with responsive CSS
                    var modalContent = `
                        <style>
                            @media (max-width: 768px) {
                                .movement-history-desktop { display: none !important; }
                                .movement-history-mobile { display: block !important; }
                                
                                .movement-detail-card {
                                    background: white;
                                    border: 1px solid #ddd;
                                    border-radius: 8px;
                                    margin-bottom: 15px;
                                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                    overflow: hidden;
                                }
                                
                                .movement-detail-header {
                                    padding: 12px 15px;
                                    border-bottom: 1px solid #ddd;
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    background: #f8f9fa;
                                }
                                
                                .movement-detail-title {
                                    font-weight: bold;
                                    font-size: 16px;
                                    color: #333;
                                }
                                
                                .movement-detail-badge {
                                    padding: 4px 12px;
                                    border-radius: 12px;
                                    font-size: 14px;
                                    font-weight: bold;
                                }
                                
                                .movement-detail-body {
                                    padding: 15px;
                                }
                                
                                .movement-detail-row {
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    padding: 6px 0;
                                    border-bottom: 1px solid #f0f0f0;
                                }
                                
                                .movement-detail-row:last-child {
                                    border-bottom: none;
                                }
                                
                                .movement-detail-label {
                                    font-weight: 600;
                                    color: #555;
                                    font-size: 13px;
                                }
                                
                                .movement-detail-value {
                                    font-size: 13px;
                                    color: #333;
                                    text-align: right;
                                }
                                
                                .movement-detail-notes {
                                    background: #f8f9fa;
                                    padding: 10px;
                                    border-radius: 5px;
                                    margin-top: 10px;
                                    font-style: italic;
                                    color: #666;
                                    font-size: 12px;
                                }
                                
                                .badge-receive { background: #28a745; color: white; }
                                .badge-release { background: #dc3545; color: white; }
                                .badge-transfer { background: #17a2b8; color: white; }
                                .badge-adjustment { background: #ffc107; color: #212529; }
                                .badge-reserve { background: #6c757d; color: white; }
                            }
                            
                            @media (min-width: 769px) {
                                .movement-history-mobile { display: none !important; }
                            }
                        </style>
                        ${desktopHtml}
                        ${mobileHtml}
                        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd; color: #666; font-size: 12px;">
                            <i class="fa fa-info-circle"></i> Showing latest 5 transactions only
                        </div>
                    `;
                    
                    bootbox.dialog({
                        title: "Stock Movement History",
                        message: modalContent,
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
        }        function getMovementBadgeClass(type) {
            var classes = {
                'RECEIVE': 'badge-receive',
                'RELEASE': 'badge-release', 
                'TRANSFER': 'badge-transfer',
                'ADJUSTMENT': 'badge-adjustment',
                'RESERVE': 'badge-reserve'
            };
            return classes[type] || 'badge-secondary';
        }

        // New report functions
        function viewExpiringStock() {
            var location_id = $("#filter_location").val() || 0;
            var days_ahead = prompt("Show products expiring in how many days?", "30");
            
            if (days_ahead === null) return; // User cancelled
            if (!days_ahead || isNaN(days_ahead) || days_ahead < 1) {
                toastr.error("Please enter a valid number of days");
                return;
            }

            $.get("<?= base_url(); ?>inventory_stock/get_expiring_stock", {
                location_id: location_id,
                days_ahead: days_ahead
            }, function(data) {
                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    toastr.error(data);
                } else {
                    try {
                        var expiringData = JSON.parse(data);
                        showExpiringStockModal(expiringData, days_ahead);
                    } catch (e) {
                        toastr.error("Error parsing response data");
                    }
                }
            });
        }

        function viewExpiredStock() {
            var location_id = $("#filter_location").val() || 0;

            $.get("<?= base_url(); ?>inventory_stock/get_expired_stock", {
                location_id: location_id
            }, function(data) {
                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    toastr.error(data);
                } else {
                    try {
                        var expiredData = JSON.parse(data);
                        showExpiredStockModal(expiredData);
                    } catch (e) {
                        toastr.error("Error parsing response data");
                    }
                }
            });
        }

        function viewStockValuation() {
            var location_id = $("#filter_location").val() || 0;

            $.get("<?= base_url(); ?>inventory_stock/get_stock_valuation", {
                location_id: location_id
            }, function(data) {
                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    toastr.error(data);
                } else {
                    try {
                        var valuationData = JSON.parse(data);
                        showStockValuationModal(valuationData);
                    } catch (e) {
                        toastr.error("Error parsing response data");
                    }
                }
            });
        }

        function showExpiringStockModal(data, daysAhead) {
            var modal = $(`
                <div class="modal fade" id="modal_expiring_stock" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Stock Expiring in ${daysAhead} Days</h4>
                            </div>
                            <div class="modal-body">
                                <div style="max-height: 400px; overflow-y: auto;">
                                    ${generateExpiringStockTable(data)}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" onclick="exportExpiringStock(${daysAhead})">Export to Excel</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            
            // Remove any existing modal
            $("#modal_expiring_stock").remove();
            $("body").append(modal);
            $("#modal_expiring_stock").modal();
        }

        function showExpiredStockModal(data) {
            var modal = $(`
                <div class="modal fade" id="modal_expired_stock" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Expired Stock Items</h4>
                            </div>
                            <div class="modal-body">
                                <div style="max-height: 400px; overflow-y: auto;">
                                    ${generateExpiredStockTable(data)}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            
            // Remove any existing modal
            $("#modal_expired_stock").remove();
            $("body").append(modal);
            $("#modal_expired_stock").modal();
        }

        function showStockValuationModal(data) {
            var modal = $(`
                <div class="modal fade" id="modal_stock_valuation" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Stock Valuation Report</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <strong>Total Stock Value: ${currency_symbol}${parseFloat(data.total_value).toLocaleString('en-US', {minimumFractionDigits: 2})}</strong>
                                </div>
                                <div style="max-height: 400px; overflow-y: auto;">
                                    ${generateValuationTable(data.items)}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            
            // Remove any existing modal
            $("#modal_stock_valuation").remove();
            $("body").append(modal);
            $("#modal_stock_valuation").modal();
        }

        function generateExpiringStockTable(data) {
            if (data.length === 0) {
                return '<p class="text-center text-muted">No expiring stock found.</p>';
            }

            var html = `
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Location</th>
                            <th>Qty</th>
                            <th>Unit Cost</th>
                            <th>Total Value</th>
                            <th>Expiry Date</th>
                            <th>Days Left</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.forEach(function(item) {
                var statusClass = '';
                var statusText = '';
                if (item.days_until_expiry < 0) {
                    statusClass = 'text-danger';
                    statusText = 'EXPIRED';
                } else if (item.days_until_expiry <= 7) {
                    statusClass = 'text-danger';
                    statusText = 'CRITICAL';
                } else if (item.days_until_expiry <= 30) {
                    statusClass = 'text-warning';
                    statusText = 'WARNING';
                } else {
                    statusClass = 'text-info';
                    statusText = 'NOTICE';
                }

                var totalValue = (parseFloat(item.qty_on_hand) * parseFloat(item.unit_cost || 0)).toFixed(2);

                html += `
                    <tr>
                        <td><strong>${item.product_name}</strong><br><small>${item.product_code}</small></td>
                        <td>${item.location}</td>
                        <td>${parseFloat(item.qty_on_hand).toFixed(2)}</td>
                        <td>${currency_symbol}${parseFloat(item.unit_cost || 0).toFixed(2)}</td>
                        <td>${currency_symbol}${totalValue}</td>
                        <td>${item.expiration_date_formatted}</td>
                        <td class="${statusClass}">${item.days_until_expiry}</td>
                        <td><span class="label label-${getStatusLabelClass(statusText)} ${statusClass}">${statusText}</span></td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            return html;
        }

        function generateExpiredStockTable(data) {
            if (data.length === 0) {
                return '<p class="text-center text-success">No expired stock found.</p>';
            }

            var html = `
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Location</th>
                            <th>Qty</th>
                            <th>Unit Cost</th>
                            <th>Total Value</th>
                            <th>Expiry Date</th>
                            <th>Days Expired</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.forEach(function(item) {
                var totalValue = (parseFloat(item.qty_on_hand) * parseFloat(item.unit_cost || 0)).toFixed(2);

                html += `
                    <tr class="danger">
                        <td><strong>${item.product_name}</strong><br><small>${item.product_code}</small></td>
                        <td>${item.location}</td>
                        <td>${parseFloat(item.qty_on_hand).toFixed(2)}</td>
                        <td>${currency_symbol}${parseFloat(item.unit_cost || 0).toFixed(2)}</td>
                        <td>${currency_symbol}${totalValue}</td>
                        <td>${item.expiration_date_formatted}</td>
                        <td class="text-danger">${item.days_expired}</td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            return html;
        }

        function generateValuationTable(data) {
            if (data.length === 0) {
                return '<p class="text-center text-muted">No stock data found.</p>';
            }

            var html = `
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Location</th>
                            <th>Qty On Hand</th>
                            <th>Unit Cost</th>
                            <th>Total Value</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.forEach(function(item) {
                html += `
                    <tr>
                        <td><strong>${item.product_name}</strong><br><small>${item.product_code}</small></td>
                        <td>${item.location}</td>
                        <td>${parseFloat(item.qty_on_hand).toFixed(2)}</td>
                        <td>${currency_symbol}${parseFloat(item.unit_cost || 0).toFixed(2)}</td>
                        <td>${currency_symbol}${parseFloat(item.stock_value).toFixed(2)}</td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            return html;
        }

        function getStatusLabelClass(status) {
            switch(status) {
                case 'EXPIRED': return 'danger';
                case 'CRITICAL': return 'danger';
                case 'WARNING': return 'warning';
                case 'NOTICE': return 'info';
                default: return 'default';
            }
        }

        function exportExpiringStock(daysAhead) {
            var location_id = $("#filter_location").val() || 0;
            window.open("<?= base_url(); ?>inventory_stock/export_expiring_stock?location_id=" + location_id + "&days_ahead=" + daysAhead, '_blank');
        }
    </script>

</body>

</html>
