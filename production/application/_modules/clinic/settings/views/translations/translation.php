<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<?php echo form_open_multipart('settings/translations/save/'.@$language.'/'.@$language_file.'/?settings=translations', array('id'=>'form-strings')); ?>
<div class="row">
	<div class="col-lg-12 col-md-12">
        <table id="table-strings" class="table table-bordered">
            <thead>
              <tr>
                <th class="col-xs-5">English</th>
                <th class="col-xs-7"><?php echo ucwords(str_replace("_"," ", $language)) ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($english as $key => $value) : ?>
              <tr>
                <td><?php echo $value ?></td>
                <td><input class="form-control" width="100%" type="text" value="<?php echo (isset($translation[$key]) ? $translation[$key] : $value)?>" name="<?php echo $key?>" /></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<input type="hidden" name="_language" value="<?php echo $language ?>">
<input type="hidden" name="_file" value="<?php echo $language_file?>">
<?php echo form_close() ?>
<script language="javascript">
//<![CDATA[
(function($){
		
	})( jQuery )
//]]>
</script>

