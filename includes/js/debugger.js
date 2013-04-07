var debugg = Class.create();
debugg.prototype = {
	initialize: function() {
		/*Unic ID*/
		var Stamp = new Date();
		var Hours=Stamp.getHours();
		var Mins=Stamp.getMinutes();
		var Seconds=Stamp.getSeconds();
		var MilliSeconds=Stamp.getMilliseconds();

		/*Vars*/
		this.TimeCode=MilliSeconds+""+Seconds+""+Mins+""+Hours;
		this.debugMode = true;
		this.debugID=this.TimeCode+"_debugID";
		this.debugIDTitle=this.TimeCode+"_debugIDTitle";
		this.debugIDTxt=this.TimeCode+"_debugIDTxt";

		/*Fenetre*/
			var debugWin = document.createElement('div');
		    debugWin.id = this.debugID;
		    debugWin.className = 'debugDiv';
	    /*Titre*/
		    var debugTitle = document.createElement('div');
		    debugTitle.id = this.debugIDTitle;
		    debugTitle.className = 'dragdebug';
	    /*Texte*/
		    var debugTxt = document.createElement('div');
		    debugTxt.id = this.debugIDTxt;
		    debugTxt.className = 'debugTxt';
		    /*Ajout dans la fenetre*/
		    debugWin.appendChild( debugTitle );
		    debugWin.appendChild( debugTxt );
	    /*Display*/
			document.body.appendChild( debugWin);

		$(this.debugIDTitle).update('Debug');
		new Draggable(this.debugID,{handle:this.debugIDTitle});
		this.debugMode=true;
	},
	removeDebugWindow:function(){
		if ($(this.debugID)){
			$(this.debugID).remove();
		}
	},
	DEBUG:function(str) {
		if ($(this.debugID) && this.debugMode) {
			$(this.debugIDTxt).appendChild(document.createTextNode(str));
			$(this.debugIDTxt).appendChild(document.createElement("br"));
			$(this.debugIDTxt).scrollTop = $(this.debugIDTxt).scrollHeight;
		}
	},
	toggleDebug:function() {
		if ($(this.debugID)) {
			this.debugMode = !this.debugMode;
			if ($(this.debugID).visible()){
				$(this.debugID).hide();
			}else{
				$(this.debugID).show();
			}
			//new Effect.toggle(this.debugID,'blind',{duration:0.3});
		}
	}
};