<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="table-responsive">
	<?php echo form_open( "", array("id" => "datatables_form", "name" => "datatables_form", "class" => "form-horizontal", "role" => "form") ); ?>
		<table id="dt-common-chart-templates" class="table table-striped table-bordered">
			<thead>
				<tr>
					<th><?php echo lang('chart_template:complaint_label') ?></th>
					<th><?php echo lang('chart_template:subjective_label') ?></th>
					<th><?php echo lang('chart_template:objective_label') ?></th>
					<th><?php echo lang('chart_template:assessment_label') ?></th>
					<th><?php echo lang('chart_template:plan_label') ?></th>
					<th><?php echo lang('chart_template:state_label') ?></th>
					<th><?php echo lang('chart_template:updated_label') ?></th>
					<th></th>
				</tr>
			</thead>        
			<tbody>
			</tbody>
		</table>
	<?php echo form_close() ?>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$.fn.extend({
				DataTable_CommonChartTemplates: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[1, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("common/chart-templates/datatable_collection") ?>",
									type: "POST",
									data: function( params ){}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "chief_complaint", 
										className: "a-right",
									},
									{ 
										data: "subjective", 
										className: "a-right" 
									},
									{ 
										data: "objective", 
										className: "a-right",
									},
									{ 
										data: "assessment", 
										className: "a-right",
									},
									{ 
										data: "plan", 
										className: "a-right",
									},
									{ 
										data: "state", 
										className: "a-center",
										render: function ( val, type, row ){
												return (1 == val) ? "<span class=\"label label-info\"><?php echo lang( "global:active" ) ?></span>" : "<span class=\"label label-danger\"><?php echo lang( "global:inactive" ) ?></span>"
											}
									},
									{ 
										data: "updated_at", 
										className: "a-right",
										render: function ( val, type, row ){
												return "<em>" + val + "</em>"
											}
									},
									{ 
										data: "id",
										className: "a-right actions",
										orderable: false,
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("common/chart-templates/edit") ?>/" + val + "\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-default btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:edit" ) ?> </a>";
													buttons += "<a href=\"<?php echo base_url("common/chart-templates/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"> <i class=\"fa fa-times\"></i> </a>";
												buttons += "</div>";
												
												return buttons
											}
									}
								]
						} );
					
					$( "#dt-common-chart-templates_length select, #dt-common-chart-templates_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-common-chart-templates" ).DataTable_CommonChartTemplates();
			});
	})( jQuery );
//]]>
</script>