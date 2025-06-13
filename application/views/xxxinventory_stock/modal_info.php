<!-- Stock Information Modal -->
<div id='modal_info' class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-info fa-fw"></i>Stock Information</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-6">
                        <label>Product Code</label>
                        <input type="text" id="txt_product_code_info" class="txt_field_info form-control" disabled />
                    </div>
                    <div class="col-sm-6">
                        <label>Product Name</label>
                        <input type="text" id="txt_product_name_info" class="txt_field_info form-control" disabled />
                    </div>
                </div>
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-6">
                        <label>Location</label>
                        <input type="text" id="txt_location_info" class="txt_field_info form-control" disabled />
                    </div>
                    <div class="col-sm-6">
                        <label>Category</label>
                        <input type="text" id="txt_category_info" class="txt_field_info form-control" disabled />
                    </div>
                </div>
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-3">
                        <label>UOM Code</label>
                        <input type="text" id="txt_uom_code_info" class="txt_field_info form-control" disabled />
                    </div>
                    <div class="col-sm-3">
                        <label>UOM Name</label>
                        <input type="text" id="txt_uom_name_info" class="txt_field_info form-control" disabled />
                    </div>
                </div>
                
                <hr />
                <h5 class="blue"><i class="fa fa-cubes"></i> Stock Quantities</h5>
                
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-4">
                        <label>Quantity On Hand</label>
                        <input type="text" id="txt_qty_on_hand_info" class="numeric txt_field_info form-control text-center" disabled />
                    </div>
                    <div class="col-sm-4">
                        <label>Quantity Reserved</label>
                        <input type="text" id="txt_qty_reserved_info" class="numeric txt_field_info form-control text-center" disabled />
                    </div>
                    <div class="col-sm-4">
                        <label>Quantity Available</label>
                        <input type="text" id="txt_qty_available_info" class="numeric txt_field_info form-control text-center" disabled />
                    </div>
                </div>
                
                <hr />
                <h5 class="blue"><i class="fa fa-clock-o"></i> Timestamps</h5>
                
                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-sm-6">
                        <label>Created At</label>
                        <input type="text" id="txt_created_at_info" class="txt_field_info form-control" disabled />
                    </div>
                    <div class="col-sm-6">
                        <label>Last Updated</label>
                        <input type="text" id="txt_last_updated_info" class="txt_field_info form-control" disabled />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right modal_button">
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">
                        <span class='fa fa-times'></span> Close
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
                            Request is being processed... Please wait!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
