<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title><?= $app_title ?> - <?= $page_name ?></title>
    
    <meta name="description" content="<?= $module_description ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- Bootstrap and core CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/fonts.googleapis.com.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/dataTables.bootstrap.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/buttons.bootstrap.min.css" />
    
    <!-- Chosen CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/chosen.min.css" />
    
    <!-- DateTimePicker CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datetimepicker.min.css" />
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/sweetalert2.min.css" />

    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .mobile-card-container {
                display: block !important;
            }
            .table-responsive {
                display: none !important;
            }
            .mobile-action-buttons {
                margin-bottom: 15px;
                text-align: center;
            }
            .mobile-action-buttons .btn {
                margin: 2px;
            }
            .batch-card {
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 8px;
                margin-bottom: 15px;
                padding: 15px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
            .batch-actions .btn {
                margin: 2px;
                padding: 4px 8px;
                font-size: 11px;
            }
        }

        @media (min-width: 769px) {
            .mobile-card-container {
                display: none !important;
            }
            .table-responsive {
                display: block !important;
            }
        }

        /* Status Badge Styles */
        .status-draft { background-color: #6c757d; }
        .status-processing { background-color: #17a2b8; }
        .status-completed { background-color: #28a745; }
        .status-cancelled { background-color: #dc3545; }

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
    </style>
</head>

<body class="no-skin">
    <!-- Main Container -->
    <div id="navbar" class="navbar navbar-default ace-save-state">
        <div class="navbar-container ace-save-state" id="navbar-container">
            <div class="navbar-header pull-left">
                <a href="<?= base_url() ?>dashboard" class="navbar-brand">
                    <small>
                        <i class="fa fa-cubes"></i>
                        <?= $app_name ?>
                    </small>
                </a>
            </div>

            <div class="navbar-buttons navbar-header pull-right" role="navigation">
                <ul class="nav ace-nav">
                    <li class="light-blue dropdown-modal">
                        <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                            <span class="user-info">
                                <small>Welcome,</small>
                                <?= $uname ?>
                            </span>
                            <i class="ace-icon fa fa-caret-down"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-container ace-save-state" id="main-container">
        <div class="main-content">
            <div class="main-content-inner">
                <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li>
                            <i class="ace-icon fa fa-home home-icon"></i>
                            <a href="<?= base_url() ?>dashboard">Home</a>
                        </li>
                        <li>
                            <a href="#"><?= $parent_menu ?></a>
                        </li>
                        <li class="active"><?= $page_name ?></li>
                    </ul>
                </div>

                <div class="page-content">
                    <div class="page-header">
                        <h1>
                            <?= $page_name ?>
                            <small>
                                <i class="ace-icon fa fa-angle-double-right"></i>
                                Create and manage batch inventory transactions
                            </small>
                        </h1>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <!-- Search and Filter Section -->
                            <div class="widget-box widget-color-blue2">
                                <div class="widget-header">
                                    <h4 class="widget-title lighter">
                                        <i class="ace-icon fa fa-search"></i>
                                        Search & Filter
                                    </h4>
                                    <div class="widget-toolbar">
                                        <a href="#" data-action="collapse">
                                            <i class="ace-icon fa fa-chevron-up"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="control-label">Search:</label>
                                                    <input type="text" id="search_text" class="form-control" placeholder="Transaction number, remarks...">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label class="control-label">Type:</label>
                                                    <select id="filter_type" class="form-control chosen-select">
                                                        <option value="">All Types</option>
                                                        <option value="RECEIVE">Receive</option>
                                                        <option value="RELEASE">Release</option>
                                                        <option value="TRANSFER">Transfer</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label class="control-label">Status:</label>
                                                    <select id="filter_status" class="form-control chosen-select">
                                                        <option value="">All Status</option>
                                                        <option value="DRAFT">Draft</option>
                                                        <option value="PROCESSING">Processing</option>
                                                        <option value="COMPLETED">Completed</option>
                                                        <option value="CANCELLED">Cancelled</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label class="control-label">Location:</label>
                                                    <select id="filter_location" class="form-control chosen-select">
                                                        <option value="">All Locations</option>
                                                        <?php foreach ($locations as $location): ?>
                                                            <option value="<?= $location->id ?>"><?= $location->location ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="control-label">Date Range:</label>
                                                    <div class="input-group">
                                                        <input type="date" id="date_from" class="form-control">
                                                        <span class="input-group-addon">to</span>
                                                        <input type="date" id="date_to" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="button" id="btn_search" class="btn btn-info">
                                                <i class="ace-icon fa fa-search"></i> Search
                                            </button>
                                            <button type="button" id="btn_reset" class="btn btn-warning">
                                                <i class="ace-icon fa fa-refresh"></i> Reset
                                            </button>
                                            <?php if ($this->cf->module_permission("add", $module_permission)): ?>
                                                <button type="button" id="btn_new_batch" class="btn btn-success">
                                                    <i class="ace-icon fa fa-plus"></i> New Batch Transaction
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Results Section -->
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
                                    <div class="widget-main">
                                        <!-- Desktop Table View -->
                                        <div class="table-responsive">
                                            <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Transaction #</th>
                                                        <th>Date</th>
                                                        <th>Type</th>
                                                        <th>From Location</th>
                                                        <th>To Location</th>
                                                        <th>Items</th>
                                                        <th>Total Qty</th>
                                                        <th>Total Cost</th>
                                                        <th>Status</th>
                                                        <th>Created By</th>
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
        </div>
    </div>

    <!-- New Batch Modal -->
    <div id="modal_new_batch" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="blue bigger">
                        <i class="ace-icon fa fa-plus"></i>
                        Create New Batch Transaction
                    </h4>
                </div>
                <div class="modal-body">
                    <form id="form_new_batch" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Transaction Date <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="date" id="nb_transaction_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Transaction Type <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select id="nb_transaction_type" class="form-control chosen-select" required>
                                    <option value="">Select Type</option>
                                    <option value="RECEIVE">Receive Stock</option>
                                    <option value="RELEASE">Release Stock</option>
                                    <option value="TRANSFER">Transfer Stock</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="nb_from_location_group" style="display: none;">
                            <label class="col-sm-3 control-label">From Location</label>
                            <div class="col-sm-9">
                                <select id="nb_from_location" class="form-control chosen-select">
                                    <option value="">Select Location</option>
                                    <?php foreach ($locations as $location): ?>
                                        <option value="<?= $location->id ?>"><?= $location->location ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="nb_to_location_group" style="display: none;">
                            <label class="col-sm-3 control-label">To Location</label>
                            <div class="col-sm-9">
                                <select id="nb_to_location" class="form-control chosen-select">
                                    <option value="">Select Location</option>
                                    <?php foreach ($locations as $location): ?>
                                        <option value="<?= $location->id ?>"><?= $location->location ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Remarks</label>
                            <div class="col-sm-9">
                                <textarea id="nb_remarks" class="form-control" rows="3" placeholder="Optional remarks about this batch transaction"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i> Cancel
                    </button>
                    <button type="button" id="btn_save_batch" class="btn btn-sm btn-primary">
                        <i class="ace-icon fa fa-check"></i> Create Batch
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
    <script src="<?= base_url() ?>assets/js/jquery.2.1.1.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
    
    <!-- DataTables -->
    <script src="<?= base_url() ?>assets/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>assets/js/dataTables.bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/js/dataTables.buttons.min.js"></script>
    <script src="<?= base_url() ?>assets/js/buttons.bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/js/buttons.html5.min.js"></script>
    <script src="<?= base_url() ?>assets/js/buttons.print.min.js"></script>
    <script src="<?= base_url() ?>assets/js/jszip.min.js"></script>
    <script src="<?= base_url() ?>assets/js/pdfmake.min.js"></script>
    <script src="<?= base_url() ?>assets/js/vfs_fonts.js"></script>
    
    <!-- Chosen -->
    <script src="<?= base_url() ?>assets/js/chosen.jquery.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="<?= base_url() ?>assets/js/sweetalert2.min.js"></script>
    
    <!-- Ace scripts -->
    <script src="<?= base_url() ?>assets/js/ace-extra.min.js"></script>
    <script src="<?= base_url() ?>assets/js/ace.min.js"></script>

    <script>
        const base_url = "<?= base_url() ?>";
        let oTable1;
        let currentBatchId = null;

        $(document).ready(function() {
            // Set default date to today
            $('#nb_transaction_date').val(new Date().toISOString().split('T')[0]);
            $('#date_from').val(new Date(Date.now() - 30*24*60*60*1000).toISOString().split('T')[0]); // 30 days ago
            $('#date_to').val(new Date().toISOString().split('T')[0]); // Today

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

            // Transaction type change handler
            $('#nb_transaction_type').on('change', handleTransactionTypeChange);

            // Form submission
            $('#form_new_batch').on('submit', function(e) {
                e.preventDefault();
                saveBatch();
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
                    { targets: [5, 6, 7], className: "text-right" },
                    { targets: [10], orderable: false }
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
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
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
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="ace-icon fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span>',
                        className: 'btn btn-white btn-primary btn-bold',
                        titleAttr: 'Print Report',
                        title: 'Batch Transactions Report',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
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
                url: base_url + 'inventory_batch/search',
                type: 'GET',
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
                success: function(data) {
                    populateTable(data);
                    populateMobileCards(data);
                    $('#lbl_result_info').text(data.length + ' records found');
                },
                error: function(xhr, status, error) {
                    console.error('Search error:', error);
                    $('#lbl_result_info').text('Error loading data');
                    Swal.fire('Error', 'Failed to load batch transactions', 'error');
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
                const totalCost = row.total_cost ? parseFloat(row.total_cost).toFixed(2) : '0.00';

                oTable1.row.add([
                    row.transaction_number,
                    row.transaction_date,
                    typeBadge,
                    fromLocation,
                    toLocation,
                    row.total_items || 0,
                    parseFloat(row.total_qty || 0).toFixed(2),
                    totalCost,
                    statusBadge,
                    row.created_by_name || '-',
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
                const totalCost = row.total_cost ? parseFloat(row.total_cost).toFixed(2) : '0.00';

                const cardHtml = `
                    <div class="batch-card">
                        <div class="batch-header">
                            <span class="batch-number">${row.transaction_number}</span>
                            ${typeBadge}
                            ${statusBadge}
                        </div>
                        <div class="batch-info-row">
                            <span class="batch-info-label">Date:</span>
                            <span class="batch-info-value">${row.transaction_date}</span>
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
                            <div class="batch-info-row">
                                <span class="batch-info-label">Total Cost:</span>
                                <span class="batch-info-value">₱${totalCost}</span>
                            </div>
                        </div>
                        <div class="batch-info-row">
                            <span class="batch-info-label">Created By:</span>
                            <span class="batch-info-value">${row.created_by_name || '-'}</span>
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

            if (row.status === 'DRAFT') {
                buttons += `
                    <button type="button" class="btn btn-xs btn-primary" onclick="editBatch(${row.id})" title="Edit">
                        <i class="ace-icon fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-xs btn-success" onclick="processBatch(${row.id})" title="Process">
                        <i class="ace-icon fa fa-play"></i>
                    </button>
                    <button type="button" class="btn btn-xs btn-danger" onclick="cancelBatch(${row.id})" title="Cancel">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                `;
            }

            if (row.status === 'COMPLETED') {
                buttons += `
                    <button type="button" class="btn btn-xs btn-purple" onclick="printBatch(${row.id})" title="Print">
                        <i class="ace-icon fa fa-print"></i>
                    </button>
                `;
            }

            return `<div class="btn-group">${buttons}</div>`;
        }

        function getMobileActionButtons(row) {
            let buttons = `
                <button type="button" class="btn btn-xs btn-info" onclick="viewBatchDetails(${row.id})">
                    <i class="ace-icon fa fa-eye"></i> View
                </button>
            `;

            if (row.status === 'DRAFT') {
                buttons += `
                    <button type="button" class="btn btn-xs btn-primary" onclick="editBatch(${row.id})">
                        <i class="ace-icon fa fa-edit"></i> Edit
                    </button>
                    <button type="button" class="btn btn-xs btn-success" onclick="processBatch(${row.id})">
                        <i class="ace-icon fa fa-play"></i> Process
                    </button>
                `;
            }

            if (row.status === 'COMPLETED') {
                buttons += `
                    <button type="button" class="btn btn-xs btn-purple" onclick="printBatch(${row.id})">
                        <i class="ace-icon fa fa-print"></i> Print
                    </button>
                `;
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
            const formData = {
                transaction_date: $('#nb_transaction_date').val(),
                transaction_type: $('#nb_transaction_type').val(),
                from_location_id: $('#nb_from_location').val(),
                to_location_id: $('#nb_to_location').val(),
                remarks: $('#nb_remarks').val()
            };

            // Basic validation
            if (!formData.transaction_date || !formData.transaction_type) {
                Swal.fire('Validation Error', 'Please fill in all required fields', 'warning');
                return;
            }

            $.ajax({
                url: base_url + 'inventory_batch/create_batch',
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#btn_save_batch').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Creating...');
                },
                success: function(response) {
                    if (response.includes('Success:')) {
                        Swal.fire('Success', response, 'success');
                        $('#modal_new_batch').modal('hide');
                        searchBatches();
                    } else {
                        Swal.fire('Error', response, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Save error:', error);
                    Swal.fire('Error', 'Failed to create batch transaction', 'error');
                },
                complete: function() {
                    $('#btn_save_batch').prop('disabled', false).html('<i class="ace-icon fa fa-check"></i> Create Batch');
                }
            });
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
                    console.error('Details error:', error);
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
                            <tr><td><strong>Date:</strong></td><td>${batch.transaction_date}</td></tr>
                            <tr><td><strong>Type:</strong></td><td>${typeBadge}</td></tr>
                            <tr><td><strong>Status:</strong></td><td>${statusBadge}</td></tr>
                            <tr><td><strong>Created By:</strong></td><td>${batch.created_by_name || '-'}</td></tr>
                            <tr><td><strong>Created At:</strong></td><td>${batch.created_at}</td></tr>
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
                            <tr><td><strong>Total Cost:</strong></td><td>₱${parseFloat(batch.total_cost || 0).toFixed(2)}</td></tr>
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
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
            `;

            if (items.length === 0) {
                html += '<tr><td colspan="8" class="text-center">No items in this batch</td></tr>';
            } else {
                $.each(items, function(i, item) {
                    const unitCost = parseFloat(item.unit_cost || 0).toFixed(2);
                    const totalCost = parseFloat(item.total_cost || 0).toFixed(2);
                    
                    html += `
                        <tr>
                            <td>${item.product_code}</td>
                            <td>${item.product_name}</td>
                            <td>${item.category || '-'}</td>
                            <td>${item.uom || '-'}</td>
                            <td class="text-right">${parseFloat(item.qty).toFixed(2)}</td>
                            <td class="text-right">₱${unitCost}</td>
                            <td class="text-right">₱${totalCost}</td>
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

        function processBatch(batchId) {
            Swal.fire({
                title: 'Process Batch Transaction?',
                text: 'This will execute all inventory movements in this batch. This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, process it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: base_url + 'inventory_batch/process_batch',
                        type: 'POST',
                        data: { batch_id: batchId },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait while the batch is being processed',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response) {
                            if (response.includes('Success:')) {
                                Swal.fire('Success', response, 'success');
                                searchBatches();
                            } else {
                                Swal.fire('Error', response, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Process error:', error);
                            Swal.fire('Error', 'Failed to process batch transaction', 'error');
                        }
                    });
                }
            });
        }

        function cancelBatch(batchId) {
            Swal.fire({
                title: 'Cancel Batch Transaction?',
                input: 'textarea',
                inputLabel: 'Reason for cancellation',
                inputPlaceholder: 'Enter reason for cancelling this batch...',
                inputAttributes: {
                    'aria-label': 'Reason for cancellation'
                },
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: base_url + 'inventory_batch/cancel_batch',
                        type: 'POST',
                        data: { 
                            batch_id: batchId,
                            reason: result.value || 'No reason provided'
                        },
                        success: function(response) {
                            if (response.includes('Success:')) {
                                Swal.fire('Success', response, 'success');
                                searchBatches();
                            } else {
                                Swal.fire('Error', response, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Cancel error:', error);
                            Swal.fire('Error', 'Failed to cancel batch transaction', 'error');
                        }
                    });
                }
            });
        }

        function printBatch(batchId) {
            window.open(base_url + 'inventory_batch/print_batch?batch_id=' + batchId, '_blank');
        }

        function editBatch(batchId) {
            // Redirect to edit page or open edit modal
            // For now, just show details
            viewBatchDetails(batchId);
        }

        function resetFilters() {
            $('#search_text').val('');
            $('#filter_type, #filter_status, #filter_location').val('').trigger('chosen:updated');
            $('#date_from').val(new Date(Date.now() - 30*24*60*60*1000).toISOString().split('T')[0]);
            $('#date_to').val(new Date().toISOString().split('T')[0]);
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
