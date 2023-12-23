<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<?php echo form_open( $form_action, [
		'id' => 'form_revenue_recognition', 
		'name' => 'form_revenue_recognition', 
		'rule' => 'form', 
		'class' => 'form-horizontal'
	]); ?>
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
            <div class="panel-heading">                
                <h3 class="panel-title"><?php echo lang('heading:revenue_recognition'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
			<div class="row">
	            <div class="col-md-12">
            		<div class="form-group">
						<?php echo form_label(lang('label:date').' *', 'Tanggal', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-7">
							<?php echo form_input('f[Tanggal]', set_value('f[Tanggal]', date('Y-m-d'), TRUE), [
									'id' => 'Tanggal', 
									'placeholder' => '', 
									'required' => 'required',
									'class' => 'form-control datepicker'
								]); ?>
						</div>
                    </div>
				</div>
			</div>
			<hr />
			<div class="row">
				<div class="col-md-12">
                    <div class="form-group">
                        <?php echo form_label(lang('label:inpatient'), '', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-7">						
							<div class="progress" style="margin-top:10px">
								<div id="inpatient" class="progress-bar progress-bar-striped progress-bar-success active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
							</div>
						</div>
						<div class="col-md-2">
							<button name="btn_process" data-target="inpatient" class="btn btn-success btn-block" type="button"><?php echo lang('buttons:process')?></button>
						</div>
					</div>
					<div class="form-group">
                    	<?php echo form_label(lang('label:outpatient'), '', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-7">						
							<div class="progress" style="margin-top:10px">
								<div id="outpatient" class="progress-bar progress-bar-striped progress-bar-success active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
							</div>
						</div>
						<div class="col-md-2">
							<button name="btn_process" data-target="outpatient" class="btn btn-success btn-block" type="button"><?php echo lang('buttons:process')?></button>
						</div>
					</div>
					<div class="form-group">
						<?php echo form_label(lang('label:otc_drug'), '', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-7">						
							<div class="progress" style="margin-top:10px">
								<div id="otc_drug" class="progress-bar progress-bar-striped progress-bar-success active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
							</div>
						</div>
						<div class="col-md-2">
							<button name="btn_process" data-target="otc_drug" class="btn btn-success btn-block" type="button"><?php echo lang('buttons:process')?></button>
						</div>
					</div>
					<div class="form-group">
						<?php echo form_label(lang('label:deposit'), '', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-7">						
							<div class="progress" style="margin-top:10px">
								<div id="deposit" class="progress-bar progress-bar-striped progress-bar-success active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
							</div>
						</div>
						<div class="col-md-2">
							<button name="btn_process" data-target="deposit" class="btn btn-success btn-block" type="button"><?php echo lang('buttons:process')?></button>
						</div>
					</div>
					<div class="form-group">
						<?php echo form_label(lang('label:outstanding_disbursement'), '', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-7">						
							<div class="progress" style="margin-top:10px">
								<div id="outstanding" class="progress-bar progress-bar-striped progress-bar-success active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
							</div>
						</div>
						<div class="col-md-2">
							<button name="btn_process" data-target="outstanding" class="btn btn-success btn-block" type="button"><?php echo lang('buttons:process')?></button>
						</div>
					</div>
					<?php /*?><div class="form-group">
						<?php echo form_label(lang('label:copayment'), '', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-7">						
							<div class="progress" style="margin-top:10px">
								<div id="copayment" class="progress-bar progress-bar-striped progress-bar-success active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
							</div>
						</div>
						<div class="col-md-2">
							<button name="btn_process" data-target="copayment" class="btn btn-success btn-block" type="button"><?php echo lang('buttons:process')?></button>
						</div>
					</div><?php */?>
                </div>
			</div>
        </div>
    </div>
</div>
<?php echo form_hidden('mass_action', ''); ?>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
	
		var _form = $( "form[name=\"form_revenue_recognition\"]" );
		var _form_date = _form.find("input[name=\"f[Tanggal]\"]");
		var _btn_process = _form.find("button[name=\"btn_process\"]");
		var _target, _progress;
		var form_actions = {
				init: function(){
						
						_btn_process.on("click", function(){
							
							_btn_process.addClass('disabled');
							
							_target = $(this).data('target');
							var params = {'date': _form_date.val(), 'case' : _target}; 
							form_actions.progress_bar_state( 2 )
							form_actions.process( params );
						});
					},
				process: function( params ){
					
						var progression = 0,
						_progress = setInterval(function() {

								$( "#"+ _target ).css({'width':progression+'%'});
								
								if(progression == 100) { progression = -10;} 
								else { progression += 1; }
						
							}, 100);
							
						$.post( _form.prop('action'), params, function( response, status, xhr ){
							clearInterval( _progress );	
							if ( response.status == 'error' )
							{
								form_actions.progress_bar_state( 0 );
								$.alert_error( response.message );
								return;
							}
							
							$.alert_success( response.message );
							form_actions.progress_bar_state( 1 );
							
						}).fail(function() {
							clearInterval( _progress );	
							form_actions.progress_bar_state( 0 );
							$.alert_error( '<?php echo lang('general_error_label');?>' );
						}).always(function(){
							_btn_process.removeClass('disabled');
						});						
					},
				progress_bar_state: function( state ){
						
						switch (state)
						{
							case 1: 
								$( "#"+ _target )
									.addClass('progress-bar-success')
									.removeClass('progress-bar-danger active')
									.css({'width':'100%'});
							break;
							case 2: 
								$( "#"+ _target )
									.addClass('progress-bar-success active')
									.removeClass('progress-bar-danger')
									.css({'width':'0%'});
							break;
							case 0 :
								$( "#"+ _target )
									.addClass('progress-bar-danger')
									.removeClass('progress-bar-success active')
									.css({'width':'100%'});
							break;
						}
						
					},
			};
		
		$.fn.extend({
				dt_trans_revenue_recognition: function(){
						
					},
			});
		

		$( document ).ready(function(e) {
				form_actions.init();				
				
			});
	})( jQuery );
//]]>
</script>
