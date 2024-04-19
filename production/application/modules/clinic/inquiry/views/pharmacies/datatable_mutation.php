<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo 'List Data Mutasi' ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo $create_link ?>" class="btn btn-info" title="Kelola Mutasi"><b><i class="fa fa-plus"></i> <?php echo 'Mutasi Baru' ?></b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-2 control-label text-center">From</label>
					<div class="col-lg-4">
						<input type="text" id="date_from" name="date_from" value="<?php echo date("Y-m-01") ?>" class="form-control datepicker">
					</div>
					<label class="col-lg-2 control-label text-center">Till</label>
					<div class="col-lg-4">
						<input type="text" id="date_till" name="date_till" value="<?php echo date("Y-m-t") ?>" class="form-control datepicker">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<a href="javascript:;" id="refresh-datatable" class="btn btn-success"><b><i class="fa fa-refresh"></i> <?php echo lang('buttons:refresh')?> </b></a>
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
		var datatable_searchable = {
				init: function(){
		
					$('#dt-data-inquiries_filter input').unbind();
	
					var timer = 0;
					$( "#dt-data-inquiries_filter input" ).on("keypress", function(e){
						if ( (e.which || e.keyCode) == 13 ) {
							e.preventDefault();
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout(searchWord, 400); 
						}
					});	
					
					$( "#dt-data-inquiries_filter input" ).on("keyup paste", function(e){
						e.preventDefault();
		
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout(searchWord, 400); 
					});		
					
					$("#refresh-datatable").on("click", function(e){
						
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout(searchWord, 500); 
					});

			
					function searchWord(){
						var words = $.trim( $("#dt-data-inquiries_filter input").val() || "" );
						$( "#dt-data-inquiries" ).DataTable().search( words );
						$( "#dt-data-inquiries" ).DataTable().draw(true);	
					}
					
					function refreshTable(){
						$( "#dt-data-inquiries" ).DataTable().ajax.reload();
					}
				}
			}
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
													buttons += "<a href=\"<?php echo base_url("inquiry/mutation-view") ?>/" + val + "\" title=\"Lihat Stok Opname\" class=\"btn btn-default btn-xs\"> <i class=\"fa fa-eye\"></i> Lihat</a>";
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
				
				datatable_searchable.init();
				
			});
	})( jQuery );
//]]>
</script>