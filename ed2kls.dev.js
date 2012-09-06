if (typeof(ZeroClipboard) == "undefined") {
/*
ZeroClipboard
by Joseph Huckaby
https://github.com/jonrohan/ZeroClipboard
MIT License
*/
var ZeroClipboard={version:"1.0.8",clients:{},moviePath:"ZeroClipboard.swf",nextId:1,$:function(a){return typeof a=="string"&&(a=document.getElementById(a)),a.addClass||(a.hide=function(){this.style.display="none"},a.show=function(){this.style.display=""},a.addClass=function(a){this.removeClass(a),this.className+=" "+a},a.removeClass=function(a){var b=this.className.split(/\s+/),c=-1;for(var d=0;d<b.length;d++)b[d]==a&&(c=d,d=b.length);return c>-1&&(b.splice(c,1),this.className=b.join(" ")),this},a.hasClass=function(a){return!!this.className.match(new RegExp("\\s*"+a+"\\s*"))}),a},setMoviePath:function(a){this.moviePath=a},newClient:function(){return new ZeroClipboard.Client},dispatch:function(a,b,c){var d=this.clients[a];d&&d.receiveEvent(b,c)},register:function(a,b){this.clients[a]=b},getDOMObjectPosition:function(a,b){var c={left:0,top:0,width:a.width?a.width:a.offsetWidth,height:a.height?a.height:a.offsetHeight};while(a&&a!=b)c.left+=a.offsetLeft,c.top+=a.offsetTop,a=a.offsetParent;return c},Client:function(a){this.handlers={},this.id=ZeroClipboard.nextId++,this.movieId="ZeroClipboardMovie_"+this.id,ZeroClipboard.register(this.id,this),a&&this.glue(a)}};ZeroClipboard.Client.prototype={id:0,ready:!1,movie:null,clipText:"",handCursorEnabled:!0,cssEffects:!0,handlers:null,zIndex:99,glue:function(a,b,c){this.domElement=ZeroClipboard.$(a),this.domElement.style.zIndex&&(this.zIndex=parseInt(this.domElement.style.zIndex,10)+1),typeof b=="string"?b=ZeroClipboard.$(b):typeof b=="undefined"&&(b=document.getElementsByTagName("body")[0]);var d=ZeroClipboard.getDOMObjectPosition(this.domElement,b);this.div=document.createElement("div");var e=this.div.style;e.position="absolute",e.left=""+d.left+"px",e.top=""+d.top+"px",e.width=""+d.width+"px",e.height=""+d.height+"px",e.zIndex=this.zIndex;if(typeof c=="object")for(var f in c)e[f]=c[f];b.appendChild(this.div),this.div.innerHTML=this.getHTML(d.width,d.height)},getHTML:function(a,b){var c="",d="id="+this.id+"&width="+a+"&height="+b;if(navigator.userAgent.match(/MSIE/)){var e=location.href.match(/^https/i)?"https://":"http://";c+='<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="'+e+'download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="'+a+'" height="'+b+'" id="'+this.movieId+'" align="middle"><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="false" /><param name="movie" value="'+ZeroClipboard.moviePath+'" /><param name="loop" value="false" /><param name="menu" value="false" /><param name="quality" value="best" /><param name="bgcolor" value="#ffffff" /><param name="flashvars" value="'+d+'"/><param name="wmode" value="transparent"/></object>'}else c+='<embed id="'+this.movieId+'" src="'+ZeroClipboard.moviePath+'" loop="false" menu="false" quality="best" bgcolor="#ffffff" width="'+a+'" height="'+b+'" name="'+this.movieId+'" align="middle" allowScriptAccess="always" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="'+d+'" wmode="transparent" />';return c},hide:function(){this.div&&(this.div.style.left="-2000px")},show:function(){this.reposition()},destroy:function(){if(this.domElement&&this.div){this.hide(),this.div.innerHTML="";var a=document.getElementsByTagName("body")[0];try{a.removeChild(this.div)}catch(b){}this.domElement=null,this.div=null}},reposition:function(a){a&&(this.domElement=ZeroClipboard.$(a),this.domElement||this.hide());if(this.domElement&&this.div){var b=ZeroClipboard.getDOMObjectPosition(this.domElement),c=this.div.style;c.left=""+b.left+"px",c.top=""+b.top+"px"}},setText:function(a){this.clipText=a,this.ready&&this.movie.setText(a)},addEventListener:function(a,b){a=a.toString().toLowerCase().replace(/^on/,""),this.handlers[a]||(this.handlers[a]=[]),this.handlers[a].push(b)},setHandCursor:function(a){this.handCursorEnabled=a,this.ready&&this.movie.setHandCursor(a)},setCSSEffects:function(a){this.cssEffects=!!a},receiveEvent:function(a,b){a=a.toString().toLowerCase().replace(/^on/,"");switch(a){case"load":this.movie=document.getElementById(this.movieId);if(!this.movie){var c=this;setTimeout(function(){c.receiveEvent("load",null)},1);return}if(!this.ready&&navigator.userAgent.match(/Firefox/)&&navigator.userAgent.match(/Windows/)){var c=this;setTimeout(function(){c.receiveEvent("load",null)},100),this.ready=!0;return}this.ready=!0,this.movie.setText(this.clipText),this.movie.setHandCursor(this.handCursorEnabled);break;case"mouseover":this.domElement&&this.cssEffects&&(this.domElement.addClass("hover"),this.recoverActive&&this.domElement.addClass("active"));break;case"mouseout":this.domElement&&this.cssEffects&&(this.recoverActive=!1,this.domElement.hasClass("active")&&(this.domElement.removeClass("active"),this.recoverActive=!0),this.domElement.removeClass("hover"));break;case"mousedown":this.domElement&&this.cssEffects&&this.domElement.addClass("active");break;case"mouseup":this.domElement&&this.cssEffects&&(this.domElement.removeClass("active"),this.recoverActive=!1)}if(this.handlers[a])for(var d=0,e=this.handlers[a].length;d<e;d++){var f=this.handlers[a][d];typeof f=="function"?f(this,b):typeof f=="object"&&f.length==2?f[0][f[1]](this,b):typeof f=="string"&&window[f](this,b)}}},typeof module!="undefined"&&(module.exports=ZeroClipboard);
}
ZeroClipboard.setMoviePath(ed2klsPath+"/ZeroClipboard.swf");

