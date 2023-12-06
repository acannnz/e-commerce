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
                <input type="text" id="tanggal" readonly="readonly" name="f[Tanggal]" value="<?php echo $item->cashier->Tanggal ?>" placeholder="" class="form-control" readonly="readonly">
            </div>
            <div class="col-lg-4">
                <input type="text" id="jam" name="f[Jam]" readonly="readonly" value="<?php echo $item->cashier->Jam ?>" placeholder="" class="form-control" readonly="readonly">
            </div>
        </div>
        
         <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('general_payment:registration_number_label') ?></label>
            <div class="col-lg-9">
                <div class="input-group">
                    <input type="text" id="NoReg" name="f[NoReg]" value="<?php echo @$item->NoReg ?>" class="form-control" readonly="readonly">
                    <span class="input-group-btn">
	                    <a href="<?php echo @$lookup_registration ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
	                    <a href="javascript:;" class="btn btn-default" ><i class="fa fa-times"></i></a>
                    </span>
                </div>
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
                <input type="text" id="NRM" name="NRM" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control" maxlength="8" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('general_payment:patient_name_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="NamaPasien" name="NamaPasien" value="<?php echo @$item->NamaPasien_Reg ?>" placeholder="" class="form-control patient" readonly="readonly">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('general_payment:address_label') ?></label>
            <div class="col-lg-9">
                <textarea id="Alamat" name="p[Alamat]" placeholder="" class="form-control patient" readonly="readonly"><?php echo !empty($item->AlamatPasien_Reg) ? $item->AlamatPasien_Reg : @$item->Alamat ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('general_payment:treatment_type_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="TipePerawatan" name="f[TipePerawatan]" value="<?php echo @$item->Status ?>" placeholder="" class="form-control patient" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('general_payment:room_label') ?></label>
            <div class="col-lg-9">
                <input type="hidden" id="SectionID" name="SectionName" value="<?php echo @$item->SectionID ?>" >
                <input type="text" id="SectionName" name="SectionName" value="<?php echo @$item->SectionName ?>" placeholder="" class="form-control patient" readonly="readonly">
            </div>
        </div>          
       <div class="form-group">
           <label class="col-lg-3 control-label">Dokter</label>
           <div class="col-lg-9">
                <div class="input-group">
                    <input type="hidden" id="SupplierID" name="f[DokterID]" value="<?php echo @$item->Kode_Supplier ?>" class="doctor">
                    <input type="text" id="SupplierName" value="<?php echo @$item->Nama_Supplier ?>" placeholder="" class="form-control" readonly="readonly">
                    <span class="input-group-btn">
                        <a href="<?php echo @$lookup_supplier ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
                        <a href="javascript:;" id="clear_doctor" class="btn btn-default" ><i class="fa fa-times"></i></a>
                    </span>
                </div>
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
            <label class="col-lg-4 control-label text-right">Tax Kartu Kredit</label>
            <div class="col-lg-8">
                <strong><input type="text" id="TaxCC" name="TaxCC" value="<?php echo !empty($item->cashier->AddCharge) ? number_format(@$item->cashier->AddCharge, 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-right" readonly="readonly"></strong>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label text-right">Grand Total</label>
            <div class="col-lg-8">
                <strong><input style="font-size:20px" type="text" id="GrandTotal" name="GrandTotal" value="<?php echo  !empty($item->cashier->Nilai) ? number_format(@$item->cashier->Nilai, 2, '.', ',') : 0.00; ?>" placeholder="" class="form-control text-right" readonly="readonly"></strong>
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
                <a href="<?php echo $print_cost_breakdown ?>" id="print_cost_breakdown" target="_blank" class="btn btn-success col-lg-12" value="1"><i class="fa fa-search"></i> <?php echo lang( 'general_payment:preview_detail_label' ) ?></a>
            </div>
        </div>
        <div class="form-group">
        	<div class="col-lg-12">
            <?php if(@$item->status_bayar == 'Belum'){ 
					if(@$item->item->Batal == 1){
				?>
                    <h1 align="center" class="text-danger"><?php echo "Transaksi di Batalkan" ?></h1>
                <?php }else{ ?>
                	<h1 align="center" class="text-warning"><?php echo @$item->status_bayar." Bayar" ?></h1>
                <?php } ?>
			<?php }elseif(@$item->status_bayar == 'Sudah Bayar'){ ?>
                <h1 align="center" class="text-success"><?php echo @$item->status_bayar ?></h1>
            <?php }elseif(@$item->status_bayar == 'Pembayaran Baru'){ ?>
                <h1 align="center" class="text-danger"><?php echo @$item->status_bayar ?></h1>
            <?php }else{ ?>
            	<h1 align="center" class="text-danger"><?php echo "" ?></h1>
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
                <li><a href="#general-payment-tab1" data-toggle="tab"><i class="fa fa-stethoscope"></i> <?php echo lang("general_payment:discount_label") ?></a></li>
                <li><a href="#general-payment-tab2" data-toggle="tab"><i class="fa fa-medkit"></i> <?php echo lang("general_payment:payment_label") ?></a></li>
            </ul>
            <div class="tab-content">
                <div id="general-payment-tab1" class="tab-pane tab-pane-padding active">
                	<?php echo modules::run("cashier/general-payments/discount/index", @$item ) ?>
                </div>
                <div id="general-payment-tab2" class="tab-pane tab-pane-padding">
                	<?php echo modules::run("cashier/general-payments/payment/index", @$item ) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-lg-12 text-right">
        	<button type="submit" class="btn btn-primary btn_save"><?php echo lang( 'buttons:submit' ) ?></button>
            <a href="<?php echo @$print_invoice ?>" id="print_invoice" class="btn btn-success" target="_blank"><i class="fa fa-print"></i> Print Invoice</a>
            <a href="<?php echo @$print_kwitansi ?>" id="print_kwitansi" class="btn btn-success" target="_blank"><i class="fa fa-print"></i> Print Kwitansi</a>
            <a href="<?php echo @$lookup_cancel ?>" data-toggle="lookup-ajax-modal" class="btn btn-danger" ><i class="fa fa-trash-o"></i> <?php echo lang("buttons:cancel") ?></a>
            <button class="btn btn-warning" onclick="window.location.href='<?php echo base_url("cashier/general-payment") ?>'; return false;"><?php echo lang( 'buttons:back' ) ?></button>
    </div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$( document ).ready(function(e) {			
				
				$("form[name=\"form_general_payment\"]").on("submit", function(e){
					e.preventDefault();	
					
					if ( !confirm("Apakah Anda yakin ingin memproses data ini ?") )
					{
						return false;
					}
					
					var Sisa = parseFloat( $("#Sisa").val() );
					if( Sisa > 0 ){
						alert('Masih terdapat Sisa pembayaran!!');
						return false;
					}

					var pasienbon = $("#PasienBon:checked").val() || 0;
					
					var data_post = {};
					data_post['DataTransaction'] = {
							"RJ" : $("#TipePerawatan").val(),
							"DokterID" : $("#SupplierID").val(),
							"FromDate" : $("#TglReg").val(),
							"ToDate" : $("#tanggal").val(),
							"Nilai" : $("#Nilai").val(),
							"NilaiOrig" : $("#Nilai").val(),
							"NilaiDiscount" : $("#NilaiDiscount").val() || 0,
							"TglUpdate" : $("#tanggal").val(),
							"TanggalInvoice" : $("#tanggal").val(),
							"Shift": "Pagi",
							"OutStanding": pasienbon,
							"NoKartu": $("#k_CardNo").val(),
							"AddCharge_Persen" : $("#k_Charge").val(),
							"AddCharge": $("#TaxCC").val(),
							"IDBank": $("#k_BankID").val(),
							"Tindakan" : $("#tindakan").val(),
							"PemeriksaanFisik" : $("#pemeriksaan").val(),
							"SewaKamar" : $("#sewa").val(),
							"Perawatan" : $("#perawatan").val(),
							"Administrasi" : $("#administrasi").val(),
							"Visite" : $("#visite").val(),
							"Obat": $("#obat").val(),
							"BHP": $("#bhp").val(),
							"Rontgen": $("#rontgen").val(),
							"Lab": $("#lab").val(),
							"MOnJantung": $("#jantung").val(),
							"Lain": $("#lain").val(),
							"Poli": $("#poli").val(),
							"Imunisasi" : $("#imunisasi").val(),
							"PPN": 0,
							"Closing": 0,
							"Audit" : 0,
							"Batal" : 0,
							"KelasID" : "xx",
							"SectionPerawatanID" : '<?php echo @$item->section->SectionID ?>',
							"NilaiSusuk" : parseFloat( $("#Pembayaran").val().replace(/[^0-9\.-]+/g,"") ).toFixed(2),
							"NilaiOutStanding" : pasienbon ? parseFloat( $("#Bon").val().replace(/[^0-9\.-]+/g,"") ).toFixed(2) : 0.00,
						};
						
					data_post['JenisBayar'] = {
							"Tunai" : parseFloat( $("#Tunai").val().replace(/[^0-9\.-]+/g,"") ).toFixed(2) || 0.00,
							"DijaminPerusahaan": parseFloat( $("#Perusahaan").val().replace(/[^0-9\.-]+/g,"")).toFixed(2) || 0.00,
							"KartuKredit": parseFloat( $("#Kartu").val().replace(/[^0-9\.-]+/g,"") ).toFixed(2) || 0.00,
							"Kredit" : parseFloat( $("#Bon").val().replace(/[^0-9\.-]+/g,"") ).toFixed(2) || 0.00,
							"TagihanLOG":0,
							"Beban": parseFloat( $("#Beban").val().replace(/[^0-9\.-]+/g,"") ).toFixed(2) || 0.00,
							"BPJS": parseFloat( $("#BPJS").val().replace(/[^0-9\.-]+/g,"") ).toFixed(2) || 0.00,	
							"BonKaryawan":0,
						};
						
					data_post['additional'] = {
							"time_start_proccess" : '<?php echo date("Y-m-d H:i:s") ?>'
						};
					
					var details = {};
					
					var table_data = $( "#dt_discounts" ).DataTable().rows().data();
					
					data_post['discount'] = {};					
					table_data.each(function (value, index) {
						var detail = {
							"NoBukti" : $("#NoBukti").val(),
							"IDDiscount"	: value.IDDiscount,
							"DokterID" : value.IDDokter,
							"Persen" : value.Persen,
							"NilaiDiscount" : parseFloat( value.NilaiDiskon.replace(/[^0-9\.-]+/g,"") ).toFixed(2) || 0.00,
							"Keterangan" : value.Keterangan,
							"NoReg" : $("#Noreg").val(),
							"JasaID" : value.IDJasa,
							"KelasID" : value.Kelas,
						}
						
						data_post['discount'][index]= detail;
					});
					
					//console.log(data_post);	

					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
						
						var response = $.parseJSON(response);
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success(response.message);
						
						var NoBukti = response.NoBukti;
						setTimeout(function(){
							document.location.href = "<?php echo base_url("cashier/general-payment/edit"); ?>/"+ NoBukti;
							}, 2500 );
							
					})	
					
				});
				
				$( "#print_kwitansi" ).on( "click", function() {
					if( !confirm( "Cetak Kwitansi ?" ) ){
						return false;
					}
				});
				
				$( "#print_cost_breakdown" ).on( "click", function() {
					if( !confirm( "Lihat Rincian Biaya ?" ) ){
						return false;
					}
				});
				
				$( "#print_invoice" ).on( "click", function() {
					if( !confirm( "Cetak Invoice Pembayaran ?" ) ){
						return false;
					}
				});
				
		});
	})( jQuery );
//]]>
</script>