<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (@$is_edit)
{
	$date = DateTime::createFromFormat("Y-m-d H:i:s.u", $item->cashier->Tanggal);
	$time = DateTime::createFromFormat("Y-m-d H:i:s.u", $item->cashier->Jam ); 
	
	$item->cashier->Tanggal = $date->format('Y-m-d');
	$item->cashier->Jam = $time->format('H:i:s');
}

?>
<?php echo form_open( current_url(), array("name" => "form_general_payment", "id"=>"form_general_payment") ); ?>
<div class="row form-group">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('general_payment:evidence_number_label') ?></label>
            <div class="col-lg-4">
                <input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->cashier->NoBukti ?>" placeholder="" class="form-control" required readonly="readonly">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('general_payment:date') ?></label>
            <div class="col-lg-4">
                <input type="text" id="tanggal" readonly="readonly" name="f[Tanggal]" value="<?php echo $item->cashier->Tanggal ?>" placeholder="" class="form-control" required>
            </div>
            <div class="col-lg-4">
                <input type="text" id="jam" name="f[Jam]" readonly="readonly" value="<?php echo $item->cashier->Jam ?>" placeholder="" class="form-control" required>
            </div>
        </div>
        
         <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('general_payment:registration_number_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="NoReg" name="f[NoReg]" value="<?php echo @$item->NoReg ?>" class="form-control" readonly="readonly">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('general_payment:date_reg') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" id="TglReg" name="TglReg" value="<?php echo substr(@$item->JamReg, 0, 19) ?>" placeholder="" class="form-control" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('general_payment:nrm_label') ?></label>
            <div class="col-lg-4">
                <input type="text" id="NRM" name="NRM" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control" maxlength="8">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('general_payment:patient_name_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="NamaPasien" name="NamaPasien" value="<?php echo @$item->NamaPasien_Reg ?>" placeholder="" class="form-control patient" disabled>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('general_payment:address_label') ?></label>
            <div class="col-lg-9">
                <textarea id="Alamat" name="p[Alamat]" placeholder="" class="form-control patient" disabled><?php echo !empty($item->AlamatPasien_Reg) ? $item->AlamatPasien_Reg : @$item->Alamat ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('general_payment:treatment_type_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="TipePerawatan" name="f[TipePerawatan]" value="<?php echo @$item->Status ?>" placeholder="" class="form-control patient" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('general_payment:room_label') ?></label>
            <div class="col-lg-9">
                <input type="hidden" id="SectionID" name="SectionName" value="<?php echo @$item->SectionID ?>" >
                <input type="text" id="SectionName" name="SectionName" value="<?php echo @$item->SectionName ?>" placeholder="" class="form-control patient" disabled>
            </div>
        </div>          
       <div class="form-group">
           <label class="col-lg-3 control-label">Dokter</label>
           <div class="col-lg-9">
                <input type="hidden" id="SupplierID" name="f[DokterID]" value="<?php echo @$item->Kode_Supplier ?>" class="doctor">
                <input type="text" id="SupplierName" value="<?php echo @$item->Nama_Supplier ?>" placeholder="" class="form-control" disabled="disabled">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-4 control-label text-right">Nilai</label>
            <div class="col-lg-8">
                <strong><input type="text" id="Nilai" name="f[Nilai]" value="<?php echo !empty($item->cashier->Nilai) ? number_format(@$item->cashier->Nilai, 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-right" readonly="readonly"></strong>
            </div>
            <?php /*?><div class="col-lg-4">
            	<div class="checkbox">
                	<input type="checkbox" name="combination_invoice" id="combination_invoice" /><label for="combination_invoice">Invoice Gabung</label>
                </div>
            </div><?php */?>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label text-right">Nilai Discount</label>
            <div class="col-lg-8">
                <strong><input type="text" id="NilaiDiskon" name="NilaiDiskon" value="<?php echo !empty($item->cashier->NilaiDiscount) ? number_format( $item->cashier->NilaiDiscount, 2, '.', ',') : 0.00 ?>" placeholder="" class="form-control text-warning text-right" readonly="readonly"></strong>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label text-right">Sub Total</label>
            <div class="col-lg-8">
                <strong><input type="text" id="SubTotal" name="SubTotal" value="<?php echo !empty($item->cashier->Nilai) ? number_format(@$item->cashier->Nilai, 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-right" readonly="readonly"></strong>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label text-right">Grand Total</label>
            <div class="col-lg-8">
                <strong><input type="text" id="GrandTotal" name="GrandTotal" value="<?php echo  !empty($item->cashier->Nilai) ? number_format(@$item->cashier->Nilai, 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-right" readonly="readonly"></strong>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label text-right">Tax Kartu Kredit</label>
            <div class="col-lg-8">
                <strong><input type="text" id="TaxCC" name="TaxCC" value="0" placeholder="" class="form-control text-right" readonly="readonly"></strong>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label text-right">Pembayaran</label>
            <div class="col-lg-8">
                <strong><input style="font-size:20px" type="text" id="Pembayaran" name="" value="<?php echo  !empty($item->cashier->Nilai) ? number_format(@$item->cashier->Nilai, 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-success text-right" readonly="readonly"></strong>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label text-right">Sisa</label>
            <div class="col-lg-8">
                <strong><input style="font-size:20px" type="text" id="Sisa" name="" value="0.00" placeholder="" class="form-control text-danger text-right" readonly="readonly"></strong>
            </div>
        </div>
        
        <div class="form-group">
        	<label class="col-lg-4 control-label text-right">&nbsp;</label>
            <div class="col-lg-8">
                <button type="reset" id="print_jasa" class="btn btn-success col-lg-12" value="1"><i class="fa fa-file-text fa-8x">&nbsp;</i><?php echo lang( 'general_payment:priview_detail' ) ?></button>
            </div>
        </div>
        <div class="form-group">
        	<div class="col-lg-offset-4 col-lg-8">
			<?php if(@$item->cashier->Batal == 1 ){ ?>
                <h3 class="text-danger">DATA INI SUDAH DIBATALKAN.</h3>
			<?php } if(@$item->cashier->Audit == 1 ){ ?>
                <h3 class="text-danger">DAT INI SUDAH DIAUDIT.</h3>
            <?php } if(@$item->cashier->Closing == 1 ){ ?>
                <h3 class="text-danger">DATA INI SUDAH DICLOSING.</h3>
            <?php } ?>
            </div>
        </div>
    </div>
</div>

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

<div class="row form-group">	
    <div class="panel panel-default">
        <div class="panel-body">
            <ul id="tab-general_payment" class="nav nav-tabs nav-justified">
                <li class="active"><a href="#general-payment-tab1" data-toggle="tab"><i class="fa fa-stethoscope"></i> <?php echo lang("general_payment:discount_label") ?></a></li>
                <li><a href="#general-payment-tab2" data-toggle="tab"><i class="fa fa-medkit"></i> <?php echo lang("general_payment:payment_label") ?></a></li>
            </ul>
            <div class="tab-content">
                <div id="general-payment-tab1" class="tab-pane tab-pane-padding active">
                	<?php echo modules::run("cashier/general-payments/discount/view", @$item ) ?>
                </div>
                <div id="general-payment-tab2" class="tab-pane tab-pane-padding">
                	<?php echo modules::run("cashier/general-payments/payment/view", @$item ) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-lg-12 text-right">
        <a id="print_invoice" class="btn btn-success" value="1"><i class="fa fa-file fa-8x">&nbsp;</i>Print Invoice</a>
        <a id="print_kwitansi" class="btn btn-success" value="1"><i class="fa fa-money fa-8x">&nbsp;</i>Print Kwitansi</a>
        <button class="btn btn-warning" onclick="window.location.href='<?php echo base_url("cashier/general-payment") ?>'; return false;"><?php echo lang( 'buttons:back' ) ?></button>
    </div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$( document ).ready(function(e) {			
							
				var noreg = $("#Noreg").val();
				var billObat = 'Obat';
				var billService = 'Jasa';
				var nobukti = $("#NoBukti").val();
				var totalan = ($("#Pembayaran").val()).replace('.','');
				var nrm = $("#NRM").val();
				$( "#print_kwitansi" ).on( "click", function() {
					if( confirm( "Cetak Rincian ?" ) ){
						window.open("<?php echo base_url() ?>cashier/print_billing/print_kwitansi/" + nrm + "/" + nobukti + "/" + totalan);
					}
				});
				
				$( "#print_jasa" ).on( "click", function() {
					if( confirm( "Cetak Rincian ?" ) ){
						window.open("<?php echo base_url() ?>cashier/print_billing/detail_biaya/" + noreg + "/" + nrm + "/" + totalan);
					}
				});
				
				$( "#print_invoice" ).on( "click", function() {
					if( confirm( "Invoice Pembayaran ?" ) ){
						window.open("<?php echo base_url() ?>cashier/print_billing/print_invoice/" + noreg + "/" + nrm + "/" + totalan);
					}
				});
				
		});
	})( jQuery );
//]]>
</script>