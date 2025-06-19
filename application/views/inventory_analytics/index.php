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
        .analytics-section {
            margin-bottom: 30px;
        }
        
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
        
        .chart-container {
            min-height: 300px;
            padding: 15px;
            position: relative;
        }
        
        .chart-placeholder {
            width: 100%;
            height: 280px;
            position: relative;
        }
        
        /* Ensure flot charts are visible */
        .flot-base {
            background: white;
        }
        
        /* Chart container styling */
        #movement-chart-placeholder,
        #stock-pie-placeholder,
        #top-products-chart-placeholder {
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        /* Error styling */
        .error {
            color: #dc3545;
            font-weight: bold;
        }
        
        .dashboard-stats {
            background: #e7f3ff;
            border: 1px solid #bee5eb;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .dashboard-stats .row {
            margin: 0;
        }
        
        .dashboard-stats .col-md-3 {
            text-align: center;
            padding: 10px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            display: block;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            margin-top: 5px;
        }
        
        .alert-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .alert-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #ffeaa7;
        }
        
        .alert-item:last-child {
            border-bottom: none;
        }
        
        .alert-product {
            font-weight: 600;
            color: #856404;
        }
        
        .alert-stock {
            color: #dc3545;
            font-weight: bold;
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
        
        @media (max-width: 768px) {
            .filter-item {
                min-width: 100%;
            }
            
            .dashboard-stats .col-md-3 {
                margin-bottom: 15px;
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
                                <h4><i class="fa fa-filter"></i> Analytics Filters</h4>
                                <div class="filter-row">
                                    <div class="filter-item">
                                        <label>Date Range:</label>
                                        <select id="date_range" class="form-control chosen-select" data-placeholder="Select date range">
                                            <option value="7">Last 7 Days</option>
                                            <option value="30" selected>Last 30 Days</option>
                                            <option value="90">Last 90 Days</option>
                                            <option value="365">Last Year</option>
                                            <option value="custom">Custom Range</option>
                                        </select>
                                    </div>
                                    <div class="filter-item" id="custom_date_range" style="display: none;">
                                        <label>From Date:</label>
                                        <input type="text" id="date_from" class="form-control datepicker" placeholder="Select start date" readonly>
                                    </div>
                                    <div class="filter-item" id="custom_date_range2" style="display: none;">
                                        <label>To Date:</label>
                                        <input type="text" id="date_to" class="form-control datepicker" placeholder="Select end date" readonly>
                                    </div>
                                    <div class="filter-item">
                                        <label>Location:</label>
                                        <select id="location_filter" class="form-control chosen-select" data-placeholder="All Locations">
                                            <option value="">All Locations</option>
                                            <!-- Locations will be loaded via AJAX -->
                                        </select>
                                    </div>
                                    <div class="filter-item">
                                        <label>&nbsp;</label>
                                        <button id="refresh_analytics" class="btn btn-primary form-control">
                                            <i class="fa fa-refresh"></i> Refresh Analytics
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Dashboard Stats -->
                            <div class="dashboard-stats">
                                <div class="row">
                                    <div class="col-md-3">
                                        <span class="stat-value" id="total_products">-</span>
                                        <div class="stat-label">Total Products</div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="stat-value" id="total_stock_value">-</span>
                                        <div class="stat-label">Total Stock Value</div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="stat-value" id="low_stock_items">-</span>
                                        <div class="stat-label">Low Stock Items</div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="stat-value" id="expired_items">-</span>
                                        <div class="stat-label">Expired Items</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Charts Row 1 -->
                            <div class="row analytics-section">
                                <div class="col-md-8">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">
                                                <i class="fa fa-line-chart"></i>
                                                Stock Movement Trends
                                            </h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <div class="chart-container" style="position: relative;">
                                                    <div id="movement-chart-placeholder" class="chart-placeholder"></div>
                                                    <div id="movement-loading" class="loading-overlay" style="display: none;">
                                                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">
                                                <i class="fa fa-pie-chart"></i>
                                                Stock Distribution
                                            </h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <div class="chart-container" style="position: relative;">
                                                    <div id="stock-pie-placeholder" class="chart-placeholder"></div>
                                                    <div id="pie-loading" class="loading-overlay" style="display: none;">
                                                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Charts Row 2 -->
                            <div class="row analytics-section">
                                <div class="col-md-12">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">
                                                <i class="fa fa-trophy"></i>
                                                Top Moving Products
                                            </h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <div class="chart-container" style="position: relative;">
                                                    <div id="top-products-chart-placeholder" class="chart-placeholder"></div>
                                                    <div id="top-loading" class="loading-overlay" style="display: none;">
                                                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stock Alerts -->
                            <div class="row analytics-section">
                                <div class="col-md-12">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">
                                                <i class="fa fa-exclamation-triangle"></i>
                                                Stock Alerts
                                            </h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <div id="stock-alerts-content">
                                                    <div class="text-center">
                                                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                                                        <p>Loading stock alerts...</p>
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
        </div>

        <?php $this->load->view('template/footer'); ?>
    </div>

    <?php $this->load->view('template/script'); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            // Debug: Check if jQuery Flot is loaded
            if (typeof $.plot === 'undefined') {
                console.error('jQuery Flot is not loaded!');
                alert('Error: Chart library not loaded. Please check if jQuery Flot is included.');
                return;
            } else {
                console.log('jQuery Flot loaded successfully');
            }

            // Initialize chosen selects
            $('.chosen-select').chosen({
                allow_single_deselect: true
            });

            // Initialize datepickers
            $('.datepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd'
            });

            // Handle date range change
            $('#date_range').on('change', function() {
                if ($(this).val() === 'custom') {
                    $('#custom_date_range, #custom_date_range2').show();
                } else {
                    $('#custom_date_range, #custom_date_range2').hide();
                }
            });

            // Load initial data from database - delay to ensure DOM is ready
            setTimeout(function() {
                console.log('Starting to load initial data...');
                testSimpleChart(); // Test if charts work at all
                loadLocations();
                loadDashboardStatsWithFallback();
                loadAllChartsWithFallback();
                loadStockAlertsWithFallback();
            }, 500);

            // Refresh button
            $('#refresh_analytics').on('click', function() {
                loadDashboardStatsWithFallback();
                loadAllChartsWithFallback();
                loadStockAlertsWithFallback();
            });

            // Auto refresh every 5 minutes
            setInterval(function() {
                loadDashboardStatsWithFallback();
                loadStockAlertsWithFallback();
            }, 300000);
        });

        // Simple test function to verify chart functionality
        function testSimpleChart() {
            console.log('Testing simple chart creation...');
            
            // Test if placeholders exist
            console.log('Movement placeholder exists:', $('#movement-chart-placeholder').length > 0);
            console.log('Pie placeholder exists:', $('#stock-pie-placeholder').length > 0);
            console.log('Top products placeholder exists:', $('#top-products-chart-placeholder').length > 0);
            
            // Test simple line chart
            try {
                var testData = [[0, 10], [1, 20], [2, 15], [3, 25]];
                $.plot("#movement-chart-placeholder", [{ data: testData, color: "#007bff" }], {
                    grid: { show: true },
                    lines: { show: true }
                });
                console.log('Simple test chart created successfully');
            } catch (e) {
                console.error('Simple test chart failed:', e);
            }
        }

        // Database functions - using actual data from stocks table
        function loadDashboardStats() {
            var filters = getFilters();
            
            $.post("<?= base_url(); ?>inventory_analytics/get_dashboard_stats", {
                [csrf_name]: csrf_hash,
                ...filters
            }, function(response) {
                try {
                    var result = JSON.parse(response);
                    if (result.success) {
                        regenerate_csrf(result.csrf_hash);
                        
                        // Use currency symbol from response or default to 'K'
                        var currencySymbol = result.currency_symbol || 'K';
                        
                        // Update with real data
                        $('#total_products').text(result.data.total_products || 0);
                        $('#total_stock_value').text(currencySymbol + ' ' + (result.data.total_stock_value || '0.00'));
                        $('#low_stock_items').text(result.data.low_stock_items || 0);
                        $('#expired_items').text(result.data.expired_items || 0);
                        
                        console.log('Real dashboard stats loaded successfully');
                    }
                } catch (e) {
                    console.log('Dashboard stats: using fallback data (backend not ready)');
                }
            }).fail(function() {
                console.log('Dashboard stats: using fallback data (backend not available)');
            });
        }

        function loadAllCharts() {
            loadMovementChart();
            loadStockPieChart();
            loadTopProductsChart();
        }

        function loadStockAlerts() {
            $.post("<?= base_url(); ?>inventory_analytics/get_stock_alerts", {
                [csrf_name]: csrf_hash
            }, function(response) {
                try {
                    var result = JSON.parse(response);
                    if (result.success) {
                        regenerate_csrf(result.csrf_hash);
                        displayStockAlerts(result.data);
                    }
                } catch (e) {
                    console.error('Error loading stock alerts:', e);
                    displayStockAlerts([]);
                }
            }).fail(function() {
                // Show message if backend call fails
                displayStockAlerts([]);
            });
        }

        function loadMovementChart() {
            $('#movement-loading').show();
            var filters = getFilters();

            $.post("<?= base_url(); ?>inventory_analytics/get_movement_trends", {
                [csrf_name]: csrf_hash,
                ...filters
            }, function(response) {
                try {
                    var result = JSON.parse(response);
                    if (result.success) {
                        regenerate_csrf(result.csrf_hash);
                        
                        // Transform backend data to expected format if needed
                        var chartData = result.data;
                        
                        // If backend returns old format, transform it
                        if (chartData.in_movements && !chartData.receiving) {
                            chartData = {
                                receiving: chartData.in_movements,
                                releasing: chartData.out_movements,
                                transferring: [] // Add empty transferring data if not provided
                            };
                        }
                        
                        drawMovementChart(chartData);
                        console.log('Real movement chart data loaded successfully');
                    }
                } catch (e) {
                    console.log('Movement chart: using fallback data (backend not ready)');
                } finally {
                    $('#movement-loading').hide();
                }
            }).fail(function() {
                console.log('Movement chart: using fallback data (backend not available)');
                $('#movement-loading').hide();
            });
        }

        function loadStockPieChart() {
            $('#pie-loading').show();
            var filters = getFilters();

            $.post("<?= base_url(); ?>inventory_analytics/get_stock_distribution", {
                [csrf_name]: csrf_hash,
                ...filters
            }, function(response) {
                try {
                    var result = JSON.parse(response);
                    if (result.success) {
                        regenerate_csrf(result.csrf_hash);
                        drawStockPieChart(result.data);
                        console.log('Real pie chart data loaded successfully');
                    }
                } catch (e) {
                    console.log('Pie chart: using fallback data (backend not ready)');
                } finally {
                    $('#pie-loading').hide();
                }
            }).fail(function() {
                console.log('Pie chart: using fallback data (backend not available)');
                $('#pie-loading').hide();
            });
        }

        function loadTopProductsChart() {
            $('#top-loading').show();
            var filters = getFilters();

            $.post("<?= base_url(); ?>inventory_analytics/get_top_products", {
                [csrf_name]: csrf_hash,
                ...filters
            }, function(response) {
                try {
                    var result = JSON.parse(response);
                    if (result.success) {
                        regenerate_csrf(result.csrf_hash);
                        drawTopProductsChart(result.data);
                    } else {
                        $('#top-products-chart-placeholder').html('<div class="text-center" style="padding: 50px;"><p>No top products data available</p></div>');
                    }
                } catch (e) {
                    console.error('Error loading top products chart:', e);
                    $('#top-products-chart-placeholder').html('<div class="text-center" style="padding: 50px;"><p class="error">Error loading top products</p></div>');
                } finally {
                    $('#top-loading').hide();
                }
            }).fail(function() {
                $('#top-products-chart-placeholder').html('<div class="text-center" style="padding: 50px;"><p class="error">Failed to load top products</p></div>');
                $('#top-loading').hide();
            });
        }

        function loadLocations() {
            // Use the existing Data_location controller to get locations from the database
            $.get("<?= base_url(); ?>data_location/search", {
                search: '' // Empty search to get all locations
            }, function(response) {
                try {
                    var locations = response;
                    if (typeof locations === 'string') {
                        locations = JSON.parse(locations);
                    }
                    
                    var options = '<option value="">All Locations</option>';
                    if (locations && Array.isArray(locations)) {
                        locations.forEach(function(location) {
                            // Use 'location' field for name and 'id' for value
                            options += '<option value="' + location.id + '">' + location.location + '</option>';
                        });
                    }
                    $('#location_filter').html(options).trigger('chosen:updated');
                    console.log('Locations loaded successfully:', locations.length + ' locations found');
                } catch (e) {
                    console.error('Error parsing locations response:', e);
                    console.log('Raw response:', response);
                    // Fallback to basic options
                    var options = '<option value="">All Locations</option>';
                    options += '<option value="1">Pharmacy</option>';
                    options += '<option value="2">Warehouse</option>';
                    options += '<option value="3">Emergency Room</option>';
                    options += '<option value="4">Laboratory</option>';
                    $('#location_filter').html(options).trigger('chosen:updated');
                }
            }).fail(function(xhr, status, error) {
                // Fallback if backend call fails
                console.error('Failed to load locations:', status, error);
                var options = '<option value="">All Locations</option>';
                options += '<option value="1">Pharmacy</option>';
                options += '<option value="2">Warehouse</option>';
                options += '<option value="3">Emergency Room</option>';
                options += '<option value="4">Laboratory</option>';
                $('#location_filter').html(options).trigger('chosen:updated');
            });
        }

        // Fallback functions that show mock data immediately and try real data
        function loadDashboardStatsWithFallback() {
            // Show mock data immediately
            var currencySymbol = 'K';
            $('#total_products').text('247');
            $('#total_stock_value').text(currencySymbol + ' 45,678.90');
            $('#low_stock_items').text('12');
            $('#expired_items').text('3');
            
            // Try to load real data in background
            loadDashboardStats();
        }

        function loadAllChartsWithFallback() {
            // Show mock charts immediately
            showMockMovementChart();
            showMockStockPieChart();
            showMockTopProductsChart();
            
            // Try to load real data in background (but don't override mock data if it fails)
            // loadAllCharts(); // Commented out to prevent overriding mock data
        }

        function loadStockAlertsWithFallback() {
            // Show mock alerts immediately
            var mockAlerts = [
                { product_name: 'Paracetamol 500mg', current_stock: 5 },
                { product_name: 'Amoxicillin 250mg', current_stock: 2 },
                { product_name: 'Ibuprofen 400mg', current_stock: 8 },
                { product_name: 'Vitamin D3', current_stock: 3 }
            ];
            displayStockAlerts(mockAlerts);
            
            // Try to load real data in background
            loadStockAlerts();
        }

        function showMockMovementChart() {
            console.log('Creating mock movement chart...');
            
            // Simple test data first
            var simpleData = {
                receiving: [[0, 30], [1, 25], [2, 35], [3, 20], [4, 40]],
                releasing: [[0, 15], [1, 20], [2, 18], [3, 25], [4, 22]],
                transferring: [[0, 5], [1, 0], [2, 8], [3, 0], [4, 12]],
                labels: ['6/14', '6/15', '6/16', '6/17', '6/18']
            };
            
            console.log('Simple mock data:', simpleData);
            drawMovementChart(simpleData);
            
            /* 
            // Original complex data generation (commented out for testing)
            var mockData = {
                receiving: [],
                releasing: [],
                transferring: []
            };
            
            // Create 30 days of sample data with day numbers instead of timestamps
            var labels = [];
            for (var i = 0; i < 30; i++) {
                var date = new Date();
                date.setDate(date.getDate() - (29 - i));
                labels.push((date.getMonth() + 1) + '/' + date.getDate());
                
                // Simulate realistic inventory patterns
                // Receiving: Higher on weekdays, lower on weekends
                var isWeekend = date.getDay() === 0 || date.getDay() === 6;
                var receivingBase = isWeekend ? 5 : 25;
                var receiving = receivingBase + Math.floor(Math.random() * 20);
                
                // Releasing: Consistent daily consumption
                var releasing = 15 + Math.floor(Math.random() * 25);
                
                // Transferring: Occasional transfers
                var transferring = Math.random() > 0.7 ? Math.floor(Math.random() * 15) + 5 : 0;
                
                mockData.receiving.push([i, receiving]);
                mockData.releasing.push([i, releasing]);
                mockData.transferring.push([i, transferring]);
            }
            
            // Store labels for use in chart
            mockData.labels = labels;
            
            console.log('Mock movement data:', mockData);
            drawMovementChart(mockData);
            */
        }

        function showMockStockPieChart() {
            console.log('Creating mock pie chart...');
            var mockData = [
                { label: "Medications", data: 45, color: "#68BC31" },
                { label: "Medical Supplies", data: 25, color: "#2091CF" },
                { label: "Equipment", data: 15, color: "#DA5430" },
                { label: "Consumables", data: 15, color: "#FFC107" }
            ];
            
            console.log('Mock pie data:', mockData);
            drawStockPieChart(mockData);
        }

        function showMockTopProductsChart() {
            console.log('Creating mock top products chart...');
            var mockData = [
                [45, 0], // [value, index]
                [38, 1],
                [32, 2],
                [28, 3],
                [22, 4]
            ];
            
            console.log('Mock top products data:', mockData);
            drawTopProductsChart(mockData);
        }

        // Enhanced tooltip for charts
        $("<div id='tooltip'></div>").css({
            position: "absolute",
            display: "none",
            border: "none",
            padding: "5px 10px",
            "background-color": "rgba(0,0,0,0.8)",
            color: "white",
            "border-radius": "3px",
            "font-size": "12px",
            "font-weight": "normal",
            "white-space": "nowrap",
            "z-index": 1000,
            "box-shadow": "0 2px 4px rgba(0,0,0,0.2)"
        }).appendTo("body");

        // Chart drawing functions using jQuery Flot
        function drawMovementChart(data) {
            console.log('=== Drawing movement chart ===');
            console.log('Input data:', data);
            
            try {
                // Verify the chart container exists and set dimensions
                var $placeholder = $('#movement-chart-placeholder');
                if ($placeholder.length === 0) {
                    console.error('Movement chart placeholder not found!');
                    return;
                }
                
                console.log('Placeholder found, setting dimensions...');
                
                // Ensure proper dimensions
                $placeholder.css({
                    'width': '100%',
                    'height': '280px',
                    'min-height': '280px'
                });

                // Support both old and new data formats
                var datasets = [];
                var xAxisOptions = {};
                
                if (data.receiving) {
                    console.log('Using new data format (receiving/releasing/transferring)');
                    // New format with receiving, releasing, transferring
                    datasets = [
                        {
                            label: "Receiving",
                            data: data.receiving || [],
                            color: "#68BC31",
                            lines: { show: true, fill: false, lineWidth: 2 },
                            points: { show: true, radius: 3 }
                        },
                        {
                            label: "Releasing", 
                            data: data.releasing || [],
                            color: "#DA5430",
                            lines: { show: true, fill: false, lineWidth: 2 },
                            points: { show: true, radius: 3 }
                        },
                        {
                            label: "Transferring",
                            data: data.transferring || [],
                            color: "#2091CF",
                            lines: { show: true, fill: false, lineWidth: 2 },
                            points: { show: true, radius: 3 }
                        }
                    ];
                    
                    console.log('Datasets prepared:', datasets);
                    
                    // Set up custom ticks if labels are provided
                    if (data.labels) {
                        console.log('Setting up custom labels:', data.labels);
                        xAxisOptions.ticks = [];
                        // Show every label for simple data, every 3rd for complex data
                        var step = data.labels.length <= 10 ? 1 : 3;
                        for (var i = 0; i < data.labels.length; i += step) { 
                            xAxisOptions.ticks.push([i, data.labels[i]]);
                        }
                        // Always show the last label
                        if ((data.labels.length - 1) % step !== 0) {
                            xAxisOptions.ticks.push([data.labels.length - 1, data.labels[data.labels.length - 1]]);
                        }
                        console.log('X-axis ticks:', xAxisOptions.ticks);
                    }
                } else {
                    console.log('Using old data format (in_movements/out_movements)');
                    // Fallback to old format
                    datasets = [
                        {
                            label: "Stock In",
                            data: data.in_movements || [],
                            color: "#68BC31",
                            lines: { show: true, fill: false, lineWidth: 2 },
                            points: { show: true, radius: 3 }
                        },
                        {
                            label: "Stock Out", 
                            data: data.out_movements || [],
                            color: "#DA5430",
                            lines: { show: true, fill: false, lineWidth: 2 },
                            points: { show: true, radius: 3 }
                        }
                    ];
                }

                var options = {
                    legend: { 
                        show: true, 
                        position: "nw",
                        backgroundOpacity: 0.85,
                        backgroundColor: "#fff",
                        labelBoxBorderColor: "transparent"
                    },
                    grid: { 
                        show: true, 
                        hoverable: true, 
                        clickable: true,
                        borderWidth: 1,
                        borderColor: "#ddd",
                        backgroundColor: "#fff"
                    },
                    xaxis: $.extend({
                        tickColor: "#ddd",
                        axisLabel: "📅 Date",
                        axisLabelUseCanvas: true,
                        axisLabelFontSizePixels: 14,
                        axisLabelFontFamily: "Arial",
                        axisLabelPadding: 15,
                        axisLabelColour: "#333"
                    }, xAxisOptions),
                    yaxis: { 
                        show: true,
                        tickColor: "#ddd",
                        min: 0,
                        axisLabel: "📦 Quantity (Units)",
                        axisLabelUseCanvas: true,
                        axisLabelFontSizePixels: 14,
                        axisLabelFontFamily: "Arial",
                        axisLabelPadding: 10,
                        axisLabelColour: "#333"
                    }
                };

                console.log('Chart options prepared:', options);
                console.log('About to plot chart...');
                
                $.plot($placeholder, datasets, options);
                console.log('✅ Movement chart plotted successfully!');
            } catch (e) {
                console.error('❌ Error drawing movement chart:', e);
                $('#movement-chart-placeholder').html('<div class="text-center" style="padding: 50px;"><p class="error">Error displaying movement chart: ' + e.message + '</p></div>');
            }
        }

        function drawStockPieChart(data) {
            console.log('Drawing pie chart with data:', data);
            try {
                // Verify the chart container exists and set dimensions
                var $placeholder = $('#stock-pie-placeholder');
                if ($placeholder.length === 0) {
                    console.error('Pie chart placeholder not found!');
                    return;
                }
                
                // Ensure proper dimensions
                $placeholder.css({
                    'width': '100%',
                    'height': '280px',
                    'min-height': '280px'
                });

                var options = {
                    series: {
                        pie: {
                            show: true,
                            radius: 1,
                            label: {
                                show: true,
                                radius: 2/3,
                                formatter: function(label, series) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + 
                                           label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                background: { opacity: 0.8 }
                            }
                        }
                    },
                    legend: { 
                        show: true,
                        position: "se"
                    },
                    grid: {
                        hoverable: true
                    }
                };

                console.log('Plotting pie chart...');
                $.plot($placeholder, data, options);
                console.log('Pie chart plotted successfully');
            } catch (e) {
                console.error('Error drawing pie chart:', e);
                $('#stock-pie-placeholder').html('<div class="text-center" style="padding: 50px;"><p class="error">Error displaying stock distribution: ' + e.message + '</p></div>');
            }
        }

        function drawTopProductsChart(data) {
            console.log('Drawing top products chart with data:', data);
            try {
                // Verify the chart container exists and set dimensions
                var $placeholder = $('#top-products-chart-placeholder');
                if ($placeholder.length === 0) {
                    console.error('Top products chart placeholder not found!');
                    return;
                }
                
                // Ensure proper dimensions
                $placeholder.css({
                    'width': '100%',
                    'height': '280px',
                    'min-height': '280px'
                });

                var productNames = ['Paracetamol', 'Amoxicillin', 'Ibuprofen', 'Vitamin D3', 'Aspirin'];
                
                var dataset = [{
                    label: "Movement Count",
                    data: data,
                    color: "#2091CF",
                    bars: { 
                        show: true, 
                        barWidth: 0.6, 
                        align: "center",
                        fillColor: { colors: ["#2091CF", "#68BC31"] }
                    }
                }];

                var options = {
                    legend: { show: false },
                    grid: { 
                        show: true, 
                        hoverable: true,
                        borderWidth: 1,
                        borderColor: "#ddd"
                    },
                    xaxis: { 
                        show: true,
                        ticks: function() {
                            var ticks = [];
                            for (var i = 0; i < productNames.length; i++) {
                                ticks.push([i, productNames[i]]);
                            }
                            return ticks;
                        }
                    },
                    yaxis: { 
                        show: true,
                        min: 0
                    }
                };

                console.log('Plotting top products chart...');
                $.plot($placeholder, dataset, options);
                console.log('Top products chart plotted successfully');
            } catch (e) {
                console.error('Error drawing top products chart:', e);
                $('#top-products-chart-placeholder').html('<div class="text-center" style="padding: 50px;"><p class="error">Error displaying top products: ' + e.message + '</p></div>');
            }
        }

        function displayStockAlerts(alerts) {
            var html = '';
            if (alerts && alerts.length > 0) {
                alerts.forEach(function(alert) {
                    html += '<tr>';
                    html += '<td>' + alert.product_name + '</td>';
                    html += '<td><span class="label label-warning">' + alert.current_stock + '</span></td>';
                    html += '</tr>';
                });
            } else {
                html = '<tr><td colspan="2" class="text-center">No stock alerts</td></tr>';
            }
            $('#stock-alerts-tbody').html(html);
        }

        function getFilters() {
            return {
                location_id: $('#location_filter').val() || '',
                date_range: $('#date_range').val() || '30_days',
                start_date: $('#start_date').val() || '',
                end_date: $('#end_date').val() || ''
            };
        }

        $("#movement-chart-placeholder, #stock-pie-placeholder, #top-products-chart-placeholder").bind("plothover", function (event, pos, item) {
            if (item) {
                var tooltip = "";
                
                if ($(this).attr('id') === 'movement-chart-placeholder') {
                    // For movement chart with day index
                    var dayIndex = Math.round(item.datapoint[0]);
                    var value = Math.round(item.datapoint[1]);
                    var date = new Date();
                    date.setDate(date.getDate() - (29 - dayIndex));
                    var formattedDate = (date.getMonth() + 1) + '/' + date.getDate();
                    tooltip = item.series.label + " on " + formattedDate + ": " + value + " units";
                } else {
                    // For other charts
                    var x = item.datapoint[0].toFixed(2);
                    var y = item.datapoint[1].toFixed(2);
                    tooltip = item.series.label + ": " + y;
                }

                $("#tooltip").html(tooltip)
                    .css({
                        top: item.pageY + 5, 
                        left: item.pageX + 5,
                        padding: "5px 10px",
                        "background-color": "rgba(0,0,0,0.8)",
                        color: "white",
                        "border-radius": "3px",
                        "font-size": "12px",
                        "white-space": "nowrap"
                    })
                    .fadeIn(200);
            } else {
                $("#tooltip").hide();
            }
        });
    </script>
</body>

</html>
