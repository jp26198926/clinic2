<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $report_title; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 10px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-details {
            font-size: 9px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .report-title {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 10px 0;
            color: #2c3e50;
        }
        
        .report-info {
            background: #f8f9fa;
            padding: 8px;
            margin-bottom: 15px;
            border-left: 3px solid #007bff;
            font-size: 9px;
        }
        
        .filters-applied {
            margin-bottom: 15px;
            font-size: 9px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 8px;
            border-radius: 3px;
        }
            font-size: 10px;
            background: #fff3cd;
            padding: 8px;
            border-left: 4px solid #ffc107;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .row-number {
            width: 30px;
            text-align: center;
            background-color: #f1f3f4;
        }
        
        .currency {
            text-align: right;
            font-family: monospace;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .badge-low { background: #fff3cd; color: #856404; }
        .badge-critical { background: #f8d7da; color: #721c24; }
        .badge-expired { background: #f8d7da; color: #721c24; }
        .badge-expiring { background: #fff3cd; color: #856404; }
        .badge-zero { background: #e2e3e5; color: #383d41; }
        
        .summary-stats {
            margin-bottom: 15px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .stat-item {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            flex: 1;
            min-width: 120px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            display: block;
        }
        
        .stat-label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            background: white;
            padding: 5px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            .footer {
                position: fixed;
                bottom: 0;
            }
        }
<?php
// Prepare report-specific data based on report type
$filters_applied = [];
if (!empty($location_name) && $location_name !== 'All Locations') {
    $filters_applied[] = "Location: {$location_name}";
}
if (!empty($date_from)) {
    $filters_applied[] = "From: {$date_from}";
}
if (!empty($date_to)) {
    $filters_applied[] = "To: {$date_to}";
}

// Calculate summary statistics
$total_records = count($report_data);
$summary_stats = [];

if ($report_type === 'low_stock') {
    $critical_count = 0;
    $low_count = 0;
    foreach ($report_data as $item) {
        if (isset($item->stock_percentage) && $item->stock_percentage < 25) {
            $critical_count++;
        } elseif (isset($item->stock_percentage) && $item->stock_percentage < 50) {
            $low_count++;
        }
    }
    $summary_stats = [
        ['label' => 'Total Items', 'value' => $total_records],
        ['label' => 'Critical (<25%)', 'value' => $critical_count],
        ['label' => 'Low (25-50%)', 'value' => $low_count]
    ];
} elseif ($report_type === 'stock_valuation') {
    $total_value = 0;
    foreach ($report_data as $item) {
        if (isset($item->total_value)) {
            $total_value += $item->total_value;
        }
    }
    $summary_stats = [
        ['label' => 'Total Items', 'value' => $total_records],
        ['label' => 'Total Value', 'value' => $currency_symbol . number_format($total_value, 2)]
    ];
}
?>
</style>
</head>
<body>
    <div class="header">
        <div class="company-name"><?= $company_name ?? 'Company Name'; ?></div>
        <div class="company-details">
            <?= $company_address ?? 'Company Address'; ?>
        </div>
        <div class="report-title"><?= $report_title; ?></div>
    </div>
    
    <div class="report-info">
        <strong>Generated:</strong> <?= $generated_date; ?> | 
        <strong>User:</strong> <?= $generated_by; ?> | 
        <strong>Location:</strong> <?= $location_name; ?> |
        <strong>Total Records:</strong> <?= $total_records; ?>
    </div>
    
    <?php if (!empty($filters_applied)): ?>
    <div class="filters-applied">
        <strong>Filters Applied:</strong> <?= implode(' | ', $filters_applied); ?>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($summary_stats)): ?>
    <div class="summary-stats">
        <?php foreach($summary_stats as $stat): ?>
        <div class="stat-item">
            <span class="stat-value"><?= $stat['value']; ?></span>
            <span class="stat-label"><?= $stat['label']; ?></span>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($report_data)): ?>
    <table>
        <thead>
            <tr>
                <th class="row-number">#</th>
                <?php if ($report_type === 'low_stock'): ?>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Current Stock</th>
                    <th>Min Level</th>
                    <th>Shortage</th>
                    <th>Stock %</th>
                    <th>UOM</th>
                <?php elseif ($report_type === 'stock_valuation'): ?>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Stock Qty</th>
                    <th>Unit Cost</th>
                    <th>Total Value</th>
                    <th>UOM</th>
                <?php elseif ($report_type === 'expiring_stock'): ?>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Stock Qty</th>
                    <th>Expiry Date</th>
                    <th>Days to Expire</th>
                    <th>Total Value</th>
                    <th>UOM</th>
                <?php elseif ($report_type === 'expired_stock'): ?>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Stock Qty</th>
                    <th>Expiry Date</th>
                    <th>Days Expired</th>
                    <th>Total Value</th>
                    <th>UOM</th>
                <?php elseif ($report_type === 'zero_stock'): ?>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Stock Qty</th>
                    <th>Min Level</th>
                    <th>UOM</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($report_data as $index => $item): ?>
            <tr>
                <td class="row-number"><?= $index + 1; ?></td>
                <?php if ($report_type === 'low_stock'): ?>
                    <td><?= $item->product_code ?? 'N/A'; ?></td>
                    <td><?= $item->product_name ?? 'N/A'; ?></td>
                    <td><?= $item->category ?? 'N/A'; ?></td>
                    <td><?= $item->location ?? 'N/A'; ?></td>
                    <td class="number"><?= number_format($item->qty_in_stock ?? 0); ?></td>
                    <td class="number"><?= number_format($item->min_level ?? 0); ?></td>
                    <td class="number critical"><?= number_format($item->shortage_qty ?? 0); ?></td>
                    <td class="number <?= ($item->stock_percentage ?? 0) < 25 ? 'critical' : (($item->stock_percentage ?? 0) < 50 ? 'warning' : ''); ?>">
                        <?= number_format($item->stock_percentage ?? 0, 1); ?>%
                    </td>
                    <td><?= $item->uom ?? 'N/A'; ?></td>
                <?php elseif ($report_type === 'stock_valuation'): ?>
                    <td><?= $item->product_code ?? 'N/A'; ?></td>
                    <td><?= $item->product_name ?? 'N/A'; ?></td>
                    <td><?= $item->category ?? 'N/A'; ?></td>
                    <td><?= $item->location ?? 'N/A'; ?></td>
                    <td class="number"><?= number_format($item->qty_in_stock ?? 0); ?></td>
                    <td class="number"><?= $currency_symbol . number_format($item->cost_per_unit ?? 0, 2); ?></td>
                    <td class="number currency"><?= $currency_symbol . number_format($item->total_value ?? 0, 2); ?></td>
                    <td><?= $item->uom ?? 'N/A'; ?></td>
                <?php elseif ($report_type === 'expiring_stock'): ?>
                    <td><?= $item->product_code ?? 'N/A'; ?></td>
                    <td><?= $item->product_name ?? 'N/A'; ?></td>
                    <td><?= $item->category ?? 'N/A'; ?></td>
                    <td><?= $item->location ?? 'N/A'; ?></td>
                    <td class="number"><?= number_format($item->qty_in_stock ?? 0); ?></td>
                    <td><?= $item->expiration_date_formatted ?? 'N/A'; ?></td>
                    <td class="number warning"><?= $item->days_to_expiry ?? 0; ?> days</td>
                    <td class="number currency"><?= $currency_symbol . number_format($item->total_value ?? 0, 2); ?></td>
                    <td><?= $item->uom ?? 'N/A'; ?></td>
                <?php elseif ($report_type === 'expired_stock'): ?>
                    <td><?= $item->product_code ?? 'N/A'; ?></td>
                    <td><?= $item->product_name ?? 'N/A'; ?></td>
                    <td><?= $item->category ?? 'N/A'; ?></td>
                    <td><?= $item->location ?? 'N/A'; ?></td>
                    <td class="number"><?= number_format($item->qty_in_stock ?? 0); ?></td>
                    <td><?= $item->expiration_date_formatted ?? 'N/A'; ?></td>
                    <td class="number critical"><?= $item->days_expired ?? 0; ?> days ago</td>
                    <td class="number currency"><?= $currency_symbol . number_format($item->total_value ?? 0, 2); ?></td>
                    <td><?= $item->uom ?? 'N/A'; ?></td>
                <?php elseif ($report_type === 'zero_stock'): ?>
                    <td><?= $item->product_code ?? 'N/A'; ?></td>
                    <td><?= $item->product_name ?? 'N/A'; ?></td>
                    <td><?= $item->category ?? 'N/A'; ?></td>
                    <td><?= $item->location ?? 'N/A'; ?></td>
                    <td class="number critical"><?= number_format($item->qty_in_stock ?? 0); ?></td>
                    <td class="number"><?= number_format($item->min_level ?? 0); ?></td>
                    <td><?= $item->uom ?? 'N/A'; ?></td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="text-align: center; padding: 40px; color: #666; background: #f8f9fa; border-radius: 5px;">
        <h3 style="margin-bottom: 10px;">No Data Available</h3>
        <p style="margin: 0;">No records found matching the selected criteria for this report.</p>
    </div>
    <?php endif; ?>
    
    <div class="footer">
        <?= $app_title ?? 'Clinic System'; ?> - <?= $report_title; ?> | Generated on <?= date('Y-m-d H:i:s'); ?>
    </div>
</body>
</html>
