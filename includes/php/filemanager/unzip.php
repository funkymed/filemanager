<?
	$dir_root="../../../../";
	$dirinclude="includes/php/filemanager/";
	require_once('pclzip.lib.php');
	$file=$dir_root.$_GET['file'];	
	$fileNewDir=substr($file,0,strlen($file)-4);
	$archive = new PclZip(''.$file);
	$return =@mkdir ($fileNewDir, 0777);		
	if ($archive->extract(PCLZIP_OPT_PATH, $fileNewDir) == 0) {
		die("Error : ".$archive->errorInfo(true));
	}
?>