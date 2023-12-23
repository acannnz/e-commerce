<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="page-subtitle margin-bottom-20">
	<div class="row">
        <div class="col-md-6">
            <h3 class="text-info">Daftar Paket Obat</h3>
            <p>Paket Obat Dapat Dikelola Disini.</p>
        </div>
        <div class="col-md-6">
            <a href="<?php echo base_url("pharmacy/products/bhp_package_create") ?>" title="Tambah Paket Obat Baru" class="btn btn-success pull-right"><i class="fa fa-plus-circle"></i> <span>Tambah Paket BHP</span></a>
        </div>
	</div>
</div>
<div class="table-responsive">
    <table id="dt-product_packages" class="table table-sm" width="100%">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Paket</th>
                <th>Section</th>
                <th>Ditagihkan</th>
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
				DataTable_Registrations: function(){
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
							lengthMenu: [ 30, 45, 75, 100 ],
							ajax: {
									url: "<?php echo base_url("pharmacy/products/datatable_bhp_package_collection") ?>",
									type: "POST",
									data: function( params ){
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "Kode", 
										className: "text-center",
										width: "150px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "NamaPaket", 
									},
									{ 
										data: "SectionName", 
									},									
									{ 
										data: "Ditagihkan", 
										render: function ( val, type, row ){
												if (val == 't')
												{
													return "Ya"
												}
												
												return "TIDAK"
											}
									},
									{ 
										data: "Kode",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("pharmacy/products/bhp_package_edit") ?>/" + val + "\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-default btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:edit" ) ?> </a>";
												<?php /*?>	buttons += "<a href=\"<?php echo base_url("product_packages/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"> <i class=\"fa fa-times\"></i> </a>";<?php */?>
												buttons += "</div>";
												
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-product_packages_length select, #dt-product_packages_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-product_packages" ).DataTable_Registrations();

				$("#btn-search").on("click", function(e){
					e.preventDefault();
					
					$( "#dt-product_packages" ).DataTable().ajax.reload();
				});
			});
	})( jQuery );
//]]>
</script>