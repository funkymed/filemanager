var winFileManager;
var winFileManagerUpload=new Array();
var tmpFile="";
var tmpFileType;
var xPos;
var yPos;
var ThumbTimeoutHide;

function getMousePosition(e){
    xPos = Event.pointerX(e);
    yPos = Event.pointerY(e);
}

Event.observe(document, "mousemove", getMousePosition, false);
var filemanager = Class.create();
filemanager.prototype = {
    initialize: function() {
        this.dir="";
        var ContentFileManagerHTML =""
                +"<div id=\"Menulist\" onselectstart=\"return false;\" oncontextmenu=\"return false;\" style=\"display:none;top:10px;left:10px;\"></div>"
                +"<div id='iconFileManager'>"
                +"<table>"
                +"<tr>"
                +"<td><div onclick=\"filemanagerObj.enterDir($('parentDirectory').value);\" class=\"icon\"><img src=\"images/file_manager/filemanager_parent.gif\" title=\"Repertoire precedent\"/></div></td>"
                +"<td><div onclick=\"filemanagerObj.makedir();\" class=\"icon\"><img src=\"images/file_manager/filemanager_newdir.gif\" title=\"Creer un nouveau repertoire\"/></div></td>"
                +"<td><div onclick=\"filemanagerObj.uploadFile();\" class=\"icon\"><img src=\"images/file_manager/filemanager_loadfile.gif\" title=\"Charger un fichier\"/></div></td>"
                +"<td><div onclick=\"filemanagerObj.refresh();\" class=\"icon\"><img src=\"images/file_manager/filemanager_refresh.gif\" title=\"Actualiser\"/></div></td>"
                +"<td><div onclick=\"filemanagerObj.zipCurrentDir();\" class=\"icon\"><img src=\"images/file_manager/filemanager_zip.gif\" title=\"Zipper le repertoire courant\"/></div></td>"
                +"</tr>"
                +"</table>"
                +"</div>"
                +"<div id='directoryPathInfo'></div>"
                +"<input type='hidden' id='hideDirectory' value='image/' />"
                +"<input type='hidden' id='parentDirectory' />"
                +"<div id='file_manager'></div>";
        winFileManager = new Window({
            className: "filemanager",
            title: "Gestionnaire de fichier",
            width:528,
            height:350,
            resizable:false,
            minimizable:false,
            closable:false,
            maximizable:false,
            showEffectOptions:{duration:0.01},
            hideEffectOptions:{duration:0.01}
        });
        winFileManager.getContent().innerHTML = ContentFileManagerHTML;
        winFileManager.setConstraint(true, {left:0, right:0, top: 0, bottom:0})

    },
    openFileManager:function(){
        winFileManager.show();
        this.displaydir=$('file_manager');
        this.getDirItems();
    },
    closeFileManager:function(){
        winFileManager.hide();
    },
    unhideContextFile:function (){

        Debugger.DEBUG(FileSelect.id);
        Position.clone($(FileSelect.id),$('Menulist'),{setWidth:false,setHeight:false});

        url="includes/php/filemanager/filepreview.php?"+FileThumb;
        new Ajax.Updater('Menulist', url,{
            onComplete:function(){
                $('Menulist').show();
                ThumbTimeoutHide=setTimeout(function(){$('Menulist').hide();},1000);
            }
        });
    },
    refresh:function(){
        Debugger.DEBUG("refresh");
        this.enterDir($('hideDirectory').value);
    },
    makedir:function (){
        var v=prompt("Nom du nouveau repertoire","");
        if (v!=null){
            Debugger.DEBUG("makedir > "+v);
            this.manageFile("newdir",v+"&directory="+$("hideDirectory").value)
        }
    },
    deletefile:function(file){
        var v=confirm("Voulez vous vraiment effacer ce fichier ?");
        if (v){
            this.manageFile("delete",file);
        }
    },
    renamefile:function (fileWork){
        var file		= fileWork.substr(fileWork.lastIndexOf("/")+1,fileWork.length);
        var directory	= tmpFile.substr(0,fileWork.lastIndexOf("/")+1);
        var v=prompt("Vous pouvez renommer ce fichier",file);
        if (v!=null){
            this.manageFile("rename",directory+v+"&old="+fileWork)
        }
    },
    manageFile:function (operation,file){
        $('Menulist').hide();
        opt={
            method: 'get',
            asynchronous:false,
            evalScripts:true,
            onComplete: this.refresh.bind(this)
        };
        new Ajax.Request("includes/php/filemanager/manageFile.php?"+operation+"="+file,opt);
    },
    display: function(e){
        var reg=new RegExp("(//)", "g");
        var hD=$('hideDirectory').value;
        var pD=$('parentDirectory').value;
        $('hideDirectory').value = hD.replace(reg,"/");
        $('parentDirectory').value = pD.replace(reg,"/");
        this.displaydir.update(e.responseText);
    },
    enterDir:function(dir){
        this.dir=dir;
        this.getDirItems();
    },
    getDirItems:function(){
        Debugger.DEBUG(this.dir);
        new Ajax.Request('includes/php/filemanager/files_manager.php', {
            parameters:{dir:this.dir},
            onComplete: this.display.bind(this)
        });
    },
    uploadFile:function(){
        var winUpload = new Window({
            className: "filemanager",
            title: "Chargement de fichier",
            width:300,
            height:20,
            resizable:false,
            minimizable:false,
            maximizable:false,
            destroyOnClose: true,
            showEffectOptions:{duration:0.01},
            hideEffectOptions:{duration:0.01}
        });

        flahsImage = new FlashTag("includes/swf/filemanagerUpload.swf", "300", "20","ffffff","8,0,0,0");
        flahsImage.setFlashvars("currentdir="+$('hideDirectory').value+"&callBack=filemanagerObj.onUploadFinished('"+String(winFileManagerUpload.length)+"',{file})");
        winUpload.getContent().innerHTML = "<div>"+flahsImage.toString()+"</div>";
        winUpload.setConstraint(true, {left:0, right:0, top: 0, bottom:0})
        winUpload.setZIndex(100);


        winFileManagerUpload.push(winUpload);
        winFileManagerUpload[winFileManagerUpload.length-1].show();
    },
    onUploadFinished:function(n,f){
        Debugger.DEBUG("onUploadFinished : "+f);
        winFileManagerUpload[n].destroy();
        winFileManagerUpload[n]=null;
        if(winFileManagerUpload.compact().length==0){
            winFileManagerUpload=winFileManagerUpload.clear();
        }
        this.refresh();
    },

    zipCurrentDir:function (){
        var v=confirm("Etes vous sur de vouloir zipper le repertoire courrant ?");
        if (v==true){
            if ($F('hideDirectory').lastIndexOf("/")!=-1){
                var dirName=$F('hideDirectory').substr(0,$F('hideDirectory').lastIndexOf("/"));
                var dirName=dirName.split("/");
                dirName=dirName[dirName.length-1];
            }else{
                var dirName=$F('hideDirectory');
            }
            new Ajax.Request('includes/php/filemanager/zip.php?dir='+$F('hideDirectory')+'&file=&dirname='+dirName,{
                method: 'get',
                onComplete: this.refresh.bind(this)
            });
        }
    },
    ZipFile:function (d,f){
        var v=confirm("Etes vous sur de vouloir zipper "+f+" ?");
        if (v==true){
            var myAjax = new Ajax.Request('includes/php/filemanager/zip.php?dir='+d+'&file='+f,{
                method: 'get',
                asynchronous:false,
                onComplete: this.refresh.bind(this)
            });
        }
    },
    unZipFile:function (f){
        new Ajax.Request('includes/php/filemanager/unzip.php?file='+f,{
            method: 'get',
            onComplete: this.refresh.bind(this)
        });
    },
    DownloadFile:function(f){
        window.open("../"+escape(f), "_blank");
    }
}