<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open(current_url(), array("id" => "form_consumables")) ?>
<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Form Resep</h4>
        </div>
        <div class="modal-body">
            <div class="row form-group">
                <div class="col-md-6">
                    <div class="form-group">
                    	<label class="control-label col-md-3">No Resep</label>
                        <div class="col-md-9">
                        	<input type="text" id="NoBuktiBHP" name="NoBuktiBHP" class="form-control" value="<?php echo @$item->NoBukti?>"  readonly="readonly" />
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">Section </label>
                        <div class="col-md-9">
                        	<select id="Farmasi_SectionID" name="Farmasi_SectionID" class="form-control">
                           		<option value="SEC002" >UGD</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">DokterID</label>
                        <div class="col-md-9">
                            <div class="input-group">
                        	<input type="text" id="bhpDokterID" name="DokterID" class="form-control" value="<?php echo @$item->DokterID ?>" />
                                <span class="input-group-btn">
                                    <a href="<?php echo @$lookup_supplier ?>" id="lookup_supplier" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
                                    <a href="javascript:;" id="clear_supplier" class="btn btn-default" ><i class="fa fa-times"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">Nama Dokter</label>
                        <div class="col-md-9">
                        	<input type="text" id="bhpDoctorName" name="Nama_Supplier" value="<?php echo @$doctor->Nama_Supplier?>" class="form-control" readonly="readonly" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-9">
                            <div class="checkbox">
                                <input type="hidden" name="IncludeJasa" value="0" >
                                <input type="checkbox" id="IncludeJasa" name="IncludeJasa" value="1" <?php echo @$item->IncludeJasa == 1 ? "Checked" : NULL ?> class=""><label for="IncludeJasa">Ditagihkan</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">Paket BHP</label>
                        <div class="col-md-9">
                            <div class="input-group">
                        	<input type="hidden" id="Paket" name="Paket" value="<?php echo @$item->Paket ?>" />
                        	<input type="text" id="Package_name" name="Package_name" class="form-control" value="<?php echo @$package->Nama_Paket ?>" />
                                <span class="input-group-btn">
                                    <a href="<?php echo @$lookup_package ?>" id="lookup_supplier_prescription" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
                                    <a href="javascript:;" id="clear_supplier_prescription" class="btn btn-default" ><i class="fa fa-times"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <h3 id="DitagihkanText" class="text-center">DITAGIHKAN</h3>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">Jumlah</label>
                        <div class="col-md-9">
	                        <input type="text" id="JumlahTransaksi" name="JumlahTransaksi" value="<?php echo @$item->JumlahTransaksi ?>" class="form-control">
    					</div>
                    </div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="dt_products" class="table table-sm table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>BarangID</th>
                                    <th>Nama</th>
                                    <th>Satuan</th>                        
                                    <th>Qty</th>                        
                                    <th>Stok</th>                        
                                    <th>Harga @</th>                        
                                    <th>Disc</th>                        
                                    <th>Jumlah</th>                        
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row form-group">
                <a href="<?php echo @$lookup_product ?>" id="add_icd" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Produk</b></a>
            </div>
        </div>
        <div class="modal-footer">
        	<div class="row form-group">
            	<button type="submit" id="submit_consumables" class="btn btn-primary"><i class="fa fa-file"></i> Simpan</button>
            	<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<?php echo form_close()?>

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
									if( confirm( "<?php echo lang('poly:delete_confirm_message') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												_datatable_actions.calculate_balance();
												}
								} catch(ex){}
								
							break;						

							case 4:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ Number(data.JmlObat || 0) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.JmlObat = Number(this.value || 0);
											data.JumlahTransaksi = data.JmlObat * data.Harga;

											if (data.Disc > 0){data.JumlahTransaksi = data.JumlahTransaksi - (data.Disc * data.JumlahTransaksi / 100);}
											_datatable.row( row ).data( data ).draw(true);
											_datatable_actions.calculate_balance();
										} catch(ex){console.log(ex)}
									});
							break;	
													
							case 7:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ Number(data.Disc || 0) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.Disc = Number(this.value || 0);
											data.JumlahTransaksi = data.JmlObat * data.Harga;

											if (data.Disc > 0){data.JumlahTransaksi = data.JumlahTransaksi - (data.Disc * data.JumlahTransaksi / 100);}
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
								.draw(false);
								
						_datatable_actions.calculate_balance();
						
					},	
				calculate_balance: function(){
					try {
							var _form = $( "form[id=\"form_consumables\"]" );
							var _form_Jumlah = _form.find( "input[id=\"JumlahTransaksi\"]" );
							
							var JumlahTransaksi = 0;
							var table_data = $( "#dt_products" ).DataTable().rows().data();
							table_data.each(function (value, index) {
								JumlahTransaksi = JumlahTransaksi + parseFloat(value.JumlahTransaksi);
								console.log(JumlahTransaksi);
							});

							_form_Jumlah.val(parseFloat(JumlahTransaksi).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
					} catch(e){console.log(e);}
					},
				add_row: function( params, fn, scope ){
						_datatable.row.add(
							{
							}
						).draw(false);
						
						
					}
					
					
			};
		
		$.fn.extend({
				dt_products: function(){
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
										{ 
											data: "Kode_Barang", 
											className: "text-center", 
										},
										{ data: "Nama_Barang", className: "" },
										{ data: "Satuan", className: "" },
										{ data: "JmlObat", className: "text-center" },
										{ data: "Stok", className: "text-center" },
										{ data: "Harga", className: "text-right" },
										{ data: "Disc", className: "text-right" },
										{ data: "JumlahTransaksi", className: "text-right" },
									],
								columnDefs  : [
										{
											"targets": [],
											"visible": false,
											"searchable": false
										}
									],
								"drawCallback": function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
									_datatable_actions.calculate_balance();
								},
								createdRow: function ( row, data, index ){
										$( "td", row ).on( "dblclick", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
											
										$( "a.btn-remove", row ).on( "click dblclick", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('poly:delete_confirm_message') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
											});
									}
							} );
							
						$( "#dt_products_length select, #dt_products_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});

		
		$( document ).ready(function(e) {
            	$( "#dt_products" ).dt_products();
				$("#bhpDokterID").val( $("#DokterID").val() );
				$("#bhpDoctorName").val( $("#DocterName").val() );
				
				$("#IncludeJasa").on("change", function(e){
					if( this.checked )
					{
						$("#DitagihkanText").html("DITAGIHKAN");
					} else {					
						$("#DitagihkanText").html("TIDAK DITAGIHKAN");
					}
				});
								
				$("form[id=\"form_consumables\"]").on("submit", function(e){
					e.preventDefault();	
					
					$("#submit_consumables").prop("disabled", true);
					
					JumlahTransaksi = $("#JumlahTransaksi").val().replace(",", "")
					JumlahTransaksi = JumlahTransaksi.replace(".", ",")
					var d = new Date();
					var data_post = { };
						data_post['f'] = {
							"NoBukti" : $("#NoBuktiBHP").val(),
							"NoReg" : $("#RegNo").val(),
							"Tanggal" :  "<?php echo date("Y-m-d")?>",
							"Jam" :"<?php echo date("Y-m-d")?> "+ d.getHours() +':'+ d.getMinutes() +':'+ d.getSeconds(),
							"SectionID" : "SEC002",
							"DokterID" : $("#bhpDokterID").val(),
							"Paket" : $("#Paket").val(),
							"JumlahTransaksi": parseFloat(JumlahTransaksi),
							"NRM" : $("#NRM").val(),
							"NoKartu" : $("#NoKartu").val(),
							"KTP" : $("#KTP").val() || 0,
							"KerjasamaID" : $("#JenisKerjasamaID").val(),
							"PerusahaanID" : $("#CompanyID").val(),
							"KomisiDokter" : '0,00',
							"SectionInput" : 'SEC002',
							"TipeTransaksi" : "POP",
							"IncludeJasa" : $("#IncludeJasa").val() || 0,
						};
						
						data_post['details'] = {};
											
					var table_data = $( "#dt_products" ).DataTable().rows().data();
					
					table_data.each(function (value, index) {
						var detail = {
							"NoBukti" : $("#NoBuktiBHP").val(),
							"Barang_ID"	: value.Barang_ID,
							"Satuan" : value.Satuan,
							"JmlObat" : value.JmlObat,
							"Harga" : value.Harga,
							"Disc" : value.Disc,
							"Stok" : value.Stok,
							"Plafon" : 0,
						}
						
						data_post['details'][index] = detail;
					});
					console.log(data_post);
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
						
						var response = $.parseJSON(response);

						if( "error" == response.status ){
							$.alert_error(response.message);
							$("#submit_consumables").prop("disabled", false);
							return false
						}
						
						$.alert_success( response.message );
						
						// Memasukan Data header reserp ke view tabel resep
						// Tambahkan nama supplier (nama dokter)
						data_post['f']['Nama_Supplier'] = $("#bhpDoctorName").val();
						$("#dt_consumables").DataTable().row.add( data_post['f'] ).draw( true );
						
						// Close Form
						$( '#form-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
						
												
					})	
				});

			});

	})( jQuery );
//]]>
</script>