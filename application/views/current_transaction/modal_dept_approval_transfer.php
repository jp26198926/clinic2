<!-- bootstrap modal -->
<div id='modal_dept_approval_transfer' class="modal fade" tabindex="-1" users="dialog">
    <div class="modal-dialog modal-sm" users="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger">
                    <i class="fa fa-check "> </i>
                    Transfer Dept Approver
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

                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-md-12">
                        <label for="txt_dept_approver_transfer">Approver</label>
                        <select id="txt_dept_approver_transfer" name="txt_dept_approver_transfer"
                            class="form-control select2">
                            <option value="">-- SELECT --</option>
                            <?php
                                foreach($dept_approvers as $key => $value){
                                    $label = strtoupper($value->name);
                                    echo "<option value='{$value->id}'>{$label}</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <div class="pull-right modal_button">
                    <button type="button" id="btn_dept_approver_transfer_send" class="btn btn-xs btn-primary">
                        <span class='fa fa-check'></span>
                        Transfer
                    </button>
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">
                        <span class='fa fa-times'></span>
                        Close
                    </button>
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