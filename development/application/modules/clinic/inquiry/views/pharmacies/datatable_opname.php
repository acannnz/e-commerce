<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo 'List Data Stok Opname' ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo $create_link ?>" class="btn btn-info" title="Kelola Stok Opname"><b><i class="fa fa-plus"></i> <?php echo 'Stok Opname Baru' ?></b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label">Section</label>
					<div class="col-lg-4">
						<select id="Lokasi_ID" name="Lokasi_ID" class="form-control">
							<?php if($option_section_opname): foreach($option_section_opname as $row):?>
							<option value="<?php echo $row->Lokasi_ID ?>" <?php echo $row->SectionID == $section->SectionID ? "selected" : '' ?>><?php echo $row->SectionName ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Periode</label>
					<div class="col-lg-4">
						<input type="text" id="periode" name="periode" value="<?php echo date("Y-m") ?>" class="form-control datepicker" data-date-format="YYYY-MM">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Kelompok Jenis</label>
					<div class="col-lg-4">
						<select id="KelompokJenis" name="KelompokJenis" class="form-control">
							<option value="ALL">ALL</option>
							<?php if($option_kelompok_jenis): foreach($option_kelompok_jenis as $row):?>
							<option value="<?php echo $row->KelompokJenis ?>" ><?php echo $row->KelompokJenis ?></option>
							<?php endforeach; endif;?>
						</select>
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
						<th>Kelompok Jenis</th>
						<th>Keterangan</th>
						<th>User</th>
						<th>Status</th>
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
									url: "<?php echo base_url("inquiry/datatable_opname") ?>",
									type: "POST",
									data: function( params ){
										params.Lokasi_ID = $("#Lokasi_ID").val();	
										params.Periode = $("#periode").val();	
										params.KelompokJenis = $("#KelompokJenis").val();	
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
										data: "Tgl_Opname", 
										class: "text-center",
										name: "a.Tgl_Opname",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
											}
									},
									{ 
										data: "KelompokJenis", 
										className: "",
										name: "a.KelompokJenis",
									},							
									{ 
										data: "Keterangan", 
										className: "",
										name: "a.Keterangan",
									},							
									{ data: "Nama_Singkat", class: "text-center" },
									{ 
										data: "Posted", 
										class: "text-center",
										name: "a.Posted",
										render: function ( val, type, row ){
											
												return ( val ) ? "<strong class=\"text-danger\">Sudah Proses</strong>" : "Belum Proses"
											}
									},									{ 
										data: "No_Bukti",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("inquiry/stock-opname-view") ?>/" + val + "\" title=\"Lihat Stok Opname\" class=\"btn btn-default btn-xs\"> <i class=\"fa fa-eye\"></i> Lihat</a>";
												buttons += "</div>";
												
												return buttons
											}
									}								]
						} );
						
					$( "#dt-data-inquiries_length select, #dt-data-inquiries_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-data-inquiries" ).DataTable_DataInquiries();
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
		
				function searchWord(){
					var words = $.trim( $("#dt-data-inquiries_filter input").val() || "" );
					$( "#dt-data-inquiries" ).DataTable().search( words );
					$( "#dt-data-inquiries" ).DataTable().draw(true);	
				}
				
				$("#Lokasi_ID, #periode, #KelompokJenis").on("blur", function(e){
					
					$( "#dt-data-inquiries" ).DataTable().ajax.reload();
				});
				
			});
	})( jQuery );
//]]>
</script>