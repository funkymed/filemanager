<?php

	include "configtool.php";
	
	print getId3InfoDipslay(getId3Info($dir_root.$_GET['file']));

	function getId3Info($file){
		if(is_file($file)){
			require_once('id3/getid3.php');
			$getID3 = new getID3;
			$ThisFileInfo = $getID3->analyze($file);
			return $ThisFileInfo;
		}else{
			echo "fichier erreur<br/>";
			echo $file;
		}
	}
	
	function getId3InfoDipslay($tabID3){
		$buffer="";
		$buffer.= "<fieldset>";
		$buffer.= "<legend>Info</legend>";
		$buffer.= "Fichier : <a href=\"javascript:DownloadFile('".$_GET['file']."');\">".$tabID3['filename']."</a><br/>";	
		$buffer.= "Format : ".$tabID3['fileformat']."<br/>";	
		$buffer.= "Dur&eacute;e : ".$tabID3['playtime_string']." Minutes<br/>";	
		$buffer.= "Taille : ".correctNum(($tabID3['filesize']/1024))."ko<br/>";	
		$buffer.= "</fieldset>";
		if ($tabID3['audio']){
			$buffer.= "<fieldset>";
			$buffer.= "<legend>Audio</legend>";
			$buffer.= "Codec : ".$tabID3['audio']['codec']."<br/>";	
			$buffer.= "Resolution : ".correctNum(($tabID3['audio']['sample_rate']/1024))." khz<br/>";	
			$buffer.= "Bit : ".$tabID3['audio']['bits_per_sample']."bits<br/>";	
			if ($tabID3['audio']['bits_per_sample']==1){
				$buffer.= "Canaux : mono<br/>";	
			}else if ($tabID3['audio']['bits_per_sample']==1){
				$buffer.= "Canaux : stéréo<br/>";	
			}
			$buffer.= "</fieldset>";
		}
		if ($tabID3['video']){
			$buffer.= "<fieldset>";
			$buffer.= "<legend>Vid&eacute;o</legend>";
			$buffer.= "Format : ".$tabID3['video']['dataformat']."<br/>";	
			$buffer.= "Format : ".$tabID3['video']['codec']."<br/>";	
			$buffer.= "Image/seconde : ".$tabID3['video']['frame_rate']."i/s<br/>";	
			$buffer.= "R&eacute;solution : ".$tabID3['video']['resolution_x']." x ".$tabID3['video']['resolution_y']."<br/>";	
			$buffer.= "</fieldset>";
		}
		return $buffer;
	}
	
	function correctNum($num){
		return number_format($num, 2, ',', ' ');
	}
?>