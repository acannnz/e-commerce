<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?= form_open(); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?= "Laporan Kunjungan Usia Produktif per Jenis Kelamin" ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Periode</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" id="for_date_from" class="form-control searchable datepicker" value="<?= date("Y-m")?>" />
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">&nbsp;</label>
					<button id="btn-search" type="button" class="btn btn-info btn-block"><b><i class="fa fa-search"></i> <?= lang("buttons:search")?></b></button>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">&nbsp;</label>
					<button id="btn-clear-filter" type="button" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?= 'Bersihkan Pencarian'?></b></button>
				</div>
			</div>
		</div>
		<?= form_close() ?>
		<div class="row">
			<div class="table-responsive">
				<table id="dt-medical-records" class="table table-sm" width="100%">
					<thead>	
						<tr>
							<!-- <th>No</th> -->
							<th>Pria</th>
							<th>Perempuan</th>
							<th>Dirujuk</th>
						</tr>
						<tr>
							<!-- <th>No</th> -->
							<th>Pria</th>
							<th>Perempuan</th>
							<th>Dirujuk</th>
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
										
						$(".datepicker").datetimepicker({format: "YYYY-MM"}).on("dp.change", function (e) {
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
							$("#dt-medical-records").DataTable()
							.columns()
							.search('')
							.draw(true);

						});						
					},
				reload_table : function(){
						$( "#dt-medical-records" ).DataTable().ajax.reload();
					}
			};
			
		$.fn.extend({
				DataTable_reservations: function(){
						var _this = this;
						
						_datatable = _this.DataTable( {
							dom: 'Bfrtip',
							buttons: [
								'copy', 'csv', 'excelHtml5', 'print',
							],
							processing: true,
							serverSide: false,								
							paginate: true,
							ordering: true,
							order: [[0, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							lengthChange: true,
							lengthMenu: [ 50, 75, 100 ],
							orderCellsTop: true,
							ajax: {
									url: "<?= $collection_url ?>",
									type: "POST",
									data: function( params ){
										params.date = $("#for_date_from").val();	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									/* { 
										data: "NRM", 
										className: "text-center",
										width: "100px",
										render: function ( val, type, row, meta ){
												return meta.row+1;
											}
									}, */
									{data: "JumlahPria"},
									{data: "JumlahWanita"},
									{data: "JumlahDirujuk"},
								]
						} );
												
					$("#dt-medical-records_length select, #dt-medical-records_filter input").addClass("form-control");
					$(".dt-button").addClass("btn btn-success");
					$(".dt-buttons").addClass("text-right");
					$("#dt-medical-records_filter").remove();
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
				$('#dt-medical-records thead tr:eq(1) th').each( function () {
					var title = $(this).text();
					$(this).html( '<input type="text" class="dt-filter" placeholder="Cari '+title+'" />' );
				});
	
            	$("#dt-medical-records").DataTable_reservations();
				
				$('#dt-medical-records thead').on( 'keyup', ".dt-filter",function () {
					$("#dt-medical-records").DataTable()
						.column( $(this).parent().index() )
						.search( $(this).val() )
						.draw();
				});
								
				search_datatable.init();
								
			});
	})( jQuery );
//]]>
</script>