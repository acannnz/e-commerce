<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('inquiry:list_subtitle') ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo $create_link ?>" class="btn btn-info" title="Kelola Mutasi"><b><i class="fa fa-plus"></i> <?php echo 'Amprah Baru' ?></b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?php echo 'Tanggal Dari' ?></label>
					<div class="input-group">
						<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
						<input type="text" id="date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-01")?>" />
						<div class="input-group-addon"><i class="fa fa-long-arrow-right"></i></div>
						<input type="text" id="date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-t") ?>" />
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
						<th>Section Asal</th>
						<th>Section Tujuan</th>
						<th>Realisasi</th>
						<th>Oleh</th>
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
									url: "<?php echo base_url("inquiry/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.SectionID = '<?php echo $section->SectionID ?>';	
										params.date_from = $("#date_from").val();	
										params.date_till = $("#date_till").val();	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "NoBukti", 
										className: "text-center",
										name: "a.NoBukti",
										width: "150px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Tanggal", 
										class: "text-center",
										name: "a.Tanggal",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
											}
									},
									{ 
										data: "SectionAsalName", 
										className: "",
										name: "b.SectionName",
									},							
									{ 
										data: "SectionTujuanName", 
										className: "",
										name: "c.SectionName",
									},							
									{ data: "Realisasi", width: null },
									{ data: "Nama_Singkat", class: "text-center" },
									{ data: "Keterangan", width: null },
									{ 
										data: "NoBukti",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("inquiry/request_view/{$type}") ?>/" + val + "\" title=\"<?php echo lang('buttons:view')?>\" class=\"btn btn-default btn-xs\"> <i class=\"fa fa-eye\"></i> <?php echo lang('buttons:view')?></a>";
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
				
				$("#date_from, #date_till").on("blur", function(e){
					
					$( "#dt-data-inquiries" ).DataTable().ajax.reload();
				});
				
			});
	})( jQuery );
//]]>
</script>