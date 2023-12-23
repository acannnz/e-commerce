<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url(), array("id" => "close_books", "name" => "close_books") ); ?>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('close_books:date_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <input type="text" id="date" name="date" placeholder="" class="form-control datepicker" value="<?php echo  date("Y-m", strtotime( "$last_close_books->date +1 day" )) ?>" data-date-format="YYYY-MM" data-date-min-date="<?php echo date("Y-m", strtotime( $last_close_books->date." -31 day")) ?>" required>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label"><?php echo lang('close_books:last_close_books_label') ?> <span class="text-danger">*</span></label>
    <div class="col-lg-6">
        <a href="javascript:;" class="btn btn-danger" ><?php echo date("Y-m", strtotime( $last_close_books->date)) ?></a>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
    	<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:proces' ) ?></button>
        <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
        <?php /*?><button account_level="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
    </div>
</div>
<?php echo form_close() ?>

<script>
//<![CDATA[
(function( $ ){
		$( document ).ready(function(e) {

				$("form[name=\"close_books\"]").on("submit", function(e){

					e.preventDefault();
					
					var data_post = $(this).serializeArray();
					
					console.log(data_post);

					$.post($(this).attr("action"), data_post, function( response, status, xhr ){

						var response = $.parseJSON(response);

						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success("<?php echo lang('close_books:success_status')?>");
						
						var id = response.id;
						
						setTimeout(function(){
													
							document.location.href = "<?php echo current_url(); ?>" ;
							
							}, 2000 );
						
					})	
				});
								
			});
	})( jQuery );
//]]>
</script>

