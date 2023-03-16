<!-- bootstrap modal -->
<div id='modal_send_email' class="modal fade" tabindex="-1" users="dialog">
	<div class="modal-dialog modal-lg" users="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="blue bigger"><i class="fa fa-plus fa-fw"></i>Send Email</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-4">
						<div class="row" style="margin-bottom: 0.5em;">
							<div class="col-sm-2">
								<label> To <span class="text-danger">*</span></label>
							</div>
							<div class="col-sm-10">
								<input type="text" id="txt_email_to" class="txt_field_email form-control" />
								<small class="help-block text-danger">Use comma (,) separator for multiple recepient</small>
							</div>
						</div>
						<div class="row" style="margin-bottom: 0.5em;">
							<div class="col-sm-2">
								<label> CC </label>
							</div>
							<div class="col-sm-10">
								<input type="text" id="txt_email_cc" class="txt_field_email form-control" />
								<small class="help-block text-danger">Use comma (,) separator for multiple recepient</small>
							</div>
						</div>
						<div class="row" style="margin-bottom: 0.5em;">
							<div class="col-sm-2">
								<label> BCC </label>
							</div>
							<div class="col-sm-10">
								<input type="text" id="txt_email_bcc" class="txt_field_email form-control" />
								<small class="help-block text-danger">Use comma (,) separator for multiple recepient</small>
							</div>
						</div>
						<div class="row" style="margin-bottom: 0.5em;">
							<div class="col-sm-2">
								<label> Subject <span class="text-danger">*</span></label>
							</div>
							<div class="col-sm-10">
								<input type="text" id="txt_email_subject" class="txt_field_email form-control" />
							</div>
						</div>
					</div>

					<div class="col-md-8" style="border-left-style: dotted">

						<div class="row" style="margin-bottom: 0.5em;">
							<div class="col-sm-2">
								<label> Message <span class="text-danger">*</span></label>
							</div>
							<div class="col-sm-10">
								<div class="wysiwyg-editor txt_field_email" id="txt_email_message"></div>
							</div>
						</div>
					</div>
				</div>


			</div>

			<div class="modal-footer">
				<div class="pull-right modal_button">
					<button type="button" id="btn_send_email" class="btn btn-xs btn-info"><span class='fa fa-send'></span> Send </button>
					<button type="button" class="btn btn-xs btn-danger" data-dismiss="modal"><span class='fa fa-times'> Cancel</button>
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
						<div class="progress-bar progress-bar-info progress-bar-striped active" users="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
							Request is being processed... Please wait!
						</div>
					</div>
				</div>
			</div>


		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->