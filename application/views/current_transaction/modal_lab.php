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
                </button>                <button type="button" id="btn_save_result_set" class="btn btn-primary">
                    <i class="fa fa-save"></i> Save Parameter
                </button>
            </div>
        </div>
    </div>
</div>
