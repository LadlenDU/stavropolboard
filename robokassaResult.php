<?php

require_once 'admin/conf.php';

define('ROBOKASSA_RESULT_EMAIL', 'serega4107@rambler.ru');

$mrh_pass2 = 'putYourPassword#2Here';

$crc = md5("$_GET[OutSum]:$_GET[InvId]:$mrh_pass2:shp_Item=$_GET[shp_Item]");

if ($_GET['SignatureValue'] == $crc) {
    $sql = '';

    $subject = 'Получена оплата';
    $msg = "От пользователя получена оплата\n\n"
        . "Цена: $_GET[OutSum]\n"
        . "ID объявления: $_GET[InvId]\n";
    if ($_GET['shp_Item'] == 'vip') {
        $msg .= 'Тип: присвоение VIP статуса';
        $sql = "update jb_board set checkbox_select=1, select_time=NOW() where id='" . mysql_real_escape_string($_GET['InvId']) . "'";
    } elseif ($_GET['shp_Item'] == 'sel') {
        $msg .= 'Тип: выделение объявления';
        $sql = "update jb_board set checkbox_top=1, top_time=NOW() where id='" . mysql_real_escape_string($_GET['InvId']) . "'";
    }

    mysql_query($sql);

    sendmailer(ROBOKASSA_RESULT_EMAIL, $c['admin_mail'], $subject, $msg);

    echo 'OK' . $_GET['InvId'];
} else {
    $subject = 'ROBOKASSA - неверная КС';
    $msg = "ROBOKASSA вернул результат с неправильной контрольной суммой.\nПараметры $_GET:\n"
        . print_r($_GET, true);
    sendmailer(ROBOKASSA_RESULT_EMAIL, $c['admin_mail'], $subject, $msg);
    echo 'wrong checksum: ' . $_GET['InvId'];
}
