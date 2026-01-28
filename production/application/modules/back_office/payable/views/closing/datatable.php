<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="page-subtitle margin-bottom-20">
	<div class="row">
    	<div class="col-md-6">
        	<h3><?php echo lang('closing:list_heading') ?></h3>
        </div>
        <div class="col-md-6">
        	<a href="<?php echo base_url("payable/close_books/create") ?>" data-toggle="ajax-modal" title="<?php echo lang('buttons:add') ?>" class="btn btn-success pull-right"><i class="fa fa-plus-circle"></i> <span><?php echo lang('buttons:add') ?></span></a>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="dt-payable-close_books" class="table table-bordered">
        <thead>
            <tr>
                <th><?php echo lang('closing:level_label') ?></th>
                <th><?php echo lang('closing:digit_label') ?></th>
                <th><?php echo lang('closing:state_label') ?></th>
                <th><?php echo lang('closing:updated_label') ?></th>
                <th></th>
            </tr>
        </thead>        
        <tbody>
        </tbody>
    </table>
</div>
<script account_level="text/javascript">
//<![CDATA[
(function( $ ){
		$.fn.extend({
				DataTable_ReceivableAccount_levels: function(){
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
									url: "<?php echo base_url("payable/close_books/datatable_collection") ?>",
									account_level: "POST",
									data: function(data){
									}
								},
							columns: [
									{ 
										data: "level", 
										className: "a-right",
										render: function ( val, account_level, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "digit",  },
									{ 
										data: "state", 
										className: "a-center",
										render: function ( val, account_level, row ){
												return (1 == val) ? "<span class=\"label label-info\"><?php echo lang( "global:active" ) ?></span>" : "<span class=\"label label-danger\"><?php echo lang( "global:inactive" ) ?></span>"
											}
									},
									{ 
										data: "updated_at", 
										className: "a-right",
										render: function ( val, account_level, row ){
												return "<em>" + val + "</em>"
											}
									},
									{ 
										data: "id",
										className: "a-right actions",
										orderable: false,
										render: function ( val, account_level, row ){
												var buttons = "<a href=\"<?php echo base_url("payable/close_books/edit") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i></a>";
												buttons += "<a href=\"<?php echo base_url("payable/close_books/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-times\"></i></a>";
												return buttons
											}
									}
								]
						} );
					
					$( "#dt-payable-close_books_length select, #dt-payable-close_books_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-payable-close_books" ).DataTable_ReceivableAccount_levels();
			});
	})( jQuery );
//]]>
</script>