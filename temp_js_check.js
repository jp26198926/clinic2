$(document).ready(function() {
    // Clear digital form
    $('#btn_clear_digital').click(function() {
        $('#lab_digital_form')[0].reset();
        // Clear result values but keep the parameters
        $('#lab_parameters_table tbody input[name="result_value[]"]').val('');
        // Restore the product name and user name
        $('#digital_test_name').val($('#digital_test_name').data('product-name') || '');
        $('#digital_performed_by').val($('#digital_performed_by').data('user-name') || '');
    });
    
    // Handle digital form submission
    $('#lab_digital_form').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: base_url + 'current_transaction/lab_save_digital',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#btn_save_digital').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            },
            success: function(response) {
                if (response.success) {
                    alert('Digital laboratory results saved successfully!');
                    $('#lab_digital_form')[0].reset();
                    $('#lab_parameters_table tbody input[name="result_value[]"]').val('');
                    // Restore the product name and user name
                    $('#digital_test_name').val($('#digital_test_name').data('product-name') || '');
                    $('#digital_performed_by').val($('#digital_performed_by').data('user-name') || '');
                    loadLabResults(); // Refresh the results list
                } else {
                    alert('Error: ' + (response.error || 'Failed to save digital results'));
                }
            },
            error: function() {
                alert('Error: Failed to communicate with server');
            },
            complete: function() {
                $('#btn_save_digital').prop('disabled', false).html('<i class="fa fa-save"></i> Save Digital Results');
            }
        });
    });
    
    // Handle save and print
    $('#btn_save_and_print').click(function() {
        var formData = new FormData($('#lab_digital_form')[0]);
        
        $.ajax({
            url: base_url + 'current_transaction/lab_save_digital',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#btn_save_and_print').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
            },
            success: function(response) {
                if (response.success) {
                    alert('Digital laboratory results saved successfully!');
                    // Open print window
                    var printUrl = base_url + 'current_transaction/lab_print_view?lab_id=' + response.lab_id;
                    window.open(printUrl, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
                    
                    // Clear form
                    $('#lab_digital_form')[0].reset();
                    $('#lab_parameters_table tbody input[name="result_value[]"]').val('');
                    // Restore the product name and user name
                    $('#digital_test_name').val($('#digital_test_name').data('product-name') || '');
                    $('#digital_performed_by').val($('#digital_performed_by').data('user-name') || '');
                    loadLabResults(); // Refresh the results list
                } else {
                    alert('Error: ' + (response.error || 'Failed to save digital results'));
                }
            },
            error: function() {
                alert('Error: Failed to communicate with server');
            },
            complete: function() {
                $('#btn_save_and_print').prop('disabled', false).html('<i class="fa fa-print"></i> Save & Print');
            }
        });
    });
    
});

// Function to update the results table display for both types
function updateLabResultsTable(results) {
    var tbody = $('#tbl_lab_results tbody');
    tbody.empty();
    
    if (results.length === 0) {
        tbody.append('<tr><td colspan="7" class="text-center text-muted">No laboratory results found</td></tr>');
        return;
    }
    
    $.each(results, function(index, lab) {
        var testDate = lab.test_date ? new Date(lab.test_date).toLocaleDateString() : '';
        var entryDate = lab.created_at ? new Date(lab.created_at).toLocaleDateString() : '';
        
        var filesOrResults = '';
        if (lab.entry_type === 'digital') {
            var paramCount = lab.lab_parameters ? lab.lab_parameters.length : 0;
            filesOrResults = '<span class="label label-info">' + paramCount + ' parameters</span>';
        } else {
            var fileCount = lab.files ? lab.files.length : 0;
            filesOrResults = '<span class="label label-success">' + fileCount + ' files</span>';
        }
        
        var actions = '';
        if (lab.entry_type === 'digital') {
            actions += '<button class="btn btn-sm btn-info print-digital" data-lab-id="' + lab.id + '" title="Print Results">';
            actions += '<i class="fa fa-print"></i></button> ';
        } else {
            // Add download/view actions for uploaded files
            if (lab.files && lab.files.length > 0) {
                $.each(lab.files, function(i, file) {
                    actions += '<button class="btn btn-sm btn-success download-file" data-file="' + file.file_name + '" title="Download ' + file.original_name + '">';
                    actions += '<i class="fa fa-download"></i></button> ';
                });
            }
        }
        actions += '<button class="btn btn-sm btn-danger delete-lab" data-lab-id="' + lab.id + '" title="Delete">';
        actions += '<i class="fa fa-trash"></i></button>';
        
        var row = '<tr>' +
            '<td class="text-center">' + testDate + '</td>' +
            '<td>' + (lab.test_name || '') + '</td>' +
            '<td>' + (lab.lab_provider || '') + '</td>' +
            '<td class="text-center">' + lab.display_type + '</td>' +
            '<td class="text-center">' + filesOrResults + '</td>' +
            '<td class="text-center">' + entryDate + '</td>' +
            '<td class="text-center">' + actions + '</td>' +
            '</tr>';
        
        tbody.append(row);
    });

    // Handle print digital results
    $(document).on('click', '.print-digital', function() {
        var labId = $(this).data('lab-id');
        var printUrl = base_url + 'current_transaction/lab_print_view?lab_id=' + labId;
        window.open(printUrl, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
    });

}); // End of $(document).ready()
</script>
