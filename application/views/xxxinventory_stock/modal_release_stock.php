<!-- Modal Release Stock -->
<div class="modal fade" id="modal_release_stock" tabindex="-1" role="dialog" aria-labelledby="modal_release_stock_label" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_release_stock_label">Release Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_release_stock">
                    <input type="hidden" id="rls_stock_id" name="rls_stock_id">
                    <input type="hidden" id="rls_product_id" name="rls_product_id">
                    <input type="hidden" id="rls_location_id" name="rls_location_id">

                    <div class="form-group row">
                        <label for="rls_product_name" class="col-sm-4 col-form-label">Product</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="rls_product_name" name="rls_product_name" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="rls_location_name" class="col-sm-4 col-form-label">Location</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="rls_location_name" name="rls_location_name" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="rls_current_on_hand" class="col-sm-4 col-form-label">Current On Hand</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="rls_current_on_hand" name="rls_current_on_hand" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="rls_current_reserved" class="col-sm-4 col-form-label">Currently Reserved</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="rls_current_reserved" name="rls_current_reserved" readonly>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="rls_qty_available_for_release" class="col-sm-4 col-form-label">Available for Release</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="rls_qty_available_for_release" name="rls_qty_available_for_release" readonly style="font-weight: bold;">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label for="rls_movement_date" class="col-sm-4 col-form-label">Movement Date <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="rls_movement_date" name="rls_movement_date" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="rls_quantity" class="col-sm-4 col-form-label">Quantity to Release <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="rls_quantity" name="rls_quantity" placeholder="Enter quantity" required min="0.01" step="any">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="rls_notes" class="col-sm-4 col-form-label">Notes</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="rls_notes" name="rls_notes" rows="3" placeholder="Optional notes for releasing (e.g., SO number, patient name)"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" id="btn_process_release">Release Stock</button>
            </div>
        </div>
    </div>
</div>
