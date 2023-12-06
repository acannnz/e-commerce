<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//print_r($item->group_detail_cost);exit;
?>
<?php if (!empty($item->group_detail_cost)): foreach(@$item->group_detail_cost as $row): ?>
	<input type="hidden" id="tindakan" value="<?php echo ($row->GroupJasa == 'Tindakan')? $row->Nilai : '0' ?>" />
	<input type="hidden" id="pemeriksaan" value="<?php echo ($row->GroupJasa == 'Pemeriksaan Fisik')? $row->Nilai : '0' ?>" />
	<input type="hidden" id="sewa" value="<?php echo ($row->GroupJasa == 'Sewa Kamar')? $row->Nilai : '0' ?>" />
	<input type="hidden" id="perawatan" value="<?php echo ($row->GroupJasa == 'Perawatan')? $row->Nilai : '0' ?>" />
	<input type="hidden" id="administrasi" value="<?php echo ($row->GroupJasa == 'Administrasi')? $row->Nilai : '0' ?>" />
	<input type="hidden" id="visite" value="<?php echo ($row->GroupJasa == 'Visite Dokter')? $row->Nilai : '0' ?>" />
	<input type="hidden" id="obat" value="<?php echo ($row->GroupJasa == 'Obat')? $row->Nilai : '0' ?>" />
	<input type="hidden" id="bhp" value="<?php echo ($row->GroupJasa == 'BHP')? $row->Nilai : '0' ?>" />
	<input type="hidden" id="rontgen" value="<?php echo ($row->GroupJasa == 'Radiologi')? $row->Nilai : '0' ?>" />
	<input type="hidden" id="lab" value="<?php echo ($row->GroupJasa == 'Laboratorium')? $row->Nilai : '0' ?>" />
	<input type="hidden" id="jantung" value="<?php echo ($row->GroupJasa == 'MOnJantung')? $row->Nilai : '0' ?>" />
	<input type="hidden" id="lain" value="<?php echo ($row->GroupJasa == 'lain')? $row->Nilai : '0' ?>" />
	<input type="hidden" id="poli" value="<?php echo ($row->GroupJasa == 'poli')? $row->Nilai : '0' ?>" />
	<input type="hidden" id="imunisasi" value="<?php echo ($row->GroupJasa == 'imunisasi')? $row->Nilai : '0' ?>" />
<?php endforeach; endif; ?>

