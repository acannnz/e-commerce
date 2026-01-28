<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open($submit_url, array("id" => "form_rate_currency", "name" => "form_rate_currency")); ?>
<div class="row col-md-12">
	<?php foreach( $currency_rate as $index => $row ): ?>
	<div class="form-group">
    	<label class="col-md-4"><?php echo $row->Currency_Name ?> <span class="text-danger">*</span></label>
        <div class="col-md-8">
        	<input type="hidden" name="<?php echo sprintf("f[%s][%s]", $index, "Currency_ID") ?>" value="<?php echo $row->Currency_ID ?>" />
        	<input type="text" name="<?php echo sprintf("f[%s][%s]", $index, "Rate") ?>" value="<?php echo number_format(@$row->Rate, 2, '.', ',') ?>"  class="form-control" required="required" />
        </div>
    </div>
    <?php endforeach; ?>
    <hr/>
	<div class="form-group">
        <div class="col-md-12 text-right">
        	<button type="submit" class="btn btn-primary"><?php echo lang("buttons:submit")?></button>
        	<a href="javascript:;" class="btn btn-default" data-dismiss="modal"><?php echo lang("buttons:close")?></a>
        </div>
    </div>    
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		
	$( document ).ready(function(e) {
			
		$("#form_rate_currency").on("submit", function(e){
			e.preventDefault();	
			
			$.post('<?php echo $submit_url ?>', $(this).serializeArray() , function( response, status, xhr ){
				
				var response = $.parseJSON( response );
				
				if( "error" == response.status ){
					$.alert_error(response.message);
					return false
				}
				
				$.alert_success(response.message);
				$('#form-ajax-modal').remove();						
				$("body").removeClass("modal-open");
				
			});	
					
		});		
	});
})( jQuery );
//]]>
</script>