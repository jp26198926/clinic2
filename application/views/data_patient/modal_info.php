<!-- bootstrap modal -->
<div id='modal_info' class="modal fade" tabindex="-1" users="dialog">
    <div class="modal-dialog modal-lg" users="document">
        <div class="modal-content">            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-info fa-fw"></i>Patient Information & History</h4>
            </div>
            <div class="modal-body">
                <style>
                    /* Custom styles for patient info modal tabs */
                    #modal_info .nav-tabs > li > a {
                        border-radius: 4px 4px 0 0;
                        padding: 8px 15px;
                    }
                    #modal_info .nav-tabs > li.active > a,
                    #modal_info .nav-tabs > li.active > a:hover,
                    #modal_info .nav-tabs > li.active > a:focus {
                        background-color: #f5f5f5;
                        border-bottom-color: transparent;
                    }
                    #modal_info .nav-tabs > li > a .badge {
                        margin-left: 5px;
                        font-size: 10px;
                        padding: 2px 6px;
                    }
                    #modal_info .tab-content {
                        background-color: #fafafa;
                    }
                    #modal_info .table thead th {
                        background-color: #337ab7 !important;
                        color: white;
                        font-size: 11px;
                        font-weight: bold;
                    }
                </style>
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
                </div>

                <!-- Tabs Section -->
                <div class="row" style="margin-top: 1.5em; border-top: 2px solid #e0e0e0; padding-top: 1em;">
                    <div class="col-md-12">
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tab_history" aria-controls="tab_history" role="tab" data-toggle="tab">
                                    <i class="fa fa-history"></i> Transaction History
                                    <span id="badge_history_count" class="badge badge-info">0</span>
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#tab_documents" aria-controls="tab_documents" role="tab" data-toggle="tab">
                                    <i class="fa fa-file-o"></i> Patient Documents
                                    <span id="badge_documents_count" class="badge badge-success">0</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" style="border: 1px solid #ddd; border-top: none; padding: 15px; min-height: 400px;">
                            
                            <!-- Transaction History Tab -->
                            <div role="tabpanel" class="tab-pane active" id="tab_history">
                                <div style="max-height: 350px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px;">
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

                            <!-- Patient Documents Tab -->
                            <div role="tabpanel" class="tab-pane" id="tab_documents">
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
                                <div style="max-height: 280px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px;">
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

                        </div> <!-- /.tab-content -->
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
