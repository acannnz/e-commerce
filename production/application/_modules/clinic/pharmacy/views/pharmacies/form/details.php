<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row form-group">
    <div class="table-responsive">
        <table id="dt_details" class="table table-sm table-bordered" width="100%">
            <thead>
                <tr>
                    <th></th>
                    <th>Nama Obat</th>                        
                    <th>Satuan</th>                        
                    <!--<th>Dosis</th>                        
                    <th>Tgl. ED</th>-->                        
                    <th>Jumlah</th>                        
                    <th>Stok</th>                        
                    <th>Harga@</th>                        
                    <th>Disc%</th>
                    <th>Resep</th>
                    <th>Total</th>                        
                    <th>Ket</th>                        
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
        	<h1 class="text-left">GRAND TOTAL</h1>
        </div>
        <div class="form-group">
        	<h1 class="text-right text-danger" id="grand_total">0.00</h1>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
        	<label class="col-md-3 text-right">Sub Total</label>
            <div class="col-md-9">
            	<input type="text" id="sub_total" name="sub_total" class="form-control" readonly/>
            </div>
        </div>
        <div class="form-group">
        	<label class="col-md-3 text-right">Biaya Racik</label>
            <div class="col-md-9">
            	<input type="text" id="total_racik" name="total_racik" class="form-control" readonly/>
            </div>
        </div>
        <div class="form-group">
        	<label class="col-md-3 text-right">Biaya Resep</label>
            <div class="col-md-9">
            	<input type="text" id="total_resep" name="total_resep" class="form-control" readonly/>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		var _detail_rows = [];
		var _option_dosis = '';
		<?php foreach($option_dosis as $row ):?>
			_option_dosis += '<option value="<?php echo $row->IDDosis?>"><?php echo $row->Dosis?></option>\n'
		<?php endforeach;?>;
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
						
						switch( this.index() ){
							
							/*case 3:
								var _input = $( "<input type=\"text\" style=\"width:100%\" value=\""+ data.Dosis  +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.Dosis = this.value || "";
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
										} catch(ex){}
									});
							break;		*/					

							/*case 4:
								var _input = $( "<input type=\"text\" style=\"width:100%\" value=\""+ data.Dosis2  +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.Dosis2 = this.value || "";
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
										} catch(ex){}
									});
							break;*/

							/*case 4:
								var _input = $( "<input type=\"text\" style=\"width:100%\" value=\""+ data.TglED  +"\" class=\"form-control datepicker\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.TglED = this.value || "";
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
										} catch(ex){}
									});
							break;	*/
							
							case 3:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ Number(data.JmlObat || 0) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.JmlObat = Number(this.value || 0);
											if( data.Barang_ID != 0  ){
												data.Total = data.JmlObat * mask_number.currency_remove( data.Harga );
												data.HExt = mask_number.currency_ceil(data.Total) - data.Total;
											}

											if (data.Disc > 0){ data.Total = data.Total - (data.Disc * data.Total / 100); }
											_datatable.row( row ).data( data ).draw(true);
											_datatable_actions.calculate_balance();
										} catch(ex){console.log(ex)}
									});
							break;	
							case 5:
								if ( data.NamaResepObat != data.Nama_Barang) {
									return false;
								}
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ mask_number.currency_remove(data.Harga || 0) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											if ( data.NamaResepObat == data.Nama_Barang) {
												var this_value = mask_number.currency_remove( this.value );
												data.Harga = this_value;
												data.Total = data.JmlObat * this_value;
												data.HExt = mask_number.currency_ceil(data.Total) - data.Total;
	
												if (data.Disc > 0){ data.Total = data.Total - (data.Disc * data.Total / 100); }
												_datatable.row( row ).data( data ).draw(true);
												_datatable_actions.calculate_balance();
											}
										} catch(ex){console.log(ex)}
									});
							break;														
							case 6:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ Number(data.Disc || 0) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.Disc = Number(this.value || 0);
											data.Total = data.JmlObat * mask_number.currency_remove( data.Harga );
											data.HExt = mask_number.currency_ceil(data.Total) - data.Total;

											if (data.Disc > 0){ 
												data.Total = data.JmlObat * mask_number.currency_remove( data.Harga ) - (data.Disc * data.Total / 100); 
												data.HExt = mask_number.currency_ceil(data.Total) - data.Total;
											}
											_datatable.row( row ).data( data ).draw(true);
											_datatable_actions.calculate_balance();
										} catch(ex){console.log(ex)}
									});
							break;	
							case 7:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ mask_number.currency_remove(data.BiayaResep || 0) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.BiayaResep = this.value || 0;

											_datatable.row( row ).data( data ).draw(true);
											_datatable_actions.calculate_balance();
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
				details: function( data, row, elem ){
						var _tr = $( elem ).closest( 'tr' );
						var _rw = $( "#dt_details" ).DataTable().row(_tr); //_datatable.row( _tr );
						
						var _dt = _rw.data();
						var _ids = $.inArray( _tr.attr( 'id' ), _detail_rows );
				 
						if( _rw.child.isShown() ){
							_tr.removeClass( 'details' );
							
							$(elem).find('i').addClass( 'fa-expand' );
							$(elem).find('i').removeClass( 'fa-compress' );
							
							_rw.child.hide();
				 
							// Remove from the 'open' array
							_detail_rows.splice( _ids, 1 );
						} else {
							$(elem).find('i').removeClass( 'fa-expand' );
							$(elem).find('i').addClass( 'fa-compress' );
									
							_tr.addClass( 'details' );
							
							//if( _rw.child() == undefined ){
								var _details = $( "<div class=\"details-loader\"></div>" );
								_rw.child( _details ).show();
																
								
								_details.html('<div class="row">'+
										'<div class="col-md-3">'+
											'<div class="form-group">'+
												'<label class="col-md-4">Tgl ED</label>'+
												'<div class="col-md-8">'+
													'<input type="date" name="TglED" value="'+ data.TglED +'" class="form-control datepicker2">'+
												'</div>'+
											'</div>'+
										'</div>'+
										'<div class="col-md-3">'+
											'<div class="form-group">'+
												'<label class="col-md-4 text-center">Dosis</label>'+
												'<div class="col-md-8">'+
													'<input type="text" name="Dosis" value="'+ data.Dosis +'" class="form-control">'+
													// '<select name="Dosis" class="form-control">'+
													// 	'<option value="'+ (data.DosisID || '') +'">'+ data.Dosis +'</option>'+
													// 	_option_dosis +
													// '</select>'+
												'</div>'+
											'</div>'+
										'</div>'+
										'<div class="col-md-3">'+
											'<div class="form-group">'+
												'<label class="col-md-4 text-center">Aturan Pakai</label>'+
												'<div class="col-md-8">'+
													'<input type="text" name="Dosis2" value="'+ data.Dosis2 +'" class="form-control">'+
													// '<select name="Dosis" class="form-control">'+
													// 	'<option value="'+ (data.DosisID || '') +'">'+ data.Dosis +'</option>'+
													// 	_option_dosis +
													// '</select>'+
												'</div>'+
											'</div>'+
										'</div>'+
										'<div class="col-md-3">'+
											'<div class="form-group text-right">'+
												'<button type="button" class="save btn btn-primary btn-block btn-save-detail">Simpan</button>'+													
											'</div>'+
										'</div>'+
									'</div>');
								
								_details.on( "click", "button.btn-save-detail", function(e){
									e.preventDefault();		

									data.TglED = _details.find('input[name="TglED"]').val();
									data.DosisID = 0;
									data.Dosis = _details.find('input[name="Dosis"]').val();
									data.Dosis2 = _details.find('input[name="Dosis2"]').val();


									// data.DosisID = _details.find('select[name="Dosis"]').val();
									// data.Dosis = _details.find('select[name="Dosis"] option:selected').html();									
									
									_datatable.row( row ).data( data ).draw();	
									_tr.find('.btn-detail').trigger( 'click' );
								});
								
								_details.on( "change", 'input[name="TglED"], select[name="Dosis"]', function(e){

									data.TglED = _details.find('input[name="TglED"]').val();
									data.DosisID = 0;
									data.Dosis = _details.find('input[name="Dosis"]').val();
									data.Dosis2 = _details.find('input[name="Dosis2"]').val();
									// data.DosisID = _details.find('select[name="Dosis"]').val();
									// data.Dosis = _details.find('select[name="Dosis"] option:selected').html();
									_datatable.row( row ).data( data ).draw();	
								});
								
									
								/*_details.on('focus',".datepicker2", function(){
									$(this).datetimepicker({
											format: "YYYY-MM-DD",
											keepOpen: true,
											collapse: false,
										});
								});*/
			
								$( window ).trigger( "resize" );
								
							/*} else {
								_rw.child.show();
							}*/
							
							// Add to the 'open' array
							if( _ids === -1 ){
								_detail_rows.push( _tr.attr( 'id' ) );
							}
						}
						
						$( window ).trigger( "resize" );
					},
				calculate_balance: function(){
						var sub_total = 0;
						var total_racik = 0;
						var total_resep = 0;
						var grand_total = 0;
						
						var dt_details = $("#dt_details").DataTable().rows().data();					
						dt_details.each(function (value, index) {							
							if ( value.NamaResepObat == value.Nama_Barang && value.Barang_ID == 0) {
								total_racik = total_racik + parseFloat(value.Total || 0);
							} else {
								sub_total = sub_total + parseFloat(value.Total || 0) + parseFloat(value.HExt || 0); 
							}						
							total_resep = total_resep + parseFloat(value.BiayaResep || 0);
						});
						
						grand_total = sub_total + total_racik + total_resep;
						
						$("#sub_total").val( mask_number.currency_add( sub_total ) );
						$("#total_racik").val( mask_number.currency_add( total_racik ) );
						$("#total_resep").val( mask_number.currency_add( total_resep ) );
						$("#grand_total").html( mask_number.currency_add( grand_total ) );
					},
				add_row: function( params, fn, scope ){
					
						var rowIndex;
						check = $("#dt_details").DataTable().rows( function ( idx, data, node ) {
										if( data.Barang_ID == params.Barang_ID && data.NamaResepObat == params.NamaResepObat  )
										{ console.log(data);
											rowIndex = idx;
											data.JmlObat = parseFloat(data.JmlObat) + parseFloat(params.JmlObat);
											if( data.Barang_ID != 0 )
											{
												data.Total =  parseFloat(data.JmlObat) * parseFloat(data.Harga);
												data.HExt = mask_number.currency_ceil(data.Total) - data.Total;
											}
											params = data;
											
											return true;
										}										
										return false;								
									} ).data();
						
						if ( check.any() )
						{
							_datatable.row( rowIndex ).data( params ).draw();
						} else {
							console.log(mask_number.currency_ceil(params.Total) - parseFloat(params.Total))
							_datatable.row.add(
							{
								Barang_ID : params.Barang_ID,
								Kode_Barang : params.Kode_Barang,
								Nama_Barang : params.Nama_Barang,
								Satuan : params.Satuan,
								JmlObat : params.JmlObat,
								Disc : params.Disc,
								Total : params.Total,
								BiayaResep : params.BiayaResep,
								Stok :  params.Stok,
								TglED : params.TglED,
								DosisID : params.DosisID,
								Dosis : params.Dosis,
								Dosis2 : params.Dosis2,
								NamaResepObat : params.NamaResepObat,
								Keterangan : params.Keterangan,							
								HNA : params.HNA,
								HPP : params.HPP,
								Harga : params.Harga,
								HargaOrig : params.HargaOrig,
								HExt : mask_number.currency_ceil(params.Total) - parseFloat(params.Total),
								HargaPersediaan : params.HargaPersediaan,
								KelompokJenis : params.KelompokJenis,
							}).draw();
						}

						$("#CheckTambahRacikan").attr("checked", false);
						$("#NamaResepObat").val('');
						$("#NamaResepObat").prop("readonly", true);
						$("#BtnTambahRacikan").addClass("disabled");
						_datatable_actions.calculate_balance();
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
											data: "BarangID", 
											width: "100px",
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
															buttons += "<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-xs btn-remove\"><i class=\"fa fa-times\"></i></a>";
															buttons += "<a href=\"javascript:;\" title=\"<?php echo lang('buttons:detail') ?>\" class=\"btn btn-success btn-xs btn-detail\"> <i class=\"fa fa-expand\"></i></a>";
														buttons += "</div>";
													
													return buttons;
												} 
										},
										{ 
											data: "Nama_Barang", 
											render: function( val, type, row){
												return row.Kode_Barang +' - '+ row.Nama_Barang;
											}
										},
										{ data: "Satuan", className: "text-center", },
										//{ data: "Dosis", className: "" },
										//{ data: "Dosis2", className: "" },
										//{ data: "TglED", className: "" },
										{ data: "JmlObat", className: "text-right" },
										{ data: "Stok", className: "text-right" },
										{ 
											data: "Harga", 
											className: "text-right",
											render: function(val, type, row){
												return mask_number.currency_add( row.Harga );
											}
										},
										{ data: "Disc", className: "text-right" },
										{ 
											data: "BiayaResep", 
											className: "text-right",
											render: function( val, type, row){
												return mask_number.currency_add( val );
											}
										},
										{ 
											data: "Total", 
											className: "text-right",
											render: function(val, type, row){
												return mask_number.currency_add( parseFloat(row.Total) + parseFloat(row.HExt) );
											}
										},
										{ data: "Keterangan", className: "" },
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
										$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
											
										_datatable_actions.calculate_balance();
										
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
											});
										
										$( row ).on( "click", "a.btn-detail", function(e){
												e.preventDefault();										
												var elem = $( this );
												_datatable_actions.details( data, row, elem );
											});
										$( row ).find("a.btn-detail").trigger('click');										
											
									}
							} );
							
						$( "#dt_details_length select, #dt_details_filter input" )
						.addClass( "form-control" );
						
						// On each draw, loop over the `_detail_rows` array and show any child rows
						_datatable.on('draw', function (){
								$.each(_detail_rows, function ( i, id ){
										$( '#' + id + ' td.details-control').trigger( 'click' );
									});
							});
							
						return _this
					},
			});
		
		$( document ).ready(function(e) {
			$( "#dt_details" ).dt_details();
			
			$("#add_product").on("click", function(e){
				e.preventDefault();
				
				var data = $("#product_object").data("product");

				if ( data == [] || $.isEmptyObject(data) || data.length == 0)
				{
					return false;
				}
				
				data.DosisID = 0;
				data.Dosis = $("#Dosis").val() || '';
				data.Dosis2 = $("#Dosis2").val() || '';
				data.JmlObat = $("#JmlObat").val();
				data.TglED = '<?php echo date('Y-m-d')?>';
				data.Harga = mask_number.currency_remove( $("#Harga").val() );
				data.Disc = parseFloat($("#Disc").val());
				data.Total =  parseFloat(data.JmlObat) * mask_number.currency_remove( $("#Harga").val() );
				
				if ( data.Disc > 0)
				{
					data.Total =  data.Total - ( data.Total * data.Disc / 100 );
				}
				
				if ($("#CheckTambahRacikan").is(':checked'))
				{
					data.NamaResepObat = $("#NamaResepObat").val();
					data.Keterangan = $("#NamaResepObat").val();
					data.BiayaResep = 0.00;
				} else {
					data.NamaResepObat = data.Nama_Barang;
					data.Keterangan = "UMUM";
					data.BiayaResep = $("#WithoutPrescription").is(":checked") || $("#IsEmployee").is(":checked") || $('#JenisKerjasamaID').val() == '9'
										? 0.00 : <?php echo config_item('BiayaResepObat') ?>   ;
				}
				
				_datatable_actions.add_row( data );
				$('.detail_form').val('');
				$('#Nama_Barang').focus();
				
			});

			$("#BtnTambahRacikan").on("click", function(e){
				e.preventDefault();
				if ( $("#NamaResepObat").val() == '')
				{
					return false;
				}
				
				var data = {
					"Barang_ID" : 0,
					"Kode_Barang" : "RACIKAN",
					"Nama_Barang" : $("#NamaResepObat").val(),
					"Satuan" : "RACIKAN",
					"JmlObat" : 1,
					"Harga" : <?php echo config_item('BiayaRacikObat') ?>,
					"Disc" : 0.00,
					"BiayaResep" : 0.00,
					"Total" : <?php echo config_item('BiayaRacikObat') ?>,
					"Stok" : 0,
					"TglED" : "",
					"Dosis" : "",
					"Dosis_view" : '',
					"Dosis2" : "",
					"NamaResepObat" : $("#NamaResepObat").val(),
					"Keterangan" : $("#NamaResepObat").val(),
				};				
				
				_datatable_actions.add_row( data );
				$('#NamaResepObat').val('');
				
			});			
			
			$("#biaya_racik").on("keyup change", function(e){
				_datatable_actions.calculate_balance();

			});	
			
		});

	})( jQuery );
//]]>
</script>