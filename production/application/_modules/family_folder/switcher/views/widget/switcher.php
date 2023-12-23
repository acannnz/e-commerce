<?php
defined( 'BASEPATH' ) OR exit( 'No direct scriu access allowed' );
?>
<div class="btn-group btn-group-xs btn-group-justified">
<?php foreach($populate_language as $language): ?>
	<?php if ($language['name'] == $current_language): ?><a href="javascript:;" class="btn btn-primary disabled"><?php echo $language["description"] ?></a>
	<?php else: ?><a href="<?php echo base_url("switcher/".$language["name"]."/from/".$current_language); ?>" class="btn btn-primary"><?php echo $language["description"] ?></a>
	<?php endif; ?>    
<?php endforeach; ?>
</div>