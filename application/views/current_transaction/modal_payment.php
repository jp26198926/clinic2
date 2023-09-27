<!-- bootstrap modal -->
<div id='modal_payment' class="modal fade" tabindex="-1" users="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog" users="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="blue bigger"><i class="fa fa-money fa-fw"></i>Payment</h4>
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

				<!-- <div class="col-md-12"> -->
				<div class="tabbable">
					<ul class="nav nav-tabs" id="myTab">
						<li class="active">
							<a data-toggle="tab" href="#tab_payment_add">
								<i class="green ace-icon fa fa-home bigger-120"></i>
								Add Payment
							</a>
						</li>
						<li>
							<a data-toggle="tab" href="#tab_payment_history">
								Payment History
								<span id="badge_payment_count" class="badge badge-danger">0</span>
							</a>
						</li>
					</ul>

					<div class="tab-content">
						<div id="tab_payment_add" class="tab-pane fade in active">
							<div class="row">
								<div class="col-md-8">
									<div class="row" style="margin-bottom: 1rem">
										<div class="col-md-4 text-right">Date <span class="danger">*</span></div>
										<div class="col-md-8">
											<input type="text" id="txt_payment_date" class="txt_payment_field form-control datepicker" readonly />
										</div>
									</div>
									<div class="row" style="margin-bottom: 1rem">
										<div class="col-md-4 text-right">PMT Type <span class="danger">*</span></div>
										<div class="col-md-8">
											<select id="txt_payment_type" class="form-control">
												<?php
												foreach ($payment_types as $payment_type) {
													echo "<option value='{$payment_type->id}'>{$payment_type->payment_type}</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="row" style="margin-bottom: 1rem; font-weight:bolder">
										<div class="col-md-4 text-right text-danger">Pay Amount <span class="danger">*</span>
										</div>
										<div class="col-md-8">
											<input id="txt_payment_pay" type="text" class="txt_payment_field form-control text-danger numeric" />
										</div>
									</div>
									<div class="row" style="margin-bottom: 1rem">
										<div class="col-md-4 text-right">Tender Amount <span class="danger">*</span>
										</div>
										<div class="col-md-8">
											<input id="txt_payment_tender" type="text" class="txt_payment_field form-control numeric" />
										</div>
									</div>
									<div class="row" style="margin-bottom: 1rem">
										<div class="col-md-4 text-right">Change</div>
										<div class="col-md-8">
											<input id="txt_payment_change" type="text" class="txt_payment_field form-control numeric" disabled />
										</div>
									</div>
									<div class="row" style="margin-bottom: 1rem">
										<div class="col-md-4 text-right">Reference</div>
										<div class="col-md-8">
											<textarea id="txt_payment_reference" class="txt_payment_field form-control"></textarea>
										</div>
									</div>
								</div>
								<div class="col-md-4 text-center">
									<h1><b>Amount Due</b></h1>
									<h3 class="text-danger">
										<b><span id="lbl_payment_amount_due">0.00</span></b>
									</h3>

									<div class="modal_button">
										<button type="button" id="btn_payment_save" class="btn btn-xs btn-info">
											<span class='fa fa-check'></span>
											Save
										</button>
										<button id="btn_payment_cancel" type="button" class="btn btn-xs btn-danger" data-dismiss="modal">
											<span class='fa fa-times'></span>
											Cancel
										</button>
									</div>
								</div>
							</div>
						</div>

						<div id="tab_payment_history" class="tab-pane fade">
							<table id="tbl_payment_list" class="table table-striped table-bordered table-hover no-margin-bottom">
								<thead>
									<tr>
										<th class="text-center">OPT</th>
										<th class="text-center">PMT #</th>
										<th class="text-center">DATE</th>
										<th class="text-center">TYPE</th>
										<th class="text-center">AMOUNT</th>
										<th class="text-center">REF</th>
										<th class="text-center">ENCODED</th>
										<th class="text-center">STATUS</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td align="center" colspan="8">No Record</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- </div> -->
			</div>

			<div class="modal-footer">

				<!-- <div class="pull-right modal_button">
					<button type="button" id="btn_item_update" class="btn btn-xs btn-info"><span class='fa fa-check'></span>
						Update</button>
					<button id="btn_update_cancel" type="button" class="btn btn-xs btn-danger" data-dismiss="modal"><span class='fa fa-times'> Cancel</button>
				</div>
				-->

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
