$(document).ready(function() {
	document.getElementById("banner").addEventListener("click", banner_click, false);
	$.ajax({
		type: "POST",
		url: script_url + "modules/banners/viewed.php",
		data : "id=" + id + "&referer=" + referer,
		success: function(status) {
		}
	});
});

function banner_click(e) {
	$.ajax({
		type: "POST",
		url: script_url + "modules/banners/clicked.php",
		data : "id=" + id + "&referer=" + referer,
		success: function(status) {
		}
	});
}
