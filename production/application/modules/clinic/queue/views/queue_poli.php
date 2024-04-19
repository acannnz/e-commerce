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

	<!-- audio angka -->
	<audio id="audio-0" src="<?php echo base_url(); ?>themes/default/assets/audio/angka/0.mp3" preload="auto"></audio>
	<audio id="audio-1" src="<?php echo base_url(); ?>themes/default/assets/audio/angka/1.mp3" preload="auto"></audio>
	<audio id="audio-2" src="<?php echo base_url(); ?>themes/default/assets/audio/angka/2.mp3" preload="auto"></audio>
	<audio id="audio-3" src="<?php echo base_url(); ?>themes/default/assets/audio/angka/3.mp3" preload="auto"></audio>
	<audio id="audio-4" src="<?php echo base_url(); ?>themes/default/assets/audio/angka/4.mp3" preload="auto"></audio>
	<audio id="audio-5" src="<?php echo base_url(); ?>themes/default/assets/audio/angka/5.mp3" preload="auto"></audio>
	<audio id="audio-6" src="<?php echo base_url(); ?>themes/default/assets/audio/angka/6.mp3" preload="auto"></audio>
	<audio id="audio-7" src="<?php echo base_url(); ?>themes/default/assets/audio/angka/7.mp3" preload="auto"></audio>
	<audio id="audio-8" src="<?php echo base_url(); ?>themes/default/assets/audio/angka/8.mp3" preload="auto"></audio>
	<audio id="audio-9" src="<?php echo base_url(); ?>themes/default/assets/audio/angka/8.mp3" preload="auto"></audio>

	<!-- audio huruf -->
	<audio id="audio-A" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/a.mp3" preload="auto"></audio>
	<audio id="audio-B" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/b.mp3" preload="auto"></audio>
	<audio id="audio-C" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/c.mp3" preload="auto"></audio>
	<audio id="audio-D" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/d.mp3" preload="auto"></audio>
	<audio id="audio-E" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/e.mp3" preload="auto"></audio>
	<audio id="audio-F" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/f.mp3" preload="auto"></audio>
	<audio id="audio-G" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/g.mp3" preload="auto"></audio>
	<audio id="audio-H" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/h.mp3" preload="auto"></audio>
	<audio id="audio-I" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/i.mp3" preload="auto"></audio>
	<audio id="audio-J" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/j.mp3" preload="auto"></audio>
	<audio id="audio-K" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/k.mp3" preload="auto"></audio>
	<audio id="audio-L" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/l.mp3" preload="auto"></audio>
	<audio id="audio-M" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/m.mp3" preload="auto"></audio>
	<audio id="audio-N" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/n.mp3" preload="auto"></audio>
	<audio id="audio-O" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/o.mp3" preload="auto"></audio>
	<audio id="audio-P" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/p.mp3" preload="auto"></audio>
	<audio id="audio-Q" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/q.mp3" preload="auto"></audio>
	<audio id="audio-R" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/r.mp3" preload="auto"></audio>
	<audio id="audio-S" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/s.mp3" preload="auto"></audio>
	<audio id="audio-T" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/t.mp3" preload="auto"></audio>
	<audio id="audio-U" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/u.mp3" preload="auto"></audio>
	<audio id="audio-V" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/v.mp3" preload="auto"></audio>
	<audio id="audio-W" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/w.mp3" preload="auto"></audio>
	<audio id="audio-X" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/x.mp3" preload="auto"></audio>
	<audio id="audio-Y" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/y.mp3" preload="auto"></audio>
	<audio id="audio-Z" src="<?php echo base_url(); ?>themes/default/assets/audio/huruf/z.mp3" preload="auto"></audio>

	<!-- audio poli -->
	<audio id="audio-gigi" src="<?php echo base_url(); ?>themes/default/assets/audio/poli/gigi.mp3" preload="auto"></audio>
	<audio id="audio-poli" src="<?php echo base_url(); ?>themes/default/assets/audio/poli/poli.mp3" preload="auto"></audio>
	<audio id="audio-umum" src="<?php echo base_url(); ?>themes/default/assets/audio/poli/umum.mp3" preload="auto"></audio>
	<audio id="audio-kebidanan" src="<?php echo base_url(); ?>themes/default/assets/audio/poli/kebidanan.mp3" preload="auto"></audio>

	<!-- audio dll -->
	<audio id="audio-antrian" src="<?php echo base_url(); ?>themes/default/assets/audio/dll/antrian.mp3" preload="auto"></audio>
	<audio id="audio-menuju" src="<?php echo base_url(); ?>themes/default/assets/audio/dll/menuju.mp3" preload="auto"></audio>
	<audio id="audio-silahkan" src="<?php echo base_url(); ?>themes/default/assets/audio/dll/silahkan.mp3" preload="auto"></audio>

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

	.box-calling-queue {
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

	<div class="dev-page-container body ">

		<!-- page content container -->
		<div class="body-top">
			<div id="calling-box" class="col-sm-4 col-xs-12 max-height top-left-padding">
				<a class="tile tile-info box-calling-queue bg-blue" style="height: 100%;">
					<div class="body-box-top bold"><span id="calling-poli"><?= $queue_active ? $queue_active->SectionName : "POLI" ?></span></div>
					<div class="body-box-bot"><span id="calling-queue"><?= $queue_active ? $queue_active->NoAntrian : "-" ?></span></div>
				</a>
			</div>
			<div class="col-sm-8 col-xs-12 max-height max-height top-right-padding">
				<a class="tile tile-default max-height bg-blue" style="padding:unset;">
					<video id="video_company" height="100%" style="float: left;" autoplay loop muted>
						<source src="<?= base_url('/themes/default/assets/img/amovie.mp4') ?>" type="video/mp4">
					</video>
					<h5 style="color: white">Informasi 1</h5>
					<h5 style="color: white">Informasi 2</h5>
					<h5 style="color: white">Informasi </h5>
				</a>
			</div>
		</div>
		<div class="body-bot">

			<?php foreach ($option_section as $key => $val) { ?>
				<div id="<?= $val->SectionID ?>" class="col-sm-<?= $column_width ?> col-xs-12 max-height <?= $key == 0 ? "bot-left-padding" : "" ?> <?= $key == count($option_section) - 1 ? "bot-right-padding" : "" ?> bot-menu-padding">
					<a class="tile tile-info max-height box-section bg-blue">
						<div class="box-top bold"><span><?= $val->SectionName ?></span></div>
						<div class="box-bot"><span id="queue-number<?= $val->SectionID ?>"><?= $val->queue ?></span></div>
					</a>
				</div>
			<?php } ?>

		</div>
	</div>


	<div class="bottom bg-blue">
		<marquee class="max-height">
			<h1 class="text-white">This is basic example of marquee</h1>
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
			$(".box-top").css("font-size", height * 3 / 100);
			$(".box-bot").css("font-size", height * 8 / 100);
			$(".body-box-top").css("font-size", height * 8 / 100);
			$(".body-box-bot").css("font-size", height * 22 / 100);

			//seconds loop
			setInterval(function() {
				var now = moment();
				$("#running-time").html(moment(now).format("HH:mm:ss"));
			}, 1000);

			// var socket = new WebSocket('ws://localhost:8080');
			var socket = new WebSocket('ws://' + '<?= config_item('websocket_ip') ?>' + ':8080');
			var list = [];
			var processing = false;

			//function request pemanggilan
			var request = function name(data) {

				if (!data) {
					return false;
				}

				var section = {};
				section['section'] = data;

				$.post('<?php echo $queue_calling_url ?>', section, function(response, status, xhr) {

					if ("success" == response.status && !processing) {
						processing = true;

						var queue = String(response.queue.NoAntrian);


						$("#calling-poli").html(response.queue.SectionName);
						$("#calling-queue").html(queue);
						$("#queue-number" + response.queue.SectionID).html(response.queue.prefixAntrian + queue);

						// pemanggilan antrian
						queue = queue.split("");

						call = ["antrian"];

						for (let i = 0; i < queue.length; i++) {
							call.push(queue[i]);
						}

						switch (response.queue.prefixAntrian) {
							case 'G':
								PoliName = 'gigi'
								break;
							case 'K':
								PoliName = 'kebidanan'
								break;
							default:
								PoliName = 'umum'
								break;
						}

						call.push("silahkan", "menuju", "poli", PoliName);
						calling();
					}
				});
			}

			//function pemanggilan antrian
			var calling = function() {
				for (let i = 0, p = Promise.resolve(); i < call.length; i++) {
					p = p.then(_ => new Promise(resolve =>
						setTimeout(function() {

							$("#audio-" + call[i]).get(0).play();
							resolve();

							if (i + 1 == call.length) {
								// mengubah status processing menjadi false
								list.shift();
								processing = false;

								//pengecekan apakah terdapat antrian panggilan
								if (list.length > 0) {
									$("#audio-" + call[i]).on("ended", function() {
										setTimeout(function() {
											request(list[0]);
										}, 5000);
									});
								}
								if (list.length <= 0) {
									setTimeout(function() {
										socket.send("end_queue");
									}, 2000);
								}
							}

						}, 1200)
					));
				}
			}

			socket.onmessage = function(e) {

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
				console.log(list);
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