<?php
defined('BASEPATH') or exit('No direct script access allowed');
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

        /* Mobile Responsive Styles */
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
        
        /* Bootbox styling */
        .bootbox-success .modal-header {
            background-color: #5cb85c;
            color: white;
        }
        
        .bootbox-error .modal-header {
            background-color: #d9534f;
            color: white;
        }
        
        .bootbox .modal-dialog {
            margin-top: 100px;
        }
        
        .bootbox textarea {
            min-height: 80px;
            resize: vertical;
        }
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
                margin-bottom: 10px;
            }

            /* Ensure form controls have proper spacing on mobile */
            .form-control {
                margin-bottom: 5px;
            }
            
            .input-group {
                margin-bottom: 10px;
            }

            /* Hide export buttons container on mobile since they won't work properly */
            .tableTools-container {
                display: none !important;
            }
            
            .batch-card {
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 8px;
                margin-bottom: 15px;
                padding: 15px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            .batch-header {
                border-bottom: 1px solid #eee;
                padding-bottom: 10px;
                margin-bottom: 10px;
            }
            .batch-number {
                font-weight: bold;
                color: #2e8b57;
                font-size: 16px;
            }
            .batch-type {
                display: inline-block;
                padding: 4px 8px;
                border-radius: 4px;
                color: #fff;
                font-size: 12px;
                margin-left: 10px;
            }
            .batch-info-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 5px;
                font-size: 13px;
            }
            .batch-info-label {
                font-weight: 600;
                color: #666;
                flex: 0 0 40%;
            }
            .batch-info-value {
                color: #333;
                flex: 1;
                text-align: right;
            }
            .batch-totals {
                background: #f8f9fa;
                padding: 10px;
                border-radius: 4px;
                margin: 10px 0;
            }
            .batch-actions {
                text-align: center;
                margin-top: 10px;
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

        /* Form Layout Styles */
        .search-filters .form-control {
            margin-bottom: 5px;
        }
        
        .search-filters .input-group {
            width: 100%;
        }
        
        .search-filters .input-group-addon {
            padding: 6px 8px;
            font-size: 12px;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
        }
        
        /* Responsive form adjustments */
        @media (max-width: 991px) {
            .search-filters [class*="col-"] {
                margin-bottom: 10px;
            }
        }

        /* Status Badge Styles */
        .status-completed { background-color: #28a745; }
        .status-cancelled { background-color: #dc3545; }
        .status-draft { background-color: #6c757d; }
        
        /* Datepicker styling */
        .datepicker {
            background-color: white !important;
        }
        
        .ui-datepicker {
            font-size: 12px;
        }

        /* Transaction Type Badge Styles */
        .type-receive { background-color: #28a745; }
        .type-release { background-color: #dc3545; }
        .type-transfer { background-color: #17a2b8; }

        /* Modal Styles */
        .modal-xl {
            width: 95%;
            max-width: 1200px;
        }

        .item-row {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .item-row:last-child {
            border-bottom: none;
        }

        /* Print Button Styles */
        .tableTools-container .btn {
            margin-right: 5px;
        }

        /* Form Styles */
        .form-actions {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
        }

        /* Loading Styles */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        /* Highlight new/updated rows */
        .highlight-row {
            background-color: #d4edda !important;
            animation: fadeHighlight 3s ease-out;
        }

        @keyframes fadeHighlight {
            from { background-color: #d4edda; }
            to { background-color: transparent; }
        }

        /* Force date inputs to show YYYY-MM-DD format */
        input[type="date"] {
            min-width: 150px;
        }
        
        /* Ensure consistent date display across all browsers */
        input[type="date"]::-webkit-datetime-edit-text {
            color: #333;
        }
        
        input[type="date"]::-webkit-datetime-edit-month-field,
        input[type="date"]::-webkit-datetime-edit-day-field,
        input[type="date"]::-webkit-datetime-edit-year-field {
            color: #333;
        }
        
        /* Show placeholder when empty */
        input[type="date"]:invalid::-webkit-datetime-edit {
            color: #999;
        }
        
        input[type="date"]:focus::-webkit-datetime-edit {
            color: #333 !important;
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

                                    <!-- Search and Filter Section -->
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
                                                    <div class="widget-main search-filters">
                                                        <!-- First Row: Search and Type filters -->
                                                        <div class="row">
                                                            <div class="col-sm-4 col-md-4">
                                                                <label>Search:</label>
                                                                <input type="text" id="search_text" class="form-control" placeholder="Transaction number, remarks...">
                                                            </div>
                                                            <div class="col-sm-2 col-md-2">
                                                                <label>Type:</label>
                                                                <select id="filter_type" class="form-control chosen-select">
                                                                    <option value="">All Types</option>
                                                                    <option value="RECEIVE">Receive</option>
                                                                    <option value="RELEASE">Release</option>
                                                                    <option value="TRANSFER">Transfer</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-2 col-md-2">
                                                                <label>Status:</label>
                                                                <select id="filter_status" class="form-control chosen-select">
                                                                    <option value="">All Status</option>
                                                                    <option value="COMPLETED">Completed</option>
                                                                    <option value="CANCELLED">Cancelled</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4 col-md-4">
                                                                <label>Location:</label>
                                                                <select id="filter_location" class="form-control chosen-select">
                                                                    <option value="">All Locations</option>
                                                                    <?php foreach ($locations as $location): ?>
                                                                        <option value="<?= $location->id ?>"><?= $location->location ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Second Row: Date Range -->
                                                        <div class="row" style="margin-top: 10px;">
                                                            <div class="col-sm-6 col-md-6">
                                                                <label>Date Range:</label>
                                                                <div class="input-group">
                                                                    <input type="text" id="date_from" class="form-control datepicker" placeholder="YYYY-MM-DD" readonly>
                                                                    <span class="input-group-addon">to</span>
                                                                    <input type="text" id="date_to" class="form-control datepicker" placeholder="YYYY-MM-DD" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6 col-md-6">
                                                                <!-- Empty column for spacing -->
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Action Buttons Row -->
                                                        <div class="row" style="margin-top: 15px;">
                                                            <div class="col-sm-12">
                                                                <button type="button" id="btn_search" class="btn btn-sm btn-primary">
                                                                    <i class="ace-icon fa fa-search"></i> Search
                                                                </button>
                                                                <button type="button" id="btn_reset" class="btn btn-sm btn-warning">
                                                                    <i class="ace-icon fa fa-refresh"></i> Reset
                                                                </button>
                                                                <?php if ($this->cf->module_permission("add", $module_permission)): ?>
                                                                    <button type="button" id="btn_new_batch" class="btn btn-sm btn-success">
                                                                        <i class="ace-icon fa fa-plus"></i> New Batch Transaction
                                                                    </button>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Results Section -->
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="widget-box">
                                                <div class="widget-header">
                                                    <h4 class="widget-title">
                                                        <i class="ace-icon fa fa-list"></i>
                                                        Batch Transactions
                                                        <span id="lbl_result_info" class="badge badge-warning"></span>
                                                    </h4>
                                                    <div class="widget-toolbar">
                                                        <div class="pull-right tableTools-container"></div>
                                                    </div>
                                                </div>
                                                <div class="widget-body">
                                                    <div class="widget-main no-padding">
                                                        <!-- Desktop Table View -->
                                                        <div class="table-responsive">
                                                            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                                                <thead class="thin-border-bottom">
                                                                    <tr>
                                                                        <th>Transaction #</th>
                                                                        <th>Date</th>
                                                                        <th>Type</th>
                                                                        <th>From Location</th>
                                                                        <th>To Location</th>
                                                                        <th>Items</th>
                                                                        <th>Total Qty</th>
                                                                        <th>Status</th>
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
                                                                <?php if ($this->cf->module_permission("add", $module_permission)): ?>
                                                                    <button type="button" id="mobile_btn_new_batch" class="btn btn-sm btn-success">
                                                                        <i class="ace-icon fa fa-plus"></i> New Batch
                                                                    </button>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div id="mobile-cards-container">
                                                                <!-- Mobile cards will be populated here -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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

    </div><!-- /.main-container -->

    <!-- New Batch Modal -->
    <div id="modal_new_batch" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="blue bigger">
                        <i class="ace-icon fa fa-plus"></i>
                        Create New Batch Transaction
                    </h4>
                </div>
                <div class="modal-body">
                    <form id="form_new_batch">
                        <!-- Header Information -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Transaction Date <span class="text-danger">*</span></label>
                                    <input type="text" id="nb_transaction_date" class="form-control datepicker" required readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Transaction Type <span class="text-danger">*</span></label>
                                    <select id="nb_transaction_type" class="form-control chosen-select" required>
                                        <option value="">Select Type</option>
                                        <option value="RECEIVE">Receive Stock</option>
                                        <option value="RELEASE">Release Stock</option>
                                        <option value="TRANSFER">Transfer Stock</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6" id="nb_from_location_group" style="display: none;">
                                <div class="form-group">
                                    <label>From Location</label>
                                    <select id="nb_from_location" class="form-control chosen-select">
                                        <option value="">Select Location</option>
                                        <?php foreach ($locations as $location): ?>
                                            <option value="<?= $location->id ?>"><?= $location->location ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6" id="nb_to_location_group" style="display: none;">
                                <div class="form-group">
                                    <label>To Location</label>
                                    <select id="nb_to_location" class="form-control chosen-select">
                                        <option value="">Select Location</option>
                                        <?php foreach ($locations as $location): ?>
                                            <option value="<?= $location->id ?>"><?= $location->location ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Remarks</label>
                                    <textarea id="nb_remarks" class="form-control" rows="2" placeholder="Optional remarks about this batch transaction"></textarea>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <!-- Items Section -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h5><strong>Transaction Items <span class="text-danger">*</span></strong></h5>
                                
                                <!-- Add Item Row -->
                                <div class="well well-sm">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Product <span class="text-danger">*</span></label>
                                                <select id="nb_product_id" class="form-control chosen-select-products">
                                                    <option value="">Select Product</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label>UOM</label>
                                                <input type="text" id="nb_uom" class="form-control" readonly placeholder="UOM">
                                                <small class="help-block">Unit of Measure</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label>Quantity <span class="text-danger">*</span></label>
                                                <input type="number" id="nb_qty" class="form-control" min="0.01" step="0.01" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label>Unit Cost</label>
                                                <input type="number" id="nb_unit_cost" class="form-control" min="0" step="0.01" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Expiration Date</label>
                                                <input type="text" id="nb_expiration_date" class="form-control datepicker" placeholder="yyyy-mm-dd" readonly>
                                                <small class="help-block">Optional</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Notes</label>
                                                <input type="text" id="nb_notes" class="form-control" placeholder="Optional">
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" id="btn_add_item" class="btn btn-success" style="width: 100%;">
                                                    <i class="ace-icon fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Items Table -->
                                <div class="table-responsive">
                                    <table id="items_table" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Product Code</th>
                                                <th>Product Name</th>
                                                <th>UOM</th>
                                                <th class="text-right">Quantity</th>
                                                <th class="text-right">Unit Cost</th>
                                                <th class="text-right">Total Cost</th>
                                                <th>Expiration Date</th>
                                                <th>Notes</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="items_table_body">
                                            <tr id="no_items_row">
                                                <td colspan="9" class="text-center text-muted">No items added yet</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i> Cancel
                    </button>
                    <button type="button" id="btn_save_batch" class="btn btn-sm btn-primary">
                        <i class="ace-icon fa fa-check"></i> Confirm
                    </button>
                    <button type="button" id="btn_save_and_print_batch" class="btn btn-sm btn-success">
                        <i class="ace-icon fa fa-print"></i> Confirm & Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Batch Details Modal -->
    <div id="modal_batch_details" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="blue bigger">
                        <i class="ace-icon fa fa-list"></i>
                        Batch Transaction Details
                    </h4>
                </div>
                <div class="modal-body">
                    <div id="batch_details_content">
                        <!-- Content will be loaded dynamically -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Scripts -->
    <?php
    $this->load->view('template/script');
    ?>

    <script>
        const base_url = "<?= base_url() ?>";
        const currency_symbol = "<?= $currency_symbol ?? '₱' ?>";
        const currency_code = "<?= $currency_code ?? 'PHP' ?>";
        let oTable1;
        let currentBatchId = null;

        // Date formatting function to ensure yyyy-mm-dd format
        function formatDate(dateString) {
            if (!dateString) return '';
            
            // If it's already in yyyy-mm-dd format, return as is
            if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
                return dateString;
            }
            
            // Try to parse and format the date
            try {
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return dateString; // Return original if invalid
                
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                
                return `${year}-${month}-${day}`;
            } catch (e) {
                return dateString; // Return original if parsing fails
            }
        }

        // DateTime formatting function for timestamps
        function formatDateTime(dateTimeString) {
            if (!dateTimeString) return '';
            
            try {
                const date = new Date(dateTimeString);
                if (isNaN(date.getTime())) return dateTimeString;
                
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                
                return `${year}-${month}-${day} ${hours}:${minutes}`;
            } catch (e) {
                return dateTimeString;
            }
        }

        $(document).ready(function() {
            // Set default date to today using YYYY-MM-DD format for datepickers
            const today = new Date();
            const todayStr = today.toISOString().split('T')[0];
            const thirtyDaysAgo = new Date(Date.now() - 30*24*60*60*1000).toISOString().split('T')[0];
            
            $('#nb_transaction_date').val(todayStr);
            $('#date_from').val(thirtyDaysAgo);
            $('#date_to').val(todayStr);
            
            // Initialize datepickers (they will automatically use YYYY-MM-DD format)
            $('.datepicker').datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                yearRange: "c-5:c+5"
            });

            // Initialize DataTable
            initializeDataTable();

            // Initialize chosen dropdowns
            $('.chosen-select').chosen({
                allow_single_deselect: true,
                no_results_text: "No results match",
                width: "100%"
            });

            // Initial search
            searchBatches();

            // Event handlers
            $('#btn_search, #mobile_btn_search').on('click', searchBatches);
            $('#btn_reset').on('click', resetFilters);
            $('#btn_new_batch, #mobile_btn_new_batch').on('click', showNewBatchModal);
            $('#btn_save_batch').on('click', saveBatch);
            $('#btn_save_and_print_batch').on('click', saveAndPrintBatch);
            $('#btn_add_item').on('click', addItem);

            // Transaction type change handler
            $('#nb_transaction_type').on('change', handleTransactionTypeChange);

            // Product selection change handler - display UOM
            $('#nb_product_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const uom = selectedOption.data('uom') || '';
                $('#nb_uom').val(uom);
            });

            // Keyboard navigation for item form fields
            $('#nb_product_id').on('keydown', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    $('#nb_qty').focus().select();
                }
            });

            $('#nb_qty').on('keydown', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    $('#nb_unit_cost').focus().select();
                }
            });

            $('#nb_unit_cost').on('keydown', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    $('#nb_expiration_date').focus();
                }
            });

            $('#nb_expiration_date').on('keydown', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    $('#nb_notes').focus().select();
                }
            });

            $('#nb_notes').on('keydown', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    addItem();
                }
            });

            // Form submission
            $('#form_new_batch').on('submit', function(e) {
                e.preventDefault();
                saveBatch();
            });

            // Allow adding item with Enter key (kept for backward compatibility)
            $('#nb_qty, #nb_unit_cost, #nb_expiration_date, #nb_notes').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                    addItem();
                }
            });

            // Auto-search on input
            $('#search_text').on('keyup', debounce(searchBatches, 500));
            $('#filter_type, #filter_status, #filter_location').on('change', searchBatches);
            $('#date_from, #date_to').on('change', searchBatches);

            // Mobile responsive check
            $(window).on('resize', checkMobileView);
            checkMobileView();
        });

        function initializeDataTable() {
            if ($.fn.DataTable.isDataTable('#dynamic-table')) {
                $('#dynamic-table').DataTable().destroy();
            }

            oTable1 = $('#dynamic-table').DataTable({
                responsive: false,
                paging: true,
                searching: false,
                ordering: true,
                info: true,
                autoWidth: false,
                pageLength: 25,
                order: [[1, "desc"]], // Order by date descending
                columnDefs: [
                    { targets: [5, 6], className: "text-right" },
                    { targets: [8], orderable: false }
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="ace-icon fa fa-file-excel-o bigger-110 green"></i> <span class="hidden">Excel</span>',
                        className: 'btn btn-white btn-primary btn-bold',
                        titleAttr: 'Export to Excel',
                        title: 'Batch Transactions Report',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="ace-icon fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">PDF</span>',
                        className: 'btn btn-white btn-primary btn-bold',
                        titleAttr: 'Export to PDF',
                        title: 'Batch Transactions Report',
                        orientation: 'landscape',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="ace-icon fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span>',
                        className: 'btn btn-white btn-primary btn-bold',
                        titleAttr: 'Print Report',
                        title: 'Batch Transactions Report',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    }
                ]
            });

            // Append buttons to container
            oTable1.buttons().container().appendTo($('.tableTools-container'));
        }

        function searchBatches() {
            const search = $('#search_text').val();
            const transaction_type = $('#filter_type').val();
            const status = $('#filter_status').val();
            const location_id = $('#filter_location').val();
            const date_from = $('#date_from').val();
            const date_to = $('#date_to').val();

            $.ajax({
                url: base_url + 'inventory_batch/get_batch_list',
                type: 'POST',
                data: {
                    search: search,
                    transaction_type: transaction_type,
                    status: status,
                    location_id: location_id,
                    date_from: date_from,
                    date_to: date_to
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#lbl_result_info').text('Loading...');
                },
                success: function(response) {
                    if (response.success) {
                        populateTable(response.data);
                        populateMobileCards(response.data);
                        $('#lbl_result_info').text(response.data.length + ' records found');
                    } else {
                        $('#lbl_result_info').text('Error loading data');
                        toastr.error(response.message || 'Failed to load batch transactions');
                    }
                },
                error: function(xhr, status, error) {
                    $('#lbl_result_info').text('Error loading data');
                    toastr.error('Failed to load batch transactions');
                }
            });
        }

        function populateTable(data) {
            oTable1.clear();

            $.each(data, function(i, row) {
                const statusBadge = getStatusBadge(row.status);
                const typeBadge = getTypeBadge(row.transaction_type);
                const actions = getActionButtons(row);

                const fromLocation = row.from_location || '-';
                const toLocation = row.to_location || '-';

                oTable1.row.add([
                    row.transaction_number,
                    formatDate(row.transaction_date),
                    typeBadge,
                    fromLocation,
                    toLocation,
                    row.total_items || 0,
                    parseFloat(row.total_qty || 0).toFixed(2),
                    statusBadge,
                    actions
                ]);
            });

            oTable1.draw();
        }

        function populateMobileCards(data) {
            const container = $('#mobile-cards-container');
            container.empty();

            if (data.length === 0) {
                container.html('<div class="text-center" style="padding: 20px; color: #666;">No batch transactions found</div>');
                return;
            }

            $.each(data, function(i, row) {
                const statusBadge = getStatusBadge(row.status);
                const typeBadge = getTypeBadge(row.transaction_type);
                const actions = getMobileActionButtons(row);

                const fromLocation = row.from_location || 'N/A';
                const toLocation = row.to_location || 'N/A';

                const cardHtml = `
                    <div class="batch-card">
                        <div class="batch-header">
                            <span class="batch-number">${row.transaction_number}</span>
                            ${typeBadge}
                            ${statusBadge}
                        </div>
                        <div class="batch-info-row">
                            <span class="batch-info-label">Date:</span>
                            <span class="batch-info-value">${formatDate(row.transaction_date)}</span>
                        </div>
                        <div class="batch-info-row">
                            <span class="batch-info-label">From:</span>
                            <span class="batch-info-value">${fromLocation}</span>
                        </div>
                        <div class="batch-info-row">
                            <span class="batch-info-label">To:</span>
                            <span class="batch-info-value">${toLocation}</span>
                        </div>
                        <div class="batch-totals">
                            <div class="batch-info-row">
                                <span class="batch-info-label">Items:</span>
                                <span class="batch-info-value">${row.total_items || 0}</span>
                            </div>
                            <div class="batch-info-row">
                                <span class="batch-info-label">Total Qty:</span>
                                <span class="batch-info-value">${parseFloat(row.total_qty || 0).toFixed(2)}</span>
                            </div>
                        </div>
                        <div class="batch-actions">
                            ${actions}
                        </div>
                    </div>
                `;

                container.append(cardHtml);
            });
        }

        function getStatusBadge(status) {
            const statusClass = 'status-' + status.toLowerCase();
            return `<span class="label ${statusClass}">${status}</span>`;
        }

        function getTypeBadge(type) {
            const typeClass = 'type-' + type.toLowerCase();
            return `<span class="label ${typeClass}">${type}</span>`;
        }

        function getActionButtons(row) {
            let buttons = `
                <button type="button" class="btn btn-xs btn-info" onclick="viewBatchDetails(${row.id})" title="View Details">
                    <i class="ace-icon fa fa-eye"></i>
                </button>
            `;

            if (row.status === 'DRAFT' || row.status === 'COMPLETED') {
                <?php if ($role_id == 1 || $this->custom_function->module_permission("modify", $module_permission)): ?>
                buttons += `
                    <button type="button" class="btn btn-xs btn-danger" onclick="cancelBatch(${row.id})" title="Cancel Batch">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                `;
                <?php endif; ?>
            }

            if (row.status === 'COMPLETED') {
                <?php if ($role_id == 1 || $this->custom_function->module_permission("print", $module_permission)): ?>
                buttons += `
                    <button type="button" class="btn btn-xs btn-purple" onclick="printBatch(${row.id})" title="Print">
                        <i class="ace-icon fa fa-print"></i>
                    </button>
                `;
                <?php endif; ?>
            }

            return `<div class="btn-group">${buttons}</div>`;
        }

        function getMobileActionButtons(row) {
            let buttons = `
                <button type="button" class="btn btn-xs btn-info" onclick="viewBatchDetails(${row.id})">
                    <i class="ace-icon fa fa-eye"></i> View
                </button>
            `;

            if (row.status === 'DRAFT' || row.status === 'COMPLETED') {
                <?php if ($role_id == 1 || $this->custom_function->module_permission("modify", $module_permission)): ?>
                buttons += `
                    <button type="button" class="btn btn-xs btn-danger" onclick="cancelBatch(${row.id})">
                        <i class="ace-icon fa fa-times"></i> Cancel
                    </button>
                `;
                <?php endif; ?>
            }

            if (row.status === 'COMPLETED') {
                <?php if ($role_id == 1 || $this->custom_function->module_permission("print", $module_permission)): ?>
                buttons += `
                    <button type="button" class="btn btn-xs btn-purple" onclick="printBatch(${row.id})">
                        <i class="ace-icon fa fa-print"></i> Print
                    </button>
                `;
                <?php endif; ?>
            }

            return buttons;
        }

        function showNewBatchModal() {
            // Reset form
            $('#form_new_batch')[0].reset();
            $('#nb_transaction_date').val(new Date().toISOString().split('T')[0]);
            
            // Reset chosen dropdowns
            $('.chosen-select').val('').trigger('chosen:updated');
            
            // Hide location groups
            $('#nb_from_location_group, #nb_to_location_group').hide();
            
            // Clear items table
            clearItemsTable();
            
            // Load products
            loadProducts();
            
            $('#modal_new_batch').modal('show');
        }

        function handleTransactionTypeChange() {
            const type = $('#nb_transaction_type').val();
            
            // Reset location selections
            $('#nb_from_location, #nb_to_location').val('').trigger('chosen:updated');
            
            // Show/hide location groups based on transaction type
            if (type === 'RECEIVE') {
                $('#nb_from_location_group').hide();
                $('#nb_to_location_group').show();
            } else if (type === 'RELEASE') {
                $('#nb_from_location_group').show();
                $('#nb_to_location_group').hide();
            } else if (type === 'TRANSFER') {
                $('#nb_from_location_group').show();
                $('#nb_to_location_group').show();
            } else {
                $('#nb_from_location_group, #nb_to_location_group').hide();
            }
        }

        function saveBatch() {
            // Validate basic fields
            if (!$('#nb_transaction_date').val() || !$('#nb_transaction_type').val()) {
                toastr.warning('Please fill in all required fields');
                return;
            }

            // Get items from table
            const items = getItemsFromTable();
            if (items.length === 0) {
                toastr.warning('Please add at least one item to the transaction');
                return;
            }

            const formData = {
                transaction_date: $('#nb_transaction_date').val(),
                transaction_type: $('#nb_transaction_type').val(),
                from_location_id: $('#nb_from_location').val(),
                to_location_id: $('#nb_to_location').val(),
                remarks: $('#nb_remarks').val(),
                items: items
            };

            $.ajax({
                url: base_url + 'inventory_batch/create_batch_with_items',
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                dataType: 'json',
                beforeSend: function() {
                    $('#btn_save_batch').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Creating...');
                    $('#btn_save_and_print_batch').prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#modal_new_batch').modal('hide');
                        searchBatches();
                    } else {
                        toastr.error(response.message || 'Failed to create batch transaction');
                    }
                },
                error: function(xhr, status, error) {
                    let errorMsg = 'Failed to create batch transaction';
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        errorMsg = errorResponse.message || errorMsg;
                    } catch (e) {
                        if (xhr.responseText) {
                            errorMsg += ': ' + xhr.responseText.substring(0, 200);
                        }
                    }
                    toastr.error(errorMsg);
                },
                complete: function() {
                    $('#btn_save_batch').prop('disabled', false).html('<i class="ace-icon fa fa-check"></i> Confirm');
                    $('#btn_save_and_print_batch').prop('disabled', false).html('<i class="ace-icon fa fa-print"></i> Confirm & Print');
                }
            });
        }

        function saveAndPrintBatch() {
            // Validate basic fields
            if (!$('#nb_transaction_date').val() || !$('#nb_transaction_type').val()) {
                toastr.warning('Please fill in all required fields');
                return;
            }

            // Get items from table
            const items = getItemsFromTable();
            if (items.length === 0) {
                toastr.warning('Please add at least one item to the transaction');
                return;
            }

            const formData = {
                transaction_date: $('#nb_transaction_date').val(),
                transaction_type: $('#nb_transaction_type').val(),
                from_location_id: $('#nb_from_location').val(),
                to_location_id: $('#nb_to_location').val(),
                remarks: $('#nb_remarks').val(),
                items: items
            };

            $.ajax({
                url: base_url + 'inventory_batch/create_batch_with_items',
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                dataType: 'json',
                beforeSend: function() {
                    $('#btn_save_batch').prop('disabled', true);
                    $('#btn_save_and_print_batch').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Creating & Printing...');
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#modal_new_batch').modal('hide');
                        searchBatches();
                        
                        // Automatically print the batch
                        if (response.batch_id) {
                            setTimeout(function() {
                                printBatch(response.batch_id);
                            }, 500); // Small delay to ensure modal is hidden and data refreshed
                        }
                    } else {
                        toastr.error(response.message || 'Failed to create batch transaction');
                    }
                },
                error: function(xhr, status, error) {
                    let errorMsg = 'Failed to create batch transaction';
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        errorMsg = errorResponse.message || errorMsg;
                    } catch (e) {
                        if (xhr.responseText) {
                            errorMsg += ': ' + xhr.responseText.substring(0, 200);
                        }
                    }
                    toastr.error(errorMsg);
                },
                complete: function() {
                    $('#btn_save_batch').prop('disabled', false).html('<i class="ace-icon fa fa-check"></i> Confirm');
                    $('#btn_save_and_print_batch').prop('disabled', false).html('<i class="ace-icon fa fa-print"></i> Confirm & Print');
                }
            });
        }

        // Items Management Functions
        let itemsCounter = 0;

        function loadProducts() {
            // First test the diagnostic endpoint
            $.ajax({
                url: base_url + 'inventory_batch/test_products',
                type: 'GET',
                dataType: 'json',
                success: function(testResult) {
                    // If diagnostic test passes, load actual products
                    if (testResult.success) {
                        loadActualProducts();
                    } else {
                        toastr.error('Database connection issue: ' + (testResult.error || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Failed to connect to server for diagnostic test');
                    
                    // Try loading products anyway
                    loadActualProducts();
                }
            });
        }

        function loadActualProducts() {
            $.ajax({
                url: base_url + 'inventory_batch/get_products',
                type: 'GET',
                dataType: 'json',
                success: function(products) {
                    if (products.error) {
                        toastr.error('Error loading products: ' + products.error);
                        return;
                    }
                    
                    const productSelect = $('#nb_product_id');
                    productSelect.empty().append('<option value="">Select Product</option>');
                    
                    $.each(products, function(i, product) {
                        productSelect.append(`<option value="${product.id}" data-code="${product.product_code}" data-name="${product.product_name}" data-uom="${product.uom || ''}" data-cost="${product.cost || 0}">${product.product_code} - ${product.product_name}</option>`);
                    });
                    
                    // Refresh chosen
                    $('.chosen-select-products').chosen({
                        allow_single_deselect: true,
                        no_results_text: "No products match",
                        width: "100%"
                    });
                    
                    // Add keyboard navigation for Chosen dropdown
                    $('#nb_product_id').on('chosen:hiding_dropdown', function(evt, params) {
                        // Small delay to allow Chosen to complete its hiding process
                        setTimeout(function() {
                            if ($('#nb_product_id').val()) {
                                $('#nb_qty').focus().select();
                            }
                        }, 50);
                    });
                },
                error: function(xhr, status, error) {
                    let errorMsg = 'Failed to load products';
                    if (xhr.responseText) {
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            errorMsg += ': ' + (errorResponse.error || errorResponse.message || 'Unknown error');
                        } catch (e) {
                            if (xhr.responseText.includes('<!DOCTYPE html>')) {
                                errorMsg += ': Server returned HTML instead of JSON (possible PHP error)';
                            } else {
                                errorMsg += ': ' + xhr.responseText.substring(0, 100);
                            }
                        }
                    }
                    toastr.error(errorMsg);
                }
            });
        }

        function clearItemsTable() {
            $('#items_table_body').html('<tr id="no_items_row"><td colspan="9" class="text-center text-muted">No items added yet</td></tr>');
            updateTotals();
            itemsCounter = 0;
        }

        function addItem() {
            const productId = $('#nb_product_id').val();
            const qty = parseFloat($('#nb_qty').val()) || 0;
            const unitCost = parseFloat($('#nb_unit_cost').val()) || 0;
            const expirationDate = $('#nb_expiration_date').val();
            const notes = $('#nb_notes').val();

            if (!productId || qty <= 0) {
                toastr.warning('Please select a product and enter valid quantity');
                return;
            }

            // Get product data
            const selectedOption = $('#nb_product_id option:selected');
            const productCode = selectedOption.data('code');
            const productName = selectedOption.data('name');
            const uom = selectedOption.data('uom') || '';

            // Check if product already exists
            if ($(`#item_row_${productId}`).length > 0) {
                toastr.warning('Product already added. Please remove it first to add again.');
                return;
            }

            // Calculate total cost
            const totalCost = qty * unitCost;

            // Format expiration date display
            let expirationDisplay = '';
            if (expirationDate) {
                const expDate = new Date(expirationDate);
                const today = new Date();
                const diffTime = expDate - today;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                let expirationClass = '';
                if (diffDays < 0) {
                    expirationClass = 'text-danger';
                    expirationDisplay = `<span class="${expirationClass}">${expirationDate} (EXPIRED)</span>`;
                } else if (diffDays <= 30) {
                    expirationClass = 'text-warning';
                    expirationDisplay = `<span class="${expirationClass}">${expirationDate} (${diffDays} days)</span>`;
                } else {
                    expirationDisplay = expirationDate;
                }
            } else {
                expirationDisplay = '<span class="text-muted">No expiry</span>';
            }

            // Remove "no items" row
            $('#no_items_row').remove();

            // Add new row with new columns
            const rowHtml = `
                <tr id="item_row_${productId}" data-product-id="${productId}" data-unit-cost="${unitCost}" data-expiration="${expirationDate}">
                    <td>${productCode}</td>
                    <td>${productName}</td>
                    <td>${uom}</td>
                    <td class="text-right">${qty.toFixed(2)}</td>
                    <td class="text-right">${currency_symbol}${unitCost.toFixed(2)}</td>
                    <td class="text-right">${currency_symbol}${totalCost.toFixed(2)}</td>
                    <td>${expirationDisplay}</td>
                    <td>${notes}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-xs btn-danger" onclick="removeItem(${productId})" title="Remove">
                            <i class="ace-icon fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            $('#items_table_body').append(rowHtml);

            // Clear form
            $('#nb_product_id').val('').trigger('chosen:updated');
            $('#nb_uom').val('');
            $('#nb_qty').val('');
            $('#nb_unit_cost').val('');
            $('#nb_expiration_date').val('');
            $('#nb_notes').val('');

            updateTotals();
            itemsCounter++;
            
            // Return focus to product field for continuous data entry
            setTimeout(function() {
                $('#nb_product_id').trigger('chosen:activate');
            }, 100);
        }

        function removeItem(productId) {
            $(`#item_row_${productId}`).remove();
            
            // If no items left, show "no items" row
            if ($('#items_table_body tr').length === 0) {
                $('#items_table_body').html('<tr id="no_items_row"><td colspan="9" class="text-center text-muted">No items added yet</td></tr>');
            }
            
            updateTotals();
        }

        function updateTotals() {
            // Calculate total cost and quantity from all items
            let totalQty = 0;
            let totalCost = 0;
            
            $('#items_table_body tr[data-product-id]').each(function() {
                const qty = parseFloat($(this).find('td:eq(3)').text()) || 0;
                const unitCost = parseFloat($(this).data('unit-cost')) || 0;
                totalQty += qty;
                totalCost += (qty * unitCost);
            });
            
            // Update summary if needed (you can add a summary section if desired)
            console.log('Total Quantity:', totalQty.toFixed(2));
            console.log('Total Cost:', totalCost.toFixed(2));
        }

        function getItemsFromTable() {
            const items = [];
            
            $('#items_table_body tr[data-product-id]').each(function() {
                const productId = $(this).data('product-id');
                const qty = parseFloat($(this).find('td:eq(3)').text()) || 0;
                const unitCost = parseFloat($(this).data('unit-cost')) || 0;
                const expirationDate = $(this).data('expiration') || '';
                const notes = $(this).find('td:eq(7)').text();
                
                items.push({
                    product_id: productId,
                    qty: qty,
                    unit_cost: unitCost,
                    expiration_date: expirationDate,
                    notes: notes
                });
            });
            
            return items;
        }

        function viewBatchDetails(batchId) {
            $.ajax({
                url: base_url + 'inventory_batch/get_batch_details',
                type: 'GET',
                data: { batch_id: batchId },
                dataType: 'json',
                beforeSend: function() {
                    $('#batch_details_content').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
                    $('#modal_batch_details').modal('show');
                },
                success: function(data) {
                    if (data.error) {
                        $('#batch_details_content').html('<div class="alert alert-danger">' + data.error + '</div>');
                        return;
                    }

                    // Build batch details HTML
                    let html = buildBatchDetailsHTML(data.batch, data.items);
                    $('#batch_details_content').html(html);
                },
                error: function(xhr, status, error) {
                    $('#batch_details_content').html('<div class="alert alert-danger">Failed to load batch details</div>');
                }
            });
        }

        function buildBatchDetailsHTML(batch, items) {
            const statusBadge = getStatusBadge(batch.status);
            const typeBadge = getTypeBadge(batch.transaction_type);
            
            let html = `
                <div class="row">
                    <div class="col-sm-6">
                        <h5><strong>Batch Information</strong></h5>
                        <table class="table table-borderless">
                            <tr><td><strong>Transaction Number:</strong></td><td>${batch.transaction_number}</td></tr>
                            <tr><td><strong>Date:</strong></td><td>${formatDate(batch.transaction_date)}</td></tr>
                            <tr><td><strong>Type:</strong></td><td>${typeBadge}</td></tr>
                            <tr><td><strong>Status:</strong></td><td>${statusBadge}</td></tr>
                            <tr><td><strong>Created By:</strong></td><td>${batch.created_by_name || '-'}</td></tr>
                            <tr><td><strong>Created At:</strong></td><td>${formatDateTime(batch.created_at)}</td></tr>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <h5><strong>Location Information</strong></h5>
                        <table class="table table-borderless">
                            <tr><td><strong>From Location:</strong></td><td>${batch.from_location || 'N/A'}</td></tr>
                            <tr><td><strong>To Location:</strong></td><td>${batch.to_location || 'N/A'}</td></tr>
                        </table>
                        <h5><strong>Summary</strong></h5>
                        <table class="table table-borderless">
                            <tr><td><strong>Total Items:</strong></td><td>${batch.total_items || 0}</td></tr>
                            <tr><td><strong>Total Quantity:</strong></td><td>${parseFloat(batch.total_qty || 0).toFixed(2)}</td></tr>
                        </table>
                    </div>
                </div>
            `;

            if (batch.remarks) {
                html += `
                    <div class="row">
                        <div class="col-sm-12">
                            <h5><strong>Remarks</strong></h5>
                            <p class="well">${batch.remarks}</p>
                        </div>
                    </div>
                `;
            }

            html += `
                <div class="row">
                    <div class="col-sm-12">
                        <h5><strong>Items (${items.length})</strong></h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>UOM</th>
                                        <th class="text-right">Quantity</th>
                                        <th class="text-right">Unit Cost</th>
                                        <th class="text-right">Total Cost</th>
                                        <th>Expiration Date</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
            `;

            if (items.length === 0) {
                html += '<tr><td colspan="9" class="text-center">No items in this batch</td></tr>';
            } else {
                $.each(items, function(i, item) {
                    // Format expiration date display
                    let expirationDisplay = '';
                    if (item.expiration_date) {
                        const expDate = new Date(item.expiration_date);
                        const today = new Date();
                        const diffTime = expDate - today;
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        
                        if (item.expiration_status === 'expired') {
                            expirationDisplay = `<span class="text-danger">${item.expiration_date_formatted} (EXPIRED)</span>`;
                        } else if (item.expiration_status === 'expiring_soon') {
                            expirationDisplay = `<span class="text-warning">${item.expiration_date_formatted} (EXPIRING)</span>`;
                        } else {
                            expirationDisplay = item.expiration_date_formatted;
                        }
                    } else {
                        expirationDisplay = '<span class="text-muted">No expiry</span>';
                    }

                    const unitCost = parseFloat(item.unit_cost || 0);
                    const totalCost = parseFloat(item.qty) * unitCost;

                    html += `
                        <tr>
                            <td>${item.product_code}</td>
                            <td>${item.product_name}</td>
                            <td>${item.category || '-'}</td>
                            <td>${item.uom || '-'}</td>
                            <td class="text-right">${parseFloat(item.qty).toFixed(2)}</td>
                            <td class="text-right">${currency_symbol}${unitCost.toFixed(2)}</td>
                            <td class="text-right">${currency_symbol}${totalCost.toFixed(2)}</td>
                            <td>${expirationDisplay}</td>
                            <td>${item.notes || '-'}</td>
                        </tr>
                    `;
                });
            }

            html += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;

            return html;
        }

        function cancelBatch(batchId) {
            bootbox.prompt({
                title: 'Cancel Batch Transaction?',
                message: 'Please enter the reason for cancelling this batch transaction:',
                inputType: 'textarea',
                placeholder: 'Enter reason for cancelling this batch...',
                callback: function(reason) {
                    if (reason !== null && reason.trim() !== '') {
                        $.ajax({
                            url: base_url + 'inventory_batch/cancel_batch',
                            type: 'POST',
                            data: { 
                                batch_id: batchId,
                                reason: reason.trim(),
                                [csrf_name]: csrf_hash
                            },
                            success: function(response) {
                                if (response.includes('Success:')) {
                                    bootbox.alert({
                                        message: response,
                                        className: 'bootbox-success',
                                        callback: function() {
                                            searchBatches();
                                        }
                                    });
                                } else {
                                    bootbox.alert({
                                        message: response,
                                        className: 'bootbox-error'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                bootbox.alert({
                                    message: 'Failed to cancel batch transaction. Please try again.',
                                    className: 'bootbox-error'
                                });
                            }
                        });
                    } else if (reason !== null) {
                        bootbox.alert({
                            message: 'Cancellation reason is required.',
                            className: 'bootbox-error'
                        });
                    }
                }
            });
        }

        function printBatch(batchId) {
            // Show format selection dialog
            bootbox.dialog({
                title: 'Print Batch Transaction',
                message: '<p>Choose the print format for this batch transaction:</p>',
                buttons: {
                    html: {
                        label: '<i class="fa fa-file-text"></i> HTML Print',
                        className: 'btn-primary',
                        callback: function() {
                            window.open(base_url + 'inventory_batch/print_batch?batch_id=' + batchId + '&format=html', '_blank');
                        }
                    },
                    pdf: {
                        label: '<i class="fa fa-file-pdf-o"></i> PDF Format',
                        className: 'btn-success',
                        callback: function() {
                            window.open(base_url + 'inventory_batch/print_batch?batch_id=' + batchId + '&format=pdf', '_blank');
                        }
                    },
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-default'
                    }
                }
            });
        }

        function editBatch(batchId) {
            // Redirect to edit page or open edit modal
            // For now, just show details
            viewBatchDetails(batchId);
        }

        function resetFilters() {
            $('#search_text').val('');
            $('#filter_type, #filter_status, #filter_location').val('').trigger('chosen:updated');
            
            // Reset date fields with proper format for datepickers
            const thirtyDaysAgo = new Date(Date.now() - 30*24*60*60*1000).toISOString().split('T')[0];
            const today = new Date().toISOString().split('T')[0];
            $('#date_from').val(thirtyDaysAgo);
            $('#date_to').val(today);
            
            searchBatches();
        }

        function checkMobileView() {
            if ($(window).width() <= 768) {
                $('.mobile-card-container').show();
                $('.table-responsive').hide();
            } else {
                $('.mobile-card-container').hide();
                $('.table-responsive').show();
            }
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Mobile swipe actions could be added here if needed
    </script>
</body>
</html>
