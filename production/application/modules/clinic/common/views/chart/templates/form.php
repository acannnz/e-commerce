<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( $form_action, array("id" => "form_chart_template", "name" => "form_chart_template", "role" => "form") ); ?>
<div class="row">
	<div class="col-md-12">
        <div class="form-group">
            <label><?php echo lang('chart_template:complaint_label') ?> <span class="text-danger">*</span></label>
            <textarea id="complaint" name="f[chief_complaint]" placeholder="" wrap="virtual" class="form-control" required><?php echo @$item->chief_complaint ?></textarea>
        </div>
    </div>
</div>
<div class="row">
	<div class="col-md-4">
        <div class="form-group">
            <label><?php echo lang('chart_template:is_default_label')?></label>
            <div class="checkbox">
                <input type="hidden" name="f[is_default]" value="0">
                <input type="checkbox" id="input_is_default" name="f[is_default]" value="1" <?php if(@$item->is_default == 1){ echo "checked=\"checked\""; } ?>>
                <label for="input_is_default"><strong class="text-info"><?php echo lang("global:yes")?></strong></label>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label><?php echo lang('chart_template:state_label')?></label>
            <div class="checkbox">
                <input type="hidden" name="f[state]" value="0">
                <input type="checkbox" id="input_state" name="f[state]" value="1" <?php if(@$item->state == 1){ echo "checked=\"checked\""; } ?>>
                <label for="input_state"><strong class="text-info"><?php echo lang("global:yes")?></strong></label>
            </div>
        </div>
    </div>
</div>
<div class="row">
	<div class="col-md-8">
    	<div class="form-group">
            <label><?php echo lang('chart_template:subjective_label') ?> <span class="text-danger">*</span></label>
            <textarea id="subjective" name="f[subjective]" placeholder="" wrap="virtual" class="form-control" required><?php echo @$item->subjective ?></textarea>
        </div>
        <div class="form-group">
            <label><?php echo lang('chart_template:objective_label') ?></label>
            <textarea id="objective" name="f[objective]" placeholder="" wrap="virtual" class="form-control"><?php echo @$item->objective ?></textarea>
        </div>
        <div class="form-group">
            <label><?php echo lang('chart_template:assessment_label') ?></label>
            <textarea id="assessment" name="f[assessment]" placeholder="" wrap="virtual" class="form-control"><?php echo @$item->assessment ?></textarea>
        </div>
        <div class="form-group">
            <label><?php echo lang('chart_template:plan_label') ?></label>
            <textarea id="plan" name="f[plan]" placeholder="" wrap="virtual" class="form-control"><?php echo @$item->plan ?></textarea>
        </div>  
    </div>
    <div class="col-md-4">
    	<div class="form-group">
            <label><?php echo lang('chart_template:service_comp_label') ?></label>
            <?php echo form_dropdown(
                'f[service_component_id]', 
                (array(0 => lang('global:select-empty')) + ((array) @$service_comp_options)), 
                @$item->service_component_id, 
                'id="" class="form-control"'
            ); ?>
        </div>
        <?php if( 'TRUE' == $this->config->item( "enable_chart_drug" ) ): ?>
        <div class="form-group">
            <label><?php echo lang('chart_template:product_comp_label') ?></label>
            <?php echo form_dropdown(
                'f[product_component_id]', 
                (array(0 => lang('global:select-empty')) + ((array) @$product_comp_options)), 
                @$item->product_component_id, 
                'id="" class="form-control"'
            ); ?>
        </div>
        <?php endif ?>
    </div>
</div>
<div class="row">
	<div class="col-md-12">
        <div class="form-group">
            <button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
            <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
            <?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
        </div>
    </div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$( document ).ready(function(e) {
            	var _form = $( "form[name=\"form_chart_template\"]" );
				var _form_inputs = _form.find( "textarea[name=\"f[subjective]\"], textarea[name=\"f[objective]\"], textarea[name=\"f[assessment]\"], textarea[name=\"f[plan]\"]" );
				var _form_input_events = "change paste";
				
				var chart_group = 'SOAP';
				
				var on_submitting = false;
				var on_submitted = function( response, status, xhr ){
						//console.log( status )
					};
								
				var _form_submit = function( data ){
						try{
							$.post( "<?php echo $form_action ?>", data, on_submitted )
						} catch(e){ console.log( "SOAP ajax submit failed: " + e.message ) }
					};
				
				_form_inputs.on( _form_input_events, function(e){
						e.preventDefault();
						
						var _this = $( e.target );
						var chart_role = _this.attr( 'name' );
						var chart_note = _this.val();
						
						_form_submit({'f': {
//								'chart_number': chart_number,
								'chart_group': chart_group,
								'chart_role': chart_role,
								'chart_note': chart_note
							}})
					});
					
				_form_inputs.each(function(i, elem) {
                    	var _elem = $( elem );
						_elem.summernote({
								height: 160,
								width: '98%',
								toolbar: [
										['style', ['bold', 'italic', 'underline', 'clear']],
										['font', ['strikethrough', 'superscript', 'subscript']],
										['fontsize', ['fontsize']],
										['color', ['color']],
										['para', ['ul', 'ol', 'paragraph']],
										['height', ['height']],
									],
								onPaste: function( e ){
										_elem.val( $( this ).code() );
										_elem.trigger( "paste" );
									},
								onChange: function( contents ){
										_elem.val( $( this ).code() );
										_elem.trigger( "submit" );
									}
							});
                	});
        	});
	})( jQuery )
//]]>
</script>
