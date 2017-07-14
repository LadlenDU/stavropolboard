<?
require_once("admin/conf.php");
# записываем в переменную открывающие блоки структуры дизайна
# просто чтобы не выводить по сто раз одно и тоже - будет выводить эту переменную
$design_div="<div class=\"container\"><article><div class=\"subcontainer\"><div class=\"centercolumn\"><!--startcontent-->";
if($c['board_works']=="only_admin"){
	if(@$_SESSION['login']&& @$_SESSION['password']){
		$admins=mysql_query("SELECT * FROM jb_admin");cq();
		$adminsdata=mysql_fetch_assoc($admins);
		if($_SESSION['login']!=$adminsdata['login'] || md5($_SESSION['password'])!=$adminsdata['password'])
		die("<div align=\"center\" style=\"margin-top:150px;\">".$lang[1118]."</div>");
	}else die("<div align=\"center\" style=\"margin-top:150px;\">".$lang[1118]."</div>");
}
if(ctype_digit(@$_GET['id_cat']) && ctype_digit(@$_GET['id_mess']) && !@$_GET['op']){
	$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat';
	$query_mess=mysql_query("SELECT jb_board.id AS board_id, UNIX_TIMESTAMP(jb_board.date_add) as unix_time, DATE_FORMAT(jb_board.date_add,'%d.%m.%Y') as dat, jb_board.*, jb_board_cat.id, jb_board_cat.root_category, jb_board_cat.".$name_cat.", jb_city.city_name, jb_city.en_city_name FROM jb_board LEFT JOIN jb_board_cat ON jb_board.id_category=jb_board_cat.id LEFT JOIN jb_city ON jb_board.city_id=jb_city.id WHERE jb_board.id=".$_GET['id_mess']." AND jb_board.old_mess='old' LIMIT 1"); cq();
	if(mysql_num_rows($query_mess)){
		$ads=mysql_fetch_assoc($query_mess);
		define("USTITLE",$ads['title'].", ".$lang[164]);
		define("USKEYWORDS",$ads['title'].", ".$lang[164]);
		define("USDESCRIPTION",utf8_substr($ads['text'],0,120));
		require_once("inc/head.inc.php");
		require_once("inc/top.inc.php");
		echo $design_div; // вывели открывающие блоки дизайна
		if($ads['root_category'] != 0){
			$navparent=$ads['root_category'];
			while($navparent != 0){ 
				$q_nav=mysql_query("SELECT id,root_category,".$name_cat." FROM jb_board_cat WHERE id='".$navparent."'");cq();    
				if(@mysql_num_rows($q_nav)){
					$cat_nav=mysql_fetch_assoc($q_nav); 
					$navparent=$cat_nav['root_category']; 
					$links[]="<a href=\"".$h."c".$cat_nav['id'].".html\">".$cat_nav[$name_cat]."</a> &rarr; ";
				}
			}
			echo "<div class=\"alcenter\">";
			if(is_array(@$links)) echo implode('',array_reverse($links));
			echo "<a href=\"".$h."c".$ads['id_category'].".html\">".$ads[$name_cat]."</a></div><br />";		
		}
		$page_uri="mess_".$_GET['id_mess'].JBLANG;
		if($JBSCACHE=="1"){
			$flnm=$cdir.$page_uri;
			ob_start();
			if(!$printmess=readData($flnm,$JBSCACHE_expire)){
				require_once("inc/message.inc.php");
				$printmess=ob_get_contents(); ob_clean();
				writeData($flnm,$printmess);
			}echo $printmess;
		} else require_once("inc/message.inc.php");
	} else { require_once("inc/head.inc.php"); require_once("404.php"); die();}

}
elseif(ctype_digit(@$_GET['id_cat']) && !@$_GET['id_mess'] && !@$_GET['op']){
	$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat';
	$querycattitle=mysql_query("SELECT id, root_category, child_category, ".$name_cat.", description FROM jb_board_cat WHERE id='".$_GET['id_cat']."'"); cq();
	if(@mysql_num_rows($querycattitle)){
		$cattitle=mysql_fetch_assoc($querycattitle);
		if(defined('USER_CITY_TITLE')){
			define("USTITLE", $cattitle[$name_cat].", ".USER_CITY_TITLE);
			define("USKEYWORDS",$cattitle['description']);
			define("USDESCRIPTION",USER_CITY_TITLE.", ".$cattitle[$name_cat].": ".$cattitle['description']);
		}else{
			define("USTITLE", $cattitle[$name_cat]." - "."объявления Ставрополь");
			define("USKEYWORDS",$cattitle['description']);
			define("USDESCRIPTION",$cattitle[$name_cat].": ".$cattitle['description']);
		}
		require_once("inc/head.inc.php");
		require_once("inc/top.inc.php");
		echo $design_div; // вывели открывающие блоки дизайна
		if($cattitle['root_category'] != 0){
			$navparent=$cattitle['root_category'];
			while($navparent != 0){ 
				$q_nav=mysql_query("SELECT id,root_category,".$name_cat." FROM jb_board_cat WHERE id='".$navparent."'");cq();    
				if(@mysql_num_rows($q_nav)){
					$cat_nav=mysql_fetch_assoc($q_nav); 
					$navparent=$cat_nav['root_category']; 
					$links[]="<a href=\"".$h."c".$cat_nav['id'].".html\">".$cat_nav[$name_cat]."</a> &rarr; ";
				}
			}
			echo "<div class=\"alcenter\">";
			if(is_array(@$links)) echo implode('',array_reverse($links));
			echo "<a href=\"".$h."c".$cattitle['id'].".html\">".$cattitle[$name_cat]."</a></div><br />";
		}
		if($cattitle['child_category']==1){
			if(defined('JBCITY')) $page_uri="c".$_GET['id_cat']."_".JBLANG.JBCITY;
			else $page_uri="c".$_GET['id_cat']."_".JBLANG;
			require_once("inc/list_subcat.inc.php");
		}else{
			if(ctype_digit(@$_GET['page']) && @$_GET['page']>0) $page=$_GET['page'];else $page=1;
			if($page<=$limit_pages_in_cache) $start_filename="-p".$page;else $start_filename="";
			if(defined('JBCITY')) $page_uri="c".$_GET['id_cat'].$start_filename."_".JBLANG.JBCITY;
			else $page_uri="c".$_GET['id_cat'].$start_filename."_".JBLANG;
			if($JBSCACHE=="1" && $page <= $limit_pages_in_cache && !@$_GET['price']){
				$flnm=$cdir.$page_uri;
				ob_start();
				if(!$cat=readData($flnm,$JBSCACHE_expire)){
					require_once("inc/list_ads.inc.php");
					$cat=ob_get_contents();
					ob_clean();
					writeData($flnm,$cat);
				} echo $cat;
			} else require_once("inc/list_ads.inc.php");
		}
	} else {header('https/1.0 404 Not Found');
    include("404.php");die();}
}
elseif(!@$_GET['id_cat'] && !@$_GET['id_mess'] && !@$_GET['op']){
	require_once("inc/head.inc.php");
	require_once("inc/top.inc.php");
	echo $design_div; // вывели открывающие блоки дизайна
	if(defined('JBCITY'))$page_uri="index_".JBLANG.JBCITY;
	else $page_uri="index_".JBLANG;
	if($JBSCACHE=="1"){
		$flnm=$cdir.$page_uri;
		ob_start();
		if(!$cat_index=readData($flnm,$JBSCACHE_expire)){
			require_once("inc/cat_index.inc.php");
			$cat_index=ob_get_contents(); ob_clean();
			writeData($flnm,$cat_index);
		}echo $cat_index;
	} else require_once("inc/cat_index.inc.php");


if(defined('JBCITY'))$page_uri="last_add_in_main_".JBLANG.JBCITY;else $page_uri="last_add_in_main_".JBLANG;
        if($JBSCACHE=="1"){
                $flnm=$cdir.$page_uri;
                ob_start();
                if(!$adv_index=readData($flnm,$JBSCACHE_expire)){
                        require_once("inc/last_add_in_main.inc.php");
                        $adv_index=ob_get_contents(); ob_clean();
                        writeData($flnm,$adv_index);
                }echo $adv_index;
        } else require_once("inc/last_add_in_main.inc.php");
}
elseif(@$_GET['op']=="newlist"){
	if(ctype_digit(@$_GET['page']) && @$_GET['page']>0) $page=$_GET['page'];else $page=1;
	if($page <= $limit_pages_in_cache) $start_filename="-p".$page;	else $start_filename="";
	if(defined('JBCITY')) $page_uri="newlist".$start_filename."_".JBLANG.JBCITY;
	else $page_uri="newlist".$start_filename."_".JBLANG;
	define("USTITLE", $lang[600].", ".$page." ".$lang[1006]);
	require_once("inc/head.inc.php");
	require_once("inc/top.inc.php");
	echo $design_div; // вывели открывающие блоки дизайна
	if($JBSCACHE=="1" && $page <= $limit_pages_in_cache){
		$flnm=$cdir.$page_uri;
		ob_start();
		if(!$adv_index=readData($flnm,$JBSCACHE_expire)){
			require_once("inc/last_add.inc.php");
			$adv_index=ob_get_contents(); ob_clean();
			writeData($flnm,$adv_index);
		}echo $adv_index;
	} else require_once("inc/last_add.inc.php");
}
elseif(@$_GET['op']=="add"){
define("USTITLE","Подать объявление Ставрополь");
	require_once("inc/head.inc.php");
	require_once("inc/top.inc.php");
	echo $design_div; // вывели открывающие блоки дизайна
	if($c['add_new_ads']=="no") echo "<div class=\"alcenter orange\"><h1>".$lang[1119]."</h1></div>";	
	else{
		if($c['add_new_only_user']=="yes"){
			if(!defined('USER')){
			setcookie('jbnocache','1',time()+60,"/");
			header("location: ".$h."login.html");
			}else{
			define("ADDNEW","reg_user");
			require_once("inc/add_new.inc.php");
			}
		}else{
			define("ADDNEW","no_reg");
			require_once("inc/add_new.inc.php");
		}
	}
}
elseif(ctype_digit(@$_GET['id_cat']) && ctype_digit(@$_GET['id_mess']) && @$_GET['op']=="print"){
	$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?'en_name_cat':'name_cat';
	$query_mess=mysql_query("SELECT jb_board.id AS board_id, UNIX_TIMESTAMP(jb_board.date_add) as unix_time, DATE_FORMAT(jb_board.date_add,'%d.%m.%Y') as dat, jb_board.*, jb_board_cat.id, jb_board_cat.root_category, jb_board_cat.".$name_cat.", jb_city.city_name, jb_city.en_city_name FROM jb_board LEFT JOIN jb_board_cat ON jb_board.id_category=jb_board_cat.id LEFT JOIN jb_city ON jb_board.city_id=jb_city.id WHERE jb_board.id=".$_GET['id_mess']." AND jb_board.old_mess='old' LIMIT 1"); cq();
	if(mysql_num_rows($query_mess)){
		$ads=mysql_fetch_assoc($query_mess);
		define("USTITLE",$ads['title'].", ".$ads['city']);
		define("USKEYWORDS",$ads['title'].", ".$ads['city']);
		define("USDESCRIPTION",utf8_substr($ads['text'],0,120));
		require_once("inc/print_message.inc.php");
		$page_uri="printmess_".$_GET['id_mess'].JBLANG;
	}
	else {header('https/1.0 404 Not Found');
    include("404.php");die();}
}
elseif(@$_GET['op']=="account"){
	define("USTITLE",$lang[841]);	
	require_once("inc/head.inc.php");
	require_once("inc/top.inc.php");
	echo $design_div; // вывели открывающие блоки дизайна
	require_once("inc/login.inc.php");
}
elseif(@$_GET['op']=="cpanel"){
	define("USTITLE",$lang[841]);	
	require_once("inc/head.inc.php");
	require_once("inc/top.inc.php");
	echo $design_div; // вывели открывающие блоки дизайна
	require_once("inc/user.inc.php");
}
elseif(@$_GET['op']=="vip"){
	define("USTITLE",$lang[510]);
	require_once("inc/head.inc.php");
	require_once("inc/top.inc.php");
	echo $design_div; // вывели открывающие блоки дизайна
	if(ctype_digit(@$_GET['id_mess']) && @$_GET['id_mess']>"0"){
		if($c['money_service']=="yes" || $c['wm_money_service']=="yes") require_once("inc/vip_info.inc.php");
		else echo "<div align=\"center\"><h2>".$lang[1120]."</h2></div>";
	} else echo "<div align=\"center\"><h2>".$lang[1032]."</h2></div>";
}
elseif(@$_GET['op']=="note"){
	define("USTITLE",$lang[501]);
	require_once("inc/head.inc.php");
	require_once("inc/top.inc.php");
	echo $design_div; // вывели открывающие блоки дизайна
	require_once("inc/note.inc.php");
}
elseif(@$_GET['op']=="informers"){
	define("USTITLE",$lang[1014]);
	$page_uri="informers";
	require_once("inc/head.inc.php");
	require_once("inc/top.inc.php");
	echo $design_div; // вывели открывающие блоки дизайна
	require_once("inc/informers.inc.php");
}
elseif(@$_GET['op']=="rss_export"){
	define("USTITLE","RSS");
	$page_uri="rss_export";
	require_once("inc/head.inc.php");
	require_once("inc/top.inc.php");
	echo $design_div; // вывели открывающие блоки дизайна
	require_once("inc/rss.inc.php");
}
elseif(@$_GET['op']=="search"){
	define("USTITLE",$_GET['query']);
	require_once("inc/head.inc.php");
	require_once("inc/top.inc.php");
	echo $design_div; // вывели открывающие блоки дизайна
	require_once("inc/search.inc.php");
}
elseif(@$_GET['op']=="contacts"){
	define("USTITLE",$lang[254]);
	$page_uri="contacts";
	require_once("inc/head.inc.php");
	require_once("inc/top.inc.php");
	echo $design_div; // вывели открывающие блоки дизайна
	require_once("inc/contacts.inc.php");
}
elseif(@$_GET['op']=="news"){
	if(ctype_digit(@$_GET['id'])){
		$query_news=mysql_query("SELECT DATE_FORMAT(jb_news.date,'%d.%m.%Y') as dat, jb_news.* FROM jb_news WHERE id='".$_GET['id']."'");cq(); 
		$news_arr=mysql_fetch_assoc($query_news);
		define("USTITLE",$news_arr['title']);
		if(@$news_arr['keywords'])define("USKEYWORDS",$news_arr['keywords']);
		else define("USKEYWORDS",$news_arr['title']);
		if(@$news_arr['descr'])define("USDESCRIPTION",$news_arr['descr']);
		else define("USDESCRIPTION",$news_arr['short']);
		$page_uri="news_".$_GET['id'];
	} else define("USTITLE",$lang[142]);
	require_once("inc/head.inc.php");
	require_once("inc/top.inc.php");
	echo $design_div;// вывели открывающие блоки дизайна
	require_once("inc/news.inc.php");
}
elseif(@$_GET['op']=="addnews"){
	$page_uri="addnews";
	define("USTITLE",$lang[292]);
	require_once("inc/head.inc.php");
	require_once("inc/top.inc.php");
	echo $design_div;// вывели открывающие блоки дизайна
	require_once("inc/addnews.inc.php");
}
elseif(@$_GET['op']=="content"){
	if(ctype_digit(@$_GET['id'])){
		$query_content=mysql_query("SELECT * FROM jb_page WHERE id='".$_GET['id']."'");cq(); 
		$content_arr=mysql_fetch_assoc($query_content);
		define("USTITLE",$content_arr['title']);
		if(@$content_arr['keywords'])define("USKEYWORDS",$content_arr['keywords']);
		else define("USKEYWORDS",$content_arr['title']);
		if(@$content_arr['descr'])define("USDESCRIPTION",$content_arr['descr']);
		else define("USDESCRIPTION",$content_arr['title']);
		$page_uri="content_".$_GET['id'];
		require_once("inc/head.inc.php");
		require_once("inc/top.inc.php");
		echo $design_div;// вывели открывающие блоки дизайна
		require_once("inc/content.inc.php");
	} else {header('https/1.0 404 Not Found');
    include("404.php");die();}
}
elseif(@$_GET['op']=="noteprint") require_once("inc/noteprint.inc.php");
if(@$_GET['op']=="print"||@$_GET['op']=="noteprint")die();
?><!--/endcontent--><?
if($JBKCACHE=="1" && @$page_uri!=""){require_once("core/cacheengine.php");}
?></div><!-- end centercolumn --><!-- end leftcolumn --><div class="clear"></div></div></article><!-- end subcontainer --><aside><div class="rightcolumn"><?
if($c['print_vip']=="yes")require_once("inc/vip.inc.php");
if($c['clouds_tags']=="yes"){
	$page_uri="clouds_tags";
	if($JBSCACHE=="1"){
		$flnm=$cdir.$page_uri;
		ob_start();
		if(!$printmess=readData($flnm,$JBSCACHE_expire)){
			require_once("inc/clouds_tags.inc.php");
			$printmess=ob_get_contents(); ob_clean();
			writeData($flnm,$printmess);
		}echo $printmess;
	} else require_once("inc/clouds_tags.inc.php");
}
if($c['print_news']=="yes")require_once("inc/news_announcement.inc.php");
if($c['print_stat']=="yes"){
	$page_uri="stat";
	if($JBSCACHE=="1"){
		$flnm=$cdir.$page_uri;
		ob_start();
		if(!$printmess=readData($flnm,$JBSCACHE_expire)){
			require_once("inc/stat.inc.php");
			$printmess=ob_get_contents(); ob_clean();
			writeData($flnm,$printmess);
		}echo $printmess;
	} else require_once("inc/stat.inc.php");
}
?>
<?
require_once("inc/vkontakte.inc.php");
?>
</div>
</aside>
<!-- end rightcolumn --><div class="clear"></div></div><!-- end container --><?
require_once("inc/foot.inc.php");
?>