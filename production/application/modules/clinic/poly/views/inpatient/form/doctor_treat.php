<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo 'Dokter Rawat'?></h4>
        </div>        
        <?php echo form_open(current_url(), ['id' => 'form_doctor_treat']); ?>
        <div class="modal-body">
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table id="dt_doctor_treat" class="table table-sm table-bordered" width="100%">
							<thead>
								<tr>
									<th></th>
									<th>ID Dokter</th>
									<th>Nama</th>                        
									<th>Spesialis</th>                        
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<a href="<?php echo @$lookup_doctor_treat ?>" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-user-md"></i> Tambah Dokter</b></a>
				</div>
			</div>
		</div>
		<div class="modal-footer"> 
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<button type="submit" class="btn btn-block btn-primary"><?php echo lang('buttons:save')?></button>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<a href="javascript:;" id="btn-doctor-treat-close" class="btn btn-block btn-default" data-dismiss="modal"><?php echo lang('buttons:close')?></a>
					</div>
				</div>
			</div>
        </div>
        <?php echo form_close() ?>
    </div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->


<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {
				remove: function( params, fn, scope ){
						
						_datatable.row( scope ).remove().draw();
					},	
			};
		
		$.fn.extend({
				dt_doctor_treat: function(){
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
											data: "DokterID", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ 
											data: "DokterID", 
											className: "text-center", 
											render: function(val){
												return '<b>'+ val +'</b>';
											}
										},
										{ data: "NamaDokter"},
										{ data: "SpesialisName"},
									
										
									],
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
							
						$( "#dt_doctor_treat_length select, #dt_doctor_treat_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_doctor_treat" ).dt_doctor_treat();
				
				$('#form_doctor_treat').on('submit', function(e){
					e.preventDefault();
					data_post = {collection: {}};
					
					var table = $( "#dt_doctor_treat" ).DataTable().rows().data();
					$.each(table, function(i, v){
						data_post['collection'][i] = {
							NoReg : '<?php echo $NoReg ?>',
							DokterRawatID : v.DokterID,
							Spesialis : v.SpesialisName
						}
					});
					
					if($.isEmptyObject(data_post['collection'])){
						$.alert_warning('Dokter Rewat tidak boleh kosong!');
						return false;
					}
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
							if( "error" == response.status ){
								$.alert_error(response.status);
								return false
							}							
							if( !response.NoBukti ){
								$.alert_error("Terjadi Kesalahan! Silahkan Hubungi IT Support.");
								return false
							}
							
							$.alert_success( response.message );							
							$('#btn-doctor-treat-close').trigger('click');
						})	
				});

			});

	})( jQuery );
//]]>
</script>