<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//print_r($resource);exit;
?>

<?php echo form_open( current_url() ); ?>
<div class="row">
	<div class="col-md-12">
    	<div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user-md"></i></span>
                <input type="search" id="lookupbox_search_words" value="" placeholder="" autocomplete="off" class="form-control">
                <div class="input-group-btn">
                	<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="dt-lookup-general-cashier-vouchers" class="table table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th></th>
                <th><?php echo lang('vouchers:date_label') ?></th>
                <th><?php echo lang('vouchers:voucher_number_label') ?></th>
                <th><?php echo lang('vouchers:supplier_label') ?></th>
                <th><?php echo lang('vouchers:remain_label') ?></th>
                <th><?php echo lang('vouchers:description_label') ?></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<?php echo form_close() ?>
<script type="text/javascript">//<![CDATA[
(function( $ ){

		var _form = $( "form[name=\"form_voucher\"]" );
		var account_id = _form.find( "input[id=\"account_id\"]" ).val();

		$.fn.extend({
				DT_Lookup_GeneralCashierVouchers: function(){
						var _this = this;
						
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
												
						var _datatable_gl = $( this ).DataTable( {
							dom: 'tip',
							lengthMenu: [ 10, 20 ],
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[1, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							//scrollCollapse: true,
							//scrollY: "200px",
							ajax: {
									url: "<?php echo base_url("payable/vouchers/lookup_collection") ?>",
									type: "POST",
									data: function( params ){
										
										params.account_id = account_id;
									}
								},
							columns: [
									{ 
										data: "id",
										className: "actions",
										orderable: false,
										searchable: false,
										width: "70px",
										render: function ( val, type, row ){
												var data = row;
												var json = JSON.stringify( data ).replace( /"/g, '\\"' );
												return "<a id=\"apply\" href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}'title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-info btn-xs apply\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>";
											}
									},
									{ data: "voucher_date", orderable: true},
									{ 
										data: "voucher_number",     
										orderable: true,
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "supplier_name", orderable: true},
									{ data: "remain", orderable: true},
									{ data: "description", orderable: true},
								]
						} );
					
					return _this
				}
			});
		

		var _datatable_gl = $( "#dt-lookup-general-cashier-vouchers" ).DT_Lookup_GeneralCashierVouchers();
		
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
				if( words ){
					_datatable_gl.DataTable().search( words );
					_datatable_gl.DataTable().draw(true);
				}
		}
		
	})( jQuery );
//]]></script>

