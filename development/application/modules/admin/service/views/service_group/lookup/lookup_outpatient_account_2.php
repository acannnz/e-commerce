<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-body">
    <script type="text/javascript">//<![CDATA[
    function lookupbox_row_selected( response ){
        var _response = JSON.parse(response);
        if( _response ){
            
            try {
                                                                
                $("#AkunNoRJ_2").val( _response.Akun_No );
				$("#NamaAkunNoRJ_2").val( _response.Akun_No+' - '+_response.Akun_Name );
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
		<table id="dt-hpp-account" class="table table-sm table-bordered table-hover" width="100%">
			<thead>
				<tr>
					<th></th>
					<th><?php echo lang('label:account_number') ?></th>
					<th><?php echo lang('label:account_name') ?></th>
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
								searching: true,
								info: true,
								responsive: true,
								scrollCollapse: true,
								//scrollY: "200px",
								ajax: {
										url: "<?php echo base_url("service/account/lookup_collection") ?>",
										type: "POST",
										data: function( params ){
											params._where = {
												"Group_ID" : "4",
											}
											
											params.database = 'BO_2';
										}
									},
								columns: [
										{ 
											data: "Akun_No",
											className: "actions",
											orderable: false,
											searchable: false,
											width: "20px",
											render: function ( val, type, row ){
													var data = {'Akun_No' : row.Akun_No, 'Akun_Name': row.Akun_Name};
													var json = JSON.stringify( data ).replace( /"/g, '\\"' );
													return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-primary btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:select" ) ?></span></a>";
												}
										},
										{data: "Akun_No"},
										{data: "Akun_Name"}
									]
							} );
						
						return _this
					}
				});
			
			var _datatable = $( "#dt-hpp-account" ).dataTableInit();
			
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
			
		})( jQuery );
	//]]></script>

</div>
<div class="modal-footer">
    <?php echo lang('patients:referrer_lookup_helper') ?>
</div>
<!-- /.modal-dialog -->

