<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$province_id = (int) @$item->id;
?>
<div class="table-responsive margin-bottom-40">
    <table id="dt-common-zones-county" class="table" width="100%">
        <thead>
            <tr>
                <th><?php echo lang('zones:code_label') ?></th>
                <th><?php echo lang('zones:county_label') ?></th>
                <th><?php echo lang('zones:state_label') ?></th>
                <th><?php echo lang('zones:updated_label') ?></th>
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
				DataTable_CommonZones_County: function(){
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
									url: "<?php echo base_url("common/zones/datatable_collection/county/{$province_id}") ?>",
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
									{ 
										data: "zone_name", 
										width: "30%",
										render: function ( val, type, row ){
												return "<a href=\"<?php echo base_url("common/zones/district/") ?>/" + row.id + "\" title=\"Show the districts from here\"><i class=\"fa fa-folder-o\"></i> " + row.zone_name + "</a>";
											}
									},
									{ 
										data: "state", 
										className: "a-center",
										searchable: false,
										render: function ( val, type, row ){
												return (1 == val) ? "<span class=\"label label-info\"><?php echo lang( "global:active" ) ?></span>" : "<span class=\"label label-danger\"><?php echo lang( "global:inactive" ) ?></span>"
											}
									},
									{ 
										data: "updated_at", 
										className: "a-right",
										searchable: false,
										render: function ( val, type, row ){
												return "<em>" + val + "</em>"
											}
									},
									{ 
										data: "id",
										className: "a-right actions",
										orderable: false,
										searchable: false,
										render: function ( val, type, row ){
												var buttons = "<a href=\"<?php echo base_url("common/zones/county/edit") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i></a>";
												buttons += "<a href=\"<?php echo base_url("common/zones/county/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-times\"></i></a>";
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-common-zones-county_length select, #dt-common-zones-county_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-common-zones-county" ).DataTable_CommonZones_County();
			});
	})( jQuery );
//]]>
</script>