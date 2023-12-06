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
    <table id="dt-lookup-general-cashier-invoices" class="table table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th></th>
                <th><?php echo lang('cash_bank_income:date_label') ?></th>
                <th><?php echo lang('cash_bank_income:customer_name_label') ?></th>
                <th><?php echo lang('cash_bank_income:invoice_number_label') ?></th>
                <th><?php echo lang('cash_bank_income:remain_label') ?></th>
                <th><?php echo lang('cash_bank_income:type_label') ?></th>
                <th><?php echo lang('cash_bank_income:description_label') ?></th>
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
				DT_Lookup_GeneralCashierInvoices: function(){
						var _this = this;
						
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
												
						var _datatable = $( this ).DataTable( {
							dom: 'tip',
							lengthMenu: [ 15, 20 ],
							processing: true,
							serverSide: false,								
							paginate: true,
							ordering: true,
							order: [[1, 'desc']],
							searching: true,
							info: true,
							responsive: true,
							//scrollCollapse: true,
							//scrollY: "200px",
							ajax: {
									url: "<?php echo base_url("general-cashier/cash-bank-income/invoices/lookup_invoice_collection") ?>",
									type: "POST",
									data: function( params ){
										
									}
								},
							columns: [
									{ 
										data: "No_Invoice",
										className: "actions",
										orderable: false,
										searchable: false,
										width: "70px",
										render: function ( val, type, row ){
												var json = JSON.stringify( row ).replace( /"/g, '\\"' );
												return "<a id=\"apply\" href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}'title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-info btn-xs apply\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>";
											}
									},
									{ data: "Tgl_Invoice", orderable: true},
									{ data: "Nama_Customer", orderable: true},
									{ 
										data: "No_Invoice",     
										orderable: true,
										width: "150px",
										class: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "Sisa", orderable: true},
									{ data: "Nama_Type", orderable: true},
									{ data: "Keterangan", orderable: true},
								]
						} );
					
					return _this
				}
			});
		

		var _datatable = $( "#dt-lookup-general-cashier-invoices" ).DT_Lookup_GeneralCashierInvoices();
		
		var timer = 0;
		
		$( "button[type=\"button\"]#lookupbox_search_button" ).on("click", function(e){
				e.preventDefault();
				
				if (timer) {
					clearTimeout(timer);
				}
				timer = setTimeout(searchWord, 300); 
				
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
				timer = setTimeout(searchWord, 300); 
				
			});
		
		function searchWord(){
			var words = $.trim( $("input[type=\"search\"]#lookupbox_search_words" ).val() || "" );
				_datatable.DataTable().search( words );
				_datatable.DataTable().draw( true );
		}
		
	})( jQuery );
//]]></script>

