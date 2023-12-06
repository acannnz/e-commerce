<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_voucher_details" class="table table-bordered table-sm" width="100%">
                <thead>
                	<tr>
                    	<th></th>
                        <th><?php echo lang("vouchers:factur_number_label") ?></th>
                        <th><?php echo lang("vouchers:date_label") ?></th>
                        <th><?php echo lang("vouchers:value_label") ?></th>
                        <th><?php echo lang("vouchers:description_label") ?></th>                 
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
    	<a  href="<?php echo @$lookup_facturs ?>" title="<?php echo lang( "buttons:add" ) ?>" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> <?php echo lang( "buttons:add" ) ?></a>
    </div>
</div>
<hr>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {		
				edit: function( row, data, index ){
						
						switch( this.index() ){
							case 0:
								
								try{
									if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
								} catch(ex){}
								
							break;
							
							case 1:
								try{
									trId = _datatable.row( row ).index();
									
									lookup_ajax_modal.show("<?php echo @$lookup_facturs ?>/"+ trId)
								} catch(ex){}
							break;
							case 2:
								try{
									trId = _datatable.row( row ).index();
									
									lookup_ajax_modal.show("<?php echo @$lookup_facturs ?>/"+ trId)
								} catch(ex){}
							break;
							case 3:
								try{
									trId = _datatable.row( row ).index();
									
									lookup_ajax_modal.show("<?php echo @$lookup_facturs ?>/"+ trId)
								} catch(ex){}
							break;
							case 4:
								try{
									trId = _datatable.row( row ).index();
									
									lookup_ajax_modal.show("<?php echo @$lookup_facturs ?>/"+ trId)
								} catch(ex){}
							break;

						}
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);
								
						_datatable_actions.calculate_balance();
						
					},
				calculate_balance: function(params, fn, scope){
						
						var _form = $( "form[name=\"form_payable\"]" );
						var _text_balance = _form.find( "h3[id=\"voucher_value\"]" );
						var _input_balance = _form.find( "input[id=\"Nilai\"]" );
						var _form_submit = _form.find( "button[id=\"btn-submit\"]" );
						
						var tol_balance = 0;
						
						
						var table_data = _datatable.rows().data();
					
						table_data.each(function (value, index) {
							tol_balance = tol_balance + Number(value.Kredit);
						});

						_text_balance.html("Rp. "+ tol_balance.toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
						_input_balance.val( tol_balance );
						
						if (tol_balance > 0)
						{
							_text_balance.removeClass("text-danger");
							_form_submit.removeAttr("disabled");
						} else {
							_text_balance.addClass("text-danger");
							_form_submit.attr("disabled");
						}			
						
					},
					
					
			};
		
		$.fn.extend({
				dt_voucher_details: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: false,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								autoWidth: false,
								responsive: true,
								columns: [
										{ 
											data: "No_Bukti", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "No_Bukti", className: "text-center",  },
										{ data: "Tgl_transaksi", className: "text-center", },
										{ data: "Kredit", className: "text-right",  },
										{ data: "Keterangan", className: "text-left" },
									],
								columnDefs  : [
										{
											"targets": ["Debit","JenisHutang_ID"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								rowCallback: function( settings ) {
									_datatable_actions.calculate_balance();
								},
								createdRow: function ( row, data, index ){
										_datatable_actions.calculate_balance();

										$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
											
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
											})
									}
							} );
							
						$( "#dt_voucher_details_length select, #dt_voucher_details_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_voucher_details" ).dt_voucher_details();
				
				$("form[name=\"form_payable\"]").on("submit", function(e){
					e.preventDefault();
					var voucher_data = {
							"Supplier_ID" : $("#Supplier_ID").val(),
							"Nilai" : $("#Nilai").val(),
							"Sisa" : $("#Nilai").val(),
							"Keterangan" : $("#Keterangan").val(),
							"Tgl_Voucher"  : $("#Tgl_Voucher").val(),
							"Tgl_Tempo"  : $("#Tgl_Tempo").val(),
							"Tgl_Update" : $("#Tgl_Voucher").val(),
						};
					
					var data_post = {
							"header" : voucher_data,
							"detail" : {}
					};
					
					var table_data = $( "#dt_voucher_details" ).DataTable().rows().data();
					table_data.each(function (value, index) {
						var detail = {
							"No_Bukti" : value.No_Bukti,
							"Keterangan" : value.Keterangan,
							"Debit"	: value.Debit,
							"Kredit" : value.Kredit,
							"Tgl_transaksi" : value.Tgl_transaksi,
							"JenisHutang_ID" : value.JenisHutang_ID
						}
						
						data_post.detail[index] = detail;
					});
					
				
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){

						var response = $.parseJSON( response );

						if( response.status == "error"){
							$.alert_error( response.message );
							return false
						}
						
						$.alert_success( response.message );
						
						var No_Voucher = response.No_Voucher;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("payable/vouchers/edit"); ?>?No_Voucher="+ No_Voucher ;
							
							}, 300);
						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>

 