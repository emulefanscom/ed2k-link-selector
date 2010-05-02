// Simple Set Clipboard System
// Author: Joseph Huckaby
// http://code.google.com/p/zeroclipboard/
var ZeroClipboard={version:"1.0.7",clients:{},moviePath:ed2klsPath+"/ZeroClipboard.swf",nextId:1,$:function(d){if(typeof(d)=='string')d=document.getElementById(d);if(!d.addClass){d.hide=function(){this.style.display='none'};d.show=function(){this.style.display=''};d.addClass=function(a){this.removeClass(a);this.className+=' '+a};d.removeClass=function(a){var b=this.className.split(/\s+/);var c=-1;for(var k=0;k<b.length;k++){if(b[k]==a){c=k;k=b.length}}if(c>-1){b.splice(c,1);this.className=b.join(' ')}return this};d.hasClass=function(a){return!!this.className.match(new RegExp("\\s*"+a+"\\s*"))}}return d},setMoviePath:function(a){this.moviePath=a},dispatch:function(a,b,c){var d=this.clients[a];if(d){d.receiveEvent(b,c)}},register:function(a,b){this.clients[a]=b},getDOMObjectPosition:function(a,b){var c={left:0,top:0,width:a.width?a.width:a.offsetWidth,height:a.height?a.height:a.offsetHeight};while(a&&(a!=b)){c.left+=a.offsetLeft;c.top+=a.offsetTop;a=a.offsetParent}return c},Client:function(a){this.handlers={};this.id=ZeroClipboard.nextId++;this.movieId='ZeroClipboardMovie_'+this.id;ZeroClipboard.register(this.id,this);if(a)this.glue(a)}};ZeroClipboard.Client.prototype={id:0,ready:false,movie:null,clipText:'',handCursorEnabled:true,cssEffects:true,handlers:null,glue:function(a,b,c){this.domElement=ZeroClipboard.$(a);var d=99;if(this.domElement.style.zIndex){d=parseInt(this.domElement.style.zIndex,10)+1}if(typeof(b)=='string'){b=ZeroClipboard.$(b)}else if(typeof(b)=='undefined'){b=document.getElementsByTagName('body')[0]}var e=ZeroClipboard.getDOMObjectPosition(this.domElement,b);this.div=document.createElement('div');var f=this.div.style;f.position='absolute';f.left=''+e.left+'px';f.top=''+e.top+'px';f.width=''+e.width+'px';f.height=''+e.height+'px';f.zIndex=d;if(typeof(c)=='object'){for(addedStyle in c){f[addedStyle]=c[addedStyle]}}b.appendChild(this.div);this.div.innerHTML=this.getHTML(e.width,e.height)},getHTML:function(a,b){var c='';var d='id='+this.id+'&width='+a+'&height='+b;if(navigator.userAgent.match(/MSIE/)){var e=location.href.match(/^https/i)?'https://':'http://';c+='<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="'+e+'download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="'+a+'" height="'+b+'" id="'+this.movieId+'" align="middle"><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="false" /><param name="movie" value="'+ZeroClipboard.moviePath+'" /><param name="loop" value="false" /><param name="menu" value="false" /><param name="quality" value="best" /><param name="bgcolor" value="#ffffff" /><param name="flashvars" value="'+d+'"/><param name="wmode" value="transparent"/></object>'}else{c+='<embed id="'+this.movieId+'" src="'+ZeroClipboard.moviePath+'" loop="false" menu="false" quality="best" bgcolor="#ffffff" width="'+a+'" height="'+b+'" name="'+this.movieId+'" align="middle" allowScriptAccess="always" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="'+d+'" wmode="transparent" />'}return c},hide:function(){if(this.div){this.div.style.left='-2000px'}},show:function(){this.reposition()},destroy:function(){if(this.domElement&&this.div){this.hide();this.div.innerHTML='';var a=document.getElementsByTagName('body')[0];try{a.removeChild(this.div)}catch(e){}this.domElement=null;this.div=null}},reposition:function(a){if(a){this.domElement=ZeroClipboard.$(a);if(!this.domElement)this.hide()}if(this.domElement&&this.div){var b=ZeroClipboard.getDOMObjectPosition(this.domElement);var c=this.div.style;c.left=''+b.left+'px';c.top=''+b.top+'px'}},setText:function(a){this.clipText=a;if(this.ready)this.movie.setText(a)},addEventListener:function(a,b){a=a.toString().toLowerCase().replace(/^on/,'');if(!this.handlers[a])this.handlers[a]=[];this.handlers[a].push(b)},setHandCursor:function(a){this.handCursorEnabled=a;if(this.ready)this.movie.setHandCursor(a)},setCSSEffects:function(a){this.cssEffects=!!a},receiveEvent:function(a,b){a=a.toString().toLowerCase().replace(/^on/,'');switch(a){case'load':this.movie=document.getElementById(this.movieId);if(!this.movie){var c=this;setTimeout(function(){c.receiveEvent('load',null)},1);return}if(!this.ready&&navigator.userAgent.match(/Firefox/)&&navigator.userAgent.match(/Windows/)){var c=this;setTimeout(function(){c.receiveEvent('load',null)},100);this.ready=true;return}this.ready=true;this.movie.setText(this.clipText);this.movie.setHandCursor(this.handCursorEnabled);break;case'mouseover':if(this.domElement&&this.cssEffects){this.domElement.addClass('hover');if(this.recoverActive)this.domElement.addClass('active')}break;case'mouseout':if(this.domElement&&this.cssEffects){this.recoverActive=false;if(this.domElement.hasClass('active')){this.domElement.removeClass('active');this.recoverActive=true}this.domElement.removeClass('hover')}break;case'mousedown':if(this.domElement&&this.cssEffects){this.domElement.addClass('active')}break;case'mouseup':if(this.domElement&&this.cssEffects){this.domElement.removeClass('active');this.recoverActive=false}break}if(this.handlers[a]){for(var d=0,len=this.handlers[a].length;d<len;d++){var e=this.handlers[a][d];if(typeof(e)=='function'){e(this,b)}else if((typeof(e)=='object')&&(e.length==2)){e[0][e[1]](this,b)}else if(typeof(e)=='string'){window[e](this,b)}}}}};
/*
ed2k link selector javascript
by tomchen1989
http://emule-fans.com/wordpress-ed2k-link-selector/
CC-BY-NC-SA
*/
var ed2kls = {

	$: function(id) {
		return document.getElementById(id);
	},

	$n: function(name) {
		return document.getElementsByName(name);
	},

	$c: function(classname, tag, parentEl) {
		parentEl = parentEl || document;
		tag = tag || "*";
		var clsels = [];
		if (document.getElementsByClassName && Array.filter){
			clsels = Array.filter(parentEl.getElementsByClassName(classname), function(els){
				return els.tagName == tag.toUpperCase();
			});
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

	ht: function(parent, val) {
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
		this.cb.init();
	},

	closeinfo: function(no) {
		if (typeof jQuery == "undefined") {
			ed2kls.$("el-s-info-" + no).style.display = "none";
		} else {
			jQuery(ed2kls.$("el-s-info-" + no)).slideUp(300);
		}
		this.cb.init();
	},

	close: function(no) {
		var tb = ed2kls.$("el-s-tb-" + no);
		var exd = ed2kls.$("el-s-exd-" + no);
		if (tb.style.display == "none") {
			ed2kls.ht(exd, "[-]");
			if (typeof jQuery != 'undefined' && !jQuery.browser.msie) {
				jQuery(tb).fadeIn("slow");
			} else {
				var rslt = navigator.appVersion.match(/MSIE (\d+\.\d+)/,'');
				if ( rslt !== null && Number(rslt[1]) <= 7.0 ) {
					tb.style.display = "table-row-group";
				} else {
					tb.style.display = "block";
				}
			}
			exd.setAttribute("title", ed2klsVar.shk);
		} else {
			ed2kls.ht(exd, "[+]");
			if (typeof jQuery != 'undefined' && !jQuery.browser.msie) {
				jQuery(tb).fadeOut("slow");
			} else {
				tb.style.display = "none";
			}
			exd.setAttribute("title", ed2klsVar.exd);
		}
		this.cb.init();
	},

	cb: {

		main: function(type, no) {
			var clip = new ZeroClipboard.Client();
			var load = false;
			clip.setHandCursor( true );
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
				clip.glue("el-s-copynames-" + no);
			} else if (type == 2) {
				clip.glue("el-s-copylinks-" + no);
			}
		},

		init: function() {
			var cbebd = ed2kls.$c("ZeroClipboard_ED2k", "embed");
			for (var j = 0, l = cbebd.length; j < l; j++) {
				var me = cbebd[j].parentNode;
				me.parentNode.removeChild(me);
			}
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
			if (window.addEventListener) {
				window.addEventListener("load", function() {
					ed2kls.cb.init();
				}, false);
			}
		},

		iecopy: function(type, no) {
			if (!window.addEventListener) {
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
			ed2kls.$("el-s-sizesymbol-" + no + "-1").selectedIndex = 0;
			ed2kls.$("el-s-sizefilter-" + no + "-1").value = "";
			ed2kls.$("el-s-sizeunit-" + no + "-1").selectedIndex = 0;
			ed2kls.$("el-s-sizesymbol-" + no + "-2").selectedIndex = 0;
			ed2kls.$("el-s-sizefilter-" + no + "-2").value = "";
			ed2kls.$("el-s-sizeunit-" + no + "-2").selectedIndex = 0;
			var chkts = ed2kls.$n("el-s-chktype-" + no + "[]");
			var n = chkts.length;
			for (var i=0; i<n; i++) {
				chkts[i].checked = false;
			}
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
		if (window.addEventListener) {
			window.addEventListener("load", ed2kls.init, false);
		} else if (window.attachEvent) {
			window.attachEvent("onload", ed2kls.init);
		}
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