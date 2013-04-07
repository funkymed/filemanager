<?php
	$dir_root="../../../";
	$dirinclude="includes/php/filemanager/";
	
	require_once('pclzip.lib.php');
	$file=$_GET['file'];
	$dir=$dir_root.$_GET['dir'].'/';
	
	if (isset($_GET['dirname'])){
		$archive = new PclZip($dir.$_GET['dirname'].'.zip');
		$v_list = $archive->create($dir,
                            PCLZIP_OPT_REMOVE_PATH, $dir);
	}else{
		$archive = new PclZip($dir.$file.'.zip');
		$v_list = $archive->create($dir.$file,
                            PCLZIP_OPT_REMOVE_PATH, $dir);
	}
	if ($v_list == 0) {
		echo 'error';
		die("Error : ".$archive->errorInfo(true));
	}else{
		echo 'ok';
	}
	
?>