<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php echo form_open(); ?>
<div class="col-md-offset-1 col-md-10">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?= "Laporan Jumlah Diagonasa Pasien" ?></h3>
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
				<!-- <div class="col-md-3">
					<div class="form-group">
						<label class="control-label">&nbsp;</label>
						<button id="reset" type="button" class="btn btn-info btn-block"><b><i class="fa fa-search"></i> <?php echo lang("buttons:search")?></b></button>
					</div>
				</div> -->
			</div>
			<?php echo form_close() ?>
			<div class="row">
				<div class="">
					<table id="dt-group-by-icd" class="table table-sm" width="100%">
						<thead>	
							<tr>
								<th class="no-search">No</th>
								<th>Kode ICD</th>
								<th>Nama ICD</th>
								<th>Jumlah</th>
							</tr>
							<tr>
								<th class="no-search"></th>
								<th>Kode ICD</th>
								<th>Nama ICD</th>
								<th class="no-search"></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
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
						
						$(".btn-clear").on("click", function(){
							target = $(this).data("target");
							
							$(target).val("");
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable.reload_table , 400 ); 

						});
		
						$("#reset").on("click", function(){
							
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable.reload_table , 400 ); 
						});
						
					},
				reload_table : function(){
						$( "#dt-group-by-icd" ).DataTable().ajax.reload();
					}
			};
			
		$.fn.extend({
				DataTable_reservations: function(){
						var _this = this;
						
						_datatable = _this.DataTable( {
							dom: 'Bfrtip',
							buttons: [
								'copy', 'csv', 'excel', 'print'
							],
							processing: true,
							serverSide: false,								
							paginate: false,
							ordering: false,
							order: [[1, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							lengthChange: false,
							lengthMenu: [ 15, 45, 75, 100 ],
							orderCellsTop: true,
							ajax: {
									url: "<?php echo base_url("{$nameroutes}/group_by_icd_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#for_date_from").val();	
										params.date_till = $("#for_date_till").val();	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{
										data: "KodeICD",
										render: function ( val, type, row, meta ){
											var number = Number(meta.row) + 1;
											return "<strong class=\"text-primary\">" + number + "</strong>"
										}
									},
									{ 
										data: "KodeICD", 
										className: "text-center",
										width: "130px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{data: "ICDName"},
									{
										data: "Jumlah",
										width: "200px",
										className: "text-right",
									},
								]
						} );
												
					$("#dt-group-by-icd_length select, #dt-group-by-icd_filter input").addClass("form-control");
					$(".dt-button").addClass("btn btn-success");
					$(".dt-buttons").addClass("text-right");
					$("#dt-group-by-icd_filter").remove();
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
				$('#dt-group-by-icd thead tr:eq(1) th').each( function () {
					var title = $(this).text();
					
					if(! $(this).hasClass('no-search'))
					{
						$(this).html( '<input type="text" class="dt-filter" placeholder="Search '+title+'" />' );
					}
				});
	
            	$("#dt-group-by-icd").DataTable_reservations();
				
				$('#dt-group-by-icd thead').on( 'keyup', ".dt-filter",function () {
					console.log($(this).parent().index(), $(this).val() );
					$("#dt-group-by-icd").DataTable()
						.column( $(this).parent().index() )
						.search( $(this).val() )
						.draw();
				});
								
				search_datatable.init();
								
			});
	})( jQuery );
//]]>
</script>