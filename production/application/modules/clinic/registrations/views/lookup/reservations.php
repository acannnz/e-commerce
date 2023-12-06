<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title">Lookup Data Pasien </h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response);
				if( _response ){
					
					try {												
							$("#NRM").prop("readonly", true);
							$("#lookup_patient").addClass("disabled");
							$("#clear_patient").addClass("disabled");

							$("#NoReservasi").val( _response.NoReservasi );
							$("#NRM").val( _response.NRM );

							if ( _response.PasienBaru == 1 )
							{ 
								$("#PasienBaru").prop("checked", true); 
								
								$.post('<?php echo @$gen_mrn_link ?>', {}, function( response, status, xhr ){
										
									if( "error" == response.status ){
										$.alert_error(response.message);
										return false
									}
									
									$("#NRM").val( response.mrn );
									
								});
							
							}

							$("#JenisKerjasamaID").val( _response.JenisKerjasamaID );
							$("#NamaPasien").val( _response.Nama );
							//$("#NamaAlias").val( _response.NamaAlias );
							$("#NoIdentitas").val( _response.NoIdentitas );
							$("#JenisKelamin").val( _response.JenisKelamin );
							
							if (_response.TglLahir != null)
							{
								
								$("#TglLahir").val( _response.TglLahir.substr(0, 10) );
	
								age = getAge( _response.TglLahir.substr(0, 10) );
								$("#UmurThn").val( age.years );
								$("#UmurBln").val( age.months );
								$("#UmurHr").val( age.days );
							}
					
		
							$("#Pekerjaan").val( _response.Pekerjaan );
							$("#Alamat").val( _response.Alamat );
							$("#PropinsiID").val( _response.PropinsiID );
							$("#KabupatenID").val( _response.KabupatenID );
							$("#KecamatanID").val( _response.KecamatanID );
							$("#DesaID").val( _response.DesaID );
							$("#BanjarID").val( _response.BanjarID );
							$("#Phone").val( _response.Phone );
							$("#Email").val( _response.Email );
							$("#JenisKerjasamaID").val( _response.JenisKerjasamaID );
							$("#CustomerKerjasamaID").val( Number(_response.CustomerKerjasamaID) );
							$("#KodePerusahaan").val( _response.CompanyID );
							$("#Nama_Customer").val( _response.Nama_Customer );
							$("#NoAnggota").val( _response.NoKartu );
							$("#Klp").val( _response.Klp );
							$("#NationalityID").val( _response.NationalityID );
							$("#Klp").val( _response.Klp );
							
							if ( _response.PasienVVIP == 1 ) $("#PasienVVIP").prop("checked", true);
							if ( _response.PasienKTP == 1 ) $("#PasienKTP").prop("checked", true);
							
							$("#Agama").val( _response.Agama );
							
							if ( _response.PenanggungIsPasien == 1 ) $("#PenanggungIsPasien").prop("checked", true);
							
							$("#PenanggungNRM").val( _response.PenanggungNRM );
							$("#PenanggungNama").val( _response.PenanggungNama );
							$("#PenanggungAlamat").val( _response.PenanggungAlamat );
							$("#PenanggungPhone").val( _response.PenanggungPhone );
							$("#PenanggungKTP").val( _response.PenanggungKTP );
							$("#PenanggungHubungan").val( _response.PenanggungHubungan );
							
							$("#KdKelas").val( _response.KdKelas );
							$("#TempatLahir").val( _response.TempatLahir );
							$("#Keterangan").val( _response.Memo );
							
							var add_data = {
									SectionID : _response.UntukSectionID,
									DokterID : _response.UntukDokterID,
									WaktuID : _response.WaktuID,
									SectionName : _response.SectionName,
									Nama_Supplier : _response.Nama_Supplier,
									Keterangan : _response.KeteranganWaktu,
									NoAntri : _response.NoAntri,
								};
							
							console.log(add_data);
							
							$("#dt_registration_section").DataTable().clear().draw(true);
							$("#dt_registration_section").DataTable().row.add( add_data ).draw(true);

							$(".patient").prop("readonly", false);
							$(".patient").prop("disabled", false);
										
					} catch (e){console.log(e);}
					$( '#lookup-ajax-modal' ).remove();
					$("body").removeClass("modal-open").removeAttr("style");
				}
			}

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

			//]]></script>
            <?php //echo Modules::run( "registrations/patients/lookup_reservation", true ) ?>
			<?php $this->load->view("lookup/datatable_view_reservations"); ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('patients:referrer_lookup_helper') ?>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

