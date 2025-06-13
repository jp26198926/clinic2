<!-- Advanced Search Modal -->
<div id="modal_asearch" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-search-plus fa-fw"></i>Advanced Search</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-6">
                        <label>Product</label>
                        <select id="cmb_product_asearch" class="chosen-select txt_field_asearch form-control" data-placeholder="Choose Product...">
                            <option value="">-- ALL PRODUCTS --</option>
                            <?php
                            if (isset($products) && $products) {
                                foreach ($products as $product) {
                                    echo "<option value='{$product->id}'>{$product->code} - {$product->name}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>Location</label>
                        <select id="cmb_location_asearch" class="chosen-select txt_field_asearch form-control" data-placeholder="Choose Location...">
                            <option value="">-- ALL LOCATIONS --</option>
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
                
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-6">
                        <label>Minimum Available Quantity</label>
                        <input type="number" step="0.01" min="0" id="txt_min_qty_asearch" 
                               class="form-control txt_field_asearch" 
                               placeholder="Enter minimum quantity" />
                    </div>
                    <div class="col-sm-6">
                        <label>Maximum Available Quantity</label>
                        <input type="number" step="0.01" min="0" id="txt_max_qty_asearch" 
                               class="form-control txt_field_asearch" 
                               placeholder="Enter maximum quantity" />
                    </div>
                </div>
                
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-12">
                        <div class="checkbox">
                            <label style="padding-left: 20px;">
                                <input type="checkbox" id="chk_low_stock_only" class="ace" />
                                <span class="lbl"> Show only low stock items (≤ 10 units)</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-12">
                        <div class="checkbox">
                            <label style="padding-left: 20px;">
                                <input type="checkbox" id="chk_zero_stock_only" class="ace" />
                                <span class="lbl"> Show only out of stock items (0 available)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <div class="pull-right modal_button">
                    <button id="btn_search_asearch" class="btn btn-sm btn-primary">
                        <i class="ace-icon fa fa-search"></i>
                        Search
                    </button>
                    <button type="button" class="btn btn-sm" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i>
                        Cancel
                    </button>
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
                        <div class="progress-bar progress-bar-info progress-bar-striped active" 
                             role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" 
                             style="width:100%">
                            Searching... Please wait!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
