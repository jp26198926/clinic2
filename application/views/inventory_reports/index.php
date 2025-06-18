<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title><?= $app_title; ?></title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    
    <?php $this->load->view('template/style'); ?>
    
    <style>
        .filter-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        
        .filter-row {
            display: flex;
            gap: 15px;
            align-items: end;
            flex-wrap: wrap;
        }
        
        .filter-item {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-item label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }
        
        .datepicker {
            background-color: white !important;
            cursor: pointer !important;
        }
        
        .datepicker:focus {
            background-color: white !important;
        }
        
        .widget-box {
            border: 1px solid #e3e3e3;
            border-radius: 0;
            box-shadow: none;
        }
        
        .widget-header {
            background: #f5f5f5;
            border-bottom: 1px solid #e3e3e3;
        }
        
        .widget-title {
            color: #333;
            font-size: 14px;
        }
        
        .widget-toolbar .btn-group .btn {
            margin: 0 2px;
        }
        
        #report_content {
            min-height: 400px;
        }
        
        #report_content .table {
            margin-bottom: 0;
        }
        
        #report_content .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-top: 1px solid #dee2e6;
        }
        
        #report_content .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
        
        .alert {
            border-radius: 4px;
        }
        
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
        
        .report-stats {
            background: #e7f3ff;
            border: 1px solid #bee5eb;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
        }
        
        .report-stats .row {
            margin: 0;
        }
        
        .report-stats .col-md-3 {
            text-align: center;
            padding: 5px;
        }
        
        .report-stats .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        
        .report-stats .stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
        }
        
        @media (max-width: 768px) {
            .filter-item {
                min-width: 100%;
            }
            
            .widget-toolbar .btn-group {
                display: flex;
                flex-direction: column;
                width: 100%;
            }
            
            .widget-toolbar .btn-group .btn {
                margin: 2px 0;
            }
        }
    </style>
</head>

