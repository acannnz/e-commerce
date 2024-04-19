<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

?>

<style>
</style>

<?php echo form_open(); ?>
<div class="row">
	<div class="table-responsive">
		<table id="dt-queue-active" class="table table-sm table-striped" width="100%">
			<thead>
				<tr>
					<th>No Antrian</th>
					<th>No Registrasi</th>
					<th>Tanggal</th>
					<th>NRM</th>
					<th>Nama</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
	//<![CDATA[
	(function($) {
		var search_datatable = {
			init: function() {
				var timer = 0;


			},
			reload_table: function() {
				$("#dt-queue-active").DataTable().ajax.reload();
			}
		};

		$.fn.extend({
			DataTable_Queue_Active: function() {
				var _this = this;

				var _datatable = _this.DataTable({
					processing: true,
					serverSide: true,
					paginate: true,
					ordering: false,
					order: [
						[0, 'desc']
					],
					searching: false,
					info: true,
					//scrollX: true,
					lengthChange: false,
					responsive: true,
					lengthMenu: [30, 45, 75, 100],
					ajax: {
						url: "<?php echo base_url("registrations/queue/datatable_queue_collection") ?>",
						type: "POST",
						data: function(params) {
							params.status_antrian = 'active';
						}
					},
					fnDrawCallback: function(settings) {
						$(window).trigger("resize");
					},
					columns: [{
							data: "NoAntrian",
							class: "text-center",
							width: "10%",
						}, {
							data: "NoReg",
							name: "a.NoReg",
							className: "text-center",
							width: "10%",
							render: function(val, type, row) {
								return "<strong class=\"text-primary\">" + val + "</strong>"
							}
						},
						{
							data: "TglReg",
							name: "TglReg",
							width: "10%",
							className: "text-center",
							render: function(val, type, row) {
								return moment(row.JamReg).format('DD MMM YYYY HH.mm')
							}
						},
						{
							data: "NRM",
							name: "NRM",
							width: "10%",
							render: function(val, type, row) {
								return "<strong class=\"text-success\">" + val + "</strong>"
							}
						},
						{
							data: "NamaPasien",
							name: "NamaPasien",
							width: "20%"
						},
						{
							data: "StatusPeriksa",
							name: "StatusPeriksa",
							class: "text-center",
							width: "10%",
							searchable: false,
							render: function(val, type, row) {
								return `<strong>Belum Dipanggil</strong>`;
							}
						}
					]
				});

			}
		});

		$(document).ready(function(e) {
			$("#dt-queue-active").DataTable_Queue_Active();
			search_datatable.init();
		});
	})(jQuery);
	//]]>
</script>