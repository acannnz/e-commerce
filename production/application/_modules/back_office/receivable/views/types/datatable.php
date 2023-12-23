<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="page-subtitle margin-bottom-20">
	<div class="row">
    	<div class="col-md-6">
        	<h3><?php echo lang('types:list_heading') ?></h3>
        </div>
        <div class="col-md-6">
        	<a href="<?php echo base_url("receivable/types/create") ?>" title="<?php echo lang('buttons:add') ?>" class="btn btn-success pull-right"><i class="fa fa-plus-circle"></i> <span><?php echo lang('buttons:add') ?></span></a>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="dt-receivable-types" class="table table-bordered table-sm">
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
									url: "<?php echo base_url("receivable/types/datatable_collection") ?>",
									type: "POST",
									data: function( params ){}
								},
							columns: [
									{ 
										data: "TypePiutang_ID", 
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
										data: "Default_Type_Piutang", 
										className: "a-right",
										render: function ( val, type, row ){
												return  val ? '<?php echo lang("global:yes") ?>' : '<?php echo lang("global:no") ?>'
											}
									},
									{ 
										data: "TypePiutang_ID",
										className: "a-right actions",
										orderable: false,
										render: function ( val, type, row ){
												var buttons = "<a href=\"<?php echo base_url("receivable/types/edit") ?>/" + val + "\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i></a>";
												buttons += "<a href=\"<?php echo base_url("receivable/types/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-times\"></i></a>";
												return buttons
											}
									}
								]
						} );
					
					$( "#dt-receivable-types_length select, #dt-receivable-types_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-receivable-types" ).DataTable_PayableTypes();
			});
	})( jQuery );
//]]>
</script>