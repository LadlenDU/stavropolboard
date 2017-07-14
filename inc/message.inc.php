<div class="form-wrapper">
<?
$printcontacts=(@$ads['contacts'])?nl2br($ads['contacts']):"";
$printmail=(@$ads['email'])?"<br /><div id=\"mailto\">".$lang[150].": <a href=\"#\" onclick=\"sendFormMailToUser('','','','".$ads['board_id']."');return false;\">".$lang[194]."</a></div>":"";
$printurl=(@$ads['url'])?$lang[546].": <a target=\"_blank\" rel=\"nofollow\" href=\"".$h."goto-".$ads['board_id'].".html\">www.".$ads['url']."</a> <span class=\"gray sm\">".$lang[547].": ".$ads['click']."</span>":"";
if(($ads['time_delete']*86400+$ads['unix_time']) > time()){
	if($ads['dat']==date("d.m.Y")) $printdate=$lang[542];
	else $printdate=$lang[127].": ".$ads['dat']." ".$lang[543].".";
	$printdate.=" (".$lang[544].": ".strftime( '%d.%m.%Y', $ads['time_delete'] * 86400 + $ads['unix_time'])." ".$lang[543].".)";
}else{
	$printdate=$lang[1013];
	if($c['view_nonactiv_contacts']=="no"){$printcontacts="";$printmail="";$printurl="";}
}
if($ads['type']=="s")$type_tit=$lang[414];elseif($ads['type']=="p")$type_tit=$lang[413];
elseif($ads['type']=="u")$type_tit=$lang[800];else $type_tit=$lang[801];
echo "<div class=\"sm gray\"><img class=\"absmid\" alt=\"".$type_tit."\" src=\"".$im."type".$ads['type'].".gif\" /> ".$printdate." - ".$type_tit." - ".$lang[862].": ".$ads['hits']."</div><index>
<br /><div class=\"alcenter\"><h1>".$ads['title']."</h1></div><br />".nl2br($ads['text'])."<div class='cat_fields'>".$ads['cat_fields']."</div></index><br />";
echo($ads['city']==$lang[164])?"":"<br />".$lang[220]."";
if($ads['price']!=0) echo "<br />".$lang[1008].": <span class=\"b orange\">".$ads['price']." ".$lang[1010]."</span>";
if($ads['prices']!=0) echo "<br />".$lang[1008].": <span class=\"b orange\">".$ads['prices']." ".$lang[1010]."</span>";
echo "<div id=\"search_autor\">";
echo $lang[100].": <strong>".$ads['autor']."</strong>";
if($ads['user_id']!=0 || @$ads['email']) echo " <a class=\"sm gray\" href=\"#\" onclick=\"search_autor('".$ads['board_id']."','1');return false;\">(".$lang[807].")</a>";
echo "</div>";
echo $printcontacts.$printmail.$printurl;
?>
<?
$photo=mysql_query("SELECT photo_name FROM jb_photo WHERE id_message='".$ads['board_id']."'"); cq(); 
if(@mysql_num_rows($photo)){
	echo "<br /><br />";
	while($list_photo=mysql_fetch_assoc($photo)){
		?><div itemscope itemtype="https://schema.org/ImageObject"><?
		echo "<div style=\"background:#FFFFFF; padding:7px; border:2px dashed #EBEBEB; float:left; margin:3px\"><img alt=\"".$ads['text']."\" title=\"".$ads['title']."\" src=\"".$u."normal/".$list_photo['photo_name']."\" itemprop=\"contentUrl\" /></div>";
	}?></div><?
	echo "<div class=\"clear\"></div>";
}
if(@$ads['video'])echo "<div class=\"clear\"></div><br /><br />
<div title=\"VIDEO\">
<object type=\"application/x-shockwave-flash\" data=\"https://www.youtube.com/v/".$ads['video']."&amp;hl=ru&amp;fs=1\" width=\"425\" height=\"355\"><param name=\"movie\" value=\"https://www.youtube.com/v/".$ads['video']."&amp;hl=ru&amp;fs=1\" /><param name=\"wmode\" value=\"transparent\"></param><param name=\"FlashVars\" value=\"playerMode=embedded\" /></object>
</div>
<div class=\"clear\"></div><br />";
echo "<br /><div style=\"margin:10px;\">";
if(@$_SESSION['login']&& @$_SESSION['password']) echo "<script type=\"text/javascript\">var confirmmess='".$lang[172]."';</script><div style=\"padding:3px;\"><img class=\"absmid\" src=\"".$im."admin_edit.png\" alt=\"".$lang[1053]."\" /> <a target=\"_blank\" class=\"red b\" href=\"".$h."a/?action=ads&amp;op=edit&amp;id_mess=".$ads['board_id']."\">".$lang[1053]."</a> ".$lang[634]." <img class=\"absmid\" src=\"".$im."del.gif\" alt=\"".$lang[300]."\" /> <a target=\"_blank\" class=\"red b\" href=\"".$h."a/?action=ads&amp;op=del&amp;id_mess=".$ads['board_id']."\" onclick=\"return conformdelete(this,confirmmess);\">".$lang[300]."</a></div>";
if(($c['money_service']=="yes" || $c['wm_money_service']=="yes") && ($ads['checkbox_top']=="0"))echo "<div style=\"padding:3px;\"><img class=\"absmid\" src=\"".$im."vip.gif\" alt=\"".$lang[1098]."\" /> <a class=\"red b\" href=\"".$h."vip".$ads['board_id'].".html\" title=\"".$lang[1098]."\">".$lang[1098]."</a></div> ";
if(defined("USER") && $ads['user_id']==$user_data['id_user']) echo "<div style=\"padding:3px;\"><img class=\"absmid\" src=\"".$im."edit_board.gif\" alt=\"".$lang[549]."\" /> <a class=\"dgray\" href=\"".$h."cpanel-".$ads['board_id']."-edit.html\">".$lang[549]."</a></div>";
echo "<div style=\"padding:3px;\" id=\"addtonote\"><img class=\"absmid\" src=\"".$im."note.gif\" alt=\"".$lang[532]."\" /> <a class=\"dgray\" href=\"#\" onclick=\"addtonote('".$ads['board_id']."');return false;\">".$lang[532]."</a></div> ";
echo "<div style=\"padding:3px;\" id=\"addabuse\"><img src=\"".$im."moder_notice.gif\" class=\"absmid\" alt=\"".$lang[551]."\" /> <a class=\"dgray\" href=\"#\" onclick=\"addabuse('0','".$ads['board_id']."');return false;\">".$lang[551]."</a></div> ";
if($c['mail_friends']=="yes") echo "<div style=\"padding:3px;\" id=\"mail_friends\"><img src=\"".$im."mail_friends.gif\" class=\"absmid\" alt=\"".$lang[552]."\" /> <a class=\"dgray\" href=\"#\" onclick=\"mail_friends('0','0','".$ads['id_category']."','".$ads['board_id']."');return false;\">".$lang[552]."</a></div>";
echo "<div style=\"padding:3px;\"><img src=\"".$im."printer.gif\" class=\"absmid\" alt=\"".$lang[553]."\" /> <a class=\"dgray\" href=\"#\" onclick=\"window.open('".$h."print".$ads['id_category']."-".$ads['board_id'].".html','qq','resizable=yes, scrollbars=yes, width=560, height=700');\">".$lang[553]."</a> <span class=\"sm gray\">(".$lang[554].")</span></div>";
/* Поиск похожих объявлений */
echo "<div>";
echo "<a class=\"dgray\" href=\"".$h."?op=search&amp;query=".$ads['title']."\">".$lang[202202]."</a> ";
echo "</div>";
if($c['view_comments']=="yes"){
	$query_comments=mysql_query("SELECT autor,text,DATE_FORMAT(date,'%d.%m.%Y') as dat FROM jb_comments WHERE id_board='".$ads['board_id']."' AND old_mess='old' ORDER by id DESC");cq();
	if($query_comments)$count_comments=mysql_num_rows($query_comments);
	$count_c=($count_comments)?"(".$count_comments.")":"";
	if(@$count_comments){
		echo "<div style=\"padding:3px;\"><img src=\"".$im."comments.gif\" class=\"absmid\" alt=\"".$lang[555]."\" /> <a class=\"dgray\" href=\"#\" onclick=\"details('d111');return false;\">".$lang[555]."</a> <span class=\"sm gray\">".$count_c."</span></div><div id=\"d111\">";
		while($comments=mysql_fetch_assoc($query_comments)) echo "<div class=\"comments\"><img src=\"".$im."comments.gif\" class=\"absmid\" alt=\"".$comments['dat']."\" /> <span class=\"gray\">".$comments['dat']." <strong>".$comments['autor']."</strong></span><br /><br />".nl2br($comments['text'])."</div>";
		echo "</div>";
	}
}
?>

