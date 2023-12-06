<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open(site_url("{$nameroutes}/update_post/{$_GET['id']}"), [
		'id' => 'form_update_receipt_item', 
		'name' => 'form_update_receipt_item', 
		'rule' => 'form', 
		'class' => ''
	]); 
?>
<input type="hidden" id="penerimaan_id" value="<?php echo $item->Penerimaan_ID ?>" name="h[Penerimaan_ID]" />
<?php /*?><input type="hidden" id="payment_method" value="" name="f[Type_Pembayaran]" />
<input type="hidden" id="payment_term" value="" name="f[Term_Pembayaran]" />
<input type="hidden" id="discount_type" value="" name="f[Type_Diskon]" />
<input type="hidden" id="Nilai_DP" value="" name="f[Nilai_DP]" />
<input type="hidden" id="Posting_KG" value="" name="f[Posting_KG]" />
<input type="hidden" id="Posting_GL" value="" name="f[Posting_GL]" />
<input type="hidden" id="Currency_ID" value="" name="f[Currency_ID]" />
<input type="hidden" id="Order_ID" value="" name="f[Order_ID]" />
<input type="hidden" id="No_Retur_Penjualan" value="" name="f[No_Retur_Penjualan]" />
<input type="hidden" id="Sumber_Penerimaan" value="" name="f[Sumber_Penerimaan]" />
<input type="hidden" id="Lokasi_ID" value="" name="f[Lokasi_ID]" />
<input type="hidden" id="Pembelian_Asset" value="" name="f[Pembelian_Asset]" />
<input type="hidden" id="NoFakturPajak" value="" name="f[NoFakturPajak]" />
<input type="hidden" id="TglFakturPajak" value="" name="f[TglFakturPajak]" />
<input type="hidden" id="Supplier_ID" value="" name="f[Supplier_ID]" />
<?php */?>
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
                                    <a href="javascript:;" 
                                    	title="<?php echo lang('action:add'); ?>" 
                                        data-act="ajax-modal" 
                                        data-title="<?php echo lang('action:add'); ?>" 
                                        data-action-url="<?php echo site_url("{$nameroutes}/create"); ?>">
                                        	<i class="fa fa-plus"></i> <?php echo lang('action:add') ?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="javascript:;" data-mass="delete">
                                        <i class="fa fa-trash-o"></i> <?php echo lang('action:delete') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo lang('heading:item_unit_list'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
            <div class="form-group">
            		<div class="row">
                        <div class="form-group col-md-2">
                        <?php echo form_label(lang('label:date').' *', 'input_date', ['class' => 'control-label']) ?>
                        <?php echo form_input('f[Tgl_Penerimaan]', set_value('f[Tgl_Penerimaan]', date("Y-m-d",strtotime($item->Tgl_Penerimaan)), TRUE), [
                                'id' => 'input_date', 
                                'placeholder' => '', 
                                'required' => 'required',
                                'class' => 'form-control datepicker'
                            ]); ?>
                        </div>
                        <div class="form-group col-md-2">
                            <?php echo form_label(lang('label:request_number').' *', 'input_receipt_number', ['class' => 'control-label']) ?>
                            <?php echo form_input('f[No_Penerimaan]', set_value('f[No_Penerimaan]', $item->No_Penerimaan, TRUE), [
                                    'id' => 'input_receipt_number', 
                                    'placeholder' => '',
                                    'required' => 'required', 
                                    'class' => 'form-control'
                                ]); ?>
                        </div>
                        <div class="form-group col-md-2">
                            <?php echo form_label(lang('label:warehouse').' *', 'input_section_id', ['class' => 'control-label']) ?>
                            <select class="form-control" required name="h[Gudang_ID]">
                            	<option value="">-- Select --</option>
                            	<?php foreach($dropdown_section as $section_id => $section_name): ?>
                            	<option value="<?php echo $section_id ?>" <?php echo $section_id == '1368' ? 'selected' : ''; ?>><?php echo $section_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <?php echo form_label(lang('label:due_date').' *', 'Tgl_JatuhTempo', ['class' => 'control-label']) ?>
                        <?php echo form_input('f[Tgl_JatuhTempo]', set_value('f[Tgl_JatuhTempo]',date("Y-m-d",strtotime($item->Tgl_JatuhTempo)), TRUE), [
                                'id' => 'Tgl_JatuhTempo', 
                                'placeholder' => '', 
                                'required' => 'required',
                                'class' => 'form-control datepicker'
                            ]); ?>
                        </div>
                        <div class="form-group col-md-4">
                            <?php echo form_label(lang('label:no_do').' *', 'no_do', ['class' => 'control-label']) ?>
                            <?php echo form_input('f[No_DO]', set_value('f[No_DO]', $item->No_DO, TRUE), [
                                'id' => 'No_DO', 
                                'placeholder' => '',
                                'required' => 'required', 
                                'class' => 'form-control'
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label class="control-label"><?php echo "Kode Supplier"//lang('reservations:mr_number_label') ?></label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user-md"></i></span>
                                <input type="text" id="Kode_Supplier" name="h[Kode_Supplier]" value="<?php echo @$data_supplier->Kode_Supplier ?>" placeholder="" class="form-control" readonly="readonly">
                                <div class="input-group-btn">
                                   <?php /*?> <a href="#" id="add_supplier" data-act="ajax-modal" data-toggle="modal" data-dismiss="modal" data-action-url="<?php echo @$lookup_supplier ?>" title="<?php echo lang('buttons:pick_patient') ?>" class="btn btn-info"><?php echo "Pick!"//lang('buttons:pick_patient') ?></a><?php */?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label"><?php echo "Nama Supplier" ?> <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                               <input type="text" id="Nama_Supplier" name="Nama_Supplier" value="<?php echo @$data_supplier->Nama_Supplier ?>" placeholder="" class="form-control" required readonly="readonly">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label"><?php echo "No PO"//lang('reservations:mr_number_label') ?></label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                                <input type="text" id="no_po" name="h[No_PO]" value="<?php echo @$data_order->No_Order ?>" placeholder="" class="form-control" readonly="readonly" required="required">
                                <div class="input-group-btn">
                                    <?php /*?><a href="#" data-toggle='modal' data-id="add_po" data-act="ajax-modal" role='modal' data-action-url="<?php echo @$lookup_po ?>" title="<?php echo lang('buttons:pick_patient') ?>" class="btn btn-info"><?php echo "Pick!"//lang('buttons:pick_patient') ?></a><?php */?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">&nbsp;</label>
                        	<button id="refresh_detail" type="button" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:refresh")?></b></button>
                        </div>
                	</div>
                </div>
                <table id="dt_trans_receipt_item" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                        	<th width="20px"></th>
                            <th><?php echo lang('label:item_code') ?></th>
                            <th><?php echo lang('label:item_name') ?></th>
                            <th><?php echo lang('label:item_unit') ?></th>
                            <th><?php echo lang('label:qty_po') ?></th>
                            <th><?php echo lang('label:has_receipt') ?></th>
                            <th><?php echo lang('label:qty_receipt') ?></th>
                            <th><?php echo lang('label:item_harga') ?></th>
                            <th><?php echo lang('label:discount_percentage') ?></th>
                            <th><?php echo lang('label:discount_money') ?></th>
                            <th><?php echo lang('label:item_jumlah') ?></th>
                            <th><?php echo lang('label:exp_date') ?></th>
                            <th><?php echo lang('label:no_batch') ?></th>
                        </tr>
                    </thead>        
                    <tbody>
                    </tbody>
                    <tfoot class="dtFilter">
                        <tr>
                        	<th width="20px"></th>
                            <th><?php echo lang('label:item_code') ?></th>
                            <th><?php echo lang('label:item_name') ?></th>
                            <th><?php echo lang('label:item_unit') ?></th>
                            <th><?php echo lang('label:qty_po') ?></th>
                            <th><?php echo lang('label:has_receipt') ?></th>
                            <th><?php echo lang('label:qty_receipt') ?></th>
                            <th><?php echo lang('label:item_harga') ?></th>
                            <th><?php echo lang('label:discount_percentage') ?></th>
                            <th><?php echo lang('label:discount_money') ?></th>
                            <th><?php echo lang('label:item_jumlah') ?></th>
                            <th><?php echo lang('label:exp_date') ?></th>
                            <th><?php echo lang('label:no_batch') ?></th>
                        </tr>
                    </tfoot>
                </table>
                <div class="row">
                    <div class="form-group">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <?php echo form_label(lang('label:description').' *', 'input_keterangan', ['class' => 'control-label']) ?>
                                <?php echo form_input('f[Keterangan]', set_value('f[Keterangan]', $item->Keterangan, TRUE), [
                                        'id' => 'Keterangan', 
                                        'placeholder' => '',
                                        'required' => '', 
                                        'class' => 'form-control'
                                    ]); ?>
                            </div>
                        </div>
                        <div class="col-lg-8">
                        	<div class="col-lg-3">
                            	<div class="form-group">
									<?php echo form_label(lang('label:total_discount').' *', 'total_diskon', ['class' => 'control-label']) ?>
                                    <?php echo form_input('h[total_diskon]', set_value('h[total_diskon]', '', TRUE), [
                                        'id' => 'total_diskon', 
                                        'placeholder' => '',
                                        'required' => 'required', 
                                        'class' => 'form-control',
										'readonly' => 'readonly'
                                    ]); ?>
                                </div>
                                <div class="form-group">
                                	<div class="row">
                                        <div class="col-lg-6">
                                            <?php echo form_label(lang('label:include_ppn').' *', 'include_ppn', ['class' => 'control-label']) ?>
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="checkbox" name="f[include_ppn]" id="include_ppn"/>
                                        </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="col-lg-9">
                            	 <div class="form-group">
									<?php echo form_label(lang('label:sub_total').' *', 'sub_total', ['class' => 'control-label']) ?>
                                    <?php echo form_input('h[sub_total]', set_value('h[sub_total]', '', TRUE), [
                                            'id' => 'sub_total', 
                                            'placeholder' => '',
                                            'required' => 'required', 
                                            'class' => 'form-control',
                                            'readonly' => 'readonly'
                                        ]); ?>
                            	</div>
                                <div class="form-group">
									<?php echo form_label(lang('label:receipt_charge').' *', 'Ongkos_Angkut', ['class' => 'control-label']) ?>
                                    <?php echo form_input('f[Ongkos_Angkut]', set_value('f[Ongkos_Angkut]', '', TRUE), [
                                            'id' => 'ongkos_kirim', 
                                            'placeholder' => '',
                                            'class' => 'form-control'
                                        ]); ?>
                            	</div>
                                <div class="form-group">
									<?php echo form_label(lang('label:potongan').' *', 'potongan', ['class' => 'control-label']) ?>
                                    <?php echo form_input('f[potongan]', set_value('f[potongan]', '', TRUE), [
                                            'id' => 'potongan', 
                                            'placeholder' => '',
                                            'class' => 'form-control'
                                        ]); ?>
                            	</div>
                                <div class="form-group">
                                	<div class="row">
                                        <div class="col-lg-1">
                                            <?php echo form_label(lang('label:ppn'), 'ppn', ['class' => 'control-label']) ?>
                                        </div>
                                        <div class="col-lg-1">
                                            <input type="checkbox" name="ppn_cek" id="ppn_cek"/>
                                        </div>
                                        <div class="col-lg-5">
                                        	<input type="text" name="h[ppn_value]" id="ppn_value" class="form-control" />
                                        </div>
                                        <div class="col-lg-5">
                                            <?php echo form_input('h[ppn]', set_value('h[ppn]', '', TRUE), [
                                                    'id' => 'ppn', 
                                                    'placeholder' => '',
                                                    'class' => 'form-control'
                                                ]); ?>
                                        </div>
                                    </div>
                            	</div>
                                <div class="form-group">
									<?php echo form_label(lang('label:grand_total').' *', 'grand_total', ['class' => 'control-label']) ?>
                                    <input type="text" name="h[grand_total]" class="form-control" id="Grand_Total" />
                            	</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                		<br /><br />
                	<!--<div class="row">-->
                        <?php /*?><button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
                        <button id="print" disabled="disabled" type="submit" class="btn btn-success"><?php echo 'Print'; ?></button>
                        <button class="btn btn-warning" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';") ?>">New</button> 
                        <button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';") ?>">Close</button> <?php */?>
                    <!--</div>-->
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
		  
		function count_all(){
			var _sub_total = 0,
				_ongkir = 0,
				_potongan = 0,
				_grand_total = 0,
				_ppn_value = 0,
				_ppn = 0;
				
			var sub_total	 = $("#sub_total").val();
			var	ongkos_kirim = $("#ongkos_kirim").val();
			var	potongan 	 = $("#potongan").val();
			var	ppn_value 	 = $("#ppn_value").val();
			var	ppn_cek 	 = $("#ppn_cek").val();
			var	ppn 		 = $("#ppn").val();
			var	total 		 = $("#total").val();
			
			
			_ppn = (sub_total * ppn_value)/100;
			_grand_total = (Number(sub_total) + Number(ongkos_kirim) + Number(ppn)) - potongan ;
			$("#ppn").val( _ppn );
			$("#Grand_Total").val( _grand_total );
		}		
		
		//kalkulasi tombol
		$("#refresh_detail").on("click",function(){
												 console.log('track')
			_datatable_actions.calculate_balance();
		})
		
		//perhitungan di bawah		
		$('#sub_total, #ongkos_kirim, #potongan, #ppn_value, #ppn_cek, #ppn').on('change click',function(){
			var _sub_total = 0,
				_ongkir = 0,
				_potongan = 0,
				_ppn_value = 0,
				_ppn = 0;
										
			if($("#ppn_cek").is(":checked")){
				_ppn_value = 10;
				$("#ppn_value").val( _ppn_value );
				count_all()
			}else{
				_ppn_value = 0;
				_ppn = 0;
				$("#ppn_value").val( _ppn_value );
				$("#ppn").val( _ppn );
				count_all()
			}			
		})
		

		
		var _datatable;		
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
												
						switch( this.index() ){}
						
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);
								
						_datatable_actions.calculate_balance();
						
					},
				calculate_balance: function(params, fn, scope){
						
						var _form = $( "form[name=\"form_update_receipt_item\"]" );
						
						var _form_balance 	  = _form.find( "input[id=\"Grand_Total\"]" ) ||0;
						var _form_sub_balance = _form.find( "input[id=\"sub_total\"]" ) ||0;
						var _form_discount 	  = _form.find( "input[id=\"total_diskon\"]" ) ||0;
						var _form_ongkir 	  = _form.find( "input[id=\"ongkos_kirim\"]" ) ||0;
						var _form_potongan 	  = _form.find( "input[id=\"potongan\"]" ) ||0;
						var _form_ppn 	  	  = _form.find( "input[id=\"ppn\"]" )||0;
						
						var _form_submit 	  = _form.find( "button[id=\"btn-submit\"]" );
						
						var tol_debit = 0, 
							tol_credit = 0, 
							tol_balance = 0,
							qty = 0,
							discount =0,
							_discount = 0,
							price =0,
							tot_discount = 0;
						
						var rows = _datatable.rows().nodes();
						//console.log(rows);
						for( var i=0; i<rows.length; i++ )
						{
							
							tol_balance = tol_balance + Number($(rows[i]).find("td:eq(10)").html());
							qty = Number($(rows[i]).find("td:eq(6)").html().replace(',',''));
							price = Number($(rows[i]).find("td:eq(7)").html().replace(',',''));
							discount = Number($(rows[i]).find("td:eq(8)").html().replace(',',''));
							
						}
						console.log(tol_balance)
						
						//hitung total diskon
						_discount = qty * price;
						tot_discount = (_discount * discount)/100; 
						
						_form_sub_balance.val(tol_balance);
						_form_ongkir.val( _form_ongkir.val());
						_form_potongan.val( _form_potongan.val());
						_form_ppn.val( _form_ppn.val());
						
						_form_balance.val( (Number((_form_sub_balance.val()).replace(',','')) + Number((_form_ongkir.val()).replace(',','')) + Number((_form_ppn.val()).replace(',',''))) - Number((_form_potongan.val()).replace(',','')) );

						
						//total diskon
						_form_discount.val(tot_discount);
						
						if (tol_balance == 0)
						{
							_form_balance.removeClass("text-danger");
							_form_submit.removeAttr("disabled");
						} else {
							_form_balance.addClass("text-danger");
							_form_submit.attr("disabled");
						}			
						
					},
				add_row: function( params, fn, scope ){
						_datatable.row.add(
							{
							}
						).draw(false);
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
								paginate: true,
								ordering: false,
								searching: true,
								info: true,
								responsive: true,
								scrollCollapse: true,
								ajax: {
										url: "<?php echo base_url("{$nameroutes}/datatable_collection") ?>",
										type: "POST",
										data: function( params ){
												params.Penerimaan_ID = $("#penerimaan_id").val();
											}
									},
								columns: [
										{ 
											data: "Barang_ID", 
											width: "5px",
											orderable: false,
											searchable: false,
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										//{data: "Permintaan_ID"},	
										{ 
											data: "Kode_Barang", 
											className: "", 
										},
										{ data: "Nama_Barang", width: "40px", className: "" },
										{ data: "Kode_Satuan", className: "" },
										{ data: "Qty_PO", className: "" },
										{ data: "Qty_Telah_Terima"},
										{ data: "Qty_Penerimaan", className: "" },
										{ data: "Harga_Beli", width: "20px", className: "text-right",render: $.fn.dataTable.render.number(',','.',2,'') },
										{ data: "Diskon_1", width: "20px"},
										{ data: "Diskon_Rp", render: $.fn.dataTable.render.number(',','.',2,'')},
										{ data: "sub_total", render: $.fn.dataTable.render.number('','',0,'') },
										{ data: "Exp_Date"},
										{ data: "NoBatch"}
									
									],
								columnDefs  : [
										{
											"targets": ["Penerimaan_ID","Barang_ID","Kode_Pajak","Rate_Pajak","Konversi"],
											"visible": true,
											"searchable": false
										}
									],
								"drawCallback": function( settings ) {
									//dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								createdRow: function ( row, data, index ){
										//_datatable_actions.get_component_service( data );
										
										$( "td", row ).on( "dblclick", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
											
										$( "a.btn-remove", row ).on( "click", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('poly:delete_confirm_message') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
											})
									}
							} );
							
						$( "#dt_trans_receipt_item_length select, #dt_trans_receipt_item_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_trans_receipt_item" ).dt_services();
								
				$("form[name=\"form_create_receipt_item\"]").on("submit", function(e){
					e.preventDefault();	
					
					
					var data_post = $(this).serializeArray();
					var details = [];
					
					var table_data = $( "#dt_trans_receipt_item" ).DataTable().rows().data();
					table_data.each(function (value, index) {
						var detail = {
								Qty_Penerimaan	: value.Qty_Penerimaan,
								Harga_Beli 		: parseInt(value.Harga_Beli),
								Diskon_1			: value.Diskon_1,
								PPn 				: $("#ppn_value").val() || 0,
								Qty_PO 			: value.Qty_PO,
								Exp_Date 			: value.Exp_Date || null,
								Penerimaan_ID 	: value.Penerimaan_ID,
								Barang_ID	: value.Barang_ID,
								Qty_Telah_Terima  : value.Qty_Telah_Terima,
								Kode_Pajak 		: value.Kode_Pajak || '',
								Rate_Pajak 		: value.Rate_Pajak || 0,
								Kode_Satuan		: value.Kode_Satuan,
								Kode_Satuan_Stok  : value.Kode_Satuan,
								JenisBarangID 	: 0,
								Qty_Stok			: value.Qty_Penerimaan,
								Diskon_Rp		: value.Diskon_Rp,
								NoBatch			: value.NoBatch,
								Konversi 			: value.Konversi
						}
						//console.log(detail);
						details.push($.param(detail));
					});
					
					data_post.push({name : "details", value : details});
					//console.log(data_post);
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
						//console.log(response);
						//var response = $.parseJSON(response);

						if( "error" == response.status ){
							$.alert_error(response.status);
							return false
						}
						
						//$.alert_success("<?php echo lang('global:created_successfully')?>");
						
						var Penerimaan_ID = response.Penerimaan_ID;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("{$nameroutes}/update"); ?>?id="+ Penerimaan_ID;
							
							}, 30 );
						
					})	
				});

			});

	})( jQuery );
//]]>
</script>

