<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php echo form_open(); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?= "Laporan Kunjungan Balita Umur (0-60 Bulan)" ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label">Periode</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" id="for_date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m")?>" data-date-format="YYYY-MM"/>
					</div>
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
				<table id="dt-toddler-visiting" class="table table-sm" width="100%">
					<thead>	
						<tr>
							<!-- <th>Tgl Kunjungan</th> -->
							<th>NIK</th>
							<th>Nama Pasien/Nama Ortu</th>
							<th>Tgl Lahir</th>
							<th>Alamat</th>
							<th>No Telp.</th>
							<th>BB/TB/Suhu</th>
							<th>Diagnosa</th>
							<th>Therapi</th>
							<th>Asi Eksklusif</th>
						</tr>
						<tr>
							<!-- <th>Tgl Kunjungan</th> -->
							<th>NIK</th>
							<th>Nama Pasien/Nama Ortu</th>
							<th>Tgl Lahir</th>
							<th>Alamat</th>
							<th>No Telp.</th>
							<th>BB/TB/Suhu</th>
							<th>Diagnosa</th>
							<th>Therapi</th>
							<th>Asi Eksklusif</th>
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

						$("#option_doctor").on("change", function(){
							
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable.reload_table , 400 ); 
						});
						
						$("#btn-clear-filter").on("click", function(){
							
							$(".dt-filter").val("");
							$("#dt-toddler-visiting").DataTable()
							.columns()
							.search('')
							.draw(true);

						});						
					},
				reload_table : function(){
						$( "#dt-toddler-visiting" ).DataTable().ajax.reload();
					}
			};
			
		$.fn.extend({
				DataTableToddlerVisiting: function(){
						var _this = this;
						
						_datatable = _this.DataTable( {
							dom: 'Bfrtip',
							buttons: [
								'copy', 'csv', 'excelHtml5', 'print',
							],
							processing: true,
							serverSide: false,								
							paginate: false,
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
										params.date_from = $("#for_date_from").val();
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									/* { 
										data: "Tanggal", 
										className: "text-center",
										width: "100px",
									}, */
									{ 
										data: "NoIdentitas", 
										className: "text-center",
										width: "120px",
										render: function(val, type, row){
											return (row.NoIdentitas != '' && row.NoIdentitas != '-') 
													? row.NoIdentitas 
													: row.PenanggungKTP;
										}
									},
									{
										data: "NamaPasien",
										render: function(val, type, row){
											return `${row.NamaPasien}/${row.PenanggungNama}`;
										}
									},
									{ 
										data: "TglLahir", 
										className: "text-center",
										width: "100px",
										render: function(val){
											return moment(val).format('DD-MM-YYYY');
										}
									},
									{
										data: "Alamat"
									},
									{
										data: "Phone",
										render: function(val, type, row){
											return (row.Phone != '' && row.Phone != '-') 
													? row.Phone 
													: row.PenanggungPhone;
										}
									},
									{
										data: "Weight",
										render: function(val, type, row){
											return `${row.Weight}KG / ${row.Height}CM / ${row.Temperature}C`;
										}
									},
									{data: "Assessment", width: "500px"},	
									{data: "Plan", width: "500px"},	
									{
										data: "Plan", 
										render: function(val){
											return '';
										}
									},	
								]
						} );
												
					$("#dt-toddler-visiting_length select, #dt-toddler-visiting_filter input").addClass("form-control");
					$(".dt-button").addClass("btn btn-success");
					$(".dt-buttons").addClass("text-right");
					$("#dt-toddler-visiting_filter").remove();
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
				$('#dt-toddler-visiting thead tr:eq(1) th').each( function () {
					var title = $(this).text();
					$(this).html( '<input type="text" class="dt-filter" placeholder="Cari '+title+'" />' );
				});	
	
            	$("#dt-toddler-visiting").DataTableToddlerVisiting();
				
				$('#dt-toddler-visiting thead').on( 'keyup', ".dt-filter",function () {
					$("#dt-toddler-visiting").DataTable()
						.column( $(this).parent().index() )
						.search( $(this).val() )
						.draw();
				});
								
				search_datatable.init();
								
			});
	})( jQuery );
//]]>
</script>