<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row form-group">
    <div class="table-responsive">
        <table id="dt_mutation_details" class="table table-sm table-bordered" width="100%">
            <thead>
                <tr>
                    <th><?php echo lang('label:item_code')?></th>                        
                    <th><?php echo lang('label:item_name')?></th>                        
                    <th><?php echo lang('label:item_unit')?></th>                        
                    <th><?php echo lang('label:conversion')?></th>                        
                    <th><?php echo lang('label:qty_stock')?></th>                        
                    <th><?php echo lang('label:qty_amprah')?></th>                        
                    <th><?php echo lang('label:qty_mutation')?></th>                        
                    <th><?php echo lang('label:item_price')?></th>                        
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
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
							case 6:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ parseFloat(data.Qty || 1) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{

											data.Qty = parseFloat(this.value || 1);
											_datatable.row( row ).data( data ).draw(true);

										} catch(ex){console.log(ex)}
									});
							break;						
						}
					},
			};
		
		$.fn.extend({
				dt_mutation_details: function(){
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
										{ data: "Kode_Barang", className: "text-center", },
										{ data: "Nama_Barang", },
										{ data: "Kode_Satuan", className: "" },
										{ data: "Konversi", className: "text-right" },
										{ data: "Qty_Stok", className: "text-right" },
										{ data: "QtyAmprah", className: "text-right" },
										{ data: "Qty", className: "text-right" },
										{ 
											data: "Harga", 
											className: "text-right",
											render: function( val ){
												return mask_number.currency_add( val );
											}
										},
									],
								columnDefs  : [
										{
											"targets": ["HRataRata","MutasiAkun_ID"],
											"visible": false,
											"searchable": false
										}
									],
								<?php if( ! @$is_edit ): ?>
								createdRow: function ( row, data, index ){
										$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
									},
								<?php endif; ?>
							} );
							
						$( "#dt_mutation_details_length select, #dt_mutation_details_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		
		$( document ).ready(function(e) {
			$( "#dt_mutation_details" ).dt_mutation_details();
			
		});

	})( jQuery );
//]]>
</script>