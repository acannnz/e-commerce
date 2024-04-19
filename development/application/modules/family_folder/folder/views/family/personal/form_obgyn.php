<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label class="col-md-3 control-label"><?php echo lang('label:born') ?></label>
			<div class="col-md-9">
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="o[born_status]" id="born_statusAlive" value="born_alive" <?php echo @$obgyn->born_status == 'born_alive' ? 'checked' : NULL ?>>
						<label for="born_statusAlive"><?php echo lang('label:born_alive') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="o[born_status]" id="born_statusDie" value="born_die" <?php echo @$obgyn->born_status == 'born_die' ? 'checked' : NULL ?>>
						<label for="born_statusDie"><?php echo lang('label:born_die') ?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label"><?php echo lang('label:condition') ?></label>
			<div class="col-md-9">
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="o[condition_status]" id="condition_statusAlive" value="condition_alive" <?php echo @$obgyn->condition_status == 'condition_alive' ? 'checked' : NULL ?>>
						<label for="condition_statusAlive"><?php echo lang('label:condition_alive') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="o[condition_status]" id="condition_statusDie" value="condition_die" <?php echo @$obgyn->condition_status == 'condition_die' ? 'checked' : NULL ?>>
						<label for="condition_statusDie"><?php echo lang('label:condition_die') ?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label"><?php echo lang('label:obgyn_by')?></label>
			<div class="col-md-9">
				<input type="text" id="obgyn_by" name="o[obgyn_by]" class="form-control" value="<?php echo @$obgyn->obgyn_by ?>" />
			</div>
		</div>
	</div>
</div>

