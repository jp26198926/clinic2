<!DOCTYPE html>
<html>
<head>
    <title>Laboratory Result - <?php echo $lab_data['patient_name']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .patient-info {
            margin-bottom: 20px;
        }
        .test-info {
            margin-bottom: 20px;
        }
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .results-table th, .results-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .results-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .interpretation {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 4px solid #007bff;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .signatures {
            margin-top: 40px;
        }
        .signature-line {
            margin-top: 30px;
            text-align: center;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LABORATORY RESULT REPORT</h2>
        <p><strong><?php echo isset($clinic_name) ? $clinic_name : 'Medical Clinic'; ?></strong></p>
    </div>

    <div class="patient-info">
        <h3>Patient Information</h3>
        <p><strong>Patient Name:</strong> <?php echo $lab_data['patient_name']; ?></p>
        <p><strong>Service/Test Ordered:</strong> <?php echo $lab_data['product_name']; ?></p>
    </div>

    <div class="test-info">
        <h3>Test Information</h3>
        <table style="width: 100%;">
            <tr>
                <td><strong>Test Name:</strong> <?php echo $lab_data['test_name']; ?></td>
                <td><strong>Test Date:</strong> <?php echo date('F j, Y', strtotime($lab_data['test_date'])); ?></td>
            </tr>
            <tr>
                <td><strong>Laboratory:</strong> <?php echo $lab_data['lab_provider'] ?: 'In-house'; ?></td>
                <td><strong>Performed By:</strong> <?php echo $lab_data['performed_by'] ?: 'Lab Staff'; ?></td>
            </tr>
        </table>
    </div>

    <?php if (!empty($lab_data['lab_parameters'])): ?>
    <div class="results">
        <h3>Laboratory Results</h3>
        <table class="results-table">
            <thead>
                <tr>
                    <th>Parameter</th>
                    <th>Result</th>
                    <th>Unit</th>
                    <th>Reference Range</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lab_data['lab_parameters'] as $param): ?>
                <tr>
                    <td><?php echo htmlspecialchars($param['name']); ?></td>
                    <td><strong><?php echo htmlspecialchars($param['value']); ?></strong></td>
                    <td><?php echo htmlspecialchars($param['unit']); ?></td>
                    <td><?php echo htmlspecialchars($param['reference_range']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <?php if (!empty($lab_data['interpretation'])): ?>
    <div class="interpretation">
        <h4>Clinical Interpretation</h4>
        <p><?php echo nl2br(htmlspecialchars($lab_data['interpretation'])); ?></p>
    </div>
    <?php endif; ?>

    <?php if (!empty($lab_data['notes'])): ?>
    <div class="interpretation">
        <h4>Additional Notes</h4>
        <p><?php echo nl2br(htmlspecialchars($lab_data['notes'])); ?></p>
    </div>
    <?php endif; ?>

    <div class="signatures">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; text-align: center;">
                    <div class="signature-line">
                        <div style="border-bottom: 1px solid #333; margin-bottom: 5px; height: 40px;"></div>
                        <p><strong>Laboratory Technician</strong></p>
                        <p><?php echo $lab_data['performed_by'] ?: 'Lab Staff'; ?></p>
                    </div>
                </td>
                <td style="width: 50%; text-align: center;">
                    <div class="signature-line">
                        <div style="border-bottom: 1px solid #333; margin-bottom: 5px; height: 40px;"></div>
                        <p><strong>Reviewing Physician</strong></p>
                        <p>Date: _______________</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Report generated on <?php echo date('F j, Y \a\t g:i A'); ?></p>
        <p>This is a computer-generated laboratory result.</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fa fa-print"></i> Print Report
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            <i class="fa fa-times"></i> Close
        </button>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            // Uncomment the line below if you want auto-print
            // window.print();
        }
    </script>
</body>
</html>
