<!-- Lab Result Modal -->
<div id='modal_lab' class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger">
                    <i class="fa fa-flask"></i>
                    Laboratory Results
                    <span id="lab_patient_name" class="label label-info"></span>
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

                <!-- Upload Section -->
                <div class="row" style="margin-bottom: 2em; border-bottom: 1px solid #ddd; padding-bottom: 1em;">
                    <div class="col-md-12">
                        <h5><i class="fa fa-upload"></i> Upload Laboratory Results</h5>
                        <form id="lab_upload_form" enctype="multipart/form-data">
                            <input type="hidden" id="lab_transaction_id" name="transaction_id" value="">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Test Name/Type <span class="text-danger">*</span></label>
                                    <input type="text" id="lab_test_name" name="test_name" class="form-control" placeholder="e.g., Complete Blood Count, Urinalysis, etc." required>
                                </div>
                                <div class="col-md-6">
                                    <label>Test Date <span class="text-danger">*</span></label>
                                    <input type="date" id="lab_test_date" name="test_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-12">
                                    <label>Laboratory/Provider</label>
                                    <input type="text" id="lab_provider" name="lab_provider" class="form-control" placeholder="Laboratory name or provider">
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-12">
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

                <!-- Lab Results List -->
                <div class="row">
                    <div class="col-md-12">
                        <h5><i class="fa fa-list"></i> Laboratory Results History</h5>
                        <div class="table-responsive">
                            <table id="tbl_lab_results" class="table table-sm table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="15%">Test Date</th>
                                        <th class="text-center" width="20%">Test Name</th>
                                        <th class="text-center" width="15%">Laboratory</th>
                                        <th class="text-center" width="20%">Files</th>
                                        <th class="text-center" width="15%">Uploaded Date</th>
                                        <th class="text-center" width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
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
