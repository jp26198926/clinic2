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

        /* Date picker styling */
        .datepicker {
            background-color: white !important;
            cursor: pointer !important;
        }
        
        .datepicker:focus {
            background-color: white !important;
        }

        /* Mobile responsive card layout for movements */
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
            
            .movement-card {
                background: white;
                border: 1px solid #ddd;
                border-radius: 8px;
                margin-bottom: 15px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            
            .movement-card-header {
                padding: 12px 15px;
                border-bottom: 1px solid #ddd;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .movement-card-title {
                font-weight: bold;
                font-size: 16px;
                color: #333;
            }
            
            .movement-type-badge {
                padding: 4px 8px;
                border-radius: 12px;
                font-size: 11px;
                font-weight: bold;
                text-transform: uppercase;
            }
            
            .movement-card-body {
                padding: 15px;
            }
            
            .movement-info-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 6px 0;
                border-bottom: 1px solid #f0f0f0;
            }
            
            .movement-info-row:last-child {
                border-bottom: none;
            }
            
            .movement-info-label {
                font-weight: 600;
                color: #555;
                font-size: 13px;
            }
            
            .movement-info-value {
                font-size: 13px;
                color: #333;
                text-align: right;
            }
            
            .movement-qty {
                font-size: 18px;
                font-weight: bold;
                text-align: center;
                padding: 10px;
                margin: 10px 0;
                border-radius: 5px;
                background: #f8f9fa;
            }
            
            .movement-notes {
                background: #f8f9fa;
                padding: 10px;
                border-radius: 5px;
                margin-top: 10px;
                font-style: italic;
                color: #666;
                font-size: 12px;
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

            /* Movement type colors */
            .badge-receive { background: #28a745; color: white; }
            .badge-release { background: #dc3545; color: white; }
            .badge-transfer { background: #17a2b8; color: white; }
            .badge-adjustment { background: #ffc107; color: #212529; }
            .badge-reserve { background: #6c757d; color: white; }
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
                                                            <div class="col-sm-3">
                                                                <label>Location:</label>
                                                                <select id="filter_location" class="form-control chosen-select">
                                                                    <option value="">All Locations</option>
                                                                    <?php foreach($locations as $location): ?>
                                                                    <option value="<?= $location->id; ?>"><?= $location->location; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label>Movement Type:</label>
                                                                <select id="filter_movement_type" class="form-control chosen-select">
                                                                    <option value="">All Types</option>
                                                                    <option value="RECEIVE">RECEIVE</option>
                                                                    <option value="RELEASE">RELEASE</option>
                                                                    <option value="TRANSFER">TRANSFER</option>
                                                                    <option value="ADJUSTMENT">ADJUSTMENT</option>
                                                                    <option value="RESERVE">RESERVE</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label>Date From:</label>
                                                                <input type="text" id="filter_date_from" class="form-control datepicker" readonly value="<?= date('Y-m-d', strtotime('-30 days')); ?>">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label>Date To:</label>
                                                                <input type="text" id="filter_date_to" class="form-control datepicker" readonly value="<?= date('Y-m-d'); ?>">
                                                            </div>
                                                        </div>                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-sm-12">
                                                <label>Search:</label>
                                                <input type="text" id="search_text" class="form-control" placeholder="Search products, categories, notes, etc...">
                                            </div>
                                        </div>
                                                        <div class="row" style="margin-top: 10px;">
                                                            <div class="col-sm-12">
                                                                <button type="button" id="btn_search" class="btn btn-sm btn-primary">
                                                                    <i class="ace-icon fa fa-search"></i> Search
                                                                </button>
                                                                <button type="button" id="btn_export" class="btn btn-sm btn-success">
                                                                    <i class="ace-icon fa fa-download"></i> Export
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Results Table -->
                                    <div class="table-header clearfix">
                                        Stock Movement History <span id="lbl_result_info" class="badge badge-warning"></span>
                                        <div class="pull-right" style="padding-right: 0.5em; padding-top: 0.4em;">
                                            <div class="pull-right tableTools-container"></div>
                                        </div>
                                    </div>

                                    <!-- Desktop Table View -->
                                    <div class="table-responsive">
                                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Product Code</th>
                                                    <th>Product Name</th>
                                                    <th>Category</th>
                                                    <th>Location</th>
                                                    <th>Movement Type</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Cost</th>
                                                    <th>Reference Type</th>
                                                    <th>Reference ID</th>
                                                    <th>Transfer Details</th>
                                                    <th>Created By</th>
                                                    <th>Notes</th>
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

        <!-- Movement Details Modal -->
        <div class="modal fade" id="modal_movement_details" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Movement Details</h4>
                    </div>
                    <div class="modal-body" id="movement_details_content">
                        <!-- Content will be loaded dynamically -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
            // Initialize datepickers with YYYY-MM-DD format
            $('.datepicker').datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                maxDate: 0, // Cannot select future dates
                onSelect: function() {
                    searchMovements();
                }
            });

            // Initialize DataTable with export buttons
            oTable1 = $('#dynamic-table').DataTable({
                "aoColumns": [
                    {"bSortable": false}, // Row number column
                    null, null, null, null, null, null, null, null, null, null, null, null, null,
                    {"bSortable": false} // Actions column
                ],
                "aaSorting": [[1, "desc"]], // Sort by date descending (adjusted for new column)
                "select": {
                    "style": "single"
                },
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'excel',
                        text: '<i class="ace-icon fa fa-file-excel-o bigger-110 green"></i> <span class="hidden">Export to Excel</span>',
                        className: 'btn btn-white btn-primary btn-bold',
                        titleAttr: 'Export to Excel',
                        title: function() {
                            var filters = [];
                            var location = $("#filter_location option:selected").text();
                            var movementType = $("#filter_movement_type").val();
                            var dateFrom = $("#filter_date_from").val();
                            var dateTo = $("#filter_date_to").val();
                            
                            if (location && location !== "All Locations") filters.push("Location: " + location);
                            if (movementType) filters.push("Type: " + movementType);
                            if (dateFrom) filters.push("From: " + dateFrom);
                            if (dateTo) filters.push("To: " + dateTo);
                            
                            var filterText = filters.length > 0 ? " (" + filters.join(", ") + ")" : "";
                            var currentDate = new Date().toISOString().split('T')[0]; // YYYY-MM-DD format
                            return 'Stock Movements Report - ' + currentDate + filterText;
                        },
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13] // Exclude row count (0) and Actions (14)
                        }
                    },
                    {
                        text: '<i class="ace-icon fa fa-file-pdf-o bigger-110 red"></i> <span class="hidden">Export to PDF</span>',
                        className: 'btn btn-white btn-primary btn-bold',
                        titleAttr: 'Export to PDF',
                        action: function(e, dt, node, config) {
                            exportToPdf();
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="ace-icon fa fa-print bigger-110 grey"></i> <span class="hidden">Print</span>',
                        className: 'btn btn-white btn-primary btn-bold',
                        titleAttr: 'Print Report',
                        title: 'Stock Movements Report',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13] // Exclude row count (0) and Actions (14)
                        }
                    }
                ]
            });

            // Append buttons to the table header container
            oTable1.buttons().container().appendTo($('.tableTools-container'));

            // Initialize chosen dropdowns
            $('.chosen-select').chosen({
                allow_single_deselect: true,
                no_results_text: "No results match",
                width: "100%"
            });

            // Initial load
            searchMovements();

            // Event handlers
            $("#btn_search").click(function() {
                searchMovements();
            });

            $("#search_text").keypress(function(e) {
                if (e.which == 13) {
                    searchMovements();
                }
            });

            $("#filter_location, #filter_movement_type, #filter_date_from, #filter_date_to").change(function() {
                searchMovements();
            });

            // Handle chosen dropdown changes
            $("#filter_location").chosen().change(function() {
                searchMovements();
            });

            $("#filter_movement_type").chosen().change(function() {
                searchMovements();
            });

            $("#btn_export").click(function() {
                exportMovements();
            });

            // Mobile search functionality
            $("#mobile-search").on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                
                $('.movement-card').each(function() {
                    var searchData = $(this).data('search');
                    if (searchData.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
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

        function searchMovements() {
            var search = $("#search_text").val();
            var location_id = $("#filter_location").val();
            var movement_type = $("#filter_movement_type").val();
            var date_from = $("#filter_date_from").val();
            var date_to = $("#filter_date_to").val();

            $.get("<?= base_url(); ?>inventory_movements/search", {
                search: search,
                location_id: location_id,
                movement_type: movement_type,
                date_from: date_from,
                date_to: date_to
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
                var actions = '<button type="button" class="btn btn-xs btn-info" onclick="viewMovementDetails(' + row.id + ')"><i class="ace-icon fa fa-eye"></i></button>';
                
                var transferDetails = '';
                if (row.movement_type === 'TRANSFER') {
                    if (row.transfer_from_location && row.transfer_to_location) {
                        transferDetails = row.transfer_from_location + ' → ' + row.transfer_to_location;
                    }
                    if (row.transfer_batch_id) {
                        transferDetails += '<br><small class="text-muted">Batch: ' + row.transfer_batch_id + '</small>';
                    }
                }
                
                var movementTypeLabel = getMovementTypeLabel(row.movement_type);
                var unitCost = row.unit_cost ? parseFloat(row.unit_cost).toFixed(2) : '-';
                var movementDate = formatDate(row.date || row.created_at);
                
                oTable1.row.add([
                    rowNumber, // Row count column
                    movementDate,
                    row.product_code,
                    row.product_name,
                    row.category,
                    row.location,
                    movementTypeLabel,
                    row.qty,
                    unitCost,
                    row.reference_type,
                    row.reference_id || '-',
                    transferDetails,
                    row.created_by_name || '-',
                    (row.notes || '').substring(0, 50) + (row.notes && row.notes.length > 50 ? '...' : ''),
                    actions
                ]);
            });

            oTable1.draw();
            
            // Populate mobile cards
            populateMobileMovementCards(data);
        }

        function populateMobileMovementCards(data) {
            var container = $('#mobile-cards-container');
            container.empty();

            if (data.length === 0) {
                container.html('<div class="text-center" style="padding: 20px; color: #666;">No movement data found</div>');
                return;
            }

            $.each(data, function(i, row) {
                var unitCost = row.unit_cost ? parseFloat(row.unit_cost).toFixed(2) : '-';
                var movementDate = formatDate(row.date || row.created_at);
                var movementTime = row.created_at ? formatDateTime(row.created_at).split(' ')[1] : '';
                var badgeClass = getMovementBadgeClass(row.movement_type);
                
                var transferInfo = '';
                if (row.movement_type === 'TRANSFER' && row.transfer_from_location && row.transfer_to_location) {
                    transferInfo = `
                        <div class="movement-info-row">
                            <span class="movement-info-label">Transfer:</span>
                            <span class="movement-info-value">${row.transfer_from_location} → ${row.transfer_to_location}</span>
                        </div>
                    `;
                }
                
                var notesSection = '';
                if (row.notes && row.notes.trim()) {
                    notesSection = `<div class="movement-notes">Notes: ${row.notes}</div>`;
                }
                
                var card = $(`
                    <div class="movement-card" data-search="${row.product_code.toLowerCase()} ${row.product_name.toLowerCase()} ${row.category.toLowerCase()} ${row.location.toLowerCase()} ${row.movement_type.toLowerCase()}">
                        <div class="movement-card-header">
                            <div class="movement-card-title">${row.product_name}</div>
                            <span class="movement-type-badge ${badgeClass}">${row.movement_type}</span>
                        </div>
                        <div class="movement-card-body">
                            <div class="movement-info-row">
                                <span class="movement-info-label">Product Code:</span>
                                <span class="movement-info-value">${row.product_code}</span>
                            </div>
                            <div class="movement-info-row">
                                <span class="movement-info-label">Category:</span>
                                <span class="movement-info-value">${row.category}</span>
                            </div>
                            <div class="movement-info-row">
                                <span class="movement-info-label">Location:</span>
                                <span class="movement-info-value">${row.location}</span>
                            </div>
                            ${transferInfo}
                            <div class="movement-qty">
                                Quantity: ${row.qty}
                            </div>
                            <div class="movement-info-row">
                                <span class="movement-info-label">Unit Cost:</span>
                                <span class="movement-info-value">${unitCost}</span>
                            </div>
                            <div class="movement-info-row">
                                <span class="movement-info-label">Reference:</span>
                                <span class="movement-info-value">${row.reference_type} ${row.reference_id ? '#' + row.reference_id : ''}</span>
                            </div>
                            <div class="movement-info-row">
                                <span class="movement-info-label">Date:</span>
                                <span class="movement-info-value">${movementDate} ${movementTime}</span>
                            </div>
                            <div class="movement-info-row">
                                <span class="movement-info-label">Created By:</span>
                                <span class="movement-info-value">${row.created_by_name || '-'}</span>
                            </div>
                            ${notesSection}
                            <div style="text-align: center; margin-top: 15px;">
                                <button type="button" class="btn btn-sm btn-info" onclick="viewMovementDetails(${row.id})">
                                    <i class="ace-icon fa fa-eye"></i> View Details
                                </button>
                            </div>
                        </div>
                    </div>
                `);
                
                container.append(card);
            });
        }

        function getMovementBadgeClass(type) {
            var classes = {
                'RECEIVE': 'badge-receive',
                'RELEASE': 'badge-release', 
                'TRANSFER': 'badge-transfer',
                'ADJUSTMENT': 'badge-adjustment',
                'RESERVE': 'badge-reserve'
            };
            return classes[type] || 'badge-secondary';
        }

        function getMovementTypeLabel(type) {
            var colors = {
                'RECEIVE': 'success',
                'RELEASE': 'danger', 
                'TRANSFER': 'info',
                'ADJUSTMENT': 'warning',
                'RESERVE': 'default'
            };
            
            return '<span class="label label-' + (colors[type] || 'default') + '">' + type + '</span>';
        }

        function viewMovementDetails(id) {
            $.post("<?= base_url(); ?>inventory_movements/search_info_row", {
                id: id
            }, function(data) {
                if (data.indexOf("<!DOCTYPE html>") > -1) {
                    alert("Error: Session Time-Out, You must login again to continue.");
                    location.reload(true);
                } else if (data.indexOf("Error: ") > -1) {
                    toastr.error(data);
                } else {
                    var movement = JSON.parse(data);
                    showMovementDetails(movement);
                }
            });
        }

        function showMovementDetails(movement) {
            var html = '<div class="row">';
            html += '<div class="col-sm-6">';
            html += '<h5><strong>Movement Information</strong></h5>';
            html += '<table class="table table-borderless">';
            html += '<tr><td><strong>ID:</strong></td><td>' + movement.id + '</td></tr>';
            html += '<tr><td><strong>Date:</strong></td><td>' + formatDate(movement.date || movement.created_at) + '</td></tr>';
            html += '<tr><td><strong>Product:</strong></td><td>' + movement.product_code + ' - ' + movement.product_name + '</td></tr>';
            html += '<tr><td><strong>Location:</strong></td><td>' + movement.location + '</td></tr>';
            html += '<tr><td><strong>Movement Type:</strong></td><td>' + getMovementTypeLabel(movement.movement_type) + '</td></tr>';
            html += '<tr><td><strong>Quantity:</strong></td><td>' + movement.qty + '</td></tr>';
            if (movement.unit_cost) {
                html += '<tr><td><strong>Unit Cost:</strong></td><td>' + parseFloat(movement.unit_cost).toFixed(2) + '</td></tr>';
            }
            html += '</table>';
            html += '</div>';
            
            html += '<div class="col-sm-6">';
            html += '<h5><strong>Reference Information</strong></h5>';
            html += '<table class="table table-borderless">';
            html += '<tr><td><strong>Reference Type:</strong></td><td>' + movement.reference_type + '</td></tr>';
            if (movement.reference_id) {
                html += '<tr><td><strong>Reference ID:</strong></td><td>' + movement.reference_id + '</td></tr>';
            }
            
            if (movement.movement_type === 'TRANSFER') {
                if (movement.transfer_from_location) {
                    html += '<tr><td><strong>From Location:</strong></td><td>' + movement.transfer_from_location + '</td></tr>';
                }
                if (movement.transfer_to_location) {
                    html += '<tr><td><strong>To Location:</strong></td><td>' + movement.transfer_to_location + '</td></tr>';
                }
                if (movement.transfer_batch_id) {
                    html += '<tr><td><strong>Transfer Batch:</strong></td><td>' + movement.transfer_batch_id + '</td></tr>';
                }
            }
            
            html += '<tr><td><strong>Created:</strong></td><td>' + formatDateTime(movement.created_at) + '</td></tr>';
            html += '</table>';
            html += '</div>';
            html += '</div>';
            
            if (movement.notes) {
                html += '<div class="row">';
                html += '<div class="col-sm-12">';
                html += '<h5><strong>Notes</strong></h5>';
                html += '<div class="well well-sm">' + movement.notes + '</div>';
                html += '</div>';
                html += '</div>';
            }

            $("#movement_details_content").html(html);
            $("#modal_movement_details").modal();
        }

        function exportMovements() {
            // Trigger the built-in DataTables export
            $('.buttons-excel').trigger('click');
        }

        function exportToPdf() {
            // Get current filter values
            var search = $("#search_text").val();
            var location_id = $("#filter_location").val();
            var movement_type = $("#filter_movement_type").val();
            var date_from = $("#filter_date_from").val();
            var date_to = $("#filter_date_to").val();

            // Create a form and submit it to the PDF export endpoint
            var form = $('<form>', {
                'method': 'POST',
                'action': '<?= base_url(); ?>inventory_movements/export_pdf',
                'target': '_blank'
            });

            // Add filter parameters as hidden inputs
            form.append($('<input>', {'type': 'hidden', 'name': 'search', 'value': search}));
            form.append($('<input>', {'type': 'hidden', 'name': 'location_id', 'value': location_id}));
            form.append($('<input>', {'type': 'hidden', 'name': 'movement_type', 'value': movement_type}));
            form.append($('<input>', {'type': 'hidden', 'name': 'date_from', 'value': date_from}));
            form.append($('<input>', {'type': 'hidden', 'name': 'date_to', 'value': date_to}));

            // Append form to body, submit, and remove
            $('body').append(form);
            form.submit();
            form.remove();
        }
    </script>

</body>

</html>
