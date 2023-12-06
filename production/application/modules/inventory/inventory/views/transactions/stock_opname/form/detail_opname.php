<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12">
        <div class="form-group">
			<div class="table-responsive">
				<table id="dt_detail_opname" class="table table-sm table-bordered" width="100%">
					<thead>
						<tr>
							<th></th>
							<th><?php echo lang("label:item_code")?></th>                        
							<th><?php echo lang("label:item_name")?></th>                        
							<th><?php echo lang("label:item_unit")?></th>                        
							<th><?php echo lang("label:conversion")?></th>                        
							<th><?php echo lang("label:qty_system")?></th>                        
							<th><?php echo lang("label:qty_physical")?></th>                        
							<th><?php echo lang("label:gap")?></th>                        
							<th><?php echo lang("label:item_price")?></th>                        
							<th><?php echo lang("label:item_category")?></th>                        
							<th><?php echo lang("label:exp_date")?></th>                        
							<th><?php echo lang("label:description")?></th>                        
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php if (@$item->Posted == 0): ?>
<div class="row">
	<div class="col-md-12">
        <div class="form-group">
        	<a href="javascript:;" data-action-url="<?php echo @$item_lookup ?>" data-act="ajax-modal" data-title="<?php echo lang('heading:item_list')?>" class="btn btn-primary btn-block"><b><i class="fa fa-search"></i> Tampilkan Data Barang</b></a>
        </div>
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
							case 6:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ parseFloat(data.Qty_Opname || 0) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{

											data.Qty_Opname = parseFloat(this.value || 0);
											data.Selisih = Math.abs( parseFloat(data.Stock_Akhir) - parseFloat(data.Qty_Opname) );
											_datatable.row( row ).data( data ).draw();

										} catch(ex){console.log(ex)}
									});
							break;						

							case 10:
								data.Tgl_Expired = data.Tgl_Expired || '';
								var _input = $( "<input type=\"text\" style=\"width:100%\" value=\""+ data.Tgl_Expired  +"\" class=\"form-control datepicker\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{

											data.Tgl_Expired = this.value || "";
											_datatable.row( row ).data( data ).draw();

										} catch(ex){console.log(ex)}
									});
							break;	
							
							case 11:
								var _input = $( "<input type=\"text\" style=\"width:100%\" value=\""+ data.Keterangan +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{

											data.Keterangan = this.value || "";
											_datatable.row( row ).data( data ).draw();

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
				dt_detail_opname: function(){
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
										{ data: "Konversi", className: "text-right" },
										{ data: "Stock_Akhir", className: "text-right" },
										{ data: "Qty_Opname", className: "text-right" },
										{ 
											data: "Selisih", 
											className: "text-right", 
											render: function(val, type, row){
												return Math.abs( parseFloat(row.Stock_Akhir) - parseFloat(row.Qty_Opname) );
											}
										},
										{ 
											data: "Harga_Rata", className: "text-right",
											render: function( val ){
												return mask_number.currency_add( val );
											} 
										},
										{ data: "Kategori", className: "" },
										{ 
											data: "Tgl_Expired", className: "text-center",
											render: function( val ) {
												return (val) ? val.substr(0, 11) : ''
											}
										},
										{ data: "Keterangan", className: "" },
									],
								columnDefs  : [
										{
											"targets": ["Barang_ID","JenisBarangID"],
											"visible": false,
											"searchable": false
										}
									],
								<?php if( @$item->Posted == 1): ?>
								fnRowCallback : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
										var index = iDisplayIndexFull + 1;
										$('td:eq(0)',nRow).html(index);
										return nRow;					
									},
								<?php endif; ?>
								<?php if( @$item->Posted == 0): ?>
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
									},
								<?php endif; ?>
							} );
							
						$( "#dt_detail_opname_length select, #dt_detail_opname_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		
		$( document ).ready(function(e) {
			$( "#dt_detail_opname" ).dt_detail_opname();
			
			// datepicker reiinte for dinamic
			$('body').on('focus',".datepicker", function(){
				$(this).datepicker({
						format: "YYYY-MM-DD", 
						widgetPositioning: {
							horizontal: 'auto', // horizontal: 'auto', 'left', 'right'
							vertical: 'auto' // vertical: 'auto', 'top', 'bottom'
						},
					});
			});
			
		});

	})( jQuery );
//]]>
</script>