<div class="form-wrapper">
<h3>Комментарии и отзывы</h3>
</div>
<?
if($c['add_comments']=="yes") echo "<div style=\"padding:3px;\" id=\"add_comments\"><img src=\"".$im."comment.gif\" class=\"absmid\" alt=\"".$lang[557]."\" /> <a class=\"dgray\" href=\"#\" onclick=\"add_comments('".$ads['board_id']."',0,0);return false;\">".$lang[557]."</a></div>";
echo "</div>";
if($ads['tags'] && $c['print_keywords']=="yes"){
	$arr_tags=explode(", ",$ads['tags']);
	if(sizeof($arr_tags)!=0){
		echo "<div class=\"searchtags\">".$lang[202].": ";
		foreach($arr_tags as $key => $value) echo "<a href=\"".$h."?op=search&amp;query=".$value."\">".$value."</a> ";
		echo "</div>";
	}
}
echo "<div class=\"alcenter\"><br />";
$prev=mysql_query("SELECT id FROM jb_board WHERE id_category='".$ads['id_category']."' AND id<'".$ads['board_id']."' ORDER by id DESC LIMIT 1");cq(); 
if(mysql_num_rows($prev)){
	$pr=mysql_fetch_assoc($prev);
	echo "<img class=\"absmid\" src=\"".$im."l_or_arr.gif\" alt=\"".$lang[813]."\" /> <a class=\"orange b\" href=\"".$h."c".$ads['id_category']."-".$pr['id'].".html\" title=\"".$lang[813]."\">".$lang[813]."</a> ";
}
echo " &nbsp; &nbsp; ";
$next=mysql_query("SELECT id FROM jb_board WHERE id_category='".$ads['id_category']."' AND id>'".$ads['board_id']."' ORDER by id ASC LIMIT 1");cq(); 
if(mysql_num_rows($next)){
	$n=mysql_fetch_assoc($next);
	echo "<a class=\"orange b\" href=\"".$h."c".$ads['id_category']."-".$n['id'].".html\" title=\"".$lang[812]."\">".$lang[812]."</a> <img class=\"absmid\" src=\"".$im."r_or_arr.gif\" alt=\"".$lang[812]."\" />";
}
echo "</div><div class=\"clear\"></div>";
mysql_query("UPDATE jb_board SET hits=hits+1 WHERE id=".$ads['board_id']." LIMIT 1");cq();
?>
</div>
<div class="form-wrapper">
<div class="cornhc">
<h3>Поделиться этим объявлением</h3>
</div></br>
<center>
<script type="text/javascript">(function() {
  if (window.pluso)if (typeof window.pluso.start == "function") return;
  if (window.ifpluso==undefined) { window.ifpluso = 1;
    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
    s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
    s.src = ('https:' == window.location.protocol ? 'https' : 'https')  + '://share.pluso.ru/pluso-like.js';
    var h=d[g]('body')[0];
    h.appendChild(s);
  }})();</script>
<div class="pluso" data-background="none;" data-options="medium,square,line,horizontal,counter,sepcounter=1,theme=14" data-services="vkontakte,odnoklassniki,bookmark,facebook,twitter,google,moimir"></center></div></div>