<!-- Stock Adjustment Modal -->
<div id="modal_stock_adjustment" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title">Stock Adjustment</h3>
            </div>

            <div class="modal-body">
                <div class="modal_error alert alert-block alert-danger" style="display: none;">
                    <i class="ace-icon fa fa-times red"></i>
                    <strong class="red modal_error_msg">
                        Error!
                    </strong>
                </div>

                <div class="modal_waiting alert alert-block alert-info" style="display: none;">
                    <i class="ace-icon fa fa-spinner fa-spin blue"></i>
                    <strong class="blue">
                        Please wait...
                    </strong>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="cmb_product_adj">Product: <span class="text-danger">*</span></label>
                            <select id="cmb_product_adj" class="chosen-select txt_field" data-placeholder="Choose Product...">
                                <option value=""></option>
                                <?php
                                if (isset($products) && $products) {
                                    foreach ($products as $product) {
                                        echo "<option value='{$product->id}'>{$product->code} - {$product->name}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="cmb_location_adj">Location: <span class="text-danger">*</span></label>
                            <select id="cmb_location_adj" class="chosen-select txt_field" data-placeholder="Choose Location...">
                                <option value=""></option>
                                <?php
                                if (isset($locations) && $locations) {
                                    foreach ($locations as $location) {
                                        echo "<option value='{$location->id}'>{$location->location}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="txt_adjustment_qty">Adjustment Quantity: <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="txt_adjustment_qty" class="form-control txt_field" 
                                   placeholder="Enter adjustment quantity (positive for increase, negative for decrease)" />
                            <span class="help-block">
                                <small class="text-muted">Use positive numbers to add stock, negative numbers to reduce stock</small>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="txt_adjustment_notes">Notes:</label>
                            <textarea id="txt_adjustment_notes" class="form-control txt_field" rows="3" 
                                      placeholder="Enter adjustment reason/notes..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="adj_movement_date">Movement Date: <span class="text-danger">*</span></label>
                            <input type="date" id="adj_movement_date" class="form-control txt_field" required />
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="modal_button">
                    <button id="btn_save_adjustment" class="btn btn-sm btn-primary">
                        <i class="ace-icon fa fa-check"></i>
                        Save Adjustment
                    </button>

                    <button class="btn btn-sm" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i>
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
