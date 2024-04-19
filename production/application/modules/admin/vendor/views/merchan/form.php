<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_merchan', 
		'name' => 'form_merchan', 
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
                <h3 class="panel-title"><?php echo lang('heading:merchan_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-12 col-xs-12">
                        <div class="form-group">
							<?php echo form_label(lang('label:code').' *', 'ID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[ID]', set_value('f[ID]', @$item->ID, TRUE), [
										'id' => 'ID', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label(lang('label:name'), 'NamaBank', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[NamaBank]', set_value('f[NamaBank]', @$item->NamaBank, TRUE), [
										'id' => 'NamaBank', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:account'), 'Akun_ID_Tujuan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input([
												'type' => 'hidden',
												'name' => 'f[Akun_ID_Tujuan]',
												'value' => set_value('f[Akun_ID_Tujuan]', @$item->Akun_ID_Tujuan, TRUE),
												'id' => 'Akun_ID_Tujuan',
												'class' => 'Akun_ID_Tujuan'
											]); ?>
											
									<?php echo form_input('Akun_ID_TujuanName', set_value('f[Akun_ID_TujuanName]', @$account->Akun_No.' '.@$account->Akun_Name, TRUE), [
												'id' => 'Akun_ID_TujuanName', 
												'readonly' => 'readonly',
												'class' => 'form-control Akun_ID_Tujuan'
											]); ?>
									<span class="input-group-btn">
										<a href="javascript:;" data-action-url="<?php echo @$lookup_account ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" data-target-class="Akun_ID_Tujuan"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:addcharge_d').' *', 'AddCharge_Debet', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[AddCharge_Debet]', set_value('f[AddCharge_Debet]', @$item->AddCharge_Debet, TRUE), [
										'id' => 'AddCharge_Debet', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:addcharge_k').' *', 'AddCharge_Kredit', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[AddCharge_Kredit]', set_value('f[AddCharge_Kredit]', @$item->AddCharge_Kredit, TRUE), [
										'id' => 'AddCharge_Kredit', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:discount').' *', 'Diskon', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Diskon]', set_value('f[Diskon]', @$item->Diskon, TRUE), [
										'id' => 'Diskon', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group text-right">
							<button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';">Close</button> 
							<button class="btn btn-warning" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';">New</button> 
							<button id="js-btn-submit" type="button" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
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
		var _form = $("#form_merchan");
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
				
				$("button#js-btn-submit").on("click", function(e){
					e.preventDefault();		
							
					$.post( _form.prop("action"), _form.serializeArray(), function( response, status, xhr ){
						
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success( response.message );
						
						var id = response.id;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("{$nameroutes}"); ?>";
							
							}, 300 );
						
					});
				});
				
				

			});

	})( jQuery );
//]]>
</script>
