$(document).ready(function() {
	document.getElementById("banner").addEventListener("click", banner_click, false);
	$("#banner").css({
						"display": "table-cell",
						"height": $(window).height(),
						"width": $(window).width(),
						"vertical-align": "middle",
						"text-align": "center",
						"font-size": ($(window).width() > 200 ? "16" : Math.floor(($(window).width() / 200) * 16)) + "px",
						"text-decoration" : "none",
						"color" : "#5555ff"
					});
	$("#banner span").css({
						"border-bottom": "1px dashed #5555ff"
					});
});

function banner_click(e) {
	if(!$("#sellbannermaster_iframe", parent.document).is("div")) {
		$("body", parent.document).append("<div id='sellbannermaster_iframe'><div>[X]</div><iframe width='900' height='680' src='" + script_url + "modules/banners/buy.php?id=" + id + "' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'></iframe></div>");
		$("#sellbannermaster_iframe div", parent.document).click(function(e) {
			$("#sellbannermaster_iframe", parent.document).hide();
		});
	} else {
		$("#sellbannermaster_iframe iframe", parent.document).attr("src", script_url + "modules/banners/buy.php?id=" + id);
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
