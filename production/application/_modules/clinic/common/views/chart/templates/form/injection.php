<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="form-group">
    <?php echo form_open( $form_action , array("id" => "form_chart_template", "name" => "form_chart_template")); ?>
    <div class="row"> 
        <div class="col-md-10">
            <div class="checkbox">
                <input type="hidden" name="save_as_template" value="0">
                <input type="checkbox" id="save_as_template" name="save_as_template" value="1">
                <label for="save_as_template"><strong class="text-primary"><?php echo lang("charts:save_chart_template_label")?></strong></label>
            </div>
        </div>
        <div class="col-md-2">
            <a href="<?php echo base_url("charts/lookup_templates")."?chart_num={$chart_number}" ?>" title="<?php echo lang( "charts:tip_services" ) ?>" data-toggle="lookup-ajax-modal" class="btn btn-block btn-primary tip"><i class="fa fa-gear"></i></a>
        </div>
    </div>
    <input type="hidden" name="template_id" value="">
    <input type="hidden" name="chief_complaint" value="">
    <input type="hidden" name="subjective" value="">
    <input type="hidden" name="objective" value="">
    <input type="hidden" name="assessment" value="">
    <input type="hidden" name="plan" value="" >
    <?php echo form_close() ?>                        
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$( document ).ready(function(e) {
				var _form = $( "form[name=\"form_chart_template\"]" );
				var _form_cc = $( "form[name=\"form_chart_cc\"]" );
				var _form_soap = $( "form[name=\"form_chart_soap\"]" );
				
				_form.on("submit", function(e){
						e.preventDefault();
						
						var save_as_template = _form.find( "input:checkbox[name=\"save_as_template\"]" ).prop( "checked" );
						if( true == save_as_template ){
							var data = {
									id: 0,
									chief_complaint: _form_cc.find( "input[name=\"chief_complaint\"]" ).val() || '',
									subjective: _form_soap.find( "textarea[name=\"subjective\"]").code() || '',
									objective: _form_soap.find( "textarea[name=\"objective\"]").code() || '',
									assessment: _form_soap.find( "textarea[name=\"assessment\"]").code() || '',
									plan: _form_soap.find( "textarea[name=\"plan\"]").code() || ''
								};
								
							_form.find( "input[name=\"template_id\"]" ).val( 0 );
							_form.find( "input[name=\"chief_complaint\"]" ).val( data.chief_complaint );
							_form.find( "input[name=\"subjective\"]" ).val( data.subjective );
							_form.find( "input[name=\"objective\"]" ).val( data.objective );
							_form.find( "input[name=\"assessment\"]" ).val( data.assessment );
							_form.find( "input[name=\"plan\"]" ).val( data.plan );
							
							$.post( "<?php echo base_url("common/chart-templates/save_as") . "?reg_num={$item->registration_number}&chart_num={$item->chart_number}" ?>", {"f": data}, function( response, status, xhr ){
									if( response.status == "error" ){
										$.alert_error( response.status );
									}
								})
						}
						
						return false;
					})
			});
	})( jQuery )
//]]>
</script>
