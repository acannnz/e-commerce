<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($collection);exit;
?>
<?php echo form_open(current_url(), [
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
							</div>
						</div>
                	</div>
                </div>
				<hr/>
                <div class="row">
					<table id="dt_trans_gift_receipt" class="table table-bordered table-hover table-striped" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th><?php echo lang('label:item_name') ?></th>
								<th><?php echo lang('label:item_unit') ?></th>
								<th><?php echo lang('label:qty_receipt') ?></th>
								<th><?php echo lang('label:item_price') ?></th>
								<th><?php echo lang('label:discount_percentage') ?></th>
								<th><?php echo lang('label:discount_money') ?></th>
								<th><?php echo lang('label:sub_total') ?></th>
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
							<?php echo form_label(lang('label:ppn'), 'ppn', ['class' => 'control-label col-md-3']) ?>
							<div class="col-lg-4">
								<div class="input-group">
									<?php echo form_input([
										'name' => 'f[PPN]',
										'value' => set_value('f[PPN]', $item->PPN, TRUE),
										'id' => 'PPN', 
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
										'name' => 'f[NilaiPPN]', 
										'value' => set_value('f[NilaiPPN]', @$item->NilaiPPN, TRUE),
										'id' => 'NilaiPPN', 
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
				<?php  if ( $item->Batal == 0): ?>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group pull-right">
							<button id="js-btn-cancel" data-act="ajax-modal" data-action-url="<?php echo @$cancel_url ?>" data-title="<?php echo lang('cancel_label') ?>" type="button" class="btn btn-danger"><?php echo lang( 'buttons:cancel' ) ?></button>
						</div>
					</div>
				</div>
				<?php endif;  ?>
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
						var _form_PPN = _form.find( "input[id=\"PPN\"]" );
						var _form_ppn = _form.find( "input[id=\"NilaiPPN\"]" );
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
									var value = sub_total * parseFloat(_form_PPN.val() || 0) / 100;
									_form_ppn.val( mask_number.currency_add(value) );
								break;
								case "value" :
									var value = parseFloat( mask_number.currency_remove(_form_ppn.val() || 0) / sub_total * 100 ).toFixed(2);
									_form_PPN.val( value );
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
												
						var _form = $( "form[name=\"form_goods_receipt\"]" );
						var _form_PPN = _form.find( "input[id=\"PPN\"]" );
						var _form_ppn = _form.find( "input[id=\"NilaiPPN\"]" );
						
						// on load
						_form_PPN.prop('readonly', false);
						_form_ppn.prop('readonly', false);
						
						_datatable_actions.calculate_balance( 'percentage' );						
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
									],
								drawCallback: function (){
										_datatable_actions.calculate_balance();
										$( window ).trigger( "resize" );
									},
							} );
							
						$( "#dt_trans_gift_receipt_length select, #dt_trans_gift_receipt_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_trans_gift_receipt" ).dt_goods_receipt();
				_form_actions.init();
			});

	})( jQuery );
//]]>
</script>
