var sellbannermaster_scripts = document.getElementsByTagName('script');
var sellbannermaster_myScript = sellbannermaster_scripts[sellbannermaster_scripts.length - 1];
var sellbannermaster_queryString = sellbannermaster_myScript.src.replace(/^[^\?]+\??/,'');
var sellbannermaster_params = sellbannermaster_parseQuery(sellbannermaster_queryString);

function sellbannermaster_parseQuery(query) {
	var Params = new Object ();
	if(!query) return Params; // return empty object
	var Pairs = query.split(/[;&]/);
	for(var i = 0; i < Pairs.length; i++) {
		var KeyVal = Pairs[i].split('=');
		if(!KeyVal || KeyVal.length != 2) continue;
		var key = unescape(KeyVal[0]);
		var val = unescape(KeyVal[1]);
		val = val.replace(/\+/g, ' ');
		Params[key] = val;
	}
	return Params;
}

document.write("<iframe width='" + sellbannermaster_params['size_x'] + "' height='" + sellbannermaster_params['size_y'] + "' src='" + sellbannermaster_params['url'] + "'  frameborder='0' scrolling='no' marginheight='0' marginwidth='0'></iframe>");
