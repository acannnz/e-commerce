<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($collection);exit;
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
            <div class="panel-heading">                
                <div class="panel-bars">
					<ul class="btn-bars">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
                                <i class="fa fa-bars fa-lg tip" data-placement="left" title="<?php echo lang("actions") ?>"></i>
                            </a>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li>
                                    <a href="<?php echo site_url("{$nameroutes}/create"); ?>">
                                        <i class="fa fa-plus"></i> <?php echo lang('action:add') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo lang('heading:goods_receipt'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-6">
                        <div class="form-group">
                        <?php echo form_label(lang('label:date').' *', 'Tgl_Bonus', ['class' => 'control-label col-md-3']) ?>
                        <div class="col-md-9">
							<?php echo form_input('f[Tgl_Bonus]', set_value('f[Tgl_Bonus]', date('Y-m-d', strtotime(@$item->Tgl_Bonus)), TRUE), [
									'id' => 'input_date', 
									'placeholder' => '', 
									'required' => 'required',
									'class' => 'form-control datepicker'
								]); ?>
							</div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label('No Bonus *', 'No_Bonus', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[No_Bonus]', set_value('f[No_Bonus]', @$item->No_Bonus, TRUE), [
										'id' => 'No_Bonus', 
										'placeholder' => '',
										'readonly' => 'readonly', 
										'class' => 'form-control'
									]); ?>
							</div>
						</div>
						<div class="form-group">
                            <?php echo form_label(lang('label:no_do').' *', 'No_DO', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[No_DO]', set_value('f[No_DO]', @$item->No_DO, TRUE), [
									'id' => 'No_DO', 
									'placeholder' => '',
									'required' => 'required', 
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
                    </div>
                    <div class="col-md-6">
						<div class="form-group">
                           <?php echo form_label(lang('label:warehouse').' *', 'Lokasi_ID', ['class' => 'control-label col-md-3']) ?>
                            <div class="col-md-9">
                                <?php echo form_dropdown('f[Lokasi_ID]', $dropdown_section, @$item->Lokasi_ID, [
                                        'id' => 'Gudang_ID', 
                                        'class' => 'form-control',
                                    ]); ?>
                            </div>
						</div>
						<div class="form-group">
                           <?php echo form_label('Mata Uang *', 'Currency_ID', ['class' => 'control-label col-md-3']) ?>
                            <div class="col-md-9">
                                <?php echo form_dropdown('f[Currency_ID]', $dropdown_currency, @$item->Currency_ID, [
                                        'id' => 'Currency_ID', 
                                        'class' => 'form-control',
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
												'id' => 'Supplier_ID'
											]); ?>
											
									<?php echo form_input('f[Supplier_Name]', set_value('f[Supplier_Name]', @$supplier->Kode_Supplier.' - '.@$supplier->Nama_Supplier, TRUE), [
												'id' => 'Supplier_Name', 
												'placeholder' => '', 
												'readonly' => 'readonly',
												'class' => 'form-control'
											]); ?>
									<span class="input-group-btn">
										<a href="javascript:;" data-action-url="<?php echo @$lookup_supplier ?>" data-title="<?php echo lang('heading:supplier_list')?>" data-act="ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
									</span>
								</div>
							</div>
						</div>
                	</div>
                </div>
				<hr/>
                <div class="row">
					<table id="dt_trans_gift_receipt" class="table table-bordered table-hover table-striped" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="20px"></th>
								<th><?php echo lang('label:item_name') ?></th>
								<th><?php echo lang('label:item_unit') ?></th>
								<th><?php echo lang('label:qty_receipt') ?></th>
								<th><?php echo lang('label:item_price') ?></th>
								<th><?php echo lang('label:discount_percentage') ?></th>
								<th><?php echo lang('label:discount_money') ?></th>
								<th><?php echo lang('label:sub_total') ?></th>
								<th><?php echo 'Tgl ED' ?></th>
								<th><?php echo 'No Batch'?></th>
							</tr>
						</thead>        
						<tbody>
						</tbody>
					</table>
				</div>

				<div class="form-group">
					<a href="javascript:;" data-action-url="<?php echo @$lookup_item ?>"  data-title="<?php echo lang('heading:item_list')?>" data-modal-lg="1" data-act="ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Barang</b></a>
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
							<?php echo form_label(lang('label:ppn'), 'ppn', ['class' => 'control-label col-md-3']) ?>
							<div class="col-lg-4">
								<div class="input-group">
									<span class="input-group-addon">
										<input type="checkbox" name="ppn_manual" id="ppn_manual" <?php echo (@$item->Pajak > 0) ? 'checked' : NULL?>/>
									</span>
									<?php echo form_input([
										'name' => 'f[ppn_percent]',
										'value' => set_value('f[ppn_percent]', '', TRUE),
										'id' => 'ppn_percent', 
										'type' => 'number',
										'placeholder' => '',
										'data-ppn_type'=> 'percent',
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
										'class' => 'form-control js-input-calculate-balance mask-number'
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
						<div class="form-group">
							<?php if ( ! @$is_edit): ?>
							<button id="js-btn-submit" type="button" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
							<button id="print" disabled="disabled" type="submit" class="btn btn-success"><?php echo 'Print'; ?></button>
							<button class="btn btn-warning" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';") ?>">New</button> 
							<button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';") ?>">Close</button> 
							<?php else: ?>
							<button id="js-btn-cancel" data-act="ajax-modal" data-action-url="<?php echo @$cancel_url ?>" data-title="<?php echo lang('cancel_label') ?>" type="button" class="btn btn-danger"><?php echo lang( 'buttons:cancel' ) ?></button>
							<?php endif; ?>
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
				edit: function( row, data, index ){
												
						switch( this.index() ){
															
							case 3:
							
								var _input = $( "<input type=\"number\" value=\"" + parseFloat(data.Qty) + "\" style=\"width:100%\" min=\"1\"  class=\"form-control\">" );										
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
																				
										try{
											
											data.Qty = this.value || 1;
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_row( row, data );
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;
							
							case 4:
							
								var _input = $( "<input type=\"number\" value=\"" + mask_number.currency_remove(data.Harga || 1) + "\" style=\"width:100%\" min=\"1\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();   
																				
										try{
											data.Harga = this.value || 1;
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_row( row, data );
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;
							case 5:
							
								var _input = $( "<input type=\"number\" value=\"" + parseFloat(data.Diskon_Persen) + "\" style=\"width:100%\" min=\"0\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
																		
										try{
											data.Diskon_Persen = this.value || 0;
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_row( row, data, 'percent' );
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;
							case 6:
							
								var _input = $( "<input type=\"number\" value=\"" + mask_number.currency_remove(data.Diskon_Rp) + "\" min=\"0\" style=\"width:100%\"  class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();     
																				
										try{
											data.Diskon_Rp = this.value || 0;
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_row( row, data, 'value' );
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;
							case 8:
							
							var _input = $( "<input type=\"date\" value=\"" + data.TglED + "\" style=\"width:100%\"  class=\"form-control\">" );
							this.empty().append( _input );
							
							_input.trigger( "focus" );
							_input.on( "blur", function(e){
									e.preventDefault();     
																			
									try{
										data.TglED = this.value;
										_datatable.row( row ).data( data );
										
									} catch(ex){}
								});
						break;
						case 9:
							
							var _input = $( "<input type=\"text\" value=\"" + data.NoBatch + "\" style=\"width:100%\"  class=\"form-control\">" );
							this.empty().append( _input );
							
							_input.trigger( "focus" );
							_input.on( "blur", function(e){
									e.preventDefault();     
																			
									try{
										data.NoBatch = this.value;
										_datatable.row( row ).data( data );
										
									} catch(ex){}
								});
						break;
						}
						
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope ).remove().draw();
								
						_datatable_actions.calculate_balance();	
					},
				calculate_row: function ( row, data, disc_type = '' ){
						var discount_percent, discount_value, sub_total = 0;
						
						sub_total = parseFloat(data.Qty) * mask_number.currency_remove(data.Harga);
						
						switch (disc_type)
						{
							case "percent" :
								data.Diskon_Rp = sub_total * parseFloat(data.Diskon_Persen) / 100;
								break;
							case "value" :
								data.Diskon_Persen = parseFloat( mask_number.currency_remove(data.Diskon_Rp) / sub_total * 100).toFixed(2);
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
						var _check_ppn = _form.find( "input[id=\"ppn_manual\"]" );
						var _form_ppn_percent = _form.find( "input[id=\"ppn_percent\"]" );
						var _form_ppn = _form.find( "input[id=\"Pajak\"]" );
						var _form_submit = _form.find( "button[id=\"btn-submit\"]" );
						
						var discount_total = sub_total = grand_total = 0;
						
						var collection = $("#dt_trans_gift_receipt").DataTable().rows().data();
						collection.each(function(value, index){							
							discount_total = discount_total + mask_number.currency_remove( value.Diskon_Rp );
							sub_total = sub_total + mask_number.currency_remove( value.sub_total );
						});
	
						_form_sub_total.val( mask_number.currency_add(sub_total) );

						grand_total = grand_total + sub_total;
						
						if ( _check_ppn.is(":checked") )
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
						
						// on load
						if ( _check_ppn.is(":checked") )
						{
							_form_ppn_percent.prop('readonly', false);
							_form_ppn.prop('readonly', false);
							
							_datatable_actions.calculate_balance( _form_ppn.data('ppn_type') );
						} else {
							_form_ppn_percent.prop('readonly', true);
							_form_ppn.prop('readonly', true);						
						}
						
						_check_ppn.on('change', function(){
							if ( $(this).is(":checked") )
							{
								_form_ppn_percent.val(10.00);							
								_form_ppn_percent.prop('readonly', false);
								_form_ppn.prop('readonly', false);
								
								_datatable_actions.calculate_balance( _form_ppn_percent.data('ppn_type') );
							} else {
								_form_ppn_percent.val('');
								_form_ppn.val('');
								_form_ppn_percent.prop('readonly', true);
								_form_ppn.prop('readonly', true);		
								
								_datatable_actions.calculate_balance();				
							}
							
						});

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
				dt_goods_receipt: function(){
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
										{ data: "Qty",  className: "text-right",},
										{ 
											data: "Harga", 
											className: "text-right",
											render: function(val, type, row){
													return mask_number.currency_add( val );
												}
										},
										{ data: "Diskon_Persen", width: "20px", className: "text-right",},
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
										{ data: "TglED", },
										{ data: "NoBatch", },					
									],
								drawCallback: function (){
										_datatable_actions.calculate_balance();
										$( window ).trigger( "resize" );
									},
								createdRow: function ( row, data, index ){
										
										$( row ).on( "click", "td", function(e){
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
											});
									}
							} );
							
						$( "#dt_trans_gift_receipt_length select, #dt_trans_gift_receipt_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_trans_gift_receipt" ).dt_goods_receipt();
				<?php if(! @$is_edit):?>
				$( '.btn-detail').trigger( 'click' );
				<?php endif;?>
				
				_form_actions.init();
								
				$("button#js-btn-submit").on("click", function(e){
					e.preventDefault();		
					
					$("button#js-btn-submit").addClass('disabled');
					
					$( '.btn-save-detail').trigger( 'click' );
					
					var data_post = {};
						data_post['header'] = {
								Tgl_Bonus : $('#Tgl_Bonus').val(),
								No_DO : $('#No_DO').val(),
								PPN : $('#ppn_percent').val(),
								NilaiPPN : $('#Pajak').val(),
								Supplier_ID : $('#Supplier_ID').val(),
								Lokasi_ID : $('#Gudang_ID').val(),
								Total_Nilai : $('#Total_Nilai').val(),
								Currency_ID : $('#Currency_ID').val(),
							};
							
						data_post['additional'] = {
								Supplier_Name : $("#Supplier_Name").val(),
								SectionName : $("#Gudang_ID").find('option:selected').html(),
							};
						data_post['details'] = {};

					var collection = $( "#dt_trans_gift_receipt" ).DataTable().rows().data();
					collection.each(function (value, index) {
						var detail = {
								Barang_ID : value.Barang_ID,
								Qty : value.Qty,
								Harga : value.Harga,
								Diskon_Persen: value.Diskon_Persen,
								Diskon_Rp : value.Diskon_Rp,
								Kode_Satuan : value.Kode_Satuan,
								TglED : value.TglED,
								NoBatch : value.NoBatch,
						}
						
						data_post['details'][index] = detail;												
					});
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
						
						if( "error" == response.status ){
							$.alert_error(response.message);
							$("button#js-btn-submit").removeClass('disabled');
							return false
						}
						
						$.alert_success( response.message );
						setTimeout(function(){							
							document.location.href = "<?php echo base_url($nameroutes); ?>";
						}, 300 );
						
					})	
				});
				
			});

	})( jQuery );
//]]>
</script>
