<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_room_status', 
		'name' => 'form_room_status', 
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
                <h3 class="panel-title"><?php echo (@$is_edit) ? lang('heading:room_status_update') : lang('heading:room_status_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-12 col-xs-12">
                        <div class="form-group">
							<?php echo form_label(lang('label:room_number').' *', 'NoKamar', ['class' => 'control-label col-md-3']) ?>
							<?php echo form_hidden('f[NoKamar]', @$item->NoKamar); ?>
							<?php echo form_hidden('f[SectionID]', @$item->SectionID); ?>
							<div class="col-md-9">
								<?php if(@$is_edit):?>	
									<?php echo form_input('NoKamar', @$item->NoKamar .' - '. $sal->SectionName, [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>	
								<?php else: ?>
									<div class="row lookupbox7-form-control">
										<div class="col-sm-8 col-xs-12">
											<?php echo form_input('NoKamar', @$item->NoKamar, [
													'placeholder' => '', 
													'id' => 'NoKamarView',
													'class' => 'form-control lookupbox7-input-search'
												]); ?>
										</div>
										<div class="col-sm-4 col-xs-12">
											<?php echo form_button([
													'type' => 'button',
													'content' => lang('buttons:search'),
													'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
												]); ?>										
										</div>
									</div>
								<?php endif;?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:status').' *', 'Status', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[Status]', @$dropdown_status, set_value('f[Status]', @$item->Status, TRUE), [
										'id' => 'Status', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:start_date').' *', 'StartDate', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input([
										'name' => 'f[StartDate]', 
										'value' => set_value('f[StartDate]', @$item->StartDate, TRUE),
										'id' => 'NoLantai', 
										'placeholder' => '', 
										'required' => 'required', 
										'class' => 'form-control datepicker',
										'autocomplete' => 'off'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:note').' *', 'Keterangan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_textarea('f[Keterangan]', @$item->Keterangan, [
									'placeholder' => '', 
									'id' => 'Keterangan',
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

<script src="<?php echo site_url("themes/bracketadmin/vendor/lookupbox7/jquery.lookupbox7.js"); ?>"></script>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("#form_room_status");
				
		$( document ).ready(function(e) {
				
				_form.on("submit", function(e){
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
				
				$('#NoKamarView').lookupbox7({
						remote: '<?php echo site_url('others/room/lookup_collection'); ?>',
						title: 'Daftar Kamar',
						columns: [
								{data: "NoKamar", orderable: true, searchable: true},
								{data: "Status", orderable: true, searchable: true},
								{data: "SectionName", orderable: true, searchable: true},
								{data: "NamaKelas", orderable: true, searchable: true},
							],
						headings: ['<?php echo lang('label:room_number')?>', '<?php echo lang('label:state')?>','<?php echo lang('label:sal')?>', '<?php echo lang('label:class')?>'],
						order: [[0, 'asc']],
						placeholder: 'Ketik cari',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
							_form.find('#NoKamarView').val(v.NoKamar +' - '+ v.SectionName +' ('+ v.Status +')');
							_form.find('input[name="f[NoKamar]"]').val(v.NoKamar);
							_form.find('input[name="f[SectionID]"]').val(v.SalID);
						}
					});

			});

	})( jQuery );
//]]>
</script>
