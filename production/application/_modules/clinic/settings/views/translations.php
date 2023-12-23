<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="dev-viewport-panel">
	<?php if( !isset($language) ) : ?>
    <button type="button" onclick="(function(){window.history.go(-1)})(this)" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?php echo lang('buttons:back')?></button>
    <?php elseif( ! isset($language_file) ) : ?>
    <button type="button" onclick="(function(){window.history.go(-1)})(this)" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?php echo lang('buttons:back')?></button>
    <?php else: ?>
    <button type="button" onclick="(function(){window.history.go(-1)})(this)" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?php echo lang('buttons:back')?></button>
    <?php endif ?>
</div>
<div class="dev-viewport-form">
    <?php if( !isset($language) ) : ?>
    <div class="row">
    	<div class="col-md-8">
    		<h4 class="text-primary margin-bottom-0 line-height-35"><i class="fa fa-globe"></i> <?php echo lang('translations')?></h4>
        </div>
        <div class="col-md-4">
        	<div class="form-group margin-bottom-0">
            	<div class="input-group">
                	<select id="add-language" name="language" class="form-control">
						<?php foreach ($available as $loc) : ?>
                        <option value="<?php echo str_replace(" ", "_", $loc->language)?>"><?php echo ucwords($loc->language)?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="input-group-btn">
                    	<button id="add-translation" class="btn btn-default"><?php echo lang('add_translation')?></button>
                    </div> 
                </div>
            </div>
        </div>
    </div>
    <hr>
	<?php elseif( ! isset($language_file) ) : ?>
    <h4 class="text-primary"><i class="fa fa-globe"></i> <?php echo lang('translations')?> - <?php echo ucwords($language)?></h4>
    <hr>
    <?php else: ?>
    <?php 
	$fn = ucwords(str_replace("_"," ", $language_file));
	if ($language_file == 'fx') { $fn = 'Main Application'; }
	if ($language_file == 'tank_auth') { $fn = 'Authenication'; }
	
	$total = count($english);
	$translated = 0;
	if ($language == 'english') { $percent = 100; } else {
		foreach ($english as $key => $value) {
			if (isset($translation[$key]) && $translation[$key] != $value) { $translated++; }
		}
		$percent = intval(($translated / $total) * 100);
	}
	?>
