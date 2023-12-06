<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


?>

<?php echo form_open( current_url(), array("name" => "form_inquiry") ); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo 'Kelola Retur Mutasi' ?></h3>
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
						<input type="text" id="tanggal" name="f[tanggal]" value="<?php echo @$item->Tanggal ?>" placeholder="" class="form-control datepicker">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Asal Retur</label>
					<div class="col-lg-9">
						<select id="Lokasi_Asal" class="form-control">
							<option value="">-- Pilih --</option>
							<?php if (!empty($option_section_from)): foreach($option_section_from as $row):?>
							<option value="<?php echo $row->SectionID ?>" data-lokasiid="<?php echo $row->Lokasi_ID ?>" <?php echo $row->Lokasi_ID == @$item->Lokasi_Asal ? "selected" : "" ?>> <?php echo $row->SectionName ?></option>
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>

			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label">Tujuan Retur</label>
					<div class="col-lg-9">
						<input type="hidden" id="SectionTujuan" name="f[SectionTujuan]" value="<?php echo @$section->SectionID ?>" class="inquiry">
						<input type="hidden" id="Lokasi_Tujuan" name="f[Lokasi_Tujuan]" value="<?php echo @$section->Lokasi_ID ?>" class="inquiry">
						<input type="text" id="SectionName" name="f[SectionName]" value="<?php echo @$section->SectionName ?>" placeholder="" class="form-control inquiry" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Keterangan</label>
					<div class="col-lg-9">
						<textarea id="Keterangan" name="f[Keterangan]" class="form-control inquiry"><?php echo @$item->Keterangan ?></textarea>
					</div>
				</div>
			</div>
		</div>
		<?php echo modules::run("inquiry/inquiries/detail_mutation_returns/index", @$item ) ?>
		<div class="form-group">
			<div class="col-lg-12 text-right">
				<button type="reset" class="btn btn-warning"><i class="fa fa-refresh" aria-hidden="true"></i> <?php echo lang( 'buttons:reset' ) ?></button>
				<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo lang( 'buttons:submit' ) ?></button>
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
							data_post['retur_mutasi'] = {};
							data_post['retur_mutasi_detail'] = {};
							
						var	d = new Date();
						var retur_mutasi = {
								No_Bukti : $("#NoBukti").val(),
								Tgl_Mutasi : "<?php echo date("Y-m-d") ?>",
								Lokasi_Asal : $("#Lokasi_Asal").find('option:selected').data("lokasiid"),
								Lokasi_Tujuan : $("#Lokasi_Tujuan").val(),
								User_ID : <?php echo $user->User_ID?>,
								Tgl_Update : "<?php echo date("Y-m-d") ?>",
								Status_Batal : 0,
								Posting_KG : 0,
								Posting_GL : 0,
								Approve : 1
							}
						
						data_post['retur_mutasi'] = retur_mutasi;						
						
						var dt_details = $( "#dt_details" ).DataTable().rows().data();					
						dt_details.each(function (value, index) {
							var detail = {
								No_Bukti : $("#NoBukti").val(),
								Barang_ID : value.Barang_ID,
								Kode_Satuan : value.Satuan_Stok,
								Qty_Stok : 0,
								QtyAmprah : 0,
								Qty : value.Qty,
								Harga : value.Harga_Jual,
								JenisBarangID : 0,
								HRataRata : value.HRataRata,
							}
							
							data_post['retur_mutasi_detail'][index] = detail;
						});
						
						$.post('<?php echo @$create_url ?>', data_post, function( response, status, xhr ){
							
							var response = $.parseJSON(response);
	
							if( "error" == response.status ){
								$.alert_error(response.message);
								return false
							}
							
							$.alert_success( response.message );
							
							setTimeout(function(){
														
								document.location.href = "<?php echo base_url("inquiry/mutation-return-list/{$type}"); ?>";
								
								}, 300 );
							
						})	
					} catch (e){ console.log(e);}
				});
			
			});

	})( jQuery );
//]]>
</script>