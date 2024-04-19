<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row form-group">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_files" class="table table-sm table-bordered" width="100%">
                <thead>
                    <tr>
						<th>No. Bukti</th>
                        <th>NRM</th>
                        <th>Nama File</th>
                        <th>Tgl Upload</th>                        
                        <th><i class="fa fa-gear"></i></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row form-group">
	<a href="<?php echo @$create_file ?>" id="add_file" data-toggle="form-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah File</b></a>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		var _datatable_populate;
		var _datatable_actions = {
			remove: function( params, fn, scope ){

					$.post( "<?php echo $delete_file ?>", { "ID" : params.ID }, function( response, status, xhr ){
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success( response.message );
						
						_datatable.row( scope )
								.remove()
								.draw(true);
													
					});	
													
				},
			
				
		};
		
		$.fn.extend({
			dt_files: function(){
					var _this = this;
					
					if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
						return _this
					}
					
					_datatable = _this.DataTable( {
							processing: true,
							serverSide: false,								
							paginate: false,
							ordering: false,
							searching: false,
							info: false,
							autoWidth: false,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("{$nameroutes}/lookup_collection") ?>",
									type: "POST",
									data: function( params ){
										params.NoBukti = "<?= $NoBukti?>";	
									}
								},
							columns: [
									
									{ data: "NoBukti",},
									{ data: "NRM",},
									{ data: "NamaFile", },
									{ 
										data: "TglCreate", className: "text-center", 
										render: function( val ){
											return moment(val).format('DD-MM-YYYY')
										}
									},
									{ 
										data: "ID", 
										className: "actions text-center", 
										render: function( val, type, row, meta ){
											var buttons = '<div class="btn-group">';
													buttons += "<a href=\"<?= base_url("{$nameroutes}/file_view") ?>/" + val + "\" title=\"View File\" data-toggle=\"form-ajax-modal\" class=\"btn btn-info btn-xs btn-show\"> <i class=\"fa fa-eye\"></i></a>";
													buttons += '<a href="javascript:;" title="<?php echo lang( "buttons:remove" ) ?>" class="btn btn-danger btn-xs btn-remove"> <i class="fa fa-times"></i> </a>';
												buttons += '</div>';

												return buttons
											} 
									},
								],
							createdRow: function ( row, data, index ){
									$( row ).on( "click", "a.btn-remove", function(e){
											e.preventDefault();												
											var elem = $( e.target );
											
											if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
												_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
											}
										});
								}
						} );
						
					$( "#dt_files_length select, #dt_files_filter input" )
					.addClass( "form-control" );
					
					return _this
				},
		});
		
		$( document ).ready(function(e) {
            	$( "#dt_files" ).dt_files();
            	
				$("#refresh_file").on("click", function(e) {
					e.preventDefault();
					$("#dt_files").DataTable().ajax.reload();
				})

			});

	})( jQuery );
//]]>
</script>