<div class="row">
	<div class="col-md-4">
		<div class="page-subtitle">
			<h3>Data Kasir</h3>
			<p>Informasi Data Kasir Pasien</p>
		</div>
		<div class="form-group">
			<label class="col-md-5 control-label">Nilai</label>
			<div class="col-md-7">
				<strong><input type="text" id="Nilai" name="f[Nilai]" value="<?php echo !empty($item->total_cost) ? number_format(@$item->total_cost->Nilai, 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-right" readonly></strong>
			</div>
			<?php /*?><div class="col-md-5">
				<div class="checkbox">
					<input type="checkbox" name="combination_invoice" id="combination_invoice" /><label for="combination_invoice">Invoice Gabung</label>
				</div>
			</div><?php */?>
		</div>
		<div class="form-group">
			<label class="col-md-5 control-label">Nilai Discount</label>
			<div class="col-md-7">
				<strong><input type="text" id="NilaiDiskon" name="NilaiDiskon" value="<?php echo @$item->NilaiDiskon ?>" placeholder="" class="form-control text-warning text-right" readonly></strong>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-5 control-label">Sub Total</label>
			<div class="col-md-7">
				<strong><input type="text" id="SubTotal" name="SubTotal" value="<?php echo !empty($item->total_cost) ? number_format(@$item->total_cost->Nilai, 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-right" readonly></strong>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-5 control-label">Tax Kartu Kredit</label>
			<div class="col-md-7">
				<strong><input type="text" id="TaxCC" name="TaxCC" value="0" placeholder="" class="form-control text-right" readonly></strong>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-5 control-label">Grand Total</label>
			<div class="col-md-7">
				<strong><input  type="text" id="GrandTotal" name="GrandTotal" value="<?php echo  !empty($item->total_cost) ? number_format(@$item->total_cost->Nilai, 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-right" readonly></strong>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-5 control-label">Total Pembayaran</label>
			<div class="col-md-7">
				<strong><input  type="text" id="Pembayaran" name="" value="<?php echo  !empty($item->total_cost) ? number_format(@$item->total_cost->Nilai, 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-success text-right" readonly></strong>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-5 control-label">Sisa</label>
			<div class="col-md-7">
				<strong><input  type="text" id="Sisa" name="" value="<?php echo  !empty($item->total_cost) ? number_format(@$item->total_cost->Nilai, 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-danger text-right" readonly></strong>
			</div>
		</div>		
		<div class="form-group">
			<div class="col-lg-12">
			<?php if(@$item->Batal == 1){ ?>
					<h3 class="text-danger text-right"><?php echo "Transaksi Sudah di Batalkan." ?></h3>
			<?php } ?>
			</div>
		</div>
	</div>
	
	<div class="col-md-4">	
		<div class="page-subtitle">
			<h3>Data Jumlah Bayar</h3>
			<p>Informasi Data Jumlah di Bayar</p>
		</div>
		<div class="form-group">
			<label class="col-lg-5 control-label">TUNAI</label>
			<div class="col-lg-7">
				<strong><input type="text" id="JumlahBayar" name="JumlahBayar" value="<?php echo number_format(@$item->JumlahBayar, 2, '.', ',') ?>" placeholder="" class="form-control text-success text-right mask-number" autofocus autocomplete="off"></strong>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-5 control-label">Kembalian</label>
			<div class="col-lg-7">
				<strong><input  type="text" id="NilaiKembalian" name="NilaiKembalian" value="<?php echo number_format(@$item->NilaiKembalian, 2, '.', ',') ?>" placeholder="" class="form-control text-success text-right mask-number" readonly></strong>
			</div>
		</div>
	</div>
	
	<div class="col-md-4">	
		<div class="page-subtitle">
			<h3>Metode Pembayaran Lain</h3>
			<p>Informasi Metode Pembayaran Lain</p>
		</div>
		<?php /*?><div class="form-group">
			<label class="col-lg-5 control-label">NILAI TRANSAKSI TUNAI</label>
			<div class="col-lg-7">
				<strong><input type="hidden" id="Tunai" name="Tunai" value="<?php echo  !empty($collection[4]) ? number_format($collection[4], 2, ".", ",") : number_format(@$item->total_cost->Nilai, 2, '.', ',') ?>" placeholder="" class="form-control text-success text-right payment-type mask-number"></strong>
			</div>
		</div><?php */?>
		<input type="hidden" id="Tunai" name="Tunai" value="<?php echo  !empty($collection[4]) ? number_format($collection[4], 2, ".", ",") : 0.00; ?>" >
		<div class="form-group">
			<label class="col-lg-5 control-label">KARTU KREDIT/DEBIT</label>
			<div class="col-lg-7">
				<input type="hidden" id="k_BankID" name="k[BankID]" value="<?php echo  @$item->cashier->IDBank ?>" placeholder="" class="credit-card">
				<input type="hidden" id="k_BankName" name="k[BankName]" value="" placeholder="" class="credit-card">
				<input type="hidden" id="k_CardNo" name="k[CardNo]" value="<?php echo  @$item->cashier->NoKartu ?>" placeholder="" class="credit-card">
				<input type="hidden" id="k_Amount" name="k[amount]" value="<?php echo  (@$item->cashier->AddCharge_Persen > 0) ? $collection[7] - ($collection[7] * $item->cashier->AddCharge_Persen / 100 ) : 0.00 ?>" placeholder="" class="credit-card">
				<input type="hidden" id="k_Charge" name="k[charge]" value="<?php echo  !empty($item->cashier->AddCharge_Persen) ? $item->cashier->AddCharge_Persen : 0.00 ?>" placeholder="" class="credit-card">
				<input type="hidden" id="k_Total" name="k[total]" value="<?php echo  !empty($collection[7]) ? number_format($collection[7], 2, '.', ',') : 0.00; ?>" placeholder="" class="credit-card">
				<div class="input-group">
					<strong><input  type="text" id="Kartu" name="Kartu" value="<?php echo  !empty($collection[7]) ? number_format($collection[7], 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-success text-right payment-type credit-card mask-number" readonly></strong>
					<span class="input-group-btn">
						<a href="<?php echo @$lookup_form_credit_card ?>" id="merchan" data-toggle="form-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
						<a href="javascript:;" class="btn btn-default btn-clear" date-target=".credit-card" ><i class="fa fa-times"></i></a>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-5 control-label">DIJAMIN BPJS</label>
			<div class="col-lg-7">
				<strong><input type="text" id="BPJS" name="BPJS" data-jenisid="9" value="<?php echo  !empty($collection[13]) ? number_format($collection[13], 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-success text-right payment-type mask-number" autocomplete="off"></strong>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-5 control-label">PERUSAHAAN</label>
			<div class="col-lg-7">
				<strong><input  type="text" id="Perusahaan" name="Perusahaan" data-jenisid="2" value="<?php echo  !empty($collection[5]) ? number_format($collection[5], 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-success text-right payment-type mask-number" autocomplete="off"></strong>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-5 control-label">KREDIT / BON</label>
			<div class="col-lg-7">
				<strong><input  type="text" id="Bon" name="Bon" value="<?php echo  !empty($collection[9]) ? number_format($collection[9], 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-success text-right payment-type mask-number" autocomplete="off"></strong>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-5 control-label">BEBAN/KEUNTUNGAN RS</label>
			<div class="col-lg-7">
				<strong><input type="text" id="Beban" name="Beban" value="<?php echo  !empty($collection[12]) ? number_format($collection[12], 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-success text-right payment-type mask-number" autocomplete="off"></strong>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-5 control-label">Opsi</label>
            <div class="col-lg-7">
            	<div class="checkbox">
                  <input type="checkbox" id="PasienBon" name="f[PasienBon]" value="1" <?php echo (@$item->cashier->OutStanding > 0) ? "checked" : NULL;?>><label for="PasienBon" class="control-label">&nbsp; Pasien BON</label>
                </div>
            </div>
		</div>
    </div>
