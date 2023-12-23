<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('types:list_heading'); ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo base_url("payable/types/create")?>"  class="btn btn-info pull-right"><b><i class="fa fa-plus"></i> <?php echo lang( 'buttons:create' ) ?></b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table id="dt-payable-types" class="table table-bordered table-sm">
				<thead>
					<tr>
						<th><?php echo lang('types:code_label') ?></th>
						<th><?php echo lang('types:type_label') ?></th>
						<th><?php echo lang('types:account_number_label') ?></th>
						<th><?php echo lang('types:account_name_label') ?></th>
						<th><?php echo lang('types:default_label') ?></th>
						<th></th>
					</tr>
				</thead>        
				
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$.fn.extend({
				DataTable_PayableTypes: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[0, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("payable/types/datatable_collection") ?>",
									type: "POST",
									data: function( params ){}
								},
							columns: [
									{ 
										data: "TypeHutang_ID", 
										className: "a-right",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "Nama_Type", width: "30%" },
									{ 
										data: "Akun_No", 
										className: "a-right",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "Akun_Name", 
										className: "a-center",
									},
									{ 
										data: "Default_Type_Hutang", 
										className: "a-right",
										render: function ( val, type, row ){
												return  val ? '<?php echo lang("global:yes") ?>' : '<?php echo lang("global:no") ?>'
											}
									},
									{ 
										data: "TypeHutang_ID",
										className: "a-right actions",
										orderable: false,
										render: function ( val, type, row ){
												var buttons = '<div class="btn-group">'
												buttons	+= "<a href=\"<?php echo base_url("payable/types/edit") ?>/" + val + "\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i></a>";
												buttons += "<a href=\"<?php echo base_url("payable/types/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-trash\"></i></a>";
												buttons += '</div>';
												return buttons
											}
									}
								]
						} );
					
					$( "#dt-payable-types_length select, #dt-payable-types_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-payable-types" ).DataTable_PayableTypes();
			});
	})( jQuery );
//]]>
</script>