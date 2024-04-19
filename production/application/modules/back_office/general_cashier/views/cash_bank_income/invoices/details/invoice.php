<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_invoices" class="table table-bordered" width="100%">
                <thead>
                	<tr>
                    	<th></th>
                        <th><?php echo lang("cash_bank_income:customer_label") ?></th>
                        <th><?php echo lang("cash_bank_income:invoice_number_label") ?></th>
                        <th><?php echo lang("cash_bank_income:remain_label") ?></th>
                        <th><?php echo lang("cash_bank_income:pay_label") ?></th>
                        <th><?php echo lang("cash_bank_income:balance_label") ?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php if (!@$is_edit): ?>	
<div class="form-group">
    <div class="col-lg-12">
        <a href="<?php echo $lookup_invoices ?>" data-toggle='lookup-ajax-modal' class="btn btn-primary btn-xl col-md-12"><b><i class="fa fa-search"></i> <?php echo lang("cash_bank_income:find_invoice_list_label")?></b></a>
    </div>
</div>
<?php endif; ?>	
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {		
				edit: function( row, data, index ){
						
						switch( this.index() ){
							case 0:
								
								<?php if (!@$is_edit): ?>	
								try{
									if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, null, row )
												}
								} catch(ex){}
								<?php endif; ?>	
								
							break;
							
							default :
								try{
									trId = _datatable.row( row ).index();
									
									lookup_ajax_modal.show("<?php echo @$form_invoice_detail_url ?>/?No_Invoice="+ encodeURIComponent(data.No_Invoice) )
								} catch(ex){}
							break;
						}
					},
				get_invoice_detail: function( row, data, index ){
					
						$.get("<?php echo $get_invoice_detail_url ?>", {'No_Invoice' : data.No_Invoice }, function( response, status, xhr ){

							var response = $.parseJSON(response);
	
							if( response.status == "error"){
								$.alert_error(response.message);
								return false
							}
							
							var collection = [];
							$.each( response.collection, function( index, value ){
								value.Kredit = value.Kredit || value.Sisa;
								
								collection[ index ] = value;
							});
							
							data.Sisa = mask_number.currency_remove( data.Sisa );
							data.Kredit = mask_number.currency_remove( data.Kredit );
							data.Saldo = mask_number.currency_remove( data.Saldo );
							
							// Store invoice item and detail (collection) to WebStroge
							webStroge.sessionSetItem( data.No_Invoice + "_Index", index );
							webStroge.sessionSetItem( data.No_Invoice + "_Header", data );
							webStroge.sessionSetItem( data.No_Invoice + "_Detail", collection );
							
						});				
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw();
														
					},
				calculate_balance: function(){
				
					var _total_pay = $("#pay_total"),
						_tot_credit = _tot_balance = 0;
											
					try {
						
						var table_data = $("#dt_invoices").DataTable().rows().data();
	
						table_data.each(function (value, key) {
													
							_tot_credit = _tot_credit + mask_number.currency_remove( value.Kredit );
							
						});		
						
						_total_pay.html( mask_number.currency_add( _tot_credit ) );
												
					} catch(ex){console.log(ex)}
										
				},
				
			};
		
		$.fn.extend({
				dt_invoices: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
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
										{ 
											data: "No_Invoice", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "Nama_Customer", },
										{ data: "Tgl_Invoice", className: "text-center",  },
										{ data: "Sisa", className: "text-right" },
										{ data: "Kredit", className: "text-right",  },
										{ data: "Saldo", className: "text-right",  },										
									],
								columnDefs  : [
										{
											"targets": ["Debit", "JenisPiutang_ID", "Akun_ID"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
									_datatable_actions.calculate_balance();
								},
								createdRow: function ( row, data, index ){
									
										_datatable_actions.get_invoice_detail( row, data, index )
										
										$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
										
										<?php if (!@$is_edit): ?>	
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, null, row )
												}
											})
										<?php endif; ?>	
									}
							} );
							
						$( "#dt_invoices_length select, #dt_invoices_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_invoices" ).dt_invoices();
				
				var timer = 0;
				$("form[name=\"form_cash_bank_income\"]").on("submit", function(e){
					e.preventDefault();
					// untuk 
					if (timer) {
						clearTimeout(timer);
					}
					
					timer = setTimeout(postCashBankIncome, 400); 
						
				});
				
				function postCashBankIncome(){
					
					var data_post = {
							"nota" : {},
							"invoice" : {},
							"factur" : {},
						};
					
					data_post.cashier = {
						"AkunBG_ID" : $("#Akun_ID").val(),
						"Type_Transaksi" : $("#Type_Transaksi").val(),
						"Keterangan" : $("#Keterangan").val(),
						"Tgl_Transaksi"  : $("#Tgl_Transaksi").val(),
						"Tgl_Update" : $("#Tgl_Transaksi").val(),
					};
					
					var invoice_data = $( "#dt_invoices" ).DataTable().rows().data();
					
					invoice_data.each(function(value, index){
						// Get Invoice from WebStroge
						data_post.invoice[ index ] = webStroge.sessionGetItem( value.No_Invoice + "_Header" ); 
						// Get Invoice detail (Factur) from WebStroge
						data_post.factur[ value.No_Invoice ] = {}
						$.each(webStroge.sessionGetItem( value.No_Invoice + "_Detail" ), function(_index, _value){
							data_post.factur[ value.No_Invoice ][ _index ] = {
									"No_Invoice" : _value.No_Invoice,
									"No_Faktur" : _value.No_Faktur,
									"Sisa" : _value.Sisa,
									"Kredit" : _value.Kredit,
									"Akun_ID" : _value.Akun_ID,
									"Keterangan" : _value.Keterangan,
								}
						}); 
					});
					
					$.post($("form[name=\"form_cash_bank_income\"]").attr("action"), data_post, function( response, status, xhr ){

						var response = $.parseJSON(response);

						if( response.status == "error"){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success( response.message );
						
						var No_Bukti = response.No_Bukti;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("general-cashier/cash-bank-income/invoices/edit"); ?>/?No_Bukti="+ No_Bukti ;
							
							}, 300 );
						
					});				
				}
								
			});
	})( jQuery );
//]]>
</script>

 