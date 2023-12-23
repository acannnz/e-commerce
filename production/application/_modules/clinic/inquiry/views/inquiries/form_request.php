<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<?php echo form_open( current_url(), array("name" => "form_inquiry") ); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo 'Kelola Amprah' ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('inquiry:evidence_number_label') ?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" readonly>
					</div>
				</div>
		
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo lang('inquiry:date_label') ?></label>
					<div class="col-lg-9">
						<input type="text" id="NRM" name="f[NRM]" value="<?php echo @$item->Tanggal ?>" placeholder="" class="form-control" maxlength="8" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Dari</label>
					<div class="col-lg-9">
						<select id="SectionAsal" name="f[SectionAsal]" class="form-control" disabled="disabled">
							<?php if(!empty($option_section_from)): foreach($option_section_from as $row):?>
							<option value="<?php echo $row->SectionID ?>" <?php echo $row->SectionID == @$item->SectionAsal ? "selected" : NULL  ?>><?php echo $row->SectionName ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Kepada</label>
					<div class="col-lg-9">
						<select id="SectionTujuan" name="f[SectionTujuan]" class="form-control">
							<?php if(!empty($option_section_pharmacy)): foreach($option_section_pharmacy as $row):?>
							<option value="<?php echo $row->SectionID ?>" <?php echo $row->SectionID == @$item->SectionTujuan ? "selected" : NULL  ?>><?php echo $row->SectionName ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Keterangan</label>
					<div class="col-lg-9">
						<textarea id="Keterangan" name="f[Keterangan]" class="form-control"><?php echo @$item->Keterangan ?></textarea>
					</div>
				</div>
			</div>
			
			<div class="col-md-6">
		
				<div class="form-group">
					<div class="col-md-3">
					</div>
					<div class="col-md-3">
						<div class="checkbox">
							<input type="checkbox" id="Disetujui" name="f[Disetujui]" value="1" <?php echo @$item->Disetujui == 1 ? "Checked" : NULL ?> class="" disabled="disabled"><label for="Disetujui">Disetujui</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Tanggal</label>
					<div class="col-lg-6">
						<input type="text" id="DisetujuiTgl"  value="<?php echo @$resep->DisetujuiTgl ?>" placeholder="" class="form-control" disabled="disabled">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Oleh</label>
					<div class="col-lg-6">
						<input type="text" id="DisetujuiUseID"  value="<?php echo @$resep->DisetujuiUseID ?>" placeholder="" class="form-control" disabled="disabled">
					</div>
				</div>        
			</div>
		</div>
		<?php echo modules::run("inquiry/inquiries/details/index", @$item ) ?>
		<div class="form-group">
			<div class="col-lg-12 text-right">
				<?php if(!@$is_edit): ?>
				<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
				<?php endif; ?>
				<?php if (@$item->Batal == 0 && @$item->Realisasi == 0 && @$is_edit): ?>
				<a href="<?php echo @$cancel_url ?>" data-toggle="ajax-modal" class="btn btn-danger <?php echo @$is_edit ? NULL : 'disabled' ?>"><?php echo lang('buttons:cancel')?></a> 
				<?php endif; ?>
				<button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
				<?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
	
		$( document ).ready(function(e) {			
												
				$("form[name=\"form_inquiry\"]").on("submit", function(e){
					e.preventDefault();	
					
					try{
						var data_post = { };
							data_post['amprahan'] = {};
							data_post['amprahan_detail'] = {};
							
						var	d = new Date();
						var amprahan = {
								NoBukti : $("#NoBukti").val(),
								Tanggal : "<?php echo date("Y-m-d H:i:s") ?>",
								SectionAsal : $("#SectionAsal").val(),
								SectionTujuan : $("#SectionTujuan").val(),
								Disetujui : 0,
								DisetujuiTgl : '',
								DisetujuiUserID : '',
								Keterangan : $("#Keterangan").val(),
								Batal : 0,
								Realisasi : 0,
								Automatis : 0,
							}
						
						data_post['amprahan'] = amprahan;						
						

						var dt_details = $( "#dt_details" ).DataTable().rows().data();					
						dt_details.each(function (value, index) {
							var detail = {
								NoBukti : $("#NoBukti").val(),
								Barang_ID : value.Barang_ID,
								Satuan : value.Satuan_Stok,
								Qty : value.Qty_Amprah,
								StatusBarang : value.StatusBarang,
								Realisasi : 0,
								QtyStok : value.Qty_Stok,
								QtyRealisasiPertama : 0,
							}
							
							data_post['amprahan_detail'][index] = detail;
						});
						
						$.post('<?php echo @$create_url ?>', data_post, function( response, status, xhr ){
							
							var response = $.parseJSON(response);
	
							if( "error" == response.status ){
								$.alert_error(response.status);
								return false
							}
							
							$.alert_success( response.message );
							
							var NoBukti = response.NoBukti;
							
							setTimeout(function(){
														
								document.location.href = "<?php echo base_url("inquiry/request-list/{$type}"); ?>";
								
								}, 300);
							
						})	
					} catch (e){ console.log(e);}
				});

			});

	})( jQuery );
//]]>
</script>