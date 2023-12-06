<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
	<div class="col-lg-12 col-md-12">
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
                    <td class=""><a href="<?php echo base_url( "settings/translations/translation/{$language}/{$shortfile}" ) ?>"><?php echo $fn?></a></td>
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
                      <a class="btn btn-xs btn-default" href="<?php echo base_url( "settings/translations/translation/{$language}/{$shortfile}" ) ?>"><i class="fa fa-edit"></i></a>
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
            	//
        	});
	})( jQuery )
//]]>
</script>

