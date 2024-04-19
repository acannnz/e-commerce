<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="row">
	<div class="col-md-12">
    	<div class="form-group">
            <div class="input-group">
                <div class="input-group-btn">
					<button type="button" id="lookupbox_refresh_button" title="Refresh Data Table" class="btn btn-primary"><b><i class="fa fa-refresh"></i></b></button>                
                </div>
                <input type="search" id="lookupbox_search_words" value="" placeholder="" class="form-control">
                <div class="input-group-btn">
                	<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="dt-lookup-invoices" class="table table-sm table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th></th>
                <th><?php echo lang("outstanding_payment:evidence_number_label")?></th>
                <th><?php echo lang("outstanding_payment:date_label")?></th>
                <th><?php echo lang("outstanding_payment:registration_number_label")?></th>
                <th><?php echo lang("outstanding_payment:nrm_label")?></th>
                <th><?php echo lang("outstanding_payment:patient_name_label")?></th>
                <th><?php echo lang("outstanding_payment:patient_type_label")?></th>
                <th><?php echo lang("outstanding_payment:outstanding_value_label")?></th>
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
				DTLookupInvoices: function(){
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
							order: [[2, 'DESC']],
							searching: true,
							info: true,
							responsive: true,
							//scrollCollapse: true,
							//scrollY: "200px",
							ajax: {
									url: "<?php echo base_url("cashier/outstanding-payment/lookup_invoice_collection") ?>",
									type: "POST",
									data: function( params ){
									}
								},
							columns: [
									{ 
										data: "NoBukti",
										className: "actions text-center",
										orderable: false,
										searchable: false,
										width: "120px",
										render: function ( val, type, row ){
												var json = JSON.stringify( row ).replace( /"/g, '\\"' );
												return "<a href='javascript:try{lookupbox_row_selected(\"" + json + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"label label-primary\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>" 
											}
									},
									{ 
										data: "NoBukti",     
										width: "160px",
										orderable: true,
										searchable: true,
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "Tanggal", 
										orderable: true, 
										searchable: true,
										className: "text-center",
										render: function( val ){
											return val.substr(0,19)
										}
									},
									{ 
										data: "NoReg", 
										orderable: true, 
										searchable: true,
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "NRM", 
										orderable: true, 
										searchable: true,
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "NamaPasien", 
										orderable: true, 
										searchable: true,
									},
									{ 
										data: "JenisKerjasama", 
										orderable: true, 
										searchable: true,
										className: "text-center",
									},
									{ 
										data: "NilaiOutStanding", 
										orderable: true, 
										searchable: true,
										className: "text-right",
										render: function( val ){
											return parseFloat(val).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
										}
									},								
								]
						} );
					
					return _this
				}
			});
		
		var _datatable = $( "#dt-lookup-invoices" ).DTLookupInvoices();
		
		var timer = 0;
		
		$("#lookupbox_refresh_button").on("click", function(e){
			e.preventDefault();
				
			if (timer) {
				clearTimeout(timer);
			}
			timer = setTimeout(reloadTable, 400); 
		});
		
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

		function reloadTable(){
			_datatable.DataTable().ajax.reload();	
		}
		
	})( jQuery );
//]]></script>

