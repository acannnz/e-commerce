<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="col-md-offset-2 col-md-8">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang('cash_flow:list_heading'); ?></h3>
			<ul class="panel-btn">
				<li><a href="<?php echo base_url("general-ledger/cash-flow/create") ?>" data-toggle='form-ajax-modal' class="btn btn-info" title="<?php echo lang('buttons:add') ?>"><b><i class="fa fa-plus"></i> <?php echo lang('buttons:add') ?></b></a></li>
			</ul>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="table-responsive">
					<table id="dt-general_ledger-cash_flow" class="table table-sm table-bordered" width="100%">
						<thead>
							<tr>
								<th><?php echo lang('cash_flow:group_label') ?></th>
								<th><?php echo lang('cash_flow:subgroup_label') ?></th>
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
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$.fn.extend({
				DataTableInit: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[0, 'asc']],
							searching: true,
							info: true,
							ajax: {
									url: "<?php echo base_url("general_ledger/cash_flow/datatable_collection") ?>",
									type: "POST",
									data: function( params ){}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "Group_Name", 
										name: "a.Group_Name", 
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "Sub_Group_Name", name: "b.Group_Name",  },
									{ 
										data: "Group_id",
										className: "text-center",
										//width: "120px",
										orderable: false,
										render: function ( val, type, row ){
												var buttons = '<div class="btn-group" role="group">';
													buttons += "<a href=\"<?php echo base_url("general_ledger/cash_flow/edit") ?>/" + val + "\" title=\"<?php echo lang( "buttons:edit" ) ?>\" data-toggle='form-ajax-modal' class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i> Edit</a>";
													buttons += "<a href=\"<?php echo base_url("general_ledger/cash_flow/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-trash\"></i></a>";
													buttons += "</div>";
												return buttons
											}
									}
								]
						} );
					
					$( "#dt-general_ledger-cash_flow_length select, #dt-general_ledger-cash_flow_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-general_ledger-cash_flow" ).DataTableInit();
			});
	})( jQuery );
//]]>
</script>