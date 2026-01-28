 <?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	$i = 0;
	foreach($patient as $k => $v): 
		echo implode(" | ", $v); echo " <br/>";
		$i++;
		if($i ==20) exit;
	endforeach;
?>
