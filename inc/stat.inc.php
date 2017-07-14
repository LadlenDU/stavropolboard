<div class="counter-left"><div class="form-wrapper"><p><?
$allcount=mysql_result(mysql_query("select count(*) from jb_board"),0);cq();
if(@$allcount)echo $lang[603]." <strong>".$allcount."</strong> ".PluralForm($allcount,$lang[262],$lang[263],$lang[264]);
else echo $lang[604];
$dont_moder_query=mysql_result(mysql_query("SELECT count(*) FROM jb_board WHERE old_mess='new'"),0);cq();
if(@$dont_moder_query)echo "<br />".$lang[605]." <strong>".$dont_moder_query."</strong> ".PluralForm($dont_moder_query,$lang[262],$lang[263],$lang[264])." ".$lang[606];
$today_query=mysql_result(mysql_query("SELECT count(*) FROM jb_board WHERE DATE(date_add)=CURDATE()"),0);cq();
if(@$today_query)echo "<br />".$lang[607]." <strong>".$today_query."</strong> ".PluralForm($today_query,$lang[262],$lang[263],$lang[264]); else echo "<br />".$lang[608];
$yestd_query=mysql_result(mysql_query("SELECT count(*) FROM jb_board WHERE DATE(date_add)=CURDATE()-INTERVAL 1 DAY"),0);cq(); 
if (@$yestd_query)  echo "<br />".$lang[609]." - <strong>".$yestd_query."</strong>";
?></p></div>

</div>