<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open(current_url(), array("id" => "form_prescriptions")) ?>
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
                        	<input type="text" id="NoResep" name="NoResep" class="form-control" value="<?php echo @$item->NoResep?>"  readonly="readonly" />
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">Section </label>
                        <div class="col-md-9">
                        	<select id="Farmasi_SectionID" name="Farmasi_SectionID" class="form-control ">
                            	<?php if (!empty($option_pharmacy)): foreach($option_pharmacy as $row):?>
                           		<option value="<?php echo $row->SectionID?>" <?php echo $row->SectionID == @$item->Farmasi_SectionID ? "selected" : NULL ?>><?php echo $row->SectionName?></option>
                                <?php endforeach;endif;?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">DokterID</label>
                        <div class="col-md-9">
                            <div class="input-group">
                        	<input type="text" id="prescriptionDokterID" name="DokterID" class="form-control" value="<?php echo @$item->DokterID ?>" />
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
                        	<input type="text" id="prescriptionDoctorName" name="Nama_Supplier" value="<?php echo @$doctor->Nama_Supplier?>" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">Berat Badan</label>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="number" id="BeratBadan" name="BeratBadan" value="<?php echo @$item->BeratBadan ?>" class="form-control text-right" />
                                <span class="input-group-btn">
                                    <a href="javascript:;" class="btn btn-default">Gr</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">Paket Obat</label>
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
                    <div class="form-group">
                        <label class="control-label col-md-3">Jumlah</label>
                        <div class="col-md-9">
                            <input type="hid" id="Jumlah" name="Jumlah" class="form-control" value="<?php echo @$item->Jumlah?>"  readonly="readonly" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">	
						 <label class="control-label col-md-3">Opsi</label>
                        <div class="col-md-3">
                            <div class="checkbox">
                                <input type="checkbox" id="CheckTambahRacikan" value="1" class=""><label for="CheckTambahRacikan"> Racikan</label>
                            </div>
                        </div>
						<div class="col-md-3">
                            <div class="checkbox">
                                <input type="hidden" name="IncludeJasa" value="0" >
                                <input type="checkbox" id="IncludeJasa" name="IncludeJasa" value="1" <?php echo @$item->IncludeJasa == 1 ? "Checked" : NULL ?> class=""><label for="IncludeJasa">Include Jasa</label>
                            </div>
                        </div>
						<div class="col-md-3">
                            <div class="checkbox">
                                <input type="hidden" name="Cyto" value="0" >
                                <input type="checkbox" id="Cyto" name="Cyto" value="1" <?php echo @$item->Cyto == 1 ? "Checked" : NULL ?> class=""><label for="Cyto">Cyto</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Nama Racikan</label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="text" id="NamaResepObat" placeholder="" class="form-control detail_form" readonly>
                                <span class="input-group-btn">
                                    <a href="javascript:;" id="BtnTambahRacikan" class="btn btn-default disabled"><i class="fa fa-plus"> Tambah Racikan</i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Nama Obat</label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="hidden" id="product_object" data-product="{}" class="detail_form">
                                <input type="text" id="Nama_Barang" placeholder="" class="form-control detail_form">
                                <span class="input-group-btn">
                                    <a href="<?php echo @$lookup_product ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
                                    <a href="javascript:;" id="detail_form" class="btn btn-default" ><i class="fa fa-times"></i></a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Qty</label>
                        <div class="col-lg-3">
                            <input type="number" id="JmlObat" name="d[JmlObat]" placeholder="" class="form-control detail_form">
                        </div>
                        <label class="col-lg-3 control-label text-center">Stok</label>
                        <div class="col-lg-3">
                            <input type="number" id="Stok" name="d[Stok]" placeholder="" class="form-control detail_form" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Harga Jual</label>
                        <div class="col-lg-3">
                            <input type="text" id="Harga" name="d[Harga]" placeholder="" class="form-control detail_form">
                        </div>
                        <label class="col-lg-3 control-label text-center">Diskon</label>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <input type="number" id="Disc" name="d[Disc]" placeholder="" class="form-control detail_form">
                                <span class="input-group-btn">
                                    <a href="javascript:;" class="btn btn-default" >%</a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Dosis</label>
                        <div class="col-lg-3">
                            <select id="Dosis" name="d[Dosis]" class="form-control detail_form select2">
                                <option value=""></option>
                                <?php if(!empty($option_dosis)): foreach($option_dosis as $row):?>
                                <option value="<?php echo $row->Dosis ?>" <?php echo $row->Dosis == @$item->Dosis ? "selected" : NULL  ?>><?php echo $row->Dosis ?></option>
                                <?php endforeach; endif;?>
                            </select>
                        </div>
                        <label class="col-lg-3 control-label text-center">Aturan</label>
                        <div class="col-lg-3">
                            <input type="text" id="Dosis2" name="d[Dosis2]" placeholder="" class="form-control detail_form">
                        </div>
                    </div>
                    <div class="form-group">
                    	<div class="col-lg-offset-3 col-md-9">
                        	<a href="javascript:;" id="add_product" class="btn btn-primary btn-block">Tambah</a>
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
                                    <th>Aturan Pakai</th>                        
                                    <th>Qty</th>                        
                                    <th>Harga @</th>                        
                                    <th>Disc</th>                        
                                    <th>Jumlah</th>                        
                                    <th>Stok</th>                        
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        	<div class="row form-group">
            	<button type="submit" id="submit_prescriptions" class="btn btn-primary"><i class="fa fa-file"></i> Simpan</button>
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
				init: function(){
						$("#CheckTambahRacikan").on("change", function(e){
							if ( $(this).is(':checked') )
							{
								$("#NamaResepObat").prop("readonly", false);
								$("#BtnTambahRacikan").removeClass("disabled");
							} else {
								$("#NamaResepObat").val('');
								$("#NamaResepObat").prop("readonly", true);
								$("#BtnTambahRacikan").addClass("disabled");
							}
						});
		
						$("#Paket").on("change", function(e){
							if ( $(this).is(':checked') )
							{
								$(".package").prop("disabled");
								$("a.package").removeClass("disabled");
							} else {
								$(".package").val('');
								$(".package").prop("readonly", true);
								$("a.package").addClass("disabled");
							}
						});
						
						$(".btn-clear").on("click", function(){
							var clearClass = "."+$(this).prop("id");
							
							$( clearClass ).val("");
							$( clearClass ).prop("checked", false);
						});
						
						$("#add_product").on("click", function(e){
							e.preventDefault();
							
							var data = $("#product_object").data("product");
			
							if ( data.Kode_Barang == '')
							{
								return false;
							}
							
							data.Dosis = $("#Dosis").val() +" "+ $("#Dosis2").val();
							data.Qty = $("#JmlObat").val();
							data.Harga_Satuan = $("#Harga").val();
							data.Disc = parseFloat($("#Disc").val());
							data.Jumlah =  parseFloat(data.Qty) * parseFloat(data.Harga_Satuan);
							
							if ( data.Disc > 0)
							{
								data.Jumlah =  data.Jumlah - ( data.Jumlah * data.Disc / 100 );
							}
							
							if ($("#CheckTambahRacikan").is(':checked'))
							{
								data.NamaResepObat = $("#NamaResepObat").val();
							}

							_datatable_actions.add_row( data );
							
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
								"Qty" : 1,
								"Harga_Satuan" : 0.00,
								"Disc_Persen" : 0.00,
								"Jumlah" : 0.00,
								"Stok" : 0,
								"Dosis" : "",
								"NamaResepObat" : $("#NamaResepObat").val(),
							};			
														
							_datatable_actions.add_row( data );
							
						});
						
					},
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
							break;							

							case 5:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ Number(data.Qty || 0) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.Qty = Number(this.value || 0);
											data.Jumlah = data.Qty * data.Harga_Satuan;

											if (data.Disc_Persen > 0){data.Jumlah = data.Jumlah - (data.Disc_Persen * data.Jumlah / 100);}
											_datatable.row( row ).data( data ).draw(true);
											_datatable_actions.calculate_balance();
										} catch(ex){console.log(ex)}
									});
							break;	

							case 6:
								if( data.Barang_ID != 0 ){
									return false;
								}
								
								var _input = $( "<input type=\"text\" style=\"width:100%\" value=\""+ parseFloat(data.Harga_Satuan || 0) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.Harga_Satuan = parseFloat(this.value || 0);
											data.Jumlah = data.Qty * data.Harga_Satuan;

											if (data.Disc_Persen > 0){data.Jumlah = data.Jumlah - (data.Disc_Persen * data.Jumlah / 100);}
											_datatable.row( row ).data( data ).draw(true);
											_datatable_actions.calculate_balance();
										} catch(ex){console.log(ex)}
									});
							break;	
																				
							case 7:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ Number(data.Disc_Persen || 0) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.Disc_Persen = Number(this.value || 0);
											data.Jumlah = data.Qty * data.Harga_Satuan;

											if (data.Disc_Persen > 0){data.Jumlah = data.Jumlah - (data.Disc_Persen * data.Jumlah / 100);}
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
				to_fixed: function(value, precision) {
						var power = Math.pow(10, precision || 0);
						return Math.round(value * power) / power;
					},
				calculate_balance: function(){
						try {
								var _form = $( "form[id=\"form_prescriptions\"]" );
								var _form_Jumlah = _form.find( "input[id=\"Jumlah\"]" );
								
								var Jumlah = 0;
								var table_data = $( "#dt_products" ).DataTable().rows().data();
								table_data.each(function (value, index) {
									if (value.Barang_ID == 0){
										return true;
									}
									
									Jumlah = Jumlah + parseFloat(value.Jumlah);
									console.log(Jumlah);
								});
	
								_form_Jumlah.val(parseFloat(Jumlah).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
						} catch(e){console.log(e);}
					},
				add_row: function( params, fn, scope ){
						_datatable.row.add(
						{
							"Barang_ID" : params.Barang_ID,
							"Kode_Barang" : params.Kode_Barang,
							"Nama_Barang" : params.Nama_Barang,
							"Satuan" : params.Satuan,
							"Qty" : params.Qty,
							"Disc_Persen" : params.Disc_Persen,
							"Jumlah" : params.Jumlah,
							"Stok" :  params.Stok,
							"Dosis" : params.Dosis,
							"NamaResepObat" : params.NamaResepObat,
							"Harga_Satuan" : params.Harga_Satuan,
						}).draw(true);

						_datatable_actions.calculate_balance();
						
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
										{ data: "Dosis", className: "" },
										{ data: "Qty", className: "text-center" },
										{ data: "Harga_Satuan", className: "text-right" },
										{ data: "Disc_Persen", className: "text-right" },
										{ data: "Jumlah", className: "text-right" },
										{ data: "Stok", className: "text-center" },
									],
								columnDefs  : [
										{
											"targets": ["NamaResepObat"],
											"visible": false,
											"searchable": false
										}
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

			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {					

						$("#DokterID").val( _response.Kode_Supplier );
						$("#DocterName").val( _response.Nama_Supplier );
					
						$( '#form-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
					} catch (e){console.log(e);}
				}
			}
		
		$( document ).ready(function(e) {
            	$("#dt_products").dt_products();
				_datatable_actions.init();
				
				$("#prescriptionDokterID").val( $("#DokterID").val() );
				$("#prescriptionDoctorName").val( $("#DocterName").val() );
								
				$("form[id=\"form_prescriptions\"]").on("submit", function(e){
					e.preventDefault();	
					
					$("#submit_prescriptions").prop("disabled", true);
					
					Jumlah = $("#Jumlah").val().replace(",", "")
					Jumlah = Jumlah.replace(".", ",")
					var d = new Date();
					var data_post = { };
						data_post['f'] = {
							"NoRegistrasi" : $("#RegNo").val(),
							"Tanggal" :  "<?php echo date("Y-m-d")?>",
							"Jam" :"<?php echo date("Y-m-d H:i:s")?>",
							"NoBukti" : $("#NoBukti").val(),
							"Farmasi_SectionID" : $("#Farmasi_SectionID").val(),
							"DokterID" : $("#prescriptionDokterID").val(),
							"Cyto" : $("#Cyto:checked").val() || 0,
							"IncludeJasa" : $("#IncludeJasa:checked").val() || 0,
							"Paket" : $("#Paket").val(),
							"Jumlah": parseFloat(Jumlah),
							"JenisKerjasamaID" : $("#JenisKerjasamaID").val(),
							"CompanyID" : $("#KodePerusahaan").val(),
							"PerusahaanID" : $("#KodePerusahaan").val(),
							"CustomerKerjasamaID" : $("#CustomerKerjasamaID").val(),
							"NRM" : $("#NRM").val(),
							"NoKartu" : $("#NoAnggota").val(),
							"KTP" : $("#PasienKTP").val(),
							"KomisiDokter" : 0.00,
							"Realisasi" : 0,
							"BeratBadan" : $("#BeratBadan").val()
						};
						
						data_post['details'] = {};
											
					var table_data = $( "#dt_products" ).DataTable().rows().data();
					
					table_data.each(function (value, index) {
						var detail = {
							"Barang_ID"	: value.Barang_ID,
							"Satuan" : value.Satuan,
							"Dosis" : value.Dosis,
							"Qty" : value.Qty,
							"Harga_Satuan" : value.Harga_Satuan,
							"Disc_Persen" : value.Disc_Persen,
							"Stok" : value.Stok,
							"Plafon" : 0,
							"NamaResepObat" : value.NamaResepObat,
							"JenisKerjasamaID" : $("#JenisKerjasamaID").val(),
							"PerusahaanID" : $("#KodePerusahaan").val(),
							"KTP" : $("#PasienKTP").val(),
							"DokterID" : $("#prescriptionDokterID").val(),
							"KelasID" : "xx",
						}
						
						data_post['details'][index] = detail;
					});
					console.log(data_post);
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
						
						var response = $.parseJSON(response);

						if( "error" == response.status ){
							$.alert_error(response.message);
							$("#submit_prescriptions").prop("disabled", false);
							return false
						}
						
						$.alert_success(response.message);
						
						// Memasukan Data header reserp ke view tabel resep
						// Tambahkan NoResep dan nama supplier (nama dokter)
						data_post['f']['NoResep'] = response.NoResep;
						data_post['f']['Nama_Supplier'] = $("#prescriptionDoctorName").val();
						$("#dt_prescriptions").DataTable().row.add( data_post['f'] ).draw( true );
						
						// Close Form
						$( '#form-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
						
												
					})	
				});

			});

	})( jQuery );
//]]>
</script>