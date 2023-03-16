<!-- bootstrap modal -->
<div id='modal_new' class="modal fade" tabindex="-1" users="dialog">
    <div class="modal-dialog" users="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-plus fa-fw"></i>New Entry</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-12">
                        <label>Product / Services <span class="text-danger">*</span></label>
                        <select id="txt_product_id" class="txt_field form-control chosen-select">
                            <option value="">-- SELECT --</option>
                            <?php
							foreach ($products as $key => $val) {
								$id = $val->id;
								$label = $val->code . " - " . $val->name;

								echo "<option value='{$id}'>{$label}</option>";
							}
							?>
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-6">
                        <label>Category</label>
                        <select id="txt_category_id" class="txt_field form-control" disabled>
                            <option value="">-- SELECT --</option>
                            <?php
							foreach ($categories as $key => $val) {
								$id = $val->id;
								$label = $val->category;

								echo "<option value='{$id}'>{$label}</option>";
							}
							?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>Result Label <span class="text-danger">*</span></label>
                        <input type="text" id="txt_result_label" class="txt_field form-control" />
                    </div>
                </div>
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-6">
                        <label>Reference Value</label>
                        <input type="text" id="txt_reference" class="txt_field form-control" />
                    </div>
                    <div class="col-sm-6">
                        <label>Unit</label>
                        <input type="text" id="txt_unit" class="txt_field form-control" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <div class="pull-right modal_button">
                    <button type="button" id="btn_save" class="btn btn-xs btn-info"><span class='fa fa-check'></span>
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