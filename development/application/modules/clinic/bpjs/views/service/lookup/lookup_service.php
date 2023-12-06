<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-body">
    <script type="text/javascript">//<![CDATA[
    function lookupbox_row_selected( response ){
        // var _response = response ;//JSON.parse(response);
		var _response = JSON.parse(response);

        if( _response ){
            
            try {
                $("#JasaIDBPJS").val( _response.kdTindakan );
                $("#TarifBPJS").val( _response.Tarif );
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
					<!--<input type="search" id="lookupbox_search_words" value="" placeholder="" class="form-control">-->
					<select id="lookupbox_search_words" class="form-control">
						<option value="10">Rawat Jalan</option>
						<option value="20">Rawat Inap</option>
					</select>
					<div class="input-group-btn">
						<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="table-responsive">
		<table id="dt-service-bpjs" class="table table-sm table-bordered table-hover" width="100%">
			<thead>
				<tr>
					<th></th>
					<th><?php echo lang('label:code') ?></th>
					<th><?php echo lang('label:name') ?></th>
					<th><?php echo lang('label:max_rates') ?></th>
					<th><?php echo lang('label:with_value') ?></th>
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
										url: "<?php echo config_item('bpjs_api_baseurl')."/tindakan/referensi/kdtkp/10/limit/100" ?>",
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
											_return.recordsFiltered = _response.found;
											_return.data = _response.collection;

											return JSON.stringify( _return ); // return JSON string
										}
									},
								columns: [
										{ 
											data: "kdTindakan",
											className: "actions",
											orderable: false,
											searchable: false,
											width: "20px",
											render: function ( val, type, row ){
													var json = JSON.stringify( {kdTindakan: row.kdTindakan, Tarif: row.maxTarif} ).replace( /"/g, '\\"' );

													//var json = JSON.stringify( row ).replace( /"/g, '\\"' );
													return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-primary btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:select" ) ?></span></a>";
												}
										},
										{data: "kdTindakan"},
										{data: "nmTindakan"},
										{
											data: "maxTarif",
											render: function(val){
												return mask_number.currency_add(val);
											}
										},
										{
											data: "withValue",
											render: function(val){
												return val ? '<?php echo lang('global:yes')?>' : '<?php echo lang('global:no')?>';
											}											
										},
									]
							} );
						
						return _this
					}
				});
			
			var _datatable = $( "#dt-service-bpjs" ).dataTableInit();
			
			var timer = 0;
			
			/*$( "button[type=\"button\"]#lookupbox_search_button" ).on("click", function(e){
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
				});	*/
			
			//$( "input[type=\"search\"]#lookupbox_search_words" ).on("keyup paste change", function(e){
			$("#lookupbox_search_words" ).on("change", function(e){	
					e.preventDefault();
	
					if (timer) {
						clearTimeout(timer);
					}
					timer = setTimeout(searchWord, 400); 
					
				});
			
			$('#dt-service-bpjs').on( 'page.dt', function (e) {
				var info = _datatable.DataTable().page.info();
				console.log(info);
				
				<?php /*?>_datatable.DataTable()
				   .ajax.url("<?php echo config_item('bpjs_api_baseurl')."/poli/5" ?>/"+ info.end);<?php */?>
				  // .load().draw(false);
			});

			
			function searchWord(){	   
				var words = $.trim( $("#lookupbox_search_words" ).val() || 10 );
				/*_datatable.DataTable().search( words );				
				_datatable.DataTable().draw(true);	*/
				
				_datatable.DataTable()
				   .ajax.url("<?php echo config_item('bpjs_api_baseurl')."/tindakan/referensi/kdtkp/" ?>"+ words +"/limit/100")
				   .load().draw();
			}
			
		})( jQuery );
	//]]></script>

</div>
<div class="modal-footer">
    <?php echo lang('patients:referrer_lookup_helper') ?>
</div>
<!-- /.modal-dialog -->

