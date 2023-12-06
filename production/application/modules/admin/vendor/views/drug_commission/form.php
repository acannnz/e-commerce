<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_drug_commission', 
		'name' => 'form_drug_commission', 
		'rule' => 'form', 
		'class' => 'form-horizontal'
	]); ?>

<div class="row">
	<div class="col-md-offset-2 col-md-8">
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
                <h3 class="panel-title"><?php echo lang('heading:drug_commission_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
					<div class="col-md-12 col-xs-12">
						<div class="form-group">
							<?php echo form_label(lang('label:code').' *', 'Kode_Supplier', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-6">
								<?php echo form_input('f[Kode_Supplier]', set_value('f[Kode_Supplier]', @$item->Kode_Supplier, TRUE), [
										'id' => 'Kode_Supplier', 
										'placeholder' => '', 
										'class' => 'form-control',
										'readonly' => 'readonly'
									]); ?>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:name'), 'Nama_Supplier', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-6">
								<?php echo form_input('f[Nama_Supplier]', set_value('f[Nama_Supplier]', @$item->Nama_Supplier, TRUE), [
										'id' => 'Nama_Supplier', 
										'placeholder' => '',
										'class' => 'form-control',
										'readonly' => 'readonly'
									]); ?>
							</div>
						</div>
						<div class="form-group">
							<?php echo form_label(lang('label:address'), 'Alamat_1', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-6">
								<?php echo form_textarea([
										'name' => 'f[Alamat_1]', 
										'value' => set_value('f[Alamat_1]', @$item->Alamat_1, TRUE),
										'id' => 'Alamat_1', 
										'placeholder' => '',
										'class' => 'form-control',
										'rows' => 3,
										'readonly' => 'readonly'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<hr/>
					<ul class="nav nav-tabs nav-justified">
						<li class="active"><a href="#commission-item" data-toggle="tab"><i class="fa fa-medkit"></i> <strong><?php echo lang("subtitle:commission_item")?></strong></a></li>
						<li class=""><a href="#commission-patient" data-toggle="tab"><i class="fa fa-user"></i> <strong><?php echo lang("subtitle:commission_patient")?></strong></a></li>
						<li class=""><a href="#commission-tht" data-toggle="tab"><i class="fa fa-user-md"></i> <strong><?php echo lang("subtitle:commission_tht")?></strong></a></li>
					</ul>
					<div class="tab-content">
						<div id="commission-item" class="tab-pane active">
							<div class="row mb10">
								<div class="col-sm-12 col-xs-12">
									<div class="col-sm-offset-1 col-sm-10 col-xs-12">
										<div class="row lookupbox7-form-control">
											<div class="col-sm-8 col-xs-12">
												<?php echo form_input('t[item]', '', [
														'placeholder' => '', 
														'class' => 'form-control lookupbox7-input-search'
													]); ?>
											</div>
											<div class="col-sm-4 col-xs-12">
												<?php echo form_button([
														'type' => 'button',
														'content' => '<i class="fa fa-search"></i>',
														'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
													]); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<table id="dt_commission_item" class="datatables table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th></th>
										<th><?php echo lang('label:code') ?></th>
										<th><?php echo lang('label:name') ?></th>
									</tr>
								</thead>        
								<tbody>
								</tbody>
							</table>
						</div>
						<div id="commission-patient" class="tab-pane ">
							<div class="row mb10">
								<div class="col-sm-12 col-xs-12">
									<div class="col-sm-offset-1 col-sm-10 col-xs-12">
										<?php echo form_label(lang('label:patient_type'), 'commission_patient', ['class' => 'control-label col-md-3']) ?>
										<div class="col-sm-6 col-xs-12">
											<?php echo form_dropdown('t[commission_patient]', $patient_dropdown, '',[
													'id' => 'commission_patient',
													'placeholder' => '', 
													'class' => 'form-control'
												]); ?>
										</div>
										<div class="col-sm-3 col-xs-12">
											<?php echo form_button([
													'type' => 'button',
													'data-from' => '#commission_patient',
													'data-aim' => '#dt_commission_patient',
													'data-field' => 'Komisi',
													'id' => 'btn-add-commission-patient',
													'content' => '<i class="fa fa-plus"> '.lang('buttons:add').'</i>',
													'class' => 'btn btn-block btn-primary cb-add-commission'
												]); ?>
										</div>
									</div>
								</div>
							</div>
							<table id="dt_commission_patient" class="datatables table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th></th>
										<th><?php echo lang('label:name') ?></th>
										<th><?php echo lang('label:commission') ?> (%)</th>
									</tr>
								</thead>        
								<tbody>
								</tbody>
							</table>
						</div>
						<div id="commission-tht" class="tab-pane">
							<div class="row mb10">
								<div class="col-sm-12 col-xs-12">
									<div class="col-sm-offset-1 col-sm-10 col-xs-12">
										<?php echo form_label(lang('label:patient_type'), 'commission_tht', ['class' => 'control-label col-md-3']) ?>
										<div class="col-sm-6 col-xs-12">
											<?php echo form_dropdown('t[commission_tht]', $patient_dropdown, '',[
													'id' => 'commission_tht',
													'placeholder' => '', 
													'class' => 'form-control'
												]); ?>
										</div>
										<div class="col-sm-3 col-xs-12">
											<?php echo form_button([
													'type' => 'button',
													'data-from' => '#commission_tht',
													'data-aim' => '#dt_commission_tht',
													'data-field' => 'NilaiTHT',
													'id' => 'btn-add-commission-tht',
													'content' => '<i class="fa fa-plus"> '.lang('buttons:add').'</i>',
													'class' => 'btn btn-block btn-primary cb-add-commission'
												]); ?>
										</div>
									</div>
								</div>
							</div>
							<table id="dt_commission_tht" class="datatables table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th></th>
										<th><?php echo lang('label:name') ?></th>
										<th><?php echo lang('label:commission') ?> (%)</th>
									</tr>
								</thead>        
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group text-right">
							<button id="js-btn-submit" type="button" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
							<button class="btn btn-warning" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';">New</button> 
							<button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';">Close</button> 
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close() ?>
<script src="<?php echo site_url("themes/bracketadmin/vendor/lookupbox7/jquery.lookupbox7.js"); ?>"></script>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("#form_drug_commission");
		
		_form.find('input[name="t[item]"]').lookupbox7({
				remote: '<?php echo site_url('vendor/item/lookup_collection'); ?>',
				title: 'Daftar Pilihan Item',
				columns: [
						{data: "Kode_Barang", orderable: true, searchable: true, className: 'text-center', width: "150px"},
						{data: "Nama_Barang", orderable: true, searchable: true}
					],
				headings: ['Kode','Nama Item'],
				order: [[1, 'asc']],
				placeholder: 'Ketik cari item',
				btnApplyText: 'Terapkan Pilihan',
				btnCloseText: 'Tutup',
				onSelected: function(v){
						_form.find('input[name="t[item]"]').val('').focus();
						var check = $("#dt_commission_item").DataTable().rows( function ( idx, row, node ) {
								return v.Barang_ID === row.Barang_ID ?	true : false;
							}).data();
						if ( check.any() )
						{	
							message = "Item: <b>"+ v.Nama_Barang +"</b> sudah ada pada Tabel.";
							$.alert_warning( message );
							return false;
						}
						
						$('#dt_commission_item').DataTable().row.add({
							Barang_ID : v.Barang_ID,
							Kode_Barang : v.Kode_Barang,
							Nama_Barang : v.Nama_Barang,
						}).draw();					
					}
			});
		
		$('.cb-add-commission').on('click', function(){
				var data_from = $(this).data('from');
				var data_aim = $(this).data('aim');
				var data_field = $(this).data('field');
				
				var check = $(data_aim).DataTable().rows( function ( idx, row, node ) {
						return $(data_from).val() === row.JenisPasienID ?	true : false;
					}).data();
				if ( check.any() )
				{	
					message = "Tipe Pasien: <b>"+ $(data_from +' option:selected').html() +"</b> sudah ada pada Tabel.";
					$.alert_warning( message );
					return false;
				}				
				var _row = {
					JenisPasienID : $(data_from).val(),
					JenisKerjasama : $(data_from +' option:selected').html(),
				}
				_row[data_field] = 0;				
				$(data_aim).DataTable().row.add( _row ).draw();			
			});
				
		$.fn.extend({
				dTInitItem: function(){
						var _this = this;
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						_datatable = _this.DataTable( {
								dom: 'tip',
								processing: false,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								responsive: true,
								data: <?php print_r(json_encode(@$commission_item, JSON_NUMERIC_CHECK)); ?>,
								columns: [
										{ 
											data: "Barang_ID", 
											className: "actions text-center", 
											render: function( val, type, row ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-xs btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "Kode_Barang", className:'text-center' },
										{ data: "Nama_Barang",},
									],
								createdRow: function ( row, data, index ){																							
										$( row ).on( "click", "a.btn-remove", function(e){
											e.preventDefault();												
											var elem = $( e.target );
											
											if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
												_this.DataTable().row( row ).remove().draw();
											}
										});
									}
							} );
							
						return _this
					},
				dTInitPatient: function(){
						var _this = this;
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						_datatable = _this.DataTable( {
								dom: 'tip',
								processing: false,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								responsive: true,
								data: <?php print_r(json_encode(@$commission_patient, JSON_NUMERIC_CHECK)); ?>,
								columns: [
										{ 
											data: "JenisPasienID", 
											className: "actions text-center", 
											render: function( val, type, row ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-xs btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "JenisKerjasama" },
										{ data: "Komisi", className:'text-center' },
									],
								createdRow: function ( row, data, index ){																							
										$( row ).on( "click", "a.btn-remove", function(e){
											e.preventDefault();												
											var elem = $( e.target );
											
											if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
												_this.DataTable().row( row ).remove().draw();
											}
										});
										
										$( row ).on( "click", "td", function(e){
											e.preventDefault();												
											if( $(this).index() == 2 ){
												var _input = $( "<input type=\"number\" value=\"" + parseFloat(data.Komisi) + "\" style=\"width:100%\" min=\"1\"  class=\"form-control\">" );										
												$(this).empty().append( _input );
												_input.trigger( "focus" );
												_input.on( "blur", function(e){
													e.preventDefault();									
													try{
														data.Komisi = this.value || 1;
														_this.DataTable().row( row ).data( data );															
													} catch(ex){}
												});
											}
										});
									}
							} );
							
						return _this
					},
				dTInitTHT: function(){
						var _this = this;
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						_datatable = _this.DataTable( {
								dom: 'tip',
								processing: false,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								responsive: true,
								data: <?php print_r(json_encode(@$commission_tht, JSON_NUMERIC_CHECK)); ?>,
								columns: [
										{ 
											data: "JenisPasienID", 
											className: "actions text-center", 
											render: function( val, type, row ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-xs btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "JenisKerjasama" },
										{ data: "NilaiTHT", className:'text-center' },
									],
								createdRow: function ( row, data, index ){																							
										$( row ).on( "click", "a.btn-remove", function(e){
											e.preventDefault();												
											var elem = $( e.target );										
											if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
												_this.DataTable().row( row ).remove().draw();
											}
										});
										
										$( row ).on( "click", "td", function(e){
											e.preventDefault();												
											if( $(this).index() == 2 ){
												var _input = $( "<input type=\"number\" value=\"" + parseFloat(data.NilaiTHT) + "\" style=\"width:100%\" min=\"1\"  class=\"form-control\">" );										
												$(this).empty().append( _input );
												_input.trigger( "focus" );
												_input.on( "blur", function(e){
													e.preventDefault();									
													try{
														data.NilaiTHT = this.value || 1;
														_this.DataTable().row( row ).data( data );															
													} catch(ex){}
												});
											}
										});
									}
							} );
						return _this
					},
			});
				
		$( document ).ready(function(e) {			
				$('#dt_commission_item').dTInitItem();
				$('#dt_commission_patient').dTInitPatient();
				$('#dt_commission_tht').dTInitTHT();
						
				_form.find("button#js-btn-submit").on("click", function(e){
					e.preventDefault();						
					var data_post = {f:{},commission_item:{},commission_patient:{},commission_tht:{}};
						data_post['f'] = {
								DokterID : <?php echo $item->Supplier_ID ?>,
							}							
					
					$.each($('#dt_commission_item').DataTable().rows().data(), function(i, v){
						data_post['commission_item'][i] = {
							DokterID : <?php echo $item->Supplier_ID ?>,
							Barang_ID : v.Barang_ID,
						};
					});
					$.each($('#dt_commission_patient').DataTable().rows().data(), function(i, v){
						data_post['commission_patient'][i] = {
							DokterID : <?php echo $item->Supplier_ID ?>,
							JenisPasienID : v.JenisPasienID,
							Komisi : v.Komisi
						};
					});
					$.each($('#dt_commission_tht').DataTable().rows().data(), function(i, v){
						data_post['commission_tht'][i] = {
							DokterID : <?php echo $item->Supplier_ID ?>,
							JenisPasienID : v.JenisPasienID,
							NilaiTHT : v.NilaiTHT
						};
					});
						
					$.post( _form.prop("action"), data_post, function( response, status, xhr ){
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}						
						$.alert_success( response.message );
						setTimeout(function(){	
							document.location.href = "<?php echo base_url($nameroutes); ?>";
							}, 300 );
					});
				});
			});
	})( jQuery );
//]]>
</script>
