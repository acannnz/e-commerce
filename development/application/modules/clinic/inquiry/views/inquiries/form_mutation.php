<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<?php echo form_open( current_url(), array("name" => "form_inquiry") ); ?>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo 'Kelola Data Mutasi' ?></h3>
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
					<label class="col-lg-3 control-label">Keterangan Mutasi</label>
					<div class="col-lg-9">
						<textarea id="KeteranganMutasi" name="f[KeteranganMutasi]" class="form-control inquiry"><?php echo @$item->Keterangan ?></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-3 control-label">Nomor Amprah</label>
					<div class="col-lg-9">
						<div class="input-group">
						<input type="hidden" id="Lokasi_Asal" name="f[Lokasi_Asal]" value="<?php echo @$item->Lokasi_Asal?>" class="inquiry">
						<input type="hidden" id="Lokasi_Tujuan" name="f[Lokasi_Tujuan]" value="<?php echo @$item->Lokasi_Tujuan?>" class="inquiry">
							<input type="text" id="NoBuktiAmprah" name="f[NoBuktiAmprah]" value="<?php echo @$item->NoBuktiAmprah ?>" placeholder="" class="form-control inquiry" maxlength="8">
							<span class="input-group-btn">
								<a href="<?php echo @$lookup_inquiry ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
								<a href="javascript:;" id="inquiry" class="btn btn-default btn-clear" ><i class="fa fa-times"></i></a>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Section Asal</label>
					<div class="col-lg-9">
						<input type="hidden" id="SectionAsal" name="f[SectionAsal]" value="<?php echo @$inquiry->SectionAsal ?>" class="inquiry">
						<input type="text" id="SectionAsalName" name="f[SectionAsalName]" value="<?php echo @$inquiry->SectionAsalName ?>" placeholder="" class="form-control inquiry" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Tanggal Amprah</label>
					<div class="col-lg-9">
						<input type="text" id="TanggalAmprah" name="f[tanggal_amprah]" value="<?php echo @$inquiry->Tanggal ?>" placeholder="" class="form-control inquiry" readonly>
					</div>
				</div>        
				<div class="form-group">
					<label class="col-lg-3 control-label">Keterangan Amprah</label>
					<div class="col-lg-9">
						<textarea id="KeteranganAmprah" name="f[KeteranganAmprah]" class="form-control inquiry" readonly><?php echo @$inquiry->Keterangan ?></textarea>
					</div>
				</div>
			</div>
		</div>
		<?php echo modules::run("inquiry/inquiries/detail_mutations/index", @$item ) ?>
		<div class="form-group">
			<div class="col-lg-12 text-right">
				<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
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
				var _form = $("form[name=\"form_inquiry\"]");		
				$("#inquiry").on("click", function(){
					_form.find(".inquiry").val('');					
				});				
				$("form[name=\"form_inquiry\"]").on("submit", function(e){
					e.preventDefault();	
					
					try{
						var data_post = { };
							data_post['mutasi'] = {};
							data_post['mutasi_detail'] = {};
							
						var mutasi = {
								No_Bukti : $("#NoBukti").val(),
								Tgl_Mutasi : "<?php echo date("Y-m-d") ?>",
								Lokasi_Asal : $("#Lokasi_Asal").val(),
								Lokasi_Tujuan : $("#Lokasi_Tujuan").val(),
								User_ID : <?php echo $user->User_ID?>,
								Tgl_Update : "<?php echo date("Y-m-d") ?>",
								Status_Batal : 0,
								Posting_KG : 0,
								Posting_GL : 0,
								Posting_Unit : 0,
								NoAmprahan : $("#NoBuktiAmprah").val(),
								JamMutasi : "<?php echo date("Y-m-d H:i:s") ?> ",
								Approve : 1
							}
						
						data_post['mutasi'] = mutasi;						
						

						var dt_details = $( "#dt_details" ).DataTable().rows().data();					
						dt_details.each(function (value, index) {
							var detail = {
								No_Bukti : $("#NoBukti").val(),
								Barang_ID : value.Barang_ID,
								Kode_Satuan : value.Kode_Satuan,
								Qty_Stok : value.Qty_Stok,
								QtyAmprah : value.QtyAmprah,
								Qty : value.Qty,
								Harga : mask_number.currency_remove(value.Harga),
								JenisBarangID : 0,
								HRataRata : value.HRataRata,
								MutasiAkun_ID : value.MutasiAkun_ID || "",
							}
							
							data_post['mutasi_detail'][index] = detail;
						});
						
						$.post('<?php echo @$create_url ?>', data_post, function( response, status, xhr ){
							
							var response = $.parseJSON(response);
	
							if( "error" == response.status ){
								$.alert_error(response.message);
								return false
							}
							
							$.alert_success( response.message );
							
							setTimeout(function(){
														
								document.location.href = "<?php echo base_url("inquiry/mutation-list/"); ?>" + response.type;
								
								}, 300 );
							
						})	
					} catch (e){ console.log(e);}
				});
			
				function getAge(dateString) {
				  var now = new Date();
				  var today = new Date(now.getYear(),now.getMonth(),now.getDate());
				
				  var yearNow = now.getYear();
				  var monthNow = now.getMonth();
				  var dateNow = now.getDate();
					// yyyy-mm-dd
				  var dob = new Date(dateString.substring(0,4), //yyyy
									 dateString.substring(5,7)-1, //mm               
									 dateString.substring(8,10)    //dd            
									 );
					
					
				  var yearDob = dob.getYear();
				  var monthDob = dob.getMonth();
				  var dateDob = dob.getDate();
				  var age = {};
				  var ageString = "";
				  var yearString = "";
				  var monthString = "";
				  var dayString = "";
				
				
				  yearAge = yearNow - yearDob;
				
				  if (monthNow >= monthDob)
					var monthAge = monthNow - monthDob;
				  else {
					yearAge--;
					var monthAge = 12 + monthNow -monthDob;
				  }
				
				  if (dateNow >= dateDob)
					var dateAge = dateNow - dateDob;
				  else {
					monthAge--;
					var dateAge = 31 + dateNow - dateDob;
				
					if (monthAge < 0) {
					  monthAge = 11;
					  yearAge--;
					}
				  }
				
				  age = {
					  years: yearAge,
					  months: monthAge,
					  days: dateAge
					  };
				  
				  return age;
				 
				}

			});

	})( jQuery );
//]]>
</script>