<!-- bootstrap modal -->
<div id="modal_asearch" class="modal fade" tabindex="-1" users="dialog">
    <div class="modal-dialog" users="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-search-plus fa-fw"></i>Advance Search</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-12">
                        <label>Package</label>
                        <input type="text" id="txt_package_asearch" class="txt_field_asearch form-control" />
                    </div>
                    <div class="col-sm-6">
                        <label>Status</label>
                        <select id="txt_status_asearch" class="txt_field_asearch form-control">
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
                </div>

            </div>
            <div class="modal-footer">

                <div class="pull-right modal_button">
                    <button type="button" id="btn_asearch_start" class="btn btn-sm btn-asearch"><span
                            class="fa fa-check"></span> Search</button>
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><span class="fa fa-times">
                            Cancel</button>
                </div>

                <div class="pull-left modal_error" style="display:none">
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

                <div class="modal_waiting" style="display:none">
                    <div class="progress">
                        <div class="progress-bar progress-bar-asearch progress-bar-striped active" users="progressbar"
                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                            Request is being processed... Please wait!
                        </div>
                    </div>
                </div>
            </div>


        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->