<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="row form-group">
	<div class="col-lg-6">	
	<div class="form-group">
        <label class="col-lg-5 control-label">TUNAI</label>
        <div class="col-lg-7">
            <strong><input style="font-size:20px" type="text" id="Tunai" name="Tunai" value="<?php echo  !empty($collection[4]) ? number_format($collection[4], 2, '.', ',') : number_format(@$item->total_cost->Nilai, 2, '.', ',') ?>" placeholder="" class="form-control text-success" readonly="readonly"></strong>
        </div>
    </div>
	<div class="form-group">
        <label class="col-lg-5 control-label">KARTU KREDIT/DEBIT</label>
        <div class="col-lg-7">
            <input type="hidden" id="k_BankID" name="k[BankID]" value="<?php echo  @$item->cashier->IDBank ?>" placeholder="" class="credit-card">
            <input type="hidden" id="k_BankName" name="k[BankName]" value="" placeholder="" class="credit-card">
            <input type="hidden" id="k_CardNo" name="k[CardNo]" value="<?php echo  @$item->cashier->NoKartu ?>" placeholder="" class="credit-card">
            <input type="hidden" id="k_Amount" name="k[amount]" value="<?php echo  ($item->cashier->AddCharge_Persen > 0) ? $collection[7] - ($collection[7] * $item->cashier->AddCharge_Persen / 100 ) : 0.00 ?>" placeholder="" class="credit-card">
            <input type="hidden" id="k_Charge" name="k[charge]" value="<?php echo  !empty($item->cashier->AddCharge_Persen) ? $item->cashier->AddCharge_Persen : 0.00 ?>" placeholder="" class="credit-card">
            <input type="hidden" id="k_Total" name="k[total]" value="<?php echo  !empty($collection[7]) ? number_format($collection[7], 2, '.', ',') : 0.00; ?>" placeholder="" class="credit-card">
            <strong><input style="font-size:20px" type="text" id="Kartu" name="Kartu" value="<?php echo  !empty($collection[7]) ? number_format($collection[7], 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-success" readonly="readonly"></strong>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-5 control-label">DIJAMIN BPJS</label>
        <div class="col-lg-7">
            <strong><input style="font-size:20px" type="text" id="BPJS" name="BPJS" data-jenisid="9" value="<?php echo  !empty($collection[13]) ? number_format($collection[13], 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-success" readonly="readonly"></strong>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-5 control-label">DIJAMIN KE PERUSAHAAN</label>
        <div class="col-lg-7">
            <strong><input style="font-size:20px" type="text" id="Perusahaan" name="Perusahaan" data-jenisid="2" value="<?php echo  !empty($collection[5]) ? number_format($collection[5], 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-success" readonly="readonly"></strong>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-5 control-label">KREDIT / BON</label>
        <div class="col-lg-7">
            <strong><input style="font-size:20px" type="text" id="Bon" name="Bon" value="<?php echo  !empty($collection[9]) ? number_format($collection[9], 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-success" readonly="readonly"></strong>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-5 control-label">BEBAN/KEUNTUNGAN RS</label>
        <div class="col-lg-7">
            <strong><input style="font-size:20px; padding:10px" type="text" id="Beban" name="Beban" value="<?php echo  !empty($collection[12]) ? number_format($collection[12], 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-success" readonly="readonly"></strong>
        </div>
    </div>
    </div>
    <div class="col-lg-6">
    	<div class="form-group">
            <div class="col-lg-3">
            	<div class="checkbox">
                  <input type="checkbox" id="PasienBon" name="f[PasienBon]" value="1" disabled="disabled"><label for="PasienBon" class="control-label">&nbsp; Pasien BON </label>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
	$(document).ready(function() {
				
	});
})( jQuery );
//]]>
</script>