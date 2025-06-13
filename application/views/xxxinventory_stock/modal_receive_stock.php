<!-- Modal Receive Stock -->
<div class="modal fade" id="modal_receive_stock" tabindex="-1" role="dialog" aria-labelledby="modal_receive_stock_label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_receive_stock_label">Receive Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_receive_stock">
                    <input type="hidden" id="rs_stock_id" name="rs_stock_id">
                    <input type="hidden" id="rs_product_id" name="rs_product_id">
                    <input type="hidden" id="rs_location_id" name="rs_location_id">

                    <div class="form-group row">
                        <label for="rs_product_name" class="col-sm-4 col-form-label">Product</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="rs_product_name" name="rs_product_name" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="rs_location_name" class="col-sm-4 col-form-label">Location</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="rs_location_name" name="rs_location_name" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="rs_current_on_hand" class="col-sm-4 col-form-label">Current On Hand</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="rs_current_on_hand" name="rs_current_on_hand" readonly>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label for="rs_movement_date" class="col-sm-4 col-form-label">Movement Date <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="rs_movement_date" name="rs_movement_date" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="rs_quantity" class="col-sm-4 col-form-label">Quantity to Receive <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="rs_quantity" name="rs_quantity" placeholder="Enter quantity" required min="0.01" step="any">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="rs_notes" class="col-sm-4 col-form-label">Notes</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="rs_notes" name="rs_notes" rows="3" placeholder="Optional notes for receiving"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_process_receive">Receive Stock</button>
            </div>
        </div>
    </div>
</div>