/*
eD2k Link Selector Main JavaScript
by tomchen1989
http://emulefans.com/wordpress-ed2k-link-selector/
GPL v2
*/
var ed2kls = {

	$: function(id) {
		return document.getElementById(id);
	},

	$n: function(name) {
		return document.getElementsByName(name);
	},

	$c: function(classname, tag, parentEl) {//compatible getElementsByClassName
		parentEl = parentEl || document;
		tag = tag || "*";
		var clsels = [];
		if (document.getElementsByClassName && Array.filter){
			if (tag == "*") {
				return parentEl.getElementsByClassName(classname);
			} else {
				clsels = Array.filter(parentEl.getElementsByClassName(classname), function(els){
					return els.tagName == tag.toUpperCase();
				});
			}
		} else {
			var allels = parentEl.getElementsByTagName(tag);
			var pattern = new RegExp("(^|\\s)" + classname + "(\\s|$)");
			for (i = 0, j = 0, l = allels.length; i < l; i++) {
				if ( pattern.test(allels[i].className) ) {
					clsels[j] = allels[i];
					j++;
				}
			}
		}
		return clsels;
	},

	bind: function(obj, evtType, fn) {//compatible addEventListener
		if (obj.addEventListener) {//W3C
			obj.addEventListener(evtType, fn, false);
		} else if (obj.attachEvent) {//IE<9
			obj.attachEvent("on" + evtType, function (e) {
				fn.call(obj, e);//make "this" keyword refer to the obj
			});
		}
	},

	ht: function(parent, val) {//a light function to quickly modify innerHTML text of the "parent" element to "val"
		for ( var mynode = parent.firstChild; mynode !== null; mynode = mynode.nextSibling ) {
			if (mynode.nodeType == 3) {
				mynode.nodeValue = val;
				return true;
			}
		}
		return false;
	},

	help: function(no, subno) {
		var info = ed2kls.$("el-s-info-" + no);
		var cont = ed2kls.$("el-s-info-content-" + no);
		cont.innerHTML = "";
		var contStr = ed2kls.$("el-s-info-content-str-" + subno).cloneNode(true);
		contStr.id = "";
		cont.appendChild(contStr);
		if (info.style.display == "none") {
			if (typeof jQuery == "undefined") {
				info.style.display = "block";
			} else {
				jQuery(info).slideDown(300);
			}
		}
	},

	closeinfo: function(no) {
		if (typeof jQuery == "undefined") {
			ed2kls.$("el-s-info-" + no).style.display = "none";
		} else {
			jQuery(ed2kls.$("el-s-info-" + no)).slideUp(300);
		}
	},

	close: function(no) {
		var tb = ed2kls.$("el-s-tb-" + no);
		var exd = ed2kls.$("el-s-exd-" + no);
		if (tb.style.display == "none") {
			ed2kls.ht(exd, "[-]");
			if (typeof jQuery != "undefined" && !jQuery.browser.msie) {
				jQuery(tb).fadeIn("slow");
			} else {
				var rslt = navigator.appVersion.match(/MSIE (\d+\.\d+)/, "");
				if ( rslt !== null && Number(rslt[1]) <= 8.0 ) {
					tb.style.display = "block";
				} else {
					tb.style.display = "table-row-group";
				}
			}
			exd.setAttribute("title", ed2klsVar.shk);
		} else {
			ed2kls.ht(exd, "[+]");
			if (typeof jQuery != "undefined" && !jQuery.browser.msie) {
				jQuery(tb).fadeOut("slow");
			} else {
				tb.style.display = "none";
			}
			exd.setAttribute("title", ed2klsVar.exd);
		}
	},

	cb: {//clipboard

		main: function(type, no) {
			var clip = new ZeroClipboard.Client();
			clip.movieId="Ed2klsZeroClipboardMovie_" + clip.id;
			var load = false;
			clip.setHandCursor(true);
			clip.addEventListener("load", function(client) {
				load = true;
			});
			clip.addEventListener("mouseOver", function(client) {
				var a = ed2kls.$n("el-s-chkbx-" + no + "[]");
				var n = a.length;
				var txt = "";
				for (var i = 0; i < n; i++)	{
					if (a[i].checked) {
						if (type == 1) {//name
							txt += ed2kls.getName(a[i].value) + "\n";
						} else if (type == 2) {//link
							txt += a[i].value + "\n";
						}
					}
				}
				clip.setText(txt);
			});
			clip.addEventListener("complete", function(client, text) {
				var notespan = ed2kls.$("el-s-copied-" + no);
				if (!load) {
					alert(ed2klsVar.retry);
				} else {
					notespan.style.display = "inline";
					window.setTimeout(function(){
						notespan.style.display = "none";
					}, 1000);
				}
			});
			if (type == 1) {
				clip.glue("el-s-copynames-" + no, "el-s-copynames-container-" + no);
			} else if (type == 2) {
				clip.glue("el-s-copylinks-" + no, "el-s-copylinks-container-" + no);
			}
		},

		init: function() {
			var ed2ks = ed2kls.$c("el-s-copylinks", "input");
			var n = ed2ks.length;
			var no;
			for (var i = 0; i < n; i++)	{
				no = ed2ks[i].id;
				no = no.substr(no.lastIndexOf("-")+1);
				if (ed2kls.$("el-s-totsize-" + no)) {
					this.main(1, no);
				}
				this.main(2, no);
			}
		},

		exe: function() {
			ed2kls.bind(window, "load", function() {
				ed2kls.cb.init();
			});
		},

		noflashcopy: function(type, no) {
			if (window.clipboardData) {
				var a = ed2kls.$n("el-s-chkbx-" + no + "[]");
				var n = a.length;
				var txt = "";
				for (var i = 0; i < n; i++)	{
					if (a[i].checked) {
						if (type == 1) {//name
							txt += ed2kls.getName(a[i].value) + "\n";
						} else if (type == 2) {//link
							txt += a[i].value + "\n";
						}
					}
				}
				window.clipboardData.setData("Text",txt);
				var notespan = ed2kls.$("el-s-copied-" + no);
				notespan.style.display = "inline";
				window.setTimeout(function(){
					notespan.style.display = "none";
				}, 1000);
			} else {
				this.init();
				alert(ed2klsVar.retry);
			}
		}

	},

	formatSize: function(val, no) {
		var sep = 100;
		var unit = ed2klsVar.bytes;
		if (val >= 1099511627776) {
			val = Math.round( val / (1099511627776/sep) ) / sep;
			unit  = ed2klsVar.tb;
		} else if (val >= 1073741824) {
			val = Math.round( val / (1073741824/sep) ) / sep;
			unit  = ed2klsVar.gb;
		} else if (val >= 1048576) {
			val = Math.round( val / (1048576/sep) ) / sep;
			unit  = ed2klsVar.mb;
		} else if (val >= 1024) {
			val = Math.round( val / (1024/sep) ) / sep;
			unit  = ed2klsVar.kb;
		}
		return val + unit;
	},

	getName: function(url) {
		var name;
		name = decodeURIComponent(url);
		name = name.split("|")[2];
		return name;
	},

	getSize: function(url) {
		var size;
		size = decodeURIComponent(url);
		size = +size.split("|")[3];
		return size;
	},

	clear: function(no) {
		if (ed2kls.$("el-s-namefilter-" + no)) {
			ed2kls.$("el-s-namefilter-" + no).value = "";
			var chkts = ed2kls.$n("el-s-chktype-" + no + "[]");
			var n = chkts.length;
			for (var i=0; i<n; i++) {
				chkts[i].checked = false;
			}
		}
		if (ed2kls.$("el-s-sizesymbol-" + no + "-1")) {
			ed2kls.$("el-s-sizesymbol-" + no + "-1").selectedIndex = 0;
			ed2kls.$("el-s-sizefilter-" + no + "-1").value = "";
			ed2kls.$("el-s-sizeunit-" + no + "-1").selectedIndex = 0;
			ed2kls.$("el-s-sizesymbol-" + no + "-2").selectedIndex = 0;
			ed2kls.$("el-s-sizefilter-" + no + "-2").value = "";
			ed2kls.$("el-s-sizeunit-" + no + "-2").selectedIndex = 0;
		}
	},

	total: function(no) {
		var isfile = (ed2kls.$("el-s-totsize-" + no)) ? true : false;
		var a = ed2kls.$n("el-s-chkbx-" + no + "[]");
		var n = a.length;
		var totsize = 0;
		var totnum = 0;
		var chkall = ed2kls.$("el-s-chkall-" + no);
		for (var i=0; i<n; i++)	{
			if (a[i].checked) {
			if (isfile) {
				totsize += this.getSize(a[i].value);
			}
			totnum++;
			}
		}
		chkall.checked = (totnum == n) ? true : false;
		if (isfile) {
			ed2kls.ht(ed2kls.$("el-s-totsize-" + no), this.formatSize(totsize, no));
		}
		ed2kls.ht(ed2kls.$("el-s-totnum-" + no), totnum);
	},

	initChk: -1,

	checkIt: function(no,event) {
		var evt = event || window.event;
		var target = evt.target || evt.srcElement;
		tarNum = target.id;
		tarNum = tarNum.substr(tarNum.lastIndexOf("-")+1);
		tarNum = tarNum - 1;
		if (!evt.shiftKey) {
			this.initChk = tarNum;
		} else {
			var cks = ed2kls.$n("el-s-chkbx-" + no + "[]");
			var low = Math.min(tarNum, this.initChk);
			var high = Math.max(tarNum, this.initChk);
			for(var i = low+1; i <= high-1; i++){
				cks[i].checked = cks[i].checked? false : true;
			}
		}
		this.total(no);
		this.clear(no);
	},

	checkAll: function(no,chked) {
		var a = ed2kls.$n("el-s-chkbx-" + no + "[]");
		var n = a.length;
		for (var i=0; i<n; i++){
			a[i].checked = chked;
		}
		this.total(no);
		this.clear(no);
	},

	download: function(no){
		var a = ed2kls.$n("el-s-chkbx-" + no + "[]");
		var n = a.length;
		var linkarr = [];
		for (var i=0; i<n; i++) {
			if (a[i].checked) {
				linkarr.push(a[i].value);
			}
		}
		var l = linkarr.length;
		if (l===0) {
			return false;
		}
		var timeout = 6000;
		var j = 0;
		var run = function(){
			window.location = linkarr[j];
			if (j < l-1) {
				j++;
				window.setTimeout(function(){
					timeout = 500;
					run();
				}, timeout);
			}
		};
		run();
	},

	test: function(reg, text){
		if ( reg === "" || reg === undefined || text === "" ) { return true; }
		reg = reg.replace(/\"(.+?)\"/g, function(word) {
			word = word.substr(1,word.length-2);
			word = word.replace(/\s/g,"\\u0020").replace(/\+/g,"\\u002B").replace(/-/g,"\\u002D").replace(/\|/g,"\\u007C").replace(/\^/g,"\\u005E").replace(/\$/g,"\\u0024");
			return word;
		});
		reg = reg.replace(/[\s\+)]*\-/g, " -").replace(/\|\s-/g, "|-").replace(/([\\\.\{\}\[\]\(\)\*\+\?])/g, "\\$1").replace(/\\(\\u[0-9A-F]{4})/g, "$1");
		var arr = reg.split(/[\s\+]+/);
		for (var i=0, l=arr.length; i<l; i++) {
			var me = arr[i];
			if ( me !== "" ) {
				if (/\|/.test(me)) {
					var subarr = me.split("|");
					var is = false;
					for (var j=0, jl=subarr.length; j<jl; j++) {
						var subme = subarr[j];
						if ( subme !== "" ) {
							if ( ( subme.charAt(0) != "-" && new RegExp(subme, "i").test(text) === true ) || ( subme.charAt(0) == "-" && new RegExp(subme.substr(1,subme.length-1), "i").test(text) === false ) ) {
								is = true;
							}
						}
					}
					if (is === false) {
						return false;
					}
				} else if ( ( me.charAt(0) != "-" && new RegExp(me, "i").test(text) === false) || ( me.charAt(0) == "-" && new RegExp(me.substr(1,me.length-1), "i").test(text) === true) ) {
					return false;
				}
			}
		}
		return true;
	},

	testSize: function(size, symbol, filter, unit){
		if ( filter === "" || filter === undefined ) {
			return true;
		} else {
			var str = filter*unit;
			switch(symbol) {
				case "1":
					if (size > str) {
						return true;
					}
					break;
				case "2":
					if (size < str) {
						return true;
					}
					break;
				default:
					return true;
			}
			return false;
		}
	},

	filter: function(no){
		this.filterRun(no);
		this.setChktype(no);
		this.total(no);
	},

	filterRun: function(no){
		var namefilter, str, sizesymbol1, sizefilter1, sizeunit1, sizesymbol2, sizefilter2, sizeunit2;
		if (ed2kls.$("el-s-namefilter-" + no)) {
			namefilter = ed2kls.$("el-s-namefilter-" + no);
			str = namefilter.value;
		}
		if (ed2kls.$("el-s-sizesymbol-" + no + "-1")) {
			sizesymbol1 = ed2kls.$("el-s-sizesymbol-" + no + "-1");
			sizesymbol1 = sizesymbol1.options[sizesymbol1.selectedIndex].value;
			sizefilter1 = ed2kls.$("el-s-sizefilter-" + no + "-1").value;
			sizeunit1 = ed2kls.$("el-s-sizeunit-" + no + "-1");
			sizeunit1 = sizeunit1.options[sizeunit1.selectedIndex].value;
			sizesymbol2 = ed2kls.$("el-s-sizesymbol-" + no + "-2");
			sizesymbol2 = sizesymbol2.options[sizesymbol2.selectedIndex].value;

			sizefilter2 = ed2kls.$("el-s-sizefilter-" + no + "-2").value;
			sizeunit2 = ed2kls.$("el-s-sizeunit-" + no + "-2");
			sizeunit2 = sizeunit2.options[sizeunit2.selectedIndex].value;
		}

		var a = ed2kls.$n("el-s-chkbx-" + no + "[]");
		var n = a.length;
		for (var i=0; i<n; i++) {
			a[i].checked = (
				this.test(str, this.getName(a[i].value)) &&
				this.testSize(this.getSize(a[i].value), sizesymbol1, sizefilter1, sizeunit1) &&
				this.testSize(this.getSize(a[i].value), sizesymbol2, sizefilter2, sizeunit2)
			)? true : false;
		}
	},

	setChktype: function(no){
		var namefilterInput = ed2kls.$("el-s-namefilter-" + no);
		if (namefilterInput) {
			var chkts = ed2kls.$n("el-s-chktype-" + no + "[]");
			var query = ed2kls.$("el-s-namefilter-" + no).value;
			var n = chkts.length;
			var myextreg, str;
			for (var i=0; i<n; i++) {
				str = chkts[i].value;
				myextreg = new RegExp("\\." + str +"\\$", "i");
				chkts[i].checked = myextreg.test(query) ? true : false;
			}
		}
	},

	typeFilter: function(no, str, chked){
		var nfbox = ed2kls.$("el-s-namefilter-" + no);
		var extreg = /(\.\S+?\$)(\|\.\S+?\$)*/i;
		var myextreg = new RegExp("\\|\\." + str + "\\$|\\." + str + "\\$\\||\\." + str +"\\$", "i");
		nfbox.value = nfbox.value.replace(myextreg, "");
		if (chked) {
			if (extreg.test(nfbox.value)) {
				nfbox.value = nfbox.value.replace(extreg, "$&|." + str + "$");
			} else {
				if (nfbox.value !== "" && nfbox.value.substr(nfbox.value.length-1, 1) !== " " ) {
					nfbox.value += " ";
				}
				nfbox.value += "." + str + "$";
			}
		}
		this.filterRun(no);
		this.total(no);
	},

	emclChk: function(no){
		var a = ed2kls.$n("el-s-chkbx-" + no + "[]");
		var n = a.length;
		for (var i=0; i<n; i++) {
			if (a[i].checked) {
				return true;
			}
		}
		return false;
	},

	exe: function(){
		this.bind(window, "load", ed2kls.init);
	},

	init: function(){
		var els = ed2kls.$c("el-s-namefilter", "input").concat(ed2kls.$c("el-s-sizefilter", "input"));
		for (var i=0, l=els.length; i<l; i++) {
			els[i].value = "";
		}
		var chks = ed2kls.$c("el-s-chkbx", "input");
		for (var j=0, jl=chks.length; j<jl; j++) {
			var mychk = chks[j];
			if (/el-s-chktype/.test(mychk.className)) {
				mychk.checked = false;
			} else {
				mychk.checked = true;
			}
		}
	}

};

ed2kls.cb.exe();
ed2kls.exe();