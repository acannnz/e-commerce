<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row mb10">
	<div class="col-md-12">
		<a href="javascript:;" data-action-url="<?php echo @$add_service_section ?>" data-act="ajax-modal"  data-title="<?php echo sprintf('%s %s', lang('buttons:search'), lang('subtitle:service_section')) ?>"  data-act="ajax-modal" class="btn btn-primary btn-sm pull-right"><b><i class="fa fa-plus"></i> <?php echo lang('buttons:add')?></b></a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<table id="dt_form_section" class="table table-bordered table-hover" width="100%" cellspacing="0">
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

<script type="text/javascript">
//<![CDATA[
(function( $ ){

		var _datatable;		
		var _datatable_actions = {
				remove: function( params, fn, scope ){
						_datatable.row( scope ).remove().draw();							
					}
			};
		
		$.fn.extend({
				dataTableFormSection: function(){
						var _this = this;
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						_datatable = _this.DataTable( {
								dom: 'tip',
								processing: true,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								responsive: true,
								scrollCollapse: true,
								data: <?php print_r(json_encode(@$collection_section, JSON_NUMERIC_CHECK)); ?>,
								columns: [
										{ 
											data: "SectionID", 
											className: "actions text-center", 
											orderable: false,
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-xs btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "SectionID"},
										{ data: "SectionName"},
									],
								createdRow: function ( row, data, index ){													
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, null, row )
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
				
            	$( "#dt_form_section" ).dataTableFormSection();

			});

	})( jQuery );
//]]>
</script>
