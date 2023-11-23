<!-- bootstrap modal -->
<div id='modal_xray' class="modal fade" tabindex="-1" users="dialog">
    <div class="modal-dialog modal-md" users="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger">
                    <i class="fa fa-check "> </i>
                    Xray Result
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
                    <div class="col-md-2 text-right">Attachment</div>
                    <div class="col-md-8">
						<?php
							if (intval($transaction_status_id) == 3) {
						?>

                        <form id="frm_xray_attachment_add" name="frm_xray_attachment_add"
                            enctype="multipart/form-data">
                            <input type="file"
								id="xray_attachment_add"
								name="xray_attachment_add[]"
								class="txt_field"
								multiple
							/>
						</form>

						<?php } ?>

                        <div id="xray_attachment_list" style="margin-top:0.5em;">
                            <?php
                                if (count($xray_attachments) > 0) {
                                    echo "<ul>";
                                    $base_url = base_url();
                                    foreach ($xray_attachments as $attachment) {
                                        $has_remove_button = "";
                                        if (intval($transaction_status_id) == 3) {
                                            $has_remove_button = "<a href='#' id='{$attachment}' class='xray_attachment_remove label label-danger arrowed-in arrowed-right'>Remove</a>";
                                        }

                                        echo "<li id='list_{$attachment}' style='padding:0.1em;'>
                                                {$has_remove_button}
                                                <a href='{$base_url}upload/xray/{$transaction_id}/{$attachment}' target='_blank'>{$attachment}</a>
                                              </li>";
                                    }

                                    echo "</ul>";
                                }
                            ?>
                       	</div>
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
