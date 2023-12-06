<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<?php echo form_open( $form_actions, array("name" => "form_mutations") ); ?>
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
                <h3 class="panel-title"><?php echo (!@$is_edit) ? lang('heading:mutations') : lang('heading:mutation_view'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
				<div class="row form-group">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('label:no_evidence') ?> <span class="text-danger">*</span></label>
							<div class="col-lg-9">
								<input type="text" id="No_Bukti" name="f[No_Bukti]" value="<?php echo @$item->No_Bukti ?>" placeholder="" class="form-control" readonly>
							</div>
						</div>
				
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('label:date') ?> <span class="text-danger">*</span></label>
							<div class="col-lg-9">
								<input type="text" id="Tgl_Mutasi" name="f[Tgl_Mutasi]" value="<?php echo @$item->Tgl_Mutasi ?>" placeholder="" class="form-control datepicker">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('label:from')?></label>
							<div class="col-lg-9">
								<select id="Lokasi_Asal" name="f[Lokasi_Asal]" class="form-control" disabled="disabled">
									<?php if(!empty($dropdown_section_from)): foreach($dropdown_section_from as $k => $v):?>
									<option value="<?php echo $k ?>" <?php echo $k == @$item->Lokasi_Asal ? "selected" : NULL  ?>><?php echo $v ?></option>
									<?php endforeach;endif;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('label:description')?>  <span class="text-danger">*</span></label>
							<div class="col-lg-9">
								<textarea id="KeteranganMutasi" name="f[KeteranganMutasi]" class="form-control amprahan"><?php echo @$item->Keterangan ?></textarea>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('label:no_amprahan')?></label>
							<div class="col-lg-9">
								<div class="input-group">
									<input type="text" id="NoBuktiAmprah" name="f[NoBuktiAmprah]" value="<?php echo @$item->NoAmprahan?>" placeholder="" class="form-control amprahan" maxlength="8">
									<?php if( ! @$is_edit ): ?>
									<span class="input-group-btn">
										<a href="javascript:;" data-action-url="<?php echo @$lookup_amprahan ?>" data-act="ajax-modal" data-title="<?php echo lang('heading:amprahan_list')?>" class="btn btn-default" ><i class="fa fa-search"></i></a>
										<a href="javascript:;" id="amprahan" class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
									</span>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('label:to')?></label>
							<div class="col-lg-9">
								<input type="hidden" id="Lokasi_Tujuan" name="f[Lokasi_Tujuan]" value="<?php echo @$item->Lokasi_Tujuan ?>" class="amprahan">
								<input type="text" id="SectionAsalName" name="f[SectionAsalName]" value="<?php echo @$amprahan->SectionAsalName ?>" class="form-control" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?php echo lang('label:date')?></label>
							<div class="col-lg-9">
								<input type="text" id="Tanggal_Amprah" name="f[Tanggal_Amprah]" value="<?php echo @$amprahan->Tanggal ?>" placeholder="" class="form-control amprahan" readonly>
							</div>
						</div>        
						<div class="form-group">
							<label class="col-lg-3 control-label">Keterangan Amprah</label>
							<div class="col-lg-9">
								<textarea id="KeteranganAmprah" name="f[KeteranganAmprah]" class="form-control amprahan" readonly><?php echo @$amprahan->Keterangan ?></textarea>
							</div>
						</div>
					</div>
				</div>
				
				<?php echo $view_detail_mutation ?>
				
				<div class="form-group">
					<div class="col-lg-12 text-right">
						<?php if( ! @$is_edit ): ?>
						<button name="btn-submit" type="button" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
						<button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
						<?php endif; ?>
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
	
		$( document ).ready(function(e) {			
				var _form = $("form[name=\"form_mutations\"]");								
				var _btn_submit = _form.find("button[name=\"btn-submit\"]");
				
				_btn_submit.on("click", function(e){
					e.preventDefault();	
					
					try{
						var data_post = {};
							data_post['header'] = {
									Tgl_Mutasi : $("#Tgl_Mutasi").val(),
									Lokasi_Asal : $("#Lokasi_Asal").val(),
									Lokasi_Tujuan : $("#Lokasi_Tujuan").val(),
									NoAmprahan : $("#NoBuktiAmprah").val(),
									Keterangan : $("#KeteranganMutasi").val()
								};						
							data_post['additional'] = {
									section_from_name : $("select#Lokasi_Asal").find("option:selected").html(),
									section_to_name : $("#SectionAsalName").val()
								};
							data_post['details'] = {};
						
						var dt_details = $( "#dt_mutation_details" ).DataTable().rows().data();					
						dt_details.each(function (value, index) {
							var detail = {
								Barang_ID	: value.Barang_ID,
								Kode_Satuan : value.Kode_Satuan,
								Qty_Stok : value.Qty_Stok,
								QtyAmprah : value.QtyAmprah,
								Qty : value.Qty,
								Harga : value.Harga || 0,
								JenisBarangID : value.JenisBarangID,
								HRataRata : value.HRataRata,
								MutasiAkun_ID : value.MutasiAkun_ID,
							}
							
							data_post['details'][ index ] = detail;
						});
						
						$.post( _form.prop('action'), data_post, function( response, status, xhr ){
							
							if( "error" == response.status ){
								$.alert_error(response.message);
								return false
							}
							
							$.alert_success( response.message );
							
							var id = response.id;
							
							setTimeout(function(){	
								document.location.href = "<?php echo base_url("{$nameroutes}/update"); ?>/"+ id;
								}, 300 );
							
						})	
					} catch (e){ console.log(e);}
				});

			});

	})( jQuery );
//]]>
</script>