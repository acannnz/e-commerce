<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');	
?>
<?php echo form_open( $form_action, [
		'id' => 'form_action', 
		'name' => 'form_action', 
		'rule' => 'form', 
		'class' => 'form-horizontal form-label-left parsley-form',
	]); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('heading:clerk_view') ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-12">
				<h2 class="page-header text-center"><?php echo '<span id="KodeClerk">'. @$item->KodeClerk .'</span>' ?></h2>
			</div>
			<div class="col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-12">
				<?php if(isset($item->StatusClerk) && $item->StatusClerk == 0):?>
					
					<div class="alert alert-success">
						<h4><i class="fa fa-check-circle"></i> Success...</h4>
						<?php echo lang('message:done_clerk_start')?> <br/>
						<?php if($item->StatusClerk == 0): ?> 
							<?php echo lang('message:allowed_transaction') ?>
							<a href="<?php echo base_url('pharmacy/selling') ?>" ><?php echo lang('label:click_to_transaction')?></a>
						<?php endif; ?>
					</div>			
					<div class="alert alert-info">
						<h4><i class="fa fa-exclamation-circle"></i> Info...</h4>
						<?php echo lang('message:clerk_end')?>
					</div>
					
					<p class="lead"><?php echo lang('label:amount_system') ?></p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php echo lang('label:code')?></th>
								<th><?php echo lang('label:date')?></th>
								<th class="text-right"><?php echo lang('label:qty_sales')?></th>
								<th class="text-right"><?php echo lang('label:amount_total')?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								
								<td><?php echo @$item->KodeClerk?></td>
								<td><?php echo @$item->WaktuMulaiClerk?></td>
								<td class="text-right"><?php echo number_format(@$item->JumlahTransaksi)?></td>
								<td class="text-right"><?php echo number_format(@$item->JumlahTotalSystem)?></td>
							</tr>
						</tbody>
					</table>
					
					<p class="lead"><?php echo lang('label:amount_clerk') ?></p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php echo lang('label:payment_type')?></th>
								<th class="text-right"><?php echo lang('label:amount_system')?></th>
								<th class="text-right"><?php echo lang('label:amount_clerk')?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach((array) @$clerk_payment as $row):?>
							<tr>
								<td><?php echo @$row->Description ?></td>
								<td class="text-right"><?php echo @$row->JumlahTotal ?></td>
								<td>
									<?php echo form_input([
										'type' => 'text',
										'name' => "f[{$row->JenisBayarID}]",
										'id' => "{$row->JenisBayarID}", 
										'autocomplete' => 'off',
										'placeholder' => lang('label:amount') . @$row->Description,
										'class' => 'form-control mask-number text-right clerk',
									]); ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
						<thead>
							<tr>
								<th><?= 'Uang Awal: '. number_format(@$item->JumlahAwalUangKasir) ?></th>
								<th class="text-right"><?php echo number_format(@$item->JumlahTotalSystem)?></th>
								<th id="amount_clerk" class="text-right"></th>
							</tr>
							<tr>
								<th><?php echo lang('label:total')?></th>
								<th class="text-right"><?php echo number_format(@$item->JumlahAwalUangKasir + @$item->JumlahTotalSystem)?></th>
								<th id="total_amount" class="text-right"></th>
							</tr>
						</thead>
					</table>
					
					<?php echo form_input([
						'type' => 'password',
						'name' => 'password',
						'id' => 'password', 
						'placeholder' => lang('label:enter_password'),
						'class' => 'form-control',
						'required' => 'required'
					]); ?>

					<div class="progress" style="margin-top:10px">
						<div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-success active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
					</div>
					<?php echo form_button([
						'id' => 'js-btn-process',
						'class' => 'btn btn-danger btn-block',
						'type' => 'submit',
						'content' => "<i class='fa fa-dot-circle-o fa-spin'></i> <b>". lang('label:clerk_end') ."</b>",
						'data-type' => 'end',
						'data-target' => '#progress-bar',
					]); ?>
					
				<?php else:?>
				
					<div class="alert alert-success">
						<h4><i class="fa fa-check-circle"></i> Success...</h4>
						<?php echo lang('message:done_clerk_start')?> <br/>
					</div>		
					<div class="alert alert-success">
						<h4><i class="fa fa-check-circle"></i> Success...</h4>
						<?php echo lang('message:done_clerk_end')?>
					</div>
					
					<p class="lead"><?php echo lang('label:amount_system') ?></p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php echo lang('label:code')?></th>
								<th><?php echo lang('label:date')?></th>
								<th class="text-right"><?php echo lang('label:qty_sales')?></th>
								<th class="text-right"><?php echo lang('label:amount_total')?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								
								<td><?php echo @$item->KodeClerk?></td>
								<td><?php echo @$item->WaktuMulaiClerk?></td>
								<td class="text-right"><?php echo number_format(@$item->JumlahTransaksi)?></td>
								<td class="text-right"><?php echo number_format(@$item->JumlahTotalSystem)?></td>
							</tr>
						</tbody>
					</table>
					
					<p class="lead"><?php echo lang('label:amount_clerk') ?></p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th><?php echo lang('label:payment_type')?></th>
								<th class="text-right"><?php echo lang('label:amount_system')?></th>
								<th class="text-right"><?php echo lang('label:amount_clerk')?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach((array) @$clerk_payment as $row):?>
							<tr>
								<td><?php echo @$row->Description ?></td>
								<td class="text-right"><?php echo @$row->JumlahTotal ?></td>
								<td>
									<?php echo form_input([
										'type' => 'text',
										'value' => (float) @$clerk_detail[$row->JenisBayarID]->JumlahTotal,
										'name' => "f[{$row->JenisBayarID}]",
										'id' => "{$row->JenisBayarID}", 
										'autocomplete' => 'off',
										'placeholder' => lang('label:amount') . @$row->Description,
										'class' => 'form-control mask-number text-right clerk',
									]); ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
						<thead>
							<tr>
								<th><?= 'Uang Awal: '. number_format(@$item->JumlahAwalUangKasir) ?></th>
								<th class="text-right"><?php echo number_format(@$item->JumlahTotalSystem)?></th>
								<th id="amount_clerk" class="text-right"></th>
							</tr>
							<tr>
								<th><?php echo lang('label:total')?></th>
								<th class="text-right"><?php echo number_format(@$item->JumlahAwalUangKasir + @$item->JumlahTotalSystem)?></th>
								<th id="total_amount" class="text-right"></th>
							</tr>
						</thead>
					</table>
					
					<?php /*
					<?php echo form_input([
						'type' => 'password',
						'name' => 'password',
						'id' => 'password', 
						'placeholder' => lang('label:enter_password'),
						'class' => 'form-control',
						'required' => 'required'
					]); ?>

					<div class="progress" style="margin-top:10px">
						<div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-success active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
					</div>
					<?php echo form_button([
						'id' => 'js-btn-process',
						'class' => 'btn btn-danger btn-block',
						'type' => 'submit',
						'content' => "<i class='fa fa-dot-circle-o fa-spin'></i> <b>". lang('buttons:save') ."</b>",
						'data-type' => 'end',
						'data-target' => '#progress-bar',
					]); ?> */ ?>
				<?php endif;?>
			</div>
		</div>
		<div class="ln_solid"></div>
	</div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){				
		var _form = $( "form[name=\"form_action\"]" );
		var _btn_process = $("#js-btn-process");
		var _target, type, _progress;
		var form_actions = {
				init: function(){	
						form_actions.calculate_total();
						
						$('.clerk').on('keyup', function(e){
							form_actions.calculate_total();
						});

						_form.on("submit", function(e){
							e.preventDefault();
							
							_btn_process.addClass('disabled');
							
							_type = _btn_process.data('type');
							_target = _btn_process.data('target');
							
							if(_type == 'start'){
								var params = {f: {KodeClerk: '<?php echo @$item->KodeClerk ?>'}};
							}else if (_type == 'end'){
								
								var params = {
										KodeClerk: '<?php echo @$item->KodeClerk ?>',
										f: {
											JumlahTransaksi: '<?php echo @$item->JumlahTransaksi ?>',
											JumlahTotalSystem: '<?php echo @$item->JumlahTotalSystem ?>',
											JumlahTotalClerk: mask_number.currency_remove($('#amount_clerk').html()) || 0
										},
										d: {}
									};
									
								$('.clerk').each(function(index, element) {
									params['d'][index] = {
										KodeClerk: '<?php echo @$item->KodeClerk ?>',
										JenisBayarID: $(this).prop('id'),
										JumlahTotal: mask_number.currency_remove($(this).val()) || 0
									}
								});
							}
							
							params['type'] = _type;
							params['password'] = $('#password').val();
							form_actions.progress_bar_state( 2 )
							form_actions.process( params );
							
						});
					},
				calculate_total: function(){
					var _total = 0;
					$('.clerk').each(function(){
						_total = _total + mask_number.currency_remove($(this).val() || 0);
					});
					
					var _total_amount = _total + <?= $item->JumlahAwalUangKasir ?>;
					$('#amount_clerk').html(mask_number.currency_add(_total));
					$('#total_amount').html(mask_number.currency_add(_total));
					return _total;
				},
				process: function( params ){						
						var progression = 0,
						_progress = setInterval(function() {

								$( _target ).css({'width':progression+'%'});
								
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
							window.location = '<?php echo base_url( $nameroutes )?>';
							// window.location = '<?php echo base_url( $nameroutes .'/end/'. @$item->KodeClerk )?>';
							
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
								$( _target )
									.addClass('progress-bar-success')
									.removeClass('progress-bar-danger active')
									.css({'width':'100%'});
							break;
							case 2: 
								$( _target )
									.addClass('progress-bar-success active')
									.removeClass('progress-bar-danger')
									.css({'width':'0%'});
							break;
							case 0 :
								$( _target )
									.addClass('progress-bar-danger')
									.removeClass('progress-bar-success active')
									.css({'width':'100%'});
							break;
						}
						
					},
			};
					
		$( document ).ready(function(e) {
				form_actions.init();	
			});

	})( jQuery );
//]]>
</script>