<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
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
                <h3 class="panel-title"><?php echo lang('heading:amprahan'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
			<div class="row">
	            <div class="col-md-6">
            		<div class="form-group">
						<?php echo form_label(lang('label:date').' *', 'Tanggal', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-9">
							<?php echo form_input('f[Tanggal]', set_value('f[Tanggal]', substr(@$item->Tanggal, 0, 10), TRUE), [
									'id' => 'Tanggal', 
									'placeholder' => '', 
									'required' => 'required',
									'class' => 'form-control datepicker'
								]); ?>
						</div>
                    </div>
                    <div class="form-group">
                        <?php echo form_label(lang('label:no_evidence').' *', 'NoBukti', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-9">
							<?php echo form_input('f[NoBukti]', set_value('f[NoBukti]', @$item->NoBukti, TRUE), [
									'id' => 'NoBukti', 
									'placeholder' => '',
									'readonly' => 'readonly', 
									'class' => 'form-control'
								]); ?>
                    	</div>
					</div>
					<div class="form-group">
                    	<?php echo form_label(lang('label:section_from').' *', 'SectionID', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-9">
							<select class="form-control" name="f[SectionAsal]" id="SectionAsal">
								<option value=""><?php echo lang('global:select');?></option>
								<?php if(!empty($dropdown_section_from)): foreach($dropdown_section_from as $section_id => $section_name): ?>
								<option value="<?php echo $section_id ?>" <?php echo $section_id == @$item->SectionAsal ? 'selected' : ''; ?>><?php echo $section_name ?></option>
								<?php endforeach; endif; ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<?php echo form_label(lang('label:section_to').' *', 'input_section_id', ['class' => 'control-label col-md-3']) ?>
						<div class="col-md-9">
							<select class="form-control" name="f[SectionTujuan]" id="SectionTujuan">
								<?php if( !empty($dropdown_section_to)): foreach($dropdown_section_to as $section_id => $section_name): ?>
								<option value="<?php echo $section_id ?>" <?php echo $section_id == @$item->SectionTujuan ? 'selected' : ''; ?>><?php echo $section_name ?></option>
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
					<div class="form-group">
						<div class="col-md-offset-3 col-md-3">
							<div class="">
								<label for="Disetujui">
									<input type="checkbox" id="Disetujui" name="f[Disetujui]" value="1" <?php echo @$item->Disetujui == 1 ? "Checked" : NULL ?> class="" disabled="disabled">
									 Disetujui
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Tanggal</label>
						<div class="col-lg-9">
							<input type="text" id="DisetujuiTgl"  value="<?php echo @$resep->DisetujuiTgl ?>" placeholder="" class="form-control" disabled="disabled">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Oleh</label>
						<div class="col-lg-9">
							<input type="text" id="DisetujuiUseID"  value="<?php echo @$resep->DisetujuiUseID ?>" placeholder="" class="form-control" disabled="disabled">
						</div>
					</div>   
					<?php if (@$item->Batal): ?>
					<div class="form-group">
						<div class="col-md-offset-3 col-md-9">
							<h4 class="text-danger"><?php echo lang('message:cancel_data')?></h4>
						</div>
					</div>
					<?php endif; ?>
					<?php if (@$item->Realisasi): ?>
					<div class="form-group">
						<div class="col-md-offset-3 col-md-9">
							<h4 class="text-danger"><?php echo lang('message:realization_data')?></h4>
						</div>
					</div>
					<?php endif; ?>
				</div>
            </div>
			<hr/>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<table id="dt_trans_amprahan" class="table table-bordered table-hover" width="100%" cellspacing="0">
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
					<?php if (@$item->Batal == 0 && @$item->Realisasi == 0): ?>
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
						<?php if (@$item->Batal == 0 && @$item->Realisasi == 0): ?>
						<button id="btn-submit" type="button" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
						<button id="print" disabled="disabled" type="submit" class="btn btn-success"><?php echo lang( 'buttons:print' ) ?></button>
						<a href="javascript:;" data-action-url="<?php echo @$cancel_url ?>" data-act="ajax-modal" data-title="<?php echo lang('buttons:cancel')?>" class="btn btn-danger <?php echo @$is_edit ? NULL : 'disabled' ?>"><?php echo lang('buttons:cancel')?></a> 
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
		var _form_date = _form.find("input[name=\"f[Tanggal]\"]");
		var _form_evidence_number = _form.find("input[name=\"f[NoBukti]\"]");
		var _form_section_from = _form.find("select[name=\"f[SectionAsal]\"]");
		var _form_section_to = _form.find("select[name=\"f[SectionTujuan]\"]");
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
		
		var form_actions = {
				init: function(){
						_form_section_from.on('change', function(){
							form_actions.gen_evidence_number( _form_date.val(), $(this).val() );
						});
					
					},
				gen_evidence_number: function( date, section_id ){
						params = {
								'date' : date,
								'section_id' : section_id
							};
						
						$.get('<?php echo @$gen_evidence_number_url ?>', params, function( response, status, xhr ){
																			
							if ( response.status != 'error' )
							{
								_form_evidence_number.val( response.evidence_number )
							}
						
						})	
					},
			};
		
		$.fn.extend({
				dt_trans_amprahan: function(){
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
											data: "Kode_Barang", 
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
										{ data: "Satuan"},
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
								<?php if (@$item->Batal == 0 && @$item->Realisasi == 0): ?>
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
							
						$( "#dt_trans_amprahan_length select, #dt_trans_amprahan_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
				form_actions.init();
			
            	$( "#dt_trans_amprahan" ).dt_trans_amprahan();
				
								
				$("#btn-submit").on("click", function(e){
					e.preventDefault();	
					
					var data_post = {};
						data_post['header'] = {
								'Tanggal' : _form_date.val(),
								'SectionAsal' : _form_section_from.val(),
								'SectionTujuan' : _form_section_to.val(),
								'Keterangan' : _form_description.val(),
							};
						data_post['additional'] = {
								'section_from_name' : _form_section_from.find('option:selected').html(),
								'section_to_name' : _form_section_to.find('option:selected').html(),
							};	
						data_post['details'] = {};
					
					var table_data = $( "#dt_trans_amprahan" ).DataTable().rows().data();
					table_data.each(function (value, index) {
						var detail = {
								StatusBarang : value.Status_Barang,
								Qty : value.Qty,
								Barang_ID : value.Barang_ID,
								Satuan : value.Satuan,
						}
						
						data_post['details'][index] = detail;
					});
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){

						if( "error" == response.status ){
							alert( response.message );
							return false
						}
						
						$.alert_success( response.message );
						
						var id = response.id;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("{$nameroutes}/update"); ?>/"+ id;
							
							}, 300 );
						
					});
				});

			});

	})( jQuery );
//]]>
</script>
