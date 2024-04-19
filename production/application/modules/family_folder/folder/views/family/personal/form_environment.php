<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:house_wall') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[house_wall]" id="house_wall1" value="permanent" <?php echo @$environment->house_wall == 'permanent' ? 'checked' : NULL ?>>
						<label for="house_wall1"><?php echo lang('label:permanent') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[house_wall]" id="house_wall2" value="semi_permanent" <?php echo @$environment->house_wall == 'semi_permanent' ? 'checked' : NULL ?>>
						<label for="house_wall2"><?php echo lang('label:semi_permanent') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[house_wall]" id="house_wall3" value="wooden" <?php echo @$environment->house_wall == 'wooden' ? 'checked' : NULL ?>>
						<label for="house_wall3"><?php echo lang('label:wooden') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[house_wall]" id="house_wall4" value="woven" <?php echo @$environment->house_wall == 'woven' ? 'checked' : NULL ?>>
						<label for="house_wall4"><?php echo lang('label:woven') ?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:house_floor') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[house_floor]" id="house_floor1" value="cement" <?php echo @$environment->house_floor == 'cement' ? 'checked' : NULL ?>>
						<label for="house_floor1"><?php echo lang('label:cement') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[house_floor]" id="house_floor2" value="flank" <?php echo @$environment->house_floor == 'flank' ? 'checked' : NULL ?>>
						<label for="house_floor2"><?php echo lang('label:flank') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[house_floor]" id="house_floor3" value="soil" <?php echo @$environment->house_floor == 'soil' ? 'checked' : NULL ?>>
						<label for="house_floor3"><?php echo lang('label:soil') ?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:house_lighting') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[house_lighting]" id="house_lighting1" value="enough" <?php echo @$environment->house_lighting == 'enough' ? 'checked' : NULL ?>>
						<label for="house_lighting1"><?php echo lang('label:enough') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[house_lighting]" id="house_lighting2" value="less" <?php echo @$environment->house_lighting == 'less' ? 'checked' : NULL ?>>
						<label for="house_lighting2"><?php echo lang('label:less') ?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:house_krpl') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[house_krpl]" id="house_krpl1" value="exist" <?php echo @$environment->house_krpl == 'exist' ? 'checked' : NULL ?>>
						<label for="house_krpl1"><?php echo lang('label:exist') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[house_krpl]" id="house_krpl2" value="none" <?php echo @$environment->house_krpl == 'none' ? 'checked' : NULL ?>>
						<label for="house_krpl2"><?php echo lang('label:none') ?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:waste_disposal') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[waste_disposal]" id="waste_disposalBurned" value="burned" <?php echo @$environment->waste_disposal == 'burned' ? 'checked' : NULL ?>>
						<label for="waste_disposalBurned"><?php echo lang('label:burned') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[waste_disposal]" id="waste_disposalPlanted" value="planted" <?php echo @$environment->waste_disposal == 'planted' ? 'checked' : NULL ?>>
						<label for="waste_disposalPlanted"><?php echo lang('label:planted') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[waste_disposal]" id="waste_disposalTPS" value="TPS" <?php echo @$environment->waste_disposal == 'TPS' ? 'checked' : NULL ?>>
						<label for="waste_disposalTPS"><?php echo lang('label:tps') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[waste_disposal]" id="waste_disposalThrownInOpen" value="thrown_in_open" <?php echo @$environment->waste_disposal == 'thrown_in_open' ? 'checked' : NULL ?>>
						<label for="waste_disposalThrownInOpen"><?php echo lang('label:thrown_in_open') ?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:sewer') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[sewer]" id="sewerExist" value="exist" <?php echo @$environment->sewer == 'exist' ? 'checked' : NULL ?>>
						<label for="sewerExist"><?php echo lang('label:exist') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[sewer]" id="sewerNo" value="none" <?php echo @$environment->sewer == 'none' ? 'checked' : NULL ?>>
						<label for="sewerNo"><?php echo lang('label:none') ?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:water_source') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[water_source]" id="water_sourceSPT" value="SPT" <?php echo @$environment->water_source == 'SPT' ? 'checked' : NULL ?>>
						<label for="water_sourceSPT"><?php echo lang('label:spt') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[water_source]" id="water_sourcePMA" value="PMA" <?php echo @$environment->water_source == 'PMA' ? 'checked' : NULL ?>>
						<label for="water_sourcePMA"><?php echo lang('label:pma') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[water_source]" id="water_sourceMA" value="MA" <?php echo @$environment->water_source == 'MA' ? 'checked' : NULL ?>>
						<label for="water_sourceMA"><?php echo lang('label:ma') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[water_source]" id="water_sourcePAH" value="PAH" <?php echo @$environment->water_source == 'PAH' ? 'checked' : NULL ?>>
						<label for="water_sourcePAH"><?php echo lang('label:pah') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[water_source]" id="water_sourceLeiding" value="Leiding" <?php echo @$environment->water_source == 'Leiding' ? 'checked' : NULL ?>>
						<label for="water_sourceLeiding"><?php echo lang('label:leiding') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[water_source]" id="water_sourceWell" value="Well" <?php echo @$environment->water_source == 'Well' ? 'checked' : NULL ?>>
						<label for="water_sourceWell"><?php echo lang('label:well') ?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:toilet') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[toilet]" id="toiletCemplung" value="Cemplung" <?php echo @$environment->toilet == 'Cemplung' ? 'checked' : NULL ?>>
						<label for="toiletCemplung"><?php echo lang('label:cemplung') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="rdio rdio-default">
						<input type="radio" name="e[toilet]" id="toiletLantrine" value="Lantrine" <?php echo @$environment->toilet == 'Lantrine' ? 'checked' : NULL ?>>
						<label for="toiletLantrine"><?php echo lang('label:lantrine') ?></label>
					</div>
				</div>
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon">
							<input type="radio" name="e[toilet]" id="toiletEtc" value="etc" <?php echo !in_array(@$environment->toilet, ['Cemplung', 'Lantrine']) ? 'checked' : NULL ?>>
							<label for="toiletEtc"><?php echo lang('label:etc') ?></label>
					  	</span>
					  	<input type="text" id="toiletEtcText" name="e[toiletEtcText]" class="form-control" value="<?php echo !in_array(@$environment->toilet, ['Cemplung', 'Lantrine']) ? @$environment->toilet : NULL ?>" >
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-12 control-label"><b><?php echo lang('label:dietary') ?></b></label>
			<div class="col-md-12 row">
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon">
							<input type="checkbox" name="e[dietary_staple_food]" id="dietary_staple_food" value="1" <?php echo !empty($environment->dietary_staple_food) ? 'checked' : NULL ?>>
							<label for="dietary_staple_food"><?php echo lang('label:dietary_staple_food') ?></label>
					  	</span>
					  	<input type="text" id="dietary_staple_food_text" name="e[dietary_staple_food_text]" class="form-control" placeholder="" value="<?php echo @$environment->dietary_staple_food ?>">
					</div>
				</div>
				<div class="col-md-3">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="e[dietary_side_dishes]" id="dietary_side_dishes" value="1" <?php echo @$environment->dietary_side_dishes == 1 ? 'checked' : NULL ?>>
						<label for="dietary_side_dishes"><?php echo lang('label:dietary_side_dishes') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="e[dietary_vegetables]" id="dietary_vegetables" value="1" <?php echo @$environment->dietary_vegetables == 1 ? 'checked' : NULL ?>>
						<label for="dietary_vegetables"><?php echo lang('label:dietary_vegetables') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="e[dietary_fruits]" id="dietary_fruits" value="1" <?php echo @$environment->dietary_fruits == 1 ? 'checked' : NULL ?>>
						<label for="dietary_fruits"><?php echo lang('label:dietary_fruits') ?></label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="ckbox ckbox-default">
						<input type="checkbox" name="e[dietary_milk]" id="dietary_milk" value="1" <?php echo @$environment->dietary_milk == 1 ? 'checked' : NULL ?>>
						<label for="dietary_milk"><?php echo lang('label:dietary_milk') ?></label>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>




