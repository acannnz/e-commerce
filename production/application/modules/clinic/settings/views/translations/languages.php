<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
	<div class="col-lg-8 col-md-12">
    </div>
    <div class="col-lg-4 col-md-12">
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
<br>
<div class="row">
	<div class="col-lg-12 col-md-12">
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
                    <td class=""><a href="<?php echo base_url( "settings/translations/files/{$l->name}" )?>"><?php echo ucwords(str_replace("_"," ", $l->name))?></a></td>
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
                      <a data-rel="tooltip" data-original-title="<?php echo lang('submit_translation_note')?>" class="submit-translation btn btn-xs btn-default" href="javascript:;" data-href="<?php echo base_url( "settings/translations/submit/{$l->name}" ) ?>"><i class="fa fa-envelope-o"></i></a>
                      <a data-rel="tooltip" data-original-title="<?php echo lang('backup')?>" class="backup-translation btn btn-xs btn-default" href="javascript:;" data-href="<?php echo base_url( "settings/translations/backup/{$l->name}" ) ?>"><i class="fa fa-download"></i></a>
                      <a data-rel="tooltip" data-original-title="<?php echo lang('restore')?>" class="restore-translation btn btn-xs btn-default" href="javascript:;" data-href="<?php echo base_url( "settings/translations/restore/{$l->name}" ) ?>"><i class="fa fa-upload"></i></a>
                      <a data-rel="tooltip" data-original-title="<?php echo ($l->active == 1 ? lang('deactivate') : lang('activate') )?>" class="active-translation btn btn-xs btn-<?php echo ($l->active == 0 ? 'default' : 'success' )?>" href="javascript:;" data-href="<?php echo base_url( "settings/translations/active/{$l->name}" ) ?>"><i class="fa fa-eye"></i></a>
                      <a data-rel="tooltip" data-original-title="<?php echo lang('edit')?>" class="btn btn-xs btn-info" href="<?php echo base_url( "settings/translations/files/{$l->name}" ) ?>"><i class="fa fa-edit"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script language="javascript">
//<![CDATA[
(function($){
		$( document ).ready(function(e) {
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