</div>

<script type="text/javascript">

var payment_actions = {
		init : function (){
			$(".payment-type, #JumlahBayar").on("keyup", function(e){
				JenisKerjasamaID = $(this).data("jenisid");
				
				if ( JenisKerjasamaID == 9 && JenisKerjasamaID != <?php echo (int) @$item->JenisKerjasamaID ?> )
				{
					e.preventDefault();
					alert("Tidak Dapat Menggunakan Tipe Pembayaran, Karena berbeda tipe pasien");
					$(this).val(0.00);
					return false;
				}

				if ( JenisKerjasamaID == 2 && JenisKerjasamaID != <?php echo (int) @$item->JenisKerjasamaID ?> )
				{
					e.preventDefault();
					alert("Tidak Dapat Menggunakan Tipe Pembayaran, Karena berbeda tipe pasien");
					$(this).val(0.00);
					return false;
				}

				payment_actions.calculate_payment();

			});
				
			$(".payment-type, #JumlahBayar").on("blur",function(){					
				payment_actions.calculate_payment();
            });
			
				
		},
		calculate_payment : function(){
			var _form = $( "form[name=\"form_general_payment\"]" );
			var TaxCC = _form.find( "input[id=\"TaxCC\"]" );
			var GrandTotal = _form.find( "input[id=\"GrandTotal\"]" );
			var Pembayaran = _form.find( "input[id=\"Pembayaran\"]" );
			var k_Amount = _form.find( "input[id=\"k_Amount\"]" );
			var Tunai = _form.find( "input[id=\"Tunai\"]" );
			var Sisa = _form.find( "input[id=\"Sisa\"]" );
			var JumlahBayar = _form.find( "input[id=\"JumlahBayar\"]" );
			var NilaiKembalian = _form.find( "input[id=\"NilaiKembalian\"]" );
			var Total = 0 , Sisa_ = 0, TaxCC_ = 0;
			
			$(".payment-type").each(function(index, element) {
				element.value = element.value || 0;
				is_credit_card = $(this).hasClass("credit-card"); 
				if ( is_credit_card ){
					TaxCC_ = mask_number.currency_remove(element.value) - mask_number.currency_remove(k_Amount.val());
					SubTotal = mask_number.currency_remove(k_Amount.val());
				} else {
					SubTotal = mask_number.currency_remove(element.value);
				}
				
				console.log("SubTotal : ", SubTotal);
				Total = Total + SubTotal;				
			});
			console.log("Total : ", Total);
			
			var GrandTotal_ = mask_number.currency_remove(GrandTotal.val());
			TaxCC.val( mask_number.currency_add(TaxCC_));					
			
			var JumlahBayar_ = mask_number.currency_remove(JumlahBayar.val());
			var Pembayaran_ = Total + JumlahBayar_;
			Pembayaran_ = Pembayaran_ > GrandTotal_ ? GrandTotal_ : Pembayaran_;
			Pembayaran.val(mask_number.currency_add( Pembayaran_ ));
			
			var Sisa_ = GrandTotal_ - Pembayaran_;
			Sisa.val( mask_number.currency_add(Sisa_));
			
			NilaiKembalian.val(0.00);
			var NilaiKembalian_ = JumlahBayar_ - (GrandTotal_ - Total) || 0;
			if( JumlahBayar_ > 0 && NilaiKembalian_ > 0 && JumlahBayar_ > NilaiKembalian_){
				NilaiKembalian.val(mask_number.currency_add(NilaiKembalian_));
			}
			
			NilaiKembalian_  = mask_number.currency_remove(NilaiKembalian.val());
			Tunai.val(mask_number.currency_add(JumlahBayar_ - NilaiKembalian_));
		},
		on_edit_calculate_payment : function(){
			var _form = $( "form[name=\"form_general_payment\"]" );
			var TaxCC = _form.find( "input[id=\"TaxCC\"]" );
			var GrandTotal = _form.find( "input[id=\"GrandTotal\"]" );
			var Pembayaran = _form.find( "input[id=\"Pembayaran\"]" );
			var k_Amount = _form.find( "input[id=\"k_Amount\"]" );
			var Tunai = _form.find( "input[id=\"Tunai\"]" );
			var Sisa = _form.find( "input[id=\"Sisa\"]" );
			var JumlahBayar = _form.find( "input[id=\"JumlahBayar\"]" );
			var NilaiKembalian = _form.find( "input[id=\"NilaiKembalian\"]" );
			var Total = 0 , Sisa_ = 0, TaxCC_ = 0;
			
			$(".payment-type").each(function(index, element) {
				element.value = element.value || 0;
				is_credit_card = $(this).hasClass("credit-card"); 
				if ( is_credit_card ){
					SubTotal = mask_number.currency_remove(element.value) - mask_number.currency_remove(TaxCC.val());
				} else {
					SubTotal = mask_number.currency_remove( element.value);
				}
				
				console.log("SubTotal : ", SubTotal);
				Total = Total + SubTotal;				
			});
			
			console.log("Total : ", Total);
			Pembayaran.val(mask_number.currency_add( Total ));
			
			Sisa_ = mask_number.currency_remove(GrandTotal.val()) - Total;
			Sisa.val(mask_number.currency_add( Sisa_ ));
		
		},
	};

//<![CDATA[
(function( $ ){
	$(document).ready(function() {
		
		payment_actions.init();
		
		<?php if (@$is_edit) :?>
		payment_actions.calculate_payment();
		<?php endif;?>
		
	});
})( jQuery );
//]]>
</script>