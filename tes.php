<?php
	$text="<?php echo 'tes2';?>";
	$fopen = fopen("file.php","w") or die("Unable to open file!");;
	fwrite($fopen, $text);
	fclose($fopen);
	chmod("file.php",0777);
?>