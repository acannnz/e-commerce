<?php if ( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>
<div class="title text-center"><strong>Petugas Medis</strong></div>
<?php echo form_open(current_url(), array('class' => '')); ?>
	<input type="hidden" value="<?php echo $type ?>">
	<div class="form-group">
    	<div class="input-group">
            <span class="input-group-addon"><i class="fa fa-building"></i></span>
            <select name="section_id" class="form-control" style="color:#999999 !important" required>
            	<?php if(!empty($option_section)): foreach($option_section as $k => $v): ?>
            	<option value="<?php echo $k ?>" <?php echo $k == @$item['section_id'] ? 'selected' : NULL?>> <?php echo $v ?></option>
                <?php endforeach; endif; ?>
            </select>
    	</div>
	</div>
    <div class="form-group">
    	<div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user-md"></i></span>
            <select name="doctor_id" class="form-control" style="color:#999999 !important" required>
            	<?php if(!empty($option_doctor)): foreach($option_doctor as $k => $v): ?>
            	<option value="<?php echo $k ?>" <?php echo $k == @$item['doctor_id'] ? 'selected' : NULL?>> <?php echo $v ?></option>
                <?php endforeach; endif; ?>
            </select>
    	</div>
	</div>
	<div class="form-group">
		<div class="input-group">
            <span class="input-group-addon"><i class="fa fa-users"></i></span>
            <select name="nurse_id" class="form-control" style="color:#999999 !important" required>
            	<?php if(!empty($option_nurse)): foreach($option_nurse as $k => $v): ?>
            	<option value="<?php echo $k ?>" <?php echo $k == @$item['nurse_id'] ? 'selected' : NULL?>> <?php echo $v ?></option>
                <?php endforeach; endif; ?>
            </select>
    	</div>
    </div>
    <div class="form-group no-border margin-top-20">
        <button type="submit" class="btn btn-danger btn-block"><?php echo lang('buttons:apply')?></button>
    </div>    
<?php echo form_close(); ?>