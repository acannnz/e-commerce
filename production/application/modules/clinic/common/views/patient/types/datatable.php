<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="page-subtitle margin-bottom-20">
	<div class="row">
    	<div class="col-md-6">
        	<h3><?php echo lang('patient_types:list_heading') ?></h3>
        </div>
        <div class="col-md-6">
        	<a href="<?php echo base_url("common/patient-types/create") ?>" data-toggle="ajax-modal" title="<?php echo lang('buttons:add') ?>" class="btn btn-success pull-right"><i class="fa fa-plus-circle"></i> <span><?php echo lang('buttons:add') ?></span></a>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="dt-common-patienttypes" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th><?php echo lang('patient_types:code_label') ?></th>
                <th><?php echo lang('patient_types:type_label') ?></th>
                <th><?php echo lang('patient_types:state_label') ?></th>
                <th><?php echo lang('patient_types:updated_label') ?></th>
                <th></th>
            </tr>
        </thead>        
        <?php /*?><tfoot>
            <tr>
                <th><?php echo lang('patient_types:code_label') ?></th>
                <th><?php echo lang('patient_types:type_label') ?></th>
                <th><?php echo lang('patient_types:state_label') ?></th>
                <th><?php echo lang('patient_types:updated_label') ?></th>
                <th></th>
            </tr>
        </tfoot><?php */?>
        <tbody>
        </tbody>
    </table>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$.fn.extend({
				DataTable_CommonPatientTypes: function(){
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
									url: "<?php echo base_url("common/patient-types/datatable_collection") ?>",
									type: "POST",
									data: function( params ){}
								},
							columns: [
									{ 
										data: "code", 
										className: "a-right",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "type_name", width: "30%" },
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
												var buttons = "<a href=\"<?php echo base_url("common/patient-types/edit") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i></a>";
												buttons += "<a href=\"<?php echo base_url("common/patient-types/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-times\"></i></a>";
												return buttons
											}
									}
								]
						} );
					
					$( "#dt-common-patienttypes_length select, #dt-common-patienttypes_filter input" )
						.addClass( "form-control" );

					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-common-patienttypes" ).DataTable_CommonPatientTypes();
			});
	})( jQuery );
//]]>
</script>