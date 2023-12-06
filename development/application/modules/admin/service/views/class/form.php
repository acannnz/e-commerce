<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_class', 
		'name' => 'form_class', 
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
                <h3 class="panel-title"><?php echo (@$is_edit) ? lang('heading:class_update') : lang('heading:class_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-12 col-xs-12">
                        <div class="form-group">
                        <?php echo form_label(lang('label:code').' *', 'KelasID', ['class' => 'control-label col-md-3']) ?>
                        <div class="col-md-9">
							<?php echo form_input('f[KelasID]', set_value('f[KelasID]', @$item->KelasID, TRUE), [
									'id' => 'KelasID', 
									'placeholder' => '', 
									'class' => 'form-control',
									'readonly' => 'readonly'
								]); ?>
							</div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label(lang('label:name').' *', 'NamaKelas', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[NamaKelas]', set_value('f[NamaKelas]', @$item->NamaKelas, TRUE), [
										'id' => 'NamaKelas', 
										'required' => 'required',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:number').' *', 'Nomor', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input([
										'name' => 'f[Nomor]', 
										'value' => set_value('f[Nomor]', @$item->Nomor, TRUE), 
										'id' => 'Nomor', 
										'type' => 'number',
										'required' => 'required',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:status').' *', 'Active', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="checkbox">
									<label for="Active">
										<?php echo form_input([
												'type' => 'hidden',
												'name' => 'f[Active]',
												'value' => 0,
											]); ?>
										<?php echo form_checkbox('f[Active]', 1, (boolean) @$item->Active, [
											'id' => 'Active', 
										]); ?>
										<?php echo lang('global:active') ?>
									</label>
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
		var _form = $("#form_class");
		var _form_actions = {
				init: function(){
					
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
