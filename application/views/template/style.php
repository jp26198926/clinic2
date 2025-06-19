<!-- bootstrap & fontawesome -->
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />

<!-- page specific plugin styles -->
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/jquery-ui.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap-clockpicker.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/chosen.min.css" />
<!--
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/table-fixed-header.css" />
-->
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/toastr.min.css" />

<!-- text fonts -->
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/fonts.googleapis.com.css" />

<!-- ace styles -->
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

<!--[if lte IE 9]>
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
<![endif]-->
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/ace-skins.min.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/ace-rtl.min.css" />

<!--[if lte IE 9]>
	<link rel="stylesheet" href="<?= base_url(); ?>assets/css/ace-ie.min.css" />
<![endif]-->

<!-- inline styles related to this page -->

<!-- ace settings handler -->
<script src="<?= base_url(); ?>assets/js/ace-extra.min.js"></script>

<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

<!--[if lte IE 8]>
	<script src="<?= base_url(); ?>assets/js/html5shiv.min.js"></script>
	<script src="<?= base_url(); ?>assets/js/respond.min.js"></script>
<![endif]-->

<!-- countdown style -->
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/jquery.countdown.css">

<!-- custom css by jaypee hindang -->
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/qoutes.css" />

<!--
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/chat.css" />
-->

<!-- custom css code by jaypee hindang -->
<style>
	@media (min-width: 768px) {
		.modal-xl {
			width: 95%;
			max-width: 1400px;
		}
	}

	.modal-body * {
		font-size: 98%;
	}

	.chat_messages {
		white-space: pre-wrap;
	}

	/* upload button */
	.btn-file {
		position: relative;
		overflow: hidden;
	}

	.btn-file input[type=file] {
		position: absolute;
		top: 0;
		right: 0;
		min-width: 100%;
		min-height: 100%;
		font-size: 100px;
		text-align: right;
		filter: alpha(opacity=0);
		opacity: 0;
		background: red;
		cursor: inherit;
		display: block;
	}

	/* Lab Modal Styles */
	#modal_lab .modal-dialog {
		max-width: 95%;
	}
	
	#modal_lab .table th {
		background-color: #f5f5f5;
		font-weight: bold;
	}
	
	#modal_lab .btn_download_lab {
		margin: 2px;
		font-size: 11px;
	}
	
	#modal_lab .upload-section {
		background-color: #f9f9f9;
		border-radius: 5px;
		padding: 15px;
	}
	
	#modal_lab .form-control {
		margin-bottom: 5px;
	}
	
	#modal_lab .table-responsive {
		max-height: 400px;
		overflow-y: auto;
	}

</style>
