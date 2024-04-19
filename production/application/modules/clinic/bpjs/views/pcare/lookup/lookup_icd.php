<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<script type="text/javascript">//<![CDATA[
function lookupbox_row_selected( response ){
	var _response = JSON.parse(response);
	if( _response ){
		
		try {
															
			$("#kdDiag<?= $id ?>").html( _response.KodeICD );
			$("#nmDiag<?= $id ?>").val( _response.Descriptions );
			$("#btn-close-icd").trigger('click');
		
		} catch (e){console.log();}
	}
}
//]]></script>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" id="btn-close-icd" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title">Lookup ICD</h4>
		</div>
		<div class="modal-body">    
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
				<table id="dt-icd" class="table table-sm table-bordered table-hover" width="100%">
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
		</div>
		<div class="modal-footer">
			<?php echo lang('patients:referrer_lookup_helper') ?>
		</div>
		<!-- /.modal-dialog -->
	</div>
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
								serverSide: true,								
								paginate: true,
								ordering: true,
								order: [[1, 'asc']],
								lengthMenu: [15],
								searching: true,
								info: true,
								ajax: {
										url: "<?php echo base_url('common/icd/lookup_collection')?>",
										type: "GET",
									},
								columns: [
										{ 
											data: "KodeICD",
											className: "actions",
											orderable: false,
											searchable: false,
											width: "20px",
											render: function ( val, type, row ){
													var json = JSON.stringify( row ).replace( /"/g, '\\"' );
													return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:select" ) ?></span></a>";
												}
										},
										{data: "KodeICD"},
										{data: "Descriptions"},
									]
							} );
						
						return _this
					}
				});
			
			var _datatable = $( "#dt-icd" ).dataTableInit();
			
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
			
			function searchWord(){	   
				var words = $.trim( $("input[type=\"search\"]#lookupbox_search_words" ).val() || "a" );
				_datatable.DataTable().search( words );				
				_datatable.DataTable().draw();					
			}
			
		})( jQuery );
	//]]></script>