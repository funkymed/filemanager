<?
	$dir_root="../../../../";
	$dirinclude="includes/php/filemanager/";	
	
	$noerror=false;
	
	if (isset($_GET['delete'])){
		if (is_file($dir_root.$_GET['delete'])){
			unlink($dir_root.$_GET['delete']);	
			$file_tab=explode("/",$_GET['delete']);
			$newfile="";
			for ($xx=0;$xx<count($file_tab)-1;$xx++){
				$newfile.=$file_tab[$xx]."/";
			}
			$newfile.="thumb_".$file_tab[count($file_tab)-1 ];
			if (is_file($dir_root.$newfile)){
				unlink($dir_root.$newfile);	
			}
			echo "Effacer fichier ".$_GET['delete'];
		}else if(is_dir($dir_root.$_GET['delete'])){
			EffacerAllFile($dir_root.$_GET['delete']);
			echo "Effacer dir ".$_GET['delete'];
		}
		
	}else if (isset($_GET['rename'])){
		
		rename ( $dir_root.$_GET['old'], $dir_root.$_GET['rename']);
		
		echo "rename ".$_GET['old']." en ".$_GET['rename'];
		
	}else if (isset($_GET['newdir'])){
		$mk=@mkdir($dir_root.$_GET['directory'].$_GET['newdir'],0777);
		if ($mk){
			$noerror="pas d'erreur";
		}else{
			$noerror="le repertoire existe deja";
		}
		
	}
	print("noerror:".$noerror);
	function EffacerAllFile($path){
	    if ($path[strlen($path)-1] != "/"){
	        $path .= "/";	
        }        
	    if (is_dir($path)){
	        $d = opendir($path);
	        while ($f = readdir($d)){
	            if ($f != "." && $f != ".."){
	               $rf = $path . $f;
	                if (is_dir($rf)){
	                    EffacerAllFile($rf);
                	}else{
	                    unlink($rf);
                    }
	            }
	        }	        
			closedir($d);	
			rmdir($path);		     
	    }
	}
	
?>