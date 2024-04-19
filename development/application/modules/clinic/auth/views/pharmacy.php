<?php if ( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>
<div class="title text-center"><strong>Pilih Farmasi</strong></div>
<?php echo form_open(current_url(), array('class' => '')); ?>
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
    <div class="form-group no-border margin-top-20">
        <button type="submit" class="btn btn-danger btn-block"><?php echo lang('buttons:apply')?></button>
    </div>    
<?php echo form_close(); ?>