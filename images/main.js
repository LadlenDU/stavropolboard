function $(id){return document.getElementById(id)}
var ind='<img hspace="20" src="'+servername+'images/load.gif">';
function check_fields_note(){var f=document.forms['note_form'];var allcheck=0;for(i=0;i<f.elements.length;i++){if(f.elements[i].type=='checkbox' && f.elements[i].checked== true)allcheck++;}
if(allcheck>0){return true;}
else{alert(alert_no_value);return false;}
}
function currency_converter(s,t){var req=new JsHttpRequest();req.onreadystatechange=function(){if(req.readyState==4)$("currency_converter").innerHTML=req.responseJS;else $('currency_converter').innerHTML=ind;}
req.caching=true;req.open(null,servername+"core/currency_converter.php",true);req.send({sum:s,type:t});}
function print_preview(n,c,r){$("informer_preview").style.display='block';var req=new JsHttpRequest();req.onreadystatechange=function(){if(req.readyState==4)$('informer_preview').innerHTML=req.responseJS;else $('informer_preview').innerHTML=ind;}
req.caching=true;req.open(null,servername+"core/informer_preview.php",true);req.send({r_n:n,r_c:c,r_r:r});}
function add_comments(id,autor,text){var req=new JsHttpRequest();req.onreadystatechange=function(){if(req.readyState==4)$("add_comments").innerHTML=req.responseJS;else $('add_comments').innerHTML=ind;}
req.open(null,servername+"core/add_comments.php",true);req.send({idmess:id,send_autor:autor,send_text:text});}
function mail_friends(to,from,cat,id){var req=new JsHttpRequest();req.onreadystatechange=function(){if(req.readyState==4)$("mail_friends").innerHTML=req.responseJS;else $('mail_friends').innerHTML=ind;}
req.open(null,servername+"core/mail_friends.php",true);req.send({send_to:to,send_from:from,idcat:cat,idmess:id});}
function toggle_s(){$('toggle_s').style.display=($('toggle_s').style.display=='block')?'none':'block';}
function toggle_s_close(){$('toggle_s').style.display='none';}
function addabuse(type,id){var req=new JsHttpRequest();req.onreadystatechange=function(){if(req.readyState==4)$("addabuse").innerHTML=req.responseJS;else $('addabuse').innerHTML=ind;}
req.open(null,servername+"core/addabuse.php",true);req.send({send_type:type,idmess:id});}
function addtonote(id){var req=new JsHttpRequest();req.onreadystatechange=function(){if(req.readyState==4)$('addtonote').innerHTML=req.responseJS;else $('addtonote').innerHTML=ind;}
req.caching=true;req.open(null,servername+"core/addtonote.php",true);req.send({idboard:id});}
function addtonote_list(id){var req=new JsHttpRequest();req.onreadystatechange=function(){if(req.readyState==4)$('addtonote_list_'+id).innerHTML='<img src="'+servername+'images/in_note.gif">';else $('addtonote_list_'+id).innerHTML=ind;}
req.caching=true;req.open(null,servername+"core/addtonote.php",true);req.send({idboard:id});}
function sendFormMailToUser(mail,text,security,id){var req=new JsHttpRequest();req.onreadystatechange=function(){if(req.readyState==4)$("mailto").innerHTML=req.responseJS;else $('mailto').innerHTML=ind;}
req.open(null,servername+"core/mailto.php",true);req.send({send_email:mail,send_text:text,securityCode:security,idmess:id});}
function search_autor(id,pageid){var req=new JsHttpRequest();req.onreadystatechange=function(){if(req.readyState==4)$("search_autor").innerHTML=req.responseJS;else $('search_autor').innerHTML=ind;}
req.open(null,servername+"core/search_autor.php",true);req.send({idmess:id,page:pageid});}
function changecity(id){var req=new JsHttpRequest();req.onreadystatechange=function(){if(req.readyState==4)$("result").innerHTML=req.responseJS;else $('result').innerHTML=ind;}
req.caching=true;req.open(null,servername+"core/changecity.php",true);req.send({rootcity:id});}
function openCenteredWindow(url){var myWindow;var width=500;var height=250;var left=parseInt((screen.availWidth/2)-(width/2));var top=parseInt((screen.availHeight/2)-(height/2));var windowFeatures="width="+width+",height="+height+",resizable=yes,left="+left+",top="+top+",screenX="+left+",screenY="+top;myWindow=window.open(url,"subWind",windowFeatures);if(!myWindow||myWindow.closed){myWindow=window.open(url,"subWind",windowFeatures);}else{myWindow.focus();}}
function checkall(self){var a=document.getElementsByTagName('input');var checkValue=self.checked;for(i=1;i<a.length;i++){if(a[i].type=='checkbox')a[i].checked=checkValue;}}
function conformdelete(form,mess){if(confirm(mess))return true;return false;}
function selcat(value,place){var req=new JsHttpRequest();req.onreadystatechange=function(){if(req.readyState==4)$(place).innerHTML=req.responseJS;else $(place).innerHTML=ind;}
req.caching=true;req.open(null,servername+"/core/selcat.php",true);req.send({id_root:value,id_place:place});}
function rootcat(place){var req=new JsHttpRequest();req.onreadystatechange=function(){if(req.readyState==4)$(place).innerHTML=req.responseJS;else $(place).innerHTML=ind;}
req.caching=true;req.open(null,servername+"/core/rootcat.php",true);req.send({id_place:place});}
function selcity(value,place){var req=new JsHttpRequest();req.onreadystatechange=function(){if(req.readyState==4)$(place).innerHTML=req.responseJS;else $(place).innerHTML=ind;}
req.caching=true;req.open(null,servername+"/core/selcity.php",true);req.send({id_root:value,id_place:place});}
function rootcity(place){var req=new JsHttpRequest();req.onreadystatechange=function(){if(req.readyState==4)$(place).innerHTML=req.responseJS;else $(place).innerHTML=ind;}
req.caching=true;req.open(null,servername+"/core/rootcity.php",true);req.send({id_place:place});}
function check_fields(){obs='document.add_form.';
s='title';ob=eval(obs+s);value=ob.value;if(value==''){ob.focus();ob.className="err";return false;}
else ob.className="";
s='type';ob=eval(obs+s);value=ob.value;if(value=='0'){ob.focus();ob.className="err";return false;}
else ob.className="";
s='id_category';ob=eval(obs+s);value=ob.value;if(value=='no'){ob.focus();ob.className="err";return false;}
else ob.className="";
s='text';ob=eval(obs+s);value=ob.value;if(value==''){ob.focus();ob.className="err";return false;}
else ob.className="";
s='video';ob=eval(obs+s);value=ob.value;
if(value!=''){var valid_str=value.match(/youtube\.com\/watch\?v\=[0-9a-z-_]{11}/gi);var valid_length=value.length;if(valid_str===null || valid_str=='undefined' || valid_str=='' || valid_length>50 || valid_length<24){alert('Link to video is not correct');ob.focus();ob.className="err";valid_str='';return false;}
else ob.className="";
}
s='autor';ob=eval(obs+s);value=ob.value;if(value==''){ob.focus();ob.className="err";return false;}
else ob.className="";
s='securityCode';ob=eval(obs+s);if(ob){value=ob.value;if(value==''){ob.focus();ob.className="err";return false;}
else ob.className="";}
return true;}
function check_fields_news(){obs='document.add_form.';s='title';ob=eval(obs+s);value=ob.value;if(value==''){ob.focus();ob.className="err";return false;}
else ob.className="";
s='short';ob=eval(obs+s);value=ob.value;if(value==''){ob.focus();ob.className="err";return false;}
else ob.className="";
s='full';ob=eval(obs+s);value=ob.value;if(value==''){ob.focus();ob.className="err";return false;}
else ob.className="";
s='securityCode';ob=eval(obs+s);if(ob){value=ob.value;if(value==''){ob.focus();ob.className="err";return false;}
else ob.className="";}
return true;}
function ff2(t){t.className=/\D/.test(t.value)?'err':'';if(/\D/.test(t.value)){t.value=t.defaultValue;if(self.ww)clearTimeout(ww);o=t;ww=setTimeout("o.className='';",100);}else t.defaultValue=t.value;}
function details(id){$(id).style.display=($(id).style.display=='none')?'block':'none';}
var det2;function details2(id){if(det2==id){$(id).style.display=($(id).style.display=='none')?'block':'none';}
else{$(id).style.display='block';if(det2)$(det2).style.display='none';det2=id;}}
function JsHttpRequest(){var t=this;t.onreadystatechange=null;t.readyState=0;t.responseText=null;t.responseXML=null;t.status=200;t.statusText="OK";t.responseJS=null;t.caching=false;t.loader=null;t.session_name="PHPSESSID";t._ldObj=null;t._reqHeaders=[];t._openArgs=null;t._errors={inv_form_el:"Invalid FORM element detected: name=%, tag=%",must_be_single_el:"If used, <form> must be a single HTML element in the list.",js_invalid:"JavaScript code generated by backend is invalid!\n%",url_too_long:"Cannot use so long query with GET request (URL is larger than % bytes)",unk_loader:"Unknown loader: %",no_loaders:"No loaders registered at all, please check JsHttpRequest.LOADERS array",no_loader_matched:"Cannot find a loader which may process the request. Notices are:\n%"};t.abort=function(){with(this){if(_ldObj&&_ldObj.abort){_ldObj.abort();}_cleanup();if(readyState==0){return;}if(readyState==1&&!_ldObj){readyState=0;return;}_changeReadyState(4,true);}};t.open=function(_2,_3,_4,_5,_6){with(this){if(_3.match(/^((\w+)\.)?(GET|POST)\s+(.*)/i)){this.loader=RegExp.$2?RegExp.$2:null;_2=RegExp.$3;_3=RegExp.$4;}try{if(document.location.search.match(new RegExp("[&?]"+session_name+"=([^&?]*)"))||document.cookie.match(new RegExp("(?:;|^)\\s*"+session_name+"=([^;]*)"))){_3+=(_3.indexOf("?")>=0?"&":"?")+session_name+"="+this.escape(RegExp.$1);}}catch(e){}_openArgs={method:(_2||"").toUpperCase(),url:_3,asyncFlag:_4,username:_5!=null?_5:"",password:_6!=null?_6:""};_ldObj=null;_changeReadyState(1,true);return true;}};t.send=function(_7){if(!this.readyState){return;}this._changeReadyState(1,true);this._ldObj=null;var _8=[];var _9=[];if(!this._hash2query(_7,null,_8,_9)){return;}var _a=null;if(this.caching&&!_9.length){_a=this._openArgs.username+":"+this._openArgs.password+"@"+this._openArgs.url+"|"+_8+"#"+this._openArgs.method;var _b=JsHttpRequest.CACHE[_a];if(_b){this._dataReady(_b[0],_b[1]);return false;}}var _c=(this.loader||"").toLowerCase();if(_c&&!JsHttpRequest.LOADERS[_c]){return this._error("unk_loader",_c);}var _d=[];var _e=JsHttpRequest.LOADERS;for(var _f in _e){var ldr=_e[_f].loader;if(!ldr){continue;}if(_c&&_f!=_c){continue;}var _11=new ldr(this);JsHttpRequest.extend(_11,this._openArgs);JsHttpRequest.extend(_11,{queryText:_8.join("&"),queryElem:_9,id:(new Date().getTime())+""+JsHttpRequest.COUNT++,hash:_a,span:null});var _12=_11.load();if(!_12){this._ldObj=_11;JsHttpRequest.PENDING[_11.id]=this;return true;}if(!_c){_d[_d.length]="- "+_f.toUpperCase()+": "+this._l(_12);}else{return this._error(_12);}}return _f?this._error("no_loader_matched",_d.join("\n")):this._error("no_loaders");};t.getAllResponseHeaders=function(){with(this){return _ldObj&&_ldObj.getAllResponseHeaders?_ldObj.getAllResponseHeaders():[];}};t.getResponseHeader=function(_13){with(this){return _ldObj&&_ldObj.getResponseHeader?_ldObj.getResponseHeader(_13):null;}};t.setRequestHeader=function(_14,_15){with(this){_reqHeaders[_reqHeaders.length]=[_14,_15];}};t._dataReady=function(_16,js){with(this){if(caching&&_ldObj){JsHttpRequest.CACHE[_ldObj.hash]=[_16,js];}responseText=responseXML=_16;responseJS=js;if(js!==null){status=200;statusText="OK";}else{status=500;statusText="Internal Server Error";}_changeReadyState(2);_changeReadyState(3);_changeReadyState(4);_cleanup();}};t._l=function(_18){var i=0,p=0,msg=this._errors[_18[0]];while((p=msg.indexOf("%",p))>=0){var a=_18[++i]+"";msg=msg.substring(0,p)+a+msg.substring(p+1,msg.length);p+=1+a.length;}return msg;};t._error=function(msg){msg=this._l(typeof(msg)=="string"?arguments:msg);msg="JsHttpRequest: "+msg;if(!window.Error){throw msg;}else{if((new Error(1,"test")).description=="test"){throw new Error(1,msg);}else{throw new Error(msg);}}};t._hash2query=function(_1e,_1f,_20,_21){if(_1f==null){_1f="";}if((""+typeof(_1e)).toLowerCase()=="object"){var _22=false;if(_1e&&_1e.parentNode&&_1e.parentNode.appendChild&&_1e.tagName&&_1e.tagName.toUpperCase()=="FORM"){_1e={form:_1e};}for(var k in _1e){var v=_1e[k];if(v instanceof Function){continue;}var _25=_1f?_1f+"["+this.escape(k)+"]":this.escape(k);var _26=v&&v.parentNode&&v.parentNode.appendChild&&v.tagName;if(_26){var tn=v.tagName.toUpperCase();if(tn=="FORM"){_22=true;}else{if(tn=="INPUT"||tn=="TEXTAREA"||tn=="SELECT"){}else{return this._error("inv_form_el",(v.name||""),v.tagName);}}_21[_21.length]={name:_25,e:v};}else{if(v instanceof Object){this._hash2query(v,_25,_20,_21);}else{if(v===null){continue;}if(v===true){v=1;}if(v===false){v="";}_20[_20.length]=_25+"="+this.escape(""+v);}}if(_22&&_21.length>1){return this._error("must_be_single_el");}}}else{_20[_20.length]=_1e;}return true;};t._cleanup=function(){var _28=this._ldObj;if(!_28){return;}JsHttpRequest.PENDING[_28.id]=false;var _29=_28.span;if(!_29){return;}_28.span=null;var _2a=function(){_29.parentNode.removeChild(_29);};JsHttpRequest.setTimeout(_2a,50);};t._changeReadyState=function(s,_2c){with(this){if(_2c){status=statusText=responseJS=null;responseText="";}readyState=s;if(onreadystatechange){onreadystatechange();}}};t.escape=function(s){return escape(s).replace(new RegExp("\\+","g"),"%2B");};}JsHttpRequest.COUNT=0;JsHttpRequest.MAX_URL_LEN=2000;JsHttpRequest.CACHE={};JsHttpRequest.PENDING={};JsHttpRequest.LOADERS={};JsHttpRequest._dummy=function(){};JsHttpRequest.TIMEOUTS={s:window.setTimeout,c:window.clearTimeout};JsHttpRequest.setTimeout=function(_2e,dt){window.JsHttpRequest_tmp=JsHttpRequest.TIMEOUTS.s;if(typeof(_2e)=="string"){id=window.JsHttpRequest_tmp(_2e,dt);}else{var id=null;var _31=function(){_2e();delete JsHttpRequest.TIMEOUTS[id];};id=window.JsHttpRequest_tmp(_31,dt);JsHttpRequest.TIMEOUTS[id]=_31;}window.JsHttpRequest_tmp=null;return id;};JsHttpRequest.clearTimeout=function(id){window.JsHttpRequest_tmp=JsHttpRequest.TIMEOUTS.c;delete JsHttpRequest.TIMEOUTS[id];var r=window.JsHttpRequest_tmp(id);window.JsHttpRequest_tmp=null;return r;};JsHttpRequest.query=function(url,_35,_36,_37){var req=new this();req.caching=!_37;req.onreadystatechange=function(){if(req.readyState==4){_36(req.responseJS,req.responseText);}};req.open(null,url,true);req.send(_35);};JsHttpRequest.dataReady=function(d){var th=this.PENDING[d.id];delete this.PENDING[d.id];if(th){th._dataReady(d.text,d.js);}else{if(th!==false){throw"dataReady(): unknown pending id: "+d.id;}}};JsHttpRequest.extend=function(_3b,src){for(var k in src){_3b[k]=src[k];}};JsHttpRequest.LOADERS.xml={loader:function(req){JsHttpRequest.extend(req._errors,{xml_no:"Cannot use XMLHttpRequest or ActiveX loader: not supported",xml_no_diffdom:"Cannot use XMLHttpRequest to load data from different domain %",xml_no_headers:"Cannot use XMLHttpRequest loader or ActiveX loader, POST method: headers setting is not supported, needed to work with encodings correctly",xml_no_form_upl:"Cannot use XMLHttpRequest loader: direct form elements using and uploading are not implemented"});this.load=function(){if(this.queryElem.length){return["xml_no_form_upl"];}if(this.url.match(new RegExp("^([a-z]+://[^\\/]+)(.*)","i"))){if(RegExp.$1.toLowerCase()!=document.location.protocol+"//"+document.location.hostname.toLowerCase()){return["xml_no_diffdom",RegExp.$1];}}var xr=null;if(window.XMLHttpRequest){try{xr=new XMLHttpRequest();}catch(e){}}else{if(window.ActiveXObject){try{xr=new ActiveXObject("Microsoft.XMLHTTP");}catch(e){}if(!xr){try{xr=new ActiveXObject("Msxml2.XMLHTTP");}catch(e){}}}}if(!xr){return["xml_no"];}var _40=window.ActiveXObject||xr.setRequestHeader;if(!this.method){this.method=_40&&this.queryText.length?"POST":"GET";}if(this.method=="GET"){if(this.queryText){this.url+=(this.url.indexOf("?")>=0?"&":"?")+this.queryText;}this.queryText="";if(this.url.length>JsHttpRequest.MAX_URL_LEN){return["url_too_long",JsHttpRequest.MAX_URL_LEN];}}else{if(this.method=="POST"&&!_40){return["xml_no_headers"];}}this.url+=(this.url.indexOf("?")>=0?"&":"?")+"JsHttpRequest="+(req.caching?"0":this.id)+"-xml";var id=this.id;xr.onreadystatechange=function(){if(xr.readyState!=4){return;}xr.onreadystatechange=JsHttpRequest._dummy;req.status=null;try{req.status=xr.status;req.responseText=xr.responseText;}catch(e){}if(!req.status){return;}try{eval("JsHttpRequest._tmp = function(id) { var d = "+req.responseText+"; d.id = id; JsHttpRequest.dataReady(d); }");}catch(e){return req._error("js_invalid",req.responseText);}JsHttpRequest._tmp(id);JsHttpRequest._tmp=null;};xr.open(this.method,this.url,true,this.username,this.password);if(_40){for(var i=0;i<req._reqHeaders.length;i++){xr.setRequestHeader(req._reqHeaders[i][0],req._reqHeaders[i][1]);}xr.setRequestHeader("Content-Type","application/octet-stream");}xr.send(this.queryText);this.span=null;this.xr=xr;return null;};this.getAllResponseHeaders=function(){return this.xr.getAllResponseHeaders();};this.getResponseHeader=function(_43){return this.xr.getResponseHeader(_43);};this.abort=function(){this.xr.abort();this.xr=null;};}};JsHttpRequest.LOADERS.script={loader:function(req){JsHttpRequest.extend(req._errors,{script_only_get:"Cannot use SCRIPT loader: it supports only GET method",script_no_form:"Cannot use SCRIPT loader: direct form elements using and uploading are not implemented"});this.load=function(){if(this.queryText){this.url+=(this.url.indexOf("?")>=0?"&":"?")+this.queryText;}this.url+=(this.url.indexOf("?")>=0?"&":"?")+"JsHttpRequest="+this.id+"-"+"script";this.queryText="";if(!this.method){this.method="GET";}if(this.method!=="GET"){return["script_only_get"];}if(this.queryElem.length){return["script_no_form"];}if(this.url.length>JsHttpRequest.MAX_URL_LEN){return["url_too_long",JsHttpRequest.MAX_URL_LEN];}var th=this,d=document,s=null,b=d.body;if(!window.opera){this.span=s=d.createElement("SCRIPT");var _49=function(){s.language="JavaScript";if(s.setAttribute){s.setAttribute("src",th.url);}else{s.src=th.url;}b.insertBefore(s,b.lastChild);};}else{this.span=s=d.createElement("SPAN");s.style.display="none";b.insertBefore(s,b.lastChild);s.innerHTML="Workaround for IE.<s"+"cript></"+"script>";var _49=function(){s=s.getElementsByTagName("SCRIPT")[0];s.language="JavaScript";if(s.setAttribute){s.setAttribute("src",th.url);}else{s.src=th.url;}};}JsHttpRequest.setTimeout(_49,10);return null;};}};JsHttpRequest.LOADERS.form={loader:function(req){JsHttpRequest.extend(req._errors,{form_el_not_belong:"Element \"%\" does not belong to any form!",form_el_belong_diff:"Element \"%\" belongs to a different form. All elements must belong to the same form!",form_el_inv_enctype:"Attribute \"enctype\" of the form must be \"%\" (for IE), \"%\" given."});this.load=function(){var th=this;if(!th.method){th.method="POST";}th.url+=(th.url.indexOf("?")>=0?"&":"?")+"JsHttpRequest="+th.id+"-"+"form";if(th.method=="GET"){if(th.queryText){th.url+=(th.url.indexOf("?")>=0?"&":"?")+th.queryText;}if(th.url.length>JsHttpRequest.MAX_URL_LEN){return["url_too_long",JsHttpRequest.MAX_URL_LEN];}var p=th.url.split("?",2);th.url=p[0];th.queryText=p[1]||"";}var _4d=null;var _4e=false;if(th.queryElem.length){if(th.queryElem[0].e.tagName.toUpperCase()=="FORM"){_4d=th.queryElem[0].e;_4e=true;th.queryElem=[];}else{_4d=th.queryElem[0].e.form;for(var i=0;i<th.queryElem.length;i++){var e=th.queryElem[i].e;if(!e.form){return["form_el_not_belong",e.name];}if(e.form!=_4d){return["form_el_belong_diff",e.name];}}}if(th.method=="POST"){var _51="multipart/form-data";var _52=(_4d.attributes.encType&&_4d.attributes.encType.nodeValue)||(_4d.attributes.enctype&&_4d.attributes.enctype.value)||_4d.enctype;if(_52!=_51){return["form_el_inv_enctype",_51,_52];}}}var d=_4d&&(_4d.ownerDocument||_4d.document)||document;var _54="jshr_i_"+th.id;var s=th.span=d.createElement("DIV");s.style.position="absolute";s.style.display="none";s.style.visibility="hidden";s.innerHTML=(_4d?"":"<form"+(th.method=="POST"?" enctype=\"multipart/form-data\" method=\"post\"":"")+"></form>")+"<iframe name=\""+_54+"\" id=\""+_54+"\" style=\"width:0px; height:0px; overflow:hidden; border:none\"></iframe>";if(!_4d){_4d=th.span.firstChild;}d.body.insertBefore(s,d.body.lastChild);var _56=function(e,_58){var sv=[];var _5a=e;if(e.mergeAttributes){var _5a=d.createElement("form");_5a.mergeAttributes(e,false);}for(var i=0;i<_58.length;i++){var k=_58[i][0],v=_58[i][1];sv[sv.length]=[k,_5a.getAttribute(k)];_5a.setAttribute(k,v);}if(e.mergeAttributes){e.mergeAttributes(_5a,false);}return sv;};var _5e=function(){top.JsHttpRequestGlobal=JsHttpRequest;var _5f=[];if(!_4e){for(var i=0,n=_4d.elements.length;i<n;i++){_5f[i]=_4d.elements[i].name;_4d.elements[i].name="";}}var qt=th.queryText.split("&");for(var i=qt.length-1;i>=0;i--){var _63=qt[i].split("=",2);var e=d.createElement("INPUT");e.type="hidden";e.name=unescape(_63[0]);e.value=_63[1]!=null?unescape(_63[1]):"";_4d.appendChild(e);}for(var i=0;i<th.queryElem.length;i++){th.queryElem[i].e.name=th.queryElem[i].name;}var sv=_56(_4d,[["action",th.url],["method",th.method],["onsubmit",null],["target",_54]]);_4d.submit();_56(_4d,sv);for(var i=0;i<qt.length;i++){_4d.lastChild.parentNode.removeChild(_4d.lastChild);}if(!_4e){for(var i=0,n=_4d.elements.length;i<n;i++){_4d.elements[i].name=_5f[i];}}};JsHttpRequest.setTimeout(_5e,100);return null;};}};
if(typeof deconcept=="undefined"){var deconcept=new Object();}
if(typeof deconcept.util=="undefined"){deconcept.util=new Object();}
if(typeof deconcept.SWFObjectUtil=="undefined"){deconcept.SWFObjectUtil=new Object();}
deconcept.SWFObject=function(_1,id,w,h,_5,c,_7,_8,_9,_a,_b){if(!document.createElement||!document.getElementById){return;}
this.DETECT_KEY=_b?_b:"detectflash";this.skipDetect=deconcept.util.getRequestParameter(this.DETECT_KEY);this.params=new Object();this.variables=new Object();this.attributes=new Array();if(_1){this.setAttribute("swf",_1);}
if(id){this.setAttribute("id",id);}
if(w){this.setAttribute("width",w);}
if(h){this.setAttribute("height",h);}
if(_5){this.setAttribute("version",new deconcept.PlayerVersion(_5.toString().split(".")));}
this.installedVer=deconcept.SWFObjectUtil.getPlayerVersion(this.getAttribute("version"),_7);if(c){this.addParam("bgcolor",c);}
var q=_8?_8:"high";this.addParam("quality",q);this.setAttribute("useExpressInstall",_7);this.setAttribute("doExpressInstall",false);var _d=(_9)?_9:window.location;this.setAttribute("xiRedirectUrl",_d);this.setAttribute("redirectUrl","");if(_a){this.setAttribute("redirectUrl",_a);}};
deconcept.SWFObject.prototype={setAttribute:function(_e,_f){this.attributes[_e]=_f;
},getAttribute:function(_10){
return this.attributes[_10];
},addParam:function(_11,_12){
this.params[_11]=_12;
},getParams:function(){
return this.params;
},addVariable:function(_13,_14){
this.variables[_13]=_14;
},getVariable:function(_15){
return this.variables[_15];
},getVariables:function(){
return this.variables;
},getVariablePairs:function(){
var _16=new Array();var key;var _18=this.getVariables();for(key in _18){_16.push(key+"="+_18[key]);}
return _16;
},getSWFHTML:function(){
var _19="";
if(navigator.plugins&&navigator.mimeTypes&&navigator.mimeTypes.length){if(this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType","PlugIn");}
_19="<embed type=\"application/x-shockwave-flash\" src=\""+this.getAttribute("swf")+"\" width=\""+this.getAttribute("width")+"\" height=\""+this.getAttribute("height")+"\"";_19+=" id=\""+this.getAttribute("id")+"\" name=\""+this.getAttribute("id")+"\" ";var _1a=this.getParams();for(var key in _1a){_19+=[key]+"=\""+_1a[key]+"\" ";}
var _1c=this.getVariablePairs().join("&");if(_1c.length>0){_19+="flashvars=\""+_1c+"\"";}
_19+="/>";
}else{
if(this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType","ActiveX");}
_19="<object id=\""+this.getAttribute("id")+"\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\""+this.getAttribute("width")+"\" height=\""+this.getAttribute("height")+"\">";_19+="<param name=\"movie\" value=\""+this.getAttribute("swf")+"\" />";var _1d=this.getParams();for(var key in _1d){_19+="<param name=\""+key+"\" value=\""+_1d[key]+"\" />";}
var _1f=this.getVariablePairs().join("&");if(_1f.length>0){_19+="<param name=\"flashvars\" value=\""+_1f+"\" />";}
_19+="</object>";}
return _19;
},write:function(_20){
if(this.getAttribute("useExpressInstall")){var _21=new deconcept.PlayerVersion([6,0,65]);if(this.installedVer.versionIsValid(_21)&&!this.installedVer.versionIsValid(this.getAttribute("version"))){this.setAttribute("doExpressInstall",true);this.addVariable("MMredirectURL",escape(this.getAttribute("xiRedirectUrl")));document.title=document.title.slice(0,47)+" - Flash Player Installation";this.addVariable("MMdoctitle",document.title);}}if(this.skipDetect||this.getAttribute("doExpressInstall")||this.installedVer.versionIsValid(this.getAttribute("version"))){var n=(typeof _20=="string")?document.getElementById(_20):_20;n.innerHTML=this.getSWFHTML();return true;
}else{
if(this.getAttribute("redirectUrl")!=""){document.location.replace(this.getAttribute("redirectUrl"));}}
return false;}};
deconcept.SWFObjectUtil.getPlayerVersion=function(_23,_24){var _25=new deconcept.PlayerVersion([0,0,0]);if(navigator.plugins&&navigator.mimeTypes.length){var x=navigator.plugins["Shockwave Flash"];if(x&&x.description){_25=new deconcept.PlayerVersion(x.description.replace(/([a-z]|[A-Z]|\s)+/,"").replace(/(\s+r|\s+b[0-9]+)/,".").split("."));}
}else{try{
var axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash");for(var i=3;axo!=null;i++){axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash."+i);_25=new deconcept.PlayerVersion([i,0,0]);}}
catch(e){}
if(_23&&_25.major>_23.major){return _25;}
if(!_23||((_23.minor!=0||_23.rev!=0)&&_25.major==_23.major)||_25.major!=6||_24){try{_25=new deconcept.PlayerVersion(axo.GetVariable("$version").split(" ")[1].split(","));}
catch(e){}}}
return _25;};
deconcept.PlayerVersion=function(_29){this.major=parseInt(_29[0])!=null?parseInt(_29[0]):0;this.minor=parseInt(_29[1])||0;this.rev=parseInt(_29[2])||0;};
deconcept.PlayerVersion.prototype.versionIsValid=function(fv){if(this.major<fv.major){return false;}
if(this.major>fv.major){return true;}
if(this.minor<fv.minor){return false;}
if(this.minor>fv.minor){return true;}
if(this.rev<fv.rev){return false;}return true;};
deconcept.util={getRequestParameter:function(_2b){var q=document.location.search||document.location.hash;if(q){var _2d=q.indexOf(_2b+"=");var _2e=(q.indexOf("&",_2d)>-1)?q.indexOf("&",_2d):q.length;if(q.length>1&&_2d>-1){return q.substring(q.indexOf("=",_2d)+1,_2e);}}return "";}};
if(Array.prototype.push==null){Array.prototype.push=function(_2f){this[this.length]=_2f;return this.length;};}
var getQueryParamValue=deconcept.util.getRequestParameter;var FlashObject=deconcept.SWFObject;var SWFObject=deconcept.SWFObject;