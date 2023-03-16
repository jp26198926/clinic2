<!-- bootstrap modal -->
<div id='modal_modify' class="modal fade" tabindex="-1" users="dialog">
    <div class="modal-dialog" users="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-pencil fa-fw"></i>Modify</h4>
                <hidden class="hidden_id" value="0" />
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-12">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text" id="txt_name_update" class="txt_field_update form-control" />
                    </div>
                </div>
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-6">
                        <label>Value Type</label>
                        <select id="txt_value_type_id_update" class="txt_field_update form-control">
                            <option value="">-- ALL --</option>
                            <?php
                            foreach ($value_types as $key => $val) {
                                $id = $val->id;
                                $label = $val->value_type;

                                echo "<option value='{$id}'>{$label}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>Value</label>
                        <input type="text" id="txt_value_update" class="numeric txt_field_update form-control" />
                    </div>
                </div>
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-6">
                        <label>Commission Type</label>
                        <select id="txt_commission_type_id_update" class="txt_field_update form-control">
                            <option value="">-- ALL --</option>
                            <?php
                            foreach ($value_types as $key => $val) {
                                $id = $val->id;
                                $label = $val->value_type;

                                echo "<option value='{$id}'>{$label}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>Commission Value</label>
                        <input type="text" id="txt_commission_value_update" class="numeric txt_field_update form-control" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <div class="pull-right modal_button">
                    <button type="button" id="btn_update" class="btn btn-xs btn-info"><span class='fa fa-check'></span>
                        Update</button>
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
                        <div class="progress-bar progress-bar-info progress-bar-striped active" users="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                            Request is being processed... Please wait!
                        </div>
                    </div>
                </div>
            </div>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
