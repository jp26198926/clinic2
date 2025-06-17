<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title><?= $app_title ?> - Manage Batch Items</title>
    
    <meta name="description" content="Batch Transaction Item Management" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- Bootstrap and core CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/ace.min.css" />
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/dataTables.bootstrap.min.css" />
    
    <!-- Chosen CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/chosen.min.css" />
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/sweetalert2.min.css" />

    <style>
        .batch-header {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        
        .batch-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .batch-details h4 {
            margin: 0;
            color: #007bff;
        }
        
        .batch-meta {
            font-size: 14px;
            color: #666;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-draft { background-color: #6c757d; }
        .status-processing { background-color: #17a2b8; }
        .status-completed { background-color: #28a745; }
        .status-cancelled { background-color: #dc3545; }
        
        .type-badge {
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
            font-size: 11px;
            font-weight: bold;
        }
        
        .type-receive { background-color: #28a745; }
        .type-release { background-color: #dc3545; }
        .type-transfer { background-color: #17a2b8; }
        
        .summary-cards {
            margin-bottom: 20px;
        }
        
        .summary-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .summary-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        
        .summary-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }
        
        .action-buttons {
            margin-bottom: 20px;
        }
        
        .item-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .readonly-field {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        
        @media (max-width: 768px) {
            .batch-info {
                flex-direction: column;
                text-align: center;
            }
            
            .summary-cards .col-sm-3 {
                margin-bottom: 15px;
            }
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
                            <a href="#">Inventory</a>
                        </li>
                        <li>
                            <a href="<?= base_url() ?>inventory_batch">Batch Transactions</a>
                        </li>
                        <li class="active">Manage Items</li>
                    </ul>
                </div>

                <div class="page-content">
                    <div class="page-header">
                        <h1>
                            Manage Batch Items
                            <small>
                                <i class="ace-icon fa fa-angle-double-right"></i>
                                Add and manage items in batch transaction
                            </small>
                        </h1>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <!-- Batch Header -->
                            <div class="batch-header">
                                <div class="batch-info">
                                    <div class="batch-details">
                                        <h4 id="batch_number">Loading...</h4>
                                        <div class="batch-meta">
                                            <span id="batch_date"></span> | 
                                            <span id="batch_type_badge"></span> | 
                                            <span id="batch_status_badge"></span>
                                        </div>
                                        <div class="batch-meta" id="batch_locations"></div>
                                    </div>
                                    <div class="batch-actions">
                                        <a href="<?= base_url() ?>inventory_batch" class="btn btn-sm btn-default">
                                            <i class="ace-icon fa fa-arrow-left"></i> Back to List
                                        </a>
                                        <button type="button" id="btn_print_batch" class="btn btn-sm btn-purple" style="display: none;">
                                            <i class="ace-icon fa fa-print"></i> Print
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary Cards -->
                            <div class="row summary-cards">
                                <div class="col-sm-3">
                                    <div class="summary-card">
                                        <div class="summary-number" id="total_items">0</div>
                                        <div class="summary-label">Total Items</div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="summary-card">
                                        <div class="summary-number" id="total_quantity">0.00</div>
                                        <div class="summary-label">Total Quantity</div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="summary-card">
                                        <div class="summary-number" id="total_cost">0.00</div>
                                        <div class="summary-label">Total Cost</div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="summary-card">
                                        <button type="button" id="btn_process_batch" class="btn btn-success" style="display: none;">
                                            <i class="ace-icon fa fa-play"></i> Process Batch
                                        </button>
                                        <button type="button" id="btn_cancel_batch" class="btn btn-danger" style="display: none;">
                                            <i class="ace-icon fa fa-times"></i> Cancel Batch
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Item Form -->
                            <div class="item-form" id="item_form_container" style="display: none;">
                                <h5><i class="ace-icon fa fa-plus"></i> Add Item to Batch</h5>
                                <form id="form_add_item" class="form-horizontal">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Product <span class="text-danger">*</span></label>
                                                <select id="item_product_id" class="form-control chosen-select" required>
                                                    <option value="">Select Product</option>
                                                    <?php foreach ($products as $product): ?>
                                                        <option value="<?= $product->id ?>" data-code="<?= $product->code ?>" data-name="<?= $product->name ?>">
                                                            <?= $product->code ?> - <?= $product->name ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label class="control-label">Quantity <span class="text-danger">*</span></label>
                                                <input type="number" id="item_qty" class="form-control" step="0.01" min="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label class="control-label">Unit Cost</label>
                                                <input type="number" id="item_unit_cost" class="form-control" step="0.01" min="0" value="0">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label">Notes</label>
                                                <input type="text" id="item_notes" class="form-control" placeholder="Optional notes">
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label">&nbsp;</label>
                                                <button type="submit" class="btn btn-success form-control">
                                                    <i class="ace-icon fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="current_batch_id" value="">
                                    <input type="hidden" id="editing_item_id" value="">
                                </form>
                            </div>

                            <!-- Items Table -->
                            <div class="widget-box">
                                <div class="widget-header">
                                    <h4 class="widget-title">
                                        <i class="ace-icon fa fa-list"></i>
                                        Batch Items
                                        <span id="items_count_badge" class="badge badge-info">0</span>
                                    </h4>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="table-responsive">
                                            <table id="items-table" class="table table-striped table-bordered table-hover">
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
                                                        <th width="100">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="items_tbody">
                                                    <!-- Items will be loaded here -->
                                                </tbody>
                                            </table>
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

    <!-- Edit Item Modal -->
    <div id="modal_edit_item" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="blue bigger">
                        <i class="ace-icon fa fa-edit"></i>
                        Edit Item
                    </h4>
                </div>
                <div class="modal-body">
                    <form id="form_edit_item" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Product</label>
                            <div class="col-sm-9">
                                <input type="text" id="edit_product_display" class="form-control readonly-field" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Quantity <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" id="edit_item_qty" class="form-control" step="0.01" min="0.01" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Unit Cost</label>
                            <div class="col-sm-9">
                                <input type="number" id="edit_item_unit_cost" class="form-control" step="0.01" min="0" value="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Notes</label>
                            <div class="col-sm-9">
                                <input type="text" id="edit_item_notes" class="form-control" placeholder="Optional notes">
                            </div>
                        </div>
                        <input type="hidden" id="edit_item_id" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i> Cancel
                    </button>
                    <button type="button" id="btn_update_item" class="btn btn-sm btn-primary">
                        <i class="ace-icon fa fa-check"></i> Update Item
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
    
    <!-- Chosen -->
    <script src="<?= base_url() ?>assets/js/chosen.jquery.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="<?= base_url() ?>assets/js/sweetalert2.min.js"></script>
    
    <!-- Ace scripts -->
    <script src="<?= base_url() ?>assets/js/ace.min.js"></script>

    <script>
        const base_url = "<?= base_url() ?>";
        const currency_symbol = "<?= $currency_symbol ?? '₱' ?>";
        const currency_code = "<?= $currency_code ?? 'PHP' ?>";
        let currentBatch = null;
        let itemsTable;

        $(document).ready(function() {
            // Get batch ID from URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const batchId = urlParams.get('batch_id');
            
            if (!batchId) {
                Swal.fire('Error', 'No batch ID specified', 'error').then(() => {
                    window.location.href = base_url + 'inventory_batch';
                });
                return;
            }
            
            $('#current_batch_id').val(batchId);

            // Initialize components
            initializeDataTable();
            initializeChosenDropdowns();
            initializeCurrencyDisplay();
            loadBatchDetails(batchId);
            
            // Initialize currency display
            $('#total_cost').text(currency_symbol + '0.00');

            // Event handlers
            $('#form_add_item').on('submit', addItem);
            $('#btn_update_item').on('click', updateItem);
            $('#btn_process_batch').on('click', processBatch);
            $('#btn_cancel_batch').on('click', cancelBatch);
            $('#btn_print_batch').on('click', printBatch);

            // Auto-calculate total cost
            $('#item_qty, #item_unit_cost').on('input', calculateTotalCost);
            $('#edit_item_qty, #edit_item_unit_cost').on('input', calculateEditTotalCost);
        });

        function initializeDataTable() {
            itemsTable = $('#items-table').DataTable({
                responsive: true,
                paging: false,
                searching: true,
                ordering: true,
                info: false,
                autoWidth: false,
                columnDefs: [
                    { targets: [4, 5, 6], className: "text-right" },
                    { targets: [8], orderable: false }
                ]
            });
        }

        function initializeChosenDropdowns() {
            $('.chosen-select').chosen({
                allow_single_deselect: true,
                no_results_text: "No results match",
                width: "100%"
            });
        }

        function initializeCurrencyDisplay() {
            // Set initial currency display for total cost
            $('#total_cost').text(currency_symbol + '0.00');
        }

        function loadBatchDetails(batchId) {
            $.ajax({
                url: base_url + 'inventory_batch/get_batch_details',
                type: 'GET',
                data: { batch_id: batchId },
                dataType: 'json',
                beforeSend: function() {
                    $('#batch_number').text('Loading...');
                },
                success: function(data) {
                    if (data.error) {
                        Swal.fire('Error', data.error, 'error').then(() => {
                            window.location.href = base_url + 'inventory_batch';
                        });
                        return;
                    }

                    currentBatch = data.batch;
                    displayBatchInfo(data.batch);
                    loadItems(data.items);
                    updateSummary(data.batch);

                    // Show/hide controls based on status
                    if (data.batch.status === 'DRAFT') {
                        $('#item_form_container').show();
                        $('#btn_process_batch, #btn_cancel_batch').show();
                    } else if (data.batch.status === 'COMPLETED') {
                        $('#btn_print_batch').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Load error:', error);
                    Swal.fire('Error', 'Failed to load batch details', 'error').then(() => {
                        window.location.href = base_url + 'inventory_batch';
                    });
                }
            });
        }

        function displayBatchInfo(batch) {
            $('#batch_number').text(batch.transaction_number);
            $('#batch_date').text(batch.transaction_date);
            
            const typeBadge = `<span class="type-badge type-${batch.transaction_type.toLowerCase()}">${batch.transaction_type}</span>`;
            const statusBadge = `<span class="status-badge status-${batch.status.toLowerCase()}">${batch.status}</span>`;
            
            $('#batch_type_badge').html(typeBadge);
            $('#batch_status_badge').html(statusBadge);
            
            let locationText = '';
            if (batch.from_location || batch.to_location) {
                locationText = 'From: ' + (batch.from_location || 'N/A') + ' | To: ' + (batch.to_location || 'N/A');
            }
            $('#batch_locations').text(locationText);
        }

        function loadItems(items) {
            itemsTable.clear();

            $.each(items, function(i, item) {
                const actions = getItemActions(item);
                const unitCost = parseFloat(item.unit_cost || 0).toFixed(2);
                const totalCost = parseFloat(item.total_cost || 0).toFixed(2);

                itemsTable.row.add([
                    item.product_code,
                    item.product_name,
                    item.category || '-',
                    item.uom || '-',
                    parseFloat(item.qty).toFixed(2),
                    currency_symbol + unitCost,
                    currency_symbol + totalCost,
                    item.notes || '-',
                    actions
                ]);
            });

            itemsTable.draw();
            $('#items_count_badge').text(items.length);
        }

        function getItemActions(item) {
            let actions = '';
            
            if (currentBatch && currentBatch.status === 'DRAFT') {
                actions = `
                    <button type="button" class="btn btn-xs btn-warning" onclick="editItem(${item.id})" title="Edit">
                        <i class="ace-icon fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-xs btn-danger" onclick="deleteItem(${item.id})" title="Delete">
                        <i class="ace-icon fa fa-trash"></i>
                    </button>
                `;
            } else {
                actions = '<span class="text-muted">-</span>';
            }

            return actions;
        }

        function updateSummary(batch) {
            $('#total_items').text(batch.total_items || 0);
            $('#total_quantity').text(parseFloat(batch.total_qty || 0).toFixed(2));
            $('#total_cost').text(currency_symbol + parseFloat(batch.total_cost || 0).toFixed(2));
        }

        function addItem(e) {
            e.preventDefault();

            const formData = {
                batch_id: $('#current_batch_id').val(),
                product_id: $('#item_product_id').val(),
                qty: $('#item_qty').val(),
                unit_cost: $('#item_unit_cost').val(),
                notes: $('#item_notes').val()
            };

            // Validation
            if (!formData.product_id || !formData.qty || parseFloat(formData.qty) <= 0) {
                Swal.fire('Validation Error', 'Please select a product and enter a valid quantity', 'warning');
                return;
            }

            $.ajax({
                url: base_url + 'inventory_batch/add_item',
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#form_add_item button[type="submit"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success: function(response) {
                    if (response.includes('Success:')) {
                        Swal.fire('Success', response, 'success');
                        // Reset form
                        $('#form_add_item')[0].reset();
                        $('#item_product_id').val('').trigger('chosen:updated');
                        // Reload batch details
                        loadBatchDetails($('#current_batch_id').val());
                    } else {
                        Swal.fire('Error', response, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Add item error:', error);
                    Swal.fire('Error', 'Failed to add item', 'error');
                },
                complete: function() {
                    $('#form_add_item button[type="submit"]').prop('disabled', false).html('<i class="ace-icon fa fa-plus"></i>');
                }
            });
        }

        function editItem(itemId) {
            // Find item in current data
            const items = itemsTable.data().toArray();
            // Get item details via AJAX (implement this in controller if needed)
            // For now, we'll implement a simple edit modal
            
            $('#edit_item_id').val(itemId);
            $('#modal_edit_item').modal('show');
        }

        function updateItem() {
            const formData = {
                item_id: $('#edit_item_id').val(),
                qty: $('#edit_item_qty').val(),
                unit_cost: $('#edit_item_unit_cost').val(),
                notes: $('#edit_item_notes').val()
            };

            $.ajax({
                url: base_url + 'inventory_batch/update_item',
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#btn_update_item').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
                },
                success: function(response) {
                    if (response.includes('Success:')) {
                        Swal.fire('Success', response, 'success');
                        $('#modal_edit_item').modal('hide');
                        loadBatchDetails($('#current_batch_id').val());
                    } else {
                        Swal.fire('Error', response, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Update item error:', error);
                    Swal.fire('Error', 'Failed to update item', 'error');
                },
                complete: function() {
                    $('#btn_update_item').prop('disabled', false).html('<i class="ace-icon fa fa-check"></i> Update Item');
                }
            });
        }

        function deleteItem(itemId) {
            Swal.fire({
                title: 'Delete Item?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: base_url + 'inventory_batch/delete_item',
                        type: 'POST',
                        data: { item_id: itemId },
                        success: function(response) {
                            if (response.includes('Success:')) {
                                Swal.fire('Success', response, 'success');
                                loadBatchDetails($('#current_batch_id').val());
                            } else {
                                Swal.fire('Error', response, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Delete item error:', error);
                            Swal.fire('Error', 'Failed to delete item', 'error');
                        }
                    });
                }
            });
        }

        function processBatch() {
            Swal.fire({
                title: 'Process Batch Transaction?',
                text: 'This will execute all inventory movements. This action cannot be undone.',
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
                        data: { batch_id: $('#current_batch_id').val() },
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
                                Swal.fire('Success', response, 'success').then(() => {
                                    loadBatchDetails($('#current_batch_id').val());
                                });
                            } else {
                                Swal.fire('Error', response, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Process error:', error);
                            Swal.fire('Error', 'Failed to process batch', 'error');
                        }
                    });
                }
            });
        }

        function cancelBatch() {
            Swal.fire({
                title: 'Cancel Batch Transaction?',
                input: 'textarea',
                inputLabel: 'Reason for cancellation',
                inputPlaceholder: 'Enter reason for cancelling this batch...',
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
                            batch_id: $('#current_batch_id').val(),
                            reason: result.value || 'No reason provided'
                        },
                        success: function(response) {
                            if (response.includes('Success:')) {
                                Swal.fire('Success', response, 'success').then(() => {
                                    loadBatchDetails($('#current_batch_id').val());
                                });
                            } else {
                                Swal.fire('Error', response, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Cancel error:', error);
                            Swal.fire('Error', 'Failed to cancel batch', 'error');
                        }
                    });
                }
            });
        }

        function printBatch() {
            window.open(base_url + 'inventory_batch/print_batch?batch_id=' + $('#current_batch_id').val(), '_blank');
        }

        function calculateTotalCost() {
            const qty = parseFloat($('#item_qty').val() || 0);
            const unitCost = parseFloat($('#item_unit_cost').val() || 0);
            const total = qty * unitCost;
            // Could add a display field for total cost if needed
        }

        function calculateEditTotalCost() {
            const qty = parseFloat($('#edit_item_qty').val() || 0);
            const unitCost = parseFloat($('#edit_item_unit_cost').val() || 0);
            const total = qty * unitCost;
            // Could add a display field for total cost if needed
        }
    </script>
</body>
</html>
