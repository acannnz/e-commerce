<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="row">
	<div class="col-md-12">
    	<div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-plus-square-o"></i></span>
                <input type="search" id="lookupbox_search_words" value="" autocomplete="off" placeholder="" class="form-control">
                <div class="input-group-btn">
                	<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="dt-lookup-common-account" class="table table-bordered table-hover table-account" width="100%">
        <thead>
            <tr>
                <th></th>
                <th><?php echo lang('accounts:account_number_label') ?></th>
                <th><?php echo lang('accounts:account_name_label') ?></th>      
                <th><?php echo lang('accounts:normal_pos_label') ?></th>      
                <th><?php echo lang('accounts:integration_label') ?></th>      
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
				DT_Lookup_CommonAccount: function(){
						var _this = this;
						
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						var _datatable = _this.DataTable( {
							dom: 'tip',
							lengthMenu: [ 15, 30, 50 ],
							processing: true,
							serverSide: false,								
							paginate: true,
							ordering: false,
							order: [[1, 'asc']],
							searching: true,
							info: false,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("general-ledger/accounts/datatable_collection/{$source}") ?>",
									type: "POST",
									data: function( params ){											
										}
								},
							columns: [
									{ 
										data: "Akun_ID",
										className: "actions",
										orderable: false,
										searchable: false,
										width: "70px",
										render: function ( val, type, row ){
												var data = row;
												var json = JSON.stringify( data ).replace( /"/g, '\\"' );
												
												return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"label label-info\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>" 
											}
									},
									{ 
										data: "Akun_No",     
										orderable: true,
										searchable: true,
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "Akun_Name", 
										orderable: true, 
										searchable: true
									},
									{ 
										data: "Normal_Pos", 
										orderable: true, 
										searchable: true,
										className: "text-center"
									},									
									{ 
										data: "SumberIntegrasi", 
										orderable: true, 
										searchable: true,
										className: "text-center"
									}									
								],
								columnDefs  : [
										{
											"targets": [],
											"visible": false,
											"searchable": false
										}
									],
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-common-account" ).DT_Lookup_CommonAccount();
		
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

