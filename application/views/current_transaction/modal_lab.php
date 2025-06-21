<!-- Lab Result Modal -->
<div id='modal_lab' class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger">
                    <i class="fa fa-flask"></i>
                    Laboratory Results
                    <span id="lab_patient_name" class="label label-info"></span>
                    <br>
                    <small id="lab_product_info" class="text-muted"></small>
                </h4>
            </div>
            <div class="modal-body">

                <div class="col-md-12 text-center modal_error" style='display:none'>
                    <div class="alert alert-danger">
                        <strong>
                            <i class="ace-icon fa fa-times"></i>
                        </strong>
                        <span class="modal_error_msg">
                            Error: Critical Error Encountered!
                        </span>
                        <br />
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs" id="lab_tabs" role="tablist">
                            <li class="nav-item active">
                                <a class="nav-link active" id="upload-tab" data-toggle="tab" href="#upload_section" role="tab">
                                    <i class="fa fa-upload"></i> File Upload
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="digital-tab" data-toggle="tab" href="#digital_section" role="tab">
                                    <i class="fa fa-keyboard-o"></i> Digital Entry
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content" id="lab_tab_content" style="margin-top: 15px;">
                    
                    <!-- File Upload Tab -->
                    <div class="tab-pane fade in active" id="upload_section" role="tabpanel">
                        <div class="row" style="margin-bottom: 2em; border-bottom: 1px solid #ddd; padding-bottom: 1em;">
                            <div class="col-md-12">
                                <h5><i class="fa fa-upload"></i> Upload Laboratory Results</h5>
                                <form id="lab_upload_form" enctype="multipart/form-data">
                                    <input type="hidden" id="lab_item_id" name="item_id" value="">
                                    <input type="hidden" id="lab_transaction_id" name="transaction_id" value="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Test Name/Type <span class="text-danger">*</span></label>
                                            <input type="text" id="lab_test_name" name="test_name" class="form-control" placeholder="e.g., Complete Blood Count, Urinalysis, etc." readonly required>
                                            <small class="text-muted">Based on selected product/service</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Test Date <span class="text-danger">*</span></label>
                                            <input type="text" id="lab_test_date" name="test_date" class="form-control datepicker" readonly required>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-6">
                                            <label>Laboratory/Provider</label>
                                            <input type="text" id="lab_provider" name="lab_provider" class="form-control" placeholder="Laboratory name or provider">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Notes/Comments</label>
                                            <textarea id="lab_notes" name="notes" class="form-control" rows="2" placeholder="Additional notes about the test or results"></textarea>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-8">
                                            <label>Select Files <span class="text-danger">*</span></label>
                                            <input type="file" id="lab_files" name="lab_files[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                                            <small class="text-muted">Supported formats: PDF, JPG, PNG, DOC, DOCX (Max 10MB per file)</small>
                                        </div>
                                        <div class="col-md-4" style="padding-top: 25px;">
                                            <button type="submit" id="btn_upload_lab" class="btn btn-success">
                                                <i class="fa fa-upload"></i> Upload Results
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Digital Entry Tab -->
                    <div class="tab-pane fade" id="digital_section" role="tabpanel">
                        <div class="row" style="margin-bottom: 2em; border-bottom: 1px solid #ddd; padding-bottom: 1em;">
                            <div class="col-md-12">
                                <h5><i class="fa fa-keyboard-o"></i> Digital Laboratory Results Entry</h5>
                                <form id="lab_digital_form">
                                    <input type="hidden" id="digital_item_id" name="item_id" value="">
                                    <input type="hidden" id="digital_transaction_id" name="transaction_id" value="">
                                    <input type="hidden" name="entry_type" value="digital">
                                    
                                    <!-- Basic Test Information -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Test/Service <span class="text-danger">*</span></label>
                                            <input type="text" id="digital_test_name" name="test_name" class="form-control" readonly required>
                                            <small class="text-muted">Based on selected product/service</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Test Date <span class="text-danger">*</span></label>
                                            <input type="text" id="digital_test_date" name="test_date" class="form-control datepicker" readonly required>
                                        </div>
                                    </div>
                                    
                                    <!-- Lab Results Data -->
                                    <div class="row" style="margin-top: 15px;">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h6><strong>Laboratory Results</strong></h6>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <button type="button" id="btn_add_result_set" class="btn btn-sm btn-success" title="Add New Result Parameter">
                                                        <i class="fa fa-plus"></i> Add Result Set
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="lab_parameters_table" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr class="bg-light">
                                                            <th width="25%">Parameter</th>
                                                            <th width="35%">Result Value <span class="text-danger">*</span></th>
                                                            <th width="25%">Unit & Reference</th>
                                                            <th width="15%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted">
                                                                <i class="fa fa-spinner fa-spin"></i> Loading parameters...
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Interpretation and Notes -->
                                    <div class="row" style="margin-top: 15px;">
                                        <div class="col-md-6">
                                            <label>Clinical Interpretation</label>
                                            <textarea id="digital_interpretation" name="interpretation" class="form-control" rows="3" placeholder="Clinical interpretation of results"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Additional Notes/Comments</label>
                                            <textarea id="digital_notes" name="notes" class="form-control" rows="3" placeholder="Additional notes or comments"></textarea>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="row" style="margin-top: 15px;">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" id="btn_save_digital" class="btn btn-primary">
                                                <i class="fa fa-save"></i> Save Digital Results
                                            </button>
                                            <button type="button" id="btn_save_and_print" class="btn btn-info">
                                                <i class="fa fa-print"></i> Save & Print
                                            </button>
                                            <button type="button" id="btn_clear_digital" class="btn btn-warning">
                                                <i class="fa fa-refresh"></i> Clear Form
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lab Results List -->
                <div class="row">
                    <div class="col-md-12">
                        <h5><i class="fa fa-list"></i> Laboratory Results History</h5>
                        <div class="table-responsive">
                            <table id="tbl_lab_results" class="table table-sm table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="12%">Test Date</th>
                                        <th class="text-center" width="18%">Test Name</th>
                                        <th class="text-center" width="12%">Laboratory</th>
                                        <th class="text-center" width="10%">Type</th>
                                        <th class="text-center" width="15%">Files/Results</th>
                                        <th class="text-center" width="13%">Entry Date</th>
                                        <th class="text-center" width="20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            <i class="fa fa-spinner fa-spin"></i> Loading laboratory results...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Result Set Modal (nested modal) -->
