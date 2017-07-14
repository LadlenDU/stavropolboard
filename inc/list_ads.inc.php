<div class="form-wrapper">
<?
if (defined('JBCITY')) $subQuery=' AND jb_board.city_id = '.JBCITY; else $subQuery='';
$result = mysql_query ("SELECT id FROM jb_board WHERE old_mess = 'old' AND id_category = '".$_GET['id_cat']."' ".$subQuery);cq();
if (@$result) $total_rows = mysql_num_rows ($result);
if (@$total_rows){
        $tot=($total_rows-1)/$c['count_adv_on_index'];
        $total=intval($tot+1);
        if($page>$total) $page=$total;
        $start=$page*$c['count_adv_on_index']-$c['count_adv_on_index'];
    $last_add = mysql_query ("SELECT jb_board.id AS board_id, jb_board.id_category, jb_board.title, jb_board.prices, jb_board.price, jb_board.text, jb_board.city, DATE_FORMAT(jb_board.date_add,'%d.%m.%Y') AS dateAdd, jb_board.checkbox_top, jb_board.checkbox_select, jb_board_cat.id, jb_board_cat.".$name_cat.", jb_photo.photo_name, jb_city.city_name, jb_city.en_city_name FROM jb_board LEFT JOIN jb_board_cat ON jb_board.id_category = jb_board_cat.id LEFT JOIN jb_city ON jb_board.city_id = jb_city.id LEFT JOIN jb_photo ON jb_board.id = jb_photo.id_message WHERE jb_board.old_mess = 'old' AND jb_board_cat.id='".$_GET['id_cat']."' ".$subQuery." GROUP by board_id ORDER BY jb_board.checkbox_top DESC, jb_board.top_time DESC, jb_board.id DESC LIMIT ".$start.", ".$c['count_adv_on_index']);cq();
        if(mysql_num_rows($last_add)){
                ?>
<center><h1><? echo $cattitle[$name_cat] ?> объявления Ставрополь</h1></center></div>
				<div class="stradv b orange">
				 <div class="o0 alcenter">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lang[153]?></div>
				 <div class="o11 alcenter"><?=$lang[123]?></div>
				 <div class="o2 alcenter"><a href="<?=$h?>note.html">Блокнот</a></div>
				 <div class="o4 ">Цена</div>
				 <div class="clear"></div>
				</div>
				
				<?
                while ($last=mysql_fetch_assoc($last_add)){
				        ?><div class="<?=smsclass($last['checkbox_top'],$last['checkbox_select'])?>">
				
				<div class="o0 alcenter"><? echo($last['photo_name'])?"
                                
                                <img src=\"".$u."small/".$last['photo_name']."\" width=\"80\" height=\"80\" alt=\"photo\" />":"
                                
                                <img src=\"".$im."nofoto.gif\" width=\"80\" height=\"80\" alt=\"nophoto\" />";?></div>
						
						<div class="o1">
						<a class="b" title="<?=$last['title']?>" href="<?="c".$last['id_category']."-".$last['board_id']?>.html"><?=$last['title']?></a><br />
                        <? # Следующая строка выводит обрезанное содержание # ?>
                        <?=cutstring($last['text'],150)?>
                        </div>
            <div class="o2 alcenter">
			 <div id="addtonote_list_<?=$last['board_id']?>"><a title="<?=$lang[532]?>" class="dgray" href="#" onclick="addtonote_list('<?=$last['board_id']?>');return false;"><img class="absmid" src="<?=$im?>note.gif" alt="<?=$lang[532]?>" /></a></div>
			</div>                 
            <div class="o4">
			<? if($last['price']!=0) echo "<div class=\"b orange\">".$last['price']."$</div>"; ?></br>
			<? if($last['prices']!=0) echo "<div class=\"b orange\">".$last['prices']." руб.</div>"; ?>
			</div><div class="clear"></div></div><?
                }
                
                ?>
				<?
                
                if ($total_rows>=$c['count_adv_on_index']){
            $a="<a href=\"c".$_GET['id_cat']."-p";
            if($page!=1)$pervpage=$a."1.html\" title=\"".$lang[174]."\">&nbsp;&nbsp;&nbsp;«&nbsp;&nbsp;&nbsp;</a> ";
            if($page!=$total) $nextpage=$a.$total.".html\" title=\"".$lang[175]."\">&nbsp;&nbsp;&nbsp;»&nbsp;&nbsp;&nbsp;</a>";        
            $pageleft="";$pageright="";
            for($i=$c['limit_pagination_on_page'];$i>=1;$i--)if($page-$i>0)$pageleft.=$a.($page-$i).".html\">".($page-$i)."</a>";
            for($i=1;$i<=$c['limit_pagination_on_page'];$i++)if($page+$i<=$total)$pageright.=$a.($page+$i).".html\">".($page+$i)."</a>"; 
            echo "<div class=\"pagination\">".@$pervpage.@$pageleft."<b><span class=\"current\">".$page."</span></b>".@$pageright.@$nextpage."</div>";
        }
    }
}
else{
    ?><br /><div class="alcenter"><h1 class="orange"><? echo $cattitle[$name_cat]; if(defined('USER_CITY_TITLE')) echo ", ".USER_CITY_TITLE;?></h1><br /><br /><br /><br /><br /><br /><h3><?=$lang[130]?></h3><br /><br /><img class="absmid" src="<?=$im?>new.gif" alt="<?=$lang[155]?>" /> <a href="<?=$_GET['id_cat']?>-new.html"><?=$lang[155]?></a></div><?
}
?>