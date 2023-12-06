<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//print_r($collection);
?>
<div class="row form-group">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_discounts" class="table table-sm table-bordered" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nama Diskon</th>                        
                        <th>Nama Dokter</th>                        
                        <th>Nama Jasa</th>
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
<div class="row form-group">
	<a href="<?php echo @$lookup_discount ?>" id="add_charge" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Diskon</b></a>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		var NoReg =  "<?php echo @$item->NoReg; ?>";
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
						switch( this.index() ){
							case 1:
							    var indexRow = _datatable.row(row).index();
								try{
									form_ajax_modal.show("<?php echo $lookup_supplier ?>/"+ indexRow)
								} catch(ex){}
								
							break;

							case 2:
							    var indexRow = _datatable.row(row).index();
								
								try{
									form_ajax_modal.show("<?php echo $lookup_supplier ?>/"+ indexRow )
								} catch(ex){}
								
							break;
							
							case 3:
							    var IdIndex = _datatable.row(row).index();
								var data = _datatable.row(IdIndex).data();
								var IdDiskon = data.IDDiscount;
								
								try{
									form_ajax_modal.show("<?php echo $lookup_discount_jasa ?>/"+ IdDiskon + "/" + NoReg + "/" + IdIndex)
								} catch(ex){}
								
							break;
							
							case 5:
							
								var _input = $( "<input type=\"number\" value=\"" + parseFloat(data.Persen) + "\" style=\"width:100%\"  class=\"form-control qty_recipient\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();

										if( !$.isNumeric( this.value ) || this.value < 0)
										{
											data.Persen = 0;
											_datatable.row( row ).data( data ).draw();
											return false;
										} else if( this.value > 100){
											data.Persen = 100;
											_datatable.row( row ).data( data ).draw();
										} else{
											data.Persen = this.value;
										}
										
										try{
																						
											_datatable_actions.discount_percent( data, function(){ _datatable_actions.calculate_discount() }, row );
											
										} catch(ex){}
								});								
							break;
							case 6:
							
								var _input = $( "<input type=\"number\" value=\"" + mask_number.currency_remove(data.NilaiDiskon) + "\" style=\"width:100%\"  class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();

										if( !$.isNumeric( this.value ) || this.value < 0)
										{
											data.NilaiDiskon = 0;
											_datatable.row( row ).data( data ).draw( false );
											return false;
										}       
										try{
											
											data.NilaiDiskon = this.value;
											_datatable_actions.discount_number( data, function(){ _datatable_actions.calculate_discount() }, row );
											
										} catch(ex){}
								});								
							break;
							case 7:
							
								var _input = $( "<input type=\"text\" value=\"" + data.Keterangan + "\" style=\"width:100%\"  class=\"form-control\">" );

								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Keterangan = this.value || "-";
											_datatable.row( row ).data( data );
										} catch(ex){}
								});								
							break;
							
						}
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);
								
						_datatable_actions.calculate_discount();
						
					},
				discount_percent: function( params, fn, scope ){
						
						var _form = $( "form[name=\"form_general_payment\"]" );
						var Persen = parseFloat(params.Persen);
												
						$.get(
								'<?php echo base_url('cashier/general_payments/discount/get_discount_value')?>', 
								{DokterID: params.IDDokter, NoReg: $('#NoReg').val(), IDDiscount: params.IDDiscount, JasaID: params.IDJasa },
								function(data){
									if($.isEmptyObject(data.collection)) return true;
									var row = data.collection;
									params.NilaiDiskon = row.Nilai * Persen / 100 ;
									_datatable.row( scope ).data( params ).draw();
									
									if( $.isFunction(fn) ){
										fn.call( scope || _datatable )
									}
								}
							);
						
						
					},
				discount_number: function( params, fn, scope ){

						var _form = $( "form[name=\"form_general_payment\"]" );
						var NilaiDiskon = mask_number.currency_remove(params.NilaiDiskon);
												
						$.get(
								'<?php echo base_url('cashier/general_payments/discount/get_discount_value')?>', 
								{DokterID: params.IDDokter, NoReg: $('#NoReg').val(), IDDiscount: params.IDDiscount, JasaID: params.IDJasa },
								function(data){
									if($.isEmptyObject(data.collection)) return true;									
									var row = data.collection;
									
									NilaiDiskon = ( NilaiDiskon > Nilai) ? Nilai : NilaiDiskon;
									var Persen =  NilaiDiskon / row.Nilai * 100;
									params.Persen = parseFloat( Persen ).toFixed( 2 );
									console.log('Nilai Diskon', params.NilaiDiskon);
									_datatable.row( scope ).data( params ).draw();
									
									if( $.isFunction(fn) ){
										fn.call( scope || _datatable )
									}
								}
							);						
					},
				calculate_discount: function( params, fn, scope ){
						var _form = $( "form[name=\"form_general_payment\"]" );
						var Nilai = _form.find( "input[id=\"Nilai\"]" ); //Nilai Total Pembayaran yg harusnya dibayar
						var SubTotal = _form.find( "input[id=\"SubTotal\"]" );
						var TaxCC = _form.find( "input[id=\"TaxCC\"]" );
						var GrandTotal = _form.find( "input[id=\"GrandTotal\"]" );
						var Pembayaran = _form.find( "input[id=\"Pembayaran\"]" );
						var Tunai = _form.find( "input[id=\"Tunai\"]" );
						var NilaiDiskon = _form.find( "input[id=\"NilaiDiskon\"]" );
	
						var k_Amount = _form.find( "input[id=\"k_Amount\"]" );
						var k_Total = _form.find( "input[id=\"k_Total\"]" );
						var Sisa = _form.find( "input[id=\"Sisa\"]" );
						var JumlahBayar = _form.find( "input[id=\"JumlahBayar\"]" );
						var NilaiKembalian = _form.find( "input[id=\"NilaiKembalian\"]" );
						var Total = 0 , Sisa_ = 0, TaxCC_ = 0;
						var DiscountTotal = 0, SubTotal_ = 0;
						
						try {
							
							/*var DiscountData = $( "#dt_discounts" ).DataTable().rows().data();
							DiscountData.each(function (v, i) {
							//	v.
							});*/
							
							
							var DiscountData = $( "#dt_discounts" ).DataTable().rows().data();
							DiscountData.each(function (v, i) {
								DiscountTotal = DiscountTotal +  mask_number.currency_remove(v.NilaiDiskon);
							});
							
							console.log("NilaiDiskon: ", DiscountTotal);
							NilaiDiskon.val( mask_number.currency_add( DiscountTotal ));
							SubTotal_ = mask_number.currency_remove(Nilai.val()) - parseFloat( DiscountTotal );
							
							console.log("SubTotal: ", SubTotal_);
							var TaxCC_ = mask_number.currency_remove(TaxCC.val());
							SubTotal.val( mask_number.currency_add( SubTotal_ ));
							GrandTotal.val( mask_number.currency_add( SubTotal_ + TaxCC_ ));
							Pembayaran.val( mask_number.currency_add( SubTotal_ ));
							
							$(".payment-type").each(function(index, element) {
								element.value = element.value || 0;
								if(element.value == 0 ) return;
								is_credit_card = $(this).hasClass("credit-card"); 
								if ( is_credit_card ){
									TaxCC_ = mask_number.currency_remove(k_Total.val()) - mask_number.currency_remove(k_Amount.val());
									SubTotal_payment = mask_number.currency_remove(k_Amount.val());
								} else {
									SubTotal_payment = mask_number.currency_remove(element.value);
								}
								
								console.log("SubTotal_payment : ", SubTotal_payment);
								Total = Total + SubTotal_payment;				
							});
							console.log("Total : ", Total);
							
							var SubTotal_ = mask_number.currency_remove(SubTotal.val());
							var GrandTotal_ = SubTotal_ + TaxCC_;
							TaxCC.val( mask_number.currency_add(TaxCC_));					
							GrandTotal.val( mask_number.currency_add(GrandTotal_));	
				
							var JumlahBayar_ = mask_number.currency_remove(JumlahBayar.val()) || 0;
							var Pembayaran_ = Total + JumlahBayar_ + TaxCC_;
							Pembayaran_ = Pembayaran_ > GrandTotal_ ? GrandTotal_ : Pembayaran_;
							Pembayaran.val(mask_number.currency_add( Pembayaran_ ));
							
							var Sisa_ = GrandTotal_ - Pembayaran_;
							Sisa.val( mask_number.currency_add(Sisa_));
							
							NilaiKembalian.val(0.00);
							var NilaiKembalian_ = JumlahBayar_ - (GrandTotal_ - TaxCC_ - Total) || 0;
							if( JumlahBayar_ > 0 && NilaiKembalian_ > 0 && JumlahBayar_ > NilaiKembalian_){
								NilaiKembalian.val(mask_number.currency_add(NilaiKembalian_));
							}
							
							NilaiKembalian_  = mask_number.currency_remove(NilaiKembalian.val());
							Tunai.val(mask_number.currency_add(JumlahBayar_ - NilaiKembalian_));
							
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
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ 
											data: "IDDiscount", 
											render: function(val, type, row){
												return row.IDDiscount +' - '+ row.NamaDiscount
											}
										},
										{ 
											data: "IDDokter",
											render: function(val, type, row){
												return row.IDDokter +' - '+ row.NamaDokter
											}
										 },
										{ 
											data: "IDJasa", 
											render: function(val, type, row){
												return row.IDJasa +' - '+ row.NamaJasa
											} 
										},
										{ data: "Kelas", className: "text-center" },
										{ data: "Persen", className: "text-right" },
										{ 
											data: "NilaiDiskon", 
											className: "text-right",
											render: function(val){
												return mask_number.currency_add(val);
											}
										},
										{ data: "Keterangan" },
									],
								columnDefs  : [
										{
											"targets": ["KomponenID","Harga","Tarif"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
									_datatable_actions.calculate_discount();	
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