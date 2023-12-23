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
			function lookupbox_row_selected( row ){
				var _response = $('#dt-lookup-patients').DataTable().row(row).data();
				if( _response ){
					
					try {					
							$("#NRM").val( _response.NRM );							
							$("#JenisKerjasamaID").val( _response.JenisKerjasamaID );
							$("#NamaPasien").val( _response.NamaPasien );
							$("#NamaAlias").val( _response.NamaAlias );
							$("#NoIdentitas").val( _response.NoIdentitas );
							$("#JenisKelamin").val( _response.JenisKelamin );
							$("#TglLahir").val( _response.TglLahir.substr(0, 10) );

							age = getAge( _response.TglLahir.substr(0, 10) );
					
							$("#UmurThn").val( age.years );
							$("#UmurBln").val( age.months );
							$("#UmurHr").val( age.days );
		
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
							$("#RiwayatAlergi").val( _response.RiwayatAlergi );
							
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
							$("#KodeRegional").val( _response.KodeRegional );

							$(".patient").prop("readonly", false);
							$(".patient").prop("disabled", false);

							//GET DATA REGIONAL
							$.get("<?php echo base_url('registrations/get_regional/') ?>" + _response.KodeRegional, function(response, val, idx){
								$("#Provinsi").val(response.data.ProvinsiId);
								$("#Kabupaten").val(response.data.KabupatenNama);
								$("#Kecamatan").val(response.data.KecamatanNama);
								$("#Desa").val(response.data.DesaNama);
							});
					
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
			
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user-md"></i></span>
							<input type="search" id="lookupbox_search_words" value="" placeholder="" class="form-control">
							<div class="input-group-btn">
								<button type="button" id="lookupbox_search_button" class="btn btn-primary"><?php echo lang('buttons:filter') ?></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table id="dt-lookup-patients" class="table table-sm table-bordered table-striped" width="100%">
					<thead>
						<tr>
							<th></th>
							<th><?php echo lang('registrations:mr_number_label')?></th>
							<th><?php echo lang('registrations:patient_label')?></th>
<th><?php echo "No Identitas"?></th>
							<th><?php echo lang('registrations:gender_label')?></th>                	
							<th><?php echo lang('registrations:type_patient_label')?></th>
							<th><?php echo lang('registrations:phone_label')?></th>
							<th><?php echo lang('registrations:address_label')?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>


        </div>
        <div class="modal-footer">
        	
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
<script type="text/javascript">//<![CDATA[
(function( $ ){
	$.fn.extend({
			DT_Lookup_Reservations: function(){
					var _this = this;
					
					if( $.fn.DataTable.isDataTable( _this.attr("id") ) ){
						return _this
					}
					
					var _datatable = _this.DataTable( {
						dom: 'tip',
						lengthMenu: [ 15, 30 ],
						processing: true,
						serverSide: true,								
						paginate: true,
						ordering: true,
						select: { style: 'single'},
						order: [[1, 'asc']],
						searching: true,
						info: true,
						responsive: true,
						//scrollCollapse: true,
						//scrollY: "200px",
						ajax: {
								url: "<?php echo base_url("registrations/patients/lookup_collection") ?>",
								type: "POST",
								data: function( params ){}
							},
						columns: [
								{ 
									data: "NRM",
									name: "a.NRM",
									className: "text-center actions",
									orderable: false,
									searchable: false,
									width: '100px',
									render: function ( val, type, row, meta ){
											return "<a href='javascript:try{lookupbox_row_selected(\"" + meta.row + "\")}catch(e){}' title=\"<?php echo lang( "buttons:apply" ) ?>\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-check\"></i> <span><?php echo lang( "buttons:apply" ) ?></span></a>" 
										}
								},
								{ 
									data: "NRM",     
									name: "a.NRM",
									className: "text-center",
									orderable: true,
									searchable: true,
									render: function(val){
										return '<b>' + val + '</b>'
									}
								},
								{ 
									data: "NamaPasien",    
									name: "a.NamaPasien", 
									orderable: true,
									searchable: true,
								},
{ 
									data: "NoIdentitas",     
									name: "a.NoIdentitas",
									className: "text-center",
									orderable: true,
									searchable: true,
									render: function(val){
										return '<b>' + val + '</b>'
									}
								},
								{   data: "JenisKelamin", 
									name: "a.JenisKelamin", 
									className: "text-center", 
									orderable: true, 
									searchable: true,
									render: function(val) {
										return (val == 'M') ? 'LAKI-LAKI' : 'PEREMPUAN';
									}
								
								},
								{ data: "JenisPasien", name: "a.JenisPasien", orderable: true, searchable: true},
								{ data: "Phone", name: "a.Phone", orderable: true, searchable: true},
								{ 
									data: "Alamat", name: "a.Alamat", orderable: true, searchable: true,
									render: function ( val ){
										return val ? val.substr(0,30) : '';
									}
								},
							]
					} );
				
				return _this
			}
		});
	
	var _datatable = $( "#dt-lookup-patients" ).DT_Lookup_Reservations();

	$('#dt-lookup-patients tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('selected') ) {
			$(this).removeClass('selected');
		}else {
			$('#dt-lookup-patients tbody tr.selected').removeClass('selected');
			$(this).addClass('selected');
		}
	} );
	
	$('#button').click( function () {
		table.row('.selected').remove().draw( false );
	} );		

	var timer = 0;
	
	$( "button[type=\"button\"]#lookupbox_search_button" ).on("click", function(e){
			e.preventDefault();
			
			if (timer) {
				clearTimeout(timer);
			}
			timer = setTimeout(searchWord, 400); 
			
		});
	
	$( "input[type=\"search\"]#lookupbox_search_words" ).on("keypress", function(e){
			if ( (e.which || e.keyCode) == 13 ) {
				e.preventDefault();
				return false
			}
		});	
	
	$( "input[type=\"search\"]#lookupbox_search_words" ).on("keyup change", function(e){
			e.preventDefault();

			if (timer) {
				clearTimeout(timer);
			}
			timer = setTimeout(searchWord, 400); 
			
		});
	
	$(document).ready(function(){
		$('#lookupbox_search_words').focus()
	})
	
	function searchWord(){
		var words = $.trim( $("input[type=\"search\"]#lookupbox_search_words" ).val() || "" );
		_datatable.DataTable().search( words );
		_datatable.DataTable().draw(true);	
	}
	
})( jQuery );
//]]></script>
