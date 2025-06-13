<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Batch Transaction - <?= $batch->transaction_number ?></title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-details {
            font-size: 11px;
            color: #666;
        }
        
        .document-title {
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0 10px 0;
            color: #2c5aa0;
        }
        
        .batch-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .batch-info-left,
        .batch-info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .batch-info-right {
            text-align: right;
        }
        
        .info-row {
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        
        .info-value {
            display: inline-block;
        }
        
        .status-badge,
        .type-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            color: white;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-completed { background-color: #28a745; }
        .status-draft { background-color: #6c757d; }
        .status-processing { background-color: #17a2b8; }
        .status-cancelled { background-color: #dc3545; }
        
        .type-receive { background-color: #28a745; }
        .type-release { background-color: #dc3545; }
        .type-transfer { background-color: #17a2b8; }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
        }
        
        .items-table td {
            font-size: 11px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .summary-section {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .summary-table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        
        .summary-table td {
            padding: 5px 10px;
            border-bottom: 1px solid #eee;
        }
        
        .summary-table .total-row {
            font-weight: bold;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
        }
        
        .remarks-section {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f8f9fa;
        }
        
        .remarks-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            font-size: 10px;
        }
        
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        
        .signature-left,
        .signature-right {
            display: table-cell;
            width: 50%;
            vertical-align: bottom;
            height: 80px;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
            height: 60px;
        }
        
        .signature-label {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <div class="no-print" style="text-align: center; margin: 10px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px;">
            <i class="fa fa-print"></i> Print Document
        </button>
    </div>

    <div class="header">
        <div class="company-name"><?= $company_name ?></div>
        <div class="company-details">
            <?= $company_address ?><br>
            <?= $company_contact ?>
        </div>
        <div class="document-title">BATCH TRANSACTION</div>
    </div>

    <div class="batch-info">
        <div class="batch-info-left">
            <div class="info-row">
                <span class="info-label">Transaction #:</span>
                <span class="info-value" style="font-weight: bold; font-size: 14px;"><?= $batch->transaction_number ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Date:</span>
                <span class="info-value"><?= date('F j, Y', strtotime($batch->transaction_date)) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Type:</span>
                <span class="info-value">
                    <span class="type-badge type-<?= strtolower($batch->transaction_type) ?>">
                        <?= $batch->transaction_type ?>
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value">
                    <span class="status-badge status-<?= strtolower($batch->status) ?>">
                        <?= $batch->status ?>
                    </span>
                </span>
            </div>
        </div>
        
        <div class="batch-info-right">
            <div class="info-row">
                <span class="info-label">From Location:</span>
                <span class="info-value"><?= $batch->from_location ?: 'N/A' ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">To Location:</span>
                <span class="info-value"><?= $batch->to_location ?: 'N/A' ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Created By:</span>
                <span class="info-value"><?= $batch->created_by_name ?: 'Unknown' ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Created Date:</span>
                <span class="info-value"><?= date('M j, Y g:i A', strtotime($batch->created_at)) ?></span>
            </div>
            <?php if ($batch->processed_at): ?>
            <div class="info-row">
                <span class="info-label">Processed Date:</span>
                <span class="info-value"><?= date('M j, Y g:i A', strtotime($batch->processed_at)) ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($batch->remarks): ?>
    <div class="remarks-section">
        <div class="remarks-title">Remarks:</div>
        <div><?= nl2br(htmlspecialchars($batch->remarks)) ?></div>
    </div>
    <?php endif; ?>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 15%;">Product Code</th>
                <th style="width: 30%;">Product Name</th>
                <th style="width: 15%;">Category</th>
                <th style="width: 8%;">UOM</th>
                <th style="width: 10%;" class="text-right">Quantity</th>
                <th style="width: 10%;" class="text-right">Unit Cost</th>
                <th style="width: 12%;" class="text-right">Total Cost</th>
                <th style="width: 20%;">Notes</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px; font-style: italic; color: #666;">
                        No items in this batch transaction
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($items as $index => $item): ?>
                <tr>
                    <td class="text-center"><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($item->product_code) ?></td>
                    <td><?= htmlspecialchars($item->product_name) ?></td>
                    <td><?= htmlspecialchars($item->category ?: '-') ?></td>
                    <td class="text-center"><?= htmlspecialchars($item->uom ?: '-') ?></td>
                    <td class="text-right"><?= number_format($item->qty, 2) ?></td>
                    <td class="text-right">₱<?= number_format($item->unit_cost ?: 0, 2) ?></td>
                    <td class="text-right">₱<?= number_format($item->total_cost ?: 0, 2) ?></td>
                    <td style="font-size: 10px;"><?= htmlspecialchars($item->notes ?: '-') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Summary Section -->
    <div class="summary-section">
        <table class="summary-table">
            <tr>
                <td>Total Items:</td>
                <td class="text-right"><strong><?= $batch->total_items ?: 0 ?></strong></td>
            </tr>
            <tr>
                <td>Total Quantity:</td>
                <td class="text-right"><strong><?= number_format($batch->total_qty ?: 0, 2) ?></strong></td>
            </tr>
            <tr class="total-row">
                <td>Total Cost:</td>
                <td class="text-right"><strong>₱<?= number_format($batch->total_cost ?: 0, 2) ?></strong></td>
            </tr>
        </table>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-left">
            <div class="signature-line"></div>
            <div class="signature-label">PREPARED BY</div>
        </div>
        <div class="signature-right">
            <div class="signature-line"></div>
            <div class="signature-label">APPROVED BY</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div style="float: left;">
            Document Generated: <?= date('F j, Y g:i A') ?>
        </div>
        <div style="float: right;">
            <?= $page_name ?> - <?= $company_name ?>
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- Auto-print script -->
    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 500);
        // };
    </script>
</body>
</html>
