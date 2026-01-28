<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_accounts" class="table table-bordered" width="100%">
                <thead>
                	<tr>
                    	<th></th>
                        <th><?php echo lang("cash_bank_income:account_number_label") ?></th>
                        <th><?php echo lang("cash_bank_income:account_name_label") ?></th>
                        <th><?php echo lang("cash_bank_income:section_label") ?></th>
                        <th><?php echo lang("cash_bank_income:description_label") ?></th>
                        <th><?php echo lang("cash_bank_income:value_label") ?></th>
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
        <a href="<?php echo $lookup_accounts_credit ?>" data-toggle='lookup-ajax-modal' class="btn btn-primary btn-xl col-md-12"><b><i class="fa fa-search"></i> <?php echo lang("cash_bank_income:find_account_list_label")?></b></a>
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
							
							case 1:
							case 2:
								lookup_ajax_modal.show("<?php echo $lookup_accounts_credit ?>/"+ index);
							break;
														
							case 3:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _value = data.SectionID ? data.SectionID : ''
								_input.load( "<?php echo base_url("common/sections/dropdown") ?>/" + _value, function( response, status, xhr ){
										_input.trigger( "focus" )
									} );
								
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{
											var _selected = $( e.target ).find( "option:selected" ).data() || {};
											data.SectionID = _selected.id || 0;
											data.SectionName = _selected.title || '';
											
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});
									
								_input.on( "blur", function( e ){
										try{
											_datatable.row( row ).data( data );
										} catch(ex){}
									});
								
								
							break;
							
							case 4:
								var _input = $( "<input type=\"text\" value=\"" + (data.Keterangan || "") + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Keterangan = this.value || "";
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;
							
							case 5:
								var _input = $( "<input type=\"text\" value=\"" + mask_number.currency_remove(data.Kredit || "0.00") + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Kredit = mask_number.currency_remove( this.value );
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;
						}
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
						
						var table_data = $("#dt_accounts").DataTable().rows().data();
	
						table_data.each(function (value, key) {
													
							_tot_credit = _tot_credit + mask_number.currency_remove( value.Kredit );
							
						});		
						
						_total_pay.html( mask_number.currency_add( _tot_credit ) );
												
					} catch(ex){console.log(ex)}
										
				},
				
			};
		
		$.fn.extend({
				dt_accounts: function(){
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
											data: "Akun_ID", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "Akun_No", },
										{ data: "Akun_Name", className: "",  },
										{ data: "SectionName", className: "",  },
										{ data: "Keterangan", className: "",  },
										{ 
											data: "Kredit", 
											className: "text-right",  
											render: function( val ){
												<?php if (@$is_edit): ?>
													return mask_number.currency_add( val );
												<?php endif ?>
													return val;
											}
										},										
									],
								columnDefs  : [
										{
											"targets": ["SectionID", "Akun_ID", "Kredit"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
									_datatable_actions.calculate_balance();
								},
								createdRow: function ( row, data, index ){
									
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
							
						$( "#dt_accounts_length select, #dt_accounts_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_accounts" ).dt_accounts();
				
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
							"cashier" : {},
							"detail" : {},
						};
					
					data_post.cashier = {
						"AkunBG_ID" : $("#Akun_ID").val(),
						"Type_Transaksi" : $("#Type_Transaksi").val(),
						"Keterangan" : $("#Keterangan").val(),
						"Tgl_Transaksi"  : $("#Tgl_Transaksi").val(),
						"Tgl_Update" : $("#Tgl_Transaksi").val(),
						"Instansi" : $("#Instansi").val(),
					};
					
					var detail_data = $( "#dt_accounts" ).DataTable().rows().data();
					
					detail_data.each(function(value, index){
						value.Kredit = mask_number.currency_remove(value.Kredit);
						data_post.detail[ index ] = value;
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
													
							document.location.href = "<?php echo base_url("general-cashier/cash-bank-income/non-invoices/edit"); ?>/?No_Bukti="+ No_Bukti ;
							
							}, 300 );
						
					});				
				}
								
			});
	})( jQuery );
//]]>
</script>

 