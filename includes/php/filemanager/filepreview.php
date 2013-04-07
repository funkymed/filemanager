<div onmouseover="if(ThumbTimeoutHide){clearInterval(ThumbTimeoutHide);}" onmouseout="ThumbTimeoutHide=setTimeout(function(){$('Menulist').hide();},100);" style="width:150px;">
	<a href="javascript:filemanagerObj.renamefile('<?php echo $_GET['dir'].$_GET['file']; ?>');">Rename</a>
	<a href="javascript:filemanagerObj.deletefile('<?php echo $_GET['dir'].$_GET['file']; ?>');">Delete</a>
	<?php if ($_GET['type']!='dir'):?>
		<a href="javascript:filemanagerObj.DownloadFile('<?php echo $_GET['dir'].$_GET['file'];?>');">Download</a>
	<?php endif; ?>
	<?php if ($_GET['type']=='zip'):?>
		<a href="javascript:filemanagerObj.unZipFile('<?php echo $_GET['dir'].$_GET['file'];?>');">UnZip</a>
	<?php else: ?>
		<a href="javascript:filemanagerObj.ZipFile('<?php echo $_GET['dir'];?>','<?php echo $_GET['file'];?>');">Zip</a>
	<?php endif; ?>
</div>