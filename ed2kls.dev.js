// ed2k link selector javascript
// by tomchen1989
// http://emule-fans.com/wordpress-ed2k-link-selector/
// CC-BY-NC-SA

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

	help: function(no, subno) {
		var info = ed2kls.$("el-s-info-" + no);
		if (info.style.display == "none") {
			if (typeof jQuery == 'undefined') {
				info.style.display = "block";
			} else {
				jQuery(info).slideDown(300);
			}
		}
		var cont = ed2kls.$c("el-s-info-content", "div", info);
		for (var i = 0, l = cont.length; i < l; i++) {
			cont[i].style.display = (i==subno)? "block" : "none";
		}
		this.cb.init();
	},

	closeinfo: function(no) {
		if (typeof jQuery == 'undefined') {
			ed2kls.$("el-s-info-" + no).style.display = "none";
		} else {
			jQuery(ed2kls.$("el-s-info-" + no)).slideUp(300);
		}
		this.cb.init();
	},

	close: function(no) {
		var tb = ed2kls.$("el-s-tb-" + no);
		var exd = ed2kls.$("el-s-exd-" + no);
		if (typeof jQuery != 'undefined' && !jQuery.browser.msie) {
			tb = jQuery(tb);
			if (tb.css("display") == "none") {
				tb.fadeIn("slow");
				jQuery(exd).html("[-]");
				jQuery(exd).attr("title", ed2klsStr.shk[no]);
			} else {
				tb.fadeOut("slow");
				jQuery(exd).html("[+]");
				jQuery(exd).attr("title", ed2klsStr.exd[no]);
			}
		} else {
			if (tb.style.display == "none") {
				tb.style.display = "table-row-group";
				exd.innerHTML = "[-]";
				exd.setAttribute("title", ed2klsStr.shk[no]);
			} else {
				tb.style.display = "none";
				exd.innerHTML = "[+]";
				exd.setAttribute("title", ed2klsStr.exd[no]);
			}
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
					alert(ed2klsStr.retry[no]);
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
				alert(ed2klsStr.retry[no]);
			}
		}

	},

	formatSize: function(val, no) {
		var sep = 100;
		var unit = ed2klsStr.bytes[no];
		if (val >= 1099511627776) {
			val = Math.round( val / (1099511627776/sep) ) / sep;
			unit  = ed2klsStr.tb[no];
		} else if (val >= 1073741824) {
			val = Math.round( val / (1073741824/sep) ) / sep;
			unit  = ed2klsStr.gb[no];
		} else if (val >= 1048576) {
			val = Math.round( val / (1048576/sep) ) / sep;
			unit  = ed2klsStr.mb[no];
		} else if (val >= 1024) {
			val = Math.round( val / (1024/sep) ) / sep;
			unit  = ed2klsStr.kb[no];
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
		var isfile = (ed2kls.$("el-s-totsize-" + no))? true : false;
		var a = ed2kls.$n("el-s-chkbx-" + no + "[]");
		var n = a.length;
		if (isfile) {
			var totsize = 0;
		}
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
			ed2kls.$("el-s-totsize-" + no).innerHTML = this.formatSize(totsize, no);
		}
		ed2kls.$("el-s-totnum-" + no).innerHTML = totnum;
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
		if ( filter === "" ) {
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
		if (ed2kls.$("el-s-namefilter-" + no)) {
			var namefilter = ed2kls.$("el-s-namefilter-" + no);
			var str = namefilter.value;
		}
		if (ed2kls.$("el-s-sizesymbol-" + no + "-1")) {
			var sizesymbol1 = ed2kls.$("el-s-sizesymbol-" + no + "-1");
			sizesymbol1 = sizesymbol1.options[sizesymbol1.selectedIndex].value;
			var sizefilter1 = ed2kls.$("el-s-sizefilter-" + no + "-1").value;
			var sizeunit1 = ed2kls.$("el-s-sizeunit-" + no + "-1");
			sizeunit1 = sizeunit1.options[sizeunit1.selectedIndex].value;
			var sizesymbol2 = ed2kls.$("el-s-sizesymbol-" + no + "-2");
			sizesymbol2 = sizesymbol2.options[sizesymbol2.selectedIndex].value;

			var sizefilter2 = ed2kls.$("el-s-sizefilter-" + no + "-2").value;
			var sizeunit2 = ed2kls.$("el-s-sizeunit-" + no + "-2");
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
		var chkts = ed2kls.$n("el-s-chktype-" + no + "[]");
		var query = ed2kls.$("el-s-namefilter-" + no).value;
		var n = chkts.length;
		var myextreg, str;
		for (var i=0; i<n; i++) {
			str = chkts[i].value;
			myextreg = new RegExp("\\." + str +"\\$", "i");
			chkts[i].checked = myextreg.test(query) ? true : false;
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
				if (nfbox.value !== "" && nfbox.value.substr(nfbox.value.length-1, 1) != " " ) {
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
		var chkalls = ed2kls.$c('el-s-chkall', "input");
		for (var j=0, jl=chkalls.length; j<jl; j++) {
			chkalls[j].checked = true;
		}
	}

};

try {
	ed2kls.cb.exe();
	ed2kls.exe();
} catch (a) {
//	alert(a);
}