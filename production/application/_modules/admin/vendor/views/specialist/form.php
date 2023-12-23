<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_specialist', 
		'name' => 'form_specialist', 
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
                <h3 class="panel-title"><?php echo lang('heading:vendor_specialist_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-12 col-xs-12">
                        <div class="form-group">
                            <?php echo form_label(lang('label:name'), 'SpesialisName', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[SpesialisName]', set_value('f[SpesialisName]', @$item->SpesialisName, TRUE), [
										'id' => 'SpesialisName', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-6">
						<h4 class="subtitle"><?php echo lang('subtitle:vendor_sub_specialist')?></h4>
					</div>
					<div class="col-md-6">
						<a href="javascript:;" data-action-url="<?php echo @$add_sub_specialist ?>" data-act="ajax-modal"  data-title="<?php echo lang('heading:vendor_sub_specialist_create')?>"  data-act="ajax-modal" class="btn btn-primary btn-sm pull-right"><b><i class="fa fa-plus"></i> <?php echo lang('buttons:add')?></b></a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<table id="dt_sub_specialist" class="datatables table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th></th>
									<th><?php echo lang('label:code') ?></th>
									<th><?php echo lang('label:name') ?></th>
								</tr>
							</thead>        
							<tbody>
							</tbody>
						</table>
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
		var _form = $("#form_specialist");
				
		$.fn.extend({
				dataTableInit: function(){
						var _this = this;
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						_datatable = _this.DataTable( {
								dom: 'tip',
								processing: false,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								responsive: true,
								data: <?php print_r(json_encode(@$sub_specialist_collection, JSON_NUMERIC_CHECK)); ?>,
								columns: [
										{ 
											data: "SubSpesialisID", 
											className: "actions text-center", 
											render: function( val, type, row ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "SubSpesialisID" },
										{ data: "SubSpesialisName" },
									],
								createdRow: function ( row, data, index ){																							
										$( row ).attr('data-action-url', '<?php echo base_url('vendor/sub_specialist/form') ?>/'+ index);
										$( row ).attr('data-act', 'ajax-modal');
										$( row ).attr('data-title', '<?php echo lang('heading:vendor_sub_specialist_create')?>');
										//$( row ).attr('data-modal-lg', 1);
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable.row( row ).remove().draw();
												}
											})
									}
							} );
							
						$( "#dt_trans_purchase_request_detail_length select, #dt_trans_purchase_request_detail_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
				
		$( document ).ready(function(e) {
			
				$('#dt_sub_specialist').dataTableInit();
						
				_form.find("button#js-btn-submit").on("click", function(e){
					e.preventDefault();		
					
					var data_post = {specialist:{}, sub_specialist:{}};
						data_post['specialist'] = {
							SpesialisName : _form.find('input[name=\"f[SpesialisName]\"]').val()
						}
					
					var sub_table = $('#dt_sub_specialist').DataTable().rows().data();
					$.each(sub_table, function(i, v){
						data_post['sub_specialist'][i] = {
							SubSpesialisID : v.SubSpesialisID,
							SubSpesialisName : v.SubSpesialisName
						}
					});
					
							
					$.post( _form.prop("action"), data_post, function( response, status, xhr ){
						
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
