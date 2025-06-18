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
        #abc-chart-placeholder,
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
                                        <label>Category:</label>
                                        <select id="category_filter" class="form-control chosen-select" data-placeholder="All Categories">
                                            <option value="">All Categories</option>
                                            <!-- Categories will be loaded via AJAX -->
                                        </select>
                                    </div>
                                    <div class="filter-item">
                                        <label>&nbsp;</label>
                                        <button id="refresh_analytics" class="btn btn-primary form-control">
                                            <i class="fa fa-refresh"></i> Refresh Analytics
                                        </button>
                                    </div>
                                    <div class="filter-item">
                                        <label>&nbsp;</label>
                                        <button id="test_charts" class="btn btn-success form-control">
                                            <i class="fa fa-cogs"></i> Test Charts
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
                                <div class="col-md-6">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">
                                                <i class="fa fa-bar-chart"></i>
                                                ABC Analysis
                                            </h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <div class="chart-container" style="position: relative;">
                                                    <div id="abc-chart-placeholder" class="chart-placeholder"></div>
                                                    <div id="abc-loading" class="loading-overlay" style="display: none;">
                                                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
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

            // Load initial data with mock data for testing
            loadCategories();
            loadDashboardStatsWithMockData();
            loadAllChartsWithMockData();
            loadStockAlertsWithMockData();

            // Refresh button
            $('#refresh_analytics').on('click', function() {
                loadDashboardStatsWithMockData();
                loadAllChartsWithMockData();
                loadStockAlertsWithMockData();
            });

            // Test button
            $('#test_charts').on('click', function() {
                console.log('Testing charts...');
                if (typeof $.plot === 'undefined') {
                    alert('jQuery Flot is not loaded!');
                } else {
                    alert('jQuery Flot is loaded. Charts should work.');
                    loadAllChartsWithMockData();
                }
            });

            // Auto refresh every 5 minutes
            setInterval(function() {
                loadDashboardStatsWithMockData();
                loadStockAlertsWithMockData();
            }, 300000);
        });

        // Mock data functions for testing (replace with real AJAX calls when backend is ready)
        function loadDashboardStatsWithMockData() {
            // Use default currency symbol 'K' as used in other parts of the application
            var currencySymbol = 'K';
            
            $('#total_products').text('247');
            $('#total_stock_value').text(currencySymbol + ' 45,678.90');
            $('#low_stock_items').text('12');
            $('#expired_items').text('3');
        }

        function loadAllChartsWithMockData() {
            loadMovementChartWithMockData();
            loadStockPieChartWithMockData();
            loadABCChartWithMockData();
            loadTopProductsChartWithMockData();
        }

        function loadStockAlertsWithMockData() {
            var mockAlerts = [
                { product_name: 'Paracetamol 500mg', current_stock: 5 },
                { product_name: 'Amoxicillin 250mg', current_stock: 2 },
                { product_name: 'Ibuprofen 400mg', current_stock: 8 },
                { product_name: 'Vitamin D3', current_stock: 3 }
            ];
            displayStockAlerts(mockAlerts);
        }

        function loadMovementChartWithMockData() {
            $('#movement-loading').show();
            
            // Generate mock data for last 30 days - use simple numeric indexing instead of timestamps
            var mockData = {
                in_movements: [],
                out_movements: []
            };
            
            // Create simple numeric data points
            for (var i = 0; i < 30; i++) {
                mockData.in_movements.push([i, Math.floor(Math.random() * 50) + 10]);
                mockData.out_movements.push([i, Math.floor(Math.random() * 40) + 5]);
            }
            
            setTimeout(function() {
                drawMovementChart(mockData);
                $('#movement-loading').hide();
            }, 500);
        }

        function loadStockPieChartWithMockData() {
            $('#pie-loading').show();
            
            var mockData = [
                { label: "Medications", data: 45, color: "#68BC31" },
                { label: "Medical Supplies", data: 25, color: "#2091CF" },
                { label: "Equipment", data: 15, color: "#DA5430" },
                { label: "Consumables", data: 15, color: "#FFC107" }
            ];
            
            setTimeout(function() {
                drawStockPieChart(mockData);
                $('#pie-loading').hide();
            }, 500);
        }

        function loadABCChartWithMockData() {
            $('#abc-loading').show();
            
            var mockData = [
                [1, 120], // Class A
                [2, 80],  // Class B
                [3, 47]   // Class C
            ];
            
            setTimeout(function() {
                drawABCChart(mockData);
                $('#abc-loading').hide();
            }, 500);
        }

        function loadTopProductsChartWithMockData() {
            $('#top-loading').show();
            
            var mockData = [
                { data: [[45, 0]], label: "Paracetamol 500mg" },
                { data: [[38, 1]], label: "Amoxicillin 250mg" },
                { data: [[32, 2]], label: "Ibuprofen 400mg" },
                { data: [[28, 3]], label: "Vitamin D3" },
                { data: [[22, 4]], label: "Aspirin 81mg" }
            ];
            
            setTimeout(function() {
                drawTopProductsChart(mockData);
                $('#top-loading').hide();
            }, 500);
        }

        // Original functions (keep for when backend is ready)
        function loadCategories() {
            // Add some mock categories for now
            var options = '<option value="">All Categories</option>';
            options += '<option value="1">Medications</option>';
            options += '<option value="2">Medical Supplies</option>';
            options += '<option value="3">Equipment</option>';
            options += '<option value="4">Consumables</option>';
            $('#category_filter').html(options).trigger('chosen:updated');
            
            /* Uncomment when backend is ready:
            $.post("<?= base_url(); ?>inventory_analytics/get_categories", {
                [csrf_name]: csrf_hash
            }, function(response) {
                try {
                    var result = JSON.parse(response);
                    if (result.success) {
                        regenerate_csrf(result.csrf_hash);
                        var options = '<option value="">All Categories</option>';
                        result.data.forEach(function(category) {
                            options += '<option value="' + category.id + '">' + category.name + '</option>';
                        });
                        $('#category_filter').html(options).trigger('chosen:updated');
                    }
                } catch (e) {
                    console.error('Error loading categories:', e);
                }
            });
            */
        }

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
                        $('#total_products').text(result.data.total_products || 0);
                        $('#total_stock_value').text(result.data.total_stock_value || '0.00');
                        $('#low_stock_items').text(result.data.low_stock_items || 0);
                        $('#expired_items').text(result.data.expired_items || 0);
                    }
                } catch (e) {
                    console.error('Error loading dashboard stats:', e);
                }
            });
        }

        function loadAllCharts() {
            loadMovementChart();
            loadStockPieChart();
            loadABCChart();
            loadTopProductsChart();
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
                        drawMovementChart(result.data);
                    }
                } catch (e) {
                    console.error('Error loading movement chart:', e);
                } finally {
                    $('#movement-loading').hide();
                }
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
                    }
                } catch (e) {
                    console.error('Error loading stock pie chart:', e);
                } finally {
                    $('#pie-loading').hide();
                }
            });
        }

        function loadABCChart() {
            $('#abc-loading').show();
            var filters = getFilters();

            $.post("<?= base_url(); ?>inventory_analytics/get_abc_analysis", {
                [csrf_name]: csrf_hash,
                ...filters
            }, function(response) {
                try {
                    var result = JSON.parse(response);
                    if (result.success) {
                        regenerate_csrf(result.csrf_hash);
                        drawABCChart(result.data);
                    }
                } catch (e) {
                    console.error('Error loading ABC chart:', e);
                } finally {
                    $('#abc-loading').hide();
                }
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
                    }
                } catch (e) {
                    console.error('Error loading top products chart:', e);
                } finally {
                    $('#top-loading').hide();
                }
            });
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
                }
            });
        }

        function getFilters() {
            var dateRange = $('#date_range').val();
            var filters = {
                date_range: dateRange,
                category_id: $('#category_filter').val()
            };

            if (dateRange === 'custom') {
                filters.date_from = $('#date_from').val();
                filters.date_to = $('#date_to').val();
            }

            return filters;
        }

        function drawMovementChart(data) {
            console.log('Drawing movement chart with data:', data);
            
            var chartData = [];
            
            if (data.in_movements && data.in_movements.length > 0) {
                chartData.push({
                    label: "Stock In",
                    data: data.in_movements,
                    color: "#68BC31",
                    lines: { show: true, fill: false },
                    points: { show: true }
                });
            }
            
            if (data.out_movements && data.out_movements.length > 0) {
                chartData.push({
                    label: "Stock Out",
                    data: data.out_movements,
                    color: "#DA5430",
                    lines: { show: true, fill: false },
                    points: { show: true }
                });
            }

            if (chartData.length === 0) {
                $('#movement-chart-placeholder').html('<div class="text-center" style="padding: 50px;"><p>No movement data available</p></div>');
                return;
            }

            console.log('Chart data prepared:', chartData);

            try {
                $.plot("#movement-chart-placeholder", chartData, {
                    grid: {
                        hoverable: true,
                        clickable: true,
                        borderWidth: 1,
                        backgroundColor: "#ffffff"
                    },
                    legend: {
                        show: true,
                        position: "nw"
                    },
                    xaxis: {
                        min: 0,
                        tickFormatter: function(val, axis) {
                            return "Day " + Math.floor(val + 1);
                        }
                    },
                    yaxis: {
                        min: 0
                    }
                });
                console.log('Movement chart plotted successfully');
            } catch (e) {
                console.error('Error plotting movement chart:', e);
                $('#movement-chart-placeholder').html('<div class="text-center" style="padding: 50px;"><p class="error">Error creating chart: ' + e.message + '</p></div>');
            }
        }

        function drawStockPieChart(data) {
            console.log('Drawing stock pie chart with data:', data);
            
            if (!data || data.length === 0) {
                $('#stock-pie-placeholder').html('<div class="text-center" style="padding: 50px;"><p>No stock distribution data available</p></div>');
                return;
            }

            try {
                $.plot("#stock-pie-placeholder", data, {
                    series: {
                        pie: {
                            show: true,
                            radius: 1,
                            label: {
                                show: true,
                                radius: 3/4,
                                formatter: function(label, series) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + 
                                           label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                background: {
                                    opacity: 0.8
                                }
                            }
                        }
                    },
                    legend: {
                        show: true,
                        position: "se"
                    }
                });
                console.log('Pie chart plotted successfully');
            } catch (e) {
                console.error('Error plotting pie chart:', e);
                $('#stock-pie-placeholder').html('<div class="text-center" style="padding: 50px;"><p class="error">Error creating chart: ' + e.message + '</p></div>');
            }
        }

        function drawABCChart(data) {
            if (!data || data.length === 0) {
                $('#abc-chart-placeholder').html('<div class="text-center" style="padding: 50px;"><p>No ABC analysis data available</p></div>');
                return;
            }

            $.plot("#abc-chart-placeholder", [{
                label: "ABC Analysis",
                data: data,
                color: "#2091CF",
                bars: { show: true, barWidth: 0.6, align: "center" }
            }], {
                grid: {
                    hoverable: true,
                    borderWidth: 1
                },
                xaxis: {
                    ticks: [[1, "Class A"], [2, "Class B"], [3, "Class C"]]
                },
                yaxis: {
                    min: 0
                }
            });
        }

        function drawTopProductsChart(data) {
            if (!data || data.length === 0) {
                $('#top-products-chart-placeholder').html('<div class="text-center" style="padding: 50px;"><p>No top products data available</p></div>');
                return;
            }

            // Handle both formats: array of objects with data property or simple array
            var chartData;
            if (Array.isArray(data) && data[0] && data[0].hasOwnProperty('data')) {
                // Format: [{ data: [[value, index]], label: "name" }, ...]
                chartData = [{
                    label: "Quantity Sold",
                    data: data.map(function(item, index) {
                        return [item.data[0][0], index];
                    }),
                    color: "#68BC31",
                    bars: { show: true, barWidth: 0.6, align: "center", horizontal: true }
                }];
                
                var yTicks = data.map(function(item, index) {
                    return [index, item.label || 'Product ' + (index + 1)];
                });
            } else {
                // Simple format: [[value, index], ...]
                chartData = [{
                    label: "Quantity Sold",
                    data: data,
                    color: "#68BC31",
                    bars: { show: true, barWidth: 0.6, align: "center", horizontal: true }
                }];
                
                var yTicks = data.map(function(item, index) {
                    return [index, 'Product ' + (index + 1)];
                });
            }

            $.plot("#top-products-chart-placeholder", chartData, {
                grid: {
                    hoverable: true,
                    borderWidth: 1
                },
                yaxis: {
                    ticks: yTicks
                },
                xaxis: {
                    min: 0
                }
            });
        }

        function displayStockAlerts(alerts) {
            var html = '';
            
            if (!alerts || alerts.length === 0) {
                html = '<div class="text-center"><p>No stock alerts at this time.</p></div>';
            } else {
                html = '<div class="alert-section">';
                alerts.forEach(function(alert) {
                    html += '<div class="alert-item">';
                    html += '<span class="alert-product">' + alert.product_name + '</span>';
                    html += '<span class="alert-stock">' + alert.current_stock + ' remaining</span>';
                    html += '</div>';
                });
                html += '</div>';
            }
            
            $('#stock-alerts-content').html(html);
        }

        /* 
         * Currency Handling: 
         * When implementing the real backend, get currency symbol from app_details table:
         * SELECT currency_symbol FROM app_details WHERE currency_id = (SELECT currency_id FROM app_details LIMIT 1)
         * For now, using default 'K' as used throughout the application
         */

        // Tooltip for charts
        $("<div id='tooltip'></div>").css({
            position: "absolute",
            display: "none",
            border: "1px solid #fdd",
            padding: "2px",
            "background-color": "#fee",
            opacity: 0.80
        }).appendTo("body");

        $("#movement-chart-placeholder, #stock-pie-placeholder, #abc-chart-placeholder, #top-products-chart-placeholder").bind("plothover", function (event, pos, item) {
            if (item) {
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2);

                $("#tooltip").html(item.series.label + ": " + y)
                    .css({top: item.pageY+5, left: item.pageX+5})
                    .fadeIn(200);
            } else {
                $("#tooltip").hide();
            }
        });
    </script>
</body>

</html>
