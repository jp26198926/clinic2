<!-- Modal Manage Reservation -->
<div class="modal fade" id="modal_manage_reservation" tabindex="-1" role="dialog" aria-labelledby="modal_manage_reservation_label">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal_manage_reservation_label">Manage Stock Reservation</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form_manage_reservation">
                    <input type="hidden" id="txt_reservation_stock_id" name="stock_id">
                    <input type="hidden" id="txt_reservation_product_id" name="product_id">
                    <input type="hidden" id="txt_reservation_location_id" name="location_id">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Product Code</label>
                                <div class="col-sm-8">
                                    <input type="text" id="txt_reservation_product_code" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Product Name</label>
                                <div class="col-sm-8">
                                    <input type="text" id="txt_reservation_product_name" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Location</label>
                                <div class="col-sm-8">
                                    <input type="text" id="txt_reservation_location_name" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Currently On Hand</label>
                                <div class="col-sm-7">
                                    <input type="text" id="txt_reservation_current_on_hand" class="form-control text-right" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Currently Reserved</label>
                                <div class="col-sm-7">
                                    <input type="text" id="txt_reservation_current_reserved" class="form-control text-right" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Currently Available</label>
                                <div class="col-sm-7">
                                    <input type="text" id="txt_reservation_current_available" class="form-control text-right" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                     <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="txt_reservation_quantity_change">Quantity to Change <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="number" id="txt_reservation_quantity_change" name="quantity_change" class="form-control numeric" placeholder="Positive to reserve, negative to release" required>
                                    <small class="help-block">Enter a positive value to reserve, or a negative value to release.</small>
                                </div>
                            </div>
                        </div>
                         <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="txt_reservation_notes">Notes</label>
                                <div class="col-sm-8">
                                    <textarea id="txt_reservation_notes" name="notes" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal_error alert alert-danger" style="display:none;">
                        <strong>Error!</strong> <span class="modal_error_msg"></span>
                    </div>
                    <div class="modal_waiting alert alert-info" style="display:none;">
                        <strong>Please wait...</strong> <span class="modal_waiting_msg"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="modal_button">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-warning" id="btn_release_stock"><i class="ace-icon fa fa-unlock"></i> Release Selected Qty</button>
                    <button type="button" class="btn btn-primary" id="btn_reserve_stock"><i class="ace-icon fa fa-lock"></i> Reserve Selected Qty</button>
                </div>
            </div>
        </div>
    </div>
</div>
