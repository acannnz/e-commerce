<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>

<?php echo form_open(current_url(), array("name" => "form_general_payment", "id" => "form_general_payment")); ?>
<div class="panel panel-info panel-collapsed">
	<div class="panel-heading panel-collapse">
		<h3 class="panel-title"><?php echo lang('general_payment:patient_label') ?></h3>
		<ul class="panel-btn">
			<li><a href="javascript:;" class="btn btn-info panel-collapse" title="Tampilkan"><i class="fa fa-angle-down"></i></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<!-- <div class="col-md-offset-1 col-md-10"> -->
		<div class="col-md-12">
			<div class="col-md-6">
				<div class="page-subtitle">
					<h3>Data Kasir</h3>
					<p>Informasi Data Kasir Pasien</p>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('general_payment:evidence_number_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" required readonly>
						<input type="hidden" id="tanggal" name="f[Tanggal]" value="<?php echo date("Y-m-d") ?>">
						<input type="hidden" id="jam" name="f[Jam]" value="<?php echo date("H:m:s") ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('general_payment:registration_number_label') ?></label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="text" id="NoReg" name="f[NoReg]" value="<?php echo @$item->NoReg ?>" class="form-control">
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_registration ?>" data-toggle="lookup-ajax-modal" class="btn btn-default <?php echo empty(@$item->NoReg) ? '' : 'disable' ?>"><i class="fa fa-search"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('general_payment:date_reg') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="TglReg" name="TglReg" value="<?php echo substr(@$item->JamReg, 0, 19) ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('general_payment:room_label') ?></label>
					<div class="col-lg-9">
						<input type="hidden" id="SectionID" name="SectionID" value="<?php echo @$item->SectionID ?>">
						<input type="text" id="SectionName" name="SectionName" value="<?php echo @$item->SectionName ?>" placeholder="" class="form-control patient" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Dokter</label>
					<div class="col-lg-9">
						<div class="input-group">
							<input type="hidden" id="DokterID" name="f[DokterID]" value="<?php echo @$item->DokterID ?>" class="doctor">
							<input type="text" id="NamaDokter" value="<?php echo @$item->NamaDokter ?>" placeholder="" class="form-control" readonly>
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_supplier ?>" data-toggle="lookup-ajax-modal" class="btn btn-default"><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="clear_doctor" class="btn btn-default"><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="page-subtitle">
					<h3>Data Pasien</h3>
					<p>Informasi Data Pasien</p>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('general_payment:nrm_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NRM" name="NRM" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control" maxlength="8" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('general_payment:patient_name_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NamaPasien" name="NamaPasien" value="<?php echo @$item->NamaPasien ?>" placeholder="" class="form-control patient" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('general_payment:patient_type_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="JenisKerjasama" name="JenisKerjasama" value="<?php echo @$item->JenisKerjasama ?>" placeholder="" class="form-control patient" readonly />
						<input type="hidden" id="JenisKerjasamaID" name="JenisKerjasamaID" value="<?php echo @$item->JenisKerjasamaID ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('general_payment:address_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="Alamat" name="p[Alamat]" value="<?php echo @$item->Alamat ?>" placeholder="" class="form-control patient" readonly />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('general_payment:treatment_type_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="TipePerawatan" name="f[TipePerawatan]" value="<?php echo @$item->RawatInap == 0 ? 'RJ' : 'RI' ?>" placeholder="" class="form-control patient" readonly>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="panel panel-success">
	<div class="panel-heading panel-collapsed">
		<?php
		// Filter dan menyimpan hanya elemen-elemen dengan GroupBiaya "OBAT"
		$obatItems = array_filter($get_rincian, function ($item) {
			return $item->GroupBiaya === "OBAT";
		});

		// Jika ada elemen-elemen dengan GroupBiaya "OBAT"
		if (!empty($obatItems)) {
			echo '<h3 class="panel-title">' . sprintf("%s: (%s) %s Obat Sudah Masuk", lang('general_payment:detail_heading'), @$item->NRM, @$item->NamaPasien_Reg) . '</h3>';
		} else {
			echo '<h3 class="panel-title" style="color: red;">' . sprintf("%s: (%s) %s Tidak Ada Obat Masuk", lang('general_payment:detail_heading'), @$item->NRM, @$item->NamaPasien_Reg) . '</h3>';
		}
		?>
		<ul class="panel-btn">
			<li><a href="<?php echo @$print_cost_breakdown ?>" id="print_cost_breakdown" target="_blank" class="btn btn-success col-lg-12" value="1"><i class="fa fa-search"></i> <?php echo lang('general_payment:preview_detail_label') ?></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<ul id="tab-general_payment" class="nav nav-tabs nav-justified">
				<li class="active"><a href="#general-payment-tab2" data-toggle="tab"><i class="fa fa-shopping-cart"></i> <?php echo lang("general_payment:payment_label") ?></a></li>
				<li><a href="#general-payment-tab1" data-toggle="tab"><i class="fa fa-percent"></i> <?php echo lang("general_payment:discount_label") ?></a></li>
			</ul>
			<div class="tab-content">
				<div id="general-payment-tab1" class="tab-pane tab-pane-padding ">
					<?php echo modules::run("cashier/general-payments/discount/index", @$item) ?>
				</div>
				<div id="general-payment-tab2" class="tab-pane tab-pane-padding active">
					<?php echo modules::run("cashier/general-payments/payment/index", @$item) ?>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-lg-3">
					<?php if (@$is_edit && @$item->Batal == 0) : ?>
						<a href="<?php echo @$lookup_cancel ?>" data-toggle="lookup-ajax-modal" class="btn btn-danger btn-block"><i class="fa fa-trash-o"></i> <?php echo lang("buttons:cancel") ?></a>
					<?php endif; ?>
				</div>
				<div class="col-lg-3">
					<?php if (@$is_edit && @$item->Batal == 0) : ?>
						<a href="javascript:;" id="print_invoice_mini" class="btn btn-success btn-block"><i class="fa fa-print"></i> Print Invoice Mini</a>
					<?php endif; ?>
				</div>
				<div class="col-lg-3">
					<?php if (@$is_edit && @$item->Batal == 0) : ?>
						<a href="<?php echo @$print_invoice ?>" id="print_invoice" class="btn btn-success btn-block" target="_blank"><i class="fa fa-print"></i> Print Invoice</a>
					<?php endif; ?>
				</div>
				<!-- <div class="col-lg-2">
					<?php if (@$is_edit && @$item->Batal == 0) : ?>
						<a href="<?php echo @$print_kwitansi ?>" id="print_kwitansi" class="btn btn-success btn-block" target="_blank"><i class="fa fa-print"></i> Print Kwitansi</a>
					<?php endif; ?>
				</div> -->
				<div class="col-lg-3">
					<?php if (@$item->Audit == 0 && @$item->Batal == 0) { ?>
						<button type="submit" class="btn btn-primary btn-block" <?php echo (@$is_edit) ? 'disabled' : null ?>><b><i class="fa fa-save"></i> <?php echo lang('buttons:submit') ?></b></button>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>

<?php if (config_item('bpjs_bridging') == 'TRUE')
	echo modules::run('bpjs/visite/add', @$item->NoReg); ?>

<script type="text/javascript">
	//<![CDATA[
	(function($) {
		var uninishedTrans = false;
		var beforeunload = false;
		var _form_actions = {
			init: function() {

				$("#print_cost_breakdown").on("click", function() {
					if (!confirm("Lihat Rincian Biaya ?")) {
						return false;
					}
				});

				$("#print_kwitansi").on("click", function() {
					if (!confirm("Cetak Kwitansi ?")) {
						return false;
					}
				});

				$("#print_invoice").on("click", function() {
					if (!confirm("Cetak Invoice Pembayaran ?")) {
						return false;
					}
				});

				<?php if (!@$is_edit) : ?>
					// Update Status ProsesPayment = 0, jika Transaksi di Tutup tapi belum disimpan.
					window.onbeforeunload = function(e) {
						// number of miliseconds to hold before unloading page
						var x = 2000;
						var a = (new Date()).getTime() + x;

						$.post('<?php echo $update_process_payment ?>', {
							f: {
								NoReg: $('#NoReg').val(),
								ProsesPayment: 0
							}
						});
						// browser will hold with unloading your page for X miliseconds, letting
						// your ajax call to finish
						while ((new Date()).getTime() < a) {}
						beforeunload = true;
						return 'onbeforeunload testing';
					};


					// setInterval(function(){
					// 	if(beforeunload)
					// 		$.post('<?php echo $update_process_payment ?>', {f: { NoReg : $('#NoReg').val(), ProsesPayment : 1} });
					// 	beforeunload = false;
					// }, 10000);

					<?php /*?>window.onbeforeunload = function(e) {							
								window.unloadTimer = setInterval(function(){
									if(uninishedTrans == true){
										$.post('<?php echo $update_process_payment ?>', {f: { NoReg : $('#NoReg').val(), ProsesPayment : 1} });
									}
									clearInterval(window.unloadTimer);							
								}, 500);
								
								window.onunload = function() {clearInterval(window.unloadTimer);}
								return 'onbeforeunload testing';
							};				<?php */ ?>
				<?php endif; ?>
			},
			post: function(dataPost, fn) {

				var pasienbon = $("#PasienBon:checked").val() || 0;
				var Sisa = mask_number.currency_remove($("#Sisa").val());
				if (Sisa != 0 && !pasienbon) {
					alert('Jumlah Pembayaran Belum Sesuai Dengan Tagihan!!');
					return false;
				}

				dataPost['DataTransaction'] = {
					NoBukti: $("#NoBukti").val(),
					Tanggal: $("#tanggal").val(),
					Jam: $("#jam").val(),
					NoReg: $("#NoReg").val(),
					RJ: $("#TipePerawatan").val(),
					DokterID: $("#DokterID").val(),
					FromDate: $("#TglReg").val(),
					ToDate: $("#tanggal").val(),
					Nilai: $("#Nilai").val(),
					NilaiOrig: $("#Nilai").val(),
					NilaiDiscount: $("#NilaiDiskon").val() || 0,
					TglUpdate: $("#tanggal").val(),
					TanggalInvoice: $("#tanggal").val(),
					// Shift : "Pagi",
					IDBank: $("#k_BankID").val(),
					NoKartu: $("#k_CardNo").val(),
					AddCharge_Persen: $("#k_Charge").val(),
					AddCharge: mask_number.currency_remove($("#TaxCC").val()),
					IDBank_2: $("#k_BankID_2").val(),
					NoKartu_2: $("#k_CardNo_2").val(),
					AddCharge_Persen_2: $("#k_Charge_2").val(),
					Tindakan: 0,
					PemeriksaanFisik: 0,
					SewaKamar: 0,
					Perawatan: 0,
					Administrasi: 0,
					Visite: 0,
					Obat: 0,
					BHP: 0,
					Rontgen: 0,
					Lab: 0,
					MOnJantung: 0,
					Lain: 0,
					Poli: 0,
					Imunisasi: 0,
					PPN: 0,
					Closing: 1,
					Audit: 0,
					Batal: 0,
					KelasID: "xx",
					SectionPerawatanID: '<?php echo @$item->section->SectionID ?>',
					JumlahBayar: mask_number.currency_remove($("#JumlahBayar").val()),
					NilaiKembalian: mask_number.currency_remove($("#NilaiKembalian").val()),
					OutStanding: pasienbon,
					NilaiOutStanding: pasienbon ? mask_number.currency_remove($("#Sisa").val()) : 0.00,
					NilaiPembayaranBonPegawai: $('#NilaiPembayaranBonPegawai').val() || 0.00,
					DokterBonID: $('#DokterBonID').val() || null,
				};

				//var _service_group = _form.find("input[class=\"GroupJasa\"]");							
				var _service_group = $(".GroupJasa");
				_service_group.map(function() {
					dataPost[this.id] = this.value;
				});

				dataPost['JenisBayar'] = {
					Tunai: mask_number.currency_remove($("#Tunai").val()) || 0.00,
					DijaminPerusahaan: mask_number.currency_remove($("#Perusahaan").val()) || 0.00,
					KartuKredit: mask_number.currency_remove($("#k_Amount").val()) || 0.00,
					KartuKredit_2: mask_number.currency_remove($("#k_Amount_2").val()) || 0.00,
					Kredit: pasienbon ? mask_number.currency_remove($("#Sisa").val()) : 0.00,
					TagihanLOG: 0,
					Beban: mask_number.currency_remove($("#Beban").val()) || 0.00,
					BPJS: mask_number.currency_remove($("#BPJS").val()) || 0.00,
					BonKaryawan: mask_number.currency_remove($("#NilaiPembayaranBonPegawai").val()) || 0.00,
				};

				dataPost['additional'] = {
					time_start_proccess: '<?php echo date("Y-m-d H:i:s") ?>'
				};

				var table_data = $("#dt_discounts").DataTable().rows().data();
				dataPost['discount'] = {};
				table_data.each(function(value, index) {
					var detail = {
						IDDiscount: value.IDDiscount,
						DokterID: value.IDDokter,
						Persen: value.Persen,
						NilaiDiscount: mask_number.currency_remove(value.NilaiDiskon) || 0.00,
						Keterangan: value.Keterangan,
						NoReg: $("#Noreg").val(),
						JasaID: value.IDJasa,
						KelasID: value.Kelas,
					}

					dataPost['discount'][index] = detail;
				});

				$.post($(this).attr("action"), dataPost, function(response, status, xhr) {
					if ("error" == response.status) {
						$.alert_error(response.message);
						return false
					}
					console.log(response.NoBukti);
					$.alert_success(response.message);
					uninishedTrans = true;

					if ($.isFunction(fn)) {
						dataPost.NoBukti = response.NoBukti
						fn(dataPost);
					} else {
						_form_actions.afterPost(response.NoBukti);
					}

				});
			},
			afterPost: function(dataPost) {
				setTimeout(function() {
					// var nobukti = $("#NoBukti").val();
					var newUrl = "<?php echo base_url('cashier/general-payment/edit'); ?>/" + dataPost.NoBukti;
					window.location.href = newUrl;
				}, 300);
			}
		}

		$(document).ready(function(e) {

			_form_actions.init();

			$("form[name=\"form_general_payment\"]").on("submit", function(e) {
				e.preventDefault();

				if (!confirm("Apakah Anda yakin ingin memproses data ini ?")) {
					return false;
				}

				var dataPost = {};
				if (typeof bpjsBridgingVisite !== 'undefined' && $('#JenisKerjasamaID').val() == 9) {
					_form_actions.post(dataPost, bpjsVisite.post);
				} else {
					_form_actions.post(dataPost, _form_actions.afterPost);
				}

			});

			// DP = Direct Print
			$("#print_invoice_mini").on("click", function(e) {
				data_post = {
					"NoBukti": $("#NoBukti").val()
				}

				$.post('<?= @$print_invoice_mini ?>', data_post, function(response, status, xhr) {
					if ("error" == response.status) {
						$.alert_error(response.status);
						return false
					}

					$.alert_success('Berhasil mencetak.');
					printJS({
						printable: response.data_print,
						type: 'pdf',
						base64: true
					});
				});
			});
		});


	})(jQuery);
	//]]>
</script>