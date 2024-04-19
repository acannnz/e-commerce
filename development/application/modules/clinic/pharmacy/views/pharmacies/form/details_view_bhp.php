<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row form-group">
    <div class="table-responsive">
        <table id="dt_details" class="table table-sm table-bordered" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Obat</th>                        
                    <th>Satuan</th>                                         
                    <th>Tgl ED</th>                        
                    <th>Qty</th>                                             
                    <th>Harga</th>                        
                    <th>Disc%</th>                      
                    <th>Total</th>                       
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
				print: function(params, fn, scope){
					var NoBukti = "<?php echo @$item->NoBuktiPOP ?>";
					// DP = Direct Print
					var data_post = {
						"BarangID": scope.Barang_ID,
						"NoBukti": "<?php echo @$item->NoBuktiPOP ?>"
					}

					$.post("<?php echo @$print_etiket ?>", data_post, function(response, status, xhr) {

						if ("error" == response.status) {
							$.alert_error(response.message);
							return false
						}

						printJS({
							printable: response.data_print,
							type: 'pdf',
							base64: true
						});
					});
				}
					
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
											data: "Barang_Id", 
											className: "actions text-center", 
										},
										{ 
											data: "Kode_Barang", className: "text-center", 
											render: function( val, type, row ){
												if ( row.Barang_Id == 0 )
												{
													return "RACIKAN"
												}
												
												return val
											}
										},
										{ 
											data: "Nama_Barang", 
											render: function( val, type, row ){
												if ( row.Barang_Id == 0 )
												{
													return row.NamaResepObat
												}
												
												return val
											}
										},
										{ data: "Satuan", className: "text-center", },
										{ data: "TglED", className: "",
											render:function(val)
											{
												return moment(val).format('DD-MM-YYYY')
											}
										},
										{ 
											data: "Qty", 
											className: "text-right",
											render: function(val, type, row){
												return row.Qty;
											}
										},
										{ 
											data: "HargaSatuan", className: "",
											render: function( val, type, row){
												return mask_number.currency_add( row.HargaSatuan )
											}
										},
										{ data: "Disc_Persen", className: "" },
										{ 
											data: "HargaSatuan", className: "",
											render: function( val, type, row){ //+ row.BiayaResep;
												return mask_number.currency_add(row.Qty * row.HargaSatuan);
											}
										},
									],
								columnDefs  : [
										{
											"targets": ["NamaResepObat",  "HNA", "HPP", "HargaOrig", "HargaPersediaan", "Stok"],
											"visible": false,
											"searchable": false
										}
									],
								fnRowCallback : function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
										var index = iDisplayIndexFull + 1;
										$('td:eq(0)',nRow).html(index);
										return nRow;					
									},
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
									//_datatable_actions.calculate_balance();
								},
								createdRow: function ( row, data, index ){
									$( row ).on( "click", "a.btn-print", function(e){
										e.preventDefault();												
										var elem = $( e.target );
										_datatable_actions.print(elem, row, data, index);
									});


									var Disc_Persen = $('#Disc_Persen');
									var total_racik = 0;
									var total_resep = 0;
									var total_obat = 0;
									var sub_total = 0;
									var total_diskon = 0;
									var grand_total = 0;
									
									var dt_details = $("#dt_details").DataTable().rows().data();					
									dt_details.each(function (value, index) {							
										if ( value.NamaResepObat == value.Nama_Barang && value.Barang_ID == 0) {
											total_racik = total_racik + parseFloat(value.Total || 0);
										} else {
											total_obat = total_obat + parseFloat(value.Total || 0);// + parseFloat(value.HExt || 0); 
										}						
										total_resep = total_resep + parseFloat(value.BiayaResep || 0);
									});
									
									sub_total = total_obat + total_racik + total_resep;
									grand_total = sub_total;
									if( parseFloat(Disc_Persen.val()) > 0) 
									{
										total_diskon = (grand_total * Disc_Persen.val() / 100);
										grand_total = grand_total - total_diskon;
										// Membulatkan Grand Total
										var grand_total_HExt = mask_number.currency_ceil(grand_total) - grand_total;
										grand_total = grand_total + grand_total_HExt;
										total_diskon = total_diskon - grand_total_HExt;
									}
									
									$("#total_racik").val( mask_number.currency_add( total_racik ) );
									$("#total_resep").val( mask_number.currency_add( total_resep ) );
									$("#total_obat").val( mask_number.currency_add( total_obat ) );
									$("#sub_total").val( mask_number.currency_add( sub_total ) );
									$("#diskon_value").val( mask_number.currency_add( total_diskon ) );
									$("#grand_total").html( mask_number.currency_add( grand_total ) );
								}
							});
							
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