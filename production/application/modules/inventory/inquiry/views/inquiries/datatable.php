<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="page-subtitle margin-bottom-20">
	<div class="row">
        <div class="col-md-6">
            <h3 class="text-info"><?php echo lang('inquiry:list_subtitle') ?></h3>
            <p><?php echo lang('inquiry:list_subtitle_helper') ?></p>
        </div>
	</div>
</div>
<div class="row form-group">
	<div class="col-md-6">
    	<div class="row">
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
</div>
<div class="table-responsive">
    <table id="dt-data-inquiries" class="table table-sm" width="100%">
        <thead>
            <tr>
                <th>NoBukti</th>
                <th>Tanggal</th>
                <th>SectionAsal</th>
                <th>SectionTujuan</th>
                <th>Disetujui</th>
                <th>Oleh</th>
                <th>Keterangan</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
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
										width: "100px",
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
										data: "SectionNameAsal", 
										className: "",
										name: "b.SectionName",
									},							
									{ 
										data: "SectionNameTujuan", 
										className: "",
										name: "c.SectionName",
									},							
									{ data: "Disetujui", width: null },
									{ data: "Nama_Singkat", class: "text-center" },
									{ data: "Keterangan", width: null },
									{ data: "Keterangan", width: null },
									{ 
										data: "NoBukti",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("inquiry/emergency/create") ?>/" + val + "\" title=\"Periksa Pasien\" class=\"btn btn-default btn-xs\"> <i class=\"fa fa-medkit\"></i> Periksa</a>";
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