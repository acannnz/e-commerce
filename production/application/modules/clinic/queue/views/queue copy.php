<?php defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set("Asia/Hong_Kong");
?>
<!DOCTYPE html>
<html lang="<?php echo lang('lang_code') ?>" class="app">

<head>
	<meta charset="utf-8" />
	<title>Halaman Antrian</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="author" content="<?php echo $this->config->item('site_author'); ?>">
	<meta name="keyword" content="<?php echo $this->config->item('site_desc'); ?>">
	<meta name="description" content="">
	<meta name="mobile-web-app-capable" content="yes">

	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url("themes/default/assets/img/favicon"); ?>/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url("themes/default/assets/img/favicon"); ?>/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url("themes/default/assets/img/favicon"); ?>/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url("themes/default/assets/img/favicon"); ?>/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url("themes/default/assets/img/favicon"); ?>/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url("themes/default/assets/img/favicon"); ?>/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url("themes/default/assets/img/favicon"); ?>/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url("themes/default/assets/img/favicon"); ?>/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url("themes/default/assets/img/favicon"); ?>/apple-icon-180x180.png">
	<link rel="shortcut icon" type="image/png" sizes="192x192" href="<?php echo base_url("themes/default/assets/img/favicon"); ?>/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url("themes/default/assets/img/favicon"); ?>/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url("themes/default/assets/img/favicon"); ?>/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url("themes/default/assets/img/favicon"); ?>/favicon-16x16.png">
	<link rel="manifest" href="<?php echo base_url("themes/default/assets/img/favicon"); ?>/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php echo base_url("themes/default/assets/img/favicon"); ?>/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/intuitive/assets/css/green-white.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/intuitive/assets/css/green-white-custom.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>themes/intuitive/assets/css/dev-plugins/font-awesome/font-awesome.min.css">

	<!-- javascripts -->
	<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/modernizr/modernizr.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/bootstrap/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>themes/intuitive/assets/js/plugins/moment/moment.js"></script>
	<!-- ./javascripts -->

</head>

