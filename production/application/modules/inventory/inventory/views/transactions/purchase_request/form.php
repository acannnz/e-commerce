<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<style>
	.btn-default {
		padding: 9px 15px 8px 15px!important;	
}
</style>
<?php echo form_open( $form_action, [
		'id' => 'form_create_purchase_request', 
		'name' => 'form_create_purchase_request', 
		'rule' => 'form', 
		'class' => ''
	]); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-heading">                
                <div class="panel-bars">
					<ul class="btn-bars">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
                                <i class="fa fa-bars fa-lg tip" data-placement="left" title="<?php echo lang("actions") ?>"></i>
                            </a>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li>
                                    <a href="<?php echo site_url("{$nameroutes}/create"); ?>" 
                                    	title="<?php echo lang('action:add'); ?>">
                                        	<i class="fa fa-plus"></i> <?php echo lang('action:add') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo lang('heading:purchase_request'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
                <div class="row">
                    <div class="col-md-6">
						<div class="form-group">
                            <?php echo form_label(lang('label:request_number').' *', 'input_request_number', ['class' => 'control-label col-md-3']) ?>
                            <div class="col-md-9">
                                <?php echo form_input('f[No_Permintaan]', set_value('f[No_Permintaan]', $item->No_Permintaan, TRUE), [
                                        'id' => 'No_Permintaan', 
                                        'placeholder' => '',
                                        'readonly' => 'readonly', 
                                        'class' => 'form-control'
                                    ]); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label(lang('label:date').' *', 'input_date', ['class' => 'control-label col-md-3']) ?>
                            <div class="col-md-9">
                                <?php echo form_input('f[Tgl_Permintaan]', set_value('f[Tgl_Permintaan]', date('Y-m-d', strtotime($item->Tgl_Permintaan)), TRUE), [
                                        'id' => 'Tgl_Permintaan', 
                                        'placeholder' => '', 
                                        @$is_edit ? 'disabled' : 'required' => TRUE, 
                                        'class' => 'form-control datepicker'
                                    ]); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label(lang('label:date_needed').' *', 'input_date_request', ['class' => 'control-label col-md-3']) ?>
                            <div class="col-md-9">
                                <?php echo form_input('f[Tgl_Dibutuhkan]', set_value('f[Tgl_Dibutuhkan]', date('Y-m-d', strtotime($item->Tgl_Dibutuhkan)), TRUE), [
                                        'id' => 'Tgl_Dibutuhkan', 
                                        'placeholder' => '', 
                                        'required' => 'required',
                                        'class' => 'form-control datepicker'
                                    ]); ?>
                            </div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:supplier').' *', 'input_supplier', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input([
												'type' => 'hidden',
												'name' => 'f[Supplier_ID]',
												'value' => set_value('f[Supplier_ID]', @$item->Supplier_ID, TRUE),
												'id' => 'Supplier_ID',
												'required' => TRUE
											]); ?>
											
									<?php echo form_input('f[Supplier_Name]', set_value('f[Supplier_Name]', @$supplier->Kode_Supplier.' '.@$supplier->Nama_Supplier, TRUE), [
												'id' => 'Supplier_Name', 
												'placeholder' => '', 
												'readonly' => 'readonly',
												'class' => 'form-control',
												'required' => TRUE
											]); ?>
									<span class="input-group-btn">
										<a href="javascript:;" data-action-url="<?php echo @$supplier_lookup ?>" data-title="<?php echo 'Daftar Supplier' ?>" data-act="ajax-modal" class="btn btn-info" style="padding: 9px 14px 9px 14px!important;"><i class="fa fa-search"></i></a>
									</span>
								</div>
							</div>
						</div>
                    </div>
					
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo form_label(lang('label:warehouse').' *', 'input_section_id', ['class' => 'control-label col-md-3']) ?>
                            <div class="col-md-9">
                                <?php echo form_dropdown('f[Gudang_ID]', $dropdown_section, @$item->Gudang_ID, [
                                        'id' => 'Gudang_ID', 
                                        @$is_edit ? 'disabled' : 'required' => TRUE, 
                                        'class' => 'form-control',
										'data-target' => '#JenisPengadaanID', 
										'data-populate' => 'procurementType'
                                    ]); ?>
                            </div>
                        </div>
    
                        <div class="form-group">
                            <?php echo form_label(lang('label:procurement_type').' *', 'input_procurement_type', ['class' => 'control-label col-md-3']) ?>
                            <div class="col-md-9">
                                <?php echo form_dropdown('f[JenisPengadaanID]', $dropdown_procurement, @$item->JenisPengadaanID, [
                                        'id' => 'JenisPengadaanID', 
                                        'required' => 'required', 
                                        'class' => 'form-control',
                                    ]); ?>
                            </div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:description').' *', 'input_keterangan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_textarea([
										'id' => 'Keterangan',
										'name' => 'f[Keterangan]',
										'value' => set_value('f[Keterangan]', $item->Keterangan, TRUE),
										'placeholder' => '',
										'required' => 'required', 
										'rows' => '3', 
										'class' => 'form-control'
									]); ?>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label('&nbsp', 'input_keterangan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<label for="Pembelian_Asset">
										<input type="checkbox" name="f[Pembelian_Asset]" class="checkbox checkth" id="Pembelian_Asset" value="1" <?php echo ($item->Pembelian_Asset) ? 'checked' : NULL?>>
										<?php echo lang('label:asset_purchase')?>
									</label>
							</div>

						</div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <table id="dt_trans_purchase_request_detail" class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th></th>
                                <th><?php echo 'Nama Barang' ?></th>
                                <th><?php echo lang('label:item_konversion') ?></th>
                                <th><?php echo lang('label:item_category') ?></th>
                                <th><?php echo lang('label:item_unit') ?></th>
                                <th><?php echo lang('label:item_min_stock') ?></th>
                                <th><?php echo lang('label:item_max_stock') ?></th>
                                <th><?php echo lang('label:item_qty_sistem') ?></th>
                                <th><?php echo lang('label:item_qty_order') ?></th>
                                <th><?php echo 'Harga' ?></th>
                                <th><?php echo 'Subtotal' ?></th>
                            </tr>
                        </thead>        
                        <tbody>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <a href="javascript:;" data-action-url="<?php echo @$item_lookup ?>"  data-title="<?php echo lang('heading:item_list')?>" data-modal-lg="1" data-act="ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Barang</b></a>
                    </div>
                </div>
				<hr />
                <div class="row">
					<div class="col-lg-6">		
						<div class="form-group">
								<?php echo form_label(lang('label:grand_total'), 'input_total', ['class' => 'control-label col-md-3']) ?>
								<div class="col-md-9">
									<?php echo form_input('adt[grand_total]', set_value('adt[grand_total]', '', TRUE), [
											'id' => 'grand_total', 
											'placeholder' => '',
											'readonly' => 'readonly', 
											'class' => 'form-control'
										]); ?>
								</div>
						</div>
					</div>
					<div class="col-md-6">
						<?php if (@$item->Status_Batal == 1): ?>
						<div class="form-group">
							<div class="col-md-offset-3 col-md-9">
								<h4 class="text-danger"><?php echo lang('message:cancel_data') ?></h4>
							</div>
						</div>
						<?php endif; ?>	
				
					</div>
                </div>
                <div class="form-group pull-right">
                		<br /><br />
						<?php if(!empty(@$item->Permintaan_ID)): ?>
							<a href="<?php echo base_url("{$nameroutes}/print_po/$item->Permintaan_ID") ?>" target="_blank" class="btn btn-warning"><i class="fa fa-print"></i> <?php echo lang("buttons:print") ?></a></li>
						<?php endif; ?>
                        <a href="javscript:;" id="btn-cancel" data-action-url="<?php echo @$cancel_url ?>" data-title="<?php echo 'Konfirmasi Batal'?>" data-act="ajax-modal" type="button" class="btn btn-danger" <?php echo (@$is_edit && @$item->Status_Batal == 1) ? 'disabled' : NULL ?> ><i class="fa fa-ban" aria-hidden="true"></i> <?php echo lang( 'buttons:cancel' ) ?></a>
                        <button class="btn btn-primary" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';") ?>"><i class="fa fa-file-o" aria-hidden="true"></i> Buat Baru</button> 
						<button id="btn-submit" type="button" class="btn btn-success" <?php echo (@$is_edit && @$item->Status_Batal == 1) ? 'disabled' : NULL ?> ><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo lang( 'buttons:save' ) ?></button>
						<!-- <button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';") ?>">Close</button>  -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_hidden('mass_action', ''); ?>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;		
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
												
						switch( this.index() ){									
							case 8:
							
								var _input = $( "<input type=\"number\" value=\"" + Number(data.Qty_Permintaan || 1) + "\" style=\"width:100%\"  class=\"form-control\" min=\"1\">" );
								var discount;
								var total;
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										try{
											data.Qty_Permintaan = this.value > 0 ? this.value : 1;
											data.Jumlah_Total = Number( data.Qty_Permintaan ) * mask_number.currency_remove( data.Harga_Beli );
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;
							
							case 9:
							
								var _input = $( "<input type=\"number\" value=\"" + mask_number.currency_remove(data.Harga_Beli || 1) + "\" style=\"width:100%\"  class=\"form-control\" min=\"1\">" );
								var discount;
								var total;
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										   
										try{
											data.Harga_Beli = this.value > 0 ? this.value : data.Harga_Beli;
											data.Jumlah_Total = Number( data.Qty_Permintaan ) * mask_number.currency_remove( data.Harga_Beli );
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;
						}
						
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);
								
						_datatable_actions.calculate_balance();
						
					},
				calculate_balance: function(params, fn, scope){
						
						var _form = $( "form[name=\"form_create_purchase_request\"]" );
						var _form_balance = _form.find( "input[id=\"grand_total\"]" );
							
						var tol_balance = 0;
						
						var collection = $( "#dt_trans_purchase_request_detail" ).DataTable().rows().data();
						
						collection.each(function(value, index){
							
							tol_balance = tol_balance + mask_number.currency_remove( value.Jumlah_Total );
								
						});
						
						_form_balance.val( mask_number.currency_add( tol_balance ) );
												
					},
			};
		
		$.fn.extend({
				dt_trans_purchase_request_detail: function(){
						var _this = this;
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						_datatable = _this.DataTable( {
								dom: 'tip',
								processing: true,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: true,
								info: false,
								responsive: true,
								scrollCollapse: true,
								data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK)); ?>,
								columns: [
										{ 
											data: "Permintaan_ID", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-sm btn-remove\"><i class=\"fa fa-trash\"></i></a>")
												} 
										},
										{ data: "Nama_Barang", className: "",
											render:function( val, type, row)
											{
												return val +'<br><small>('+ row.Kode_Barang + ")</small>"
											}
										},
										{ data: "Konversi", className: "" },
										{ data: "Nama_Kategori", className: "" },
										{ data: "Nama_Satuan"},
										{ data: "Min_Stok", className: "" ,width: "7%"},
										{ data: "Max_Stok", className: "" },
										{ data: "Qty_Stok", className: "text-center", width: "8%", },
										{ data: "Qty_Permintaan", className: "text-center", },
										{ 
											data: "Harga_Beli",
											width: "10%",
											render: function( val, type, row){
													return mask_number.currency_add( val );
												}
										},
										{ 
											data: "Jumlah_Total", 
											render: function( val, type, row ){
													return mask_number.currency_add( val );
												}
										}
									
									],
								columnDefs  : [
										{
											"targets": ["Kode_Barang"],
											"visible": true,
											"searchable": false
										}
									],
								createdRow: function ( row, data, index ){		
										_datatable_actions.calculate_balance();
										
										$( row ).on( "dblclick", "td",  function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
											
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable_actions.remove( data, null, row )
												}
											})
									}
							} );
							
						$( "#dt_trans_purchase_request_detail_length select, #dt_trans_purchase_request_detail_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		
		var populateGroups = {
			procurementType: JSON.parse('<?php print_r(json_encode(@$dropdown_procurement_group, JSON_NUMERIC_CHECK)) ?>')
		}
			
		$.fn.extend({
			option_chosen: function( ){
					var _this = this;
					if( !_this.size() ){return _this}
					
					var target = _this.data("target");
					var populate = _this.data("populate");
					var _target = jQuery( target );
					var selected = _this.val() || 0;
	
					_target.option_clear();
					_target.option_populate( populate, selected );
	
					_this.on( "change", function(){
							if( selected = _this.val() || 0 ){
								_target.option_clear();
								_target.option_populate( populate, selected );
							}
						});
						
					return _this;						
				},
			option_populate: function( populate, key ){
					var _this = this;
					if( !_this.size() ){return _this}
					
					var populate = populateGroups[ populate ][ key ] || [];
					_this.option_option( populate );
					
					return _this;
				},
			option_option: function( populate ){
					var _this = this;
					if( !_this.size() ){return _this}
					
					if( !$.isEmptyObject(populate) ){
						_this.html("");
						
						jQuery( "<option></option>" )
							.val("")
							.text( "<?php echo lang('global:select-pick')?>" )
							.appendTo( _this );
						
						$.each(populate, function(index, value){
								var _option = jQuery( "<option></option>" );
								_option.val( index );
								_option.text( value );
								
								_this.append( _option );
							});
					} else {
						_this.html("");
						
						jQuery( "<option></option>" )
							.val("")
							.text("<?php echo lang('global:select-empty')?>")
							.appendTo( _this );
					}
					
					return _this;
				},
			option_clear: function( ){
					var _this = this;
					if( !_this.size() ){return _this}
	
					_this.html("");
						
					jQuery( "<option></option>" )
						.val("")
						.text("<?php echo lang('global:select-empty')?>")
						.appendTo( _this );
											
					var target = _this.data("target");
					if( target ){
						var _target = jQuery( target );				
						_target.option_clear();
					}
										
					return _this;
				},
		});
		
		$( document ).ready(function(e) {
				<?php if ( ! @$is_edit): ?>
				$( "select#Gudang_ID" ).option_chosen();
				<?php endif;?>

            	$( "#dt_trans_purchase_request_detail" ).dt_trans_purchase_request_detail();
				
								
				$("button#btn-submit").on("click", function(e){
					e.preventDefault();	
					
					if( $("#JenisPengadaanID").val() == '')
					{
						$.alert_warning( 'Jenis Pengadaan Belum Dipilih.' );
						return true;
					}
					
					
					var data_post = {};
						data_post.header = {
								"Tgl_Permintaan" : $("#Tgl_Permintaan").val(),
								"Tgl_Dibutuhkan" : $("#Tgl_Dibutuhkan").val(),
								"Gudang_ID" : $("#Gudang_ID").val(),
								"JenisPengadaanID" : $("#JenisPengadaanID").val() || 1,
								"Supplier_ID" : $("#Supplier_ID").val(),
								"Pembelian_Asset" : $("#Pembelian_Asset:checked").val() || 0,
								"Keterangan" : $("#Keterangan").val(),
							};
							
						data_post.additional = {
								'grand_total' : mask_number.currency_remove( $("#grand_total").val() )
							};
							
						data_post.details = {};
						
					var table_data = $( "#dt_trans_purchase_request_detail" ).DataTable().rows().data();
					table_data.each(function (value, index) {
						var detail = {
								"Qty_Permintaan" : value.Qty_Permintaan,
								"Harga_Terakhir" : mask_number.currency_remove( value.Harga_Beli ),
								"Barang_ID" : value.Barang_ID,
								"Kode_Satuan" : value.Nama_Satuan,
								"Keterangan" : value.Keterangan,
								"JenisBarangID" : value.JenisBarangID,
						}
						
						data_post.details[index] = detail;
					});
					
					
					$.post($("form[name=\"form_create_purchase_request\"]").attr("action"), data_post, function( response, status, xhr ){
						
						if( "error" == response.status ){
							$.alert_error( response.message );
							return false
						}
						
						$.alert_success( response.message );
						
						var Permintaan_ID = response.Permintaan_ID;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("{$nameroutes}/update"); ?>/"+ Permintaan_ID;
							
							}, 300 );
						
					})	
				});

			});

	})( jQuery );
//]]>
</script>
