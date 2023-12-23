<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item);exit;
?>
<style>
	.btn-info {
		padding: 9px 15px 8px 15px!important;	
}
</style>
<?php echo form_open( $form_action, [
		'id' => 'form_goods_receipt_detail', 
		'name' => 'form_goods_receipt_detail', 
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
                                    <a href="<?php echo site_url("{$nameroutes}/create_penerimaan"); ?>">
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
                        <?php echo form_label(lang('label:date').' *', 'Tgl_Penerimaan', ['class' => 'control-label col-md-3']) ?>
                        <div class="col-md-9">
							<?php echo form_input('f[Tgl_Penerimaan]', set_value('f[Tgl_Penerimaan]', date('Y-m-d', strtotime(@$item->Tgl_Penerimaan)), TRUE), [
									'id' => 'input_date', 
									'placeholder' => '', 
									'required' => 'required',
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
                                        @$is_edit ? 'disabled' : 'required' => TRUE, 
                                        'class' => 'form-control',
										'data-target' => '#JenisPengadaanID', 
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
										'class' => 'form-control'
									]); ?>
							</div>
						</div>
                    </div>
                    <div class="col-md-6">
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
                        <div class="form-group">
                            <?php echo form_label(lang('label:due_date').' *', 'Tgl_JatuhTempo', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Tgl_JatuhTempo]', set_value('f[Tgl_JatuhTempo]', date('Y-m-d', strtotime(@$item->Tgl_JatuhTempo)), TRUE), [
										'id' => 'Tgl_JatuhTempo', 
										'placeholder' => '', 
										'required' => 'required',
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
									'required' => 'required', 
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
						<div class="form-group">
							<label class="control-label col-md-3"></label>						
							<label for="IncludePPN" class="col-md-3">
								<input type="checkbox" name="f[IncludePPN]" id="IncludePPN" class="checkbox checkth" value="1" <?php echo @$item->IncludePPN ? 'checked' : NULL ?>/>
								<?php echo lang('label:include_ppn') ?>
							</label>
						</div>
                	</div>
                </div>
				<hr/>
                <div class="row">
					<table id="dt_trans_goods_receipt_detail" class="table table-bordered table-hover table-striped" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="20px"></th>
								<th><?php echo lang('label:item_name') ?></th>
								<th><?php echo lang('label:item_unit') ?></th>
								<th><?php echo "Konversi" ?></th>
								<th><?php echo "Qty Order" ?></th>
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
					<div class="form-group">
                        <a href="javascript:;" data-action-url="<?php echo @$item_lookup ?>"  data-title="<?php echo lang('heading:item_list')?>" data-modal-lg="1" data-act="ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Barang</b></a>
                    </div>
				</div>
				<hr/>
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
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<?php echo form_label(lang('label:receipt_charge').' *', 'Ongkos_Angkut', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Ongkos_Angkut]', set_value('f[Ongkos_Angkut]', @$item->Ongkos_Angkut, TRUE), [
										'id' => 'Ongkos_Angkut', 
										'placeholder' => '',
										'class' => 'form-control js-input-calculate-balance  mask-number'
									]); ?>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:potongan').' *', 'potongan', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Potongan]', set_value('f[Potongan]', @$item->Potongan, TRUE), [
										'id' => 'Potongan', 
										'placeholder' => '',
										'class' => 'form-control js-input-calculate-balance  mask-number'
									]); ?>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:grand_total').' *', 'Total_Nilai', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Total_Nilai]', set_value('f[Total_Nilai]', $item->Total_Nilai, TRUE), [
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
					<div class="col-md-6">
						<?php if (@$item->Status_Batal == 1): ?>
						<div class="form-group">
							<div class="col-md-offset-3 col-md-9">
								<h4 class="text-danger"><?php echo lang('message:cancel_data') ?></h4>
							</div>
						</div>
						<?php endif; ?>	
				
					</div>
					<div class="col-md-6">
						<div class="form-group pull-right">
							<?php if ( ! @$is_edit): ?>
								<!-- <button id="print" disabled="disabled" class="btn btn-warning"><i class="fa fa-print" aria-hidden="true"></i> <?php echo 'Cetak'; ?></button> -->
								<button class="btn btn-primary" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';") ?>"><i class="fa fa-file-o" aria-hidden="true"></i> Buat Baru</button> 
								<button class="btn btn-danger" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';") ?>"><i class="fa fa-times-circle" aria-hidden="true"></i> Tutup</button> 
								<button id="js-btn-submit" type="button" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo lang( 'buttons:save' ) ?></button>
							<?php else: ?>
								<a href="<?php echo base_url("{$nameroutes}/print_factur/{$item->Penerimaan_ID}") ?>" target="_blank" class="btn btn-warning"><i class="fa fa-print"></i> <?php echo lang("buttons:print") ?></a></li>
								<!-- <button id="print" class="btn btn-warning" <?= (@$item->Status_Batal == 1) ? 'disabled' : null ?>><i class="fa fa-print" aria-hidden="true"></i> <?php echo 'Cetak'; ?></button> -->
								<button class="btn btn-primary" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';") ?>"><i class="fa fa-file-o" aria-hidden="true"></i> Buat Baru</button> 
								<button id="js-btn-cancel" data-act="ajax-modal" data-action-url="<?php echo @$cancel_url ?>" data-title="<?php echo 'Konfirmasi Batal' ?>" type="button" class="btn btn-danger" <?= (@$item->Status_Batal == 1) ? 'disabled' : null ?>><i class="fa fa-ban" aria-hidden="true"></i> <?php echo 'Batalkan' ?></button>
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
							
								var _input = $( "<input type=\"number\" value=\"" + parseFloat(data.Konversi) + "\" style=\"width:100%\" min=\"1\"  class=\"form-control\">" );										
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
																				
										try{
											
											data.Konversi = this.value || 1;
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_row( row, data );
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;

							case 4:
							
								var _input = $( "<input type=\"number\" value=\"" + parseFloat(data.Qty_Penerimaan) + "\" style=\"width:100%\" min=\"1\"  class=\"form-control\">" );										
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
																				
										try{
											
											data.Qty_Penerimaan = this.value || 1;
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_row( row, data );
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;

							case 5:
							
								var _input = $( "<input type=\"number\" value=\"" + mask_number.currency_remove(data.Harga_Beli || 1) + "\" style=\"width:100%\" min=\"1\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();   
																				
										try{
											data.Harga_Beli = this.value || 1;
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_row( row, data );
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;
							
							case 6:
							
								var _input = $( "<input type=\"number\" value=\"" + parseFloat(data.Diskon_1) + "\" style=\"width:100%\" min=\"0\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
																		
										try{
											data.Diskon_1 = this.value || 0;
											
											_datatable.row( row ).data( data );
											_datatable_actions.calculate_row( row, data, 'percent' );
											_datatable_actions.calculate_balance();
											
										} catch(ex){}
									});
							break;

							case 7:
							
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
						}
						
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope ).remove().draw();
								
						_datatable_actions.calculate_balance();	
					},
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
													'<input type="text" name="Exp_Date" value="'+ data.Exp_Date +'" class="form-control datepicker" autocomplete="off">'+
												'</div>'+
											'</div>'+
										'</div>'+
										'<div class="col-md-4">'+
											'<div class="form-group">'+
												'<label class="col-md-4">Nomor Batch</label>'+
												'<div class="col-md-8">'+
													'<input type="text" name="NoBatch" value="'+ (data.NoBatch || '') +'" class="form-control" autocomplete="off">'+
												'</div>'+
											'</div>'+
										'</div>'+
										'<div class="col-md-4">'+
											'<div class="form-group text-right">'+
												'<button type="button" class="save btn btn-primary btn-save-detail btn-block"><i class="fa fa-angle-double-up" aria-hidden="true"></i> Tutup Detail</button>'+													
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
						
						var _form = $( "form[name=\"form_goods_receipt_detail\"]" );
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
						
						var collection = $("#dt_trans_goods_receipt_detail").DataTable().rows().data();
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
						
						var _form = $( "form[name=\"form_goods_receipt_detail\"]" );
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
										{ data: "Konversi", },
										{ data: "Qty_Penerimaan", className: "text-right", width:'120px'},
										{ 
											data: "Harga_Beli", 
											className: "text-right",
											render: function(val, type, row){
													return mask_number.currency_add( val );
												}
										},
										{ data: "Diskon_1", width: "100px", className: "text-right",},
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
											});
											
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
            	$( "#dt_trans_goods_receipt_detail" ).dt_services();
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
								Tgl_Penerimaan : $('#Tgl_Penerimaan').val(),
								Lokasi_ID : $('#Lokasi_ID').val(),
								Keterangan : $('#Keterangan').val(),
								Order_ID : $('#Order_ID').val(),
								Supplier_ID : $('#Supplier_ID').val(),
								Tgl_JatuhTempo : $('#Tgl_JatuhTempo').val(),
								No_DO : $('#No_DO').val(),
								IncludePPN : $('#IncludePPN:checked').val() || 0,
								Pajak : $('#ppn_manual').is(':checked') ? $('#Pajak').val() : 0,
								Ongkos_Angkut : $('#Ongkos_Angkut').val(),
								Potongan : $('#Potongan').val(),
								Total_Nilai : $('#Total_Nilai').val(),
							};
							
						data_post['additional'] = {
								Supplier_Name : $("#Supplier_Name").val(),
								SectionName : $("#Lokasi_ID").find('option:selected').html(),
							};
						data_post['details'] = {};
					
					var collection = $( "#dt_trans_goods_receipt_detail" ).DataTable().rows().data();
					
					collection.each(function (value, index) { 
						var detail = {
								Qty_Penerimaan : value.Qty_Penerimaan,
								Harga_Beli : mask_number.currency_remove(value.Harga_Beli),
								Diskon_1 : value.Diskon_1,
								Qty_PO : value.Qty_PO,
								Exp_Date : value.Exp_Date || '<?php echo ('Y-m-d')?>',
								NoBatch : value.NoBatch,
								Rate_Pajak : $('#ppn_manual').is(':checked') ? $('#ppn_percent').val() : 0,
								Barang_ID : value.Barang_ID,
								Qty_Telah_Terima : value.Qty_Telah_Terima,
								Kode_Satuan : value.Kode_Satuan,
								Kode_Satuan_Stok : value.Kode_Satuan,
								JenisBarangID : value.JenisBarangID,
								Qty_Stok : value.Qty_Penerimaan,
								Diskon_Rp : value.Diskon_Rp,
								Konversi : value.Konversi
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
							document.location.href = "<?php echo base_url("{$nameroutes}/update"); ?>/"+ response.Penerimaan_ID;
						}, 300 );
						
					})	
				});
				
			});

	})( jQuery );
//]]>
</script>
