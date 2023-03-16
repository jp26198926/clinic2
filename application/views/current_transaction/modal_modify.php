<!-- bootstrap modal -->
<div id='modal_modify' class="modal fade" tabindex="-1" users="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" users="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-pencil fa-fw"></i>Modify</h4>
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

                <form id="frm_item_update" name="frm_item_update">
                    <input type="hidden" id="transaction_id_item_update" name="transaction_id_item_update" value="0" />
                    <input type="hidden" id="id_item_update" name="id_item_update" value="0" />

                    <div class="row" style="margin-bottom: 0.5em;">
                        <div class="col-md-4">
                            <label for="series_no_update">Series No.</label>
                            <input type="text" id="series_no_item_update" name="series_no_item_update"
                                class="field_item_update form-control" disabled />
                        </div>
                        <div class="col-md-4">
                            <label for="status_update">Status</label>
                            <input type="text" id="status_item_update" name="status_item_update"
                                class="field_item_update form-control" disabled />
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 0.5em;">
                        <div class="col-md-4">
                            <label for="qty_item_update">QTY <span class="text-danger">*</span></label>
                            <input type="number" id="qty_item_update" name="qty_item_update"
                                class="field_item_update form-control" required />
                        </div>
                        <div class="col-md-4">
                            <label for="uom_id_item_update">UOM <span class="text-danger">*</span></label>
                            <select id="uom_id_item_update" name="uom_id_item_update"
                                class="field_item_update form-control text-center">
                                <?php
                                    foreach($uoms as $key => $value){
                                        echo "<option value='{$value->id}'>{$value->name}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="type_id_item_update">TYPE <span class="text-danger">*</span></label>
                            <select id="type_id_item_update" name="type_id_item_update"
                                class="field_item_update form-control text-center">
                                <?php
                                    foreach($types as $key => $value){
                                        echo "<option value='{$value->id}'>{$value->name}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 0.5em;">
                        <div class="col-md-12">
                            <label for="description_item_update">DESCRIPTION <span class="text-danger">*</span></label>
                            <textarea id="description_item_update" name="description_item_update"
                                class="field_item_update form-control" required></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">

                <div class="pull-right modal_button">
                    <button type="button" id="btn_item_update" class="btn btn-xs btn-info"><span
                            class='fa fa-check'></span>
                        Update</button>
                    <button id="btn_update_cancel" type="button" class="btn btn-xs btn-danger"
                        data-dismiss="modal"><span class='fa fa-times'> Cancel</button>
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