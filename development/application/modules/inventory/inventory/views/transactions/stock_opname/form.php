<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php echo form_open( $form_action, array("name" => "form_stock_opname") ); ?>
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
                <h3 class="panel-title"><?php echo lang('heading:stock_opname'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
				<div class="row form-group">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo lang('label:no_evidence') ?> <span class="text-danger">*</span></label>
							<div class="col-md-4">
								<input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->No_Bukti ?>" placeholder="" class="form-control" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo lang('label:date')?></label>
							<div class="col-md-4">
								<input type="text" id="Tgl_Opname" name="f[Tgl_Opname]" value="<?php echo @$item->Tgl_Opname ?>" placeholder="" class="form-control" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo lang('label:warehouse')?></label>
							<div class="col-md-9">
							   <select id="Lokasi_ID" name="f[Lokasi_ID]" class="form-control" <?php echo ( @$is_edit || @$item->Posted == 1) ? 'disabled'  : NULL ?>>
									<option value=""><?php echo lang('global:select-all') ?></option>
									<?php if (!empty($dropdown_section)): foreach($dropdown_section as $key => $val):?>
									<option value="<?php echo $key ?>" <?php echo $key == @$item->Lokasi_ID ? "selected" : "" ?>> <?php echo $val ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo lang('label:item_type_group')?></label>
							<div class="col-md-9">
								<select id="KelompokJenis" name="f[KelompokJenis]" class="form-control" <?php echo ( @$is_edit || @$item->Posted == 1) ? 'disabled'  : NULL ?>>
									<option value=""><?php echo lang('global:select-all') ?></option>
									<?php if (!empty($dropdown_type_group)): foreach($dropdown_type_group as $key => $val):?>
									<option value="<?php echo $key ?>" <?php echo $key == @$item->KelompokJenis ? "selected" : "" ?>> <?php echo $val ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-md-offset-3 col-md-9">
								<h4 class="text-danger">
									<?php echo ( @$item->Posted == 1 ) ? lang('message:stock_opname_procced') : lang('message:stock_opname_not_procced') ?>
								</h4>
							</div>
						</div>
					</div>
				</div>
				<hr />
				<div class="form-group">
					<?php echo @$view_detail_opname  ?>
				</div>
				<hr />
				<?php if (@$item->Posted == 0): ?>
				<div class="form-group">
					<div class="col-md-12 text-right">
						<button type="button" value="1" class="btn btn-danger btn-submit"><?php echo lang( 'process:stock_opname' ) ?></button>
						<button type="button" value="0" class="btn btn-primary btn-submit"><?php echo lang( 'buttons:submit' ) ?></button>
						<button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
					</div>
				</div>
				<?php endif;?>
			</div>
        </div>
    </div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("form[name=\"form_stock_opname\"]");
		var _btn_submit = _form.find("button.btn-submit");
		
		$( document ).ready(function(e) {			
												
				_btn_submit.on("click", function(e){
					e.preventDefault();	
					
					try{
						var data_post = { };
							data_post['header'] = {
								"Lokasi_ID" : $("#Lokasi_ID").val(),
								"KelompokJenis" : $("#KelompokJenis").val()
							};
							data_post['additional'] = {
								'process_stock_opname' : $(this).val(),
								'section_name' : $("#Lokasi_ID").find("option:selected").html()
							};						
							data_post['details'] = {};						
						
						var dt_details = $( "#dt_detail_opname" ).DataTable().rows().data();	
						dt_details.each(function (value, index) {
							var detail = {
								"Barang_ID"	: value.Barang_ID,
								"Kode_Satuan" : value.Kode_Satuan,
								"Stock_Akhir" : value.Stock_Akhir,
								"Qty_Opname" : value.Qty_Opname,
								"Harga_Rata" : value.Harga_Rata,
								"Keterangan" : value.Keterangan,
								"JenisBarangID" : value.JenisBarangID,
								//"Tgl_Expired" : value.Tgl_Expired,
							}
							
							data_post['details'][index] = detail;
						});
						
						$.post( _form.prop('action'), data_post, function( response, status, xhr ){
							
							if( "error" == response.status ){
								$.alert_error( response.message );
								return false
							}
							
							$.alert_success( response.message );
							
							var id = response.id;
							
							setTimeout(function(){
								
									if (response.posted == 1)						
									{
										document.location.href = "<?php echo current_url() ?>/view/"+ id;
									} else {
										document.location.href = "<?php echo current_url() ?>/update/"+ id;
									}
								
								}, 300 );
							
						})	
					} catch (e){ console.log(e);}
				});

			});

	})( jQuery );
//]]>
</script>