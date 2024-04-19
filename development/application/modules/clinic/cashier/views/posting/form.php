<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_amprahan', 
		'name' => 'form_amprahan', 
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
                                	<a href="<?php echo site_url("{$nameroutes}/create") ?>"><i class="fa fa-plus"></i> <?php echo lang('action:add') ?></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>              
                <h3 class="panel-title"><?php echo ( ! @$is_edit) ? lang('heading:mutation_returns') : lang('heading:mutation_return_view'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
			<div class="row">
	            <div class="col-md-6">
            		<div class="form-group">
						<?php echo form_label(lang('label:date').' *', 'Tgl_Mutasi', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-9">
							<?php echo form_input('f[Tgl_Mutasi]', set_value('f[Tgl_Mutasi]', substr(@$item->Tgl_Mutasi, 0, 10), TRUE), [
									'id' => 'Tgl_Mutasi', 
									'placeholder' => '', 
									'readonly' => 'readonly',
									'class' => 'form-control'
								]); ?>
						</div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label(lang('label:no_evidence').' *', 'No_Bukti', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-9">
							<?php echo form_input('f[No_Bukti]', set_value('f[No_Bukti]', @$item->No_Bukti, TRUE), [
									'id' => 'No_Bukti', 
									'placeholder' => '',
									'readonly' => 'readonly', 
									'class' => 'form-control'
								]); ?>
                    	</div>
					</div>
					<div class="form-group">
                    	<?php echo form_label(lang('label:section_from').' *', 'SectionID', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-9">
							<select class="form-control" name="f[Lokasi_Asal]" id="Lokasi_Asal">
								<option value=""><?php echo lang('global:select');?></option>
								<?php if(!empty($dropdown_section_from)): foreach($dropdown_section_from as $section_id => $section_name): ?>
								<option value="<?php echo $section_id ?>" <?php echo $section_id == @$item->Lokasi_Asal ? 'selected' : ''; ?>><?php echo $section_name ?></option>
								<?php endforeach; endif; ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<?php echo form_label(lang('label:section_to').' *', 'input_section_id', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-9">
							<select class="form-control" name="f[Lokasi_Tujuan]" id="location_to">
								<?php if( !empty($dropdown_section_to)): foreach($dropdown_section_to as $section_id => $section_name): ?>
								<option value="<?php echo $section_id ?>" <?php echo $section_id == @$item->Lokasi_Tujuan ? 'selected' : ''; ?>><?php echo $section_name ?></option>
								<?php endforeach; endif; ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<?php echo form_label(lang('label:description').' *', 'input_keterangan', ['class' => 'control-label col-md-3']) ?>
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
				</div>
            </div>
			<hr/>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<table id="dt_trans_mutation_return" class="table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th></th>
									<th><?php echo lang('label:item_code') ?></th>
									<th><?php echo lang('label:item_name') ?></th>
									<th><?php echo lang('label:item_konversion') ?></th>
									<th><?php echo lang('label:item_unit') ?></th>
									<th><?php echo lang('label:item_qty') ?></th>
								</tr>
							</thead>        
							<tbody>
							</tbody>
						</table>
					</div>
					<?php if ( ! @$is_edit): ?>
					<div class="form-group">
						<a href="javascript:;" data-action-url="<?php echo @$item_lookup ?>" data-act="ajax-modal" data-title="<?php echo lang('heading:item_list')?>" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Barang</b></a>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<hr/>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<?php if ( ! @$is_edit): ?>
						<button id="btn-submit" type="button" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
						<button id="print" disabled="disabled" type="submit" class="btn btn-success"><?php echo lang( 'buttons:print' ) ?></button>
						<?php endif; ?>
					</div>
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
	
		var _form = $( "form[name=\"form_amprahan\"]" );
		var _form_date = _form.find("input[name=\"f[Tgl_Mutasi]\"]");
		var _form_evidence_number = _form.find("input[name=\"f[No_Bukti]\"]");
		var _form_section_from = _form.find("select[name=\"f[Lokasi_Asal]\"]");
		var _form_section_to = _form.find("select[name=\"f[Lokasi_Tujuan]\"]");
		var _form_description = _form.find("textarea[name=\"f[Keterangan]\"]");
		
		var _datatable;		
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
						switch( this.index() ){
							
							case 5:
							
								var _input = $( "<input type=\"number\" value=\"" + parseFloat(data.Qty || 1) + "\" style=\"width:100%\"  class=\"form-control Qty\">" );
								var discount;
								var total;
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function(e){
										e.preventDefault();
      
										try{
											data.Qty = (this.value > 0) ? this.value : 1;
											_datatable.row( row ).data( data );
											
										} catch(ex){}
									});
							break;
						}
						
					},
				remove: function( params, fn, scope ){
						_datatable.row( scope ).remove().draw(false);						
					},
			};

		
		$.fn.extend({
				dt_trans_mutation_return: function(){
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
								data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK)) ?>,
								columns: [
										{ 
											data: "Barang_ID", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ 
											data: "Kode_Barang", 
											className: "", 
										},
										{ data: "Nama_Barang", className: "" },
										{ data: "Konversi", className: "" },
										{ data: "Kode_Satuan"},
										{ data: "Qty", className: "text-center", },
									
									],
								columnDefs  : [
										{
											"targets": ["Barang_ID"],
											"visible": true,
											"searchable": false
										}
									],
								drawCallback: function( settings ){ $( window ).trigger( "resize" ); },
								<?php if ( ! @$is_edit): ?>
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
								<?php endif; ?>
							} );
							
						$( "#dt_trans_mutation_return_length select, #dt_trans_mutation_return_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {			
            	$( "#dt_trans_mutation_return" ).dt_trans_mutation_return();
				
								
				$("#btn-submit").on("click", function(e){
					e.preventDefault();	
					
					var data_post = {};
						data_post['header'] = {
								'Tgl_Mutasi' : _form_date.val(),
								'Lokasi_Asal' : _form_section_from.val(),
								'Lokasi_Tujuan' : _form_section_to.val(),
								'Keterangan' : _form_description.val(),
							};
						data_post['additional'] = {
								'section_from_name' : _form_section_from.find('option:selected').html(),
								'section_to_name' : _form_section_to.find('option:selected').html(),
							};	
						data_post['details'] = {};
					
					var table_data = $( "#dt_trans_mutation_return" ).DataTable().rows().data();
					table_data.each(function (value, index) {
						var detail = {
								Qty : value.Qty,
								Barang_ID : value.Barang_ID,
								Kode_Satuan : value.Kode_Satuan,
								Harga : value.Harga,
								HRataRata : value.HRataRata
						}
						
						data_post['details'][index] = detail;
					});
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){

						if( "error" == response.status ){
							$.alert_error( response.message );
							return false
						}
						
						$.alert_success( response.message );
						
						var id = response.id;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("{$nameroutes}/view"); ?>/"+ id;
							
							}, 300 );
						
					});
				});

			});

	})( jQuery );
//]]>
</script>
