<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="page-subtitle margin-bottom-20">
	<div class="row">
        <div class="col-md-6">
            <h3 class="text-info"><?php echo lang('suppliers:list_subtitle') ?></h3>
            <p><?php echo lang('suppliers:list_subtitle_helper') ?></p>
        </div>    
    </div>
	<div class="row form-group">
        <div class="col-md-6">
        	<label class="col-md-3 label-control"><?php echo lang("suppliers:view_data_label")?></label>
            <div class="col-md-6">
                <select id="view_member" name="view_member" class="form-control">
                    <option value=""><?php echo lang("global:select-all")?></option>
                    <option value="0"><?php echo lang("suppliers:supplier_label")?></option>
                    <option value="1"><?php echo lang("suppliers:member_label")?></option>
                </select>
			</div>
        </div>
        <div class="col-md-6">
            <a href="<?php echo base_url("common/suppliers/create") ?>" title="<?php echo lang('buttons:add_supplier') ?>" class="btn btn-success pull-right"><i class="fa fa-plus-circle"></i> <span><?php echo lang('buttons:add_supplier') ?></span></a>
        </div>
	</div>
</div>
<div class="table-responsive margin-bottom-40">
    <table id="dt-common-suppliers" class="table" width="100%">
        <thead>
            <tr>
                <th><?php echo lang('suppliers:mr_number_label') ?></th>
                <th><?php echo lang('suppliers:type_label') ?></th>
                <th><?php echo lang('suppliers:name_label') ?></th>
                <th><?php echo lang('suppliers:address_label') ?></th>
                <th><?php echo lang('suppliers:phone_label') ?></th>
                <th><?php echo lang('suppliers:created_label') ?></th>
                <th><?php echo lang('suppliers:updated_label') ?></th>
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
				DataTable_CommonSuppliers: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							lengthMenu: [ 20, 50, 100 ],
							ordering: true,
							order: [[0, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("common/suppliers/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
											params.is_member = $("#view_member").val();
										}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "mr_number", 
										className: "a-right",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ data: "type_name", 
										width: "5%",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a";
											}
									},
									{ data: "personal_name", width: "20%" },
									{ 
										data: "personal_address", 
										width: "20%",
										render: function ( val, type, row ){
												return "<em>" + val + "</em>"
											}
									},
									{ 
										data: "phone_number", 
										className: "", 
										render: function ( val, type, row ){
												return ( val ) ? "<a href=\"tel:" + val + "\"><i class=\"fa fa-phone\" class=\"text-success\"></i> " + val + "</a>" : "n/a"
											}
									},
									{ 
										data: "created_at", 
										className: "a-right",
										searchable: false,
										render: function ( val, type, row ){
												return "<em>" + val + "</em>"
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
										className: "text-center",
										orderable: false,
										width: "120px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group\" role=\"group\">";
													buttons = "<a href=\"<?php echo base_url("common/suppliers/edit") ?>/" + val + "\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"label label-default\"><i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:edit" ) ?></a>";
													buttons += "<a href=\"<?php echo base_url("common/suppliers/list_downline") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:downline" ) ?>\" class=\"label label-info\"><i class=\"fa fa-sitemap\"></i></a>";
													buttons += "<a href=\"<?php echo base_url("common/suppliers/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"label label-danger\"><i class=\"fa fa-times\"></i></a>";
												buttons += "</div>";
												
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-common-suppliers_length select, #dt-common-suppliers_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-common-suppliers" ).DataTable_CommonSuppliers();
				
				$("#view_member").on("change", function(e){
					$( "#dt-common-suppliers" ).DataTable().ajax.reload();
				});
				
			});
	})( jQuery );
//]]>
</script>