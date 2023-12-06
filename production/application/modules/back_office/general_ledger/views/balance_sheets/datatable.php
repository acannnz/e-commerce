<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="table-responsive">
    <table id="dt-common-account" class="table" width="100%">
        <thead>
            <tr>
                <th><?php echo lang('accounts:account_number_label') ?></th>
                <th><?php echo lang('accounts:account_name_label') ?></th>
                <th><?php echo lang('accounts:state_label') ?></th>
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
				DataTableAccountingAccount: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							lengthMenu: [ 15, 30, 60, 120 ],
							ordering: true,
							order: [[0, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("general_ledger/accounts/datatable_collection") ?>",
									type: "POST",
									data: function( params ){}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "account_number", 
										className: "a-right",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "account_name", width: "30%" },
								
									{ 
										data: "state", 
										className: "a-center",
										render: function ( val, type, row ){
												return (1 == val) ? "<span class=\"label label-info\"><?php echo lang( "global:active" ) ?></span>" : "<span class=\"label label-danger\"><?php echo lang( "global:inactive" ) ?></span>"
											}
									},
									
									<?php /*?>{ 
										data: "id",
										className: "a-right actions",
										orderable: false,
										render: function ( val, type, row ){
												var buttons = "<a href=\"<?php echo base_url("common/accounts/edit") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i></a>";
												buttons += "<a href=\"<?php echo base_url("common/accounts/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-times\"></i></a>";
												return buttons
											}
									}<?php */?>
								]
						} );
						
					$( "#dt-common-account_length select, #dt-common-account_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-common-account" ).DataTableAccountingAccount();
				
				
			});
	})( jQuery );
//]]>
</script>