<div id='modal_add_result_set' class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger">
                    <i class="fa fa-plus"></i>
                    Add New Result Parameter
                </h4>
            </div>
            <div class="modal-body">
                <form id="add_result_set_form">
                    <input type="hidden" id="result_set_product_id" name="product_id" value="">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label>Parameter Name <span class="text-danger">*</span></label>
                            <input type="text" id="result_label" name="result_label" class="form-control" placeholder="e.g., Hemoglobin, White Blood Cell Count, etc." required>
                        </div>
                    </div>
                    
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-6">
                            <label>Unit</label>
                            <input type="text" id="unit" name="unit" class="form-control" placeholder="e.g., g/dL, cells/µL, mg/dL">
                        </div>
                        <div class="col-md-6">
                            <label>Reference Range</label>
                            <input type="text" id="reference" name="reference" class="form-control" placeholder="e.g., 12-16 g/dL, 4000-11000 cells/µL">
                        </div>
                    </div>
                    
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <label>Description/Notes</label>
                            <textarea id="description" name="description" class="form-control" rows="2" placeholder="Additional information about this parameter"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times"></i> Cancel
                </button>
                <button type="button" id="btn_save_result_set" class="btn btn-primary">
                    <i class="fa fa-save"></i> Save Parameter
                </button>
            </div>
        </div>
    </div>
</div>

<script>
var base_url = "<?= base_url(); ?>";

