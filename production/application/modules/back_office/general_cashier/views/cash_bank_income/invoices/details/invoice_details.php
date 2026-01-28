<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('cash_bank_income:form_invoice_title') ?></h4>
        </div>
        <div class="modal-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4"><?php echo lang("cash_bank_income:invoice_number_label")?></label>
                    <div class="col-md-8">
                        <input type="text" id="No_Invoice_lookup" name="No_Invoice_lookup" class="form-control" readonly="readonly"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4"><?php echo lang("cash_bank_income:date_label")?></label>
                    <div class="col-md-8">
                        <input type="text" id="Tgl_Invoice_lookup" name="Tgl_Invoice_lookup" class="form-control" readonly="readonly"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4"><?php echo lang("cash_bank_income:description_label")?></label>
                    <div class="col-md-8">
                        <textarea id="Keterangan_lookup" name="Keterangan_lookup" class="form-control" readonly="readonly"> </textarea>
                    </div>
                </div>
			</div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4"><?php echo lang("cash_bank_income:remain_label")?></label>
                    <div class="col-md-8">
                        <input type="text" id="Sisa" name="Sisa" class="form-control" readonly="readonly"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4"><?php echo lang("cash_bank_income:pay_label")?></label>
                    <div class="col-md-8">
                        <input type="text" id="Kredit" name="Kredit" class="form-control" readonly="readonly"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4"><?php echo lang("cash_bank_income:balance_label")?></label>
                    <div class="col-md-8">
                        <input type="text" id="Saldo" name="Saldo" class="form-control" readonly="readonly"/>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table id="dt_invoice_detail" class="table table-bordered table-hover table-sm" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo lang('cash_bank_income:factur_number_label') ?></th>
                            <th><?php echo lang('cash_bank_income:description_label') ?></th>                
                            <th><?php echo lang('cash_bank_income:remain_label') ?></th>                
                            <th><?php echo lang('cash_bank_income:pay_label') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    <a href="javascript:;" title="<?php echo lang( "buttons:close" ) ?>" class="btn btn-default" data-dismiss="modal"> <?php echo lang( "buttons:close" ) ?></a>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        	<?php echo lang('supplier:supplier_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script type="text/javascript">//<![CDATA[
(function( $ ){
	var _datatable_detail;
	
	var No_Invoice = "<?php echo $No_Invoice; ?>";
	var _rowIndex = webStroge.sessionGetItem(No_Invoice + "_Index");
	var _item = webStroge.sessionGetItem(No_Invoice + "_Header");
	var _collection = webStroge.sessionGetItem(No_Invoice + "_Detail");
	
	var _datatable_populate_detail;
	var _datatable_actions_detail = {			
			init: function( row ){
					
					$.each( _collection, function(index, value){
						value.Sisa = mask_number.currency_add( value.Sisa );
						value.Kredit = mask_number.currency_add( value.Kredit );
						_datatable_detail.row.add( value ).draw();
					});
				
					$("#No_Invoice_lookup").val( _item.No_Invoice );
					$("#Tgl_Invoice_lookup").val( _item.Tgl_Invoice );
					$("#Keterangan_lookup").val( _item.Keterangan );
			
					$("#Sisa").val( mask_number.currency_add( _item.Sisa ) );
					$("#Kredit").val( mask_number.currency_add( _item.Kredit ) );
					$("#Saldo").val( mask_number.currency_add( _item.Saldo ) );
										
				},
			edit: function( row, data, index ){
					
					<?php if (@$is_edit) echo 'return;';?> 

					switch( this.index() ){
						
						case 3:
						
							var _input = $( "<input type=\"text\" value=\"\" style=\"width:100%\" class=\"form-control\">" );
							this.empty().append( _input );
							
							data.Kredit = data.Kredit || '0.00';
							_input.val(  mask_number.currency_remove( data.Kredit ) );
							_input.trigger( "focus" );
							_input.on( "blur", function(e){
									e.preventDefault();
									try{
										data.Kredit = mask_number.currency_add( this.value || '0.00' );
										
										_datatable_detail.row( row ).data( data );
										_datatable_actions_detail.calculate_balance();
										_datatable_actions_detail.store_data_detail();
										
									} catch(ex){}
								});
							
						break;
					}
				},			
			store_data_detail: function(){
					var _input_credit = $("#Kredit"),
						_input_balance = $("#Saldo"),
						collection = [];
					
					try {
						var _detail_data = _datatable_detail.rows().data();
						_detail_data.each(function(value, index){
							
							collection[index] = {
									'No_Invoice' : value.No_Invoice,
									'No_Faktur' : value.No_Faktur,
									'Sisa' : mask_number.currency_remove( value.Sisa ),
									'Kredit' : mask_number.currency_remove( value.Kredit ),
									'Keterangan' : value.Keterangan,
									'Akun_ID' : value.Akun_ID
								};
							
						});
						webStroge.sessionSetItem( No_Invoice + "_Detail", collection );
						
						// Update datatable invoice
						_item.Sisa = mask_number.currency_add( _item.Sisa );
						_item.Kredit = _input_credit.val();
						_item.Saldo = _input_balance.val();
						$("#dt_invoices").DataTable().row( _rowIndex ).data( _item ).draw();
						
						_item.Sisa = mask_number.currency_remove( _item.Sisa );
						_item.Kredit = mask_number.currency_remove( _input_credit.val() );
						_item.Saldo = mask_number.currency_remove( _input_balance.val() );
						webStroge.sessionSetItem( No_Invoice + "_Header", _item );
						
					} catch(e){console.log(e)}
				},
			calculate_balance: function(){
				
					var _input_credit = $("#Kredit"),
						_input_balance = $("#Saldo"),
						_tot_credit = _tot_balance = 0;
											
					try {
						
						var table_data = _datatable_detail.rows().data();
	
						table_data.each(function (value, key) {
													
							_tot_credit = _tot_credit + mask_number.currency_remove( value.Kredit );
							
						});		
						
						_tot_balance = _item.Sisa - _tot_credit;
						
						_input_credit.val( mask_number.currency_add( _tot_credit ) );
						_input_balance.val( mask_number.currency_add( _tot_balance ) );
												
					} catch(ex){console.log(ex)}
										
				},
		};
	
	$.fn.extend({
			dt_invoice_detail: function(){
					var _this = this;
					
					if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
						return _this
					}
					
					_datatable_detail = _this.DataTable( {
							processing: true,
							serverSide: false,								
							paginate: false,
							ordering: false,
							searching: false,
							info: false,
							autoWidth: false,
							responsive: true,
							<?php if (!empty($collection)):?>
							data: <?php print_r(json_encode($collection, JSON_NUMERIC_CHECK));?>,
							<?php endif; ?>
							columns: [
									{ data: "No_Faktur", className: "text-center", },
									{ data: "Keterangan", className: "text-left" },
									{ data: "Sisa", className: "text-left", },
									{ data: "Kredit", className: "text-left", },
									
								],
							columnDefs  : [
									{
										"targets": ["No_Invoice", "Akun_ID"],
										"visible": false,
										"searchable": false
									}
								],
							drawCallback: function( settings ) {
								//_datatable_actions_detail.calculate_total_paid();
								dev_layout_alpha_content.init(dev_layout_alpha_settings);
							},
							createdRow: function ( row, data, index ){
									$( row ).on( "dblclick", "td", function(e){
											e.preventDefault();												
											var elem = $( e.target );
											_datatable_actions_detail.edit.call( elem, row, data, index );
										});
							}
								
						} );
						
					$( "#dt_invoice_detail_length select, #dt_invoice_detail_filter input" )
					.addClass( "form-control" );
					
					return _this
				},
		});			

    $( document ).ready(function(e) {

		$( "#dt_invoice_detail" ).dt_invoice_detail();
		
		_datatable_actions_detail.init();
		                            		
	});
})( jQuery );
//]]></script>