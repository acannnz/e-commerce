<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<?php echo form_open( $form_action, [
		'id' => 'form_contract', 
		'name' => 'form_contract', 
		'rule' => 'form', 
		'class' => ''
	]); ?>

<div class="row">
	<div class="col-md-12">
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
                <h3 class="panel-title"><?php echo lang('heading:contract_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<?php echo form_label(lang('label:customer'), 'CustomerID', ['class' => 'control-label']) ?>
							<div class="input-group">
								<?php echo form_input([
											'type' => 'hidden',
											'name' => 'f[CustomerID]',
											'value' => set_value('f[CustomerID]', @$item->CustomerID, TRUE),
											'id' => 'CustomerID',
											'class' => 'customer'
										]); ?>
										
								<?php echo form_input('NamaCustomer', set_value('NamaCustomer', @$item->Kode_Customer.' '.@$item->Nama_Customer, TRUE), [
											'id' => 'NamaCustomer', 
											'readonly' => 'readonly',
											'class' => 'form-control customer'
										]); ?>
								<span class="input-group-btn">
									<a href="javascript:;" data-action-url="<?php echo @$lookup_customer ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:customer'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
									<a href="javascript:;" data-target-class="customer"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
            		<div class="col-md-3">
                        <div class="form-group">
							<?php echo form_label(lang('label:cooperation_type').' *', 'JenisKerjasamaID', ['class' => 'control-label']) ?>
							<?php echo form_dropdown('f[JenisKerjasamaID]', $cooperation_type_dropdown, '',[
								'id' => 'JenisKerjasamaID',
								'placeholder' => '', 
								'class' => 'form-control'
							]); ?>
                        </div>
					</div>
					<div class="col-md-3">
                        <div class="form-group">
                            <?php echo form_label(lang('label:class'), 'KelasID', ['class' => 'control-label']) ?>
							<?php echo form_dropdown('f[KelasID]', $class_dropdown, '',[
								'id' => 'KelasID',
								'placeholder' => '', 
								'class' => 'form-control'
							]); ?>
                        </div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo form_label(lang('label:date_start').'/'. lang('label:date_end') .' *', 'StartDate', ['class' => 'control-label']) ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<?php echo form_input('f[StartDate]', set_value('f[StartDate]', @$item->StartDate, TRUE), [
										'id' => 'StartDate', 
										'placeholder' => '', 
										'class' => 'form-control datepicker',
										'autocomplete' => 'off'
									]); ?>
								<span class="input-group-addon"><i class="fa fa-long-arrow-right"></i></span>
								<?php echo form_input('f[EndDate]', set_value('f[EndDate]', @$item->EndDate, TRUE), [
										'id' => 'EndDate', 
										'placeholder' => '', 
										'class' => 'form-control datepicker',
										'autocomplete' => 'off'
									]); ?>
							</div>
                        </div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo form_label(lang('label:borne').' *', 'Ditanggung', ['class' => 'control-label']) ?>
							<?php echo form_dropdown('f[Ditanggung]', $borne_dropdown, '',[
								'id' => 'Ditanggung',
								'placeholder' => '', 
								'class' => 'form-control',
							]); ?>
                        </div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<?php echo form_label(lang('label:due_date').' *', 'TglJatuhTempo', ['class' => 'control-label']) ?>
							<?php echo form_dropdown('f[TglJatuhTempo]', range(0, 15), '',[
								'id' => 'TglJatuhTempo',
								'placeholder' => '', 
								'class' => 'form-control'
							]); ?>
                        </div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo form_label(lang('label:max_treat').' - '. lang('label:opname') .' *', 'MaxHariRawatPerOpname', ['class' => 'control-label']) ?>
							<div class="input-group">
								<?php echo form_input([
										'name' => 'f[MaxHariRawatPerOpname]', 
										'value' => set_value('f[MaxHariRawatPerOpname]', @$item->MaxHariRawatPerOpname, TRUE),
										'type' => 'number',
										'id' => 'MaxHariRawatPerOpname', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
								<span class="input-group-addon"> <?php echo lang('label:day')?> </span>
							</div>
                        </div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo form_label(lang('label:max_treat') .'/ '. lang('label:year') .' *', 'MaxHariRawatPerTahun', ['class' => 'control-label']) ?>
							<div class="input-group">
								<?php echo form_input([
										'name' => 'f[MaxHariRawatPerTahun]', 
										'value' => set_value('f[MaxHariRawatPerTahun]', @$item->MaxHariRawatPerTahun, TRUE),
										'type' => 'number',
										'id' => 'MaxHariRawatPerTahun', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
								<span class="input-group-addon"> <?php echo lang('label:day')?> </span>
							</div>
                        </div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo form_label(lang('label:max_inpatient_value').'/'. lang('label:year') .' *', 'MaxRIRupiahPerTahun', ['class' => 'control-label']) ?>
							<?php echo form_input([
									'name' => 'f[MaxRIRupiahPerTahun]', 
									'value' => set_value('f[MaxRIRupiahPerTahun]', @$item->MaxRIRupiahPerTahun, TRUE),
									'id' => 'MaxRIRupiahPerTahun', 
									'placeholder' => '', 
									'class' => 'form-control mask-number text-right'
								]); ?>
                        </div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<?php echo form_label(lang('label:account_receivable'), 'AkunPiutang_ID', ['class' => 'control-label']) ?>
							<div class="input-group">
								<?php echo form_input([
											'type' => 'hidden',
											'name' => 'f[AkunPiutang_ID]',
											'value' => set_value('f[AkunPiutang_ID]', @$item->AkunPiutang_ID, TRUE),
											'id' => 'AkunPiutang_ID',
											'class' => 'account'
										]); ?>
										
								<?php echo form_input('NamaAkunPiutang', set_value('NamaAkunPiutang', @$item->Nama_Akun, TRUE), [
											'id' => 'NamaAkunPiutang', 
											'readonly' => 'readonly',
											'class' => 'form-control account'
										]); ?>
								<span class="input-group-btn">
									<a href="javascript:;" data-action-url="<?php echo @$lookup_receivable_account ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:account_receivable'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
									<a href="javascript:;" data-target-class="account"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo form_label(lang('label:drug') .' *', 'ObatUp', ['class' => 'control-label']) ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-long-arrow-up"></i> (&permil;)</span>
								<?php echo form_input([
										'name' => 'f[ObatUp]', 
										'value' => set_value('f[ObatUp]', @$item->ObatUp, TRUE),
										'id' => 'ObatUp', 
										'placeholder' => '',
										'type'  => 'number',
										'class' => 'form-control'
									]); ?>
								<span class="input-group-addon"><i class="fa fa-long-arrow-down"></i> (&permil;)</span>
								<?php echo form_input([
										'name' => 'f[ObatDiscount]', 
										'value' => set_value('f[ObatDiscount]', @$item->ObatDiscount, TRUE),
										'id' => 'ObatDiscount', 
										'placeholder' => '', 
										'type' => 'number',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo form_label(lang('label:status') .' *', 'Active', ['class' => 'control-label']) ?>
							<div class="col-md-12 row">
								<?php echo form_checkbox([
										'id' => 'Active',
										'name' => 'f[Active]',
										'value' => 1,
										'checked' => set_value('f[Active]', (boolean) @$item->Active, TRUE),
										'class' => 'checkbox'
									]).' '.form_label('<b>'. lang('label:active').'</b>', 'Active'); ?>
							</div>
						</div>
					</div>
				</div>
				<hr/>				
				<ul class="nav nav-tabs nav-justified">
					<li class="active"><a href="#post-service-detail" data-toggle="tab"><i class="fa fa-stethoscope"></i> <strong><?php echo lang("label:service")?></strong></a></li>
					<li class=""><a href="#post-drug-detail" data-toggle="tab"><i class="fa fa-medkit"></i> <strong><?php echo lang("label:drug")?></strong></a></li>
				</ul>
				<div class="tab-content">
					<div id="post-service-detail" class="tab-pane active">
						<?php echo modules::run("marketing/contracts/service/index", @$item->CustomerKerjasamaID) ?>
					</div>
					<div id="post-drug-detail" class="tab-pane">
						<?php echo modules::run("marketing/contracts/drug/index", @$item->CustomerKerjasamaID) ?>
					</div>
				</div>	
				<hr/>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group text-right">
							<button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';">Close</button> 
							<button class="btn btn-warning" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';">New</button> 
							<button id="js-btn-submit" type="submit" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("#form_contract");
		var _form_actions = {
				init: function(){
					_form.find('a.btn-clear').on('click', function(e){
						var _target_class = $(this).data('target-class');
						$('.'+ _target_class).val('');
					});
				}
			};
				
		$( document ).ready(function(e) {
				_form_actions.init();
				
				_form.on('submit', function(e){
					e.preventDefault();		
					
					var post_data = $(this).serializeArray();
					post_data.push({name : 'f[MaxRIRupiahPerTahun]', value : mask_number.currency_remove($('#MaxRIRupiahPerTahun').val()) });
					
					var iComponent = 0;
					var tb_contract_service = $('#dt_contract_service').DataTable().rows().data();
					$.each(tb_contract_service, function(i, v){
						post_data.push({name : 'service['+i+'][Harga_Lama]', value : v.Harga_Lama});
						post_data.push({name : 'service['+i+'][Harga_Baru]', value : v.Harga_Baru});
						post_data.push({name : 'service['+i+'][Included]', value : v.Included});
						post_data.push({name : 'service['+i+'][AutoSystem]', value : v.AutoSystem});
						post_data.push({name : 'service['+i+'][ListHargaID]', value : v.ListHargaID});
						post_data.push({name : 'service['+i+'][HonorDefault]', value : 1});
						post_data.push({name : 'service['+i+'][Honor]', value : 100});
						post_data.push({name : 'service['+i+'][Paket]', value : 0});
						post_data.push({name : 'service['+i+'][TglPerubahanHarga]', value : ''});
						
						$.each(v.components, function(idx, val){
							post_data.push({name : 'component['+iComponent+'][ListHargaID]', value : v.ListHargaID});
							post_data.push({name : 'component['+iComponent+'][KomponenBiayaID]', value : val.KomponenBiayaID});
							post_data.push({name : 'component['+iComponent+'][HargaLama]', value : val.Harga_Lama});
							post_data.push({name : 'component['+iComponent+'][HargaBaru]', value : val.Harga_Baru});
							post_data.push({name : 'component['+iComponent+'][Prosentase]', value : 0});
							post_data.push({name : 'component['+iComponent+'][NilaiPersen]', value : 0});
							post_data.push({name : 'component['+iComponent+'][AkunNo]', value : val.AkunNo});
							post_data.push({name : 'component['+iComponent+'][TglUpdate]', value : ''});	
							iComponent++;						
						});
						
					});
					
					var tb_contract_drug = $('#dt_contract_drug').DataTable().rows().data();
					$.each(tb_contract_drug, function(i, v){
						post_data.push({name : 'drug['+i+'][Barang_ID]', value : v.Barang_ID});
						post_data.push({name : 'drug['+i+'][JenisKerjasamaID]', value : $('#JenisKerjasamaID').val()});
						post_data.push({name : 'drug['+i+'][Include]', value : v.Include});
						post_data.push({name : 'drug['+i+'][Ditanggung]', value : v.Ditanggung});
					});
					
					$.post( _form.prop("action"), post_data, function( response, status, xhr ){
						
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success( response.message );
												
						setTimeout(function(){													
							//document.location.href = "<?php echo base_url("{$nameroutes}"); ?>";							
							}, 300 );
						
					});
				});
				
				

			});

	})( jQuery );
//]]>
</script>
