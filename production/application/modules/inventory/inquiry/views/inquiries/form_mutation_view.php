<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<?php echo form_open( current_url(), array("name" => "form_inquiry") ); ?>
<div class="row form-group">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('inquiry:evidence_number_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->No_Bukti ?>" placeholder="" class="form-control" readonly="readonly">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('inquiry:date_label') ?></label>
            <div class="col-lg-4">
                <input type="text" id="tanggal" name="f[tanggal]" value="<?php echo @$item->Tgl_Mutasi ?>" placeholder="" class="form-control datepicker">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Keterangan Mutasi</label>
            <div class="col-lg-9">
            	<textarea id="KeteranganMutasi" name="f[KeteranganMutasi]" class="form-control "><?php echo @$item->Keterangan ?></textarea>
            </div>
        </div>
	</div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 control-label">Nomor Amprah</label>
            <div class="col-lg-9">
                <input type="text" id="NoBuktiAmprah" name="f[NoBuktiAmprah]" value="<?php echo @$item->NoAmprahan ?>" placeholder="" class="form-control " maxlength="8">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Section Asal</label>
            <div class="col-lg-9">
                <input type="text" id="SectionAsalName" name="f[SectionAsalName]" value="<?php echo @$item->SectionTujuan ?>" placeholder="" class="form-control " readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Tanggal Amprah</label>
            <div class="col-lg-4">
                <input type="text" id="TanggalAmprah" name="f[tanggal_amprah]" value="<?php echo @$item->TanggalAmprah ?>" placeholder="" class="form-control " readonly="readonly">
            </div>
        </div>        
        <div class="form-group">
            <label class="col-lg-3 control-label">Keterangan Amprah</label>
            <div class="col-lg-9">
            	<textarea id="KeteranganAmprah" name="f[KeteranganAmprah]" class="form-control " readonly="readonly"><?php echo @$item->KeteranganAmprah ?></textarea>
            </div>
        </div>
    </div>
</div>
<?php echo modules::run("inquiry/inquiries/detail_mutations/view", @$item ) ?>
<div class="form-group">
    <div class="col-lg-12 text-right">
    	<a href="<?php echo $create_url ?>" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo lang('buttons:create')?></a>
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
							data_post['mutasi'] = {};
							data_post['mutasi_detail'] = {};
							
						var	d = new Date();
						var mutasi = {
								"No_Bukti" : $("#NoBukti").val(),
								"Tgl_Mutasi" : "<?php echo date("Y-m-d") ?>",
								"Lokasi_Asal" : $("#Lokasi_Asal").val(),
								"Lokasi_Tujuan" : $("#Lokasi_Tujuan").val(),
								"User_ID" : <?php echo $user->User_ID?>,
								"Tgl_Update" : "<?php echo date("Y-m-d") ?>",
								"Status_Batal" : 0,
								"Posting_KG" : 0,
								"Posting_GL" : 0,
								"Posting_Unit" : 0,
								"NoAmprahan" : $("#NoBuktiAmprah").val(),
								"JamMutasi" : "<?php echo date("Y-m-d") ?> "+ d.getHours() +":"+ d.getMinutes() +":"+ d.getSeconds(),
								"Approve" : 1
							}
						
						data_post['mutasi'] = mutasi;						
						

						var dt_details = $( "#dt_details" ).DataTable().rows().data();					
						dt_details.each(function (value, index) {
							var detail = {
								"No_Bukti" : $("#NoBukti").val(),
								"Barang_ID"	: value.Barang_ID,
								"Kode_Satuan" : value.Kode_Satuan,
								"Qty_Stok" : value.Qty_Stok,
								"QtyAmprah" : value.QtyAmprah,
								"Qty" : value.Qty,
								"Harga" : value.Harga,
								"JenisBarangID" : 0,
								"HRataRata" : value.HRataRata,
								"MutasiAkun_ID" : value.MutasiAkun_ID || "",
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
							
							var No_Bukti = response.No_Bukti;
							
							setTimeout(function(){
														
								document.location.href = "<?php echo base_url("inquiry/emergency/edit"); ?>/"+ No_Bukti;
								
								}, 3000 );
							
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