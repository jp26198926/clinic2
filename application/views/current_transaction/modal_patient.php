<!-- bootstrap modal -->
<div id='modal_patient' class="modal fade" tabindex="-1" users="dialog">
    <div class="modal-dialog" users="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-plus fa-fw"></i>New Patient</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-md-4">
                        <label>Lastname <span class="text-danger">*</span></label>
                        <input type="text" id="txt_patient_lastname" class="txt_patient_field form-control" />
                    </div>
                    <div class="col-md-4">
                        <label>Firstname <span class="text-danger">*</span></label>
                        <input type="text" id="txt_patient_firstname" class="txt_patient_field form-control" />
                    </div>
                    <div class="col-md-4">
                        <label>Middlename</label>
                        <input type="text" id="txt_patient_middlename" class="txt_patient_field form-control" />
                    </div>
                </div>
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-md-4">
                        <label>Gender</label>
                        <select id="txt_patient_gender_id" class="txt_patient_field form-control">
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
                        <select id="txt_patient_civil_id" class="txt_patient_field form-control">
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
                        <input type="text" id="txt_patient_contact_no" class="txt_patient_field form-control" />
                    </div>
                </div>
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-md-12">
                        <label>Address</label>
                        <textarea id="txt_patient_address" class="txt_patient_field form-control"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="pull-right modal_button">
                    <button type="button" id="btn_patient_save" class="btn btn-xs btn-info"><span class='fa fa-check'></span>
                        Save </button>
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal"><span class='fa fa-times'>
                            Cancel</button>
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
                        <div class="progress-bar progress-bar-info progress-bar-striped active" users="progressbar"
                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                            Request is being processed... Please wait!
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
