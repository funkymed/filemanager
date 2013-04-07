<?php
	
	print_r($_GET);
	
	$uploaddir = "../../../../".$_GET['dir'];
	
	if ($_FILES['Filedata']){
		$extension_fichier = strtolower( array_pop( explode( ".", $_FILES['Filedata']['name'] ) ) ) ;
		$uploadfile = $uploaddir . basename( $_FILES['Filedata']['name'] );
		move_uploaded_file( $_FILES['Filedata']['tmp_name'], $uploadfile );
		chmod($uploadfile,0777);
	} 
	
	
	
?>
