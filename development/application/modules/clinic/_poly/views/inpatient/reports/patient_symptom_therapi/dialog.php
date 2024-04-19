<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<?php echo form_open( $export_url, array("target" => "_blank") ); ?>
<div class="col-md-6">
    <div class="form-group">
        <label class="col-md-3 control-label"><?php echo lang('reports:date_label')?></label>
        <div class="col-md-3">
            <input id="date_start" name="f[date_start]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" />
        </div>
        <label class="col-md-3 control-label text-center"><?php echo lang('reports:till_label')?></label>
        <div class="col-md-3">
           <input id="date_end" name="f[date_end]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" />
        </div>
    </div>   
    <div class="form-group">
        <label class="col-md-3 control-label"><?php echo lang('reports:doctor_label')?></label>
        <div class="col-md-9">
        	<select id="doctor_id" name="f[doctor_id]" class="form-control" required>
				<?php foreach( option_doctor() as $key => $val): ?>
            	<option value="<?php echo $key ?>"><?php echo $val ?></option>
				<?php endforeach; ?>
            </select>
        </div>
    </div>
	<div class="form-group">
        <label class="col-md-3 control-label"><?php echo lang('reports:export_to_label')?></label>
        <div class="col-md-9">
        	<select id="export_to" name="export_to" class="form-control">
            	<option value="excel">EXCEL</option>
				<option value="pdf">PDF</option>            	
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3  form-group">
        <button type="submit" class="btn btn-primary"><b><i class="fa fa-file"></i> <?php echo lang( 'buttons:export' ) ?></b></button>
        <button type="reset" class="btn btn-default"><?php echo lang( 'buttons:reset' ) ?></button>
    </div>
</div>
<?php echo form_close()?>
<script type="text/javascript">
(function (e) {	
	$(document).ready(function(e) {

			$('body').on('focus',".datepicker", function(){
				$(this).datetimepicker({
						format: "YYYY-MM-DD", 
						widgetPositioning: {
							horizontal: 'auto', // horizontal: 'auto', 'left', 'right'
							vertical: 'auto' // vertical: 'auto', 'top', 'bottom'
						},
					});
			});				
    });
}) (jQuery);
</script>