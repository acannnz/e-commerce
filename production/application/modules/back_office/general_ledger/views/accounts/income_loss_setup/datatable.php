<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col-md-offset-2 col-md-8">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang('income_loss_setup:list_heading') ?></h3>
			<ul class="panel-btn">
				<li><a href="<?php echo base_url("general-ledger/account/income-loss-setup/setup") ?>" title="<?php echo lang('buttons:setup') ?>" class="btn btn-info pull-right"><i class="fa fa-plus-circle"></i> <span><?php echo lang('buttons:create') ?></span></a></li>
			</ul>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="table-responsive">
					<table id="dt-income_loss_setup" class="table table-bordered table-striped table-sm">
						<thead>
							<tr>
								<th><?php echo lang('income_loss_setup:account_number_label') ?></th>
								<th><?php echo lang('income_loss_setup:account_name_label') ?></th>
								<th><?php echo lang('income_loss_setup:type_label') ?></th>
								<th></th>
							</tr>
						</thead>        
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script account_level="text/javascript">
//<![CDATA[
(function( $ ){
		$.fn.extend({
				DataTable_Income_loss_setup: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: false,								
							paginate: false,
							ordering: false,
							order: [[0, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("general-ledger/account/income-loss-setup/datatable_collection") ?>",
									type: "POST",
									data: function(data){
									}
								},
							columns: [
									{ 
										data: "Keterangan", 
									},
									{ 
										data: "Akun_No", 
										className: "a-right",
										render: function ( val, account_level, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "Akun_Name",  },

									{ 
										data: "Akun_ID",
										className: "a-right actions",
										orderable: false,
										render: function ( val, account_level, row ){
												var buttons = "<a href=\"<?php echo base_url("general-ledger/account/income-loss-setup/edit") ?>/" + val + "\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:edit" ) ?></a>";
												buttons += "<a href=\"<?php echo base_url("general-ledger/account/income-loss-setup/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-times\"></i></a>";
												return buttons
											}
									}
								]
						} );
					
					$( "#dt-income_loss_setup_length select, #dt-income_loss_setup_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-income_loss_setup" ).DataTable_Income_loss_setup();
			});
	})( jQuery );
//]]>
</script>