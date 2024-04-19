<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open(site_url("{$nameroutes}/update_post/{$_GET['id']}"), [
		'id' => 'form_update_purchase_request', 
		'name' => 'form_update_purchase_request', 
		'rule' => 'form', 
		'class' => ''
	]); 
?>
<input type="hidden" id="permintaan_id" value="<?php echo @$item->Permintaan_ID ?>" name="h[Permintaan_ID]" />
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
                    <div class="form-group">
                    <?php echo form_label(lang('label:date').' *', 'input_date', ['class' => 'control-label']) ?>
                    <?php echo form_input('f[Tgl_Permintaan]', set_value('f[Tgl_Permintaan]', date("Y-m-d",strtotime($item->Tgl_Permintaan)), TRUE), [
                            'id' => 'input_date', 
                            'placeholder' => '', 
                            'required' => 'required',
                            'class' => 'form-control datepicker',
							'readonly' => 'readonly'
                        ]); ?>
                    </div>
                    <div class="form-group">
                        <?php echo form_label(lang('label:request_number').' *', 'input_request_number', ['class' => 'control-label']) ?>
                        <?php echo form_input('f[No_Permintaan]', set_value('f[No_Permintaan]', $item->No_Permintaan, TRUE), [
                                'id' => 'input_request_number', 
                                'placeholder' => '',
                                'required' => 'required', 
                                'class' => 'form-control'
                            ]); ?>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <?php echo form_label(lang('label:warehouse').' *', 'input_section_id', ['class' => 'control-label']) ?>
                            
                            <select class="form-control" required name="f[Gudang_ID]">
                            	<option value="">-- Select --</option>
                            	<?php foreach($dropdown_section as $section_id => $section_name): ?>
                            	<option value="<?php echo $section_id ?>" <?php echo $item->Gudang_ID == $section_id ? 'selected' : ''; ?>><?php echo $section_name ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                        <div class="form-group col-md-6">
                            <?php echo form_label(lang('label:date_needed').' *', 'input_date_request', ['class' => 'control-label']) ?>
                        <?php echo form_input('f[Tgl_Dibutuhkan]', set_value('f[Tgl_Dibutuhkan]', date("Y-m-d",strtotime($item->Tgl_Dibutuhkan)), TRUE), [
                                'id' => 'input_date_request', 
                                'placeholder' => '', 
                                'required' => 'required',
                                'class' => 'form-control datepicker'
                            ]); ?>
                        </div>
                        <div class="form-group col-md-6">
                            <?php echo form_label(lang('label:procurement_type').' *', 'input_procurement_type', ['class' => 'control-label']) ?>
                            <select class="form-control" required name="f[JenisPengadaanID]">
                            	<option value="">-- Select --</option>
                            	<?php foreach($dropdown_procurement as $section_id => $section_name): ?>
                            	<option value="<?php echo $section_id ?>" <?php echo $item->JenisPengadaanID == $section_id ? 'selected' : ''; ?>><?php echo $section_name ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                </div>
                </div>
                <table id="dt_trans_purchase_request_detail" class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                        	<th></th>
                            <th><?php echo lang('label:item_code') ?></th>
                            <th><?php echo lang('label:item_name') ?></th>
                            <th><?php echo lang('label:item_konversion') ?></th>
                            <th><?php echo lang('label:item_category') ?></th>
                            <th><?php echo lang('label:item_unit') ?></th>
                            <th><?php echo lang('label:item_min_stock') ?></th>
                            <th><?php echo lang('label:item_max_stock') ?></th>
                            <th><?php echo lang('label:item_qty_sistem') ?></th>
                            <th><?php echo lang('label:item_qty') ?></th>
                            <th><?php echo lang('label:item_harga') ?></th>
                            <th><?php echo lang('label:item_jumlah') ?></th>
                        </tr>
                    </thead>        
                    <tbody>
                    </tbody>
                    <tfoot class="dtFilter">
                        <tr>
                        	<th></th>
                            <th><?php echo lang('label:item_code') ?></th>
                            <th><?php echo lang('label:item_name') ?></th>
                            <th><?php echo lang('label:item_konversion') ?></th>
                            <th><?php echo lang('label:item_category') ?></th>
                            <th><?php echo lang('label:item_unit') ?></th>
                            <th><?php echo lang('label:item_min_stock') ?></th>
                            <th><?php echo lang('label:item_max_stock') ?></th>
                            <th><?php echo lang('label:item_qty_sistem') ?></th>
                            <th><?php echo lang('label:item_qty') ?></th>
                            <th><?php echo lang('label:item_harga') ?></th>
                            <th><?php echo lang('label:item_jumlah') ?></th>
                        </tr>
                    </tfoot>
                </table>
                <div class="form-group">
                    <a href="#" data-action-url="<?php echo @$item_lookup ?>" id="update_charge" data-act="ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Barang</b></a>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <?php echo form_label(lang('label:description').' *', 'input_keterangan', ['class' => 'control-label']) ?>
                                <?php echo form_input('f[Keterangan]', set_value('f[Keterangan]', $item->Keterangan, TRUE), [
                                        'id' => 'input_keterangan', 
                                        'placeholder' => '',
                                        'required' => '', 
                                        'class' => 'form-control'
                                    ]); ?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <?php echo form_label(lang('label:grand_total').' *', 'input_total', ['class' => 'control-label']) ?>
                                <?php echo form_input('h[grand_total]', set_value('h[grand_total]', number_format($jumlah_total,'2','.',','), TRUE), [
                                        'id' => 'input_total', 
                                        'placeholder' => '',
                                        'required' => 'required', 
                                        'class' => 'form-control'
                                    ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                	<!--<div class="row">-->
                    	<br /><br />
                        <button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
                        <button id="print" type="submit" class="btn btn-success"><?php echo 'Print'; ?></button>
                        <button class="btn btn-warning" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';") ?>">New</button> 
                        <button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';") ?>">Close</button> 
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_hidden('mass_action', ''); ?>
<?php echo form_close() ?>
<?php /*?><script>
(function( $ ){   
		$( document ).ready(function(e) {
            	var _form = $('form[name="form_crud__detail_transaction"]');
				 
				$( "#dt_trans_purchase_request_detail" ).DataTable({
						order: false,
						searching: false,
						info: false,
						ajax: {
								url: "<?php echo site_url("{$nameroutes}/table_item_collection") ?>",
								type: "POST",
								data: function( params ){}
							},
						columns: [
								null,
								null,
								null,
								null,
								null,
								null,
								null,
								null,
								null, 
								{data: null, render: function(data, type, display, row) {var input_text = '<input type="number" value="'+ data[9]+'" name="qty_request">'; return input_text}},
								{data: 10, defaultContent: '<input type="number" value="0" class="form-control col-md-2" name="jumlah_total">',render: $.fn.dataTable.render.number(',','.',0,'')},
								{data: null, render: function(data, type, display, row){var calculate = (Number(data[10]) * data[9]); var input_text = '<input type="text" value="'+ calculate +'" name="qty_request">';return input_text }},
								{orderable: false, searchable: false}
							]
					});
				$('.panel-bars .btn-bars .dropdown-menu a[data-mass="delete"]').click(function (e) {
						e.preventDefault();
						_form.find('input[name="mass_action"]').val($(this).attr('data-mass'));
						_form.trigger('submit');
					});
			});
	})( jQuery );
</script>
<?php */?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;		
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
						
						<?php /*?>if ( this.index() == 0 ) {
								
							try{
								if( confirm( "<?php echo lang('poly:delete_confirm_message') ?>" ) ){
										_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
								}
							} catch(ex){}
								
						}
						
						if ( this.index() > 0 ) {
							try{
								indexRow = _datatable.row( row ).index();
								
								form_ajax_modal.show("<?php echo $view_service='' ?>/"+ indexRow +"/"+ data.ListHargaID)
							} catch(ex){}
						}<?php */?>
						
						switch( this.index() ){
							case 0:
								try{
									if( confirm( "<?php echo lang('charts:remove_service_confirm') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
								} catch(ex){}
							break;
									
							case 9:
							
								var _input = $( "<input type=\"number\" value=\"" + Number(data.Qty_Permintaan || 0) + "\" style=\"width:100%\"  class=\"form-control qty_recipient\">" );
								var discount;
								var total;
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
										if(_input.val() < 0)
										{
											alert("Angka input tidak Valid!");
											_input.val("0");
										}       
										try{
											data.Qty_Permintaan = this.value || this.value;
											data.Jumlah_Total = Number(data.Qty_Permintaan) * Number(data.Harga_Beli);
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
						
						var _form = $( "form[name=\"form_update_purchase_request\"]" );
						var _form_balance = _form.find( "input[id=\"grand_total\"]" );
						var _form_credit = _form.find( "input[id=\"credit\"]" );
						var _form_balance = _form.find( "input[id=\"balance\"]" );
						var _form_submit = _form.find( "button[id=\"btn-submit\"]" );
						
						var tol_debit = 0, 
							tol_credit = 0, 
							tol_balance = 0;
						
						var rows = _datatable.rows().nodes();
						//console.log(rows);
						for( var i=0; i<rows.length; i++ )
						{
							tol_debit = tol_debit + Number($(rows[i]).find("td:eq(11)").html());
						}
						
						//tol_balance = tol_debit - tol_credit;

						//_form_debit.val(tol_debit);	
						//_form_credit.val(tol_credit);
						$("#input_total").val(tol_debit);
						
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
				dt_purchase_request: function(){
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
												params.Permintaan_ID = $("#permintaan_id").val();
												params.Barang_ID = $("#barang_id").val();
											}
									},
								columns: [
										{ 
											data: "Permintaan_ID", 
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
										{ data: "Nama_Barang", className: "" },
										{ data: "Konversi", className: "" },
										{ data: "Nama_Kategori", className: "" },
										{ data: "Nama_Satuan"},
										{ data: "Min_Stok", className: "" },
										{ data: "Max_Stok", className: "" },
										{ data: "Qty_Stok", className: "text-center", },
										{ data: "Qty_Permintaan", className: "text-center", },
										{ data: "Harga_Beli", render: $.fn.dataTable.render.number('','',0,'') },
										{ data: "Jumlah_Total", render: $.fn.dataTable.render.number('','',0,'') }
									
									],
								columnDefs  : [
										{
											"targets": [0,1,10],
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
							
						$( "#dt_trans_purchase_request_detail_length, #dt_trans_purchase_request_detail_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_trans_purchase_request_detail" ).dt_purchase_request();
								
				$("form[name=\"form_update_purchase_request\"]").on("submit", function(e){
					e.preventDefault();	
					
					
					var data_post = $(this).serializeArray();
					var details = [];
					
					var table_data = $( "#dt_trans_purchase_request_detail" ).DataTable().rows().data();
					table_data.each(function (value, index) {
						var detail = {
								"Qty_Permintaan" : value.Qty_Permintaan,
								"Harga_Terakhir" : value.Harga_Beli,
								"Permintaan_ID" : value.Permintaan_ID,
								"Barang_ID" : value.Barang_ID,
								"Kode_Satuan" : value.Nama_Satuan,
								"Keterangan" : value.Keterangan,
								"JenisBarangID" : value.JenisBarangID,
								"Qty_Stok" : value.Qty_Stok,
								"Min_Stok" : value.Min_Stok,
								"Max_Stok" : value.Max_Stok
						}
						//console.log(detail);
						details.push($.param(detail));
					});
					
					data_post.push({name : "details", value : details});
					//console.log(data_post);
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
						console.log(response);
						console.log(status);
						
						//var response = $.parseJSON(response);

						if( "error" == response.status ){
							$.alert_error(response.status);
							return false
						}
						
						//$.alert_success("<?php echo lang('global:created_successfully')?>");
						
						var Penerimaan_ID = response.Penerimaan_ID;
						
						setTimeout(function(){
							document.location.href = "<?php echo base_url("{$nameroutes}/edit"); ?>?id="+ Penerimaan_ID;
							}, 30 );
						
					})	
				});

			});

	})( jQuery );
//]]>
</script>
