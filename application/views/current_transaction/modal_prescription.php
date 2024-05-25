<!-- bootstrap modal -->
<div id='modal_prescription' class="modal fade" tabindex="-1" users="dialog">
	<div class="modal-dialog modal-lg" users="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="blue bigger">
					<i class="fa fa-glass "> </i>
					Prescription
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
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
				</div>

				<div class="row" style="margin-bottom: 0.5em;">
					<div class="col-md-12">
						<form id="frm_prescription">
							<div class="row">
								<div class="col-md-4">
									<label for="txt_prescription_product_id">Product</label>
									<select id="txt_prescription_product_id" name="txt_prescription_product_id" class="txt_field_prescription chosen-select form-control" ">
										<option value=" 0">-- Select --</option>
										<?php
										foreach ($pharmacy_products as $product) {
											$id = $product->id;
											$label = $product->code . " - " . $product->name . " [{$product->uom_code}]";
											echo "<option value='{$id}'>{$label}</option>";
										}
										?>
									</select>
								</div>
								<div class="col-md-2">
									<label>QTY</label>
									<input type="number" name="txt_prescription_qty" id="txt_prescription_qty" value="1" class="txt_field_prescription text-center form-control" />
								</div>
								<div class="col-md-4">
									<label>Instruction</label>
									<textarea name="txt_prescription_instruction" id="txt_prescription_instruction" class="form-control"></textarea>
								</div>
								<div class="col-md-2">
									<label>&nbsp;</label>
									<button id="btn_prescription_add" class="btn btn-primary btn-lg btn-block">
										<i class="fa fa-plus fa-fw"></i>
										Add
									</button>
								</div>
							</div>
						</form>
						<hr />
						<table id="tbl_prescription" class="table table-sm table-striped table-bordered">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Product</th>
									<th class="text-center">Qty</th>
									<th class="text-center">Instruction</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="5" align="center">No Record</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

			</div>

			<div class="modal-footer">

				<div class="pull-right modal_button">
					<button type="button" id="btn_prescription_print" class="btn btn-xs btn-primary">
						<span class='fa fa-print'></span>
						Print
					</button>
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
						<div class="progress-bar progress-bar-info progress-bar-striped active" users="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
							Request is being processed... Please wait!
						</div>
					</div>
				</div>
			</div>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
