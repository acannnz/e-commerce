 <?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($personal);exit;
	try{
?>
<?php if(!empty($personal)): foreach($personal as $k => $v): ?>
	<?php echo implode(" | ", $v); ?><br />
<?php endforeach;endif;?>

<?php if(!empty($family)): foreach($family as $k => $v): ?>
	<?php echo implode(" | ", $v); ?><br />
<?php endforeach;endif; ?>
	
<?php if(!empty($patient)): foreach($patient as $k => $v): ?>
	<?php echo implode(" | ", $v); ?><br />
<?php endforeach;endif; exit; } catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
} ?>
