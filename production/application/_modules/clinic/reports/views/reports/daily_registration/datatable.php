<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php echo form_open(); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?= "Laporan Registrasi Harian" ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Tanggal</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" id="for_date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d")?>" />
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Dokter</label>
					<?= form_dropdown('option_doctor', $option_doctor, '', ['id' => 'option_doctor', 'class' => 'form-control'])?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">&nbsp;</label>
					<button id="btn-search" type="button" class="btn btn-info btn-block"><b><i class="fa fa-search"></i> <?php echo lang("buttons:search")?></b></button>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">&nbsp;</label>
					<button id="btn-clear-filter" type="button" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo 'Bersihkan Pencarian'?></b></button>
				</div>
			</div>
		</div>
		<?php echo form_close() ?>
		<div class="row">
			<div class="table-responsive">
				<table id="dt-medical-records" class="table table-sm" width="100%">
					<thead>	
						<tr>
							<th>No</th>
							<th>Pasien</th>
							<th>Customer</th>
							<th>L/P/Umur</th>
							<th>Diagnosa</th>
							<th>Jenis Obat</th>
							<th>Biaya Obat</th>
							<th>Tindakan</th>
							<th>Biaya Tindakan</th>
						</tr>
						<tr>
							<th>No</th>
							<th>Pasien</th>
							<th>Customer</th>
							<th>L/P/Umur</th>
							<th>Diagnosa</th>
							<th>Jenis Obat</th>
							<th>Biaya Obat</th>
							<th>Tindakan</th>
							<th>Biaya Tindakan</th>
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

						$("#option_doctor").on("change", function(){
							
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
							order: [[7, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							lengthChange: true,
							lengthMenu: [ 15, 45, 75, 100 ],
							orderCellsTop: true,
							ajax: {
									url: "<?php echo base_url("{$nameroutes}/daily_registration_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date = $("#for_date_from").val();	
										params.doctor = $("#option_doctor").val();	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "NRM", 
										className: "text-center",
										width: "100px",
										render: function ( val, type, row, meta ){
												return meta.row+1;
											}
									},
									{data: "NamaPasien"},
									{
										data: "NamaCustomer",
										render: function(v, t, r){
											if (r.JenisKerjasamaID != 3) {
												return v;
											}
											 return '';
										}
									},
									
									{
										data: "JenisKelamin",
										render: function(val, type, row){
											var gender = val == 'F' ? 'P' : 'L';
											return gender +'/'+ row.Umur
										}
									},
									{data: "Assessment", width: "500px"},	
									{data: "Plan", width: "500px"},	
									{data: "Plan", render:function(v){return '';}},	
									{
										data: "Tindakan", 
										render: function(val){
											var _return = '';
											
											$.each(val, function(i, v){
												_return += v.JasaName +', ';
											});

											return _return;
										}
									},	
									{
										data: "Tindakan", 
										render: function(val){
											var _return = 0;
											$.each(val, function(i, v){
												_return += parseFloat(v.Tarif);
											});

											return mask_number.currency_add(_return);
										}
									},	
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