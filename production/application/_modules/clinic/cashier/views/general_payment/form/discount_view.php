<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row form-group">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_discounts" class="table table-sm table-bordered" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Diskon</th>
                        <th>Nama Diskon</th>                        
                        <th>ID Dokter</th>                        
                        <th>Nama Dokter</th>                        
                        <!--<th>ID Jasa</th>                        
                        <th>Nama Jasa</th>-->
                        <th>Kelas</th>                        
                        <th>Persen (%)</th>
                        <th>Nilai Diskon</th>
                        <th>Keterangan</th>                        
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
				calculate_discount: function( params, fn, scope ){
						var _form = $( "form[name=\"form_general_payment\"]" );
						var Nilai = _form.find( "input[id=\"Nilai\"]" ); //Nilai Total Pembayaran yg harusnya dibayar
						var SubTotal = _form.find( "input[id=\"SubTotal\"]" );
						var GrandTotal = _form.find( "input[id=\"GrandTotal\"]" );
						var Pembayaran = _form.find( "input[id=\"Pembayaran\"]" );
						var Tunai = _form.find( "input[id=\"Tunai\"]" );
						var NilaiDiskon = _form.find( "input[id=\"NilaiDiskon\"]" );
						var DiscountTotal = 0;
						var SubTotal_ = 0;
						
						try {
							
							//var DiscountData = _datatable.rows().data();
							var DiscountData = $( "#dt_discounts" ).DataTable().rows().data();
							DiscountData.each(function (value, index) {
								DiscountTotal = DiscountTotal +  parseFloat( value.NilaiDiskon.replace(/[^0-9\.-]+/g,"") );
							});
							
							console.log("NilaiDiskon: ", DiscountTotal);
							NilaiDiskon.val( parseFloat( DiscountTotal ).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
							SubTotal_ = parseFloat( Nilai.val().replace(/[^0-9\.-]+/g,"") ).toFixed( 2 ) - parseFloat( DiscountTotal ).toFixed( 2 );
							
							console.log("SubTotal: ", SubTotal_);
							
							SubTotal.val( parseFloat( SubTotal_ ).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
							GrandTotal.val( parseFloat( SubTotal_ ).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
							Pembayaran.val( parseFloat( SubTotal_ ).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
							//Tunai.val( parseFloat( SubTotal_ ).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
							
							_datatable_actions.calculate_payment();
							
						} catch (e){console.log(e)}
					},
					
			};
		
		$.fn.extend({
				dt_discounts: function(){
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
											data: "IDDiscount", 
											className: "actions text-center",  
										},
										{ 
											data: "IDDiscount", 
											className: "", 
										},
										{ data: "NamaDiscount", className: "" },
										{ data: "IDDokter", className: "" },
										{ data: "NamaDokter", className: "" },
										//{ data: "IDJasa", className: "" },
										//{ data: "NamaJasa", className: "" },
										{ data: "Kelas", className: "text-center" },
										{ data: "Persen", className: "text-center" },
										{ data: "NilaiDiskon", className: "text-center"},
										{ data: "Keterangan" },
									],
								columnDefs  : [
										{
											"targets": ["KomponenID","Harga","Tarif"],
											"visible": false,
											"searchable": false
										}
									],
								fnRowCallback : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
										var index = iDisplayIndexFull + 1;
										$('td:eq(0)', nRow).html(index);
										return nRow;					
									},
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
									_datatable_actions.calculate_discount();	
								},
							});
							
						$( "#dt_discounts_length select, #dt_discounts_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		
		$( document ).ready(function(e) {
            	$( "#dt_discounts" ).dt_discounts();

			});
	})( jQuery );
//]]>
</script>