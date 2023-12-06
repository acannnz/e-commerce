<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:bcg') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-2">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="i[BCG_1]" id="BCG_1" value="1" <?php echo @$immunization->BCG_1 == 1 ? 'checked' : NULL ?>>
						<label for="BCG_1">1</label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:dpt') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-2">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="i[DPT_1]" id="DPT_1" value="1" <?php echo @$immunization->DPT_1 == 1 ? 'checked' : NULL ?>>
						<label for="DPT_1">1</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="i[DPT_2]" id="DPT_2" value="1" <?php echo @$immunization->DPT_2 == 1 ? 'checked' : NULL ?>>
						<label for="DPT_2">2</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="i[DPT_3]" id="DPT_3" value="1" <?php echo @$immunization->DPT_3 == 1 ? 'checked' : NULL ?>>
						<label for="DPT_3">3</label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:polio') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-2">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="i[polio_1]" id="polio_1" value="1" <?php echo @$immunization->polio_1 == 1 ? 'checked' : NULL ?>>
						<label for="polio_1">1</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="i[polio_2]" id="polio_2" value="1" <?php echo @$immunization->polio_2 == 1 ? 'checked' : NULL ?>>
						<label for="polio_2">2</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="i[polio_3]" id="polio_3" value="1" <?php echo @$immunization->polio_3 == 1 ? 'checked' : NULL ?>>
						<label for="polio_3">3</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="i[polio_4]" id="polio_4" value="1" <?php echo @$immunization->polio_4 == 1 ? 'checked' : NULL ?>>
						<label for="polio_4">4</label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:hepatitis_b') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-2">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="i[hepatitis_b_1]" id="hepatitis_b_1" value="1" <?php echo @$immunization->hepatitis_b_1 == 1 ? 'checked' : NULL ?>>
						<label for="hepatitis_b_1">1</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="i[hepatitis_b_2]" id="hepatitis_b_2" value="1" <?php echo @$immunization->hepatitis_b_2 == 1 ? 'checked' : NULL ?>>
						<label for="hepatitis_b_2">2</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="i[hepatitis_b_3]" id="hepatitis_b_3" value="1" <?php echo @$immunization->hepatitis_b_3 == 1 ? 'checked' : NULL ?>>
						<label for="hepatitis_b_3">3</label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:campak') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-2">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="i[campak_1]" id="campak_1" value="1" <?php echo @$immunization->campak_1 == 1 ? 'checked' : NULL ?>>
						<label for="campak_1">1</label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:dt') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-2">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="i[DT_1]" id="DT_1" value="1" <?php echo @$immunization->DT_1 == 1 ? 'checked' : NULL ?>>
						<label for="DT_1">1</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="i[DT_2]" id="DT_2" value="1" <?php echo @$immunization->DT_2 == 1 ? 'checked' : NULL ?>>
						<label for="DT_2">2</label>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

