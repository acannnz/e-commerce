<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<?php echo form_open( $form_action, [
		'id' => 'form_discount', 
		'name' => 'form_discount', 
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
                <h3 class="panel-title"><?php echo (@$is_edit) ? lang('heading:discount_update') : lang('heading:discount_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-6">
                        <div class="form-group">
							<?php echo form_label(lang('label:code').' *', 'IDDiscount', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[IDDiscount]', set_value('f[IDDiscount]', @$item->IDDiscount, TRUE), [
										'id' => 'IDDiscount', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:name').' *', 'NamaDiscount', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[NamaDiscount]', set_value('f[NamaDiscount]', @$item->NamaDiscount, TRUE), [
										'id' => 'NamaDiscount', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:international_name'), 'NamaInternational', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[NamaInternational]', set_value('f[NamaInternational]', @$item->NamaInternational, TRUE), [
										'id' => 'NamaInternational', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:account'), 'AkunNo', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input([
												'type' => 'hidden',
												'name' => 'f[AkunNo]',
												'value' => set_value('f[AkunNo]', @$item->AkunNo, TRUE),
												'id' => 'AkunNo',
												'class' => 'AkunNo'
											]); ?>
											
									<?php echo form_input('NamaAkunNo', set_value('f[NamaAkunNo]', @$item->AkunNo.' '.@$account->Akun_Name, TRUE), [
												'id' => 'NamaAkunNo', 
												'readonly' => 'readonly',
												'class' => 'form-control AkunNo'
											]); ?>
									<span class="input-group-btn">
										<a href="javascript:;" data-action-url="<?php echo @$lookup_account ?>" data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('label:account'))?>" data-act="ajax-modal" class="btn btn-default btn-md" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" data-target-class="AkunNo"  class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:with_operator').' *', 'HaveD', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_dropdown('f[HaveD]', $dropdown_haved, set_value('f[HaveD]', @$item->HaveD, TRUE), [
										'id' => 'HaveD', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:service_group_discount'), 'GroupJasaID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<span class="input-group-addon">
										<?php echo form_radio('DiskonJasa', 1, (boolean) @$item->DiskonGroupJasa, [
												'data-target' => 'GroupJasaID', 
												'id' => 'DiskonGroupJasa'
											]); ?>
									</span>
									<?php 
										$dropdown_service_group[''] = 'NONE';
										echo form_dropdown('f[GroupJasaID]', $dropdown_service_group, set_value('f[GroupJasaID]', @$item->GroupJasaID, TRUE), [
											'id' => 'GroupJasaID', 
											'class' => 'form-control DiskonJasa'
										]); ?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:service_component_discount'), 'JenisLayanan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<span class="input-group-addon">
										<?php echo form_radio('DiskonJasa', 1, (boolean) @$item->DiskonKomponen, [
												'data-target' => 'KomponenBiayaID',
												'id' => 'DiskonKomponen' 
											]); ?>
									</span>
									<?php 
										$dropdown_service_component[''] = 'NONE';
										echo form_dropdown('f[KomponenBiayaID]', $dropdown_service_component, set_value('f[KomponenBiayaID]', @$item->KomponenBiayaID, TRUE), [
											'id' => 'KomponenBiayaID', 
											'class' => 'form-control DiskonJasa'
										]); ?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-3"></div>
							<div class="col-md-9 col-xs-12">
								<div class="row">
									<div class="col-md-6">
										<div class="checkbox">
											<label for="DiskonTotal">
												<?php echo form_hidden('f[DiskonTotal]', 0); ?>
												<?php echo form_checkbox('f[DiskonTotal]', 1, (boolean) @$item->DiskonTotal, [
													'id' => 'DiskonTotal', 
												]); ?>
												<?php echo lang('label:total_discount') ?>
											</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="checkbox">
											<label for="DiskonTdkLangsung">
												<?php echo form_hidden('f[DiskonTdkLangsung]', 0); ?>
												<?php echo form_checkbox('f[DiskonTdkLangsung]', 1, (boolean) @$item->DiskonTdkLangsung, [
													'id' => 'DiskonTdkLangsung', 
												]); ?>
												<?php echo lang('label:indirect_discount') ?>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-md-6">
								<div class="checkbox">
									<label for="DenganJasa">
										<?php echo form_hidden('f[DenganJasa]', 0); ?>
										<?php echo form_checkbox('f[DenganJasa]', 1, (boolean) @$item->DenganJasa, [
											'id' => 'DenganJasa', 
										]); ?>
										<?php echo lang('label:with_service') ?>
									</label>
								</div>
							</div>
							<div class="col-md-6 text-right">
								<a href="javascript:;" id="js-add-service" data-action-url="<?php echo @$lookup_service ?>" data-title="<?php echo lang('subtitle:account'); ?>" data-act="ajax-modal" data-modal-lg="1" class="btn btn-success btn-sm disabled"><i class="fa fa-plus"></i> <?php echo lang('buttons:add')?></a>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<table id="dt_form_discount_service" class="table table-bordered table-stripped table-hover" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th><i class="fa fa-gear"></i></th>
											<th><?php echo lang('label:service_code')?></th>
											<th><?php echo lang('label:service_name')?></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
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
		var _form = $("#form_discount");
		var _form_actions = {
				init: function(){
					_form.find('a.btn-clear').on('click', function(e){
						var _target_class = $(this).data('target-class');
						$('.'+ _target_class).val('');
					});
					
					// disaled all DiskonJasa
					$(".DiskonJasa").prop('disabled', 'disabled');
					var target = _form.find('input[name="DiskonJasa"]:checked').data('target');
					$('#'+ target).removeProp('disabled');
					
					_form.find('input[name="DiskonJasa"]').on('change', function(){
						target = $(this).data('target');
						
						$(".DiskonJasa").prop('disabled', 'disabled');
						$(".DiskonJasa").val('');
						$('#'+ target).removeProp('disabled');
					});
					
					if( _form.find('input[id="DenganJasa"]').is(':checked') ){
						$("#js-add-service").removeClass('disabled');
					}
					
					_form.find('input[id="DenganJasa"]').on('change', function(){
						if( $(this).is(':checked') ){
							$("#js-add-service").removeClass('disabled');
						} else {
							$("#js-add-service").addClass('disabled');
						}
					});
					
					
					_form.find("button#js-btn-submit").on("click", function(e){
						e.preventDefault();		
						
						var data_post = _form.serializeArray();
						
						data_post.push({name: 'f[DiskonGroupJasa]', value: $('#DiskonGroupJasa:checked').val() || 0 });
						data_post.push({name: 'f[DiskonKomponen]', value: $('#DiskonKomponen:checked').val() || 0 });
						
						$.each($('#dt_form_discount_service').DataTable().rows().data(), function(i, v){
							data_post.push({name: 'discount_service['+ i +'][IDDiscount]', value: _form.find('input[name="f[IDDiscount]"]').val() });
							data_post.push({name: 'discount_service['+ i +'][IDJasa]', value: v.JasaID });
						});
								
						$.post( _form.prop("action"), data_post, function( response, status, xhr ){
							
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
				}
			};
			
		var _datatable;
		var _datatable_actions = {
				delete_row: function( scope ){
					console.log(_datatable);
					_datatable.row( scope ).remove().draw();						
				},
			};
			
		$.fn.extend({
				DataTableInit: function(){
					
						var _this = this;
						if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						//function code for custom search
						var _datatable = _this.DataTable( {		
							processing: true,
							serverSide: false,								
							paginate: false,
							ordering: true,
							lengthMenu: [ 10, 30, 75],
							order: [[0, 'asc']],
							searching: false,
							info: false,
							responsive: true,
							data: <?php print_r(json_encode(@$discount_service, JSON_NUMERIC_CHECK)); ?>,
							columns: [
								{ 
									data: 'JasaID',
									orderable: false,
									searchable: false,
									render: function ( val, type, row ) {
										return '<a href="javascript:;" title="<?php echo lang( "buttons:remove" ) ?>" class="btn-remove"><i class="fa fa-trash"></i> <?php echo lang('buttons:delete')?></a>';
									  }
								},
								{ data: 'JasaID'},
								{ data: 'JasaName'}
							],
							createdRow: function ( row, data, index ){												
								$( row ).on( "click", "a.btn-remove", function(e){
										e.preventDefault();												
										var elem = $( e.target );
										
										if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
											//_datatable_actions.delete_row( index )
											$('#dt_form_discount_service').DataTable().row( row ).remove().draw();
										}
									})
							}
						});
							
					return _this;
				}

			});
			
		$( document ).ready(function(e) {				
				
				_form_actions.init();
				$('#dt_form_discount_service').DataTableInit();

			});

	})( jQuery );
//]]>
</script>
