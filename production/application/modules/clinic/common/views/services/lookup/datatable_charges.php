<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="row">
	<div class="col-md-6">
    	<div class="form-group">
            <div class="checkbox">
                <input type="checkbox" id="show_all" name="f[show_all]" value="1" class=""><label for="show_all">Tampil Semua</label>
            </div>
        </div>
    </div>
	<div class="col-md-6">
    	<div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user-md"></i></span>
                <input type="search" id="lookupbox_search_words" value="" placeholder="" class="form-control">
                <div class="input-group-btn">
                	<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="dt-lookup-common-services" class="table table-sm table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th></th>
                <th><?php echo lang('services:code_label') ?></th>
                <th><?php echo lang('services:title_label') ?></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<?php echo form_close() ?>
<script type="text/javascript">//<![CDATA[
(function( $ ){
		$.fn.extend({
				DT_Lookup_CommonServices: function(){
						var _this = this;
						
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						var _datatable = _this.DataTable( {
							dom: 'tip',
							lengthMenu: [ 15, 30, 60 ],
							processing: true,
							serverSide: false,								
							paginate: true,
							ordering: true,
							order: [[1, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							//scrollCollapse: true,
							//scrollY: "200px",
							ajax: {
									url: "<?php echo base_url("common/services/datatable_charge_collection") ?>",
									type: "POST",
									data: function( params ){
										params.SectionID = $("#SectionID").val();
										params.JenisKerjasamaID = $("#JenisKerjasamaID").val();
										params.NoAnggota = $('#NoAnggota').val() || '';
										params.PasienKTP = $("#PasienKTP").val();
										params.show_all = $("#show_all:checked").val() || 0;
									}
								},
							columns: [
									{ 
										data: "JasaID",
										className: "actions",
										orderable: false,
										searchable: false,
										width: "70px",
										render: function ( val, type, row ){
												var data = row;
												var json = JSON.stringify( data ).replace( /"/g, '\\"' );
												return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>";
											}
									},
									{ 
										data: "JasaID",     
										width: "70px",
										orderable: true,
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "JasaName", orderable: true},
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-common-services" ).DT_Lookup_CommonServices();
		
		var timer = 0;
		
		$( "button[type=\"button\"]#lookupbox_search_button" ).on("click", function(e){
				e.preventDefault();
				
				if (timer) {
					clearTimeout(timer);
				}
				timer = setTimeout(searchWord, 400); 
				
			});
		
		$( "input[type=\"search\"]#lookupbox_search_words" ).on("keypress", function(e){
				if ( (e.which || e.keyCode) == 13 ) {
					e.preventDefault();
					return false
				}
			});	
		
		$( "input[type=\"search\"]#lookupbox_search_words" ).on("keyup paste change", function(e){
				e.preventDefault();

				if (timer) {
					clearTimeout(timer);
				}
				timer = setTimeout(searchWord, 400); 
				
			});
		
		function searchWord(){
			var words = $.trim( $("input[type=\"search\"]#lookupbox_search_words" ).val() || "" );
			_datatable.DataTable().search( words );
			_datatable.DataTable().draw(true);	
		}
		
		$("#show_all").on("change",function(e){
			_datatable.DataTable().ajax.reload();
		});
				
	})( jQuery );
//]]></script>

