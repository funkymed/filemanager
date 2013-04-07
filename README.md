#filemanager
===========
A FileManager in JS + PHP to help to manage your file

This project was coded in 2007 with prototypeJS (http://www.prototypejs.org)

GitHub : https://github.com/funkymed/selectfile

##Demo

http://www.cyrilpereira.com/filemanager/

##Author

Cyril Pereira http://www.cyrilpereira.com

##Documentation

To initialize it you have to add this to your header page
~~~
<link type="text/css" rel="stylesheet" href="css/filemanager.css" media="screen" />
<link type="text/css" rel="stylesheet" href="css/debug.css" media="screen" />
<link type="text/css" rel="stylesheet" href="css/themes/default.css" media="screen" />
<link type="text/css" rel="stylesheet" href="css/themes/filemanager.css" media="screen" />
~~~

And this to your bottom body
~~~
<script type="text/javascript" src="includes/js/protoculous-packer.js"></script>
<script type="text/javascript" src="includes/js/debugger.js"></script>
<script type="text/javascript" src="includes/js/window.js"></script>
<script type="text/javascript" src="includes/js/FlashTag.js"></script>
<script type="text/javascript" src="includes/js/filemanager.js"></script>
<script type="text/javascript">
    var Debugger=new Object(),
        filemanagerObj = new Object();

    window.onload=function()
    {
        Debugger=new debugg();
        filemanagerObj	= new filemanager();
        filemanagerObj.openFileManager();

    }
</script>
~~~

/!\ You need to create a directory called "directory" in chmod 0777

To open FileManger
~~~
filemanagerObj.openFileManager();
~~~

To close FileManger
~~~
filemanagerObj.closeFileManager();
~~~

To hide the debug window
~~~
Debugger.toggleDebug();
~~~

In the directory includes/swf you will found the source code in a .rar file of my uploader in swf