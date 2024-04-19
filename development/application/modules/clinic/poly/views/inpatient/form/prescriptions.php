<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row form-group">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="dt_prescriptions" class="table table-sm table-bordered" width="100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>Tanggal</th>
                        <th>Jam</th>                        
                        <th>ID Dokter</th>                        
                        <th>Nama Dokter</th>                        
                        <th>Jumlah</th>                        
                        <th>Cito</th>                        
                        <th>Realisasi</th>                        
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row form-group">
	<a href="<?php echo @$create_prescription ?>" id="add_prescripstions" data-toggle="form-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Resep</b></a>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
						if ( this.index() > 0 ) {
							try{
								
								form_ajax_modal.show("<?php echo $view_prescription ?>/"+ data.NoResep)
							} catch(ex){}
						}
							
					},
				remove: function( params, fn, scope ){
												
						$.post( "<?php echo $delete_prescription ?>", { "NoResep" : params.NoResep }, function( response, status, xhr ){
		
							if( "error" == response.status ){
								$.alert_error(response.message);
								return false
							}
							
							$.alert_success( response.message );
							
							_datatable.row( scope ).remove().draw();
														
						});	
														
					},
				add_row: function( params, fn, scope ){
						_datatable.row.add(
							{
							}
						).draw(false);
						
						
					}
					
					
			};
		
		$.fn.extend({
				dt_prescriptions: function(){
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
								<?php if (!empty($collection)):?>
								data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ 
											data: "NoResep", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ 
											data: "Tanggal", className: "text-center", 
											render: function( val ){
												return val.substring(0, 10)
											}
										},
										{ 
											data: "Jam", className: "text-center",
											render: function( val ) {
												return val.substring(11, 19)
											}
										},
										{ data: "DokterID", className: "" },
										{ data: "Nama_Supplier", className: "" },
										{ 
											data: "Jumlah", className: "text-right",
											render: function(val){
												return parseFloat(val).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
											} 
										},
										{ 
											data: "Cyto", className: "text-center",
											render: function ( val ) {
												if ( val == 1)
												{
													return "YA"
												}
												
												return "TIDAK"
											}
										},
										{ 
											data: "Realisasi", className: "text-center",
											render: function ( val ) {
												if ( val == 1)
												{
													return "SUDAH"
												}
												
												return "BELUM"
											}
										},
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
										$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
											
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
											})
									}
							} );
							
						$( "#dt_prescriptions_length select, #dt_prescriptions_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_prescriptions" ).dt_prescriptions();

			});

	})( jQuery );
//]]>
</script>