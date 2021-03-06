var timer_interval = 0;
$(document).ready(function() {
	$("body").css("background-color", "#eeeeee");
	if($('#title_length').is("span")) $('#title_length').text(title_length - $('input[name=ads_title]').val().length);
	if(typeof ads_id !== 'undefined') {
		timer_interval = setInterval("show_timer()", 5000);
	}
});

function show_timer() {
	$.ajax({
		type: "POST",
		url: script_url + "modules/ads/timer.php",
		data : "id=" + id + "&ads_id=" + ads_id,
		success: function(status) {
			if(status == 1 || status == 4) {
				clearInterval(timer_interval);
				$(".gateways").html("<h2>" + successful + "</h2>");
			}
		}
	});
}

function rebuild() {
	$.ajax({
		type: "POST",
		url: script_url + "modules/ads/rebuild.php",
		data : "ads_id=" + ads_id,
		success: function(status) {
			$("#sellbannermaster_iframe", parent.document).remove();
		}
	});
}

function calculate_price() {
	price = 0;
	if(price_ad > 0) {
		price += price_ad;
	}
	if(price_no_blank > 0 && $("input[name=ads_no_blank]:checked").length) {
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
			if(discount_money.length > 1) {
				if(index in discount_money) {
					price -= discount_money[index];
				} else {
					price -= discount_money[discount_money.length - 1];
				}
			} else if(discount_profit.length > 1) {
				if(index in discount_profit) {
					price = price * (1 - discount_profit[index] / 100);
				} else {
					price = price * (1 - discount_profit[discount_profit.length - 1] / 100);
				}
			}
		}
	}

	price = price.toFixed(2);

	$("#ads_price span").text(price);
}

function check_title_length(code) {
	var len = $("input[name=ads_title]").val().length;
	if(len >= title_length && code != 0) {
		$("#title_length").text(0);
		return false;
	}
	return true;
}

function filled() {
	if($("input[name=ads_title]").val() == "") {
		alert(wrong_title);
		return false;
	} else if(!$("input[name=ads_terms]:checked").length) {
		alert(wrong_accept);
		return false;
	} else if(!isUrlValid($("input[name=ads_target_url]").val())) {
		alert(wrong_target);
		return false;
	} else if(!isValidEmailAddress($("input[name=ads_email]").val())) {
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
