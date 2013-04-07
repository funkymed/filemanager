<?php
    session_start();
	$buffer='';
	if (file_exists($_SESSION['upload']['tmp_name'])){
		$currentsize=filesize($_SESSION['upload']['tmp_name']);
		$pourcentage=($currentsize/$_SESSION['upload']['size'])*100;
	}else{
		$pourcentage=0;
	}
	
	$buffer.=$currentsize." >> ".$_SESSION['upload']['tmp_name'];
	$buffer.= '<div id="filecontainer" style="width:100%;">';
	$buffer.= '<div id="fileupload" style="margin:0px;padding:0px;width:'.$pourcentage.'%;background: url(\'img/uploading.gif\');">';
	$buffer.= '&nbsp;';
	$buffer.= '</div>';
	$buffer.= '<p>'.$pourcentage.'%</p>';
	
	$buffer.= '<p> TIME : '.mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))."</p>";

	
	echo  $buffer;
?>