<!-- bootstrap modal -->
<div id='modal_items' class="modal fade" tabindex="-1" users="dialog">
    <div class="modal-dialog" users="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger">
                    <i class="fa fa-check "> </i>
                    Products / Services
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

                <div class="row" style="margin-bottom: 0.5em;">
                    <div class="col-md-12" style="overflow-y: scroll;max-height:200px;">
                        <table id="tbl_items" class="table table-bordered table-stripe table-hover"
                            style="font-size:90%">
                            <thead class="header">
                                <tr>
                                    <th class="text-center">ACTION</th>
                                    <th class="text-center">PRODUCT / SERVICES</th>
                                    <th class="text-center">CATEGORY</th>
                                    <th class="text-center">UNIT PRICE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if (count($products) > 0){
                                        foreach($products as $product){
                                            echo "<tr>
                                                    <td align='center'>
                                                        <button id='{$product->id}' class='btn_add_item btn btn-xs btn-primary fa fa-download' title='Select' ></button>
                                                    </td>
                                                    <td>{$product->code} - {$product->name}</td>
                                                    <td align='center'>{$product->category}</td>
                                                    <td align='right'>{$product->amount}</td>
                                                  </tr>";
                                        }
                                    }else{
                                        echo "<tr>
                                                <td colspan='4'>No Record</td>
                                              </tr>";
                                    }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <div class="pull-right modal_button">
                    <input id="txt_search_item" type="search" class="input-sm" placeholder="Search" />
                    <button type="button" class="btn btn-xs btn-danger" data-dismiss="modal">
                        <span class='fa fa-times'></span>
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