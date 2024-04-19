<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-btns">
        	<a href="#" 
                class="" 
                title="<?php echo lang('button:add'); ?>" 
                data-act="ajax-modal" 
                data-title="Tambah Baru" 
                data-action-url="<?php echo get_uri("system/users/create"); ?>">
                    <i class="fa fa-plus-circle"></i> <?php echo lang('button:add'); ?>
                </a>
        </div>
        <h3 class="panel-title"><?php echo lang('users:heading') ?></h3>
    </div>
    <div class="panel-body table-responsive">
        <table id="dt-system-users" class="table" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th><?php echo lang('label:name') ?></th>
                    <th><?php echo lang('label:access') ?></th>
                    <th><?php echo lang('label:username') ?></th>
                    <th><?php echo lang('label:email') ?></th>
                    <th><?php echo lang('label:phone') ?></th>
                    <th><?php echo lang('label:state') ?></th>
                    <th class="option"><i class="fa fa-bars"></i></th>
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
		$.fn.extend({
				DT_SystemUsers: function(){
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
									url: "<?php echo base_url("system/users/collection") ?>",
									type: "POST",
									data: function( params ){}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{data: "name", className: "", 
										render: function ( val, type, row ){
											return "<b>" + val + "</b>"
										}
									},
									{data: "access", className: ""},
									{data: "username", className: ""},
									{data: "email", className: ""},
									{data: "mobile", className: ""},
									{data: "state", className: "text-center w80",			
										render: function ( val, type, row ){
											return (1 == val) ? "<span class=\"label label-info\"><?php echo lang('state:active') ?></span>" : "<span class=\"label label-danger\"><?php echo lang('state:inactive') ?></span>"
										}
									},
									{data: "id", className: "text-center option w80", orderable: false,
										render: function ( val, type, row ){
											var buttons = "<a href=\"javascript:;\" data-action-url=\"<?php echo base_url("system/users/edit") ?>/" + val + "\" data-post-id=\"" + val + "\" data-act=\"ajax-modal\" title=\"<?php echo lang( "button:edit" ) ?>\" data-title=\"<?php echo lang( "button:edit" ) ?>\" class=\"btn edit\"><i class=\"fa fa-pencil\"></i></a>";
											buttons += "<a href=\"javascript:;\" data-action-url=\"<?php echo base_url("system/users/delete") ?>/" + val + "\" data-post-id=\"" + val + "\" data-act=\"ajax-modal\" title=\"<?php echo lang( "button:delete" ) ?>\" data-title=\"<?php echo lang( "button:delete" ) ?>\" class=\"btn delete\"><i class=\"fa fa-times\"></i></a>";
											return buttons
										}
									}
								],
							language: {
									"decimal":        "",
									"emptyTable":     "<?php echo lang('datatable:empty_table'); ?>",
									"info":           "<?php echo lang('datatable:info'); ?>",
									"infoEmpty":      "<?php echo lang('datatable:empty_table'); ?>",
									"infoFiltered":   "<?php echo lang('datatable:info_filtered'); ?>",
									"infoPostFix":    "",
									"thousands":      ",",
									"lengthMenu":     "<?php echo lang('datatable:length_menu'); ?>",
									"loadingRecords": "<?php echo lang('datatable:loading_records'); ?>",
									"processing":     "<div class='table-loader'><span class='loading'></span></div>",
									"search":         "<?php echo lang('datatable:search'); ?>",
									"zeroRecords":    "<?php echo lang('datatable:zero_records'); ?>",
									"paginate": {
											"first":      "<?php echo lang('datatable:paginate_first'); ?>",
											"last":       "<?php echo lang('datatable:paginate_last'); ?>",
											"next":       "<i class='fa fa-angle-double-right'></i>",
											"previous":   "<i class='fa fa-angle-double-left'></i>"
										},
									"aria": {
											"sortAscending":  "<?php echo lang('datatable:sort_ascending'); ?>",
											"sortDescending": "<?php echo lang('datatable:sort_descending'); ?>"
										}
								},
							dom: "<'datatable-tools'<'col-md-4'l><'col-md-8 custom-toolbar'f>r>t<'datatable-tools clearfix'<'col-md-4'i><'col-md-8'p>>"	
						} );
					
					//_this.closest(".dataTables_wrapper").find("select,input").addClass('form-control');	
					_this.closest(".dataTables_wrapper").find("select").select2({
							minimumResultsForSearch: -1
						});
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-system-users" ).DT_SystemUsers();
			});
	})( jQuery );
//]]>
</script>