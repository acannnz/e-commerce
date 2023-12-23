<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_factur_details" class="table table-bordered table-sm" width="100%">
                <thead>
                	<tr>
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

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {		
				edit: function( row, data, index ){
						
						switch( this.index() ){		
							
							case 0:
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
						}
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);
								
						_datatable_actions.calculate_balance();
						
					},
				update_payable_type: function( _selected_data ){
						indexRow = _datatable_actions.get_payable_type_row();
						row_data = _datatable.row( indexRow ).data();
						
						row_data.Akun_ID = _selected_data.accountid;
						row_data.Akun_No = _selected_data.accountno;
						row_data.Akun_Name = _selected_data.accountname;
						
						_datatable.row( indexRow ).data( row_data );
						
					},
				get_payable_type_row: function(){
						table_data = _datatable.rows().data();
						
						indexRow = 0;
						table_data.each(function (value, index) {
							if ( value.Pos == "K" ) indexRow = index;
						});
						
						return indexRow;
					},
				calculate_balance: function(params, fn, scope){
						
						var _form = $( "form[name=\"form_payable\"]" );
						var _text_balance = _form.find( "h3[id=\"factur_value\"]" );
						var _input_balance = _form.find( "input[id=\"Nilai_Faktur\"]" );
						var _form_submit = _form.find( "button[id=\"btn-submit\"]" );
						
						var tol_balance = 0;
						
						
						var table_data = _datatable.rows().data();
					
						table_data.each(function (value, index) {
							if (value.Pos == "D")
							{
								tol_balance = tol_balance + Number(value.Harga_Transaksi);
							}
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
								<?php if (!empty($collection)):?>
								data: <?php print_r(json_encode($collection, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ data: "Keterangan", className: "text-left" },
										{ data: "Qty", className: "text-left",  },
										{ data: "Harga_Transaksi", className: "text-left", },
										{ data: "Akun_No", className: "text-left",  },
										{ data: "Akun_Name", className: "text-left",  },
										{ data: "SectionName", className: "text-left",  },										
									],
								columnDefs  : [
										{
											"targets": ["Akun_ID", "Pos", "SectionID"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
									<?php if ( @$item->TutupBuku == 0 || @$item->Posted == 0): ?>
										$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
									<?php endif; ?>
								}
							} );
							
						$( "#dt_factur_details_length select, #dt_factur_details_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_factur_details" ).dt_factur_details();
				
				$( "#JenisHutang_ID" ).on("change", function(){
					_selected_data = $( this ).find( "option:selected" ).data();
					_datatable_actions.update_payable_type( _selected_data );
				});
				
				$("form[name=\"form_payable\"]").on("submit", function(e){
					e.preventDefault();
					var d = new Date();

					var month = d.getMonth()+1;
					var day = d.getDate();
					
					var today = d.getFullYear() + '/' +
						(month<10 ? '0' : '') + month + '/' +
						(day<10 ? '0' : '') + day;
						
					var factur_data = {
								"No_Faktur" : $("#No_Faktur").val(),
								"Supplier_ID" : $("#Supplier_ID").val(),
								"Nilai_Faktur" : $("#Nilai_Faktur").val(),
								"Sisa" : $("#Nilai_Faktur").val(),
								"Keterangan" : $("#Keterangan").val(),
								"JenisHutang_ID" : $("#JenisHutang_ID").val(),
								"Tgl_Faktur"  : $("#Tgl_Faktur").val(),
								"Tgl_JatuhTempo"  : $("#Tgl_JatuhTempo").val(),
								"Tgl_Update" : today,
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
							"Pos" : value.Pos,
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
													
							document.location.href = "<?php echo base_url("payable/factur/edit"); ?>?No_Faktur="+ No_Faktur ;
							
							}, 300 );
						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>

 