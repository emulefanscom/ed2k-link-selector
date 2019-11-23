/* eslint-disable no-undef */
// eslint-disable-next-line multiline-comment-style
/*!
 * eD2k Link Selector JavaScript
 * by Tom Chen
 * https://emulefans.com/wordpress-ed2k-link-selector/
 * GPL v2
 */

(function() {
  var $ = function(id) {
    return document.getElementById(id);
  };

  var $n = function(name) {
    return document.getElementsByName(name);
  };

  var $c = function(classname, tag, parentEl) {
    //compatible getElementsByClassName
    parentEl = parentEl || document;
    tag = tag || "*";
    var clsels = [];
    if (document.getElementsByClassName && Array.filter) {
      if (tag == "*") {
        return parentEl.getElementsByClassName(classname);
      } else {
        clsels = Array.filter(
          parentEl.getElementsByClassName(classname),
          function(els) {
            return els.tagName == tag.toUpperCase();
          }
        );
      }
    } else {
      var allels = parentEl.getElementsByTagName(tag);
      var pattern = new RegExp("(^|\\s)" + classname + "(\\s|$)");
      for (var i = 0, j = 0, l = allels.length; i < l; i++) {
        if (pattern.test(allels[i].className)) {
          clsels[j] = allels[i];
          j++;
        }
      }
    }
    return clsels;
  };

  var listen = function(obj, evtType, fn) {
    //compatible addEventListener
    if (obj === null) {
      return;
    }
    if (obj.addEventListener) {
      //W3C
      obj.addEventListener(evtType, fn, false);
    } else if (obj.attachEvent) {
      //IE<9
      obj.attachEvent("on" + evtType, function(e) {
        fn.call(obj, e); //make "this" keyword refer to the obj
      });
    }
  };

  var ht = function(parent, val) {
    //a light function to quickly modify innerHTML text of the "parent" element to "val"
    for (
      var mynode = parent.firstChild;
      mynode !== null;
      mynode = mynode.nextSibling
    ) {
      if (mynode.nodeType == 3) {
        mynode.nodeValue = val;
        return true;
      }
    }
    return false;
  };

  var help = function(no, subno) {
    var info = $("el-s-info-" + no);
    var cont = $("el-s-info-content-" + no);
    cont.innerHTML = "";
    var contStr = $("el-s-info-content-str-" + subno).cloneNode(true);
    contStr.id = "";
    cont.appendChild(contStr);
    if (info.style.display == "none") {
      if (typeof jQuery == "undefined") {
        info.style.display = "block";
      } else {
        jQuery(info).slideDown(300);
      }
    }
  };

  var closeinfo = function(no) {
    if (typeof jQuery == "undefined") {
      $("el-s-info-" + no).style.display = "none";
    } else {
      jQuery($("el-s-info-" + no)).slideUp(300);
    }
  };

  var close = function(no) {
    var tb = $("el-s-tb-" + no);
    var exd = $("el-s-exd-" + no);
    if (tb.style.display == "none") {
      ht(exd, "[-]");
      if (typeof jQuery != "undefined" && !jQuery.browser.msie) {
        jQuery(tb).fadeIn("slow");
      } else {
        var rslt = navigator.appVersion.match(/MSIE (\d+\.\d+)/, "");
        if (rslt !== null && Number(rslt[1]) <= 8.0) {
          tb.style.display = "block";
        } else {
          tb.style.display = "table-row-group";
        }
      }
      exd.setAttribute("title", ed2klsVar.shk);
    } else {
      ht(exd, "[+]");
      if (typeof jQuery != "undefined" && !jQuery.browser.msie) {
        jQuery(tb).fadeOut("slow");
      } else {
        tb.style.display = "none";
      }
      exd.setAttribute("title", ed2klsVar.exd);
    }
  };

  var formatSize = function(val) {
    var sep = 100;
    var unit = ed2klsVar.bytes;
    if (val >= 1099511627776) {
      val = Math.round(val / (1099511627776 / sep)) / sep;
      unit = ed2klsVar.tb;
    } else if (val >= 1073741824) {
      val = Math.round(val / (1073741824 / sep)) / sep;
      unit = ed2klsVar.gb;
    } else if (val >= 1048576) {
      val = Math.round(val / (1048576 / sep)) / sep;
      unit = ed2klsVar.mb;
    } else if (val >= 1024) {
      val = Math.round(val / (1024 / sep)) / sep;
      unit = ed2klsVar.kb;
    }
    return val + unit;
  };

  var getName = function(url) {
    var name;
    name = decodeURIComponent(url);
    name = name.split("|")[2];
    return name;
  };

  var getSize = function(url) {
    var size;
    size = decodeURIComponent(url);
    size = +size.split("|")[3];
    return size;
  };

  var clear = function(no) {
    if ($("el-s-namefilter-" + no)) {
      $("el-s-namefilter-" + no).value = "";
      var chkts = $n("el-s-chktype-" + no + "[]");
      forEach(chkts, function(chkt) {
        chkt.checked = false;
      });
    }
    if ($("el-s-sizesymbol-" + no + "-1")) {
      $("el-s-sizesymbol-" + no + "-1").selectedIndex = 0;
      $("el-s-sizefilter-" + no + "-1").value = "";
      $("el-s-sizeunit-" + no + "-1").selectedIndex = 0;
      $("el-s-sizesymbol-" + no + "-2").selectedIndex = 0;
      $("el-s-sizefilter-" + no + "-2").value = "";
      $("el-s-sizeunit-" + no + "-2").selectedIndex = 0;
    }
  };

  var total = function(no) {
    var isfile = $("el-s-totsize-" + no) ? true : false;
    var a = $n("el-s-chkbx-" + no + "[]");
    var n = a.length;
    var totsize = 0;
    var totnum = 0;
    var chkall = $("el-s-chkall-" + no);
    for (var i = 0; i < n; i++) {
      if (a[i].checked) {
        if (isfile) {
          totsize += getSize(a[i].value);
        }
        totnum++;
      }
    }
    chkall.checked = totnum == n ? true : false;
    if (isfile) {
      ht($("el-s-totsize-" + no), formatSize(totsize));
    }
    ht($("el-s-totnum-" + no), totnum);
  };

  var initChk = -1;

  var checkIt = function(no, event) {
    var evt = event || window.event;
    var target = evt.target || evt.srcElement;
    var tarNum = target.id;
    tarNum = tarNum.substr(tarNum.lastIndexOf("-") + 1);
    tarNum = tarNum - 1;
    if (!evt.shiftKey) {
      initChk = tarNum;
    } else {
      var cks = $n("el-s-chkbx-" + no + "[]");
      var low = Math.min(tarNum, initChk);
      var high = Math.max(tarNum, initChk);
      for (var i = low + 1; i <= high - 1; i++) {
        cks[i].checked = cks[i].checked ? false : true;
      }
    }
    total(no);
    clear(no);
  };

  var checkAll = function(no, chked) {
    var a = $n("el-s-chkbx-" + no + "[]");
    forEach(a, function(me) {
      me.checked = chked;
    });
    total(no);
    clear(no);
  };

  var download = function(no) {
    var a = $n("el-s-chkbx-" + no + "[]");
    var linkarr = [];
    forEach(a, function(me) {
      if (me.checked) {
        linkarr.push(me.value);
      }
    });
    var l = linkarr.length;
    if (l === 0) {
      return false;
    }
    var timeout = 6000;
    var j = 0;
    var run = function() {
      window.location = linkarr[j];
      if (j < l - 1) {
        j++;
        window.setTimeout(function() {
          timeout = 500;
          run();
        }, timeout);
      }
    };
    run();
  };

  var test = function(reg, text) {
    if (reg === "" || reg === undefined || text === "") {
      return true;
    }
    reg = reg.replace(/"(.+?)"/g, function(word) {
      word = word.substr(1, word.length - 2);
      word = word
        .replace(/\s/g, "\\u0020")
        .replace(/\+/g, "\\u002B")
        .replace(/-/g, "\\u002D")
        .replace(/\|/g, "\\u007C")
        .replace(/\^/g, "\\u005E")
        .replace(/\$/g, "\\u0024");
      return word;
    });
    reg = reg
      .replace(/[\s+)]*-/g, " -")
      .replace(/\|\s-/g, "|-")
      .replace(/([\\.{}[\]()*+?])/g, "\\$1")
      .replace(/\\(\\u[0-9A-F]{4})/g, "$1");
    var arr = reg.split(/[\s+]+/);
    for (var i = 0, l = arr.length; i < l; i++) {
      var me = arr[i];
      if (me !== "") {
        if (/\|/.test(me)) {
          var subarr = me.split("|");
          var is = false;
          for (var j = 0, jl = subarr.length; j < jl; j++) {
            var subme = subarr[j];
            if (subme !== "") {
              if (
                (subme.charAt(0) != "-" &&
                  new RegExp(subme, "i").test(text) === true) ||
                (subme.charAt(0) == "-" &&
                  new RegExp(subme.substr(1, subme.length - 1), "i").test(
                    text
                  ) === false)
              ) {
                is = true;
              }
            }
          }
          if (is === false) {
            return false;
          }
        } else if (
          (me.charAt(0) != "-" && new RegExp(me, "i").test(text) === false) ||
          (me.charAt(0) == "-" &&
            new RegExp(me.substr(1, me.length - 1), "i").test(text) === true)
        ) {
          return false;
        }
      }
    }
    return true;
  };

  var testSize = function(size, symbol, filter, unit) {
    if (filter === "" || filter === undefined) {
      return true;
    } else {
      var str = filter * unit;
      switch (symbol) {
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
  };

  var filter = function(no) {
    filterRun(no);
    setChktype(no);
    total(no);
  };

  var filterRun = function(no) {
    var namefilter,
      str,
      sizesymbol1,
      sizefilter1,
      sizeunit1,
      sizesymbol2,
      sizefilter2,
      sizeunit2;
    if ($("el-s-namefilter-" + no)) {
      namefilter = $("el-s-namefilter-" + no);
      str = namefilter.value;
    }
    if ($("el-s-sizesymbol-" + no + "-1")) {
      sizesymbol1 = $("el-s-sizesymbol-" + no + "-1");
      sizesymbol1 = sizesymbol1.options[sizesymbol1.selectedIndex].value;
      sizefilter1 = $("el-s-sizefilter-" + no + "-1").value;
      sizeunit1 = $("el-s-sizeunit-" + no + "-1");
      sizeunit1 = sizeunit1.options[sizeunit1.selectedIndex].value;
      sizesymbol2 = $("el-s-sizesymbol-" + no + "-2");
      sizesymbol2 = sizesymbol2.options[sizesymbol2.selectedIndex].value;

      sizefilter2 = $("el-s-sizefilter-" + no + "-2").value;
      sizeunit2 = $("el-s-sizeunit-" + no + "-2");
      sizeunit2 = sizeunit2.options[sizeunit2.selectedIndex].value;
    }
    var a = $n("el-s-chkbx-" + no + "[]");
    forEach(a, function(me) {
      me.checked =
        test(str, getName(me.value)) &&
        testSize(getSize(me.value), sizesymbol1, sizefilter1, sizeunit1) &&
        testSize(getSize(me.value), sizesymbol2, sizefilter2, sizeunit2)
          ? true
          : false;
    });
  };

  var setChktype = function(no) {
    var namefilterInput = $("el-s-namefilter-" + no);
    if (namefilterInput) {
      var chkts = $n("el-s-chktype-" + no + "[]");
      var query = $("el-s-namefilter-" + no).value;
      var myextreg, str;
      forEach(chkts, function(chkt) {
        str = chkt.value;
        myextreg = new RegExp("\\." + str + "\\$", "i");
        chkt.checked = myextreg.test(query) ? true : false;
      });
    }
  };

  var typeFilter = function(no, str, chked) {
    var nfbox = $("el-s-namefilter-" + no);
    var extreg = /(\.\S+?\$)(\|\.\S+?\$)*/i;
    var myextreg = new RegExp(
      "\\|\\." + str + "\\$|\\." + str + "\\$\\||\\." + str + "\\$",
      "i"
    );
    nfbox.value = nfbox.value.replace(myextreg, "");
    if (chked) {
      if (extreg.test(nfbox.value)) {
        nfbox.value = nfbox.value.replace(extreg, "$&|." + str + "$");
      } else {
        if (
          nfbox.value !== "" &&
          nfbox.value.substr(nfbox.value.length - 1, 1) !== " "
        ) {
          nfbox.value += " ";
        }
        nfbox.value += "." + str + "$";
      }
    }
    filterRun(no);
    total(no);
  };

  var copyToClipboard = function(str) {
    // IE10+ (maybe OK for IE9-)
    var el = document.createElement("textarea");
    el.value = str;
    el.setAttribute("readonly", "");
    el.style.position = "absolute";
    el.style.left = "-9999px";
    document.body.appendChild(el);
    var selected =
      document.getSelection().rangeCount > 0
        ? document.getSelection().getRangeAt(0)
        : false;
    el.select();
    document.execCommand("copy");
    document.body.removeChild(el);
    if (selected) {
      document.getSelection().removeAllRanges();
      document.getSelection().addRange(selected);
    }
  };

  var copy = function(no, type) {
    var a = $n("el-s-chkbx-" + no + "[]");
    var txt = "";
    forEach(a, function(me) {
      if (me.checked) {
        if (type == 1) {
          // name
          txt += getName(me.value) + "\n";
        } else if (type == 2) {
          // link
          txt += me.value + "\n";
        }
      }
    });
    copyToClipboard(txt);
  };

  var saveFile = function(data, fileName, type) {
    // IE10+
    var el = document.createElement("a");
    var blob = new Blob([data], { type: type });
    document.body.appendChild(el);
    el.style = "display: none";
    if (window.navigator.msSaveOrOpenBlob) {
      window.navigator.msSaveOrOpenBlob(blob, fileName);
    } else {
      var url = (window.URL || window.webkitURL).createObjectURL(blob);
      el.href = url;
      el.download = fileName;
      el.click();
      (window.URL || window.webkitURL).revokeObjectURL(url);
    }
    document.body.removeChild(el);
  };

  var emcl = function(no) {
    var a = $n("el-s-chkbx-" + no + "[]");
    var txt =
      "# eMule Collection (Simple Text Format). Created by eD2k Link Selector WordPress Plugin (emulefans.com)\n";
    forEach(a, function(me) {
      if (me.checked) {
        txt += me.value + "\n";
      }
    });
    saveFile(
      txt,
      getName(a[0].value) +
        "_" +
        new Date().toISOString().replace(/\D/g, "") +
        ".emulecollection",
      "application/octetstream"
    );
  };

  var reset = function() {
    var els = $c("el-s-namefilter", "input").concat(
      $c("el-s-sizefilter", "input")
    );
    forEach(els, function(el) {
      el.value = "";
    });
    var chks = $c("el-s-chkbx", "input");
    forEach(chks, function(chk) {
      if (/el-s-chktype/.test(chk.className)) {
        chk.checked = false;
      } else {
        chk.checked = true;
      }
    });
  };

  var init = function() {
    reset();
    var ed2ks = $c("el-s");

    forEach(ed2ks, function(ed2k) {
      var number = ed2k.id.match(/el-s-(\d+)/)[1];
      var elsChkall = $("el-s-chkall-" + number);

      listen(elsChkall, "click", function() {
        checkAll(number, elsChkall.checked);
      });
      listen($("el-s-help-" + number), "click", function() {
        help(number, 0);
      });
      listen($("el-s-namefilterhelp-" + number), "click", function() {
        help(number, 1);
      });
      listen($("el-s-sizefilterhelp-" + number), "click", function() {
        help(number, 2);
      });
      listen($("el-s-info-close-" + number), "click", function() {
        closeinfo(number);
      });
      listen($("el-s-exd-" + number), "click", function() {
        close(number);
      });
      listen($("el-s-namefilter-" + number), "keyup", function() {
        filter(number);
      });
      listen($("el-s-sizesymbol-" + number + "-1"), "change", function() {
        filter(number);
      });
      listen($("el-s-sizefilter-" + number + "-1"), "keyup", function() {
        filter(number);
      });
      listen($("el-s-sizeunit-" + number + "-1"), "change", function() {
        filter(number);
      });
      listen($("el-s-sizesymbol-" + number + "-2"), "change", function() {
        filter(number);
      });
      listen($("el-s-sizefilter-" + number + "-2"), "keyup", function() {
        filter(number);
      });
      listen($("el-s-sizeunit-" + number + "-2"), "change", function() {
        filter(number);
      });
      listen($("el-s-download-" + number), "click", function() {
        download(number);
      });
      listen($("el-s-copylinks-" + number), "click", function() {
        copy(number, 2);
      });
      listen($("el-s-copynames-" + number), "click", function() {
        copy(number, 1);
      });
      listen($("el-s-submit-" + number), "click", function() {
        emcl(number);
      });

      forEach($c("el-s-chkbx-ed2k-" + number, "input", ed2k), function(me) {
        listen(me, "click", function(event) {
          checkIt(number, event);
        });
      });

      forEach($c("el-s-chktype-" + number, "input", ed2k), function(me) {
        listen(me, "click", function() {
          typeFilter(number, me.value, me.checked);
        });
      });
    });
  };

  var forEach = function(els, func) {
    for (var i = 0, l = els.length; i < l; i++) {
      func(els[i], i, els);
    }
  };

  listen(window, "load", init);
})();
