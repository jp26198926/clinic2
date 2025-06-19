<!-- bootstrap modal -->
<div id='modal_info' class="modal fade" tabindex="-1" users="dialog">
    <div class="modal-dialog modal-lg" users="document">
        <div class="modal-content">            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-info fa-fw"></i>Patient Information & History</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-md-4">
                        <label>Lastname <span class="text-danger">*</span></label>
                        <input type="text" id="txt_lastname_info" class="txt_field_info form-control" disabled />
                    </div>
                    <div class="col-md-4">
                        <label>Firstname <span class="text-danger">*</span></label>
                        <input type="text" id="txt_firstname_info" class="txt_field_info form-control" disabled />
                    </div>
                    <div class="col-md-4">
                        <label>Middlename</label>
                        <input type="text" id="txt_middlename_info" class="txt_field_info form-control" disabled />
                    </div>
                </div>
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-md-4">
                        <label>Gender</label>
                        <select id="txt_gender_id_info" class="txt_field_info form-control" disabled>
                            <option value="">-- ALL --</option>
                            <?php
                            foreach ($genders as $key => $val) {
                                $id = $val->id;
                                $label = $val->gender;

                                echo "<option value='{$id}'>{$label}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Civil Status</label>
                        <select id="txt_civil_id_info" class="txt_field_info form-control" disabled>
                            <option value="">-- ALL --</option>
                            <?php
                            foreach ($civils as $key => $val) {
                                $id = $val->id;
                                $label = $val->civil;

                                echo "<option value='{$id}'>{$label}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Contact No.</label>
                        <input type="text" id="txt_contact_no_info" class="txt_field_info form-control" disabled />
                    </div>
                </div>                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-md-4">
                        <label>Status</label>
                        <select id="txt_status_info" class="txt_field_info form-control" disabled>
                            <option value="">-- ALL --</option>
                            <?php
                            foreach ($status as $key => $val) {
                                $id = $val->id;
                                $label = $val->status;

                                echo "<option value='{$id}'>{$label}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label>Address</label>
                        <textarea id="txt_address_info" class="txt_field_info form-control" disabled></textarea>
                    </div>
                </div>                <!-- Patient Transaction History Section -->
                <div class="row" style="margin-top: 1.5em; border-top: 2px solid #e0e0e0; padding-top: 1em;">
                    <div class="col-md-12">
                        <h5 class="blue">
                            <i class="fa fa-history"></i> Transaction History
                            <small class="text-muted">(Recent transactions)</small>
                        </h5>
                        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px;">
                            <table id="tbl_patient_history" class="table table-sm table-striped table-bordered" style="margin-bottom: 0;">
                                <thead style="background-color: #f5f5f5;">
                                    <tr>
                                        <th class="text-center" style="width: 12%;">TRANS NO.</th>
                                        <th class="text-center" style="width: 12%;">DATE</th>
                                        <th class="text-center" style="width: 15%;">TYPE</th>
                                        <th class="text-center" style="width: 15%;">DOCTOR</th>
                                        <th class="text-center" style="width: 25%;">DIAGNOSIS</th>
                                        <th class="text-center" style="width: 11%;">STATUS</th>
                                        <th class="text-center" style="width: 10%;">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" align="center">Loading transaction history...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Patient Documents Section -->
                <div class="row" style="margin-top: 1.5em; border-top: 2px solid #e0e0e0; padding-top: 1em;">
                    <div class="col-md-12">
                        <h5 class="blue">
                            <i class="fa fa-file-o"></i> Patient Documents
                            <small class="text-muted">(Scanned files and documents)</small>
                        </h5>
                        
                        <!-- File Upload Section -->
                        <div class="row" style="margin-bottom: 1em;">
                            <div class="col-md-8">
                                <input type="file" id="patient_files" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx,.txt,.bmp,.tiff,.tif" style="height: auto; padding: 6px;">
                                <small class="text-muted">Supported formats: PDF, JPG, JPEG, PNG, GIF, DOC, DOCX, TXT, BMP, TIFF (Max: 10MB per file)</small>
                            </div>
                            <div class="col-md-4">
                                <button type="button" id="btn_upload_files" class="btn btn-sm btn-success">
                                    <i class="fa fa-upload"></i> Upload Files
                                </button>
                                <button type="button" id="btn_refresh_files" class="btn btn-sm btn-info">
                                    <i class="fa fa-refresh"></i> Refresh
                                </button>
                            </div>
                        </div>

                        <!-- Uploaded Files List -->
                        <div style="max-height: 250px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px;">
                            <table id="tbl_patient_files" class="table table-sm table-striped table-bordered" style="margin-bottom: 0;">
                                <thead style="background-color: #f5f5f5;">
                                    <tr>
                                        <th class="text-center" style="width: 40%;">FILE NAME</th>
                                        <th class="text-center" style="width: 15%;">SIZE</th>
                                        <th class="text-center" style="width: 20%;">UPLOADED DATE</th>
                                        <th class="text-center" style="width: 25%;">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" align="center">Loading patient documents...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">

                <div class="pull-right modal_button">
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal"><span class='fa fa-times'>
                            Close</button>
                </div>

                <div class="pull-left modal_error" style='display:none'>
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

                <div class="modal_waiting" style='display:none'>
                    <div class="progress">
                        <div class="progress-bar progress-bar-info progress-bar-striped active" users="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                            Request is being processed... Please wait!
                        </div>
                    </div>
                </div>
            </div>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