<?php echo lang('translations')?> | <a href="<?php echo base_url()?>settings/translations/view/<?php echo $language?>/?settings=translations"><?php echo ucwords(str_replace("_"," ", $language))?></a> | <?php echo $fn?> | <?php echo $percent?>% <?php echo mb_strtolower(lang('done'))?>
<button type="submit" id="save-translation" class="btn btn-xs btn-primary pull-right"><?php echo lang('save_translation')?></button>
    <?php endif ?>
    <div class="row">
        <div class="col-md-12">
        	<?php if( !isset($language) ) : ?>            
            <table id="table-translations" class="table table-bordered">
                <thead>
                        <tr>
                        <th class="col-xs-1 no-sort"><?php echo lang('icon')?></th>
                        <th class="col-xs-2"><?php echo lang('language')?></th>
                        <th class="col-xs-4"><?php echo lang('progress')?></th>
                        <th class="col-xs-1"><?php echo lang('done')?></th>
                        <th class="col-xs-1"><?php echo lang('total')?></th>
                        <th class="col-options no-sort col-xs-3"><?php echo lang('options')?></th>
                        </tr>
                </thead>
                <tbody>
                    <?php foreach($languages as $l) : 
                        $st = $translation_stats;
                        $total = $st[$l->name]['total'];
                        $translated = $st[$l->name]['translated'];
                        $pc = intval(($translated/$total)*1000) / 10;
                    ?>
                    <tr>
                        <td class=""><img src="<?php echo base_url('resource/images/flags/'.$l->icon)?>.gif" /></td>
                        <td class=""><a href="<?php echo base_url()?>settings/translations/view/<?php echo $l->name?>/?settings=translations"><?php echo ucwords(str_replace("_"," ", $l->name))?></a></td>
                        <td>
                            <div class="progress">
                            <?php $bar = 'danger'; if ($pc > 20) { $bar = 'warning'; } if ($pc > 50) { $bar = 'info'; } if ($pc > 80) { $bar = 'success'; } ?>
                            <div class="progress-bar progress-bar-<?php echo $bar?>" role="progressbar" aria-valuenow="<?php echo $pc?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $pc?>%;">
                            <?php echo $pc?>%
                            </div>
                            </div>                        
                        </td>
                        <td class=""><?php echo $translated ?></td>
                        <td class=""><?php echo $total ?></td>
                        <td class="">
                          <a data-rel="tooltip" data-original-title="<?php echo lang('submit_translation_note')?>" class="submit-translation btn btn-xs btn-default" href="javascript:;" data-href="<?php echo base_url()?>settings/translations/<?php echo $l->name?>/submit/?settings=translations"><i class="fa fa-envelope-o"></i></a>
                          <a data-rel="tooltip" data-original-title="<?php echo lang('backup')?>" class="backup-translation btn btn-xs btn-default" href="javascript:;" data-href="<?php echo base_url()?>settings/translations/backup/<?php echo $l->name?>/?settings=translations"><i class="fa fa-download"></i></a>
                          <a data-rel="tooltip" data-original-title="<?php echo lang('restore')?>" class="restore-translation btn btn-xs btn-default" href="javascript:;" data-href="<?php echo base_url()?>settings/translations/restore/<?php echo $l->name?>/?settings=translations"><i class="fa fa-upload"></i></a>
                          <a data-rel="tooltip" data-original-title="<?php echo ($l->active == 1 ? lang('deactivate') : lang('activate') )?>" class="active-translation btn btn-xs btn-<?php echo ($l->active == 0 ? 'default' : 'success' )?>" href="javascript:;" data-href="<?php echo base_url()?>settings/translations/active/<?php echo $l->name?>/?settings=translations"><i class="fa fa-eye"></i></a>
                          <a data-rel="tooltip" data-original-title="<?php echo lang('edit')?>" class="btn btn-xs btn-info" href="<?php echo base_url()?>settings/translations/view/<?php echo $l->name?>/?settings=translations"><i class="fa fa-edit"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>            
            <?php elseif( ! isset($language_file) ) : ?>
            <table id="table-translations-files" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="col-xs-2 no-sort"><?php echo lang('type')?></th>
                        <th class="col-xs-3"><?php echo lang('file')?></th>
                        <th class="col-xs-4"><?php echo lang('progress')?></th>
                        <th class="col-xs-1"><?php echo lang('done')?></th>
                        <th class="col-xs-1"><?php echo lang('total')?></th>
                        <th class="col-options no-sort col-xs-1"><?php echo lang('options')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($language_files as $file => $altpath) : 
                        $shortfile = str_replace("_lang.php", "", $file);
                        $st = $translation_stats[$language]['files'][$shortfile];
                        $fn = ucwords(str_replace("_"," ", $shortfile));
                        if ($shortfile == 'fx') { $fn = 'Main Application'; }
                        if ($shortfile == 'tank_auth') { $fn = 'Authenication'; }
                        $total = $st['total'];
                        $translated = $st['translated'];
                        $pc = intval(($translated/$total)*1000) / 10;
                    ?>
                    <tr>
                        <td class=""><?php echo ($altpath == './system/language/' ? 'System':'Application')?></td>
                        <td class=""><a href="<?php echo base_url()?>settings/translations/edit/<?php echo $language?>/<?php echo $shortfile?>/?settings=translations"><?php echo $fn?></a></td>
                        <td>
                            <div class="progress">
                            <?php $bar = 'danger'; if ($pc > 20) { $bar = 'warning'; } if ($pc > 50) { $bar = 'info'; } if ($pc > 80) { $bar = 'success'; } ?>
                            <div class="progress-bar progress-bar-<?php echo $bar?>" role="progressbar" aria-valuenow="<?php echo $pc?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $pc?>%;">
                            <?php echo $pc?>%
                            </div>
                            </div>                        
                        </td>
                        <td class=""><?php echo $translated?></td>
                        <td class=""><?php echo $total?></td>
                        <td class="">
                          <a class="btn btn-xs btn-default" href="<?php echo base_url()?>settings/translations/edit/<?php echo $language?>/<?php echo $shortfile?>/?settings=translations"><i class="fa fa-edit"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <?php echo form_open_multipart('settings/translations/save/'.@$language.'/'.@$language_file.'/?settings=translations', array('id'=>'form-strings')); ?>
			<table id="table-strings" class="table table-bordered">
                <thead>
                  <tr>
                    <th class="col-xs-5">English</th>
                    <th class="col-xs-7"><?php echo ucwords(str_replace("_"," ", $language))?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($english as $key => $value) : ?>
                  <tr>
                    <td><?php echo $value?></td>
                    <td><input class="form-control" width="100%" type="text" value="<?php echo (isset($translation[$key]) ? $translation[$key] : $value)?>" name="<?php echo $key?>" /></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
            </table>
            <input type="hidden" name="_language" value="<?php echo $language?>">
            <input type="hidden" name="_file" value="<?php echo $language_file?>">
            <?php echo form_close() ?>
            <?php endif ?>
        </div>
	</div>