<style>
	.max-height {
		height: 100% !important;
	}

	.max-width {
		width: 100% !important;
	}

	.top-left-padding {
		padding-left: 2%;
		padding-top: 2%;
		padding-bottom: 2%;
	}

	.top-right-padding {
		padding-right: 2%;
		padding-top: 2%;
		padding-bottom: 2%;
	}

	.bot-menu-padding {
		padding-top: unset !important;
		padding-bottom: 2% !important;
		padding-left: 2%;
    	padding-right: 2%;
	}

	.bot-left-padding {
		padding-left: 2%;
	}

	.bot-right-padding {
		padding-right: 2%;
	}

	.header {
		z-index: 2;
		height: 10%;
		background-color: black;
	}

	.body {
		height: 85%;
		background-image: linear-gradient(to bottom, #74ebd5, #96ebc3, #b3eab5, #cee8ad, #e5e5ac);
	}

	.body-top {
		height: 70%;
	}

	.body-bot {
		height: 30%;
	}

	.bottom {
		z-index: 2;
		background-color: black;
		height: 5%;
	}

	.text-white {
		color: white;
	}

	.box-queue_active {
		padding: 5%;
	}

	.box-section {
		padding: 3%;
	}

	.box-top {
		height: 30%;
	}

	.box-bot {
		height: 70%;
	}

	.body-box-top {
		height: 35%;
		color: yellow;
	}

	.body-box-bot {
		height: 65%;
		color: yellow;
	}

	.tile {
		z-index: 2;
	}

	.bold {
		font-weight: bold;
	}

	.bg-blue {
		background-color: #125873 !important;
		border-color: #125873 !important;
	}

	.bg-bubbles {
		position: absolute;
		top: 0;
		left: 0;
		width: 95%;
		height: 75%;
		z-index: 1;
	}

	.bg-bubbles li {
		position: absolute;
		list-style: none;
		display: block;
		width: 40px;
		height: 40px;
		background-color: rgba(255, 255, 255, 0.15);
		bottom: -160px;
		-webkit-animation: square 25s infinite;
		animation: square 25s infinite;
		-webkit-transition-timing-function: linear;
		transition-timing-function: linear;
	}

	.bg-bubbles li:nth-child(1) {
		left: 10%;
	}

	.bg-bubbles li:nth-child(2) {
		left: 20%;
		width: 80px;
		height: 80px;
		-webkit-animation-delay: 2s;
		animation-delay: 2s;
		-webkit-animation-duration: 17s;
		animation-duration: 17s;
	}

	.bg-bubbles li:nth-child(3) {
		left: 25%;
		-webkit-animation-delay: 4s;
		animation-delay: 4s;
	}

	.bg-bubbles li:nth-child(4) {
		left: 40%;
		width: 60px;
		height: 60px;
		-webkit-animation-duration: 22s;
		animation-duration: 22s;
		background-color: rgba(255, 255, 255, 0.25);
	}

	.bg-bubbles li:nth-child(5) {
		left: 70%;
	}

	.bg-bubbles li:nth-child(6) {
		left: 80%;
		width: 120px;
		height: 120px;
		-webkit-animation-delay: 3s;
		animation-delay: 3s;
		background-color: rgba(255, 255, 255, 0.2);
	}

	.bg-bubbles li:nth-child(7) {
		left: 32%;
		width: 160px;
		height: 160px;
		-webkit-animation-delay: 7s;
		animation-delay: 7s;
	}

	.bg-bubbles li:nth-child(8) {
		left: 55%;
		width: 20px;
		height: 20px;
		-webkit-animation-delay: 15s;
		animation-delay: 15s;
		-webkit-animation-duration: 40s;
		animation-duration: 40s;
	}

	.bg-bubbles li:nth-child(9) {
		left: 25%;
		width: 10px;
		height: 10px;
		-webkit-animation-delay: 2s;
		animation-delay: 2s;
		-webkit-animation-duration: 40s;
		animation-duration: 40s;
		background-color: rgba(255, 255, 255, 0.3);
	}

	.bg-bubbles li:nth-child(10) {
		left: 90%;
		width: 160px;
		height: 160px;
		-webkit-animation-delay: 11s;
		animation-delay: 11s;
	}

	@-webkit-keyframes square {
		0% {
			-webkit-transform: translateY(0);
			transform: translateY(0);
		}

		100% {
			-webkit-transform: translateY(-700px) rotate(600deg);
			transform: translateY(-700px) rotate(600deg);
		}
	}

	@keyframes square {
		0% {
			-webkit-transform: translateY(0);
			transform: translateY(0);
		}

		100% {
			-webkit-transform: translateY(-700px) rotate(600deg);
			transform: translateY(-700px) rotate(600deg);
		}
	}
</style>

<body class="max-width max-height modal-open">
	<div class="dev-page-header header bg-blue">
		<div class="max-height">
			<img src="<?= base_url('/themes/default/assets/img/logo.png') ?>" class="max-height" id="logo">
			<span id="today" class="max-height text-white text-center" style="float:right"><span id="running-time"></span><br><b><?= date('d F Y') ?></b></span>
		</div>
	</div>
	<div class="audioAntrean">

	</div>
	<button type="button" id="playAnteran" style="display:none" onclick="document.getElementById('player').play()"></button>


	<div class="dev-page-container body ">

		<!-- page content container -->
		<div class="body-top">
			<div id="calling-box" class="col-sm-4 col-xs-12 max-height top-left-padding">
				<a class="tile tile-info box-queue_active bg-blue" style="height: 100%;">
					<div class="body-box-top bold"><span style="margin-bottom: 20px;">NRM </span></div>
					<div class="body-box-top bold"><span id="queue_nrm"></span></div>
					
				</a>
			</div>
			<div class="col-sm-8 col-xs-12 max-height max-height top-right-padding">
				<a class="tile tile-default max-height bg-blue" style="padding:unset;">
					<div class="body-box-bot bold"><span id="queue_poli" style="font-size: 50px;">POLI</span></div>
				</a>
			</div>
		</div>
		<div class="body-bot">

			<div id="queue-container" class="col-sm-12 col-xs-12 max-height bot-menu-padding">
				<a class="tile tile-info max-height box-section bg-blue">
					<div class="box-top bold"> <span id="queue_patient_name" style="font-size: 42px;">NAMA PASIEN</span></div>
				</a>
			</div>

		</div>
	</div>


	<div class="bottom bg-blue">
		<marquee class="max-height">
			<h1 class="text-white"><?php echo config_item('company_name') ?></h1>
		</marquee>
	</div>
	<ul class="bg-bubbles">
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
	</ul>
	<script>
		$(document).on("ready", function(e) {

			height = parseInt($("body").height());

			//responsive display
			$("marquee h1").css("font-size", height * 3 / 100);
			$("marquee h1").css("margin", 0);
			$("marquee").css("padding-top", height * 1 / 100);
			$("#logo").css("padding", height * 1 / 100);
			$("#today").css("font-size", height * 2.5 / 100);
			$("#running-time").css("font-size", height * 3.5 / 100);
			$("#today").css("padding", height * 1 / 100);
			$(".box-top").css("font-size", height * 6 / 100);
			$(".box-bot").css("font-size", height * 8 / 100);
			$(".body-box-top").css("font-size", height * 8 / 100);
			$(".body-box-bot").css("font-size", height * 22 / 100);

			//seconds loop
			setInterval(function() {
				var now = moment();
				$("#running-time").html(moment(now).format("HH:mm:ss"));
			}, 1000);

			var socket = new WebSocket('ws://' + '<?= config_item('websocket_ip') ?>' + ':8080');
			var list = [];
			var processing = false;

			//function request pemanggilan
			var request = function name(data) {

				if (!data) {
					return false;
				}

			}


			socket.onmessage = function(e) {
				// JIKA ADA PEMANGGILAN DI POLI
				if (e.data == "start_queue" || e.data == "end_queue") {
					return false;
				}

				if (e.data == "queue_calling") {
					$.post('<?php echo base_url('queue/queue_calling_new') ?>', function(response, status, xhr) {
						if ("success" == response.status && !processing) {
							$("#queue_left").html(response.queue_left);
						}
					});
					return false;
				}

				if (e.data == "queue_calling") {
					$.get('<?php echo base_url('queue/queue_calling_new') ?>', function(response, status, xhr) {
						// $(".audioAntrean").html("");
						// $('.audioAntrean').append(response.html)
						// $("#playAnteran").click(); 
						// $("#queue_patient_name").html(response.data.NamaPasien);
						// $("#queue_poli").html(response.data.SectionName);
						// $("#queue_nrm").html(response.data.NRM);
						
					});
				}
				// var _response = e.data;
				// var _response_mapping = _response.split(',');
				// // JIKA ADA PEMANGGILAN DI POLI
				// if (_response_mapping[0] == "queue_calling") {
				// 	var data_post = {};
				// 	data_post['patient'] = {
				// 		NoReg: _response_mapping[1],
				// 		SectionID: _response_mapping[2]
				// 	}
				// 	$.post('<?php echo base_url('queue/queue_calling_new') ?>', data_post, function(response, status, xhr) {
				// 		$(".audioAntrean").html("");
				// 		$('.audioAntrean').append(response.html)
				// 		$("#playAnteran").click(); 
				// 		$("#queue_patient_name").html(response.data.NamaPasien);
				// 		$("#queue_poli").html(response.data.SectionName);
				// 		$("#queue_nrm").html(response.data.NRM);
						
				// 	});
				// }
				if (e.data == "start_queue" || e.data == "end_queue") {
					return false;
				}

				socket.send("start_queue");

				//duplicate section check
				if (list.length < 1) {
					list.push(e.data);
				} else {
					var duplicate = false;
					for (let i = 0; i < list.length; i++) {
						if (list[i] == e.data) {
							duplicate = true;
						}
					}

					if (!duplicate) {
						list.push(e.data);
					}
				}
				// console.log(list);
				request(e.data);
			}
		});

		$(document).on("click", ".close,#start-queue", function(e) {
			e.preventDefault();
			$("#form-ajax-modal").remove();
		});
	</script>

</body>

<div class="modal in" id="form-ajax-modal" role="dialog" tabindex="-1" aria-hidden="false" style="display: block; padding-right: 17px;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-default">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h1 class="modal-title">Halaman Antrian</h1>
			</div>
			<div class="modal-body">
				<div class="row">
					<h3>Tekan tombol mulai untuk memulai antrian.</h3>
				</div>
			</div>
			<div class="modal-footer">
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<button id="start-queue" type="button" class="btn btn-info btn-block">Mulai</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

</html>