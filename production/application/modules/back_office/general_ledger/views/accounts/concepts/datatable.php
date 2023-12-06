<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="page-subtitle margin-bottom-20">
	<div class="row">
    	<div class="col-md-6">
        	<h3><?php echo lang('concepts:list_heading') ?></h3>
        </div>
        <?php /*?><div class="col-md-6">
        	<a href="<?php echo base_url("general-ledger/account/concepts/create") ?>" data-toggle="ajax-modal" title="<?php echo lang('buttons:add') ?>" class="btn btn-success pull-right"><i class="fa fa-plus-circle"></i> <span><?php echo lang('buttons:add') ?></span></a>
        </div><?php */?>
    </div>
</div>
<div class="table-responsive">
    <table id="dt-account-concepts" class="table table-bordered table-stripped">
        <thead>
            <tr>
                <th><?php echo lang('concepts:level_label') ?></th>
                <th><?php echo lang('concepts:digit_label') ?></th>
                <th><?php echo lang('concepts:description_label') ?></th>
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
				DataTable_AccountConcepts: function(){
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
									url: "<?php echo base_url("general-ledger/account/concepts/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
									}
								},
							columns: [
									{ 
										data: "Jumlah_Level", 
										className: "text-center",
										render: function ( val ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "Jumlah_Digit",  
										className: "text-center",
										render: function ( val ){
												return "<b>" + val + "</b>"
											}
									},
									{ data: "Keterangan",	},
									{ 
										data: "Setup_ID",
										orderable: false,
										width: "100px",
										render: function ( val ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("general-ledger/account/concepts/edit") ?>/" + val + "\" data-toggle=\"form-ajax-modal\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-info btn-xs\"><b><i class=\"fa fa-pencil\"></i> <?php echo lang("buttons:edit") ?></b></a>";
													buttons += "</div>";
												return buttons
											}
									}
								]
						} );
					
					$( "#dt-account-concepts_length select, #dt-account-concepts_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-account-concepts" ).DataTable_AccountConcepts();
			});
	})( jQuery );
//]]>
</script>