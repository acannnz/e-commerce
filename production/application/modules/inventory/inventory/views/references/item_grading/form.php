<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_item_grading', 
		'name' => 'form_item_grading', 
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
                <h3 class="panel-title"><?php echo lang('heading:item_grading'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-6">
						<div class="form-group">
							<?php echo form_label(lang('label:service_type').' *', 'TipePelayanan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[TipePelayanan]', $dropdown_service_type, set_value('f[TipePelayanan]', @$item->TipePelayanan, TRUE), [
										'id' => 'TipePelayanan', 
										'class' => 'form-control',
										'required' => 'required'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:patient_type').' *', 'JenisKerjasamaID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[JenisKerjasamaID]', $dropdown_patient_type, set_value('f[JenisKerjasamaID]', @$item->JenisKerjasamaID, TRUE), [
										'id' => 'JenisKerjasamaID', 
										'class' => 'form-control',
										'required' => 'required'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:class').' *', 'KelasID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[KelasID]', $dropdown_class, set_value('f[KelasID]', @$item->KelasID, TRUE), [
										'id' => 'KelasID', 
										'class' => 'form-control',
										'required' => 'required'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:groups').' *', 'Golongan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php 
									$dropdown_item_group['ALL'] = 'ALL';
									echo form_dropdown('f[Golongan]', $dropdown_item_group, set_value('f[Golongan]', @$item->Golongan, TRUE), [
										'id' => 'Golongan', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:group').' *', 'KelompokJenis', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[KelompokJenis]', $dropdown_item_typegroup, set_value('f[KelompokJenis]', @$item->KelompokJenis, TRUE), [
										'id' => 'KelompokJenis', 
										'class' => 'form-control',
										'required' => 'required'
									]); ?>
							</div>
                        </div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
                            <?php echo form_label(lang('label:price_range'), 'StartHarga', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input('f[StartHarga]', set_value('f[StartHarga]', @$item->StartHarga, TRUE), [
											'id' => 'StartHarga', 
											'placeholder' => '',
											'class' => 'form-control mask-number text-right',
											'required' => 'required'
										]); ?>
									<div class="input-group-addon">-</div>
									<?php echo form_input('f[EndHarga]', set_value('f[EndHarga]', @$item->EndHarga, TRUE), [
											'id' => 'EndHarga', 
											'placeholder' => '',
											'class' => 'form-control mask-number text-right',
											'required' => 'required'
										]); ?>
								</div>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:percentage_up'), 'StartHarga', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">		
								<div class="input-group">					
									<?php echo form_input('f[ProsentaseUp]', set_value('f[ProsentaseUp]', @$item->ProsentaseUp, TRUE), [
											'id' => 'ProsentaseUp', 
											'placeholder' => '',
											'class' => 'form-control text-right',
											'required' => 'required'
										]); ?>
									<div class="input-group-addon">%</div>
								</div>									
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:percentage_discount'), 'ProsentaseDiscount', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">		
								<div class="input-group">					
									<?php echo form_input('f[ProsentaseDiscount]', set_value('f[ProsentaseDiscount]', @$item->ProsentaseDiscount, TRUE), [
											'id' => 'ProsentaseDiscount', 
											'placeholder' => '',
											'class' => 'form-control text-right'
										]); ?>
									<div class="input-group-addon">%</div>
								</div>									
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:ppn'), 'PPN', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">		
								<div class="input-group">					
									<?php echo form_input('f[PPN]', set_value('f[PPN]', @$item->PPN, TRUE), [
											'id' => 'PPN', 
											'placeholder' => '',
											'class' => 'form-control text-right'
										]); ?>
									<div class="input-group-addon">%</div>
								</div>									
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:effective_date'), 'TglBerlaku', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">		
								<?php echo form_input('f[TglBerlaku]', set_value('f[TglBerlaku]', substr(@$item->TglBerlaku, 0, 10), TRUE), [
										'id' => 'TglBerlaku', 
										'placeholder' => '',
										'class' => 'form-control datepicker',
										'required' => 'required'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label('', 'KTP', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_hidden('f[KTP]', 0); ?>
								<?php echo form_checkbox([
										'id' => 'KTP',
										'name' => 'f[KTP]',
										'value' => 1,
										'checked' => set_value('f[KTP]', (boolean) @$item->KTP, TRUE),
										'class' => 'checkbox'
									]).' '.form_label('<b>'. lang('label:ktp').'</b>', 'KTP'); ?>
							</div>
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
		var _form = $("#form_item_grading");
		var _form_actions = {
				init: function(){
					
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
				}
			};
			
		$( document ).ready(function(e) {				
				
				_form_actions.init();

			});

	})( jQuery );
//]]>
</script>
