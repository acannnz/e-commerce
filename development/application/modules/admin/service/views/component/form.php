<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_component', 
		'name' => 'form_component', 
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
                <h3 class="panel-title"><?php echo (@$is_edit) ? lang('heading:service_component_update') : lang('heading:service_component_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-12 col-xs-12">
                        <div class="form-group">
                        <?php echo form_label(lang('label:code').' *', 'KomponenBiayaID', ['class' => 'control-label col-md-3']) ?>
                        <div class="col-md-9">
							<?php echo form_input('f[KomponenBiayaID]', set_value('f[KomponenBiayaID]', @$item->KomponenBiayaID, TRUE), [
									'id' => 'KomponenBiayaID', 
									'placeholder' => '', 
									'readonly' => 'readonly',
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label(lang('label:name').' *', 'KomponenName', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[KomponenName]', set_value('f[KomponenName]', @$item->KomponenName, TRUE), [
										'id' => 'KomponenName', 
										'required' => 'required',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:account_group'), 'KelompokAkun', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[KelompokAkun]', $dropdown_account_group, set_value('f[KelompokAkun]', @$item->KelompokAkun, TRUE), [
										'id' => 'KelompokAkun', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:posting_to'), 'PostinganKe', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[PostinganKe]', $dropdown_posting_to, set_value('f[PostinganKe]', @$item->PostinganKe, TRUE), [
										'id' => 'PostinganKe', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:debt_to'), 'HutangKe', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[HutangKe]', $dropdown_debt_to, set_value('f[HutangKe]', @$item->HutangKe, TRUE), [
										'id' => 'HutangKe', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:service_group'), 'KelompokJasa', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[KelompokJasa]', $dropdown_service_group, set_value('f[KelompokJasa]', @$item->KelompokJasa, TRUE), [
										'id' => 'KelompokJasa', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<div class="col-md-offset-3 col-md-3">
								<div class="checkbox">
									<label for="IncludeInsentif">
										<?php echo form_input([
												'type' => 'hidden',
												'name' => 'f[IncludeInsentif]',
												'value' => 0,
											]); ?>
										<?php echo form_checkbox('f[IncludeInsentif]', 1, (boolean) @$item->IncludeInsentif, [
											'id' => 'IncludeInsentif', 
										]); ?>
										<?php echo lang('label:include_insentif') ?>
									</label>
								</div>
							</div>
							<div class="col-md-3">
								<div class="checkbox">
									<label for="ExcludeCostRS">
										<?php echo form_input([
												'type' => 'hidden',
												'name' => 'f[ExcludeCostRS]',
												'value' => 0,
											]); ?>
										<?php echo form_checkbox('f[ExcludeCostRS]', 1, (boolean) @$item->ExcludeCostRS, [
											'id' => 'ExcludeCostRS', 
										]); ?>
										<?php echo lang('label:exclude_cost_rs') ?>
									</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:hpp_account'), 'AkunNoHPP', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input([
												'type' => 'hidden',
												'name' => 'f[AkunNoHPP]',
												'value' => set_value('f[AkunNoHPP]', @$item->AkunNoHPP, TRUE),
												'id' => 'AkunNoHPP',
												'class' => 'AkunNoHPP'
											]); ?>
											
									<?php echo form_input('NamaAkunNoHPP', set_value('f[NamaAkunNoHPP]', @$item->AkunNoHPP.' '.@$hpp->Akun_Name, TRUE), [
												'id' => 'NamaAkunNoHPP', 
												'readonly' => 'readonly',
												'class' => 'form-control AkunNoHPP'
											]); ?>
									<span class="input-group-btn">
										<a href="javascript:;" data-action-url="<?php echo @$lookup_hpp_account ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:hpp_account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" data-target-class="AkunNoHPP"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:hpp_againts_account'), 'AkunNoLawanHPP', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input([
												'type' => 'hidden',
												'name' => 'f[AkunNoLawanHPP]',
												'value' => set_value('f[AkunNoLawanHPP]', @$item->AkunNoLawanHPP, TRUE),
												'id' => 'AkunNoLawanHPP',
												'class' => 'AkunNoLawanHPP'
											]); ?>
											
									<?php echo form_input('NamaAkunNoLawanHPP', set_value('f[NamaAkunNoLawanHPP]', @$item->AkunNoLawanHPP.' '.@$hpp_againts->Akun_Name, TRUE), [
												'id' => 'NamaAkunNoLawanHPP', 
												'readonly' => 'readonly',
												'class' => 'form-control AkunNoLawanHPP'
											]); ?>
									<span class="input-group-btn">
										<a href="javascript:;" data-action-url="<?php echo @$lookup_hpp_againts_account ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:hpp_againts_account')); ?>" data-act="ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" data-target-class="AkunNoLawanHPP"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
									</span>
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
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("#form_component");
		var _form_actions = {
				init: function(){
					
					if( _form.find('select#PostinganKe').val() == 'Hutang' ){
						_form.find('select#HutangKe').val('None');
						_form.find('select#HutangKe').removeProp('disabled');
					}
					
					_form.find('select#PostinganKe').on("change", function(){
						if($(this).val() == 'Hutang'){
							_form.find('select#HutangKe').removeProp('disabled');
						} else {
							_form.find('select#HutangKe').val('None');
							_form.find('select#HutangKe').prop('disabled', 'disabled');
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
				
				_form.find("button#js-btn-submit").on("click", function(e){
					e.preventDefault();		
							
					$.post( _form.prop("action"), _form.serializeArray(), function( response, status, xhr ){
						
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success( response.message );
						
						var id = response.id;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url($nameroutes); ?>";
							
							}, 300 );
						
					});
				});
				
				

			});

	})( jQuery );
//]]>
</script>
