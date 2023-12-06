<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">Daftar Antrian</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-4 col-xs-12">
				<a href="#" class="tile tile-default">
					<h3 style="font-size: 30px !important;">Antrian</h3>
					<h1 style="font-size: 50px !important;" id="queue-number"><?= @$queue_active ?></h1>
				</a>
			</div>
			<div class="col-sm-4 col-xs-12">
				<a href="javascript;" class="tile tile-success btn-calling">
					<i class="fa  fa-microphone"></i>
					<h3>Panggil</h3>
				</a>
			</div>
			<div class="col-sm-4 col-xs-12">
				<a href="javascript;" class="tile tile-info btn-skip">
					<i class="fa fa-angle-double-right"></i>
					<h3>Selanjutnya</h3>
				</a>
			</div>
		</div>
		<ul id="tab-poly" class="nav nav-tabs nav-justified">
			<li class="active"><a href="#tab-queue-active" data-toggle="tab"> Belum Panggil</a></li>
			<li><a href="#tab-queue-processed" data-toggle="tab"> Sudah Panggil</a></li>
		</ul>
		<div class="tab-content">
			<div id="tab-queue-active" class="tab-pane tab-pane-padding active">
				<?php echo Modules::run("registrations/queue/queue_active"); ?>
			</div>
			<div id="tab-queue-processed" class="tab-pane tab-pane-padding">
				<?php echo Modules::run("registrations/queue/queue_processed"); ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	//<![CDATA[
	(function($) {
		// var socket = new WebSocket('ws://localhost:8080');
		var socket = new WebSocket('ws://' + '<?= config_item('websocket_ip') ?>' + ':8080');
		$(document).ready(function(e) {

			socket.onmessage = function(e) {
				console.log('tes');
				if (e.data == "refresh_queue") {
					setTimeout($("#dt-queue-active").DataTable().ajax.reload(), 600);
					setTimeout($("#dt-queue-processed").DataTable().ajax.reload(), 600);

					$.post('<?php echo $queue_calling_url ?>', function(response, status, xhr) {

						$("#queue-number").html(response.queue);
					});
				}
			};
		});

		$(".btn-calling").on("click", function(e) {
			e.preventDefault();

			if (confirm('Anda yakin memanggil antrian ?')) {
				$.post('<?php echo $queue_calling_url ?>', function(response, status, xhr) {

					$("#queue-number").html(response.queue);
					
					socket.send("queue_calling");
				});
			}
		})

		$(".btn-skip").on("click", function(e) {
			e.preventDefault();

			if (confirm('Anda yakin melewati antrian saat ini ?')) {
				$.post('<?php echo $queue_skip_url ?>', function(response, status, xhr) {

					$("#queue-number").html(response.queue);
					setTimeout($("#dt-queue-active").DataTable().ajax.reload(), 600);
					setTimeout($("#dt-queue-processed").DataTable().ajax.reload(), 600);

					socket.send("refresh_queue");
				});
			}
		})


	})(jQuery);
	//]]>
</script>