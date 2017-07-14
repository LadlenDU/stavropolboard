<?
##############################################################################################################
###################                                                                     ######################
###################    Установка и настройка Joker Board Commercial 3 ==> ICQ:183917    ######################
###################                                                                     ######################
##############################################################################################################

require_once("../admin/conf.php");
require_once("jshttprequest.php");
$JsHttpRequest=new JsHttpRequest("utf-8");
$host=parse_url(@$_SERVER['HTTP_REFERER']); if(@$host['host']!=@$_SERVER['HTTP_HOST'])die();
if (ctype_digit(@$_REQUEST['idmess'])>0){
	$q=mysql_query("SELECT user_id, autor, email FROM jb_board WHERE id='".intval($_REQUEST['idmess'])."' LIMIT 1");
	if(mysql_num_rows($q)){
		$GLOBALS['_RESULT']="<div style=\"padding:5px;margin:5px;border:2px #66FF00 solid;\">";
		$d=mysql_fetch_assoc($q);
		if($d['user_id']!=0){
			if(@$d['email'])$subq="OR email='".$d['email']."'";else $subq="";
			$result=mysql_query("SELECT id FROM jb_board WHERE user_id='".$d['user_id']."' ".$subq);
			if(@$result)$total_rows=mysql_num_rows($result);
			if(@$total_rows){
				if(@$_REQUEST['page']>'0')$page=intval($_REQUEST['page']);else $page=1;
				$tot=($total_rows-1)/5;$total=intval($tot+1);if($page>$total)$page=$total;$start=$page*5-5;
				$query=mysql_query("SELECT id,id_category,title FROM jb_board WHERE user_id='".$d['user_id']."' ".$subq." ORDER by id DESC LIMIT ".$start.",5");	
				if(mysql_num_rows($query)){
					$GLOBALS['_RESULT'].="<strong>".$d['autor']."</strong> <span class=\"sm gray\">(".$total_rows." ".PluralForm($total_rows,$lang[262],$lang[263],$lang[264]).")</span><br /><br />";
					while($l=mysql_fetch_assoc($query)) $GLOBALS['_RESULT'].="<img class=\"absmid\" src=\"".$im."orstar.gif\" /> <a href=\"".$h."c".$l['id_category']."-".$l['id'].".html\">".$l['title']."</a><br />";
					if ($total_rows>=5){
						$a="<a href=\"#\" onclick=\"search_autor('".intval($_REQUEST['idmess'])."','";
						$z="');return false;\"";
						if($page!=1)$pervpage=$a."1".$z." title=\"".$lang[174]."\">&nbsp;&nbsp;&nbsp;&#171;&nbsp;&nbsp;&nbsp;</a> ";
						if($page!=$total) $nextpage=$a.$total.$z." title=\"".$lang[175]."\">&nbsp;&nbsp;&nbsp;&#187;&nbsp;&nbsp;&nbsp;</a>";		
						$pageleft="";$pageright="";
						for($i=$c['limit_pagination_on_page'];$i>=1;$i--)if($page-$i>0)$pageleft.=$a.($page-$i).$z.">".($page-$i)."</a>";
						for($i=1;$i<=$c['limit_pagination_on_page'];$i++)if($page+$i<=$total)$pageright.=$a.($page+$i).$z.">".($page+$i)."</a>"; 
						$GLOBALS['_RESULT'].="<div class=\"pagination\">".@$pervpage.@$pageleft."<b><span class=\"current\">".$page."</span></b>".@$pageright.@$nextpage."</div>";
					}
				} else $GLOBALS['_RESULT'].="<span class=\"b red\">".$lang[8099]."</span>";
			} else $GLOBALS['_RESULT'].="<span class=\"b red\">".$lang[8099]."</span>";
		} else $GLOBALS['_RESULT'].="<span class=\"b red\">".$lang[8099]."</span>";
		$GLOBALS['_RESULT'].="</div>";
	} else $GLOBALS['_RESULT'].="<span class=\"b red\">".$lang[8099]."</span>";
} else $GLOBALS['_RESULT']="<span class=\"red b\">".$lang[98]."</span>";
?>
