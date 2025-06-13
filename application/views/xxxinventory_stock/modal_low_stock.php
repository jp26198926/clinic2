<!-- Low Stock Report Modal -->
<div id="modal_low_stock" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="red bigger"><i class="fa fa-warning fa-fw"></i>Low Stock Report</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 1em;">
                    <div class="col-sm-6">
                        <label>Minimum Quantity Threshold</label>
                        <input type="number" step="0.01" min="0" id="txt_threshold_qty" 
                               class="form-control" value="10" 
                               placeholder="Enter minimum quantity threshold" />
                    </div>
                    <div class="col-sm-6">
                        <div style="margin-top: 25px;">
                            <button id="btn_generate_low_stock" class="btn btn-sm btn-warning">
                                <i class="ace-icon fa fa-search"></i>
                                Generate Report
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-header">
                            Low Stock Items <span id="lbl_low_stock_count" class="badge badge-warning">0</span>
                        </div>
                        <div class="table-responsive">
                            <table id="tbl_low_stock" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Location</th>
                                        <th>Available Qty</th>
                                        <th>UOM</th>
                                        <th>Category</th>
                                        <th>Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <em>Click "Generate Report" to view low stock items</em>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="row" style="margin-top: 1em;">
                    <div class="col-sm-12">
                        <div class="alert alert-warning">
                            <i class="ace-icon fa fa-lightbulb-o"></i>
                            <strong>Note:</strong> This report shows items with available quantity at or below the specified threshold.
                            Items with zero quantity are highlighted in red.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <div class="pull-right modal_button">
                    <button id="btn_export_low_stock" class="btn btn-sm btn-success" style="display:none;">
                        <i class="ace-icon fa fa-download"></i>
                        Export to Excel
                    </button>
                    <button type="button" class="btn btn-sm" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i>
                        Close
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
                        <div class="progress-bar progress-bar-warning progress-bar-striped active" 
                             role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" 
                             style="width:100%">
                            Generating report... Please wait!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
