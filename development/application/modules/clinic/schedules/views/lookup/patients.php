<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><?php echo lang('registrations:lookup_heading') ?></h4>
        </div>
        <div class="modal-body">
        	<script type="text/javascript">//<![CDATA[
			function lookupbox_row_selected( response ){
                    var _patient = JSON.parse(response);
					console.log(_patient);
                    if( _patient ){
                                var _form = $( "form[name=\"form_reservations\"]" );
								//var date = date('Y-m-d');
                                
                                _form.find( "input[name=\"f[NRM]\"]" ).val( _patient.NRM);
                                _form.find( "input[name=\"p[NamaPasien]\"]" ).val( _patient.NamaPasien );
								_form.find( "input[name=\"p[Phone]\"]" ).val( _patient.Phone);
								_form.find( "input[name=\"p[email]\"]" ).val( _patient.Email);
								_form.find( "input[name=\"p[Alamat]\"]" ).val( _patient.Alamat);
								
								_form.find( "#JenisKerasamaID" ).val( _patient.JenisKerasamaID);
								
                                $( '#lookup-ajax-modal' ).remove();
								$("body").removeClass("modal-open");
                    }
			}
			//]]></script>
            <?php echo Modules::run( "common/patients/lookup", true ) ?>
        </div>
        <div class="modal-footer">
        	<?php echo lang('registrations:lookup_helper') ?>
        </div>
    </div>
</div>