<body class="no-skin">
    <?php $this->load->view('template/header'); ?>

    <div class="main-container ace-save-state" id="main-container">
        <script type="text/javascript">
            try {
                ace.settings.loadState('main-container')
            } catch (e) {}
        </script>

        <?php $this->load->view('template/sidebar'); ?>

        <div class="main-content">
            <div class="main-content-inner">
                <div class="page-content">
                    <?php $this->load->view('template/ace-settings'); ?>

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

                            <!-- Global Filters -->
                            <div class="filter-section">
                                <h4><i class="fa fa-filter"></i> Global Filters</h4>
                                <div class="filter-row">
                                    <div class="filter-item">
                                        <label>Report Template:</label>
                                        <select id="report_template" class="form-control chosen-select" data-placeholder="Select a report template">
                                            <option value="">Select Report Template</option>
                                            <option value="low_stock">Low Stock Report</option>
                                            <option value="stock_valuation">Stock Valuation Report</option>
                                            <option value="expiring_stock">Expiring Stock Report</option>
                                            <option value="expired_stock">Expired Stock Report</option>
                                            <option value="zero_stock">Zero Stock Report</option>
                                            <option value="movement_summary">Movement Summary Report</option>
                                            <option value="abc_analysis">ABC Analysis Report</option>
                                            <option value="turnover_analysis">Turnover Analysis Report</option>
                                        </select>
                                    </div>
                                    <div class="filter-item">
                                        <label>Location:</label>
                                        <select id="global_location" class="form-control chosen-select">
                                            <option value="">All Locations</option>
                                            <?php foreach($locations as $location): ?>
                                            <option value="<?= $location->id; ?>"><?= $location->location; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="filter-item">
                                        <label>Date From:</label>
                                        <input type="text" id="global_date_from" class="form-control datepicker" placeholder="Select start date" readonly>
                                    </div>
                                    <div class="filter-item">
                                        <label>Date To:</label>
                                        <input type="text" id="global_date_to" class="form-control datepicker" placeholder="Select end date" readonly>
                                    </div>
                                    <div class="filter-item">
                                        <button type="button" id="btn_generate_report" class="btn btn-primary">
                                            <i class="fa fa-play"></i> Generate Report
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Report Results Section -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">
                                                <i class="ace-icon fa fa-table"></i>
                                                <span id="report_title">Report Results</span>
                                            </h4>
                                            <div class="widget-toolbar">
                                                <div class="btn-group">
                                                    <button type="button" id="btn_export_pdf" class="btn btn-danger btn-sm" disabled>
                                                        <i class="fa fa-file-pdf-o"></i> Export PDF
                                                    </button>
                                                    <button type="button" id="btn_export_excel" class="btn btn-success btn-sm" disabled>
                                                        <i class="fa fa-file-excel-o"></i> Export Excel
                                                    </button>
                                                    <button type="button" id="btn_refresh_report" class="btn btn-info btn-sm" disabled>
                                                        <i class="fa fa-refresh"></i> Refresh
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <div id="report_content">
                                                    <div class="alert alert-info text-center">
                                                        <i class="fa fa-info-circle fa-2x"></i>
                                                        <h4>No Report Selected</h4>
                                                        <p>Please select a report template from the filters above and click "Generate Report" to view results.</p>
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

        <?php $this->load->view('template/footer'); ?>
    </div><!-- /.main-container -->

    <?php $this->load->view('template/script'); ?>

    <script type="text/javascript">
        var currentReportType = '';
        var reportDataTable = null;
        
        $(document).ready(function() {
            // Initialize datepickers
            $('.datepicker').datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                maxDate: 0 // Cannot select future dates
            });

            // Initialize chosen dropdowns
            $('.chosen-select').chosen({
                allow_single_deselect: true,
                no_results_text: "No results match",
                width: "100%"
            });

            // Generate report handler
            $('#btn_generate_report').click(function() {
                var reportType = $('#report_template').val();
                if (!reportType) {
                    toastr.warning('Please select a report template first.');
                    return;
                }
                generateReport(reportType);
            });

            // Export handlers
            $('#btn_export_pdf').click(function() {
                if (!currentReportType) {
                    toastr.warning('Please generate a report first.');
                    return;
                }
                exportReport(currentReportType, 'pdf');
            });

            $('#btn_export_excel').click(function() {
                if (!currentReportType) {
                    toastr.warning('Please generate a report first.');
                    return;
                }
                exportReport(currentReportType, 'excel');
            });

            // Refresh report handler
            $('#btn_refresh_report').click(function() {
                if (!currentReportType) {
                    toastr.warning('Please generate a report first.');
                    return;
                }
                generateReport(currentReportType);
            });

            // Report template change handler
            $('#report_template').on('change', function() {
                var selectedTemplate = $(this).val();
                if (selectedTemplate) {
                    $('#report_title').text(getReportTitle(selectedTemplate));
                } else {
                    $('#report_title').text('Report Results');
                    resetReportSection();
                }
            });
        });

        function generateReport(reportType) {
            var filters = getGlobalFilters();
            
            // Show loading
            showLoading();
            currentReportType = reportType;
            
            // Enable buttons
            enableExportButtons();
            
            // Update title
            $('#report_title').text(getReportTitle(reportType));
            
            $.ajax({
                url: '<?= base_url("inventory_reports/generate_report"); ?>',
                type: 'POST',
                data: {
                    report_type: reportType,
                    location_id: filters.location_id,
                    date_from: filters.date_from,
                    date_to: filters.date_to
                },
                success: function(response) {
                    hideLoading();
                    try {
                        var data = JSON.parse(response);
                        if (data.success) {
                            displayReportData(data.html, reportType, data.record_count || 0);
                            toastr.success('Report generated successfully!');
                        } else {
                            showError('Error: ' + (data.message || 'Failed to generate report'));
                        }
                    } catch (e) {
                        showError('Error: Invalid response from server');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    showError('Error: Failed to connect to server - ' + error);
                }
            });
        }

        function displayReportData(htmlContent, reportType, recordCount) {
            // Parse the HTML content and create a proper DataTable
            var $tempDiv = $('<div>').html(htmlContent);
            var $table = $tempDiv.find('table').first();
            
            if ($table.length === 0) {
                $('#report_content').html('<div class="alert alert-warning text-center">No data available for this report.</div>');
                return;
            }
            
            // Add DataTable classes and structure
            $table.addClass('table table-striped table-bordered table-hover');
            $table.attr('id', 'reportDataTable');
            
            // Create stats using the record count from server
            var stats = generateReportStats(reportType, recordCount);
            
            // Display the content
            $('#report_content').html(stats + '<div class="table-responsive">' + $table[0].outerHTML + '</div>');
            
            // Initialize DataTable
            initializeDataTable();
        }

        function generateReportStats(reportType, recordCount) {
            var currentDate = new Date().toLocaleDateString();
            var filters = getGlobalFilters();
            
            var locationText = filters.location_id ? $('#global_location option:selected').text() : 'All Locations';
            
            return '<div class="report-stats">' +
                '<div class="row">' +
                '<div class="col-md-3">' +
                '<div class="stat-value">' + recordCount + '</div>' +
                '<div class="stat-label">Total Records</div>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<div class="stat-value">' + locationText + '</div>' +
                '<div class="stat-label">Location</div>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<div class="stat-value">' + (filters.date_from || 'All') + '</div>' +
                '<div class="stat-label">Date From</div>' +
                '</div>' +
                '<div class="col-md-3">' +
                '<div class="stat-value">' + currentDate + '</div>' +
                '<div class="stat-label">Generated</div>' +
                '</div>' +
                '</div>' +
                '</div>';
        }

        function initializeDataTable() {
            // Destroy existing DataTable if it exists
            if (reportDataTable) {
                reportDataTable.destroy();
            }
            
            // Initialize new DataTable
            reportDataTable = $('#reportDataTable').DataTable({
                "responsive": true,
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                "order": [[0, "asc"]],
                "columnDefs": [
                    { "className": "text-center", "targets": [0] } // Row number column
                ],
                "language": {
                    "search": "Search in results:",
                    "lengthMenu": "Show _MENU_ entries per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "No entries available",
                    "infoFiltered": "(filtered from _MAX_ total entries)",
                    "emptyTable": "No data available in table",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });
        }

        function exportReport(reportType, format) {
            var filters = getGlobalFilters();
            
            // For PDF exports, use direct URL with target="_blank"
            if (format === 'pdf') {
                var url = '<?= base_url("inventory_reports/export_pdf"); ?>';
                var params = [];
                
                params.push('report_type=' + encodeURIComponent(reportType));
                if (filters.location_id) {
                    params.push('location_id=' + encodeURIComponent(filters.location_id));
                }
                if (filters.date_from) {
                    params.push('date_from=' + encodeURIComponent(filters.date_from));
                }
                if (filters.date_to) {
                    params.push('date_to=' + encodeURIComponent(filters.date_to));
                }
                
                if (params.length > 0) {
                    url += '?' + params.join('&');
                }
                
                // Open PDF in new window/tab
                window.open(url, '_blank');
                toastr.info('PDF export opened in new tab.');
                return;
            }
            
            // For Excel exports, use form submission
            var form = $('<form>', {
                'method': 'POST',
                'action': '<?= base_url("inventory_reports/export_report"); ?>',
                'target': '_self'
            });
            
            form.append($('<input>', { 'type': 'hidden', 'name': 'report_type', 'value': reportType }));
            form.append($('<input>', { 'type': 'hidden', 'name': 'format', 'value': format }));
            form.append($('<input>', { 'type': 'hidden', 'name': 'location_id', 'value': filters.location_id }));
            form.append($('<input>', { 'type': 'hidden', 'name': 'date_from', 'value': filters.date_from }));
            form.append($('<input>', { 'type': 'hidden', 'name': 'date_to', 'value': filters.date_to }));
            
            $('body').append(form);
            form.submit();
            form.remove();
            
            toastr.info('Excel export started.');
        }

        function getGlobalFilters() {
            return {
                location_id: $('#global_location').val() || '',
                date_from: $('#global_date_from').val() || '',
                date_to: $('#global_date_to').val() || ''
            };
        }

        function getReportTitle(reportType) {
            var titles = {
                'low_stock': 'Low Stock Report',
                'stock_valuation': 'Stock Valuation Report',
                'expiring_stock': 'Expiring Stock Report',
                'expired_stock': 'Expired Stock Report',
                'zero_stock': 'Zero Stock Report',
                'movement_summary': 'Movement Summary Report',
                'abc_analysis': 'ABC Analysis Report',
                'turnover_analysis': 'Turnover Analysis Report'
            };
            return titles[reportType] || 'Report Results';
        }

        function showLoading() {
            $('#report_content').html(
                '<div class="loading-overlay">' +
                '<div class="text-center">' +
                '<i class="fa fa-spinner fa-spin fa-3x text-primary"></i>' +
                '<h4 style="margin-top: 15px;">Generating Report...</h4>' +
                '<p class="text-muted">Please wait while we process your request.</p>' +
                '</div>' +
                '</div>'
            );
        }

        function hideLoading() {
            // Loading will be replaced by report content
        }

        function showError(message) {
            $('#report_content').html('<div class="alert alert-danger text-center"><i class="fa fa-exclamation-triangle"></i> ' + message + '</div>');
            disableExportButtons();
            currentReportType = '';
        }

        function enableExportButtons() {
            $('#btn_export_pdf, #btn_export_excel, #btn_refresh_report').prop('disabled', false);
        }

        function disableExportButtons() {
            $('#btn_export_pdf, #btn_export_excel, #btn_refresh_report').prop('disabled', true);
        }

        function resetReportSection() {
            $('#report_content').html(
                '<div class="alert alert-info text-center">' +
                '<i class="fa fa-info-circle fa-2x"></i>' +
                '<h4>No Report Selected</h4>' +
                '<p>Please select a report template from the filters above and click "Generate Report" to view results.</p>' +
                '</div>'
            );
            disableExportButtons();
            currentReportType = '';
            if (reportDataTable) {
                reportDataTable.destroy();
                reportDataTable = null;
            }
        }
    </script>
</body>
</html>
