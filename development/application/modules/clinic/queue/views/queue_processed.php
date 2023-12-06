<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

?>

<style>
</style>

<?php echo form_open(); ?>
<div class="row">
	<div class="table-responsive">
		<table id="dt-queue-processed" class="table table-sm table-striped" width="100%">
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
				$("#dt-queue-processed").DataTable().ajax.reload();
			}
		};

		$.fn.extend({
			DataTable_Queue_Processed: function() {
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
							params.status_antrian = 'deactive';
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
							className: "text-center",
							width: "10%",
							render: function(val, type, row) {
								return "<strong class=\"text-primary\">" + val + "</strong>"
							}
						},
						{
							data: "TglReg",
							width: "10%",
							className: "text-center",
							render: function(val, type, row) {
								return moment(row.JamReg).format('DD MMM YYYY HH.mm')
							}
						},
						{
							data: "NRM",
							width: "10%",
							render: function(val, type, row) {
								return "<strong class=\"text-success\">" + val + "</strong>"
							}
						},
						{
							data: "NamaPasien",
							width: "20%"
						},
						{
							data: "status_antrian",
							class: "text-center",
							width: "10%",
							render: function(val, type, row) {
								var status = `<div class="btn-group" role="group"><a href="#" title="Edit" class="btn btn-warning btn-xs"> Dilewati </a></div>`;

								if (val == 1) {
									status = `<div class="btn-group" role="group"><a href="#" title="Edit" class="btn btn-success btn-xs"> Sudah Asesmen </a></div>`;
								}

								return status;
							}
						},

					]
				});

			}
		});

		$(document).ready(function(e) {
			$("#dt-queue-processed").DataTable_Queue_Processed();
			search_datatable.init();

		});
	})(jQuery);
	//]]>
</script>