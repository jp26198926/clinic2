<!-- bootstrap modal -->
<div id='modal_modify' class="modal fade" tabindex="-1" users="dialog">
    <div class="modal-dialog " users="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-pencil fa-fw"></i>Modify</h4>
                <hidden class="hidden_id" value="0" />
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-4">
                        <label>Code <span class="text-danger">*</span></label>
                        <input type="text" id="txt_code_update" class="txt_field_update form-control" />
                    </div>
                    <div class="col-sm-8">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text" id="txt_name_update" class="txt_field_update form-control" />
                    </div>
                </div>
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-4">
                        <label>UOM</label>
                        <select id="txt_uom_id_update" class="txt_field_update form-control">
                            <option value="">-- SELECT --</option>
                            <?php
                            foreach ($uoms as $key => $val) {
                                $id = $val->id;
                                $label = $val->code . " - " . $val->name;

                                echo "<option value='{$id}'>{$label}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-8">
                        <label>Category</label>
                        <select id="txt_category_id_update" class="txt_field_update form-control">
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
                </div>
                <hr />
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-md-3">
                        <label>Amount</label>
                        <input type="text" id="txt_amount_update" placeholder="0.00" class="numeric txt_field_update form-control text-center" />
                    </div>
                    <div class="col-md-3">
                        <label>Amount PO</label>
                        <input type="text" id="txt_amount_po_update" placeholder="0.00" class="numeric txt_field_update form-control text-center" />
                    </div>
                    <div class="col-md-3">
                        <label>After Office AMT</label>
                        <input type="text" id="txt_after_amount_update" placeholder="0.00" class="numeric txt_field_update form-control text-center" />
                    </div>                    <div class="col-md-3">
                        <label>After Office AMT PO</label>
                        <input type="text" id="txt_after_amount_po_update" placeholder="0.00" class="numeric txt_field_update form-control text-center" />
                    </div>
                </div>
                <hr />
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-md-12">
                        <label>
                            <input type="checkbox" id="txt_is_allow_upload_update" class="txt_field_update ace" value="1" />
                            <span class="lbl"> Allow File Upload (for lab results, images, etc.)</span>
                        </label>
                        <small class="text-muted">Check this box if this product/service requires file uploads (e.g., lab results, X-ray images, diagnostic reports)</small>
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
