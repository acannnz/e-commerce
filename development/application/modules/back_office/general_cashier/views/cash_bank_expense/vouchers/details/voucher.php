<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_vouchers" class="table table-bordered" width="100%">
                <thead>
                	<tr>
                    	<th></th>
                        <th><?php echo lang("cash_bank_expense:customer_label") ?></th>
                        <th><?php echo lang("cash_bank_expense:voucher_number_label") ?></th>
                        <th><?php echo lang("cash_bank_expense:remain_label") ?></th>
                        <th><?php echo lang("cash_bank_expense:pay_label") ?></th>
                        <th><?php echo lang("cash_bank_expense:balance_label") ?></th>
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
        <a href="<?php echo $lookup_vouchers ?>" data-toggle='lookup-ajax-modal' class="btn btn-primary btn-xl col-md-12"><b><i class="fa fa-search"></i> <?php echo lang("cash_bank_expense:find_voucher_list_label")?></b></a>
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
									
									lookup_ajax_modal.show("<?php echo @$form_voucher_detail_url ?>/?No_Voucher="+ encodeURIComponent(data.No_Voucher) )
								} catch(ex){}
							break;
						}
					},
				get_voucher_detail: function( row, data, index ){
					
						$.get("<?php echo $get_voucher_detail_url ?>", {'No_Voucher' : data.No_Voucher }, function( response, status, xhr ){

							var response = $.parseJSON(response);
	
							if( response.status == "error"){
								$.alert_error(response.message);
								return false
							}
							
							var collection = [];
							$.each( response.collection, function( index, value ){
								value.Debit = value.Debit || value.Sisa;
								
								collection[ index ] = value;
							});
							
							data.Sisa = mask_number.currency_remove( data.Sisa );
							data.Debit = mask_number.currency_remove( data.Debit );
							data.Saldo = mask_number.currency_remove( data.Saldo );
							
							// Store voucher item and detail (collection) to WebStroge
							webStroge.sessionSetItem( data.No_Voucher + "_Index", index );
							webStroge.sessionSetItem( data.No_Voucher + "_Header", data );
							webStroge.sessionSetItem( data.No_Voucher + "_Detail", collection );
							
						});				
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw();
														
					},
				calculate_balance: function(){
				
					var _total_pay = $("#pay_total"),
						_tot_debit = _tot_balance = 0;
											
					try {
						
						var table_data = $("#dt_vouchers").DataTable().rows().data();
	
						table_data.each(function (value, key) {
													
							_tot_debit = _tot_debit + mask_number.currency_remove( value.Debit );
							
						});		
						
						_total_pay.html( mask_number.currency_add( _tot_debit ) );
												
					} catch(ex){console.log(ex)}
										
				},
				
			};
		
		$.fn.extend({
				dt_vouchers: function(){
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
											data: "No_Voucher", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "Nama_Supplier", },
										{ data: "Tgl_Voucher", className: "text-center",  },
										{ data: "Sisa", className: "text-right" },
										{ data: "Debit", className: "text-right",  },
										{ data: "Saldo", className: "text-right",  },										
									],
								columnDefs  : [
										{
											"targets": ["Kredit", "JenisHutang_ID", "Akun_ID"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
									_datatable_actions.calculate_balance();
								},
								createdRow: function ( row, data, index ){
									
										_datatable_actions.get_voucher_detail( row, data, index )
										
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
							
						$( "#dt_vouchers_length select, #dt_vouchers_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_vouchers" ).dt_vouchers();
				
				var timer = 0;
				$("form[name=\"form_cash_bank_expense\"]").on("submit", function(e){
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
							"voucher" : {},
							"factur" : {},
						};
					
					data_post.cashier = {
						"AkunBG_ID" : $("#Akun_ID").val(),
						"Type_Transaksi" : $("#Type_Transaksi").val(),
						"Keterangan" : $("#Keterangan").val(),
						"Tgl_Transaksi"  : $("#Tgl_Transaksi").val(),
						"Tgl_Update" : $("#Tgl_Transaksi").val(),
						"Debet" : mask_number.currency_add( $("#pay_total").html() )
					};
					
					var voucher_data = $( "#dt_vouchers" ).DataTable().rows().data();
					
					voucher_data.each(function(value, index){
						// Get Voucher from WebStroge
						data_post.voucher[ index ] = webStroge.sessionGetItem( value.No_Voucher + "_Header" ); 
						// Get Voucher detail (Factur) from WebStroge
						data_post.factur[ value.No_Voucher ] = {}
						$.each(webStroge.sessionGetItem( value.No_Voucher + "_Detail" ), function(_index, _value){
							data_post.factur[ value.No_Voucher ][ _index ] = {
									"No_Voucher" : _value.No_Voucher,
									"No_Faktur" : _value.No_Faktur,
									"Sisa" : _value.Sisa,
									"Debit" : _value.Debit,
									"Akun_ID" : _value.Akun_ID,
									"Keterangan" : _value.Keterangan,
								}
						}); 
					});
					
					$.post($("form[name=\"form_cash_bank_expense\"]").attr("action"), data_post, function( response, status, xhr ){

						var response = $.parseJSON(response);

						if( response.status == "error"){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success( response.message );
						
						var No_Bukti = response.No_Bukti;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("general-cashier/cash-bank-expense/vouchers/edit"); ?>/?No_Bukti="+ No_Bukti ;
							
							}, 300 );
						
					});				
				}
								
			});
	})( jQuery );
//]]>
</script>

 