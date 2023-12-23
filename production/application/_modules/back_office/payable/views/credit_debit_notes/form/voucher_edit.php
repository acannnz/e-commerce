<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_vouchers" class="table table-bordered" width="100%">
                <thead>
                	<tr>
                        <th><?php echo lang("credit_debit_notes:voucher_number_label") ?></th>
                        <th><?php echo lang("credit_debit_notes:date_label") ?></th>
                        <th><?php echo lang("credit_debit_notes:original_value_label") ?></th>
                        <th><?php echo lang("credit_debit_notes:increase_label") ?></th>
                        <th><?php echo lang("credit_debit_notes:decrease_label") ?></th>
                        <th><?php echo lang("credit_debit_notes:balance_label") ?></th>                 
                        <th><?php echo lang("credit_debit_notes:description_label") ?></th>                 
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {		
				edit: function( row, data, index ){
						
						switch( this.index() ){
							
							default :
								try{
									trId = _datatable.row( row ).index();
									
									lookup_ajax_modal.show("<?php echo @$form_voucher_detail_url ?>/TRUE?No_Voucher="+ encodeURIComponent(data.No_Voucher) )
								} catch(ex){}
							break;
						}
					},
				get_voucher_detail: function( row, data, index ){
					
						$.get("<?php echo $get_voucher_detail_url ?>", {'No_Voucher' : '<?php echo $item->No_Voucher ?>', 'No_Bukti' : data.No_Voucher }, function( response, status, xhr ){

							var response = $.parseJSON(response);
	
							if( response.status == "error"){
								$.alert_error(response.message);
								return false
							}
							
							var collection = [];
							$.each( response.collection, function( index, value ){								
								collection[ index ] = value;
							});
							
							data.Nilai = mask_number.currency_remove( data.Nilai );
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
										{ data: "No_Voucher", className: "text-center", },
										{ data: "Tgl_Voucher", className: "text-center",  },
										{ 
											data: "Nilai", className: "text-right" ,
											render: function( val ){
												return mask_number.currency_add( val );
											}
										},
										{ 
											data: "Kredit", className: "text-right", 
											render: function( val ){
												return mask_number.currency_add( val );
											}
										},
										{ 
											data: "Debit", className: "text-right",  
											render: function( val ){
												return mask_number.currency_add( val );
											}
										},
										{ 
											data: "Saldo", className: "text-right",  
											render: function( val ){
												return mask_number.currency_add( val );
											}
										},
										{ 
											data: "Keterangan", 
											className: "text-left",  
											render: function( val){
												return val.substr(0, 30);
											}
										},
										
									],
								columnDefs  : [
										{
											"targets": ["JenisHutang_ID"],
											"visible": false,
											"searchable": false
										}
									],
								"drawCallback": function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
									
										_datatable_actions.get_voucher_detail( row, data, index )
										
										$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
											
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, null, row )
												}
											})
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
				$("form[name=\"form_debit_credit_note\"]").on("submit", function(e){
					e.preventDefault();
					// untuk 
					if (timer) {
						clearTimeout(timer);
					}
					
					timer = setTimeout(postCreditDebitNotes, 500); 
						
				});
				
				function postCreditDebitNotes(){
					
					var data_post = {
							"nota" : {},
							"voucher" : {},
							"factur" : {},
						};
					
					data_post.nota = {
						"Supplier_ID" : $("#Supplier_ID").val(),
						"Nilai" : $("#Nilai").val(),
						"Sisa" : $("#Nilai").val(),
						"Akun_ID" : $("#Akun_ID").val(),
						"Keterangan" : $("#Keterangan").val(),
						"Tgl_Voucher"  : $("#Tgl_Voucher").val(),
						"Tgl_Tempo"  : $("#Tgl_Tempo").val(),
						"Tgl_Update" : $("#Tgl_Voucher").val(),
					};
					
					var voucher_data = $( "#dt_vouchers" ).DataTable().rows().data();
					
					voucher_data.each(function(value, index){
						// Get Voucher from WebStroge
						data_post.voucher[ index ] = webStroge.sessionGetItem( value.No_Voucher + "_Header" ); 
						// Get Voucher detail (Factur) from WebStroge
						// (No_Voucher,No_Bukti,Tgl_transaksi,JTransaksi_ID,NilaiAsal,Debit,Kredit,Keterangan,sectionID)
						data_post.factur[ value.No_Voucher ] = {}
						$.each(webStroge.sessionGetItem( value.No_Voucher + "_Detail" ), function(_index, _value){
							data_post.factur[ value.No_Voucher ][ _index ] = {
									"No_Voucher" : _value.No_Voucher,
									"No_Faktur" : _value.No_Faktur,
									"Sisa" : _value.Sisa,
									"Debit" : _value.Debit,
									"Kredit" : _value.Kredit,
								}
						}); 
					});
					
					$.post($("form[name=\"form_debit_credit_note\"]").attr("action"), data_post, function( response, status, xhr ){

						var response = $.parseJSON(response);

						if( response.status == "error"){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success( response.message );
						
						var No_Voucher = response.No_Voucher;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("payable/debit_credit_note/edit"); ?>/?No_Voucher="+ No_Voucher ;
							
							}, 300 );
						
					});				
				}
								
			});
	})( jQuery );
//]]>
</script>

 