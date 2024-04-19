<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_factur_details" class="table table-bordered table-sm" width="100%">
                <thead>
                	<tr>
                    	<th></th>
                        <th><?php echo lang("facturs:description_label") ?></th>
                        <th><?php echo lang("facturs:qty_label") ?></th>
                        <th><?php echo lang("facturs:value_label") ?></th>
                        <th><?php echo lang("facturs:account_number_label") ?></th>
                        <th><?php echo lang("facturs:account_name_label") ?></th>                 
                        <th><?php echo lang("facturs:section_label") ?></th>                 
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-12">
    	<a  href="<?php echo @$lookup_accounts ?>" title="<?php echo lang( "buttons:add" ) ?>" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> <?php echo lang( "buttons:add" ) ?></a>
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
							case 0:
								
								try{
									if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
								} catch(ex){}
								
							break;
							
							
							case 1:
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

							case 2:
								var _input = $( "<input type=\"number\" value=\"" + (data.Qty || 1 ) + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Qty = this.value || 1;
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;
							
							case 3:
								var _input = $( "<input type=\"text\" value=\"" + (data.Harga_Transaksi || 0.00 ) + "\" style=\"width:100%\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Harga_Transaksi = this.value || 0.00;
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;
							
							case 4:
								try{
									trId = _datatable.row( row ).index();
									
									lookup_ajax_modal.show("<?php echo @$lookup_accounts ?>/"+ trId)
								} catch(ex){}
							break;

							case 5:
								try{
									trId = _datatable.row( row ).index();
									
									lookup_ajax_modal.show("<?php echo @$lookup_accounts ?>/"+ trId)
								} catch(ex){}
							break;
							
							case 6:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Initializing...</option>\n</select>" );
								this.empty().append( _input );
								
								var _value = data.SectionID ? data.SectionID : ''
								_input.load( "<?php echo base_url("common/sections/dropdown") ?>/" + _value, function( response, status, xhr ){
										_input.trigger( "focus" )
									} );
								
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											$( e.target ).remove();
											_datatable.row( row ).data( data );
										} catch(ex){}
									});
								
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{
											var _selected = $( e.target ).find( "option:selected" ).data() || {};
											data.SectionID = _selected.id || 0;
											data.SectionName = _selected.title || '';
											
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});
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
						
						var _form = $( "form[name=\"form_receivable\"]" );
						var _text_balance = _form.find( "h3[id=\"factur_value\"]" );
						var _input_balance = _form.find( "input[id=\"Nilai_Faktur\"]" );
						var _form_submit = _form.find( "button[id=\"btn-submit\"]" );
						
						var tol_balance = 0;
						
						
						var table_data = _datatable.rows().data();
					
						table_data.each(function (value, index) {
							tol_balance = tol_balance + Number(value.Harga_Transaksi);
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
				dt_factur_details: function(){
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
											data: "No_Faktur", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "Keterangan", className: "text-left" },
										{ data: "Qty", className: "text-left",  },
										{ data: "Harga_Transaksi", className: "text-left", },
										{ data: "Akun_No", className: "text-left",  },
										{ data: "Akun_Name", className: "text-left",  },
										{ data: "SectionName", className: "text-left",  },										
									],
								columnDefs  : [
										{
											"targets": ["Akun_ID","SectionID"],
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
							
						$( "#dt_factur_details_length select, #dt_factur_details_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_factur_details" ).dt_factur_details();
				
				$("form[name=\"form_receivable\"]").on("submit", function(e){
					e.preventDefault();
					var factur_data = {
							"Customer_ID" : $("#Customer_ID").val(),
							"CustomerID_Transaksi" : $("#Customer_ID").val(),
							"Nilai_Faktur" : $("#Nilai_Faktur").val(),
							"Sisa" : $("#Nilai_Faktur").val(),
							"Keterangan" : $("#Keterangan").val(),
							"JenisPiutang_ID" : $("#JenisPiutang_ID").val(),
							"Diagnosa" : $("#Diagnosa").val(),
							"Tgl_Faktur"  : $("#Tgl_Faktur").val(),
							"Tgl_JatuhTempo"  : $("#Tgl_JatuhTempo").val(),
							"Tgl_Update" : $("#Tgl_Faktur").val(),
							"Tgl_Pengakuan" : $("#Tgl_Faktur").val(),
						};
					
					var data_post = {
							"header" : factur_data,
							"detail" : {}
					};
					
					var table_data = $( "#dt_factur_details" ).DataTable().rows().data();
					table_data.each(function (value, index) {
						var detail = {
							"Akun_ID" : value.Akun_ID,
							"Keterangan" : value.Keterangan,
							"Harga_Transaksi"	: value.Harga_Transaksi,
							"Qty" : value.Qty,
							"SectionID" : value.SectionID,
							"Pos" : "K",
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
						
						var No_Faktur = response.No_Faktur;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("receivable/factur/edit"); ?>?No_Faktur="+ No_Faktur ;
							
							}, 3000 );
						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>

 