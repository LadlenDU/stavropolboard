<div class="form-wrapper">

<?
if(!defined('USER')){setcookie('jbnocache','1',time()+60,"/");header("location: ".$h."login.html");}
if (@$_GET['act'] == "profile" && $user_data['activ']=="yes"){
	if (@$_POST['oldpass'] && @$_POST['npassone'] && @$_POST['npasstwo']){
		$host = parse_url($_SERVER['HTTP_REFERER']); if($host['host']!=$_SERVER['HTTP_HOST']) die();
		$_POST['npassone']=trim($_POST['npassone']);
		$_POST['npasstwo']=trim($_POST['npasstwo']);
		$_POST['oldpass']=trim($_POST['oldpass']);
		if ($_POST['npassone']!=$_POST['npasstwo']) die('<div class="alcenter red" style="margin:20px;">'.$lang[851].'<br /><a href="javascript:history.back(1)"> &larr; '.$lang[401].'</a></div>');
		$seluser = mysql_query ("SELECT pass FROM jb_user WHERE id_user='".@$user_data['id_user']."'");cq();
		if (@mysql_num_rows($seluser)){
			$seldata = mysql_fetch_assoc($seluser);
			if ($seldata['pass'] != md5($_POST['oldpass'])) die ('<div class="alcenter red" style="margin:20px;">'.$lang[852].'<br /><a href="javascript:history.back(1)"> &larr; '.$lang[401].'</a></div>');
		} else die ('<div class="alcenter red" style="margin:20px;">'.$lang[852].'<br /><a href="javascript:history.back(1)"> &larr; '.$lang[401].'</a></div>');
		if (!preg_match('/^[a-z0-9]+$/iu',$_POST['npasstwo'])) die('<div class="alcenter red" style="margin:20px;">'.$lang[853].'<br /><a href="javascript:history.back(1)"> &larr; '.$lang[401].'</a></div>');
		else $pass_update = md5($_POST['npasstwo']); cq();
		if (mysql_query ("UPDATE jb_user SET pass='".$pass_update."' WHERE id_user='".$user_data['id_user']."' LIMIT 1")){
			$_SESSION['id_user'] = $pass_update;
			if (@$_COOKIE['id_user']) setcookie("id_user",$_SESSION['id_user'],time()+77760000,"/");
			echo "<div class=\"alcenter\" style=\"margin:20px;\">".$lang[400]."<br /><a href=\"".$h."cpanel.html\">".$lang[854]."</a></div>";
		} else echo "<div class=\"alcenter\" style=\"margin:20px;\">".$lang[855]."<br /><br /><a href='javascript:history.back(1)'> &larr; ".$lang[401]."</a></div>";
	}
	else echo "<div class=\"alcenter\" style=\"margin:20px;\"><h1>".$lang[856]."</h1><br /><br /><form method=\"post\" action=\"".$h."profile.html\">".$lang[857].":<br /><input type=\"text\" name=\"oldpass\" /><br /><br />".$lang[858].":<br /><span class=\"red sm\">".$lang[859]."</span><br /><input type=\"text\" name=\"npassone\" /><br /><br />".$lang[860].":<br /><input type=\"text\" name=\"npasstwo\" /><br /><br /><input type=\"submit\" value=\"".$lang[15]."\" /></form></div>";
}else{
	if(@$user_data['activ']=="no"){
		if(@$_GET['act']=="accept_rules"){
			if(@$_POST['accept_rules']=="on"){
				if(mysql_query("UPDATE jb_user SET activ='yes' WHERE email='".$user_data['email']."'")){
					setcookie('jbnocache','1',time()+60,"/");
					header("location: ".$h."cpanel.html");
				} else die($lang[98]);
			} else echo "<h3>".$lang[847]."</h3><br /><br /><form action=\"".$h."accept_rules.html\" method=\"post\"><h1 class=\"alcenter\">".$lang[848]."</h1><br />".$lang['840']."<br /><br /><input type=\"checkbox\" name=\"accept_rules\" type=\"text\" /> ".$lang[849]." <br /><br /><input type=\"submit\" value=\"".$lang[850]."\" /><br /><br /></form>";
		}else{
			setcookie('jbnocache','1',time()+60,"/");
			header("location: ".$h."accept_rules.html");
		}	
	}else{
		echo "<center><h1>".$lang[861]."</h1></center><br /><br />";
		if(@$_GET['group']=="edit" && ctype_digit(@$_GET['id_mess'])){require_once("inc/user_edit.inc.php");}
		else if (@$_GET['group']=="prolongation" && ctype_digit(@$_GET['id_mess'])){
			if (mysql_query("UPDATE jb_board SET date_add = NOW() WHERE id = '".$_GET['id_mess']."'")){
				 echo "<center><h2>".$lang[400]."</h2></center>";cq();
			}
		}
		else if (@$_GET['group']=="del" && ctype_digit(@$_GET['id_mess'])){
			$p_del=mysql_query("SELECT id_photo, photo_name FROM jb_photo WHERE id_message = '".$_GET['id_mess']."'");cq();   
			if(@mysql_num_rows($p_del)){
				while($list=mysql_fetch_assoc($p_del)){
					if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$list['photo_name']))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$list['photo_name']);
					if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$list['photo_name']))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$list['photo_name']);
					mysql_query("DELETE FROM jb_photo WHERE id_photo = '".$list['id_photo']."' LIMIT 1");cq();
				}
			}
			$cat_cashe_clear=mysql_query("SELECT id_category FROM jb_board WHERE id='".$_GET['id_mess']."'");
			$arr_cat_cashe_clear=mysql_fetch_assoc($cat_cashe_clear);
			mysql_query("DELETE FROM jb_board WHERE id='".$_GET['id_mess']."' LIMIT 1");cq();
			mysql_query("DELETE FROM jb_abuse WHERE id_board='".$_GET['id_mess']."' LIMIT 1");cq();
			mysql_query("DELETE FROM jb_comments WHERE id_board='".$_GET['id_mess']."' LIMIT 1");cq();
			mysql_query("DELETE FROM jb_notes WHERE id_board='".$_GET['id_mess']."' LIMIT 1");cq();
			echo "<center><h2>".$lang[400]."</h2></center>";
			if($c['cache_clear']=="auto"){
				$dirname="./cache/";
				$dir=opendir($dirname);
				while($file=readdir($dir)){
					if($file!="." && $file!=".." && $file!=".htaccess" && (utf8_substr($file,0,8) == "newlist-" || $file=="clouds_tags" || $file=="kaleidoscope" || $file=="stat" || (utf8_substr($file,0,(utf8_strlen($arr_cat_cashe_clear['id_category'])+2)) == "c".$arr_cat_cashe_clear['id_category']."-")))unlink($dirname.$file);
				} closedir ($dir);
			}
		}
		else if (@$_GET['group']=="del" && @$_POST['board_check']){
			$impl=implode(', ',$_POST['board_check']);
			$p_del=mysql_query("SELECT id_photo,photo_name FROM jb_photo WHERE id_message IN (".$impl.")");   
			if(@mysql_num_rows($p_del)){
				while($list=mysql_fetch_assoc($p_del)){
					if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$list['photo_name']))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/small/".$list['photo_name']);
					if(file_exists($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$list['photo_name']))unlink($_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/upload/normal/".$list['photo_name']);
					mysql_query("DELETE FROM jb_photo WHERE id_photo = '".$list['id_photo']."' LIMIT 1");
				}
			}
			if($c['cache_clear']=="auto"){
				$ccdel=mysql_query("SELECT id_category FROM jb_board WHERE id IN (".$impl.")");cq();
				$ccdel_arr=array();
				while($list_ccdel=mysql_fetch_assoc($ccdel))$ccdel_arr[]=$list_ccdel['id_category'];
				$ccdel_arr=array_unique($ccdel_arr);
				$dirname="./cache/";
				$dir=opendir($dirname);
				while($file=readdir($dir)){
					if($file!="." && $file!=".." && $file!=".htaccess"){
						if(utf8_substr($file,0,8) == "newlist-" || $file=="clouds_tags" || $file=="kaleidoscope" || $file=="stat"){
							unlink($dirname.$file);
						}
						foreach($ccdel_arr as $k=>$v){
							if(utf8_substr($file,0,(utf8_strlen($v)+2)) == "c".$v."-"){unlink($dirname.$file);}
				}}}
				closedir ($dir);
			}
			mysql_query("DELETE FROM jb_board WHERE id IN (".$impl.")");
			mysql_query("DELETE FROM jb_abuse WHERE id_board IN (".$impl.")");
			mysql_query("DELETE FROM jb_comments WHERE id_board IN (".$impl.")");
			mysql_query("DELETE FROM jb_notes WHERE id_board IN (".$impl.")");
			echo "<center><h2>".$lang[400]."</h2></center>";
		}else{
			$result = mysql_query ("SELECT id FROM jb_board WHERE user_id = '".$user_data['id_user']."'");cq();  
			if ($result) $total_rows = mysql_num_rows ($result);
			if (@$total_rows){
				$limit=5;$tot=($total_rows-1)/$limit;$total=intval($tot+1);
				if(ctype_digit(@$_GET['page']) && @$_GET['page']>0) $page=$_GET['page'];else $page=1;
				if($page>$total) $page=$total;$start=$page*$limit-$limit;
				$name_cat=(defined('JBLANG')&& constant('JBLANG')=='en')?'en_name_cat':'name_cat';
				$query=mysql_query("SELECT jb_board.id AS board_id, jb_board.id_category, jb_board.title, jb_board.hits, jb_board.time_delete, DATE_FORMAT(jb_board.date_add,'%d.%m.%Y') AS dateAdd, UNIX_TIMESTAMP(jb_board.date_add) as unix_time, jb_board.checkbox_top, jb_board.checkbox_select, jb_board.time_delete, jb_board_cat.id, jb_board_cat.".$name_cat." FROM jb_board LEFT JOIN jb_board_cat ON jb_board.id_category = jb_board_cat.id WHERE jb_board.user_id = '".$user_data['id_user']."' ORDER BY jb_board.id DESC LIMIT ".$start.", ".$limit); cq();
				if (@mysql_num_rows ($query)){			
					?><script language="JavaScript">var confirmmess='<?=$lang[172]?>';</script><center><h2 class="orange"><?=$lang[816]?> (<?=$total_rows?>)</h2></center><br /><form method="post" action="<?=$h?>cpanel-del.html"><div class="stradv b orange"><div class="cp1 alcenter"><?=$lang[123]?></div><div class="cp2 alcenter"><?=$lang[406]?> <input type="checkbox" onclick="checkall(this)" /></div><div class="cp3 alcenter">&nbsp;</div><div class="cp4 alcenter"><?=$lang[127]?></div><div class="clear"></div></div><?
					while ($last=mysql_fetch_assoc($query)){
						?><div class="<?=smsclass($last['checkbox_top'],$last['checkbox_select'])?>">
						<div class="cp1"><a class="b" title="<?=$last['title']?>" href="<?="c".$last['id_category']."-".$last['board_id']?>.html"><?=$last['title']?></a><br /><span class="lgray sm"><?=$lang[122]?>: <?=$last[$name_cat]?><br /><?=$lang[544].": ".strftime('%d.%m.%Y',$last['time_delete']*86400+$last['unix_time'])?><br /><?=$lang[862]?>: <?=$last['hits']?></span></div><div class="cp2 alcenter"><input type="checkbox" value="<?=$last['board_id']?>" name="board_check[]" /></div><div class="cp3 alcenter"><? if(($c['money_service']=="yes" || $c['wm_money_service']=="yes") && ($last['checkbox_top']=="0" || $last['checkbox_select']=="0")) echo "<a href=\"vip".$last['board_id'].".html\" title=\"".$lang[510]."\"><img class=\"absmid\" src=\"".$im."vip.gif\" alt=\"".$lang[510]."\" /></a>";  else echo "&nbsp;"; ?> <a href="cpanel-<?=$last['board_id']?>-prolongation.html" title="<?=$lang[817]?>"><img class="absmid" src="<?=$im?>clock.png" alt="<?=$lang[817]?>" /></a> <a href="cpanel-<?=$last['board_id']?>-edit.html" title="<?=$lang[549]?>"><img class="absmid" src="<?=$im?>edit.gif" alt="<?=$lang[549]?>" /></a> <a onclick="return conformdelete(this,confirmmess);" href="cpanel-<?=$last['board_id']?>-del.html" title="<?=$lang[300]?>"><img class="absmid" src="<?=$im?>del.gif" alt="<?=$lang[300]?>" /></a></div><div class="cp4 alcenter"><? echo($last['dateAdd']==date("d.m.Y"))?$lang[531]:$last['dateAdd'];?></div><div class="clear"></div></div><? 
					}
					?><div style="text-align:right"><?=$lang[651]?>: <input onclick="return conformdelete(this,confirmmess);" type="submit" value="<?=$lang[300]?>" /></div></form><br /><br /><?
						if ($total_rows>=$limit){
						$a="<a href=\"cpanel-p";
						if($page!=1)$pervpage=$a."1.html\" title=\"".$lang[174]."\">&nbsp;&nbsp;&#171;&nbsp;&nbsp;</a> ";
						if($page!=$total) $nextpage=$a.$total.".html\" title=\"".$lang[175]."\">&nbsp;&nbsp;&#187;&nbsp;&nbsp;</a>";		
						$pageleft="";$pageright="";
						for($i=$c['limit_pagination_on_page'];$i>=1;$i--)if($page-$i>0)$pageleft.=$a.($page-$i).".html\">".($page-$i)."</a>";
						for($i=1;$i<=$c['limit_pagination_on_page'];$i++)if($page+$i<=$total)$pageright.=$a.($page+$i).".html\">".($page+$i)."</a>"; 
						echo "<div class=\"pagination\">".@$pervpage.@$pageleft."<b><span class=\"current\">".$page."</span></b>".@$pageright.@$nextpage."</div>";
					}
				}
			}else echo "<center><h2 class=\"red\">".$lang[654]."</h1><br /><a href=\"".$h."new.html\">".$lang[155]."</a></center>";	
		}
	}
}
?></div>