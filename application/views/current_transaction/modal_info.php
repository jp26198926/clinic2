<!-- bootstrap modal -->
<div id='modal_info' class="modal fade" tabindex="-1" users="dialog">
    <div class="modal-dialog" users="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger">
                    <i class="fa fa-info fa-fw"> </i>
                    Information
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

                <form id="frm_item_info" name="frm_item_info">
                    <input type="hidden" id="transaction_id_item_info" name="transaction_id_item_info" value="0" />
                    <input type="hidden" id="id_item_info" name="id_item_info" value="0" />

                    <div class="row" style="margin-bottom: 0.5em;">
                        <div class="col-md-4">
                            <label for="series_no_info">Series No.</label>
                            <input type="text" id="series_no_item_info" name="series_no_item_info"
                                class="field_item_info form-control" readonly />
                        </div>
                        <div class="col-md-4">
                            <label for="status_info">Status</label>
                            <input type="text" id="status_item_info" name="status_item_info"
                                class="field_item_info form-control" readonly />
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 0.5em;">
                        <div class="col-md-4">
                            <label for="qty_item_info">QTY</label>
                            <input type="number" id="qty_item_info" name="qty_item_info"
                                class="field_item_info form-control" readonly />
                        </div>
                        <div class="col-md-4">
                            <label for="uom_id_item_info">UOM</label>
                            <select id="uom_id_item_info" name="uom_id_item_info"
                                class="field_item_info form-control text-center" readonly>
                                <?php
                                    foreach($uoms as $key => $value){
                                        echo "<option value='{$value->id}'>{$value->name}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="type_id_item_info">TYPE</label>
                            <select id="type_id_item_info" name="type_id_item_info"
                                class="field_item_info form-control text-center" readonly>
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
                            <label for="description_item_info">DESCRIPTION</label>
                            <textarea id="description_item_info" name="description_item_info"
                                class="field_item_info form-control" readonly></textarea>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 0.5em;">
                        <div class="col-md-12">
                            <label for="delete_reason_item_info">
                                DELETE REASON
                                <span class="text-danger">*</span>
                            </label>
                            <textarea id="deleted_reason_item_info" name="deleted_reason_item_info"
                                class="field_item_info form-control" required></textarea>
                        </div>
                    </div>
                </form>

            </div>

            <div class="modal-footer">

                <div class="pull-right modal_button">
                    <button type="button" id="btn_item_delete" class="btn btn-xs btn-primary"><span
                            class='fa fa-trash'></span>
                        Delete this Item</button>
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