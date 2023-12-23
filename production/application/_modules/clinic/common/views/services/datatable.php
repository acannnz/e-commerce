<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="table-responsive">
    <table id="dt-common-services" class="table">
        <thead>
            <tr>
                <th><?php echo lang('services:code_label') ?></th>
                <th><?php echo lang('services:title_label') ?></th>
                <th><?php echo lang('services:price_label') ?></th>
                <th><?php echo lang('services:state_label') ?></th>
                <th><?php echo lang('services:updated_label') ?></th>
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
				DataTable_CommonServices: function(){
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
									url: "<?php echo base_url("common/services/datatable_collection") ?>",
									type: "POST",
									data: function( params ){}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "code", 
										className: "a-right",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "service_title", width: "30%" },
									{ 
										data: "service_price", 
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
												var buttons = "<a href=\"<?php echo base_url("common/services/edit") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i></a>";
												buttons += "<a href=\"<?php echo base_url("common/services/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-times\"></i></a>";
												return buttons
											}
									}
								]
						} );
					
					$( "#dt-common-services_length select, #dt-common-services_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-common-services" ).DataTable_CommonServices();
			});
	})( jQuery );
//]]>
</script>