<!-- Stock Transfer Modal -->
<div id="modal_stock_transfer" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg"> <!-- Changed to modal-lg -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title">Stock Transfer</h3>
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

                <div class="row" style="margin-bottom: 0.5em;"> <!-- Added style for consistent spacing -->
                    <div class="col-sm-12"> <!-- Changed to col-sm-12 for full width -->
                        <div class="form-group">
                            <label for="cmb_product_transfer">Product: <span class="text-danger">*</span></label>
                            <select id="cmb_product_transfer" class="chosen-select txt_field form-control" data-placeholder="Choose Product..."> <!-- Added form-control class -->
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

                <div class="row" style="margin-bottom: 0.5em;"> <!-- Added style for consistent spacing -->
                    <div class="col-sm-6"> <!-- Changed to col-sm-6 -->
                        <div class="form-group">
                            <label for="cmb_from_location">From Location: <span class="text-danger">*</span></label>
                            <select id="cmb_from_location" class="chosen-select txt_field form-control" data-placeholder="Choose Source Location..."> <!-- Added form-control class -->
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
                    <div class="col-sm-6"> <!-- Changed to col-sm-6 -->
                        <div class="form-group">
                            <label for="cmb_to_location">To Location: <span class="text-danger">*</span></label>
                            <select id="cmb_to_location" class="chosen-select txt_field form-control" data-placeholder="Choose Destination Location..."> <!-- Added form-control class -->
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

                <div class="row" style="margin-bottom: 0.5em;"> <!-- Added style for consistent spacing -->
                    <div class="col-sm-12"> <!-- Changed to col-sm-12 -->
                        <div class="form-group">
                            <label for="txt_transfer_qty">Transfer Quantity: <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0.01" id="txt_transfer_qty" class="form-control txt_field" 
                                   placeholder="Enter quantity to transfer" />
                            <span class="help-block">
                                <small class="text-muted">Quantity must be positive and available at source location</small>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 0.5em;"> <!-- Added style for consistent spacing -->
                    <div class="col-sm-12"> <!-- Changed to col-sm-12 -->
                        <div class="form-group">
                            <label for="txt_transfer_notes">Notes:</label>
                            <textarea id="txt_transfer_notes" class="form-control txt_field" rows="3" 
                                      placeholder="Enter transfer reason/notes..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="transfer_movement_date">Movement Date: <span class="text-danger">*</span></label>
                            <input type="date" id="transfer_movement_date" class="form-control txt_field" required />
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="modal_button">
                    <button id="btn_save_transfer" class="btn btn-sm btn-primary">
                        <i class="ace-icon fa fa-exchange"></i>
                        Transfer Stock
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
