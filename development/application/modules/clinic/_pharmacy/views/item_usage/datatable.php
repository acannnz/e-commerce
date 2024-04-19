<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="page-subtitle margin-bottom-20">
	<div class="row">
        <div class="col-md-6">
            <h3 class="text-info">Daftar Pemakaian Barang Section </h3>
            <p>Pemakaian Barang Section Dikelola Disini</p>
        </div>
        <div class="col-md-6">
            <a href="<?php echo base_url("pharmacy/item-usage/create") ?>" title="<?php echo lang('buttons:create') ?>" class="btn btn-success pull-right"><i class="fa fa-plus-circle"></i> <span>Buat Pemakain Baru</span></a>
        </div>
	</div>
</div>
<div class="table-responsive">
    <table id="dt-item-usage" class="table table-sm table-bordered table-striped" width="100%">
        <thead>
            <tr>
                <th><?php echo lang("item_usage:evidence_number_label")?></th>
                <th><?php echo lang("item_usage:date_label")?></th>
                <th><?php echo lang("item_usage:section_label")?></th>
                <th><?php echo lang("item_usage:description_label")?></th>
                <th><?php echo lang("item_usage:state_label")?></th>
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
				DataTable_ItemUsage: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[1, 'DESC']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("pharmacy/item-usage/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "NoBukti", 
										className: "text-center",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Jam", 
										className: "text-center",
										render: function( val ){
											return val.substr(0, 19);
										}
									},
									{ 
										data: "SectionName", 
									},
									{ 
										data: "Keterangan", 
									},
									{ 
										data: "StatusBatal",
										className: "text-right",
										orderable: false,
										searchable: false,
										render: function (val){
											if ( val == 1)
											{
												return "<span class=\"label label-danger label-sm\"><?php echo lang( "buttons:cancel" ) ?> </span>";
											}
											return ''
										}
									},
									{ 
										data: "NoBukti", 
										className: "text-center",
										orderable: false,
										searchable: false,
										render: function ( val, type, row ){
											var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
												buttons += "<a href=\"<?php echo base_url("pharmacy/item-usage/view") ?>/" + row.NoBukti + "\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"label label-default label-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:edit" ) ?> </a>";
											<?php /*?>	buttons += "<a href=\"<?php echo base_url("registrations/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"> <i class=\"fa fa-times\"></i> </a>";<?php */?>
											buttons += "</div>";
											
											return buttons
										}
									},
										
								]
						} );
						
					$( "#dt-item-usage_length select, #dt-item-usage_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-item-usage" ).DataTable_ItemUsage();

				$("#btn-search").on("click", function(e){
					e.preventDefault();
					
					$( "#dt-item-usage" ).DataTable().ajax.reload();
				});
			});
	})( jQuery );
//]]>
</script>