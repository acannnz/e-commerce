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
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                    	<label class="control-label">No. Bukti</label>
						<input type="text" id="NoBuktiBHP" name="NoBuktiBHP" class="form-control" value="<?php echo @$item->NoBukti?>"  readonly="readonly" />
					</div>
				</div>
				<div class="col-md-3">
                    <div class="form-group">
                    	<label class="control-label">Dokter</label>
						<div class="input-group">
							<input type="hidden" id="bhpDokterID" name="DokterID" class="form-control" value="<?php echo @$item->DokterID ?>" />
							<input type="text" id="bhpNamaDokter" name="NamaDokter" class="form-control" value="<?php echo @$item->NamaDokter ?>" />
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_supplier ?>" id="lookup_supplier" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="clear_supplier" class="btn btn-default" ><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
                    <div class="form-group">
						<label class="control-label col-md-12">&nbsp;</label>
                        <div class="col-md-6">
                            <div class="checkbox">
                                <input type="checkbox" id="IncludeJasa" name="IncludeJasa" value="1" <?php echo @$item->IncludeJasa == 1 ? "Checked" : NULL ?> class=""><label for="IncludeJasa">Ditagihkan</label>
                            </div>
                        </div>
                    </div>
				</div>
				<?php /*?><div class="col-md-3">
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
                </div><?php */?>
            </div>
            <div class="row">
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
							<tfoot>
								<tr>
									<th></th>
									<th colspan="7">Total</th>
									<th id="JumlahTransaksi"></th>
								</tr>
							</tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <a href="<?php echo @$lookup_product ?>" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Produk</b></a>
            </div>
        </div>
        <div class="modal-footer">
        	<div class="row">
				<div class="col-md-6">
					<button type="button" class="btn btn-danger btn-block" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
				</div>
				<div class="col-md-6">
					<button type="submit" id="submit_consumables" class="btn btn-primary btn-block"><i class="fa fa-file"></i> Simpan</button>
				</div>
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
								.draw();
								
						_datatable_actions.calculate_balance();
						
					},	
				calculate_balance: function(){
					try {
							var _form = $( "form[id=\"form_consumables\"]" );
							var _form_Jumlah = _form.find( "#JumlahTransaksi" );
							
							var JumlahTransaksi = 0;
							var table_data = $( "#dt_products" ).DataTable().rows().data();
							table_data.each(function (value, index) {
								JumlahTransaksi = JumlahTransaksi + parseFloat(value.JumlahTransaksi);
								console.log(JumlahTransaksi);
							});

							_form_Jumlah.html(mask_number.currency_add(JumlahTransaksi));
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
										{ 
											data: "Harga", 
											className: "text-right",
											render: function(val){
												return mask_number.currency_add(val);
											}
										},
										{ data: "Disc", className: "text-right" },
										{ 
											data: "JumlahTransaksi", 
											className: "text-right",
											render: function(val){
												return mask_number.currency_add(val);
											}
										},
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
									_datatable_actions.calculate_balance();
								},
								createdRow: function ( row, data, index ){
										$( row ).on( "dblclick", "td", function(e){
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
								
				$("form[id=\"form_consumables\"]").on("submit", function(e){
					e.preventDefault();	
					
					$("#submit_consumables").prop("disabled", true);
					
					var data_post = { };
						data_post['f'] = {
							NoReg : "<?php echo $item->NoReg ?>",
							SectionAsalID : "<?php echo $item->SectionID ?>",
							SectionID : "<?php echo $item->SectionID ?>",
							DokterID : $("#bhpDokterID").val(),
							Paket : $("#Paket").val(),
							JumlahTransaksi : mask_number.currency_remove($("#JumlahTransaksi").html()),
							NRM : $("#NRM").val(),
							NoKartu : $("#NoKartu").val(),
							KTP : $("#PasienKTP").val() || 0,
							Paket : 1,
							KerjasamaID : $("#JenisKerjasamaID").val(),
							PerusahaanID : $("#CompanyID").val(),
							KomisiDokter : '0,00',
							SectionInput : "<?php echo $item->SectionID ?>",
							TipeTransaksi : "POP",
							IncludeJasa : $("#IncludeJasa:checked").val() || 0,
						};
						
						data_post['details'] = {};
											
					var table_data = $( "#dt_products" ).DataTable().rows().data();
					
					table_data.each(function (value, index) {
						var detail = {
							Barang_ID : value.Barang_ID,
							Satuan : value.Satuan,
							JmlObat : value.JmlObat,
							Disc : value.Disc,
							Stok : value.Stok,
							Plafon : 0,
							JenisKerjasamaID : $("#JenisKerjasamaID").val(),
							Nama_Barang : value.Nama_Barang,
							Harga : value.Harga || 0,
							HargaOrig : value.HargaOrig || 0,
							HargaPersediaan : value.HargaPersediaan || 0,
						}
						
						data_post['details'][index] = detail;
					});
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){	
									
						if( "error" == response.status ){
							$.alert_error(response.message);
							$("#submit_consumables").prop("disabled", false);
							return false
						}
						
						$.alert_success( response.message );
						
						// Memasukan Data header reserp ke view tabel resep
						// Tambahkan nama supplier (nama dokter)
						data_post['f']['NoBukti'] = response.NoBukti;
						data_post['f']['Nama_Supplier'] = $("#bhpDoctorName").val();
						data_post['f']['Tanggal'] = '<?php echo date('Y-m-d H:i:s')?>';
						data_post['f']['Jam'] = '<?php echo date('Y-m-d H:i:s')?>';
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