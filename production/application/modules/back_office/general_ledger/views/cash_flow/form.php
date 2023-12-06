<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<?php echo form_open( current_url(), ['id' => 'form-create-edit']); ?>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label"><?php echo lang('cash_flow:group_label') ?> <span class="text-danger">*</span></label>
			<div class="col-md-12">
				<select id="GroupI_Name" name="f[GroupI_Name]" class="form-control">
					<?php foreach($option_group as $k => $v):?>
						<option value="<?php echo $k?>" <?php echo $k == @$item->GroupI_Name ? 'selected' : NULL ?>><?php echo $v?></option>
					<?php endforeach;?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label"><?php echo lang('cash_flow:subgroup_label') ?> <span class="text-danger">*</span></label>
			<div class="col-md-12">
				<input type="text" id="Group_Name" name="f[Group_Name]" value="<?php echo @$item->Group_Name ?>" placeholder="" class="form-control" required>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6">
				<button type="submit" class="btn btn-primary btn-block"><?php echo lang( 'buttons:submit' ) ?></button>
			</div>
			<div class="col-md-6">
				<button type="reset" class="btn btn-warning btn-block"><?php echo lang( 'buttons:reset' ) ?></button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){		
		$( document ).ready(function(e) {
            	$("#form-create-edit" ).on('submit', function(e){
					e.preventDefault();
					$.post( $(this).prop('action'), $(this).serialize(), function(response, textStatus, xhr){
						if(response.response_status == 'error'){
							$.alert_error( response.message);
							return;
						}
						
						$.alert_success( response.message );
						location.reload();
					});
				});
			});
	})( jQuery );
//]]>
</script>