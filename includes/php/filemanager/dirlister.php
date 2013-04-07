<?php
	// Gestionnaire de fichier 
	
	class dirLister{
		
		var $root_dir, $current_dir, $dossier, $dirObj,$html,$directory_file_size;
		var $all_file=array();	
		var $all_dir=array();	
		var $imageFileExt=array('.jpg','.png','.gif');
		var $validExt="";
		var $thumbSize=128;
		
		function dirLister($root_dir=null,$validExt){
			$this->validExt=explode(",",$validExt);
			$this->root_dir=$root_dir;
			$this->dossier=$root_dir;
			$this->update_dir();
			$this->directory_file_size=0;
		}
		
		function enterDir($setdossier=null){
			//$this->dossier=$dossier.$setdossier;
			$this->dossier=$setdossier;
		}
		
		function update_dir(){
			if (is_dir($this->dossier)){
				$this->all_file=array(
					"NAME"=>array(),
					"SIZE"=>array(),
					"OCTSIZE"=>array(),
					"EXT"=>array(),
					"DATE"=>array(),
					"PATH"=>array(),
					"ICON"=>array(),
					"COUNT"=>0,
					"DIRECTORY_SIZE"=>0
				);	
				$this->all_dir=array(
					"NAME"=>array(),
					"DATE"=>array(),
					"ICON"=>array(),
					"COUNT"=>0
				);	
				$this->dirObj = opendir($this->dossier);
				while (false !== ($Fichier=@readdir($this->dirObj))){
					
					if (is_dir($this->dossier.$Fichier) && $Fichier!="." && $Fichier!=".."){
						$this->all_dir["NAME"][]=$Fichier;
						$this->all_dir["DATE"][]=date ("d/m/Y H:i:s", filemtime($this->dossier.$Fichier));
						$this->all_dir["ICON"][]='filestypes/dir.png';
					}else if (is_file($this->dossier.$Fichier) && in_array(strtolower(strrchr($Fichier, '.')),$this->validExt)){
						$fileSize=filesize($this->dossier.$Fichier);
						$testthumb=explode("_",$Fichier);
						
						if ($testthumb[0]!="thumb"){
							$this->all_file["NAME"][]=$Fichier;
							$this->all_file["EXT"][]=strtolower(strrchr($Fichier, '.'));
							$this->all_file["SIZE"][]=$this->convertOct($fileSize);
							$this->all_file["OCTSIZE"][]=$fileSize;
							$this->all_file["DATE"][]=date ("d/m/Y H:i:s", filemtime($this->dossier.$Fichier));
							$this->all_file["PATH"][]=$this->dossier.$Fichier;
							$this->all_file["ICON"][]=$this->getFileType(strtolower(strrchr($Fichier, '.')),$Fichier);
							$this->directory_file_size+=$fileSize;
							$this->makethumbnail($this->dossier.$Fichier);
						}
					}
					
				}
				$this->all_dir["COUNT"]=count($this->all_dir["NAME"]);
				$this->all_file["COUNT"]=count($this->all_file["NAME"]);
				$this->all_file["DIRECTORY_SIZE"]=$this->convertOct($this->directory_file_size);
				
				sort($this->all_dir["NAME"]);
				sort($this->all_file["NAME"]);
				
			}else{
				$this->error("[PARSE ERROR] directory not recognized");
			}
		}
		function error($msg){
			print $msg."<br/>\n";
		}
		function get_FileArray(){
			return $this->all_file;
		}
		function get_DirArray(){
			return $this->all_dir;
		}
		function getFileType($ext,$Fichier){
			$FileExt=substr($ext,1,strlen($ext));
			if (is_file('filestypes/'.$FileExt.'.png')){
				return 'filestypes/'.$FileExt.'.png';
			}else{
				return 'filestypes/default.png';
			}
		}
		function debugHTML(){
			if (is_dir($this->dossier)){
				$buffer='<div id="FileManager"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
				$buffer.='<tr class="top">';
				$buffer.='<td align="left">Nom</td>';
				$buffer.='<td align="right">Taille</td>';
				$buffer.='<td align="center">Ext</td>';
				$buffer.='<td align="right">Date</td>';
				$buffer.='</tr>';
				/*repertoires*/
				for ($aa=0;$aa<count($this->all_dir['NAME']);$aa++){
					if ($aa % 2==0){ $class="dir1"; }else{ $class="dir2"; }
					$buffer.= "<tr class=\"".$class."\"><td  colspan=\"3\">".$this->all_dir['NAME'][$aa]."</td>";
					$buffer.= "<td align=\"right\">".$this->all_dir['DATE'][$aa]."</td></tr>";
				}
				/*fichiers*/
				for ($bb=0;$bb<count($this->all_file['NAME']);$bb++){
					if ($bb % 2==0){ $class="file1"; }else{ $class="file2"; }
					$buffer.= "<tr class=\"".$class."\">";
					$buffer.= "<td>".$this->all_file['NAME'][$bb]."</td>";
					$buffer.= "<td align=\"right\">".number_format($this->all_file['SIZE'][$bb], 2, ',', ' ')."ko</td>";
					$buffer.= "<td align=\"center\">".$this->all_file['EXT'][$bb]."</td>";
					$buffer.= "<td align=\"right\">".$this->all_file['DATE'][$bb]."</td>";
					$buffer.= "</tr>";
				}
				$buffer.='</table></div>';
				$this->html=$buffer;
			}else{
				$this->error("[DISPLAY ERROR] directory not recognized");
			}
		}
		function get_json(){
			$arrayDir=null;
			$arrayFile=null;
			if ($this->all_dir["COUNT"]>0){
				$arrayDir=$this->all_dir;
			}
			if ($this->all_file["COUNT"]>0){
				$arrayFile=$this->all_file;
			}
			return $this->array2json(array(array("DIR"=>$arrayDir,"FILE"=>$arrayFile)));
		}
		
		function getFileArray(){
			return $this->all_file;
		}
		
		function getHTML(){
			return $this->html;
		}
		function array2json($arr) {
		    $parts = array();
		    $is_list = false;
		    $keys = array_keys($arr);
		    $max_length = count($arr)-1;
		    if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {
		        $is_list = true;
		        for($i=0; $i<count($keys); $i++) {
		            if($i != $keys[$i]) { 
		                $is_list = false;
		                break;
		            }
		        }
		    }
		    foreach($arr as $key=>$value) {
		        if(is_array($value)) { 
		            if($is_list) $parts[] = $this->array2json($value);
		            else $parts[] = '"' . $key . '":' . $this->array2json($value);
		        } else {
		            $str = '';
		            if(!$is_list) $str = '"' . $key . '":';
		            if(is_numeric($value)) $str .= $value;
		            elseif($value === false) $str .= 'false';
		            elseif($value === true) $str .= 'true';
		            else $str .= '"' . addslashes($value) . '"';
		            $parts[] = $str;
		        }
		    }
		    $json = implode(',',$parts);
		    if($is_list) return '[' . $json . ']';
		    return '{' . $json . '}';
		} 
	
		function convertOct($val){
			if ($val>1000000000){
				$val/=1024;
				$val/=1024;
				$val/=1024;
				return number_format($val, 2, ',', ' ')." Go";	
			}else if ($val>1000000){
				$val/=1024;
				$val/=1024;
				return number_format($val, 2, ',', ' ')." Mo";	
			}else if ($val>1000){
				$val/=1024;
				return number_format($val, 2, ',', ' ')." ko";	
			}else{
				return number_format($val, 2, ',', ' ')." octs";	
			}
		}
		
		function makethumbnail($filename){
			$newNameTab=explode("/",$filename);
			$newName="";
			for ($xx=0;$xx<count($newNameTab)-1;$xx++){
				$newName.=$newNameTab[$xx]."/";
			}
			$newName.="thumb_".$newNameTab[count($newNameTab)-1];
			
			if(!is_file($newName)){
				$width = 64;
				$height = 64;
				list($width_orig, $height_orig) = getimagesize($filename);
				$ratio_orig = $width_orig/$height_orig;
				if ($width/$height > $ratio_orig) {
				   $width = $height*$ratio_orig;
				} else {
				   $height = $width/$ratio_orig;
				}
				$image_p = imagecreatetruecolor($width, $height);
				$image = imagecreatefromjpeg($filename);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
				imagejpeg($image_p, $newName, 90);
			}
			
		}
	}
	
?>