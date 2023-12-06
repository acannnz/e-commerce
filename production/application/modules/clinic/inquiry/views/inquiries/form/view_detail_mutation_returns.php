<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row form-group">
    <div class="table-responsive">
        <table id="dt_details" class="table table-sm table-bordered" width="100%">
            <thead>
                <tr>
                    <th>Barang ID</th>                        
                    <th>Nama Barang</th>                        
                    <th>Satuan Beli</th>                        
                    <th>Satuan Stok</th>                        
                    <th>Konversi</th>                        
                    <th>Qty Retur</th>                        
                    <th>Harga</th>                        
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
								info: true,
								autoWidth: false,
								responsive: true,
								<?php if (!empty($collection)):?>
								data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ data: "Kode_Barang", className: "text-center", },
										{ data: "Nama_Barang", },
										{ data: "Satuan_Stok", className: "" },
										{ data: "Satuan_Beli", className: "" },
										{ data: "Konversi", className: "text-right" },
										{ data: "Qty", className: "text-right" },
										{ data: "Harga", className: "text-right" },
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