<?php

$dir_root="../../../";
$dirinclude="includes/php/filemanager/";

$FileToolTip=Array(
    "id"=>Array(),
    "dir"=>Array(),
    "file"=>Array(),
    "type"=>Array(),
    "thumb"=>Array(),
    "preview"=>Array()
);
$ToolTipCount=-1;

$dir_script="";
if (isset($_POST['dir']) &&  $_POST['dir']!=""){
    $dir_script=$_POST['dir'];

}else{
    $dir_script="directory/";
}
$dossier = opendir($dir_root.$dir_script);
$alldir=array();
$allfile=array();
while (false !== ($Fichier=@readdir($dossier))){
    if (is_dir($dir_root.$dir_script.$Fichier)){
        $alldir[]=$Fichier;
    }else if (is_file($dir_root.$dir_script.$Fichier)){
        $allfile[]=$Fichier;
    }
}
$checkFileSelect="FileSelect=this;FileSelectClass=this.className;";

$buffer='<div style="overflow-x:hidden;overflow:auto;height:300px;">';
$buffer.='<table width="100%" id="FileManager" border="0" cellspacing="0" cellpadding="0">';
$buffer.='<tr class="entete">';
$buffer.='<td colspan="2" align="left">Nom</td>';
$buffer.='<td align="right">Taille</td>';
$buffer.='<td align="center">Ext</td>';
$buffer.='<td align="center">Date</td>';
$buffer.='</tr>';

natcasesort($alldir);
$parentValue="directory/";
foreach ($alldir as $dir){
    $realpathDir=$dir_script.$dir."/";
    $realpathDir=str_replace("//","/",$realpathDir);
    if ($dir!=".."){
        if ($dir=="."){
            $realpathDirTab=explode("/",$realpathDir);
            $realpathDir="";
            for ($xx=0;$xx<count($realpathDirTab)-3;$xx++){
                if ($realpathDirTab[$xx]!="."){
                    $realpathDir.=$realpathDirTab[$xx]."/";
                }
            }
            $realpathDir=str_replace("//","/",$realpathDir);
        }
        $MouseEventDir='onDblClick="filemanagerObj.enterDir(\''.$realpathDir.'\');" onmouseout="this.className=FileSelectClass;"';
        if ($dir=="."){
            if ($realpathDir!=""){
                $parentValue=$realpathDir;
            }
        }else{
            $ToolTipCount++;
            $FileToolTip["id"][]='file_'.$ToolTipCount;
            $FileToolTip["dir"][]=$dir_script;
            $FileToolTip["file"][]=$dir;
            $FileToolTip["thumb"][]="";
            $FileToolTip["type"][]="dir";
            $FileToolTip["preview"][]="false";
            $overThumb='onselectstart="return false;" oncontextmenu="return false;" onmouseover="'.$checkFileSelect.'this.className=\'dir_over\';tmpFile=\''.$realpathDir.'\';tmpFileType=\'dir\';';
            $overThumb.='FileThumb=\'dir='.$dir_script.'&file='.$dir.'&type=dir&thumb=&preview=false\';" ';
            $buffer.= '<tr id="file_'.$ToolTipCount.'" class="dir" '.$MouseEventDir.' '.$overThumb.'>';
            $buffer.= '<td colspan="4" valign="bottom"><img src="images/file_manager/dir.png" align="left"/>&nbsp;'.$dir.'</td>';
            $buffer.= '<td width="100" align="right" nowrap>'.date ("d/m/Y H:i:s", filemtime($dir_root.$dir_script.$dir)).'</td>';
            $buffer.= '</tr>';
        }
    }
}

$count=0;
natcasesort($allfile);

