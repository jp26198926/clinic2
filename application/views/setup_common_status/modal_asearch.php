<!-- bootstrap modal -->
<div id="modal_asearch" class="modal fade" tabindex="-1" users="dialog">
	<div class="modal-dialog modal-sm" users="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="blue bigger"><i class="fa fa-search fa-fw"></i>Advance Search</h4>
			</div>
			<div class="modal-body">
				<div class="row" style="margin-bottom: 0.5em;">
					<div class="col-sm-12">
						<label>Status</label>
						<input type="text" id="txt_status_asearch" class="txt_field form-control" />
					</div>
				</div>
			</div>
			<div class="modal-footer">

				<div class="pull-right modal_button">
					<button type="button" id="btn_asearch_start" class="btn btn-sm btn-info"><span class="fa fa-check"></span> Search</button>
					<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><span class="fa fa-times"> Cancel</button>
				</div>

				<div class="pull-left modal_error" style="display:none">
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

				<div class="modal_waiting" style="display:none">
					<div class="progress">
						<div class="progress-bar progress-bar-info progress-bar-striped active" users="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
							Request is being processed... Please wait!
						</div>
					</div>
				</div>
			</div>


		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->