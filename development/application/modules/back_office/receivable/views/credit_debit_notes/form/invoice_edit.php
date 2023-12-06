<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_invoices" class="table table-bordered" width="100%">
                <thead>
                	<tr>
                        <th><?php echo lang("credit_debit_notes:invoice_number_label") ?></th>
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
									
									lookup_ajax_modal.show("<?php echo @$form_invoice_detail_url ?>/TRUE?No_Invoice="+ encodeURIComponent(data.No_Invoice) )
								} catch(ex){}
							break;
						}
					},
				get_invoice_detail: function( row, data, index ){
					
						$.get("<?php echo $get_invoice_detail_url ?>", {'No_Invoice' : '<?php echo $item->No_Invoice ?>', 'No_Bukti' : data.No_Invoice }, function( response, status, xhr ){

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
										{ data: "No_Invoice", className: "text-center", },
										{ data: "Tgl_Invoice", className: "text-center",  },
										{ data: "Nilai", className: "text-right" },
										{ data: "Debit", className: "text-right",  },
										{ data: "Kredit", className: "text-right", },
										{ data: "Saldo", className: "text-right",  },
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
											"targets": ["JenisPiutang_ID"],
											"visible": false,
											"searchable": false
										}
									],
								"drawCallback": function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
									
										_datatable_actions.get_invoice_detail( row, data, index )
										
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
							
						$( "#dt_invoices_length select, #dt_invoices_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_invoices" ).dt_invoices();
				
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
							"invoice" : {},
							"factur" : {},
						};
					
					data_post.nota = {
						"Customer_ID" : $("#Customer_ID").val(),
						"Nilai" : $("#Nilai").val(),
						"Sisa" : $("#Nilai").val(),
						"Akun_ID" : $("#Akun_ID").val(),
						"Keterangan" : $("#Keterangan").val(),
						"Tgl_Invoice"  : $("#Tgl_Invoice").val(),
						"Tgl_Tempo"  : $("#Tgl_Tempo").val(),
						"Tgl_Update" : $("#Tgl_Invoice").val(),
					};
					
					var invoice_data = $( "#dt_invoices" ).DataTable().rows().data();
					
					invoice_data.each(function(value, index){
						// Get Invoice from WebStroge
						data_post.invoice[ index ] = webStroge.sessionGetItem( value.No_Invoice + "_Header" ); 
						// Get Invoice detail (Factur) from WebStroge
						// (No_Invoice,No_Bukti,Tgl_transaksi,JTransaksi_ID,NilaiAsal,Debit,Kredit,Keterangan,sectionID)
						data_post.factur[ value.No_Invoice ] = {}
						$.each(webStroge.sessionGetItem( value.No_Invoice + "_Detail" ), function(_index, _value){
							data_post.factur[ value.No_Invoice ][ _index ] = {
									"No_Invoice" : _value.No_Invoice,
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
						
						var No_Invoice = response.No_Invoice;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("receivable/debit_credit_note/edit"); ?>/?No_Invoice="+ No_Invoice ;
							
							}, 2000 );
						
					});				
				}
								
			});
	})( jQuery );
//]]>
</script>

 