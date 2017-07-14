<div class="form-wrapper">
<?
if(@$_GET['query'] || ctype_digit(@$_GET['time']) || @$_GET['type'] || ctype_digit(@$_GET['city']) || @$_GET['images'] || ctype_digit(@$_GET['cat'])){
	if(@$_GET['query']!=""){
		if(is_utf8($_GET['query']))$gquery=trim($_GET['query']);
		else $gquery=cp1259_to_utf8(trim($_GET['query']));
		$gquery=strip_tags_smart($gquery);
		$gquery=preg_replace("/[^a-zа-яЁёі0-9\s]+/umi","",$gquery); 
		$gquery=htmlspecialchars($gquery);
		$gquery=cleansql($gquery);
		if(utf8_strlen($gquery)<3)die($lang[158]);
		$sq=array();
		$sq[]=$gquery;
		if(@!$_GET['nomorph']){
			class Lingua_Stem_Ru{
				var $VERSION="0.02";
				var $Stem_Caching=0;
				var $Stem_Cache=array();
				var $VOWEL='#аеиоуыэюя#u';
				var $PERFECTIVEGROUND='#((ив|ивши|ившись|ыв|ывши|ывшись)|((?<=[ая])(в|вши|вшись)))$#u';
				var $REFLEXIVE='#(с[яь])$#u';
				var $ADJECTIVE='#(ее|ие|ые|ое|ими|ыми|ей|ий|ый|ой|ем|им|ым|ом|его|ого|ему|ому|их|ых|ую|юю|ая|яя|ою|ею)$#u';
				var $PARTICIPLE='#((ивш|ывш|ующ)|((?<=[ая])(ем|нн|вш|ющ|щ)))$#u';
				var $VERB='#((ила|ыла|ена|ейте|уйте|ите|или|ыли|ей|уй|ил|ыл|им|ым|ен|ило|ыло|ено|ят|ует|уют|ит|ыт|ены|ить|ыть|ишь|ую|ю)|((?<=[ая])(ла|на|ете|йте|ли|й|л|ем|н|ло|но|ет|ют|ны|ть|ешь|нно)))$#u';
				var $NOUN='#(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|иям|ям|ием|ем|ам|ом|о|у|ах|иях|ях|ы|ь|ию|ью|ю|ия|ья|я)$#u';
				var $RVRE='#^(.*?[аеиоуыэюя])(.*)$#u';
				var $DERIVATIONAL='#[^аеиоуыэюя][аеиоуыэюя]+[^аеиоуыэюя]+[аеиоуыэюя].*(?<=о)сть?$#u';
				function s(&$s,$re,$to){$orig=$s;$s=preg_replace($re,$to,$s);return $orig !== $s;}
				function m($s,$re){return preg_match($re,$s);}			
				function stem_word($word){
					$word=utf8_strtolower($word);
					//$word=strtr($word,array('ё'=>'е'));
					$word=str_ireplace('ё','е',$word);
					if($this->Stem_Caching && isset($this->Stem_Cache[$word])){return $this->Stem_Cache[$word];}
					$stem=$word;
					do{
					  if(!preg_match($this->RVRE, $word, $p)) break;
					  $start=$p[1];$RV=$p[2];
					  if(!$RV) break;			
					  if(!$this->s($RV,$this->PERFECTIVEGROUND,'')){
						  $this->s($RV,$this->REFLEXIVE,'');
						  if($this->s($RV,$this->ADJECTIVE,'')){$this->s($RV,$this->PARTICIPLE,'');}
						  else{if(!$this->s($RV,$this->VERB,''))$this->s($RV,$this->NOUN,'');}
					  }$this->s($RV, '#и$#u', '');
					  if($this->m($RV, $this->DERIVATIONAL)) $this->s($RV, '#ость?$#u', '');
					  if(!$this->s($RV,'#ь$#u','')){$this->s($RV,'#ейше?#u','');$this->s($RV,'#нн$#u','н');}
					  $stem=$start.$RV;
					}while(false);
					if($this->Stem_Caching) $this->Stem_Cache[$word]=$stem;
					return $stem;
				}
				function stem_caching($parm_ref){
					$caching_level=@$parm_ref['-level'];
					if($caching_level){
						if(!$this->m($caching_level,'#^[012]$#u')){
							die(__CLASS__ . "::stem_caching() - Legal values are '0','1' or '2'. '$caching_level' is not a legal value");
						}$this->Stem_Caching=$caching_level;
					}return $this->Stem_Caching;
				}function clear_stem_cache(){$this->Stem_Cache=array();}
			}$arr_words=explode(" ",$gquery);
			if(count($arr_words)>=1){
				foreach($arr_words as $key=>$value){
					$key= new Lingua_Stem_Ru();
					$iter=$key->stem_word($value);
					$sq[]=trim($iter);
			}}else die("Query Error");
			unset($sq[0]);
		}
		$search_columns=array("jb_board.title","jb_board.text","jb_board.tags");
		if(@$_GET['l']=="or"){
			$logic=" AND ((1!=1) ";
			foreach($sq as $key=>$value){
				foreach($search_columns as $sckey=>$scvalue){
					$logic.=" OR ".$scvalue." LIKE '%".$value."%' ";
			}}$logic.=") ";
		}else{
			$logic=" AND ( (1!=1) ";
			foreach($search_columns as $sckey=>$scvalue){
				$logic.=" OR ( 1=1";
				foreach($sq as $key=>$value){
					$logic.=" AND ".$scvalue." LIKE '%".$value."%' ";
				}$logic.=") ";
			}$logic.=") ";
	}}else $logic="";
	if(@$_GET['time']=="0" || @$_GET['time']=="1" || @$_GET['time']=="7" || @$_GET['time']=="4" || @$_GET['time']=="6" || @$_GET['time']=="12"){
		if(@$_GET['time']==1)$search_time=" AND CURDATE()=DATE_FORMAT(jb_board.date_add,'%Y-%m-%d') "; 
		elseif(@$_GET['time']==7)$search_time=" AND DATE(jb_board.date_add)>=(CURDATE()-INTERVAL 7 DAY) ";
		elseif(@$_GET['time']==4)$search_time=" AND DATE(jb_board.date_add)>=(CURDATE()-INTERVAL 30 DAY) ";
		elseif(@$_GET['time']==6)$search_time=" AND DATE(jb_board.date_add)>=(CURDATE()-INTERVAL 180 DAY) ";
		elseif(@$_GET['time']==12)$search_time=" AND DATE(jb_board.date_add)>=(CURDATE()-INTERVAL 365 DAY) ";
		else $search_time="";
	}else $search_time="";
	if(@$_GET['type']=="0" || @$_GET['type']=="s" || @$_GET['type']=="p" || @$_GET['type']=="u" || @$_GET['type']=="o"){
		if($_GET['type']!="0")$search_type=" AND jb_board.type='".$_GET['type']."' ";
		else $search_type="";
	}else $search_type="";
	if(ctype_digit(@$_GET['city']) && @$_GET['city']>"1"){
		$query_country=mysql_query("SELECT id FROM jb_city WHERE parent=".$_GET['city']); cq(); 
		$numrows_cities=mysql_num_rows($query_country);
		if(@$numrows_cities){
			$in_city=array();
			while($arrcities=mysql_fetch_assoc($query_country))$in_city[]=$arrcities['id'];
			$search_city=" AND jb_board.city_id IN (".implode(',',$in_city).") ";
		}else $search_city=" AND jb_board.city_id='".$_GET['city']."' ";
		$other_city_column=" , jb_board.city ";
		$other_city_rightjoin="";
	}else{
		$search_city="";
		$other_city_column=" , jb_city.city_name, jb_city.en_city_name ";
		$other_city_rightjoin=" LEFT JOIN jb_city ON jb_board.city_id = jb_city.id ";
	}
	if(ctype_digit(@$_GET['cat']) && @$_GET['cat']>0){
		$GLOBALS['searchcat']="";
		function listcat3($id,$sub){
			$categories=mysql_query("SELECT id, child_category FROM jb_board_cat WHERE root_category=".$id); cq(); 
			$count=0;
			while($category=mysql_fetch_assoc($categories)){	
				if($category['child_category']==1)listcat3($category['id'],$sub+1);
				else{
					if($count==0)$delimiter="";else $delimiter=",";
					$GLOBALS['searchcat'].=$delimiter.$category['id'];
				}
				$count++;
		}}
		$categories=mysql_query("SELECT child_category FROM jb_board_cat WHERE id='".$_GET['cat']."'");cq();
		if(mysql_num_rows($categories)){
			while($category=mysql_fetch_assoc($categories)){
				if($category['child_category']==1)listcat3($_GET['cat'],1);
				else $GLOBALS['searchcat'].=$_GET['cat'];
		}}else die("ERROR_3");
		if(@$GLOBALS['searchcat']!="")$search_cat=" AND jb_board.id_category IN (".$GLOBALS['searchcat'].") ";else $search_cat="";
	}else $search_cat="";
	if(ctype_digit(@$_GET['lp'])&& @$_GET['lp']>0)$lp=$_GET['lp'];else $lp=20;
	if(@$_GET['from']|| @$_GET['before']){
		$search_price=" AND jb_board.price!=0 ";
		if(ctype_digit(@$_GET['from'])&& @$_GET['from']>0)$search_price.=" AND jb_board.price>='".$_GET['from']."' ";
		if(ctype_digit(@$_GET['before'])&& @$_GET['before']>0)$search_price.=" AND jb_board.price <='".$_GET['before']."' ";
	}else $search_price="";
	if(@$_GET['images']=="1")$zz="SELECT jb_board.id, jb_photo.id_photo ".@$other_city_column." FROM jb_board ".@$other_city_rightjoin." LEFT JOIN jb_photo ON jb_board.id=jb_photo.id_message WHERE jb_board.old_mess='old' ".$logic.$search_time.$search_city.$search_type.$search_cat.$search_price." GROUP by jb_board.id"; 
	else $zz="SELECT jb_board.id ".@$other_city_column." FROM jb_board ".@$other_city_rightjoin." WHERE jb_board.old_mess='old' ".$logic.$search_time.$search_city.$search_type.$search_cat.$search_price." GROUP by jb_board.id";
	$result=mysql_query($zz);cq();
	if(@$result)$total_rows=mysql_num_rows($result);
	if(@$total_rows){
		if(ctype_digit(@$_GET['page']) && @$_GET['page']>0)$page=$_GET['page'];else $page=1;
		$tot=($total_rows-1)/$lp;
		$total=intval($tot+1);
		if($page>$total)$page=$total;
		$start=$page*$lp-$lp;
		if(@$_GET['images']=="1")$zzz="SELECT jb_board.id AS board_id, jb_board.id_category, jb_board.text, jb_photo.photo_name, jb_board.title, DATE_FORMAT(jb_board.date_add,'%d.%m.%Y') AS dateAdd, jb_photo.photo_name ".@$other_city_column." FROM jb_board ".@$other_city_rightjoin."	LEFT JOIN jb_photo ON jb_board.id = jb_photo.id_message	WHERE jb_board.old_mess='old' ".$logic.$search_time.$search_city.$search_type.$search_cat.$search_price." GROUP by jb_board.id ORDER by board_id DESC LIMIT ".$start.", ".$lp;
		else $zzz="SELECT jb_board.id AS board_id, jb_board.id_category, jb_board.text, jb_board.prices, jb_board.price, jb_board.title, DATE_FORMAT(jb_board.date_add,'%d.%m.%Y') AS dateAdd ".@$other_city_column." FROM jb_board ".@$other_city_rightjoin." WHERE jb_board.old_mess='old' ".$logic.$search_time.$search_city.$search_type.$search_cat.$search_price." GROUP by jb_board.id ORDER by board_id DESC LIMIT ".$start.", ".$lp;
		$last_add=mysql_query($zzz);cq();
		$numr_last_add = mysql_num_rows($last_add);
		if(@$numr_last_add){
			?><center><h1><?=$_GET['query']?> Ставрополь</h1><br /><?=$total_rows." ".PluralForm($total_rows,$lang[262],$lang[263],$lang[264])?></center><br />

			<?
			while($last=mysql_fetch_assoc($last_add)){
			?>
<div class="stradv">
	<? $photo=mysql_query("SELECT photo_name FROM jb_photo WHERE id_message='".$last['board_id']."'"); cq();
$list_photo=mysql_fetch_assoc($photo);
$u."small/".$list_photo['photo_name'];?>
	<div class="o0 alcenter">
		<? echo(@$list_photo['photo_name'])?"<a href=\"c".$last['id_category']."-".$last['board_id'].".html\"><img class=\"thumb\" src=\"".$u."small/".$list_photo['photo_name']."\" alt=\"".$last['title']."\" /></a>":"<img class=\"thumb\" src=\"".$im."nofoto.gif\" alt=\"nophoto\" />";?>
	</div>
	<div class="o1">
		<a class="b" title="<?=$last['title']?>" href="<?="c".$last['id_category']."-".$last['board_id']?>.html"><?=$last['title']?></a>
		<? # Следующая строка выводит обрезанное содержание # ?>
		<p><?=cutstring($last['text'],300)?></p>	
	</div>
	<div class="o2 alcenter">
			 <div id="addtonote_list_<?=$last['board_id']?>"><a title="<?=$lang[532]?>" class="dgray" href="#" onclick="addtonote_list('<?=$last['board_id']?>');return false;"><img class="absmid" src="<?=$im?>note.gif" alt="<?=$lang[532]?>" /></a></div>
			</div>                 
            <div class="o4">
			<? if($last['price']!=0) echo "<div class=\"b orange\">".$last['price']."$</div>"; ?></br>
			<? if($last['prices']!=0) echo "<div class=\"b orange\">".$last['prices']." руб.</div>"; ?></br>
			</div>
	
</div>

		<? 
			}
			if($total_rows>=$lp){
				$a="<a href=\"".$h."?op=search";
				if(@$_GET['query'])$a.="&amp;query=".@$_GET['query'];
				if(@$_GET['l'])$a.="&amp;l=".$_GET['l'];
				if(@$_GET['nomorph'])$a.="&amp;nomorph=".@$_GET['nomorph'];
				if(@$_GET['cat'])$a.="&amp;cat=".@$_GET['cat'];
				if(@$_GET['type'])$a.="&amp;type=".@$_GET['type'];
				if(@$_GET['images'])$a.="&amp;images=".@$_GET['images'];
				if(@$_GET['city'])$a.="&amp;city=".@$_GET['city'];
				if(@$_GET['from'])$a.="&amp;from=".@$_GET['from'];
				if(@$_GET['before'])$a.="&amp;before=".@$_GET['before'];
				if(@$_GET['time'])$a.="&amp;time=".@$_GET['time'];
				$a.="&amp;page=";
				if($page!=1)$pervpage=$a."1\" title=\"".$lang[174]."\">&nbsp;&nbsp;&#171;&nbsp;&nbsp;</a> ";
				if($page!=$total)$nextpage=$a.$total."\" title=\"".$lang[175]."\">&nbsp;&nbsp;&#187;&nbsp;&nbsp;</a>";		
				$pageleft="";$pageright="";
				for($i=$c['limit_pagination_on_page'];$i>=1;$i--)if($page-$i>0)$pageleft.=$a.($page-$i)."\">".($page-$i)."</a>";
				for($i=1;$i<=$c['limit_pagination_on_page'];$i++)if($page+$i<=$total)$pageright.=$a.($page+$i)."\">".($page+$i)."</a>"; 
				echo "<div class=\"pagination\">".@$pervpage.@$pageleft."<b><span class=\"current\">".$page."</span></b>".@$pageright.@$nextpage."</div>";
		}}
	}else echo "<center><span class=\"red b\">".$lang[34]."</span>";
}else{
	?><div class="addform" align="center"><h1 class="alcenter"><?=$lang[179]?></h1><br /><br /><form method="get" action="<?=$h?>"><input type="hidden" name="op" value="search" /><div class="lc"><?=$lang[655]?></div><div class="rc"><input name="query" type="text" size="50" maxlength="49" /></div><div class="pad"></div><div class="lc"><?=$lang[1034]?></div><div class="rc"><input type="radio" name="l" value="and" checked="checked" /> <?=$lang[1035]?> &nbsp; <input type="radio" name="l" value="or" /> <?=$lang[1036]?> &nbsp; &nbsp; &nbsp; <input type="checkbox" name="nomorph" /> <?=$lang[1037]?></div><div class="pad"></div><div class="lc"><?=$lang[122]?></div><div class="rc"><select class="w99" name="cat"><option value="0"><?=$lang[539]?></option><?
	$name_cat=(defined('JBLANG') && constant('JBLANG')=='en')?"en_name_cat":"name_cat";
	$categories=mysql_query("SELECT id,".$name_cat." FROM jb_board_cat WHERE root_category=0 ORDER by sort_index");  cq();
	while($category=mysql_fetch_assoc($categories)) echo "<option value=\"".$category['id']."\">".$category[$name_cat]."</option>";
	?></select></div><div class="pad"></div><div class="lc"><?=$lang[114]?></div><div class="rc"><select class="w99" name="type"><option value="0"><?=$lang[656]?></option><option value="s"><?=$lang[414]?></option><option value="p"><?=$lang[413]?></option><option value="u"><?=$lang[800]?></option><option value="o"><?=$lang[801]?></option></select></div><div class="pad"></div><div class="lc"><?=$lang[106]?></div><div class="rc"><select class="w99" name="images"><option value="0"><?=$lang[656]?></option><option value="1"><?=$lang[657]?></option></select></div><div class="pad"></div><div class="lc"><?=$lang[163]?></div><div class="rc"><select name="city" class="w99"><option value="1"><?=$lang[164]?></option><?
	if(@constant('JBLANG')==="en")$qcity="en_city_name";else $qcity="city_name";
	$q_city=mysql_query("SELECT id,".$qcity." FROM jb_city WHERE parent=0 ORDER by sort_index");cq(); 
	while($city=mysql_fetch_assoc($q_city)){
	echo "<option style=\"font-weight:bold;\" value=\"".$city['id']."\">".$city[$qcity]."</option>";
	$q_city_ch=mysql_query("SELECT id,".$qcity." FROM jb_city WHERE parent='".$city['id']."' ORDER by sort_index");cq();
	if(mysql_num_rows($q_city_ch)){
	while($city_ch=mysql_fetch_assoc($q_city_ch)){
	echo "<option value=\"".$city_ch['id']."\"";
	if(defined('JBCITY') && $city_ch['id']==JBCITY) echo " selected=\"selected\" ";
	echo "> &nbsp; &nbsp; &nbsp; &nbsp; ".$city_ch[$qcity]."</option>";
	}}}
	?></select></div><div class="pad"></div><div class="lc"><?=$lang[1008]?> (<?=$lang[101010]?>)</div><div class="rc"><input type="text" name="from" onkeyup="ff2(this)" /> &mdash; <input type="text" name="before" onkeyup="ff2(this)" /></div><div class="pad"></div><div class="lc"><?=$lang[165]?></div><div class="rc"><select class="w99" name="time"><option value="0"><?=$lang[166]?></option><option value="1"><?=$lang[167]?></option><option value="7"><?=$lang[168]?></option><option value="4"><?=$lang[169]?></option><option value="6"><?=$lang[170]?></option><option value="12"><?=$lang[171]?></option></select></div><div class="pad"></div><br /><br /><div align="center"><input style="width:70%;" type="submit" value="<?=$lang[156]?>" /></div></form></div><br /><br /><br /><?
}
?>
</div>
<div class="form-wrapper">

</div>