<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( current_url() ); ?>
<div class="col-md-offset-2 col-md-8">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang('income_loss_setup:setup_heading'); ?></h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label class="col-lg-3 control-label"><?php echo lang('income_loss_setup:account_label') ?> <span class="text-danger">*</span></label>
						<input type="hidden" id="Akun_ID" name="f[Akun_ID]" value="<?php echo @$item->Akun_ID ?>" />
						<div class="col-md-2">
							<input type="text" id="Akun_No" name="f[Akun_No]"  value="<?php echo @$item->Akun_No ?>" class="form-control" <?php echo @$is_edit ? "readonly" : "required" ?> />
						</div>
						<div class="col-md-7 input-group">
							<input type="text" id="Akun_Name" name="f[Akun_Name]" value="<?php echo @$item->Akun_Name ?>" class="form-control"  <?php echo @$is_edit ? "readonly" : "required" ?>/>
							<div class="input-group-btn">
								<a href="<?php echo $lookup_accounts ?>" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" data-original-title=""  <?php echo @$is_edit ? "disabled" : NULL ?> ><i class="fa fa-gear"></i></a>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label"><?php echo lang('income_loss_setup:type_label') ?> <span class="text-danger">*</span></label>
						<div class="col-lg-9">
							<select name="f[Type_Akun]" id="Type_Akun" class="form-control" required>
								<option value=""><?php echo lang('global:select-none')?></option>
								<?php if (!empty($option_income_loss)): foreach($option_income_loss as $row):?>
								<option <?php echo @$item->Type_Akun == @$row->ID ? "selected" : NULL; ?> value="<?php echo @$row->ID ?>" data-used="<?php echo $row->Akun_ID?>" data-warning="<?php echo sprintf(lang('income_loss_setup:used_warning'), @$row->Akun_No, @$row->Akun_Name) ?>"><?php echo @$row->Keterangan ?></option>
								<?php endforeach; endif; ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-3 col-lg-9">
							<button type="submit" class="btn btn-primary"><?php echo lang( 'buttons:submit' ) ?></button>
							<button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
							<?php /*?><button account_level="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close() ?>

<script>
(function($){
		$.fn.extend({
			OptionWarning: function(){
				var _this = this;
				
				_this.on("change", function(e){
					
					if ( $(this).find("option:selected").data("used") != '' ){
						
						warning = $(this).find("option:selected").data("warning");
						alert(warning);
						
						_this.val('');
					}
						
				})
				
				return _this;
			}
		});
		
	$(document).ready(function(e) {
		
		$("#Type_Akun").OptionWarning();
		 
    });

})( jQuery );
</script>