</div>
<script language="javascript">
//<![CDATA[
(function($){
		var base_url = '<?php echo base_url() ?>';
		
		$( document ).ready(function(e) {
			
			$('#save-translation').on('click', function (e) {
					e.preventDefault();
					oTable1.fnResetAllFilters();
					$.ajax({
						url: base_url + 'settings/translations/save/?settings=translations',
						type: 'POST',
						data: $('#form-strings').serialize()+'&_language=&_file=',
						success: function(data, textStatus, xhr) {
							toastr.success("Translation Updated Successfully", "Response Status");
						},
						error: function(xhr, textStatus, errorThrown) {
							alert('Error: '+errorThrown);
						}
					});
				});
			
			$('#table-translations').on('click','.backup-translation', function (e) {
					e.preventDefault();
					var target = $(this).attr('data-href');
					$.ajax({
						url: target,
						type: 'GET',
						data: {},
						success: function(data, textStatus, xhr) {
							toastr.success("Translation Backed Up Successfully", "Response Status");
						},
						error: function(xhr, textStatus, errorThrown) {
							alert('Error: '+errorThrown);
						}
					});
				});
			
			$("#table-translations").on('click', '.restore-translation', function (e) {
					e.preventDefault();
					var target = $(this).attr('data-href');
					$.ajax({
						url: target,
						type: 'GET',
						data: {},
						success: function(data, textStatus, xhr) {
							toastr.success("Translation Restored Successfully", "Response Status");
						},
						error: function(xhr, textStatus, errorThrown) {
							alert('Error: '+errorThrown);
						}
					});
				});
			
			$('#table-translations').on('click','.submit-translation', function (e) {
					e.preventDefault();
					var target = $(this).attr('data-href');
					$.ajax({
						url: target,
						type: 'GET',
						data: {},
						success: function(data, textStatus, xhr) {
							toastr.success("Translation Submitted Successfully", "Response Status");
						},
						error: function(xhr, textStatus, errorThrown) {
							alert('Error: '+errorThrown);
						}
					});
				});
			
			$("#table-translations").on('click','.active-translation',function (e) {
					e.preventDefault();
					var target = $(this).attr('data-href');
					var isActive = 0;
					if (!$(this).hasClass('btn-success')) { isActive = 1; }
					$(this).toggleClass('btn-success').toggleClass('btn-default');
					$.ajax({
						url: target,
						type: 'POST',
						data: { active: isActive },
						success: function(data, textStatus, xhr) {
							toastr.success("Translation Updated Successfully", "Response Status");
						},
						error: function(xhr, textStatus, errorThrown) {
							alert('Error: '+errorThrown);
						}
					});
				});            
        });
	})( jQuery )
//]]>
</script>


