<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_goods_receipt', 
		'name' => 'form_goods_receipt', 
		'rule' => 'form', 
		'class' => ''
	]); ?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-6">
                        <div class="form-group">
                        <?php echo form_label(lang('label:date').' *', 'Tgl_Penerimaan', ['class' => 'control-label col-md-3']) ?>
                        <div class="col-md-9">
							<?php echo form_input('f[Tgl_Penerimaan]', set_value('f[Tgl_Penerimaan]', date('Y-m-d', strtotime(@$item->Tgl_Penerimaan)), TRUE), [
									'id' => 'input_date', 
									'placeholder' => '', 
									'readonly' => 'readonly',
									'class' => 'form-control datepicker'
								]); ?>
							</div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label(lang('label:request_number').' *', 'input_receipt_number', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[No_Penerimaan]', set_value('f[No_Penerimaan]', @$item->No_Penerimaan, TRUE), [
										'id' => 'input_receipt_number', 
										'placeholder' => '',
										'readonly' => 'readonly', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                           <?php echo form_label(lang('label:warehouse').' *', 'Lokasi_ID', ['class' => 'control-label col-md-3']) ?>
                            <div class="col-md-9">
                                <?php echo form_dropdown('f[Lokasi_ID]', $dropdown_section, @$item->Lokasi_ID, [
                                        'id' => 'Lokasi_ID', 
                                        'class' => 'form-control',
										'data-target' => '#JenisPengadaanID', 
										'readonly' => 'readonly',
										'data-populate' => 'procurementType'
                                    ]); ?>
                            </div>
	                    </div>
						<div class="form-group">
							<?php echo form_label(lang('label:description').' *', 'Keterangan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_textarea([
										'name' => 'f[Keterangan]', 
										'value' => set_value('f[Keterangan]', @$item->Keterangan, TRUE),
										'id' => 'Keterangan', 
										'placeholder' => '',
										'required' => '', 
										'rows' => 3,
										'readonly' => 'readonly',
										'class' => 'form-control'
									]); ?>
							</div>
						</div>
                    </div>
                    <div class="col-md-6">
						<div class="form-group">
                            <?php echo form_label(lang('label:no_po').' *', 'No_Order', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<div class="input-group">
									<?php echo form_input([
											'type' => 'hidden',
											'name' => 'f[Order_ID]',
											'readonly' => 'readonly',
											'value' => set_value('f[Order_ID]', @$item->Order_ID, TRUE),
											'id' => 'Order_ID'
										]); ?>
										
									<?php echo form_input('f[No_Order]', set_value('f[No_Order]', @$order->No_Order, TRUE), [
											'id' => 'No_Order', 
											'placeholder' => '',
											'readonly' => 'readonly',
											'class' => 'form-control'
										]); ?>
								</div>
							</div>	
                        </div>
                        <div class="form-group">
                            <?php echo form_label(lang('label:supplier').' *', 'Supplier_Name', ['class' => 'control-label col-md-3']) ?>
                            <div class="col-md-9">
								<?php echo form_input([
											'type' => 'hidden',
											'name' => 'f[Supplier_ID]',
											'value' => set_value('f[Supplier_ID]', @$item->Supplier_ID, TRUE),
											'id' => 'Supplier_ID',
											'readonly' => 'readonly'
										]); ?>
										
								<?php echo form_input('f[Supplier_Name]', set_value('f[Supplier_Name]', @$supplier->Kode_Supplier.' - '.@$supplier->Nama_Supplier, TRUE), [
											'id' => 'Supplier_Name', 
											'placeholder' => '', 
											'readonly' => 'readonly',
											'class' => 'form-control'
										]); ?>
							</div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label(lang('label:due_date').' *', 'Tgl_JatuhTempo', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Tgl_JatuhTempo]', set_value('f[Tgl_JatuhTempo]', date('Y-m-d', strtotime(@$item->Tgl_JatuhTempo)), TRUE), [
										'id' => 'Tgl_JatuhTempo', 
										'placeholder' => '', 
										'readonly' => 'readonly',
										'class' => 'form-control datepicker'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:no_do').' *', 'No_DO', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[No_DO]', set_value('f[No_DO]', @$item->No_DO, TRUE), [
									'id' => 'No_DO', 
									'placeholder' => '',
									'readonly' => 'readonly',
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
						<div class="form-group">
							<label class="control-label col-md-3"></label>						
							<label for="IncludePPN" class="col-md-3">
								<input type="checkbox" name="f[IncludePPN]" id="IncludePPN" value="1" <?php echo @$item->IncludePPN ? 'checked' : NULL ?> readonly/>
								<?php echo lang('label:include_ppn') ?>
							</label>
						</div>
                	</div>
                </div>
				<hr/>
                <div class="row">
					<table id="dt_trans_goods_receipt" class="table table-bordered table-hover table-striped" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="20px"></th>
								<th><?php echo lang('label:item_name') ?></th>
								<th><?php echo lang('label:item_unit') ?></th>
								<th><?php echo lang('label:qty_po') ?></th>
								<th><?php echo lang('label:has_receipt') ?></th>
								<th><?php echo lang('label:qty_receipt') ?></th>
								<th><?php echo lang('label:item_price') ?></th>
								<th><?php echo lang('label:discount_percentage') ?></th>
								<th><?php echo lang('label:discount_money') ?></th>
								<th><?php echo lang('label:sub_total') ?></th>
								<?php /*?><th><?php echo lang('label:exp_date') ?></th><?php */?>
							</tr>
						</thead>        
						<tbody>
						</tbody>
					</table>
				</div>

                <div class="row">
					<div class="col-lg-6">
						<div class="form-group">
							<?php echo form_label(lang('label:sub_total').' *', 'sub_total', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[sub_total]', set_value('f[sub_total]', '', TRUE), [
										'id' => 'sub_total', 
										'placeholder' => '',
										'required' => 'required', 
										'class' => 'form-control',
										'readonly' => 'readonly'
									]); ?>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:total_discount').' *', 'total_diskon', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[total_diskon]', set_value('f[total_diskon]', '', TRUE), [
									'id' => 'total_diskon', 
									'placeholder' => '',
									'required' => 'required', 
									'class' => 'form-control',
									'readonly' => 'readonly'
								]); ?>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:ppn'), 'ppn', ['class' => 'control-label col-md-3']) ?>
							<div class="col-lg-4">
								<div class="input-group">
									<span class="input-group-addon">
										<input type="checkbox" name="ppn_manual" id="ppn_manual" value="<?php echo @$item->Pajak?>" <?php echo (@$item->Pajak > 0) ? 'checked' : NULL?> readonly/>
									</span>
									<?php echo form_input([
										'name' => 'f[ppn_percent]',
										'value' => set_value('f[ppn_percent]', @$item->ppn_percent, TRUE),
										'id' => 'ppn_percent', 
										'type' => 'number',
										'placeholder' => '',
										'data-ppn_type'=> 'percent',
										'readonly' => 'readonly',
										'class' => 'form-control js-input-calculate-balance'
									]); ?>
									<span class="input-group-addon">
										<span>%</span>
									</span>
								</div>
							</div>
							<div class="col-lg-5">
								<?php echo form_input([
										'name' => 'f[Pajak]', 
										'value' => set_value('f[Pajak]', @$item->Pajak, TRUE),
										'id' => 'Pajak', 
										'type' => 'text',
										'placeholder' => '',
										'data-ppn_type'=> 'value',
										'readonly' => 'readonly',
										'class' => 'form-control js-input-calculate-balance mask-number'
									]); ?>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<?php echo form_label(lang('label:receipt_charge').' *', 'Ongkos_Angkut', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Ongkos_Angkut]', set_value('f[Ongkos_Angkut]', number_format($item->Ongkos_Angkut, 2, '.', ','), TRUE), [
										'id' => 'Ongkos_Angkut', 
										'placeholder' => '',
										'readonly' => 'readonly',
										'class' => 'form-control js-input-calculate-balance  mask-number'
									]); ?>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:potongan').' *', 'potongan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Potongan]', set_value('f[Potongan]', number_format($item->Potongan, 2, '.', ','), TRUE), [
										'id' => 'Potongan', 
										'placeholder' => '',
										'readonly' => 'readonly',
										'class' => 'form-control js-input-calculate-balance  mask-number'
									]); ?>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:grand_total').' *', 'Total_Nilai', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Total_Nilai]', set_value('f[Total_Nilai]', '', TRUE), [
										'id' => 'Total_Nilai', 
										'placeholder' => '',
										'class' => 'form-control',
										'readonly' => 'readonly'
									]); ?>
							</div>
						</div>
					</div>
                </div>
				<hr/>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group text-right">
							<button class="btn btn-default" type="button" data-close="close">Close</button> 
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
				
		var _datatable;		
		var _datatable_populate;
		var _detail_rows = [];
		var _datatable_actions = {
				details: function( data, row, elem ){
						var _tr = $( elem ).closest( 'tr' );
						var _rw = _datatable.row( _tr );
						
						var _dt = _rw.data();
						var _ids = $.inArray( _tr.attr( 'id' ), _detail_rows );
				 
						if( _rw.child.isShown() ){
							_tr.removeClass( 'details' );
							
							$(elem).find('i').addClass( 'fa-expand' );
							$(elem).find('i').removeClass( 'fa-compress' );
							
							_rw.child.hide();
				 
							// Remove from the 'open' array
							_detail_rows.splice( _ids, 1 );
						} else {
							
							$(elem).find('i').removeClass( 'fa-expand' );
							$(elem).find('i').addClass( 'fa-compress' );
									
							_tr.addClass( 'details' );
							
							//if( _rw.child() == undefined ){
								var _details = $( "<div class=\"details-loader\"></div>" );
								_rw.child( _details ).show();
																				
								_details.html('<div class="row">'+
										'<div class="col-md-4">'+
											'<div class="form-group">'+
												'<label class="col-md-4">Tanggal Expired</label>'+
												'<div class="col-md-8">'+
													'<input type="text" name="Exp_Date" value="'+ data.Exp_Date +'" class="form-control datepicker" readonly autocomplete="off">'+
												'</div>'+
											'</div>'+
										'</div>'+
										'<div class="col-md-4">'+
											'<div class="form-group">'+
												'<label class="col-md-4">Nomor Batch</label>'+
												'<div class="col-md-8">'+
													'<input type="text" name="NoBatch" value="'+ (data.NoBatch || '') +'" class="form-control" readonly autocomplete="off">'+
												'</div>'+
											'</div>'+
										'</div>'+
										'<div class="col-md-4">'+
											'<div class="form-group text-right">'+
												'<button type="button" class="save btn btn-primary btn-save-detail btn-block" >Tutup Detail</button>'+													
											'</div>'+
										'</div>'+
									'</div>');
								
								$( window ).trigger( "resize" );
								
								_details.on( "click", "button.btn-save-detail", function(e){
									e.preventDefault();		
									
									data.Exp_Date = _details.find('input[name="Exp_Date"]').val();
									data.NoBatch = _details.find('input[name="NoBatch"]').val();
									
									_datatable.row( row ).data( data ).draw();	
									_tr.find('.btn-detail').trigger( 'click' );
								});
								
							/*} else {
								_rw.child.show();
							}*/
							
							// Add to the 'open' array
							if( _ids === -1 ){
								_detail_rows.push( _tr.attr( 'id' ) );
							}
						}
						
						$( window ).trigger( "resize" );
					},
				calculate_row: function ( row, data, disc_type = '' ){
						var discount_percent, discount_value, sub_total = 0;
						
						sub_total = parseFloat(data.Qty_Penerimaan) * mask_number.currency_remove(data.Harga_Beli);
						
						switch (disc_type)
						{
							case "percent" :
								data.Diskon_Rp = sub_total * parseFloat(data.Diskon_1) / 100;
								break;
							case "value" :
								data.Diskon_1 = parseFloat( mask_number.currency_remove(data.Diskon_Rp) / sub_total * 100).toFixed(2);
								break;
						}
						
						data.sub_total = sub_total - data.Diskon_Rp;
						
						_datatable.row(row).data(data);
						_datatable_actions.calculate_balance();
					},
				calculate_balance: function( ppn_type = 'value' ){
						
						var _form = $( "form[name=\"form_goods_receipt\"]" );
						var _form_grand_total = _form.find( "input[id=\"Total_Nilai\"]" );
						var _form_sub_total = _form.find( "input[id=\"sub_total\"]" );
						var _form_discount = _form.find( "input[id=\"total_diskon\"]" );
						var _form_shipping_cost = _form.find( "input[id=\"Ongkos_Angkut\"]" );
						var _form_potongan = _form.find( "input[id=\"Potongan\"]" );
						var _check_ppn = _form.find( "input[id=\"ppn_manual\"]" );
						var _form_ppn_percent = _form.find( "input[id=\"ppn_percent\"]" );
						var _form_ppn = _form.find( "input[id=\"Pajak\"]" );
						var _form_submit = _form.find( "button[id=\"btn-submit\"]" );
						
						var discount_total = sub_total = grand_total = 0;
						
						var collection = $("#dt_trans_goods_receipt").DataTable().rows().data();
						collection.each(function(value, index){
							
							discount_total = discount_total + mask_number.currency_remove( value.Diskon_Rp );
							sub_total = sub_total + mask_number.currency_remove( value.sub_total );
						});
	
						_form_discount.val( mask_number.currency_add(discount_total) );
						_form_sub_total.val( mask_number.currency_add(sub_total) );

						grand_total = grand_total + sub_total;
						grand_total = grand_total + mask_number.currency_remove( _form_shipping_cost.val() || 0 );
						grand_total = grand_total - mask_number.currency_remove( _form_potongan.val() || 0 );						
						//grand_total = grand_total - discount_total || 0;
						
						if ( _check_ppn.val() > 0 )
						{
							switch (ppn_type)
							{
								case "percent" :
									var value = sub_total * parseFloat(_form_ppn_percent.val() || 0) / 100;
									_form_ppn.val( mask_number.currency_add(value) );
								break;
								case "value" :
									var value = parseFloat( mask_number.currency_remove(_form_ppn.val() || 0) / sub_total * 100 ).toFixed(2);
									_form_ppn_percent.val( value );
								break;
							}
							
							grand_total = grand_total + mask_number.currency_remove( _form_ppn.val() || 0 );	
						}
						
						_form_grand_total.val( mask_number.currency_add(grand_total) );
					
						if (grand_total > 0)
						{
							_form_grand_total.removeClass("text-danger");
							_form_submit.prop("disabled", false);
						} else {
							_form_grand_total.addClass("text-danger");
							_form_submit.prop("disabled", true);
						}			
						
					},
			};
		
		_form_actions = {
				init: function(){
						
						$('body').on('focus',".datepicker", function(){
							$(this).datepicker({
									format: "yyyy-mm-dd"
								});
						});
						
						var _form = $( "form[name=\"form_goods_receipt\"]" );
						var _form_calculate = _form.find( "input.js-input-calculate-balance" );
						var _check_ppn = _form.find( "input[id=\"ppn_manual\"]" );
						var _form_ppn_percent = _form.find( "input[id=\"ppn_percent\"]" );
						var _form_ppn = _form.find( "input[id=\"Pajak\"]" );
						
						
						_form_calculate.each(function(){
							$(this).on('focus', function(e){
								
								ppn_type = $(this).data('ppn_type') || '';
								_datatable_actions.calculate_balance( ppn_type );
							});

							$(this).on('keyup', function(e){
								ppn_type = $(this).data('ppn_type') || '';
								_datatable_actions.calculate_balance( ppn_type );
							});

							$(this).on('blur', function(e){
								
								ppn_type = $(this).data('ppn_type') || '';
								_datatable_actions.calculate_balance( ppn_type );
							});

						});

						
					}
			};
		
		$.fn.extend({
				dt_services: function(){
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
								searching: false,
								info: false,
								responsive: true,
								scrollCollapse: true,
								data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK));?>,
								columns: [
										{ 
											data: "Barang_ID", 
											width: "100px",
											orderable: false,
											searchable: false,
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
															buttons += "<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-xs btn-remove\"><i class=\"fa fa-times\"></i></a>";
															buttons += "<a href=\"javascript:;\" title=\"<?php echo lang('buttons:detail') ?>\" class=\"btn btn-success btn-xs btn-detail\"> <i class=\"fa fa-expand\"></i></a>";
														buttons += "</div>";
													
													return buttons;
												} 
										},
										{ 
											data: "Nama_Barang", 
											className: "details-control",
											render: function(val, type, row){
												return val +' - '+ row.Kode_Barang;
											}
										},
										{ data: "Kode_Satuan", },
										{ data: "Qty_PO", className: "text-right", width:'90px'},
										{ data: "Qty_Telah_Terima",  className: "text-right",},
										{ data: "Qty_Penerimaan",  className: "text-right",},
										{ 
											data: "Harga_Beli", 
											className: "text-right",
											render: function(val, type, row){
													return mask_number.currency_add( val );
												}
										},
										{ data: "Diskon_1", width: "20px", className: "text-right",},
										{ 
											data: "Diskon_Rp", 
											 className: "text-right",
											render: function( val, type, row){
													return mask_number.currency_add( val );
												}
										},
										{ 
											data: "sub_total", 
											 className: "text-right",
											render: function(val, type, row){
													return mask_number.currency_add( val );
												}
										},						
									],
								drawCallback: function (){
										_datatable_actions.calculate_balance();
										$( window ).trigger( "resize" );
									},
								createdRow: function ( row, data, index ){											
										$( row ).on( "click", "a.btn-detail", function(e){
												e.preventDefault();												
												var elem = $( this );
												_datatable_actions.details( data, row, elem )
											});
									}
							} );
							
						$( "#dt_trans_goods_receipt_length select, #dt_trans_goods_receipt_filter input" )
						.addClass( "form-control" );
													
						// On each draw, loop over the `_detail_rows` array and show any child rows
						_datatable.on('draw', function (){
								$.each(_detail_rows, function ( i, id ){
										$( '#' + id + ' td.details-control').trigger( 'click' );
									});
							});
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_trans_goods_receipt" ).dt_services();
				$( '.btn-detail').trigger( 'click' );
				
				_form_actions.init();
				
			});

	})( jQuery );
//]]>
</script>
