<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php echo form_open(); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?= "Laporan Rekam Medis Obat Pasien" ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Periode</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" id="for_date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d")?>" />
						<span class="input-group-addon"><i class="fa fa-long-arrow-right"></i></span>
						<input type="text" id="for_date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
					</div>
				</div>
			</div>
		</div>
		<?php echo form_close() ?>
		<div class="row">
			<div class="table-responsive">
				<table id="dt-drug-history" class="table table-sm" width="100%">
					<thead>	
						<tr>
							<th>Tanggal</th>
                            <th>Nama Pasien</th>
							<th>No Resep</th>
							<th>Nama Resep Obat</th>
							<th>Nama Dokter</th>
						</tr>
						<tr>
                            <th>Tanggal</th>
                            <th>Nama Pasien</th>
							<th>No Resep</th>
							<th>Nama Resep Obat</th>
							<th>Nama Dokter</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		var search_datatable = {
				init : function(){
						var timer = 0;
										
						$(".datepicker").datetimepicker({format: "YYYY-MM-DD"}).on("dp.change", function (e) {
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable.reload_table , 400 ); 
		
						});

						$("#btn-search").on("click", function(){
							
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable.reload_table , 400 ); 
						});
						
						$("#btn-clear-filter").on("click", function(){
							
							$(".dt-filter").val("");
							$("#dt-drug-history").DataTable()
							.columns()
							.search('')
							.draw(true);

						});						
					},
				reload_table : function(){
						$( "#dt-drug-history" ).DataTable().ajax.reload();
					}
			};
			
		$.fn.extend({
				DataTable_drug_history: function(){
						var _this = this;
						
						_datatable = _this.DataTable( {
							dom: 'Bfrtip',
							buttons: [
								'copy', 'csv', 'excel', 'print'
							],
							processing: true,
							serverSide: false,								
							paginate: true,
							ordering: true,
							// order: [[7, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							lengthChange: true,
							lengthMenu: [ 15, 45, 75, 100 ],
							orderCellsTop: true,
							ajax: {
									url: "<?php echo base_url("{$nameroutes}/drug_history_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#for_date_from").val();	
										params.date_till = $("#for_date_till").val();
										// params.patient_age = $("#patient_age").val();	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "Tanggal", 
										className: "text-center",
										width: "100px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
                                    {data: "NamaPasien"},
									{ 
										data: "NoResep", 
										className: "text-center",
										width: "130px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{data: "NamaResepObat"},
									{data: "NamaDokter"},
								]
						} );
												
					$("#dt-drug-history_length select, #dt-drug-history_filter input").addClass("form-control");
					$(".dt-button").addClass("btn btn-success");
					$(".dt-buttons").addClass("text-right");
					$("#dt-drug-history_filter").remove();
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
				$('#dt-drug-history thead tr:eq(1) th').each( function () {
					var title = $(this).text();
					$(this).html( '<input type="text" class="dt-filter" placeholder="Cari '+title+'" />' );
				});
	
            	$("#dt-drug-history").DataTable_drug_history();
				
				$('#dt-drug-history thead').on( 'keyup', ".dt-filter",function () {
					$("#dt-drug-history").DataTable()
						.column( $(this).parent().index() )
						.search( $(this).val() )
						.draw();
				});
								
				search_datatable.init();
								
			});
	})( jQuery );
//]]>
</script>