function md_vdoni_casata(){var k=window,j=document,s=k.external,t,p,o='',w={},F=[],C=[],n=[],z,B;if(!k.__gwt_stylesLoaded){k.__gwt_stylesLoaded={};}if(!k.__gwt_scriptsLoaded){k.__gwt_scriptsLoaded={};}function u(){try{return s&&(s.gwtOnLoad&&k.location.search.indexOf('gwt.hybrid')== -1);}catch(a){return false;}}
function v(){if(t&&p){t(z,'md.vdoni.casata',o);}}
function r(){var i,h;j.write('<script id="__gwt_marker_md.vdoni.casata"><\/script>');h=j.getElementById('__gwt_marker_md.vdoni.casata');if(h){i=h.previousSibling;}function d(b){var a=b.lastIndexOf('/');return a>=0?b.substring(0,a+1):'';}
;if(i&&i.src){o=d(i.src);}if(o==''){var c=j.getElementsByTagName('base');if(c.length>0){o=c[c.length-1].href;}else{var g=j.location;var e=g.href;o=d(e.substr(0,e.length-g.hash.length));}}else if(o.match(/^\w+:\/\//)){}else{var f=j.createElement('img');f.src=o+'clear.cache.gif';o=d(f.src);}if(h){h.parentNode.removeChild(h);}}
function A(){var f=document.getElementsByTagName('meta');for(var d=0,g=f.length;d<g;++d){var e=f[d],h=e.getAttribute('name'),b;if(h){if(h=='gwt:property'){b=e.getAttribute('content');if(b){var i,c=b.indexOf('=');if(c>=0){h=b.substring(0,c);i=b.substring(c+1);}else{h=b;i='';}w[h]=i;}}else if(h=='gwt:onPropertyErrorFn'){b=e.getAttribute('content');if(b){try{B=eval(b);}catch(a){alert('Bad handler "'+b+'" for "gwt:onPropertyErrorFn"');}}}else if(h=='gwt:onLoadErrorFn'){b=e.getAttribute('content');if(b){try{z=eval(b);}catch(a){alert('Bad handler "'+b+'" for "gwt:onLoadErrorFn"');}}}}}}
function m(a,b){return b in F[a];}
function l(a){var b=w[a];return b==null?null:b;}
function E(d,e){var a=n;for(var b=0,c=d.length-1;b<c;++b){a=a[d[b]]||(a[d[b]]=[]);}a[d[c]]=e;}
function q(d){var e=C[d](),b=F[d];if(e in b){return e;}var a=[];for(var c in b){a[b[c]]=c;}if(B){B(d,a,e);}throw null;}
C['user.agent']=function(){var d=navigator.userAgent.toLowerCase();var b=function(a){return parseInt(a[1])*1000+parseInt(a[2]);};if(d.indexOf('opera')!= -1){return 'opera';}else if(d.indexOf('webkit')!= -1){return 'safari';}else if(d.indexOf('msie')!= -1){var c=/msie ([0-9]+)\.([0-9]+)/.exec(d);if(c&&c.length==3){if(b(c)>=6000){return 'ie6';}}}else if(d.indexOf('gecko')!= -1){var c=/rv:([0-9]+)\.([0-9]+)/.exec(d);if(c&&c.length==3){if(b(c)>=1008)return 'gecko1_8';}return 'gecko';}return 'unknown';};F['user.agent']={'gecko':0,'gecko1_8':1,'ie6':2,'opera':3,'safari':4};md_vdoni_casata.onScriptLoad=function(a){md_vdoni_casata=null;t=a;v();};r();A();var y;function x(){if(!p){p=true;v();if(j.removeEventListener){j.removeEventListener('DOMContentLoaded',x,false);}if(y){clearInterval(y);}}}
if(j.addEventListener){j.addEventListener('DOMContentLoaded',x,false);}var y=setInterval(function(){if(/loaded|complete/.test(j.readyState)){x();}},50);var D;try{E(['gecko'],'1C83DBABD4A709C3D8F12095FDCA07C0');E(['opera'],'2BACB92379C6D8CEBF872582C60175E2');E(['ie6'],'A83006666C34E68B9F0A9CF6B694FDF3');E(['gecko1_8'],'D7870BC01AD17BB1107BB5A827B31EB0');E(['safari'],'DE1A2324718E6B2B91700B73AA617F7F');D=n[q('user.agent')];}catch(a){return;}D+='.cache.js';if(!__gwt_stylesLoaded['casata.css']){__gwt_stylesLoaded['casata.css']=true;document.write('<link rel="stylesheet" href="'+o+'casata.css">');}j.write('<script src="'+o+D+'"><\/script>');}
md_vdoni_casata.__gwt_initHandlers=function(i,e,j){var d=window,g=d.onresize,f=d.onbeforeunload,h=d.onunload;d.onresize=function(a){try{i();}finally{g&&g(a);}};d.onbeforeunload=function(a){var c,b;try{c=e();}finally{b=f&&f(a);}if(c!=null){return c;}if(b!=null){return b;}};d.onunload=function(a){try{j();}finally{h&&h(a);}};};md_vdoni_casata();