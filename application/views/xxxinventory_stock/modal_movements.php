<!-- Stock Movements Modal -->
<div id="modal_movements" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-list fa-fw"></i>Stock Movements History</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-bottom: 1em;">
                    <div class="col-sm-12">
                        <div id="movements_product_info" class="alert alert-info">
                            <strong>Product:</strong> <span id="movements_product_name"></span><br>
                            <strong>Location:</strong> <span id="movements_location_name"></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="tbl_movements" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Movement Date</th>
                                        <th>Log Date/Time</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Reference</th>
                                        <th>From Location</th>
                                        <th>To Location</th>
                                        <th>Notes</th>
                                        <th>User</th> 
                                    </tr>
                                </thead>                                <tbody>
                                    <!-- DataTable will populate this -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <div class="pull-right modal_button">
                    <button id="btn_refresh_movements" class="btn btn-sm btn-info">
                        <i class="ace-icon fa fa-refresh"></i>
                        Refresh
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
                        <div class="progress-bar progress-bar-info progress-bar-striped active" 
                             role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" 
                             style="width:100%">
                            Loading movements... Please wait!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