foreach ($allfile as $file){
    $ToolTipCount++;
    $testthumb=explode("_",$file);
    if ($testthumb[0]!="thumb"){
        if ($count % 2==0){
            $classLine='tr_Info_line1';
        }else{
            $classLine='tr_Info_line2';
        }
        $realpathDir=$dir_script.'/'.$file;
        $realpathDir=str_replace("//","/",$realpathDir);
        $ext=strtolower(strrchr($file, '.'));

        if ($ext==".jpg" || $ext==".gif" || $ext==".png"){
            createthumb("thumb_".$file,$file,$dir_root.$dir_script,getimagesize($dir_root.$dir_script.$file));
            $FileToolTip["type"][]="image";
            $typeFile="image";
        }else if ($ext==".zip"){
            $FileToolTip["type"][]="zip";
            $typeFile="zip";
        }else{
            $FileToolTip["type"][]="file";
            $typeFile="file";
        }

        $MouseEventDir='class="'.$classLine.'" onmouseout="this.className=FileSelectClass;" ';

        $FileToolTip["id"][]='file_'.$ToolTipCount;
        $FileToolTip["dir"][]=$dir_script;
        $FileToolTip["file"][]=$file;

        if (is_file($dir_root.$dir_script."thumb_".$file)){
            $FileToolTip["thumb"][]="thumb_".$file;
        }else{
            $FileToolTip["thumb"][]="";
        }

        if (is_file("filestypes/".substr($ext,1,3).".png")){
            $FileToolTip["preview"][]="true";
            $icon="<img src=\"".$dirinclude."filestypes/".substr($ext,1,3).".png\" width=\"16\" height=\"16\" alt=\"\" />";
        }else{
            $FileToolTip["preview"][]="false";
            $icon='<img src="images/file_manager/filenone.png" width="16" height="16" alt="fichier inconnus" title="fichier inconnus"/>';
        }
        $overThumb='onselectstart="return false;" oncontextmenu="return false;" onmouseover=" window.oncontextmenu=filemanagerObj.unhideContextFile;'.$checkFileSelect.'this.className=\'tr_Info_over\';';
        $overThumb.='FileThumb=\'dir='.$dir_script.'&file='.$file.'&type='.$typeFile.'&thumb=thumb_'.$file.'&preview=true\';"';
        $buffer.= '<tr id="file_'.$ToolTipCount.'" '.$MouseEventDir.' '.$overThumb.'>';
        $buffer.= '<td width="16">'.$icon.'</td>';
        $buffer.= '<td>'.stripslashes($file).'</td>';
        $buffer.= '<td align="right" width="90">'.correctNum(filesize($dir_root.$dir_script.$file)/1024).' ko</td>';
        $buffer.= '<td width="25" align="center">'.$ext.'</td>';
        $buffer.= '<td width="100" align="right" nowrap>'.date ("d/m/Y H:i:s", filemtime($dir_root.$dir_script.$file)).'</td>';
        $buffer.= '</tr>';
        $count++;
    }
}
$buffer.="</table>";
$buffer.="</div>";


$nbFile=$count;//count($allfile)-1;

$dir_script_tab=explode("/",$dir_script);
$dir_display="";
for ($rr=1;$rr<count($dir_script_tab);$rr++){
    $dir_display.=$dir_script_tab[$rr]."/";
}

$dir_display=str_replace("//","/",$dir_display);
if ($nbFile<0){
    $nbFile=0;
}
$buffer.='
	<script language="text/javascript">
	var FileSelectClass=null;
	$("parentDirectory").value="'.$parentValue.'";
	$("hideDirectory").value="'.$dir_script.'";
	Debugger.DEBUG($("hideDirectory").value);
	var pathFile="('.$nbFile.') '.$dir_display.'";
	$("directoryPathInfo").update(pathFile.truncate(110,"..."));
	$("file_manager").scrollTop=0;';
$buffer.='</script>';

print $buffer;

function correctNum($num){
    return number_format($num, 2, ',', ' ');
}

function createthumb($file,$originalfile,$dir,$size){
    $picture_error=false;
    if (!file_exists($dir."/".$file)) {
        $width=$size[0];
        $height=$size[1];
        if ($width>=1800 || $height>=1800){
            $picture_error=true;
        }else{
            $max=92;
            $thumbW=$max;
            $thumbH=$max;
            $ratio=1;

            if ($width>$height ){
                $ratio=$width/$max;
            }else{
                $ratio=$height/$max;
            }

            $thumbW=$width/$ratio;
            $thumbH=$height/$ratio;

            switch($size[2]){
                case 1:
                    $image_src=imagecreatefromgif($dir."/".$originalfile);
                    break;
                case 2:
                    $image_src=imagecreatefromjpeg($dir."/".$originalfile);
                    break;
                case 3:
                    $image_src=imagecreatefrompng($dir."/".$originalfile);
                    break;
            }
            $image_dest=imagecreatetruecolor($thumbW,$thumbH);
            imagecopyresized($image_dest, $image_src, 0, 0, 0, 0,$thumbW,$thumbH, $width, $height);
            if(!imagejpeg($image_dest,$dir."/".$file)){
                echo "la création de la vignette a echouée pour l'image $image";
                exit;
            }
            @chmod($dir."/".$file,0777);
        }
    }
    return $picture_error;
}

?>