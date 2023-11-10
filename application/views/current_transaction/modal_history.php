<!-- bootstrap modal -->
<div id='modal_history' class="modal fade" tabindex="-1" users="dialog">
    <div class="modal-dialog modal-lg" users="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger">
                    <i class="fa fa-check "> </i>
                    Patient History Record
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

                <div class="row" style="margin-bottom:1em;">
					<div class="col-md-12">
						<table id="tbl_history" class="table table-sm table-striped table-bordered">
							<thead>
								<tr>
									<th class="text-center">TRANS NO.</th>
									<th class="text-center">DATE</th>
									<th class="text-center">TYPE</th>
									<th class="text-center">DOCTOR</th>
									<th class="text-center">DIAGNOSIS</th>
									<th class="text-center">STATUS</th>
									<th class="text-center">ACTION</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="7" align="center">No Record</td>
								</tr>
							</tbody>
						</table>
					</div>
                </div>

            </div>

            <div class="modal-footer">

                <div class="pull-right modal_button">
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
