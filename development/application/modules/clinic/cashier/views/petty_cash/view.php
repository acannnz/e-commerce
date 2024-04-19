<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php echo form_open( current_url(), array("name" => "form_petty_cash", "id"=>"form_petty_cash") ); ?>
<div class="row form-group">
	<div class="col-md-6">
        <div class="page-subtitle">
            <div class="col-md-12">
                <h3><?php echo lang('petty_cash:general_data_subtitle') ?></h3>
                <p><?php echo lang('petty_cash:general_data_subtitle_helper') ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('petty_cash:evidence_number_label') ?></label>
            <div class="col-lg-7">
                <input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" required readonly="readonly">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('petty_cash:date_label') ?></label>
            <div class="col-lg-7">
                <input type="text" id="Jam" name="Jam" value="<?php echo date("Y-m-d H:m:s") ?>"  class="form-control" readonly="readonly" />
            </div>
        </div>
        

        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('petty_cash:section_label') ?></label>
            <div class="col-lg-7">
                <input type="text" id="SectionName" name="p[SectionName]" value="<?php echo @$section->SectionName?>" placeholder="" class="form-control " readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('petty_cash:user_label') ?></label>
            <div class="col-lg-7">
                <input type="text" id="Nama_Singkat" name="p[Nama_Singkat]" value="<?php echo @$user->Nama_Singkat ?>" placeholder="" class="form-control" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('petty_cash:shift_label') ?></label>
            <div class="col-lg-7">
                <input type="text" id="Shift" name="p[Shift]" value="<?php echo @$item->Shift ?>" placeholder="" class="form-control" readonly="readonly">
            </div>
        </div>
    </div>    
    
    <div class="col-md-6">
        <div class="page-subtitle">
            <div class="col-md-12">
                <h3><?php echo lang('petty_cash:transaction_data_subtitle') ?></h3>
                <p><?php echo lang('petty_cash:transaction_data_subtitle_helper') ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('petty_cash:income_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="Debet" name="f[Debet]" value="<?php echo number_format(@$item->Debet, 2, '.', ',') ?>" placeholder="" class="form-control text-right" autofocus="autofocus">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('petty_cash:expense_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="Kredit" name="f[Kredit]" value="<?php echo number_format(@$item->Kredit, 2, '.', ',') ?>" placeholder="" class="form-control text-right">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('petty_cash:toward_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="Kepada" name="f[Kepada]" value="<?php echo @$item->Kepada ?>" placeholder="" class="form-control" required="required">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('petty_cash:account_label') ?></label>
            <div class="col-lg-9">
                <div class="input-group">
                    <input type="hidden" id="Akun_ID_Tujuan" name="f[Akun_ID_Tujuan]" value="<?php echo @$item->Akun_ID_Tujuan ?>" placeholder="" class="account">
                    <input type="text" id="Akun_Name" name="f[Akun_Name]" value="<?php echo sprintf( "%s - %s", @$item->Akun_No, @$item->Akun_Name ) ?>" placeholder="" class="form-control account" readonly="readonly">
                    <span class="input-group-btn">
                        <a href="<?php echo @$lookup_account ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
                        <a href="javascript:;" id="clear_account" class="btn btn-default btn-clear" data-clear=".account" ><i class="fa fa-times"></i></a>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('petty_cash:description_label') ?></label>
            <div class="col-lg-9">
                <textarea id="Deskripsi" name="f[Deskripsi]" placeholder="" class="form-control" required="required"><?php echo @$item->Deskripsi ?></textarea>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-md-6">
    </div>
	<div class="col-md-6">
    	<?php if ($item->POsted == 1): ?>
        <div class="col-md-offset-3 col-md-7">
            <h3 class="text-danger">PETTY CASH INI SUDAH DI POSTING!</h3>
        </div>
    	<?php elseif ($item->Batal == 1): ?>
        <div class="col-md-offset-3 col-md-7">
            <h3 class="text-danger">PETTY CASH INI SUDAH DIBATALKAN!</h3>
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
            	<a href="<?php echo $print_link ?>" id="print_link" target="_blank" class="btn btn-primary btn-block"><b><i class="fa fa-print"></i> <?php echo lang("buttons:print") ?></b></a>
			</div>                
			<?php endif; ?>
		</div>
    </div>
    <div class="col-lg-6">
        <div class="form-group text-right"> 
            <a href="<?php echo base_url("cashier/petty-cash/create")?>" class="btn btn-default"><b><i class="fa fa-plus"></i> <?php echo lang( 'buttons:create' ) ?></b></a>
		</div>
    </div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
	
		$( document ).ready(function(e) {	
				
				// --> Start Masking Pembayaran
				
				$("#Debet, #Kredit").on("focus", function(){
					val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
					if ( val == 0)
					{
						$(this).val("")
					}
                });

				$("#Debet, #Kredit").on("blur",function(){
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
				
				$("form[name=\"form_petty_cash\"]").on("submit", function(e){
					e.preventDefault();

					$("#NilaiPembayaran").each(function(index, element) {
						val = parseFloat(element.value.replace(/[^0-9\.-]+/g,""));
						$(this).val(val);
                    });
				
					if ( !confirm("Apakah Anda yakin akan menyimpan data ini ?"))
					{
						// mengembalikan tanda baca currency

						$("#NilaiPembayaran").each(function(index, element) {
							val = parseFloat($(this).val().replace(/[^0-9\.-]+/g,""));
							var mask = parseFloat(val).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
							$(this).val( mask );
						});

						return false;
					}
					
					if ( parseFloat($("#Debet").val()) > 0 && parseFloat($("#Kredit").val()) > 0)
					{
						alert("Nilai pemasukan dan pengeluaran salah satunya harus 0 (nol)!")
						return false;
					}

					if ( $("#akun_Name").val() == "" )
					{
						alert("Silahkan Pilih Rekening Terlebih dahulu!")
						return false;
					}
					
					var data_post = {}
					
						data_post['f'] = {
						"Debet" : $("#Debet").val(),
						"Kredit" : $("#Kredit").val(),
						"Kepada" : $("#Kepada").val(),
						"Akun_ID_Tujuan" : $("#Akun_ID_Tujuan").val(),
						"Deskripsi" : $("#Deskripsi").val(),
					};

					
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
								document.location.href = "<?php echo base_url("cashier/petty-cash/edit"); ?>/"+ NoBukti;
								}, 3000 );
						})	
				});
			
		 });
	})( jQuery );
//]]>
</script>