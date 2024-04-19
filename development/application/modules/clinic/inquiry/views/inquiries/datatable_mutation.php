<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo 'List Data Mutasi' ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo $create_link ?>" class="btn btn-info" title="Mutasi Baru"><b><i class="fa fa-plus"></i> <?php echo 'Mutasi Baru' ?></b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?php echo 'Tanggal Dari' ?></label>
					<div class="input-group">
						<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
						<input type="text" id="date_from" name="date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-01")?>" />
						<div class="input-group-addon"><i class="fa fa-long-arrow-right"></i></div>
						<input type="text" id="date_till" name="date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-t") ?>" />
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table id="dt-data-inquiries" class="table table-sm" width="100%">
				<thead>
					<tr>
						<th>NoBukti</th>
						<th>Tanggal</th>
						<th>Lokasi Asal</th>
						<th>Lokasi Tujuan</th>
						<th>Keterangan</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var search_datatable = {
			init : function(){
					var timer = 0;
			
					$( ".searchable" ).on("keyup", function(e){
						e.preventDefault();
		
						var isWordCharacter = event.key.length === 1;
						var isBackspaceOrDelete = (event.keyCode == 8 || event.keyCode == 46);
					
						if (isWordCharacter || isBackspaceOrDelete) {
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable.reload_table , 600 ); 
						}
					});
	
					$( ".searchable_option, .check-searchable" ).on("change", function(e){
		
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable.reload_table , 600 ); 
							
					});
					
					$("#date_from, #date_till").datetimepicker({format: "YYYY-MM-DD"}).on("dp.change", function (e) {
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable.reload_table , 600 ); 
	
					});
							
					$("#reset").on("click", function(){
						
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable.reload_table , 600 ); 
					});
					
				},
			reload_table : function(){
					$( "#dt-data-inquiries" ).DataTable().ajax.reload();
				}
		};

		$.fn.extend({
				DataTable_DataInquiries: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[1, 'desc']],
							searching: true,
							info: true,
							responsive: true,
							lengthMenu: [ 30, 45, 75, 100 ],
							ajax: {
									url: "<?php echo base_url("inquiry/datatable_mutation") ?>",
									type: "POST",
									data: function( params ){
										params.section_id = '<?php echo $section->SectionID?>';	
										params.date_from = $("#date_from").val();	
										params.date_till = $("#date_till").val();	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "No_Bukti", 
										className: "text-center",
										name: "a.No_Bukti",
										width: "150px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Tgl_Mutasi", 
										class: "text-center",
										name: "a.Tgl_Mutasi",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
											}
									},
									{ 
										data: "NamaLokasiAsal", 
										className: "",
										name: "b.Nama_Lokasi",
									},							
									{ 
										data: "NamaLokasiTujuan", 
										className: "",
										name: "c.Nama_Lokasi",
									},							
									{ 
										data: "Keterangan", 
										className: "",
										name: "a.Keterangan",
									},							
									{ 
										data: "No_Bukti",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("inquiry/mutation-view/{$type}") ?>/" + val + "\" title=\"Lihat Mutasi\" class=\"btn btn-default btn-sm\"> <i class=\"fa fa-eye\"></i> Lihat</a>";
												buttons += "</div>";
												
												return buttons
											}
									}								
								]
						} );
						
					$( "#dt-data-inquiries_length select, #dt-data-inquiries_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-data-inquiries" ).DataTable_DataInquiries();
				search_datatable.init();
				
			});
	})( jQuery );
//]]>
</script>