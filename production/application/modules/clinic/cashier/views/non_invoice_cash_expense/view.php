<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php echo form_open( current_url(), array("name" => "form_non_invoice_cash_expense", "id"=>"form_non_invoice_cash_expense") ); ?>
<div class="row form-group">
	<div class="col-md-6">
        <div class="page-subtitle">
            <div class="col-md-12">
                <h3><?php echo lang('non_invoice_cash_expense:general_data_subtitle') ?></h3>
                <p><?php echo lang('non_invoice_cash_expense:general_data_subtitle_helper') ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:evidence_number_label') ?></label>
            <div class="col-lg-7">
                <input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" required readonly="readonly">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:date_label') ?></label>
            <div class="col-lg-7">
                <input type="text" id="Jam" name="Jam" value="<?php echo date("Y-m-d H:m:s") ?>"  class="form-control" readonly="readonly" />
            </div>
        </div>
        

        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:section_label') ?></label>
            <div class="col-lg-7">
                <input type="text" id="SectionName" name="p[SectionName]" value="<?php echo @$section->SectionName?>" placeholder="" class="form-control " readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:user_label') ?></label>
            <div class="col-lg-7">
                <input type="text" id="Nama_Singkat" name="p[Nama_Singkat]" value="<?php echo @$user->Nama_Singkat ?>" placeholder="" class="form-control" readonly="readonly">
            </div>
        </div>
    </div>    
    
    <div class="col-md-6">
        <div class="page-subtitle">
            <div class="col-md-12">
                <h3><?php echo lang('non_invoice_cash_expense:transaction_data_subtitle') ?></h3>
                <p><?php echo lang('non_invoice_cash_expense:transaction_data_subtitle_helper') ?></p>
            </div>
        </div>
        <?php /*?><div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:type_label') ?></label>
            <div class="col-lg-9">
            	<select id="TipeTransaksi" name="f[TipeTransaksi]" class="form-control" disabled="disabled">
                	<option value="KAS" data-type="" <?php echo $item->TipeTransaksi == "KAS" ? "selected" : NULL ?>>KAS</option>
                    <option value="KARTU KREDIT" data-type=".merchan" <?php echo $item->TipeTransaksi == "KARTU KREDIT" ? "selected" : NULL ?>>KARTU KREDIT</option>
                    <option value="BANK" data-type=".account-merchan" <?php echo $item->TipeTransaksi == "BANK" ? "selected" : NULL ?>>BANK</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:merchan_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="MerchanName" name="f[MerchanName]" value="<?php echo sprintf( "%s - %s", @$item->MerchanID, @$item->MerchanName ) ?>" placeholder="" class="form-control merchan" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:merchan_account_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="AkunMerchanName" name="f[AkunMerchanName]" value="<?php echo sprintf( "%s - %s", @$item->AkunMerchanNo, @$item->AkunMerchanName ) ?>" placeholder="" class="form-control account-merchan" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:receipt_from_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="DIterimaDari" name="f[DIterimaDari]" value="<?php echo @$item->DIterimaDari ?>" placeholder="" class="form-control" readonly="readonly">
            </div>
        </div><?php */?>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:description_label') ?></label>
            <div class="col-lg-9">
            	<textarea id="Keterangan" name="f[Keterangan]" class="form-control" readonly="readonly"><?php echo @$item->Keterangan ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:value_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="Nilai" name="f[Nilai]" value="<?php echo number_format(@$item->Nilai, 2, ".", ",") ?>" placeholder="" class="form-control text-right" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('non_invoice_cash_expense:account_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="Akun_Name" name="f[Akun_Name]" value="<?php echo sprintf( "%s - %s", @$item->Akun_No, @$item->Akun_Name ) ?>" placeholder="" class="form-control account" readonly="readonly">
            </div>
        </div>
    </div>
</div>
<div class="row">
	<div class="col-md-6">
    </div>
	<div class="col-md-6">
    	<?php if ($item->Posting == 1): ?>
        <div class="col-md-offset-3 col-md-7">
            <h3 class="text-danger">PENGELUARAN KAS NON INVOICE INI SUDAH DI POSTING!</h3>
        </div>
    	<?php elseif ($item->Batal == 1): ?>
        <div class="col-md-offset-3 col-md-7">
            <h3 class="text-danger">PENGELUARAN KAS NON INVOICE INI SUDAH DIBATALKAN!</h3>
        </div>
        <?php endif; ?>
    </div>
</div>
<div class="row form-group">
    <div class="col-lg-6">
        <div class="form-group">
			<?php if (@$is_edit): ?>
        	<div class="col-lg-3">
            	<?php /*?><a href="<?php echo $print_billing_link ?>" id="print_billing" target="_blank" class="btn btn-info btn-block"><b><i class="fa fa-search"></i> <?php echo lang("buttons:preview") ?></b></a><?php */?>
            </div>
        	<div class="col-lg-7">
            	<a href="<?php echo $print_kwitansi_link ?>" id="print_link" target="_blank" class="btn btn-primary btn-block"><b><i class="fa fa-print"></i> <?php echo lang("buttons:print") ?></b></a>
			</div>                
			<?php endif; ?>
		</div>
    </div>
    <div class="col-lg-6">
        <div class="form-group text-right"> 
            <a href="<?php echo base_url("cashier/non-invoice-cash-expense/create")?>" class="btn btn-default"><b><i class="fa fa-plus"></i> <?php echo lang( 'buttons:create' ) ?></b></a>
		</div>
    </div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
	
		$( document ).ready(function(e) {	
				
				// --> Start Masking Pembayaran
				
				$("#Nilai").on("focus", function(){
					val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
					if ( val == 0)
					{
						$(this).val("")
					}
                });

				$("#Nilai").on("blur",function(){
					val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
					if ( val > 0)
					{
						val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
						var mask = parseFloat(val).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
						$(this).val( mask );
					} else {
						$(this).val( "0.00" );
					}
                });
				
				// --> End Masking Pembayaran
				
				
				// --> Start Clear Button
				$(".btn-clear").on("click", function(e){
					var classClear = $(this).data("clear");
					
					$( classClear ).val("");
					$( classClear ).html("");
				});
				// --> End Clear Buttson
				
				$("form[name=\"form_non_invoice_cash_expense\"]").on("submit", function(e){
					e.preventDefault();

					$("#Nilai").each(function(index, element) {
						val = parseFloat(element.value.replace(/[^0-9\.-]+/g,""));
						$(this).val(val);
                    });
				
					if ( !confirm("Apakah Anda yakin akan menyimpan data ini ?"))
					{
						// mengembalikan tanda baca currency

						$("#Nilai").each(function(index, element) {
							val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
							var mask = parseFloat(val).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
							$(this).val( mask );
						});

						return false;
					}
					
					var data_post = {};
					
						data_post['f'] = {
						"TipeTransaksi" : $("#TipeTransaksi").val(),
						"DIterimaDari" : $("#DIterimaDari").val(),
						"Keterangan" : $("#Keterangan").val(),
						"Nilai" : $("#Nilai").val(),
						"AkunID" : $("#AkunID").val(),
					};
					
					var TipeTransaksi = $("#TipeTransaksi").val();
					console.log(TipeTransaksi);
					if ( TipeTransaksi == "KARTU KREDIT" )
					{
						data_post['f']['MerchanID'] = $("#MerchanID").val();
						data_post['f']['AkunMerchanID'] = $("#AkunMerchanID").val();
					}
					if ( TipeTransaksi == "BANK" )
					{
						data_post['f']['AkunMerchanID'] = $("#AkunMerchanID").val();
					}
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
							
							var response = $.parseJSON(response);
	
							if( "error" == response.status ){
								$.alert_error(response.message);
								return false
							}

							if( !response.NoBukti ){
								$.alert_error("Terjadi Kesalahan! Silahkan Hubungi IT Support.");
								return false
							}
							
							$.alert_success( response.message );
							
							var NoBukti = response.NoBukti;
							
							setTimeout(function(){
								document.location.href = "<?php echo base_url("cashier/non-invoice-cash-expense/edit"); ?>/"+ NoBukti;
								}, 3000 );
						})	
				});
			
		 });
	})( jQuery );
//]]>
</script>