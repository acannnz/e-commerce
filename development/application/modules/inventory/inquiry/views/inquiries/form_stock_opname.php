<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

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
            <label class="col-lg-3 control-label">Tanggal Opname</label>
            <div class="col-lg-4">
                <input type="text" id="tanggal" name="f[tanggal]" value="<?php echo @$item->Tanggal ?>" placeholder="" class="form-control" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Lokasi Opaname</label>
            <div class="col-lg-9">
                <input type="hidden" id="SectionID" name="f[SectionID]" value="<?php echo @$section->SectionID ?>" class="inquiry">
                <input type="hidden" id="Lokasi_ID" name="f[Lokasi_ID]" value="<?php echo @$section->Lokasi_ID ?>" class="inquiry">
                <input type="text" id="SectionName" name="f[SectionName]" value="<?php echo @$section->SectionName ?>" placeholder="" class="form-control inquiry" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Kelompok Jenis</label>
            <div class="col-lg-9">
            	<select id="KelompokJenis" class="form-control">
                	<option value="ALL">ALL</option>
                	<?php if (!empty($option_kelompok_jenis_obat)): foreach($option_kelompok_jenis_obat as $row):?>
                    <option value="<?php echo $row->KelompokJenis ?>" <?php echo $row->KelompokJenis == @$item->KelompokJenis ? "selected" : "" ?>> <?php echo $row->KelompokJenis ?></option>
                    <?php endforeach; endif;?>
                </select>
            </div>
        </div>
	</div>
    <div class="col-md-6">
        <div class="form-group">
        	<a href="<?php echo $lookup_product_opname ?>" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-search"></i> Tampilkan Data Barang</b></a>
        </div>
    </div>
</div>
<?php echo modules::run("inquiry/inquiries/detail_opnames/index", @$item ) ?>
<div class="form-group">
    <div class="col-lg-12 text-right">
    	<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
        <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
        <?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
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
							data_post['opname'] = {};
							data_post['opname_detail'] = {};
							
						var	d = new Date();
						var opname = {
								"No_Bukti" : $("#NoBukti").val(),
								"Tgl_Opname" : "<?php echo date("Y-m-d") ?>",
								"Lokasi_ID" : $("#Lokasi_ID").val(),
								"User_ID" : "<?php echo $user->User_ID ?>",
								"Tgl_Update" : "<?php echo date("Y-m-d") ?>",
								"Status_Batal" : 0,
								"Posting_KG" : 0,
								"Posting_GL" : 0,
								"Posted" : 0,
								"KelompokJenis" : $("#KelompokJenis").val()
							}
						
						data_post['opname'] = opname;						
						
						var dt_details = $( "#dt_details" ).DataTable().rows().data();					
						dt_details.each(function (value, index) {
							var detail = {
								"No_Bukti" : $("#NoBukti").val(),
								"Barang_ID"	: value.Barang_ID,
								"Kode_Satuan" : value.Kode_Satuan,
								"Stock_Akhir" : value.Stock_Akhir,
								"Qty_Opname" : value.Qty_Opname,
								"Harga_Rata" : value.Harga_Rata,
								"Keterangan" : value.Keterangan,
								"JenisBarangID" : value.JenisBarangID,
								"Tgl_Expired" : value.Tgl_Expired,
							}
							
							data_post['opname_detail'][index] = detail;
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
														
								document.location.href = "<?php echo current_url() ?>/view/"+ No_Bukti;
								
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