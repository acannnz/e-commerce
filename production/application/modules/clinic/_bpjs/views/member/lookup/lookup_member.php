<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-body">
    <script type="text/javascript">//<![CDATA[
    function lookupbox_row_selected( response ){
        var _response = JSON.parse(response);
        if( _response ){
            
            try {
                                                                
                $("#Kode_Supplier_BPJS").val( _response.kdDokter );
				$("#ajaxModal").modal('hide');
            
            } catch (e){console.log();}
        }
    }
    //]]></script>
    
	<?php echo form_open( current_url() ); ?>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-plus-square-o"></i></span>
					<input type="search" id="lookupbox_search_words" value="" placeholder="" class="form-control">
					<div class="input-group-btn">
						<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="table-responsive">
		<table id="dt-doctor-bpjs" class="table table-sm table-bordered table-hover" width="100%">
			<thead>
				<tr>
					<th></th>
					<th><?php echo lang('label:code') ?></th>
					<th><?php echo lang('label:name') ?></th>
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
					dataTableInit: function(){
							var _this = this;
							
							if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
								return _this
							}
							
							var _datatable = _this.DataTable( {
								dom: 'tip',
								processing: true,
								serverSide: false,								
								paginate: true,
								ordering: false,
								lengthMenu: [10],
								searching: true,
								info: true,
								responsive: true,
								scrollCollapse: true,
								ajax: {
										url: "<?php echo config_item('bpjs_api_baseurl')."/dokter/100" ?>",
										type: "GET",
										beforeSend: function (request) {
											request.setRequestHeader("X-API-KEY", '<?php echo config_item('bpjs_api_key') ?>');
										},
										data: function( params ){
										},
										dataFilter: function(response){
											var _response = jQuery.parseJSON( response );
											var _return = {};
											
											_return.recordsTotal = _response.found;
											_return.recordsFiltered = _response.collection.length;
											_return.data = _response.collection;
											return JSON.stringify( _return ); // return JSON string
										}
									},
								columns: [
										{ 
											data: "kdDokter",
											className: "actions",
											orderable: false,
											searchable: false,
											width: "20px",
											render: function ( val, type, row ){
													var json = JSON.stringify( row ).replace( /"/g, '\\"' );
													return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-primary btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:select" ) ?></span></a>";
												}
										},
										{data: "kdDokter"},
										{data: "nmDokter"}
									]
							} );
						
						return _this
					}
				});
			
			var _datatable = $( "#dt-doctor-bpjs" ).dataTableInit();
			
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
			
			$( "input[type=\"search\"]#lookupbox_search_words" ).on("keyup change", function(e){
					e.preventDefault();
	
					if (timer) {
						clearTimeout(timer);
					}
					timer = setTimeout(searchWord, 400); 
					
				});
			
			$('#dt-doctor-bpjs').on( 'page.dt', function () {
				var info = table.page.info();
				console.log(info);
			});
			
			function searchWord(){
				<?php /*?> $('#table_tabl').DataTable()
				   .ajax.url(
					  "<?php  echo '' ?>server_processing_reservTables.php?param=" 
					  + encodeURIComponent(this.value)
				   )
				   .load();<?php */?>
	   
				var words = $.trim( $("input[type=\"search\"]#lookupbox_search_words" ).val() || "" );
				_datatable.DataTable().search( words );				
				_datatable.DataTable().draw(true);	
			}
			
		})( jQuery );
	//]]></script>

</div>
<div class="modal-footer">
    <?php echo lang('patients:referrer_lookup_helper') ?>
</div>
<!-- /.modal-dialog -->

