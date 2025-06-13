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
        .dataTables_wrapper .dt-buttons {
            float: left;
            margin-bottom: 10px;
        }
        
        .dataTables_wrapper .dt-buttons .btn {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        
        @media (max-width: 768px) {
            .dataTables_wrapper .dt-buttons {
                text-align: center;
                width: 100%;
            }
            
            .dataTables_wrapper .dt-buttons .btn {
                margin: 2px;
                font-size: 11px;
                padding: 4px 8px;
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
                                                                <select id="filter_movement_type" class="form-control">
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
                                                                <input type="date" id="filter_date_from" class="form-control" value="<?= date('Y-m-d', strtotime('-30 days')); ?>">
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <label>Date To:</label>
                                                                <input type="date" id="filter_date_to" class="form-control" value="<?= date('Y-m-d'); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top: 10px;">
                                                            <div class="col-sm-12">
                                                                <label>Search:</label>
                                                                <input type="text" id="search_text" class="form-control" placeholder="Search products, categories, notes, etc...">
                                                            </div>
                                                        </div>
                                                        <div class="row" style="margin-top: 10px;">
                                                            <div class="col-sm-12">
                                                                <button type="button" id="btn_search" class="btn btn-primary">
                                                                    <i class="ace-icon fa fa-search"></i> Search
                                                                </button>
                                                                <button type="button" id="btn_export" class="btn btn-success">
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
                                    <div class="table-header">
                                        Stock Movement History
                                    </div>

                                    <div>
                                        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
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
            // Initialize DataTable with export buttons
            oTable1 = $('#dynamic-table').DataTable({
                "aoColumns": [
                    null, null, null, null, null, null, null, null, null, null, null, null, null,
                    {"bSortable": false}
                ],
                "aaSorting": [[0, "desc"]], // Sort by date descending
                "select": {
                    "style": "single"
                },
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'excel',
                        text: '<i class="ace-icon fa fa-file-excel-o"></i> Export to Excel',
                        className: 'btn btn-success btn-sm',
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
                            return 'Stock Movements Report - ' + new Date().toLocaleDateString() + filterText;
                        },
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] // Exclude Actions column
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="ace-icon fa fa-file-pdf-o"></i> Export to PDF',
                        className: 'btn btn-danger btn-sm',
                        title: 'Stock Movements Report',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] // Exclude Actions column
                        },
                        customize: function(doc) {
                            // Adjust column widths for landscape orientation - 13 exported columns
                            doc.content[1].table.widths = ['7%', '8%', '12%', '8%', '8%', '8%', '7%', '7%', '7%', '7%', '8%', '8%', '7%'];
                            doc.styles.tableHeader.fontSize = 6;
                            doc.defaultStyle.fontSize = 5;
                            doc.styles.tableHeader.alignment = 'left';
                            
                            // Add header with filter information
                            var filters = [];
                            var location = $("#filter_location option:selected").text();
                            var movementType = $("#filter_movement_type").val();
                            var dateFrom = $("#filter_date_from").val();
                            var dateTo = $("#filter_date_to").val();
                            
                            if (location && location !== "All Locations") filters.push("Location: " + location);
                            if (movementType) filters.push("Movement Type: " + movementType);
                            if (dateFrom) filters.push("Date From: " + dateFrom);
                            if (dateTo) filters.push("Date To: " + dateTo);
                            
                            var filterText = filters.length > 0 ? "Filters Applied: " + filters.join(", ") + "\n" : "";
                            
                            doc.content.splice(0, 0, {
                                text: [
                                    { text: 'Stock Movements Report\n', fontSize: 16, bold: true },
                                    { text: 'Generated on: ' + new Date().toLocaleString() + '\n', fontSize: 10 },
                                    { text: filterText + '\n', fontSize: 8, italics: true }
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
                        title: 'Stock Movements Report',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] // Exclude Actions column
                        },
                    {
                        extend: 'print',
                        text: '<i class="ace-icon fa fa-print"></i> Print',
                        className: 'btn btn-info btn-sm',
                        title: 'Stock Movements Report',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] // Exclude Actions column
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
                                    'font-size: 9px; ' +
                                    'line-height: 1.2; ' +
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
                                    'padding: 4px 2px; ' +
                                    'text-align: left; ' +
                                    'word-wrap: break-word; ' +
                                    'overflow-wrap: break-word; ' +
                                    'hyphens: auto; ' +
                                '}' +
                                'th { ' +
                                    'background-color: #f5f5f5; ' +
                                    'font-weight: bold; ' +
                                    'font-size: 8px; ' +
                                '}' +
                                'td { ' +
                                    'font-size: 7px; ' +
                                '}' +
                                '/* Column widths for movements table */' +
                                'th:nth-child(1), td:nth-child(1) { width: 7%; }' +
                                'th:nth-child(2), td:nth-child(2) { width: 8%; }' +
                                'th:nth-child(3), td:nth-child(3) { width: 12%; }' +
                                'th:nth-child(4), td:nth-child(4) { width: 8%; }' +
                                'th:nth-child(5), td:nth-child(5) { width: 8%; }' +
                                'th:nth-child(6), td:nth-child(6) { width: 8%; }' +
                                'th:nth-child(7), td:nth-child(7) { width: 7%; }' +
                                'th:nth-child(8), td:nth-child(8) { width: 7%; }' +
                                'th:nth-child(9), td:nth-child(9) { width: 7%; }' +
                                'th:nth-child(10), td:nth-child(10) { width: 7%; }' +
                                'th:nth-child(11), td:nth-child(11) { width: 8%; }' +
                                'th:nth-child(12), td:nth-child(12) { width: 8%; }' +
                                'th:nth-child(13), td:nth-child(13) { width: 7%; }' +
                                '.print-info { ' +
                                    'text-align: center; ' +
                                    'font-size: 9px; ' +
                                    'margin-bottom: 15px; ' +
                                    'color: #666; ' +
                                '}' +
                                '</style>'
                            );
                            
                            // Add header with filter information
                            var filters = [];
                            var location = $("#filter_location option:selected").text();
                            var movementType = $("#filter_movement_type").val();
                            var dateFrom = $("#filter_date_from").val();
                            var dateTo = $("#filter_date_to").val();
                            var searchText = $("#search_text").val();
                            
                            if (location && location !== "All Locations") filters.push("Location: " + location);
                            if (movementType) filters.push("Type: " + movementType);
                            if (dateFrom) filters.push("From: " + dateFrom);
                            if (dateTo) filters.push("To: " + dateTo);
                            if (searchText) filters.push("Search: \"" + searchText + "\"");
                            
                            var filterText = filters.length > 0 ? filters.join(" | ") + " | " : "";
                            filterText += "Generated: " + new Date().toLocaleString();
                            
                            $(win.document.body).prepend(
                                '<div style="text-align: center; margin-bottom: 20px;">' +
                                '<h1>Stock Movements Report</h1>' +
                                '<div class="print-info">' + filterText + '</div>' +
                                '</div>'
                            );
                        }
                    }
                    }
                ]
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

            $("#btn_export").click(function() {
                exportMovements();
            });
        });

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
                
                oTable1.row.add([
                    row.date || row.created_at.split(' ')[0],
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
            html += '<tr><td><strong>Date:</strong></td><td>' + (movement.date || movement.created_at.split(' ')[0]) + '</td></tr>';
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
            
            html += '<tr><td><strong>Created:</strong></td><td>' + movement.created_at + '</td></tr>';
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
    </script>

</body>

</html>
