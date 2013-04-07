<?php
	require_once("dirlister.php");
	$__dir=new dirLister($_POST["_currentdir"],$_POST["validExt"]);
	print $__dir->get_json();
?>