$(document).ready(function() {
	$(".ads_ad").click(function() {
		$.ajax({
			type: "POST",
			url: script_url + "modules/ads/clicked.php",
			data : "id=" + $(this).attr("rel") + "&referer=" + referer,
			success: function(status) {
			}
		});
	});
	document.getElementById("ads_sell").addEventListener("click", ads_sell_click, false);
	$.ajax({
		type: "POST",
		url: script_url + "modules/ads/viewed.php",
		data : "id=" + id + "&referer=" + referer,
		success: function(status) {
		}
	});
});

function ads_sell_click(e) {
	if(!$("#sellbannermaster_iframe", parent.document).is("div")) {
		$("body", parent.document).append("<div id='sellbannermaster_iframe'><div>[X]</div><iframe width='900' height='680' src='" + script_url + "modules/ads/buy.php?id=" + id + "' frameborder='0' scrolling='no' marginheight='0' marginwidth='0'></iframe></div>");
		$("#sellbannermaster_iframe div", parent.document).click(function(e) {
			$("#sellbannermaster_iframe", parent.document).hide();
		});
	} else {
		$("#sellbannermaster_iframe iframe", parent.document).attr("src", script_url + "modules/ads/buy.php?id=" + id);
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
