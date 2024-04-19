<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


?>

<?php echo form_open( current_url(), array("name" => "form_inquiry") ); ?>
<div class="row form-group">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('inquiry:evidence_number_label') ?> <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" readonly="readonly">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('inquiry:date_label') ?></label>
            <div class="col-lg-4">
                <input type="text" id="tanggal" name="f[tanggal]" value="<?php echo @$item->Tanggal ?>" placeholder="" class="form-control datepicker">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Asal Retur</label>
            <div class="col-lg-9">
            	<select id="Lokasi_Asal" class="form-control">
                	<option value=""></option>
                	<?php if (!empty($option_section_from)): foreach($option_section_from as $row):?>
                    <option value="<?php echo $row->SectionID ?>" data-lokasiid="<?php echo $row->Lokasi_ID ?>" <?php echo $row->Lokasi_ID == @$item->Lokasi_Asal ? "selected" : "" ?>> <?php echo $row->SectionName ?></option>
                    <?php endforeach; endif;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Tujuan Retur</label>
            <div class="col-lg-9">
                <input type="text" id="SectionName" name="f[SectionName]" value="<?php echo @$section->SectionTujuan ?>" placeholder="" class="form-control inquiry" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Keterangan</label>
            <div class="col-lg-9">
            	<textarea id="Keterangan" name="f[Keterangan]" class="form-control inquiry"><?php echo @$item->Keterangan ?></textarea>
            </div>
        </div>
	</div>
    <div class="col-md-6">
    </div>
</div>
<?php echo modules::run("inquiry/inquiries/detail_mutation_returns/view", @$item ) ?>
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
							data_post['retur_mutasi'] = {};
							data_post['retur_mutasi_detail'] = {};
							
						var	d = new Date();
						var retur_mutasi = {
								"No_Bukti" : $("#NoBukti").val(),
								"Tgl_Mutasi" : "<?php echo date("Y-m-d") ?>",
								"Lokasi_Asal" : $("#Lokasi_Asal").data("lokasiid"),
								"Lokasi_Tujuan" : $("#Lokasi_Tujuan").val(),
								"User_ID" : <?php echo $user->User_ID?>,
								"Tgl_Update" : "<?php echo date("Y-m-d") ?>",
								"Status_Batal" : 0,
								"Posting_KG" : 0,
								"Posting_GL" : 0,
								"Approve" : 1
							}
						
						data_post['retur_mutasi'] = retur_mutasi;						
						
						var dt_details = $( "#dt_details" ).DataTable().rows().data();					
						dt_details.each(function (value, index) {
							var detail = {
								"No_Bukti" : $("#NoBukti").val(),
								"Barang_ID"	: value.Barang_ID,
								"Kode_Satuan" : value.Satuan_Stok,
								"Qty_Stok" : 0,
								"QtyAmprah" : 0,
								"Qty" : value.Qty,
								"Harga" : value.Harga_Jual,
								"JenisBarangID" : 0,
								"HRataRata" : value.HRataRata,
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