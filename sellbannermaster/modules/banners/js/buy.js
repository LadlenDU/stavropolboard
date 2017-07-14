var timer_interval = 0;
$(document).ready(function() {
	$("body").css("background-color", "#eeeeee");
	if($("#timer").is("div")) {
		timer_interval = setInterval("show_timer()", 1000);
	}
});

function rebuild() {
	$.ajax({
		type: "POST",
		url: script_url + "modules/banners/rebuild.php",
		data : "banner_id=" + banner_id,
		success: function(status) {
			$("#sellbannermaster_iframe", parent.document).remove();
		}
	});
}

function show_timer() {
	timer--;
	if(timer > 0) {
		var minutes = Math.floor(timer / 60);
		var seconds = timer - minutes * 60;
		seconds = seconds > 9 ? seconds : ("0" + seconds);
		$("#timer label").text(minutes + ":" + seconds);
	} else {
		clearInterval(timer_interval);
		$(".gateways").html("<h2>" + time_over + "</h2>");
	}
	var div = Math.floor(timer / 20);
	var rem = timer % 20;
	if(!rem) {
		$.ajax({
			type: "POST",
			url: script_url + "modules/banners/timer.php",
			data : "id=" + id + "&banner_id=" + banner_id,
			success: function(status) {
				if(status == 1 || status == 4) {
					clearInterval(timer_interval);
					$(".gateways").html("<h2>" + successful + "</h2>");
				}
			}
		});
	}
}

function calculate_price() {
	price = 0;
	var banner_show_bought_count = $("input[name=banner_show_bought_count]").val() <= 0 ? 1 : $("input[name=banner_show_bought_count]").val();
	var banner_show_bought_time = $("input[name=banner_show_bought_time]").val() <= 0 ? 1 : $("input[name=banner_show_bought_time]").val();
	$("input[name=banner_show_bought_count]").val(banner_show_bought_count);
	$("input[name=banner_show_bought_time]").val(banner_show_bought_time);
	if(price_1000 > 0 && $("select[name=banner_views_or_days]").val() == 0) {
		price += price_1000 * banner_show_bought_count;
		$("#bought_count").text(banner_show_bought_count * 1000);
	} else if(price_day > 0 && $("select[name=banner_views_or_days]").val() == 1)  {
		price += price_day * banner_show_bought_time;
	} else if(price_1000 > 0) {
		price += price_1000 * banner_show_bought_count;
		$("#bought_count").text(banner_show_bought_count * 1000);
	} else if(price_day > 0) {
		price += price_day * banner_show_bought_time;
	}
	if(price_no_blank > 0 && $("input[name=banner_no_blank]:checked").length) {
		price += price_no_blank;
	}

	var discount_data = website_discount.split("|");
	if(discount_data.length >= 2) {
		if(price >= discount_data[0]) {
			discount_money = discount_data[1].split('$');
			discount_money.pop();
			discount_profit = discount_data[1].split('%');
			discount_profit.pop();
			index = Math.floor(price / discount_data[0]) - 1;
			if(discount_money.length >= 1) {
				if(index in discount_money) {
					price -= discount_money[index];
				} else {
					price -= discount_money[discount_money.length - 1];
				}
			} else if(discount_profit.length >= 1) {
				if(index in discount_profit) {
					price = price * (1 - discount_profit[index] / 100);
				} else {
					price = price * (1 - discount_profit[discount_profit.length - 1] / 100);
				}
			}
		}
	}

	price = price.toFixed(2);

	$("#banner_price span").text(price);
}

function filled() {
	if($("input[name=banner_title]").val() == "") {
		alert(wrong_title);
		return false;
	} else if(!$("input[name=banner_terms]:checked").length) {
		alert(wrong_accept);
		return false;
	} else if(!isUrlValid($("input[name=banner_target_url]").val())) {
		alert(wrong_target);
		return false;
	} else if(!fileselected) {
		alert(wrong_file);
		return false;
	} else if(document.getElementById('banner_file').files[0].size > banner_weight) {
		alert(wrong_large);
		return false;
	} else if(!isValidEmailAddress($("input[name=banner_email]").val())) {
		alert(wrong_email);
		return false;
	} else if(price <= 0) {
		alert(wrong_data);
		return false;
	}
	return true;
}

function isUrlValid(url) {
	return /^(https?):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}

function isValidEmailAddress(emailAddress) {
	var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};
