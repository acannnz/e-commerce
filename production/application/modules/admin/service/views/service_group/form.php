<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_group', 
		'name' => 'form_group', 
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
                <h3 class="panel-title"><?php echo (@$is_edit) ? lang('heading:service_group_update') : lang('heading:service_group_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-12 col-xs-12">
                        <div class="form-group">
                            <?php echo form_label(lang('label:name').' *', 'GroupJasaName', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[GroupJasaName]', set_value('f[GroupJasaName]', @$item->GroupJasaName, TRUE), [
										'id' => 'GroupJasaName', 
										'required' => 'required',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:international_name'), 'NamaInternational', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[NamaInternational]', set_value('f[NamaInternational]', @$item->NamaInternational, TRUE), [
										'id' => 'NamaInternational', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:treatment_group'), 'KelompokPerawatan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[KelompokPerawatan]', $dropdown_treatment_group, set_value('f[KelompokPerawatan]', @$item->KelompokPerawatan, TRUE), [
										'id' => 'KelompokPerawatan', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<hr />
						<h4 class="subtitle"><?php echo lang('subtitle:account_integration')?></h4>
						<ul class="nav nav-tabs nav-justified">
							<li class="active"><a href="#tab-integration-1" data-toggle="tab"><i class="fa fa-database"></i> <strong><?php echo lang("subtitle:bo_1")?></strong></a></li>
							<?php if(config_item('multi_bo') == 'TRUE'): ?>
							<li class=""><a href="#tab-integration-2" data-toggle="tab"><i class="fa fa-database"></i> <strong><?php echo lang("subtitle:bo_2")?></strong></a></li>
							<?php endif; ?>
						</ul>
						<div class="tab-content">
							<div id="tab-integration-1" class="tab-pane active">
								<div class="row">
									<div class="form-group">
										<?php echo form_label(lang('label:outpatient_account'), 'AkunNoRJ', ['class' => 'control-label col-md-3']) ?>
										<div class="col-md-9">
											<div class="input-group">
												<?php echo form_input([
															'type' => 'hidden',
															'name' => 'f[AkunNoRJ]',
															'value' => set_value('f[AkunNoRJ]', @$item->AkunNoRJ, TRUE),
															'id' => 'AkunNoRJ',
															'class' => 'AkunNoRJ'
														]); ?>
														
												<?php echo form_input('NamaAkunNoRJ', set_value('f[NamaAkunNoRJ]', @$item->AkunNoRJ.' '.@$outpatient->Akun_Name, TRUE), [
															'id' => 'NamaAkunNoRJ', 
															'readonly' => 'readonly',
															'class' => 'form-control AkunNoRJ'
														]); ?>
												<span class="input-group-btn">
													<a href="javascript:;" data-action-url="<?php echo @$lookup_outpatient_account ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:outpatient_account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" data-target-class="AkunNoRJ"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<?php echo form_label(lang('label:inpatient_account'), 'AkunNoRI', ['class' => 'control-label col-md-3']) ?>
										<div class="col-md-9">
											<div class="input-group">
												<?php echo form_input([
															'type' => 'hidden',
															'name' => 'f[AkunNoRI]',
															'value' => set_value('f[AkunNoRI]', @$item->AkunNoRI, TRUE),
															'id' => 'AkunNoRI',
															'class' => 'AkunNoRI'
														]); ?>
														
												<?php echo form_input('NamaAkunNoRI', set_value('f[NamaAkunNoRI]', @$item->AkunNoRI.' '.@$inpatient->Akun_Name, TRUE), [
															'id' => 'NamaAkunNoRI', 
															'readonly' => 'readonly',
															'class' => 'form-control AkunNoRI'
														]); ?>
												<span class="input-group-btn">
													<a href="javascript:;" data-action-url="<?php echo @$lookup_inpatient_account ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:inpatient_account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" data-target-class="AkunNoRI"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<?php echo form_label(lang('label:emergency_account'), 'AKunNoUGD', ['class' => 'control-label col-md-3']) ?>
										<div class="col-md-9">
											<div class="input-group">
												<?php echo form_input([
															'type' => 'hidden',
															'name' => 'f[AKunNoUGD]',
															'value' => set_value('f[AKunNoUGD]', @$item->AKunNoUGD, TRUE),
															'id' => 'AKunNoUGD',
															'class' => 'AKunNoUGD'
														]); ?>
														
												<?php echo form_input('NamaAKunNoUGD', set_value('f[NamaAKunNoUGD]', @$item->AKunNoUGD.' '.@$emergency->Akun_Name, TRUE), [
															'id' => 'NamaAKunNoUGD', 
															'readonly' => 'readonly',
															'class' => 'form-control AKunNoUGD'
														]); ?>
												<span class="input-group-btn">
													<a href="javascript:;" data-action-url="<?php echo @$lookup_emergency_account ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:emergency_account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" data-target-class="AKunNoUGD"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<?php echo form_label(lang('label:oncall_account'), 'AkunNoOnCall', ['class' => 'control-label col-md-3']) ?>
										<div class="col-md-9">
											<div class="input-group">
												<?php echo form_input([
															'type' => 'hidden',
															'name' => 'f[AkunNoOnCall]',
															'value' => set_value('f[AkunNoOnCall]', @$item->AkunNoOnCall, TRUE),
															'id' => 'AkunNoOnCall',
															'class' => 'AkunNoOnCall'
														]); ?>
														
												<?php echo form_input('NamaAkunNoOnCall', set_value('f[NamaAkunNoOnCall]', @$item->AkunNoOnCall.' '.@$oncall->Akun_Name, TRUE), [
															'id' => 'NamaAkunNoOnCall', 
															'readonly' => 'readonly',
															'class' => 'form-control AkunNoOnCall'
														]); ?>
												<span class="input-group-btn">
													<a href="javascript:;" data-action-url="<?php echo @$lookup_oncall_account ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:oncall_account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" data-target-class="AkunNoOnCall"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<?php echo form_label(lang('label:ma_account'), 'AkunNoMA', ['class' => 'control-label col-md-3']) ?>
										<div class="col-md-9">
											<div class="input-group">
												<?php echo form_input([
															'type' => 'hidden',
															'name' => 'f[AkunNoMA]',
															'value' => set_value('f[AkunNoMA]', @$item->AkunNoMA, TRUE),
															'id' => 'AkunNoMA',
															'class' => 'AkunNoMA'
														]); ?>
														
												<?php echo form_input('NamaAkunNoMA', set_value('f[NamaAkunNoMA]', @$item->AkunNoMA.' '.@$ma->Akun_Name, TRUE), [
															'id' => 'NamaAkunNoMA', 
															'readonly' => 'readonly',
															'class' => 'form-control AkunNoMA'
														]); ?>
												<span class="input-group-btn">
													<a href="javascript:;" data-action-url="<?php echo @$lookup_ma_account ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:ma_account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" data-target-class="AkunNoMA"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
									</div>	
								</div>					
							</div>
							<?php if(config_item('multi_bo') == 'TRUE'): ?>
							<div id="tab-integration-2" class="tab-pane">
								<div class="row">
									<div class="form-group">
										<?php echo form_label(lang('label:outpatient_account'), 'AkunNoRJ_2', ['class' => 'control-label col-md-3']) ?>
										<div class="col-md-9">
											<div class="input-group">
												<?php echo form_input([
															'type' => 'hidden',
															'name' => 'f[AkunNoRJ_2]',
															'value' => set_value('f[AkunNoRJ_2]', @$item->AkunNoRJ_2, TRUE),
															'id' => 'AkunNoRJ_2',
															'class' => 'AkunNoRJ_2'
														]); ?>
														
												<?php echo form_input('NamaAkunNoRJ_2', set_value('f[NamaAkunNoRJ_2]', @$item->AkunNoRJ_2.' '.@$outpatient_2->Akun_Name, TRUE), [
															'id' => 'NamaAkunNoRJ_2', 
															'readonly' => 'readonly',
															'class' => 'form-control AkunNoRJ_2'
														]); ?>
												<span class="input-group-btn">
													<a href="javascript:;" data-action-url="<?php echo @$lookup_outpatient_account_2 ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:outpatient_account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" data-target-class="AkunNoRJ_2"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<?php echo form_label(lang('label:inpatient_account'), 'AkunNoRI_2', ['class' => 'control-label col-md-3']) ?>
										<div class="col-md-9">
											<div class="input-group">
												<?php echo form_input([
															'type' => 'hidden',
															'name' => 'f[AkunNoRI_2]',
															'value' => set_value('f[AkunNoRI_2]', @$item->AkunNoRI_2, TRUE),
															'id' => 'AkunNoRI_2',
															'class' => 'AkunNoRI_2'
														]); ?>
														
												<?php echo form_input('NamaAkunNoRI_2', set_value('f[NamaAkunNoRI_2]', @$item->AkunNoRI_2.' '.@$inpatient_2->Akun_Name, TRUE), [
															'id' => 'NamaAkunNoRI_2', 
															'readonly' => 'readonly',
															'class' => 'form-control AkunNoRI_2'
														]); ?>
												<span class="input-group-btn">
													<a href="javascript:;" data-action-url="<?php echo @$lookup_inpatient_account_2 ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:inpatient_account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" data-target-class="AkunNoRI_2"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<?php echo form_label(lang('label:emergency_account'), 'AKunNoUGD_2', ['class' => 'control-label col-md-3']) ?>
										<div class="col-md-9">
											<div class="input-group">
												<?php echo form_input([
															'type' => 'hidden',
															'name' => 'f[AKunNoUGD_2]',
															'value' => set_value('f[AKunNoUGD_2]', @$item->AKunNoUGD_2, TRUE),
															'id' => 'AKunNoUGD_2',
															'class' => 'AKunNoUGD_2'
														]); ?>
														
												<?php echo form_input('NamaAKunNoUGD_2', set_value('f[NamaAKunNoUGD_2]', @$item->AKunNoUGD_2.' '.@$emergency_2->Akun_Name, TRUE), [
															'id' => 'NamaAKunNoUGD_2', 
															'readonly' => 'readonly',
															'class' => 'form-control AKunNoUGD_2'
														]); ?>
												<span class="input-group-btn">
													<a href="javascript:;" data-action-url="<?php echo @$lookup_emergency_account_2 ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:emergency_account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" data-target-class="AKunNoUGD_2"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<?php echo form_label(lang('label:oncall_account'), 'AkunNoOnCall_2', ['class' => 'control-label col-md-3']) ?>
										<div class="col-md-9">
											<div class="input-group">
												<?php echo form_input([
															'type' => 'hidden',
															'name' => 'f[AkunNoOnCall_2]',
															'value' => set_value('f[AkunNoOnCall_2]', @$item->AkunNoOnCall_2, TRUE),
															'id' => 'AkunNoOnCall_2',
															'class' => 'AkunNoOnCall_2'
														]); ?>
														
												<?php echo form_input('NamaAkunNoOnCall_2', set_value('f[NamaAkunNoOnCall_2]', @$item->AkunNoOnCall_2.' '.@$oncall_2->Akun_Name, TRUE), [
															'id' => 'NamaAkunNoOnCall_2', 
															'readonly' => 'readonly',
															'class' => 'form-control AkunNoOnCall_2'
														]); ?>
												<span class="input-group-btn">
													<a href="javascript:;" data-action-url="<?php echo @$lookup_oncall_account_2 ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:oncall_account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" data-target-class="AkunNoOnCall_2"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<?php echo form_label(lang('label:ma_account'), 'AkunNoMA_2', ['class' => 'control-label col-md-3']) ?>
										<div class="col-md-9">
											<div class="input-group">
												<?php echo form_input([
															'type' => 'hidden',
															'name' => 'f[AkunNoMA_2]',
															'value' => set_value('f[AkunNoMA_2]', @$item->AkunNoMA_2, TRUE),
															'id' => 'AkunNoMA_2',
															'class' => 'AkunNoMA_2'
														]); ?>
														
												<?php echo form_input('NamaAkunNoMA_2', set_value('f[NamaAkunNoMA_2]', @$item->AkunNoMA_2.' '.@$ma_2->Akun_Name, TRUE), [
															'id' => 'NamaAkunNoMA_2', 
															'readonly' => 'readonly',
															'class' => 'form-control AkunNoMA_2'
														]); ?>
												<span class="input-group-btn">
													<a href="javascript:;" data-action-url="<?php echo @$lookup_ma_account_2 ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:ma_account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
													<a href="javascript:;" data-target-class="AkunNoMA_2"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
												</span>
											</div>
										</div>
									</div>						
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group text-right">
							<button id="js-btn-submit" type="submit" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
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
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("#form_group");
		var _form_actions = {
				init: function(){
					
					if( _form.find('select#PostinganKe').val() == 'Hutang' ){
						_form.find('select#HutangKe').val('None');
						_form.find('select#HutangKe').prop('disabled', 'disabled');
					}
					
					_form.find('select#PostinganKe').on("change", function(){
						if($(this).val() == 'Hutang'){
							_form.find('select#HutangKe').val('None');
							_form.find('select#HutangKe').prop('disabled', 'disabled');
						} else {
							_form.find('select#HutangKe').removeProp('disabled');
						}
					});
					
					_form.find('a.btn-clear').on('click', function(e){
						var _target_class = $(this).data('target-class');
						$('.'+ _target_class).val('');
					});
					
					
				}
			}
				
		$( document ).ready(function(e) {
				
				_form_actions.init();
				
				_form.on("submit", function(e){
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
