<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_payment_type', 
		'name' => 'form_payment_type', 
		'rule' => 'form', 
		'class' => ''
	]); ?>

<div class="row">
	<div class="col-md-offset-2 col-md-8">
		<div class="panel panel-default">
            <div class="panel-heading">                
                <div class="panel-bars">
					<ul class="btn-bars">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
                                <i class="fa fa-bars fa-lg tip" data-placement="left" title="<?php echo lang("actions") ?>"></i>
                            </a>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li>
                                    <a href="<?php echo site_url("{$nameroutes}/create"); ?>">
                                        <i class="fa fa-plus"></i> <?php echo lang('action:add') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo (@$is_edit) ? lang('heading:payment_type_update') : lang('heading:payment_type_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-12 col-xs-12">
                        <div class="form-group">
							<?php echo form_label(lang('label:name').' *', 'Description', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Description]', set_value('f[Description]', @$item->Description, TRUE), [
										'id' => 'Description', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
                        <div class="form-group">
							<?php echo form_label(lang('label:order').' *', 'NoUrut', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[NoUrut]', set_value('f[NoUrut]', @$item->NoUrut, TRUE), [
										'id' => 'NoUrut', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:account').' *', 'Akun_Id', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
							<div class="col-sm-9 col-xs-12">
								<div class="row lookupbox7-form-control">
									<div class="col-sm-9 col-xs-12">
										<?php echo form_hidden('f[Akun_Id]', set_value('f[Akun_Id]', @$item->Akun_Id, TRUE)); ?>
										<?php echo form_input('t[NamaAkun_Id]', set_value('t[NamaAkun_Id]', @$account->Akun_No .' '. @$account->Akun_Name, TRUE), [
												'placeholder' => '', 
												'class' => 'form-control lookupbox7-input-search',
												'required' => 'required'
											]); ?>
									</div>
									<div class="col-sm-3 col-xs-12">
										<?php echo form_button([
												'type' => 'button',
												'content' => '<i class="fa fa-search"></i>',
												'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
											]); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label('Opsi', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
							<div class="col-sm-9 col-xs-12">
								<div class="row">
									<div class="col-sm-4 col-xs-12">
										<?php echo form_hidden('f[radio_option]',0); ?>
										<label><?php echo form_radio([
												'name' => 'f[radio_option]',
												'value' => 'Cash',
												'checked' => (1 == @$item->Cash),
												'class' => 'radio'
											]).' Cash'; ?></label>
									</div>
									<div class="col-sm-4 col-xs-12">
										<label><?php echo form_radio([
												'name' => 'f[radio_option]',
												'value' => 'Bank',
												'checked' => (1 == @$item->Bank),
												'class' => 'radio'
											]).' Bank'; ?></label>
									</div>
									<div class="col-sm-4 col-xs-12">
										<label><?php echo form_radio([
												'name' => 'f[radio_option]',
												'value' => 'CC',
												'checked' => (1 == @$item->CC),
												'class' => 'radio'
											]).' Kartu Kredit'; ?></label>
									</div>
									<div class="col-sm-4 col-xs-12">
										<label><?php echo form_radio([
												'name' => 'f[radio_option]',
												'value' => 'Jaminan',
												'checked' => (1 == @$item->Jaminan),
												'class' => 'radio'
											]).' Jaminan'; ?></label>
									</div>
									<div class="col-sm-4 col-xs-12">
										<label><?php echo form_radio([
												'name' => 'f[radio_option]',
												'value' => 'Others',
												'checked' => (1 == @$item->Others),
												'class' => 'radio'
											]).' Others'; ?></label>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-4 col-xs-12">
										<?php echo form_hidden('f[HCOnly]',0); ?>
										<label><?php echo form_checkbox([
												'name' => 'f[HCOnly]',
												'value' => 1,
												'checked' => (1 == @$item->HCOnly),
												'class' => 'checkbox'
											]).' HC Only'; ?></label>
									</div>
									<div class="col-sm-4 col-xs-12">
										<?php echo form_hidden('f[KerjasamaOnly]',0); ?>
										<label><?php echo form_checkbox([
												'name' => 'f[KerjasamaOnly]',
												'value' => 1,
												'checked' => (1 == @$item->KerjasamaOnly),
												'class' => 'checkbox'
											]).' Kerjasama Only'; ?></label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group text-right">
							<button id="js-btn-submit" type="button" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
							<button class="btn btn-warning" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';">New</button> 
							<button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';">Close</button> 
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close() ?>
<script src="<?php echo site_url("themes/bracketadmin/vendor/lookupbox7/jquery.lookupbox7.js"); ?>"></script>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("#form_payment_type");
		
		_form.find('input[name="t[NamaAkun_Id]"]').lookupbox7({
				remote: '<?php echo site_url('others/account/lookup_collection'); ?>',
				title: 'Daftar Pilihan Rekening',
				columns: [
						{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
						{data: "Akun_Name", orderable: true, searchable: true}
					],
				headings: ['Kode','Nama Rekening'],
				order: [],
				placeholder: 'Ketik cari rekening',
				btnApplyText: 'Terapkan Pilihan',
				btnCloseText: 'Tutup',
				onSelected: function(v){
						_form.find('input[name="f[Akun_Id]"]').val(v.Akun_ID);
						_form.find('input[name="t[NamaAkun_Id]"]').val(v.Akun_No +' '+ v.Akun_Name);
					}
			});
				
		$( document ).ready(function(e) {
				_form.find("button#js-btn-submit").on("click", function(e){
					e.preventDefault();
					$.post( _form.prop("action"), _form.serializeArray(), function( response, status, xhr ){
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						$.alert_success( response.message );					
						setTimeout(function(){
							document.location.href = "<?php echo base_url($nameroutes); ?>";	
							}, 300 );
						
					});
				});
				
				

			});

	})( jQuery );
//]]>
</script>
