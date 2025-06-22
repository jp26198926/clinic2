<!-- Delete Reason Modal -->
<div id="modal_delete_reason" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-white">
                    <i class="fa fa-trash"></i>
                    Delete Item - Reason Required
                </h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fa fa-warning"></i>
                    <strong>Warning:</strong> This action cannot be undone. Please provide a reason for deleting this item.
                </div>
                
                <div class="form-group">
                    <label for="delete_reason">Reason for Deletion <span class="text-danger">*</span></label>
                    <textarea id="delete_reason" name="delete_reason" class="form-control" rows="3" 
                        placeholder="Please explain why this item is being deleted..." required></textarea>
                    <small class="text-muted">This reason will be logged for audit purposes.</small>
                </div>
                
                <div id="delete_item_info" class="well well-sm">
                    <!-- Item information will be populated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times"></i> Cancel
                </button>
                <button type="button" id="btn_confirm_delete" class="btn btn-danger">
                    <i class="fa fa-trash"></i> Delete Item
                </button>
            </div>
        </div>
    </div>
</div>
