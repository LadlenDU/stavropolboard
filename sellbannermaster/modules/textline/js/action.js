$(document).ready(function() {
	document.getElementById("textline").addEventListener("click", textline_click, false);
	document.getElementById("textline_sell").addEventListener("click", textline_sell_click, false);
	$.ajax({
		type: "POST",
		url: script_url + "modules/textline/viewed.php",
		data : "id=" + textline_id + "&referer=" + referer,
		success: function(status) {
		}
	});
});

function textline_click(e) {
	$.ajax({
		type: "POST",
		url: script_url + "modules/textline/clicked.php",
		data : "id=" + textline_id + "&referer=" + referer,
		success: function(status) {
		}
	});
}

function textline_sell_click(e) {
	if(!$("#sellbannermaster_iframe", parent.document).is("div")) {
		$("body", parent.document).append("<div id='sellbannermaster_iframe'><div>[X]</div><iframe width='900' height='680' src='" + script_url + "modules/textline/buy.php?id=" + id + "' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'></iframe></div>");
		$("#sellbannermaster_iframe div", parent.document).click(function(e) {
			$("#sellbannermaster_iframe", parent.document).hide();
		});
	} else {
		$("#sellbannermaster_iframe iframe", parent.document).attr("src", script_url + "modules/textline/buy.php?id=" + id);
	}
	$("#sellbannermaster_iframe", parent.document).css({
						"position": "fixed",
						"left": "0px",
						"top": "0px",
						"display": "table-cell",
						"height": parent.document.body.clientHeight + "px",
						"width": parent.document.body.clientWidth + "px",
						"padding-top": "5px",
						"z-index": "10000",
						"text-align": "center",
						"background" : "url(" + script_url + "images/opacity.png) repeat"
					});
	$("#sellbannermaster_iframe div", parent.document).css({
						"display": "block",
						"width": "900px",
						"text-align": "right",
						"margin": "50px auto 0px auto",
						"color": "#ffffff",
						"cursor": "pointer"
					});
}
