<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<style>	
	ul.typeahead.dropdown-menu {
		width: 100% !important;
	}
	
	.zoom {
			display:inline-block;
			position: relative;
		}
	.zoomImg {
			display:block;
		}
	/* magnifying glass icon */
	.zoom:after {
		content:'';
		display:block; 
		width:33px; 
		height:33px; 
		position:absolute; 
		top:0;
		right:0;
		background:url(icon.png);
	}
	.zoom img {
		display: block;
	}
	.zoom img::selection { background-color: transparent; }
</style>

<?php echo form_open( current_url(), array("name" => "form_pharmacy") ); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">Realisasi Obat</h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("pharmacy/selling") ?>" class="btn btn-info" title="Realisasi Obat"><b><i class="fa fa-plus"></i> Realisasi Obat</b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="page-subtitle">
					<h3 class="text-primary">Data Resep Pasien</h3>
					<p>Informasi Data Resep Pasien</p>
				</div>
			</div>
			<input type="hidden" id="KTP" value="<?php echo @$item->KTP ?>">
			<div class="col-md-4">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('pharmacy:evidence_number_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('pharmacy:mr_number_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NRM" name="f[NRM]" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control" maxlength="8" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('pharmacy:patient_name_label') ?></label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="hidden" id="NoReg" value="<?php echo @$item->NoReg ?>" placeholder="" class="form-control prescription">
							<input type="text" id="NamaPasien" name="p[NamaPasien]" value="<?php echo @$item->NamaPasien ?>" placeholder="" class="form-control patient">
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_examination ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="prescription" class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div> 
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('pharmacy:type_patient_label') ?></label>
					<div class="col-lg-9">
						<select id="JenisKerjasamaID" name="f[JenisKerjasamaID]" class="form-control">
							<?php if(!empty($option_patient_type)): foreach($option_patient_type as $row):?>
							<option value="<?php echo $row->JenisKerjasamaID ?>" <?php echo $row->JenisKerjasamaID == @$item->KerjasamaID ? "selected" : NULL  ?>><?php echo $row->JenisKerjasama ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="col-lg-3 control-label">Tanggal/Jam</label>
					<div class="col-lg-6">
						<input type="text" id="WaktuResep" value="<?php echo substr(@$item->Jam, 0, 19) ?>" placeholder="" class="form-control prescription <?php echo (config_item('allow_change_transaction_date')) ? 'datetimepicker' : '' ?>" <?php echo (config_item('allow_change_transaction_date')) ? '' : 'disabled' ?>>
					</div>
					
					<div class="col-md-2">
						<div class="checkbox">
							<input type="checkbox" id="Cyto" name="f[Cyto]" value="1" <?php echo @$item->Cyto == 1 ? "Checked" : NULL ?> class="prescription" disabled="disabled"><label for="Cyto">Cyto</label>
						</div>
					</div>					
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Resep</label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="text" id="NoResep" value="<?php echo @$item->NoResep ?>" placeholder="" class="form-control prescription">
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_prescription ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="prescription" class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Section Asal</label>
					<div class="col-lg-9">
						<select id="SectionID" name="f[SectionID]" class="form-control patient">
							<option value=""></option>
							<?php if(!empty($option_section)): foreach($option_section as $row):?>
							<option value="<?php echo $row->SectionID ?>" <?php echo $row->SectionID == @$item->SectionID ? "selected" : NULL  ?>><?php echo $row->SectionName ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('pharmacy:doctor_label') ?></label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="hidden" id="DokterID" name="f[DokterID]" value="<?php echo @$item->DokterID ? $item->DokterID : "xx"  ?>" class="doctor">
							<input type="text" id="DocterName" value="<?php echo @$item->Nama_Supplier ? $item->Nama_Supplier : "NONE" ?>" placeholder="" class="form-control doctor">
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_supplier ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="doctor" class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('pharmacy:company_label') ?></label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="hidden" id="KelasID" value="<?php echo @$item->KelasID ?>" placeholder="" class="form-control cooperation">
							<input type="hidden" id="KodePerusahaan" value="<?php echo @$item->KodePerusahaan ?>" placeholder="" class="form-control cooperation">
							<input type="hidden" id="CustomerKerjasamaID" value="<?php echo @$item->CustomerKerjasamaID ?>" placeholder="" class="form-control cooperation">
							<input type="text" id="Nama_Customer"  value="<?php echo @$item->Nama_Customer ?>" placeholder="" class="form-control cooperation" readonly>
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_cooperation ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="cooperation" class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label"><?php echo lang('pharmacy:card_number_label') ?></label>
					<div class="col-md-9">
						<input type="text" id="NoKartu" name="f[NoKartu]" value="<?php echo @$item->NoKartu ?>" placeholder="" class="form-control cooperation">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('pharmacy:dob_label') ?></label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="text" id="TglLahir" name="p[TglLahir]" value="<?php echo @$item->TglLahir ?>" placeholder="" class="form-control datepicker patient" disabled>
							<div class="input-group-addon"><?php echo lang('pharmacy:age_label') ?></div>
							<input type="text" id="Umur" name="f[Umur]" value="<?php echo sprintf('%s %s %s %s', (float) @$item->UmurThn, lang('pharmacy:year_label'), (float) @$item->UmurBln, lang('pharmacy:month_label'))  ?>" placeholder="" class="form-control" readonly>
							<input type="hidden" id="UmurThn" name="f[UmurThn]" value="<?php echo @$item->UmurThn ?>">
							<input type="hidden" id="UmurBln" name="f[UmurBln]" value="<?php echo @$item->UmurBln ?>">
							<input type="hidden" id="UmurHr" name="f[UmurHr]" value="<?php echo @$item->UmurHr ?>">
						</div>
					</div>
				</div>    
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('pharmacy:address_label') ?></label>
					<div class="col-lg-9">
						<textarea id="Alamat" name="p[Alamat]" placeholder="" class="form-control patient" disabled><?php echo @$item->Alamat ?></textarea>
					</div>
				</div>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-md-12">	
				<div class="page-subtitle">
					<h3 class="text-primary">Form Data Obat</h3>
					<p>Informasi Penambahan Data Obat</p>
				</div>
			</div>
			<?php if(!empty($item->PrescriptionPicture)):?>
			<div class="col-md-6">
				<div class="form-group" style="overflow:hidden">
					<div class="well text-center">
						<a id="PrescriptionPicture" class="zoom" target="_blank" href="http://api.klinikkulhen.com/assets/prescriptions/<?php echo $item->PrescriptionPicture ?>">
							<img width="400" height="300" src="http://api.klinikkulhen.com/assets/prescriptions/<?php echo $item->PrescriptionPicture ?>" />
						</a>
					</div>
				</div>
			</div>
			<?php endif;?>
			<div class="col-md-6">	
				<div class="form-group">
					<label class="col-lg-3 control-label">Opsi</label>
					<div class="col-md-9">
						<div class="row">
							<div class="col-md-3">
								<div class="checkbox">
									<input type="checkbox" id="CheckTambahRacikan" value="1" class=""><label for="CheckTambahRacikan">Racikan</label>
								</div>
							</div>
							 <div class="col-md-3">
								<div class="checkbox">
									<input type="checkbox" id="IsEmployee" value="1" class=""><label for="IsEmployee">Karyawan</label>
								</div>
							</div>
							 <div class="col-md-3">
								<div class="checkbox">
									<input type="checkbox" id="WithoutPrescription" value="1" class=""><label for="WithoutPrescription">Tanpa Resep</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="checkbox">
									<input type="checkbox" id="IncludeJasa" name="f[IncludeJasa]" value="1" <?php echo @$item->IncludeJasa == 1 ? "Checked" : NULL ?> class="prescription"><label for="IncludeJasa">Include Jasa</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="checkbox">
									<input type="checkbox" id="ResepLuar" value="1" class=""><label for="ResepLuar">Resep Luar</label>
								</div>
							</div>
						</div>
					</div>					
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Nama Racikan</label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="text" id="NamaResepObat" placeholder="" class="form-control typeahead detail_form" autocomplete="off" readonly>
							<span class="input-group-btn">
								<a href="javascript:;" id="BtnTambahRacikan" class="btn btn-primary disabled"><i class="fa fa-plus"> Tambah</i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Nama Obat</label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="hidden" id="product_object" data-product="{}" class="detail_form">
							<input type="text" id="Nama_Barang" placeholder="" class="form-control typeahead detail_form" autocomplete="off">
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_products ?>" id="lookup_products" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i> <sup>(f3)</sup></a>
								<a href="javascript:;" id="detail_form" class="btn btn-default" ><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
			<?php if(empty($item->PrescriptionPicture)):?>
			</div>
			<div class="col-md-6">
			<?php endif;?>
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
						<input type="text" id="Harga" name="d[Harga]" placeholder="" class="form-control detail_form mask-number">
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
				<?php /*?><div class="form-group">
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
				</div><?php */?>
				<div class="form-group">
					<div class="col-lg-offset-3 col-md-9">
						<a href="javascript:;" id="add_product" class="btn btn-primary btn-block">Tambah</a>
					</div>
				</div>
			</div>
		</div>
		<?php echo modules::run("pharmacy/pharmacies/details/index", @$item ) ?>
		<div class="form-group">
			<div class="col-lg-12 text-right">
				<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
				<button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
				<?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>
<script type="text/javascript" src="<?php echo base_url('themes/default/assets/js/zoom.min.js')?>"></script>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
	
		var typeahead = {
			init : function(){
				$('#NamaResepObat').typeahead(
					{
					  hint: true,
					  highlight: true,
					  minLength: 1
					},
					{
					  name: 'NamaResepObat',
					  source: typeahead.collection()
					}
				);
					
				$('#NamaResepObat').on("keyup", function(){
					$("#NamaResepObat").data('typeahead').source = typeahead.collection();
				});
					
				$('#Nama_Barang').typeahead(
					{
						async: true,
						minLength: 3,
						name: 'Item Drug',
						autoSelect: true,
						displayText: function(item) {

							item.Total = mask_number.currency_ceil(item.Harga, 500);

							var objectItem = encodeURIComponent(JSON.stringify(item));
							return `<span data-item="${objectItem}">${item.Nama_Barang}</span>`;
						},
						highlighter: function(item) {
						var objectItem = $(item).data('item');
						var data = JSON.parse(decodeURIComponent(objectItem));
						// Rp. ${mask_number.currency_add(mask_number.currency_ceil(data.Harga, 500))} 
						var html = `
								<div class="row">
									<div class="col-md-8">
										${data.Nama_Barang}
									</div>
									<div class="col-md-4">
										per ${data.Satuan}
									</div>
								</div>
								<div class="row">
									<div class="col-md-8">
										${data.Kode_Barang}
									</div>
									<div class="col-md-4">
										Sisa stok: ${data.Qty_Stok} ${data.Satuan}
									</div>
								</div>
							`;

							return html;
						},
						afterSelect: function(_response) { 
							try {					
								var product_object = {
									"Barang_ID" : _response.Barang_ID,
									"Kode_Barang" : _response.Kode_Barang,
									"Nama_Barang" : _response.Nama_Barang,
									"Satuan" : _response.Satuan,
									"JmlObat" : 1,
									"Disc" : 0.00,
									"Total" : _response.Harga,
									"Stok" :  _response.Qty_Stok,
									"TglED" : "",
									"Dosis" : "",
									"Dosis2" : "",
									"NamaResepObat" : _response.Nama_Barang,
									"Keterangan" : "UMUM",
									"HNA" : _response.HNA,
									"HPP" : _response.HPP,
									"Harga" : _response.Harga,
									"HargaOrig" : _response.HargaOrig,
									"HargaPersediaan" : _response.HargaPersediaan,
									"KelompokJenis" : _response.KelompokJenis
								};
							
									
								$("#product_object").data("product", product_object);
								if(_response.Qty_Stok < 15)
								{
									$("#Stok").css("background","red");
								}
								else{
									$("#Stok").css("background","transparent");
								}
								$("#Nama_Barang").val( _response.Nama_Barang );
								$("#JmlObat").val(1);
								$("#Stok").val( _response.Qty_Stok );
								$("#Harga").val( mask_number.currency_add( _response.Harga ) );
								$("#Disc").val( 0 );
								
							} catch(e){console.log(e)}
						}
					}
				);
				
				$('#Nama_Barang').on("keyup change", function(){
					$("#Nama_Barang").data('typeahead').source = typeahead.collection_item();					
				});
				
			},
			collection : function(){
				return function findMatches(q, cb) {
					var matches, substringRegex;
					
					// an array that will be populated with substring matches
					matches = [];
					
					// regex used to determine if a string contains the substring `q`
					substrRegex = new RegExp(q, 'i');

					// iterate through the pool of strings and for any string that
					// contains the substring `q`, add it to the `matches` array
					data_details = $("#dt_details").DataTable().rows().data();
					data_details.each(function (value, index) {
						if ( value.Barang_ID === 0 && value.Nama_Barang == value.NamaResepObat ) {
							if ( substrRegex.test(value.Nama_Barang) ) {
								matches.push(value.Nama_Barang);
							}
						}
					});
					
					cb( matches );
				};
			},
			collection_item: function(){
				return function findMatches(query, processSync) {

					$.ajax({
					  url: "<?php echo base_url("pharmacy/products/lookup_collection") ?>",
					  type: 'GET',
					  data: {
						  	search : {value: query},
						  	SectionID : "<?php echo $section->SectionID?>",
							JenisKerjasamaID : $("#JenisKerjasamaID").val() || 2,
							CustomerKerjasamaID : $("#CustomerKerjasamaID").val() || 0,
							KTP : $("#KTP").val() || 0,
							IsEmployee : $("#IsEmployee:checked").val() || 0,
					  },
					  dataType: 'json',
					  success: function (json) {
						// in this example, json is simply an array of strings
						processSync(json.data);
					  }
					});
			  }
			}
		};
	
		$(document).keydown(function(event) {
			if(event.which == 114) { //F3
				$('#lookup_products').click();
				return false;
			}
		});

		$( document ).ready(function(e) {	
				typeahead.init();
				
				$('#PrescriptionPicture').zoom({on:'mouseover'});
				
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
				
				$("form[name=\"form_pharmacy\"]").on("submit", function(e){
					e.preventDefault();	

					if($("#JenisKerjasamaID").val() == 2 && ($("#CustomerKerjasamaID").val() == '' || $("#CustomerKerjasamaID").val() == 0) )
					{
						$.alert_error('Transaksi tidak dapat dilanjutkan.<br/> Perusahaan belum dipilih.');
						return false;
					}
					
					if ( ! $('#dt_details').DataTable().data().any() ) {
						$.alert_error('Transaksi tidak dapat dilanjutkan.<br/> Belum Ada Barang Yang dipilih.');
						return false;
					}
					
					if (!confirm("Apakah Anda yakin ingin memproses Data ini ?"))
					{
						return false;
					}
					
					try{
						var data_post = { };
							data_post['farmasi'] = {};
							data_post['farmasi_detail'] = {};
							
						var	d = new Date();
						var farmasi = {
								NoReg : $("#NoReg").val() || '-',
								NoResep : $("#NoResep").val() || '-',
								<?php if(config_item('allow_change_transaction_date')): ?>
								Tanggal : $("#WaktuResep").val() || '<?php echo date('Y-m-d') ?>',
								Jam : $("#WaktuResep").val() || '<?php echo date('Y-m-d H:i:s') ?>',
								<?php endif;?>
								NRM : $("#NRM").val() || '-',
								DokterID : $("#DokterID").val() || '',
								SectionID : '<?php echo $section->SectionID ?>',
								SectionAsalID : $("#SectionID").val() || '<?php echo $section->SectionID ?>',
								KerjasamaID : $("#JenisKerjasamaID").val(),
								KodePerusahaan : $("#KodePerusahaan").val(),
								CustomerKerjasamaID : $("#CustomerKerjasamaID").val() || 0,
								NoKartu : $("#NoKartu").val(),
								KelasID : $("KelasID").val() || 'xx',
								Paket : 0,
								BiayaRacik : $("#total_racik").val(),
								BiayaResep : $("#total_resep").val(),
								Total : $("#grand_total").html(),
								//JumlahTransaksi : $("#grand_total").html(),
								JenisPewatan : "RJ",
								Keterangan : $("#NamaPasien").val(),
								UserID : "<?php echo $user->User_ID?>",
								Shift : "<?php echo @$item->Shift ?>",
								ObatOral : 1,
								Retur : 0,
								unitbisnisid : 1,
								Karyawan : $("#karyawan:checked").val() || 0,
								IncludeJasa : $("#IncludeJasa:checked").val() || 0,
								DeskripsiObat : '',
								SectionInput : '<?php echo $section->SectionID ?>',
								ClosePayment : 0,
								ObatBebas : $("#WithoutPrescription:checked").val() || 0,
								ResepLuar : $("#ResepLuar:checked").val() || 0,
							}
						
						data_post['farmasi'] = farmasi;						
	
						var dt_details = $( "#dt_details" ).DataTable().rows().data();					
						dt_details.each(function (value, index) {
							var detail = {
								Barang_ID : value.Barang_ID,
								JenisKerjasamaID : $("#JenisKerjasamaID").val(),
								Satuan	: value.Satuan,
								JmlResep : 1,
								JmlObat : value.JmlObat,
								Qty : value.Qty,
								Disc : value.Disc,
								JmlObat : value.JmlObat,
								JmlPemakaian : value.JmlObat,
								ClosedPemakaian : value.JmlObat,
								Stok : value.Stok,
								NamaResepObat : value.NamaResepObat,
								JenisKerjasamaID : $("#JenisKerjasamaID").val(),
								Nama_Barang : value.Nama_Barang,
								HNA : value.HNA || 0,
								HPP : value.HPP || 0,
								Harga : value.Harga || 0,
								HargaOrig : value.HargaOrig || 0,
								HargaPersediaan : value.HargaPersediaan || 0,
								HExt : value.HExt || 0,
								BiayaResep : value.BiayaResep || 0,
								DosisID : value.DosisID,
								Dosis : value.Dosis,
								Dosis2 : value.Dosis2,
								TglED : value.TglED,
							}
							
							data_post['farmasi_detail'][index] = detail;
							data_post['farmasi']['DeskripsiObat'] += value.Nama_Barang +" # " ;						
						});
	
						$.post($(this).attr("action"), data_post, function( response, status, xhr ){
							
							var response = $.parseJSON(response);
	
							if( "error" == response.status ){
								$.alert_error(response.message);
								return false
							}
							
							$.alert_success( response.message );
							
							var NoBukti = response.NoBukti;
							
							setTimeout(function(){
								document.location.href = "<?php echo base_url("pharmacy/selling-view"); ?>/"+ NoBukti;								
							}, 300 );
							
						})	
					} catch (e){ console.log(e);}
				});

			});

	})( jQuery );
//]]>
</script>