$(document).ready(function() {
    // Initialize datepickers
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

    // Set default dates to today
    var today = new Date().toISOString().split('T')[0];
    $('#lab_test_date, #digital_test_date').val(today);

    // Clear digital form
    $('#btn_clear_digital').click(function() {
        $('#lab_digital_form')[0].reset();
        // Clear result values but keep the parameters
        $('#lab_parameters_table tbody input[name="result_value[]"]').val('');
        // Restore the product name
        $('#digital_test_name').val($('#digital_test_name').data('product-name') || '');
    });
    
    // Handle digital form submission
    $('#lab_digital_form').submit(function(e) {
        e.preventDefault();
        
        // Validate form before submission
        if (!validateDigitalForm()) {
            return false;
        }
        
        var formData = new FormData(this);
        
        // Add result parameters data
        var resultSetIds = [];
        var resultValues = [];
        
        $('#lab_parameters_table tbody tr').each(function() {
            var resultSetId = $(this).find('input[name="result_set_id[]"]').val();
            var resultValue = $(this).find('input[name="result_value[]"]').val();
            
            if (resultSetId && resultValue !== '') {
                resultSetIds.push(resultSetId);
                resultValues.push(resultValue);
            }
        });
        
        // Add arrays to form data
        for (var i = 0; i < resultSetIds.length; i++) {
            formData.append('result_set_id[]', resultSetIds[i]);
            formData.append('result_value[]', resultValues[i]);
        }
        
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
                    toastr.success('Digital laboratory results saved successfully!');
                    $('#lab_digital_form')[0].reset();
                    $('#lab_parameters_table tbody input[name="result_value[]"]').val('');
                    // Restore the product name
                    $('#digital_test_name').val($('#digital_test_name').data('product-name') || '');
                    loadLabResults(); // Refresh the results list
                } else {
                    toastr.error('Error: ' + (response.error || 'Failed to save digital results'));
                }
            },
            error: function() {
                toastr.error('Error: Failed to communicate with server');
            },
            complete: function() {
                $('#btn_save_digital').prop('disabled', false).html('<i class="fa fa-save"></i> Save Digital Results');
            }
        });
    });
    
    // Handle save and print
    $('#btn_save_and_print').click(function() {
        // Validate form before submission
        if (!validateDigitalForm()) {
            return false;
        }
        
        var formData = new FormData($('#lab_digital_form')[0]);
        
        // Add result parameters data
        var resultSetIds = [];
        var resultValues = [];
        
        $('#lab_parameters_table tbody tr').each(function() {
            var resultSetId = $(this).find('input[name="result_set_id[]"]').val();
            var resultValue = $(this).find('input[name="result_value[]"]').val();
            
            if (resultSetId && resultValue !== '') {
                resultSetIds.push(resultSetId);
                resultValues.push(resultValue);
            }
        });
        
        // Add arrays to form data
        for (var i = 0; i < resultSetIds.length; i++) {
            formData.append('result_set_id[]', resultSetIds[i]);
            formData.append('result_value[]', resultValues[i]);
        }
        
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
                    toastr.success('Digital laboratory results saved successfully!');
                    // Open print window
                    var printUrl = base_url + 'current_transaction/lab_print_digital';
                    var printForm = $('<form method="post" target="_blank" action="' + printUrl + '">' +
                                     '<input type="hidden" name="lab_id" value="' + response.lab_id + '">' +
                                     '</form>');
                    $('body').append(printForm);
                    printForm.submit();
                    printForm.remove();
                    
                    // Clear form
                    $('#lab_digital_form')[0].reset();
                    $('#lab_parameters_table tbody input[name="result_value[]"]').val('');
                    // Restore the product name
                    $('#digital_test_name').val($('#digital_test_name').data('product-name') || '');
                    loadLabResults(); // Refresh the results list
                } else {
                    toastr.error('Error: ' + (response.error || 'Failed to save digital results'));
                }
            },
            error: function() {
                toastr.error('Error: Failed to communicate with server');
            },
            complete: function() {
                $('#btn_save_and_print').prop('disabled', false).html('<i class="fa fa-print"></i> Save & Print');
            }
        });
    });

    // Handle file upload form submission
    $('#lab_upload_form').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: base_url + 'current_transaction/lab_upload',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#btn_upload_lab').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Uploading...');
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Laboratory results uploaded successfully!');
                    $('#lab_upload_form')[0].reset();
                    loadLabResults(); // Refresh the results list
                } else {
                    toastr.error('Error: ' + (response.error || 'Failed to upload laboratory results'));
                }
            },
            error: function() {
                toastr.error('Error: Failed to communicate with server');
            },
            complete: function() {
                $('#btn_upload_lab').prop('disabled', false).html('<i class="fa fa-upload"></i> Upload Results');
            }
        });
    });
    
    // Function to load lab results for the current item
    function loadLabResults() {
        var itemId = $('#lab_item_id').val() || $('#digital_item_id').val();
        if (!itemId) return;
        
        $.post(base_url + 'current_transaction/lab_list', {
            item_id: itemId
        }, function(response) {
            if (response.success && response.results) {
                updateLabResultsTable(response.results);
            } else {
                $('#tbl_lab_results tbody').html('<tr><td colspan="7" class="text-center text-muted">No laboratory results found</td></tr>');
            }
        }, 'json').fail(function() {
            $('#tbl_lab_results tbody').html('<tr><td colspan="7" class="text-center text-danger">Error loading laboratory results</td></tr>');
        });
    }

    // Function to load result sets for digital entry
    function loadResultSets(product_id) {
        if (!product_id) {
            $('#lab_parameters_table tbody').html('<tr><td colspan="4" class="text-center text-muted">No result parameters defined for this service</td></tr>');
            return;
        }
        
        $.post(base_url + 'current_transaction/lab_get_result_sets', {
            product_id: product_id
        }, function(response) {
            if (response.success && response.result_sets && response.result_sets.length > 0) {
                let tableBody = '';
                response.result_sets.forEach(function(resultSet) {
                    tableBody += `
                        <tr>
                            <td>
                                <input type="hidden" name="result_set_id[]" value="${resultSet.id}">
                                <strong>${resultSet.result_label}</strong>
                                ${resultSet.description ? '<br><small class="text-muted">' + resultSet.description + '</small>' : ''}
                            </td>
                            <td>
                                <input type="text" name="result_value[]" class="form-control" placeholder="Enter result value">
                            </td>
                            <td>
                                <span class="text-muted">${resultSet.unit || ''}</span>
                                ${resultSet.reference ? '<br><small>Ref: ' + resultSet.reference + '</small>' : '<br><small>Ref: N/A</small>'}
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-xs btn-danger delete-result-set" data-result-set-id="${resultSet.id}" title="Delete Parameter">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                $('#lab_parameters_table tbody').html(tableBody);
            } else {
                $('#lab_parameters_table tbody').html(`
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No result parameters defined for this service
                            <br><small>Click "Add Result Set" to add parameters</small>
                        </td>
                    </tr>
                `);
            }
        }, 'json').fail(function() {
            $('#lab_parameters_table tbody').html('<tr><td colspan="4" class="text-center text-danger">Error loading result parameters</td></tr>');
        });
    }
    
});

// Form validation function
function validateDigitalForm() {
    var testName = $('#digital_test_name').val().trim();
    var testDate = $('#digital_test_date').val().trim();
    var hasResults = false;
    
    // Check required fields
    if (!testName) {
        toastr.error('Test/Service name is required');
        $('#digital_test_name').focus();
        return false;
    }
    
    if (!testDate) {
        toastr.error('Test date is required');
        $('#digital_test_date').focus();
        return false;
    }
    
    // Check if at least one result parameter has a value
    $('#lab_parameters_table tbody tr').each(function() {
        var resultValue = $(this).find('input[name="result_value[]"]').val();
        if (resultValue && resultValue.trim() !== '') {
            hasResults = true;
            return false; // break out of each loop
        }
    });
    
    if (!hasResults) {
        toastr.error('Please enter at least one result value');
        return false;
    }
    
    return true;
}

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

    // Handle file downloads
    $(document).on('click', '.download-file', function() {
        var fileName = $(this).data('file');
        var downloadUrl = base_url + 'current_transaction/lab_download_file?file=' + fileName;
        window.open(downloadUrl, '_blank');
    });

    // Handle lab result deletion
    $(document).on('click', '.delete-lab', function() {
        var labId = $(this).data('lab-id');
        if (confirm('Are you sure you want to delete this laboratory result?')) {
            $.post(base_url + 'current_transaction/lab_delete', {
                lab_id: labId
            }, function(response) {
                if (response.success) {
                    toastr.success('Laboratory result deleted successfully!');
                    loadLabResults(); // Refresh the results list
                } else {
                    toastr.error('Error: ' + (response.error || 'Failed to delete laboratory result'));
                }
            }, 'json').fail(function() {
                toastr.error('Error: Failed to communicate with server');
            });
        }
    });
}
</script>
