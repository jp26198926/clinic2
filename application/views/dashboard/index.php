<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title> <?= $app_title; ?> </title>

	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<?php
	$this->load->view('template/style');
	?>

</head>

<body class="no-skin">

	<?php

	$this->load->view('template/header');

	?>

	<div class="main-container ace-save-state" id="main-container">
		<script type="text/javascript">
			try {
				ace.settings.loadState('main-container')
			} catch (e) {}
		</script>

		<?php
		$this->load->view('template/sidebar.php');
		?>

		<div class="main-content">
			<div class="main-content-inner">

				<div class="page-content">
					<?php
					$this->load->view('template/ace-settings.php');
					?>

					<div class="row">
						<div id='page_content' class="col-xs-12">
							<!-- PAGE CONTENT BEGINS -->
							<div class="page-header">
								<h1>
									Dashboard
								</h1>
							</div><!-- /.page-header -->

							<div class="row">
								<div class="col-md-6">
									<div class="widget-box">
										<div class="widget-header widget-header-flat widget-header-small">
											<h5 class="widget-title">
												<i class="ace-icon fa fa-signal"></i>
												Transactions
											</h5>

											<div class="widget-toolbar no-border">
												<div class="inline dropdown-hover">
													<button class="btn btn-minier btn-primary">
														Today
														<i class="ace-icon fa fa-angle-down icon-on-right bigger-110"></i>
													</button>

													<!-- <ul class="dropdown-menu dropdown-menu-right dropdown-125 dropdown-lighter dropdown-close dropdown-caret">
															<li class="active">
																<a href="#" class="blue">
																	<i class="ace-icon fa fa-caret-right bigger-110">&nbsp;</i>
																	This Week
																</a>
															</li>

															<li>
																<a href="#">
																	<i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
																	Last Week
																</a>
															</li>

															<li>
																<a href="#">
																	<i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
																	This Month
																</a>
															</li>

															<li>
																<a href="#">
																	<i class="ace-icon fa fa-caret-right bigger-110 invisible">&nbsp;</i>
																	Last Month
																</a>
															</li>
														</ul> -->
												</div>
											</div>
										</div>

										<div class="widget-body">
											<div class="widget-main">
												<div id="piechart-placeholder"></div>

												<div class="hr hr8 hr-double"></div>

												<div class="clearfix">
													<div class="grid3 text-primary">
														<span class="grey">
															<i class="ace-icon fa fa-users fa-2x text-primary"></i>
															PENDING
														</span>
														<h4 id="pie-pending-value" class="bigger pull-right">0</h4>
													</div>

													<div class="grid3 text-success">
														<span class="grey">
															<i class="ace-icon fa fa-check fa-2x text-success"></i>
															COMPLETED
														</span>
														<h4 id="pie-completed-value" class="bigger pull-right">0</h4>
													</div>

													<div class="grid3 text-danger">
														<span class="grey">
															<i class="ace-icon fa fa-times fa-2x text-danger"></i>
															CANCELLED
														</span>
														<h4 id="pie-cancelled-value" class="bigger pull-right">0</h4>
													</div>
												</div>
											</div><!-- /.widget-main -->
										</div><!-- /.widget-body -->
									</div><!-- /.widget-box -->
								</div><!-- /.col -->

								<div class="col-md-6">
									<div class="alert alert-info">
										<i class="fa fa-comments-o"></i>
										Public Chat
										<small class="help-block">Please Use English Language</small>
									</div>
									<div class="">
										<div class="chat_content2" style="max-height: 300px; overflow-y:scroll;">
										</div>
									</div>
									<div class="form-actions">
										<div class="input-group">
											<textarea id="txt_chat_msg2" placeholder="Type your message here ..." class="form-control" name="message2"></textarea>
											<span class="input-group-btn">
												<button id="btn_chat_send2" class="btn btn-lg btn-info no-radius" type="button">
													<i class="ace-icon fa fa-send"></i>
													Send
												</button>
											</span>
										</div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-6">
									<div class="row">
										<div class="col-md-12">
											<div class="carousel slide" data-ride="carousel" id="quote-carousel">
												<!-- Bottom Carousel Indicators -->
												<ol class="carousel-indicators">
													<li data-target="#quote-carousel" data-slide-to="0" class="active">
													</li>
													<li data-target="#quote-carousel" data-slide-to="1"></li>
													<li data-target="#quote-carousel" data-slide-to="2"></li>
												</ol>

												<!-- Carousel Slides / Quotes -->
												<div class="carousel-inner">
													<!-- Quote 1 -->
													<div class="item active">
														<blockquote>
															<div class="row">
																<div class="col-sm-3 text-center">
																	<img class="img-circle" src="<?= base_url(); ?>assets/images/1.gif" style="width: 100px;height:100px;">
																	<!--<img class="img-circle" src="https://s3.amazonaws.com/uifaces/faces/twitter/kolage/128.jpg" style="width: 100px;height:100px;">-->
																</div>
																<div class="col-sm-9">
																	<p class='quotes_msg1'>Neque porro quisquam est qui
																		dolorem
																		ipsum quia dolor sit amet, consectetur, adipisci
																		velit!
																	</p>
																	<small class='quotes_author1'>Someone famous</small>
																</div>
															</div>
														</blockquote>
													</div>
													<!-- Quote 2 -->
													<div class="item">
														<blockquote>
															<div class="row">
																<div class="col-sm-3 text-center">
																	<img class="img-circle" src="<?= base_url(); ?>assets/images/2.gif" style="width: 100px;height:100px;">
																</div>
																<div class="col-sm-9">
																	<p class='quotes_msg2'>Lorem ipsum dolor sit amet,
																		consectetur
																		adipiscing elit. Etiam auctor nec lacus ut
																		tempor.
																		Mauris.
																	</p>
																	<small class='quotes_author2'>Someone famous</small>
																</div>
															</div>
														</blockquote>
													</div>
													<!-- Quote 3 -->
													<div class="item">
														<blockquote>
															<div class="row">
																<div class="col-sm-3 text-center">
																	<img class="img-circle" src="<?= base_url(); ?>assets/images/3.gif" style="width: 100px;height:100px;">
																</div>
																<div class="col-sm-9">
																	<p class='quotes_msg3'>Lorem ipsum dolor sit amet,
																		consectetur
																		adipiscing elit. Ut rutrum elit in arcu blandit,
																		eget
																		pretium nisl accumsan. Sed ultricies commodo
																		tortor, eu
																		pretium mauris.</p>
																	<small class='quotes_author3'>Someone famous</small>
																</div>
															</div>
														</blockquote>
													</div>
												</div>

												<!-- Carousel Buttons Next/Prev -->
												<a data-slide="prev" href="#quote-carousel" class="left carousel-control"><i class="fa fa-chevron-left"></i></a>
												<a data-slide="next" href="#quote-carousel" class="right carousel-control"><i class="fa fa-chevron-right"></i></a>
											</div>
										</div>
									</div>

									<!-- <div class="row">
										<div class="col-md-12">
											<div class="page-header">
												<h1>
													Video Tutorials
												</h1>
											</div>
											<ul>
												<li><a href="https://drive.google.com/file/d/1qax9vWdWQ0O-8RHGREFLQQ098KOMhYTp/view?usp=drive_link" target="_blank">How To Create a Request</a></li>
												<li><a href="https://drive.google.com/file/d/1qayJEUtHMS6FZ_hrVCVV8vVh3t7juXbz/view?usp=drive_link" target="_blank">How The Dept Head Approved the Request</a></li>
												<li><a href="https://drive.google.com/file/d/1qbMZU9YjslIemAfPN8qg1aPw_FVeSU_p/view?usp=drive_link" target="_blank">How The GM Approved the Request</a></li>
											</ul>
										</div>
									</div> -->
								</div>
							</div>

							<!-- <div class="row" style="margin-top: 1em;">
                                <div class="col-md-12 text-center">
                                    <object data="<?= base_url(); ?>docs/Flow_Chart.pdf" type="application/pdf"
                                        width="100%" height="500px">
                                        <p>Unable to display PDF file. <a
                                                href="<?= base_url(); ?>docs/Flow_Chart.pdf">Download</a> instead.</p>
                                    </object>
                                </div>
                            </div> -->

							<!-- PAGE CONTENT ENDS -->
						</div><!-- /.col -->
					</div><!-- /.row -->

				</div><!-- /.page-content -->
			</div>
		</div><!-- /.main-content -->

		<?php
		//include('layout/footer.php');
		$this->load->view('template/footer.php');
		?>

		<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
			<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
		</a>
	</div><!-- /.main-container -->

	<!-- basic scripts -->
	<?php
	//include('layout/script.php');
	$this->load->view('template/loading');
	$this->load->view('template/script');

	?>

	<!-- inline scripts related to this page -->
	<script>
		var isLoading = false;

		$(document).ready(function() {

			const now = new Date();
			const year = now.getFullYear();
			const month = now.getMonth() + 1; // January is 0, so we add 1
			const day = now.getDate();

			let fromDate = `${year}-${month}-${day}`; // Outputs the current year, month, and day

			$("#loading").modal();

			$.post("<?= base_url(); ?>chat/get_messages", {
				// fromDate: fromDate
				fromDate: ""
			}, function(data) {
				$("#loading").modal("hide");

				if (data.indexOf("<!DOCTYPE html>") > -1) {
					alert("Error: Session Time-Out, You must login again to continue.");
					location.reload(true);
				} else if (data.indexOf("Error: ") > -1) {
					bootbox.alert(data);
				} else {
					var msg_lst = "No Converssation Yet";

					if (data) {
						var messages = JSON.parse(data);

						if (messages.length > 0) {
							msg_lst = "";
							$.each(messages, function(i, v) {
								var chat_id = v.id;
								var sender_id = v.sender_id;
								var sender = v.sender;
								var msg = v.msg;
								var dt = v.dt;
								var photo = "<?= base_url(); ?>assets/images/user.png";
								msg_lst += chat_messages(chat_id, sender_id, sender, msg, dt,
									photo);
							});

							$(".chat_content2").html(msg_lst);


							$("#txt_chat_msg2").focus().select();
							$(".chat_content2").animate({
								scrollTop: $(".chat_content2").prop("scrollHeight")
							}, 1000);
						} else {
							$(".chat_content2").html(msg_lst);
						}
					} else {
						$(".chat_content").html(msg_lst);
					}
				}
			});

		});


		$("#main").trigger("click");

		$(document).on("click", "#btn_common_password_update", function(e) {
			e.preventDefault();

			var id = $(".hidden_user_id").val();
			var oldpassword = $("#txt_common_oldpassword_update").val();
			var password = $("#txt_common_password_update").val();
			var repassword = $("#txt_common_password_update").val();

			if (id && oldpassword && password && repassword) {
				if (password == repassword) {
					$("#modal_password .modal-body").hide();
					$("#modal_password .modal_button").hide();
					$("#modal_password .modal_error").hide();
					$("#modal_password .modal_waiting").show();

					$.post("model/db_user_common.php", {
						action: 1,
						id: id,
						oldpassword: oldpassword,
						password: password,
						repassword: repassword
					}, function(data) {

						$("#modal_password .modal-body").show();
						$("#modal_password .modal_button").show();
						$("#modal_password .modal_waiting").hide();

						if (data.indexOf("<!DOCTYPE html>") > -1) {
							alert("Error: Session Time-Out, You must login again to continue.");
							location.reload(true);
						} else if (data.indexOf("Error: ") > -1) {
							$("#modal_password .modal_error_msg").text(data);
							$("#modal_password .modal_error").stop(true, true).show().delay(15000).fadeOut(
								"slow");
							$("#txt_common_oldpassword_update").focus().select();
						} else {
							$("#modal_password").modal('hide');
							bootbox.alert("Password Changed!");
						}
					});
				} else {
					$("#modal_password .modal_error_msg").text("Error: Password does not matched!");
					$("#modal_password .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
					$("#txt_common_oldpassword_update").focus().select();
				}
			} else {
				$("#modal_password .modal_error_msg").text("Error: Critical Error Encountered!");
				$("#modal_password .modal_error").stop(true, true).show().delay(15000).fadeOut("slow");
				$("#txt_common_oldpassword_update").focus().select();
			}

		});

		$(document).on("click", ".common_changepass", function(e) {
			e.preventDefault();

			var id = $(this).attr('id');
			if (id) {
				$("#modal_password .modal-body").show();
				$("#modal_password .modal_button").show();
				$("#modal_password .modal_waiting").hide();
				$("#modal_password .modal_error").hide();
				$("#modal_password").modal();

				$(".hidden_user_id").val(id);
				$(".field_user").val("");
			} else {
				bootbox.alert("Error: Critical Error Encountered!");
			}
		});

		$('#modal_password').on('shown.bs.modal', function() {
			$('#txt_common_oldpassword_update').trigger('select', 'focus');
		});


		$('.easy-pie-chart.percentage').each(function() {
			var $box = $(this).closest('.infobox');
			var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
			var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
			var size = parseInt($(this).data('size')) || 50;
			$(this).easyPieChart({
				barColor: barColor,
				trackColor: trackColor,
				scaleColor: false,
				lineCap: 'butt',
				lineWidth: parseInt(size / 10),
				animate: ace.vars['old_ie'] ? false : 1000,
				size: size
			});
		})

		//flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
		//but sometimes it brings up errors with normal resize event handlers
		$.resize.throttleWindow = false;

		var placeholder = $('#piechart-placeholder').css({
			'width': '90%',
			'min-height': '150px'
		});

		var data = [{
				label: "PENDING",
				data: 0,
				color: "#2091CF"
			},
			{
				label: "COMPLETED",
				data: 0,
				color: "#68BC31"
			},
			{
				label: "CANCELLED",
				data: 0,
				color: "#DA5430"
			}
		]

		function drawPieChart(placeholder, data, position) {
			$.plot(placeholder, data, {
				series: {
					pie: {
						show: true,
						tilt: 0.8,
						highlight: {
							opacity: 0.25
						},
						stroke: {
							color: '#fff',
							width: 2
						},
						startAngle: 2
					}
				},
				legend: {
					show: true,
					position: position || "ne",
					labelBoxBorderColor: null,
					margin: [-30, 15]
				},
				grid: {
					hoverable: true,
					clickable: true
				}
			})
		}

		drawPieChart(placeholder, data);

		/**
		we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
		so that's not needed actually.
		*/
		placeholder.data('chart', data);
		placeholder.data('draw', drawPieChart);


		//pie chart tooltip example
		var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
		var previousPoint = null;

		placeholder.on('plothover', function(event, pos, item) {
			if (item) {
				if (previousPoint != item.seriesIndex) {
					previousPoint = item.seriesIndex;
					var tip = item.series['label'] + " : " + item.series['percent'] + '%';
					$tooltip.show().children(0).text(tip);
				}
				$tooltip.css({
					top: pos.pageY + 10,
					left: pos.pageX + 10
				});
			} else {
				$tooltip.hide();
				previousPoint = null;
			}

		});

		// Declare the updatePieChart function
		function updatePieChart() {
			if (isLoading === false) {
				isLoading = true;
				// Make an AJAX request to fetch the new data from the server
				$.post("<?= base_url(); ?>dashboard/get_summary", {
					dt_from: "",
					dt_to: ""
				}, function(data) {

					isLoading = false;
					let result = JSON.parse(data);

					if (result.success) {
						drawPieChart(placeholder, result.data);
						result.data.forEach(function(item) {
							let label = item.label.toLowerCase();
							$(`#pie-${label}-value`).text(item.data);
						});
					}
				});
			}
		}

		// Call the updatePieChart function every 10 seconds
		setInterval(updatePieChart, 10000);
	</script>
</body>

</html>
