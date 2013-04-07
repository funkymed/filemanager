<?	
	include "configtool.php";

	$buffer='';
	if (isset($_POST['upload'])){
		$buffer.='<div id="resultFile">';
		$tmp=$_FILES['fichier']['tmp_name'];
		$file=$dir_root.$_POST['directory'].$_FILES['fichier']['name'];
        if (move_uploaded_file($tmp,$file)){
	        $buffer.="true";
        }else{
	      	$buffer.="false";
        }
        $buffer.='</div>';
	}
	print $buffer;
	print_r($_FILES);
	print_r($_POST);

?>