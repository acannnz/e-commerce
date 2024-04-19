<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row form-group">
    <div class="table-responsive">
        <table id="dt_details" class="table table-sm table-bordered" width="100%">
            <thead>
                <tr>
                    <th></th>
                    <th>Barang ID</th>                        
                    <th>Nama Barang</th>                        
                    <th>Sat. Stok</th>                        
                    <th>Sat. Beli</th>                        
                    <th>Konversi</th>                        
                    <th>Qty Stok</th>                        
                    <th>Qty Amprah</th>                        
                    <th>Qty Mutasi</th>                        
                    <th>Harga</th>                        
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="row form-group">
	<a href="<?php echo base_url("inquiry/lookup_product")?>" id="add_product" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Produk</b></a>
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
									if( confirm( "<?php echo lang('inquiry:delete_confirm_message') ?>" ) ){
										_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
									}
								} catch(ex){}
								
							break;						

							case 8:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ Number(data.Qty || 0) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{

											data.Qty = Number(this.value || 0);
											_datatable.row( row ).data( data ).draw(true);

										} catch(ex){console.log(ex)}
									});
							break;						
						}
					},
				remove: function( params, fn, scope ){
							
						_datatable.row( scope )
								.remove()
								.draw(true);
						_datatable_actions.calculate_balance();

					},
				calculate_balance: function(params, fn, scope){
						
						var _form = $( "form[name=\"form_general_ledger\"]" );
						var _form_debit = _form.find( "input[id=\"debit\"]" );
						var _form_credit = _form.find( "input[id=\"credit\"]" );
						var _form_balance = _form.find( "input[id=\"balance\"]" );
						var _form_submit = _form.find( "button[id=\"btn-submit\"]" );
						
						var tol_debit = 0, 
							tol_credit = 0, 
							tol_balance = 0;
						
						var rows = _datatable.rows().nodes();
						
						for( var i=0; i<rows.length; i++ )
						{
							
							tol_debit = tol_debit + Number($(rows[i]).find("td:eq(3)").html());
							tol_credit = tol_credit + Number($(rows[i]).find("td:eq(4)").html());
							
						}
						
						tol_balance = tol_debit - tol_credit;

						_form_debit.val(tol_debit);	
						_form_credit.val(tol_credit);
						_form_balance.val(tol_balance);
						
						if (tol_balance == 0)
						{
							_form_balance.removeClass("text-danger");
							_form_submit.removeAttr("disabled");
						} else {
							_form_balance.addClass("text-danger");
							_form_submit.attr("disabled");
						}			
						
					},
			};
		
		$.fn.extend({
				dt_details: function(){
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
								data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ 
											data: "Barang_ID", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "Kode_Barang", className: "text-center", },
										{ data: "Nama_Barang", },
										{ data: "Kode_Satuan", className: "" },
										{ data: "Satuan_Beli", className: "" },
										{ data: "Konversi", className: "text-right" },
										{ data: "Qty_Stok", className: "text-right" },
										{ data: "QtyAmprah", className: "text-right" },
										{ data: "Qty", className: "text-right" },
										{ data: "Harga", className: "text-right",
											render:function(val)
											{
												return mask_number.currency_add(val)
											}
										},
									],
								columnDefs  : [
										{
											"targets": ["Barang_ID","HRataRata","MutasiAkun_ID"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback : function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
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
												
												if( confirm( "<?php echo lang('inquiry:delete_confirm_message') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
											})
									},
							} );
							
						$( "#dt_details_length select, #dt_details_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		
		$( document ).ready(function(e) {
			$( "#dt_details" ).dt_details();
			
		});

	})( jQuery );
//]]>
</script>