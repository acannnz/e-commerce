<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open(  current_url(), [
		'id' => 'form_icd', 
		'name' => 'form_icd', 
		'rule' => 'form', 
		'class' => ''
	]); ?>
<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo (@$is_edit) ? lang('icd:edit_heading') : lang('icd:create_heading')?></h3>
		</div>
		<div class="panel-body">
				<div class="row">
					<div class="col-md-12 col-xs-12">
                        <div class="form-group">
							<?php echo form_label('Kode ICD *', 'KodeICD', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[KodeICD]', set_value('f[KodeICD]', @$item->KodeICD, TRUE), [
										'id' => 'KodeICD', 
										'placeholder' => '', 
										'class' => 'form-control',
									]); ?>
							</div>
                        </div>
						<?php 
							if(config_item('bpjs_bridging') == 'TRUE')
								echo modules::run('bpjs/icd_bpjs/form_mapping', @$item->KodeICDBPJS);
						?>
						<div class="form-group">
							<?php echo form_label('Deskripsi *', 'Descriptions', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Descriptions]', set_value('f[Descriptions]', @$item->Descriptions, TRUE), [
										'id' => 'Descriptions', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-lg-12 text-right">
						<button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
						<button id="js-btn-submit" type="button" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("#form_icd");
				
		$( document ).ready(function(e) {
			
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