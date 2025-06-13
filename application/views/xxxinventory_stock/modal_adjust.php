<div class="modal fade" id="modal-adjust" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    <i class="ace-icon fa fa-plus-minus"></i>
                    Stock Adjustment
                </h4>
            </div>
            <form id="form-adjust" class="form-horizontal" role="form">
                <input type="hidden" id="adjust_stock_id" name="stock_id">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ace-icon fa fa-info-circle"></i>
                        Use this form to add or subtract stock quantities. All adjustments will be logged in stock movements.
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">Product</label>
                        <div class="col-sm-9">
                            <p class="form-control-static" id="adjust_product_info"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">Location</label>
                        <div class="col-sm-9">
                            <p class="form-control-static" id="adjust_location_info"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">Current Stock</label>
                        <div class="col-sm-9">
                            <p class="form-control-static" id="adjust_current_stock"></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="adjust_type">Adjustment Type <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-control" id="adjust_type" name="adjustment_type" required>
                                <option value="">Select Adjustment Type</option>
                                <option value="add">Add Stock (+)</option>
                                <option value="subtract">Subtract Stock (-)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="adjust_quantity">Quantity <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="adjust_quantity" name="quantity" min="0.01" step="0.01" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="adjust_reason">Reason</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="adjust_reason" name="reason" rows="3" placeholder="Enter reason for adjustment (optional)"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ace-icon fa fa-check"></i>
                        Apply Adjustment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#form-adjust').on('submit', function(e) {
        e.preventDefault();
        
        var adjustmentType = $('#adjust_type').val();
        var quantity = $('#adjust_quantity').val();
        
        if (!adjustmentType || !quantity) {
            alert('Please fill in all required fields');
            return;
        }
        
        var confirmMessage = 'Are you sure you want to ' + 
                           (adjustmentType == 'add' ? 'add ' : 'subtract ') + 
                           quantity + ' from stock?';
        
        if (confirm(confirmMessage)) {
            $.ajax({
                url: '<?php echo base_url("inventory_stock/adjust_stock"); ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        $('#modal-adjust').modal('hide');
                        $('#form-adjust')[0].reset();
                        table.ajax.reload();
                        alert('Stock adjustment completed successfully');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error processing stock adjustment');
                }
            });
        }
    });

    $('#modal-adjust').on('hidden.bs.modal', function() {
        $('#form-adjust')[0].reset();
    });
});
</script>
