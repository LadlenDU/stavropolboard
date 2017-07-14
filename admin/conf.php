<? 
$GLOBALS['p']="";
//Данные для подключения к БД
$host="localhost";				// Имя хоста
$bdname="host1512699";				// Имя БД
$bdlogin="host1512699";				// Логин к БД
$bdpassword="9tj3is81";		// Пароль к БД
setlocale (LC_ALL,'ru_RU.UTF8','ru_RU','russian');
define("REF",true);
session_start();
header("Content-Type: text/html; charset=utf-8");
$h="https://".$_SERVER['HTTP_HOST'].$GLOBALS['p']."/";
$u=$h."upload/";
$im=$h."images/";
$cdir="cache/";
$c_exp_dir="cache/exp/";
$stylecss="<link type=\"text/css\" href=\"".$im."style.css\" rel=\"stylesheet\" />";
$mainjs="<script type=\"text/javascript\" src=\"".$im."main.js\"></script>";
$GLOBALS['cq']=0;function cq(){$GLOBALS['cq']++;}
function gentime(){static $a;if($a==0)$a=microtime(true);else return(string)(microtime(true)-$a);}gentime();
$db=@mysql_connect($host,$bdlogin,$bdpassword);
if(!$db)die(mysql_error());
if(!@mysql_select_db($bdname,$db))die(mysql_error()); 	
mysql_query("SET NAMES utf8");cq();
$conf=mysql_query("SELECT * FROM jb_config");cq(); 
$c=@mysql_fetch_assoc($conf);
$limit_pages_in_cache=3;
$JBSCACHE=@$c['scache'];
$JBSCACHE_expire=@$c['scache_expire'];
$JBSCACHE_exp_expire=@$c['scache_exp_expire'];
$JBKCACHE=@$c['kcache'];
if(@$_COOKIE['jbnocache']=='1')$JBKCACHE="";
if(@!$_SESSION['email'] && @!$_SESSION['id_user']){
if(@$_COOKIE['email'] && @$_COOKIE['id_user']){
$_SESSION['email']=$_COOKIE['email'];
$_SESSION['id_user']=$_COOKIE['id_user'];
}}
if(@$_SESSION['email'] && @$_SESSION['id_user']){
$userquery=mysql_query ("SELECT * FROM jb_user WHERE email='".addslashes($_SESSION['email'])."'");cq();
if(@mysql_num_rows($userquery)){
$user_data=mysql_fetch_assoc($userquery);
if($_SESSION['id_user'] != $user_data['pass']){
unset ($_SESSION['email'],$_SESSION['id_user']); 
session_unregister (@$_SESSION['email']);
session_unregister (@$_SESSION['id_user']);
session_destroy();
}else{
define("USER",true);
}}}
if(ctype_alpha(@$_COOKIE['jblang'])){
if($_COOKIE['jblang']=="ru") require_once("ru.lang.php");
elseif($_COOKIE['jblang']=="en") require_once("en.lang.php");
else die();
}else{
setcookie ('jblang','ru',time()+77760000,"/");
require_once("ru.lang.php");
}
if(ctype_digit(@$_COOKIE['jbcity']) && @$_COOKIE['jbcity']>0){
define("JBCITY",$_COOKIE['jbcity']);
$qcityfromdef=mysql_query("SELECT city_name,en_city_name FROM jb_city WHERE id='".JBCITY."' LIMIT 1");cq();
$arrcityfromdef=@mysql_fetch_assoc($qcityfromdef);
if(@constant('JBLANG')==="en"){
define("USER_CITY_TITLE",$arrcityfromdef['en_city_name']);
define("USER_CITY_TITLE_TWO",$arrcityfromdef['city_name']);
}else{
define("USER_CITY_TITLE",$arrcityfromdef['city_name']);
define("USER_CITY_TITLE_TWO",$arrcityfromdef['en_city_name']);
}}
if((ctype_digit(@$_COOKIE['jbusernote']) && @$_COOKIE['jbusernote']>0) || defined('USER')){
	if(ctype_digit(@$_COOKIE['jbusernote']) && @$_COOKIE['jbusernote']>0 && !defined('USER'))$query_numr_note="SELECT id_board FROM jb_notes WHERE id_notes_user='".$_COOKIE['jbusernote']."'";
	elseif(!@$_COOKIE['jbusernote'] && defined('USER'))$query_numr_note="SELECT id_board FROM jb_notes WHERE user_id='".$user_data['id_user']."'";
	elseif(ctype_digit(@$_COOKIE['jbusernote']) && @$_COOKIE['jbusernote']>0 && defined('USER'))$query_numr_note="SELECT id_board FROM jb_notes WHERE user_id='".$user_data['id_user']."' OR user_id='".$user_data['id_user']."'";
	$qucn=mysql_query($query_numr_note); cq();
	$un_mnr=@mysql_num_rows(@$qucn);
	if(@$un_mnr)define("COUNT_USER_NOTES",$un_mnr); 
}
function pr(){
echo "<pre>_REQUEST: "; print_r ($_REQUEST); echo "</pre>";
echo "<pre>_FILES: "; print_r ($_FILES); echo "</pre>";
echo "<pre>_SESSION: "; print_r ($_SESSION); echo "</pre>";
}
function smsclass($vip,$sel){
if($vip==1)$class="topmess";
else{if($sel==1)$class="selectmess";
else $class="stradv";}
return $class;
}
function sendmailer($to,$from,$subject,$msg){
$s="=?utf-8?b?".base64_encode($subject)."?="; 
$headers="MIME-Version: 1.0"."\r\n"."Content-type: text/plain; charset=utf-8"."\r\n"."From: ".$from."\r\n"."Reply-To: ".$from."\r\n"."Return-Path: ".$from."\r\n"."X-Mailer: PHP/".phpversion()."\r\n"."Content-type: text/plain; charset=utf-8";
if($sendPass=mail($to,$s,$msg,$headers)) return true; else return false;
}
function PluralForm($n,$form1,$form2,$form5){
$n=abs($n)%100;$n1=$n%10;if($n>10 && $n<20) return $form5;
if($n1>1 && $n1<5) return $form2;if($n1==1) return $form1;return $form5;
}
function cleansql($input){
$input=trim($input);
if(get_magic_quotes_gpc())$input=stripslashes($input);
if(!is_numeric($input))$input=mysql_real_escape_string($input);
return $input;
}
function clean($input){
$input=strip_tags_smart($input);
$input=htmlspecialchars($input);
$input=cleansql($input);
return $input;
}
function translit($content){ 
$transA=array('А'=>'a','Б'=>'b','В'=>'v','Г'=>'g','Ґ'=>'g','Д'=>'d','Е'=>'e','Є'=>'e','Ё'=>'yo','Ж'=>'zh','З'=>'z','И'=>'i','І'=>'i','Й'=>'y','Ї'=>'y','К'=>'k','Л'=>'l','М'=>'m','Н'=>'n','О'=>'o','П'=>'p','Р'=>'r','С'=>'s','Т'=>'t','У'=>'u','Ў'=>'u','Ф'=>'f','Х'=>'h','Ц'=>'c','Ч'=>'ch','Ш'=>'sh','Щ'=>'sch','Ъ'=>'','Ы'=>'y','Ь'=>'','Э'=>'e','Ю'=>'yu','Я'=>'ya'); 
$transB=array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','ґ'=>'g','д'=>'d','е'=>'e','ё'=>'yo','є'=>'e','ж'=>'zh','з'=>'z','и'=>'i','і'=>'i','й'=>'y','ї'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ў'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'sch','ъ'=>'','ы'=>'y','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya','&quot;'=>'','&amp;'=>'','µ'=>'u','№'=>'');
$content=trim(strip_tags_smart($content)); 
$content=strtr($content,$transA); 
$content=strtr($content,$transB); 
$content=preg_replace("/\s+/ums","_",$content); 
$content=preg_replace('/[\-]+/ui','-',$content);
$content=preg_replace('/[\.]+/u','_',$content);
$content=preg_replace("/[^a-z0-9\_\-\.]+/umi","",$content); 
$content=str_replace("/[_]+/u","_",$content);	
return $content; 
}
function ru2en($content){ 
$transA=array('А'=>'A','Б'=>'B','В'=>'V','Г'=>'G','Ґ'=>'G','Д'=>'D','Е'=>'E','Є'=>'E','Ё'=>'Yo','Ж'=>'Zh','З'=>'Z','И'=>'I','І'=>'I','Й'=>'Y','Ї'=>'Y','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N','О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ў'=>'U','Ф'=>'F','Х'=>'H','Ц'=>'C','Ч'=>'Ch','Ш'=>'Sh','Щ'=>'Sch','Ъ'=>'','Ы'=>'Y','Ь'=>'','Э'=>'E','Ю'=>'Yu','Я'=>'Ya'); 
$transB=array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','ґ'=>'g','д'=>'d','е'=>'e','ё'=>'yo','є'=>'e','ж'=>'zh','з'=>'z','и'=>'i','і'=>'i','й'=>'y','ї'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ў'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'sch','ъ'=>'','ы'=>'y','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya','&quot;'=>'','&amp;'=>'','µ'=>'u','№'=>'');
$content=trim(strip_tags_smart($content)); 
$content=strtr($content,$transA); 
$content=strtr($content,$transB); 
return $content; 
}
function writeData($path,$data){
$fp=fopen($path,'w');if(!$fp){return false;}
$retries=0;$max_retries=10;
do{if($retries > 0){usleep(rand(1,10000));}$retries += 1;}
while (!flock($fp,LOCK_EX) and $retries <= $max_retries);
if($retries >= $max_retries){return false;}
fwrite($fp,"$data\n");flock($fp,LOCK_UN);fclose($fp);return true;
}
function readData($path,$expiry){
if(file_exists($path)){
if((time()-$expiry)>filemtime($path))return false;
$cache=file($path);return implode('',$cache);
}return false;
}
function split_punct($string){
$string=preg_replace("/(\.|\,|\)|\:|\;|\!|\?)/ui","\\1 ",$string);
$arr=explode(" ",$string);
for($i=0;$i<count($arr);$i++){
	$arr[$i]=trim($arr[$i]);
	if(utf8_strlen($arr[$i])>30) $arr[$i]=chunk_split($arr[$i],30," ");
}
$string=implode(' ',$arr);
return $string;
}
/**
 * @license  https://creativecommons.org/licenses/by-sa/3.0/
 * @author   Nasibullin Rinat <nasibullin at starlink ru>
 * @charset  ANSI
 * @version  1.0.0
 */
function utf8_chunk_split($string,$length=null,$glue=null){
    if(!is_string($string))trigger_error('A string type expected in first parameter, '.gettype($string).' given!',E_USER_ERROR);
    $length=intval($length);
    $glue=strval($glue);
    if($length<1)$length=76;
    if($glue==='')$glue="\r\n";
    if(!is_array($a=utf8_str_split($string, $length)))return false;
    return implode($glue,$a);
}
/**
* @license https://creativecommons.org/licenses/by-sa/3.0/
* @author Nasibullin Rinat, https://orangetie.ru/
* @charset ANSI
* @version 1.2.5
*/
function utf8_convert_case($s, $mode){
static $trans=array(
#en (английский латиница)
#CASE_UPPER=>case_lower
"\x41"=>"\x61", #A a
"\x42"=>"\x62", #B b
"\x43"=>"\x63", #C c
"\x44"=>"\x64", #D d
"\x45"=>"\x65", #E e
"\x46"=>"\x66", #F f
"\x47"=>"\x67", #G g
"\x48"=>"\x68", #H h
"\x49"=>"\x69", #I i
"\x4a"=>"\x6a", #J j
"\x4b"=>"\x6b", #K k
"\x4c"=>"\x6c", #L l
"\x4d"=>"\x6d", #M m
"\x4e"=>"\x6e", #N n
"\x4f"=>"\x6f", #O o
"\x50"=>"\x70", #P p
"\x51"=>"\x71", #Q q
"\x52"=>"\x72", #R r
"\x53"=>"\x73", #S s
"\x54"=>"\x74", #T t
"\x55"=>"\x75", #U u
"\x56"=>"\x76", #V v
"\x57"=>"\x77", #W w
"\x58"=>"\x78", #X x
"\x59"=>"\x79", #Y y
"\x5a"=>"\x7a", #Z z
#ru (русский кириллица)
#CASE_UPPER=>case_lower
"\xd0\x81"=>"\xd1\x91", #Ё ё
"\xd0\x90"=>"\xd0\xb0", #А а
"\xd0\x91"=>"\xd0\xb1", #Б б
"\xd0\x92"=>"\xd0\xb2", #В в
"\xd0\x93"=>"\xd0\xb3", #Г г
"\xd0\x94"=>"\xd0\xb4", #Д д
"\xd0\x95"=>"\xd0\xb5", #Е е
"\xd0\x96"=>"\xd0\xb6", #Ж ж
"\xd0\x97"=>"\xd0\xb7", #З з
"\xd0\x98"=>"\xd0\xb8", #И и
"\xd0\x99"=>"\xd0\xb9", #Й й
"\xd0\x9a"=>"\xd0\xba", #К к
"\xd0\x9b"=>"\xd0\xbb", #Л л
"\xd0\x9c"=>"\xd0\xbc", #М м
"\xd0\x9d"=>"\xd0\xbd", #Н н
"\xd0\x9e"=>"\xd0\xbe", #О о
"\xd0\x9f"=>"\xd0\xbf", #П п
#CASE_UPPER=>case_lower
"\xd0\xa0"=>"\xd1\x80", #Р р
"\xd0\xa1"=>"\xd1\x81", #С с
"\xd0\xa2"=>"\xd1\x82", #Т т
"\xd0\xa3"=>"\xd1\x83", #У у
"\xd0\xa4"=>"\xd1\x84", #Ф ф
"\xd0\xa5"=>"\xd1\x85", #Х х
"\xd0\xa6"=>"\xd1\x86", #Ц ц
"\xd0\xa7"=>"\xd1\x87", #Ч ч
"\xd0\xa8"=>"\xd1\x88", #Ш ш
"\xd0\xa9"=>"\xd1\x89", #Щ щ
"\xd0\xaa"=>"\xd1\x8a", #Ъ ъ
"\xd0\xab"=>"\xd1\x8b", #Ы ы
"\xd0\xac"=>"\xd1\x8c", #Ь ь
"\xd0\xad"=>"\xd1\x8d", #Э э
"\xd0\xae"=>"\xd1\x8e", #Ю ю
"\xd0\xaf"=>"\xd1\x8f", #Я я
#tt (татарский, башкирский кириллица)
#CASE_UPPER=>case_lower
"\xd2\x96"=>"\xd2\x97", #Ж ж с хвостиком &#1174;=>&#1175;
"\xd2\xa2"=>"\xd2\xa3", #Н н с хвостиком &#1186;=>&#1187;
"\xd2\xae"=>"\xd2\xaf", #Y y &#1198;=>&#1199;
"\xd2\xba"=>"\xd2\xbb", #h h мягкое &#1210;=>&#1211;
"\xd3\x98"=>"\xd3\x99", #Э э &#1240;=>&#1241;
"\xd3\xa8"=>"\xd3\xa9", #О o перечеркнутое &#1256;=>&#1257;
#uk (украинский кириллица)
#CASE_UPPER=>case_lower
"\xd2\x90"=>"\xd2\x91", #г с хвостиком
"\xd0\x84"=>"\xd1\x94", #э зеркальное отражение
"\xd0\x86"=>"\xd1\x96", #и с одной точкой
"\xd0\x87"=>"\xd1\x97", #и с двумя точками
#be (белорусский кириллица)
#CASE_UPPER=>case_lower
"\xd0\x8e"=>"\xd1\x9e", #у с подковой над буквой
#tr,de,es (турецкий, немецкий, испанский, французский латиница)
#CASE_UPPER=>case_lower
"\xc3\x84"=>"\xc3\xa4", #a умляут &#196;=>&#228; (турецкий)
"\xc3\x87"=>"\xc3\xa7", #c с хвостиком &#199;=>&#231; (турецкий, французский)
"\xc3\x91"=>"\xc3\xb1", #n с тильдой &#209;=>&#241; (турецкий, испанский)
"\xc3\x96"=>"\xc3\xb6", #o умляут &#214;=>&#246; (турецкий)
"\xc3\x9c"=>"\xc3\xbc", #u умляут &#220;=>&#252; (турецкий, французский)
"\xc4\x9e"=>"\xc4\x9f", #g умляут &#286;=>&#287; (турецкий)
"\xc4\xb0"=>"\xc4\xb1", #i c точкой и без &#304;=>&#305; (турецкий)
"\xc5\x9e"=>"\xc5\x9f", #s с хвостиком &#350;=>&#351; (турецкий)
#hr (хорватский латиница)
#CASE_UPPER=>case_lower
"\xc4\x8c"=>"\xc4\x8d", #c с подковой над буквой
"\xc4\x86"=>"\xc4\x87", #c с ударением
"\xc4\x90"=>"\xc4\x91", #d перечеркнутое
"\xc5\xa0"=>"\xc5\xa1", #s с подковой над буквой
"\xc5\xbd"=>"\xc5\xbe", #z с подковой над буквой
#fr (французский латиница)
#CASE_UPPER=>case_lower
"\xc3\x80"=>"\xc3\xa0", #a с ударением в др. сторону
"\xc3\x82"=>"\xc3\xa2", #a с крышкой
"\xc3\x86"=>"\xc3\xa6", #ae совмещенное
"\xc3\x88"=>"\xc3\xa8", #e с ударением в др. сторону
"\xc3\x89"=>"\xc3\xa9", #e с ударением
"\xc3\x8a"=>"\xc3\xaa", #e с крышкой
"\xc3\x8b"=>"\xc3\xab", #ё
"\xc3\x8e"=>"\xc3\xae", #i с крышкой
"\xc3\x8f"=>"\xc3\xaf", #i умляут
"\xc3\x94"=>"\xc3\xb4", #o с крышкой
"\xc5\x92"=>"\xc5\x93", #ce совмещенное
"\xc3\x99"=>"\xc3\xb9", #u с ударением в др. сторону
"\xc3\x9b"=>"\xc3\xbb", #u с крышкой
"\xc5\xb8"=>"\xc3\xbf", #y умляут
#xx (другой язык)
#CASE_UPPER=>case_lower
#""=>"", #
);
if($mode==CASE_UPPER){
if(function_exists('mb_strtoupper')) return mb_strtoupper($s, 'utf-8');
if(preg_match('/^[\x00-\x7e]*$/', $s)) return strtoupper($s);
return strtr($s, array_flip($trans));
}
elseif($mode==CASE_LOWER)
{
if(function_exists('mb_strtolower')) return mb_strtolower($s, 'utf-8');
if(preg_match('/^[\x00-\x7e]*$/', $s)) return strtolower($s);
return strtr($s, $trans);
}else{
trigger_error('Parameter 2 should be a constant of CASE_LOWER or CASE_UPPER!', E_USER_WARNING);
return $s;
}
return $s;
}
function utf8_strtolower($s){
return utf8_convert_case($s, CASE_LOWER);
}
function utf8_uppercase($s){
return utf8_convert_case($s, CASE_UPPER);
}
function utf8_lowercase($s){
return utf8_convert_case($s, CASE_LOWER);
}
function utf8_ucfirst($s, $is_other_to_lowercase = true){
    if($s==='' or !is_string($s))return $s;
    if(preg_match('/^(.)(.*)$/us',$s,$m)===false)return false;
    return utf8_uppercase($m[1]).($is_other_to_lowercase?utf8_lowercase($m[2]):$m[2]);
}
function img_resize($src,$dest,$width,$tolarge,$bgcolor,$ext,$imgwidth,$imgheight,$logomerge){
if(!file_exists($src))return false;
$icfunc="imagecreatefrom".$ext;
if(!function_exists($icfunc))return false;
if($tolarge!='1' && $imgheight<$width)$width=$imgheight;
$x_ratio=$width/$imgheight;
$y_ratio=$width/$imgwidth;
$ratio=min($x_ratio,$y_ratio);
$use_x_ratio=($x_ratio==$ratio);
$new_width=$use_x_ratio?$width:floor($imgheight*$ratio);
$new_height=!$use_x_ratio?$width:floor($imgwidth*$ratio);
$isrc=$icfunc($src);
if($bgcolor!=0){
$new_left=$use_x_ratio?0:floor(($width-$new_width)/2);
$new_top=!$use_x_ratio?0:floor(($width-$new_height)/2);
$nn_width=$nn_height=$width;
$bgc=$bgcolor;
}else{
$new_left=0;
$new_top=0;
$nn_width=$new_width;
$nn_height=$new_height;
$bgc=0xFFFFFF;
}
$idest=imagecreatetruecolor($nn_width,$nn_height);
imagefill($idest,0,0,$bgc);
imagecopyresampled($idest,$isrc,$new_left,$new_top,0,0,$new_width,$new_height,$imgheight,$imgwidth);
if($logomerge=="1"){
$mergelogo=$_SERVER['DOCUMENT_ROOT'].$GLOBALS['p']."/images/logo_merge/logoj.gif";
if(file_exists($mergelogo)){
$size_logo=getimagesize($mergelogo);
if($size_logo[2]==1)$logo=imagecreatefromgif($mergelogo);
elseif($size_logo[2]==2)$logo=imagecreatefromjpeg($mergelogo);
elseif($size_logo[2]==3)$logo=imagecreatefrompng($mergelogo);
if(@$logo){
if($nn_width>($size_logo[0]+50)&&$nn_height>($size_logo[1]+50)){
  $coordmerge_x=$nn_width-($size_logo[0]+20);
  $coordmerge_y=$nn_height-($size_logo[1]+20);
  imagecopymerge($idest,$logo,$coordmerge_x,$coordmerge_y,0,0,$size_logo[0],$size_logo[1],100);
}} else {return false;}
} else {return false;}
} 
if($ext=="gif")imagegif($idest,$dest);
elseif($ext=="png")imagepng($idest,$dest);
else imagejpeg($idest,$dest,100);
imagedestroy($isrc);
imagedestroy($idest);
return true;
}
/**
* @author Nasibullin Rinat, https://orangetie.ru/
*/
function utf8_str_split($string,$length=null){
if(! is_string($string)) trigger_error('A string type expected in first parameter, ' . gettype($string) . ' given!', E_USER_ERROR);
$length=($length === null) ? 1 : intval($length);
if($length < 1) return false;
if($length < 100){
preg_match_all('/(?>[\x09\x0A\x0D\x20-\x7E] # ASCII
| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
#| (.) # catch bad bytes
){1,' . $length . '}
/xsSX', $string, $m);
$a =& $m[0];
}else{
preg_match_all('/(?>[\x09\x0A\x0D\x20-\x7E] # ASCII
| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
#| (.) # catch bad bytes
)
/xsSX', $string, $m);
$a=array();
for ($i=0, $c=count($m[0]); $i < $c; $i += $length) $a[]=implode('', array_slice($m[0], $i, $length));
}
$distance=strlen($string) - strlen(implode('', $a));
if($distance > 0){
trigger_error('Charset is not UTF-8, total ' . $distance . ' unknown bytes found!', E_USER_WARNING);
return false;
}
return $a;
}
/**
* @author Nasibullin Rinat, https://orangetie.ru/
*/
function utf8_substr($str, $offset, $length=null){
if(function_exists('mb_substr')) return mb_substr($str, $offset, $length, 'utf-8');
if(function_exists('iconv_substr')) return iconv_substr($str, $offset, $length, 'utf-8');
if(! is_array($a=utf8_str_split($str))) return false;
if($length !== null) $a=array_slice($a, $offset, $length);
else $a=array_slice($a, $offset);
return implode('', $a);}
/**
* @author <chernyshevsky at hotmail dot com>
* @author Nasibullin Rinat, https://orangetie.ru/
*/
function utf8_strlen($str){
if(function_exists('mb_strlen')) return mb_strlen($str, 'utf-8');
return strlen(utf8_decode($str));}
/**
* @author Nasibullin Rinat, https://orangetie.ru/
*/ 
function utf8_strpos($haystack, $needle, $offset=null){
if($offset === null or $offset < 0) $offset=0;
if(function_exists('mb_strpos')) return mb_strpos($haystack, $needle, $offset, 'utf-8');
if(function_exists('iconv_strpos')) return iconv_strpos($haystack, $needle, $offset, 'utf-8');
$byte_pos=$offset;
do if(($byte_pos=strpos($haystack, $needle, $byte_pos)) === false) return false;
while (($char_pos=utf8_strlen(substr($haystack, 0, $byte_pos++))) < $offset);
return $char_pos;
}
/**
* @author Nasibullin Rinat, https://orangetie.ru/
*/
function strip_tags_smart($s,array $allowable_tags=null,$is_format_spaces=true,array $pair_tags=array('script', 'style', 'map', 'iframe', 'frameset', 'object', 'applet', 'comment', 'button', 'textarea', 'select'),array $para_tags=array('p', 'td', 'th', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'form', 'title', 'pre')){
static $_callback_type =false;
static $_allowable_tags=array();
static $_para_tags =array();
static $re_attrs_fast_safe= '(?![a-zA-Z\d]) #statement, which follows after a tag
#correct attributes
(?>
[^>"\']+
| (?<=[\=\x20\r\n\t]|\xc2\xa0) "[^"]*"
| (?<=[\=\x20\r\n\t]|\xc2\xa0) \'[^\']*\'
)*
#incorrect attributes
[^>]*+';
if(is_array($s)){
if($_callback_type === 'strip_tags'){
$tag=strtolower($s[1]);
if($_allowable_tags &&
(array_key_exists($tag, $_allowable_tags) || array_key_exists('<' . trim(strtolower($s[0]), '< />') . '>', $_allowable_tags))
) return $s[0];
if($tag === 'br') return "\r\n";
if($_para_tags && array_key_exists($tag, $_para_tags)) return "\r\n\r\n";
return '';
}
trigger_error('Unknown callback type "' . $_callback_type . '"!', E_USER_ERROR);
}
if(($pos=strpos($s, '<')) === false || strpos($s, '>', $pos) === false){
return $s;
}
$length=strlen($s);
$re_tags='/ <[\/\!]?+
(
[a-zA-Z][a-zA-Z\d]*+
(?>\:[a-zA-Z][a-zA-Z\d]*+)?
)
' . $re_attrs_fast_safe . '
>
/sxSX';
$patterns=array(
'/<([\?\%]) .*? \\1>/sxSX',
'/<\!\[CDATA\[ .*? \]\]>/sxSX',
'/<\!--.*?-->/sSX',
'/ <\! (?:--)?+
\[
(?> [^\]"\']+ | "[^"]*" | \'[^\']*\' )*
\]
(?:--)?+
>
/sxSX',
);
if($pair_tags){
foreach ($pair_tags as $k=>$v) $pair_tags[$k]=preg_quote($v, '/');
$patterns[]='/ <((?i:' . implode('|', $pair_tags) . '))' . $re_attrs_fast_safe . '(?<!\/)>
.*?
<\/(?i:\\1)' . $re_attrs_fast_safe . '>
/sxSX';
}
$i=0;
$max=99;
while($i < $max){
$s2=preg_replace($patterns, '', $s);
if(preg_last_error() !== PREG_NO_ERROR){
$i=999;
break;
}
if($i==0){
$is_html=($s2 != $s || preg_match($re_tags, $s2));
if(preg_last_error() !== PREG_NO_ERROR){
$i=999;
break;
}
if($is_html){
if($is_format_spaces){
/*
В библиотеке PCRE для PHP \s - это любой пробельный символ, а именно класс символов [\x09\x0a\x0c\x0d\x20\xa0] или, по другому, [\t\n\f\r \xa0]
Если \s используется с модификатором /u, то \s трактуется как [\x09\x0a\x0c\x0d\x20]
Браузер не делает различия между пробельными символами, друг за другом подряд идущие символы воспринимаются как один
*/
#$s2=str_replace(array("\r", "\n", "\t"), ' ', $s2);
#$s2=strtr($s2, "\x09\x0a\x0c\x0d", ' ');
$s2=preg_replace('/ [\x09\x0a\x0c\x0d]++
| <((?i:pre|textarea))' . $re_attrs_fast_safe . '(?<!\/)>
.+?
<\/(?i:\\1)' . $re_attrs_fast_safe . '>
\K
/sxSX', ' ', $s2);
if(preg_last_error() !== PREG_NO_ERROR){
$i=999;
break;
}}
if($allowable_tags) $_allowable_tags=array_flip($allowable_tags);
if($para_tags) $_para_tags=array_flip($para_tags);
}}
if($is_html){
$_callback_type='strip_tags';
$s2=preg_replace_callback($re_tags, __FUNCTION__, $s2);
$_callback_type=false;
if(preg_last_error() !== PREG_NO_ERROR){
$i=999;
break;
}}
if($s === $s2) break;
$s=$s2; $i++;
}
if($i >= $max) $s=strip_tags($s);
if($is_format_spaces && strlen($s) !== $length){
$s=preg_replace('/\x20\x20++/sSX', ' ', trim($s));
$s=str_replace(array("\r\n\x20", "\x20\r\n"), "\r\n", $s);
$s=preg_replace('/[\r\n]{3,}+/sSX', "\r\n\r\n", $s);
}
return $s;
}
/**
* @author Nasibullin Rinat, https://orangetie.ru/
*/
function is_utf8(&$data, $is_strict=true){
if(is_array($data)){
foreach ($data as $k=>&$v) if(! is_utf8($v, $is_strict)) return false;
return true;
}
elseif(is_string($data)){
if(function_exists('iconv')){
$distance=strlen($data) - strlen(@iconv('UTF-8', 'UTF-8//IGNORE', $data));
if($distance > 0) return false;
if($is_strict && preg_match('/[^\x09\x0A\x0D\x20-\xFF]/sSX', $data)) return false;
return true;
}
$result=$is_strict ?
preg_replace('/(?>[\x09\x0A\x0D\x20-\x7E] # ASCII
| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
#| (.) # catch bad bytes
)*
/sxSX', '', $data) :
preg_replace('/\X*/suSX', '', $data);
if(function_exists('preg_last_error')){
if(preg_last_error() === PREG_NO_ERROR) return $result === '';
if(preg_last_error() === PREG_BAD_UTF8_ERROR) return false;
}
elseif(is_string($result)) return $result === '';
return utf8_check($data, $is_strict);
}
elseif(is_scalar($data) || is_null($data)) return true;
trigger_error('Scalar, null or array type expected, ' . gettype($data) . ' given ', E_USER_WARNING);
return false;
}
/**
* @author <bmorel at ssi dot fr>
* @author Nasibullin Rinat, https://orangetie.ru/ (small changes)
*/
function utf8_check($str, $is_strict=true){
for ($i=0, $len=strlen($str); $i < $len; $i++){
$c=ord($str[$i]);
if($c < 0x80){
if($is_strict === false || ($c > 0x1F && $c < 0x7F) || $c==0x09 || $c==0x0A || $c==0x0D) continue;
}
if(($c & 0xE0)==0xC0) $n=1;
elseif(($c & 0xF0)==0xE0) $n=2;
elseif(($c & 0xF8)==0xF0) $n=3;
elseif(($c & 0xFC)==0xF8) $n=4;
elseif(($c & 0xFE)==0xFC) $n=5;
else return false;
for ($j=0; $j < $n; $j++){
$i++;
if($i==$len || ((ord($str[$i]) & 0xC0) != 0x80) ) return false;
}}
return true;
}
/**
* @author Nasibullin Rinat, https://orangetie.ru/
*/
function cp1259_to_utf8(&$str){
static $trans=array(
"\x80"=>"\xd3\x98", #0x04d8 CYRILLIC CAPITAL LETTER SCHWA
"\x81"=>"\xd0\x83", #0x0403 CYRILLIC CAPITAL LETTER GJE
"\x82"=>"\xe2\x80\x9a", #0x201a SINGLE LOW-9 QUOTATION MARK
"\x83"=>"\xd1\x93", #0x0453 CYRILLIC SMALL LETTER GJE
"\x84"=>"\xe2\x80\x9e", #0x201e DOUBLE LOW-9 QUOTATION MARK
"\x85"=>"\xe2\x80\xa6", #0x2026 HORIZONTAL ELLIPSIS
"\x86"=>"\xe2\x80\xa0", #0x2020 DAGGER
"\x87"=>"\xe2\x80\xa1", #0x2021 DOUBLE DAGGER
"\x88"=>"\xe2\x82\xac", #0x20ac EURO SIGN
"\x89"=>"\xe2\x80\xb0", #0x2030 PER MILLE SIGN
"\x8a"=>"\xd3\xa8", #0x04e8 CYRILLIC CAPITAL LETTER BARRED O
"\x8b"=>"\xe2\x80\xb9", #0x2039 SINGLE LEFT-POINTING ANGLE QUOTATION MARK
"\x8c"=>"\xd2\xae", #0x04ae CYRILLIC CAPITAL LETTER STRAIGHT U
"\x8d"=>"\xd2\x96", #0x0496 CYRILLIC CAPITAL LETTER ZHE WITH DESCENDER
"\x8e"=>"\xd2\xa2", #0x04a2 CYRILLIC CAPITAL LETTER EN WITH HOOK
"\x8f"=>"\xd2\xba", #0x04ba CYRILLIC CAPITAL LETTER SHHA
"\x90"=>"\xd3\x99", #0x04d9 CYRILLIC SMALL LETTER SCHWA
"\x91"=>"\xe2\x80\x98", #0x2018 LEFT SINGLE QUOTATION MARK
"\x92"=>"\xe2\x80\x99", #0x2019 RIGHT SINGLE QUOTATION MARK
"\x93"=>"\xe2\x80\x9c", #0x201c LEFT DOUBLE QUOTATION MARK
"\x94"=>"\xe2\x80\x9d", #0x201d RIGHT DOUBLE QUOTATION MARK
"\x95"=>"\xe2\x80\xa2", #0x2022 BULLET
"\x96"=>"\xe2\x80\x93", #0x2013 EN DASH
"\x97"=>"\xe2\x80\x94", #0x2014 EM DASH
#"\x98" #UNDEFINED
"\x99"=>"\xe2\x84\xa2", #0x2122 TRADE MARK SIGN
"\x9a"=>"\xd3\xa9", #0x04e9 CYRILLIC SMALL LETTER BARRED O
"\x9b"=>"\xe2\x80\xba", #0x203a SINGLE RIGHT-POINTING ANGLE QUOTATION MARK
"\x9c"=>"\xd2\xaf", #0x04af CYRILLIC SMALL LETTER STRAIGHT U
"\x9d"=>"\xd2\x97", #0x0497 CYRILLIC SMALL LETTER ZHE WITH DESCENDER
"\x9e"=>"\xd2\xa3", #0x04a3 CYRILLIC SMALL LETTER EN WITH HOOK
"\x9f"=>"\xd2\xbb", #0x04bb CYRILLIC SMALL LETTER SHHA
"\xa0"=>"\xc2\xa0", #0x00a0 NO-BREAK SPACE
"\xa1"=>"\xd0\x8e", #0x040e CYRILLIC CAPITAL LETTER SHORT U
"\xa2"=>"\xd1\x9e", #0x045e CYRILLIC SMALL LETTER SHORT U
"\xa3"=>"\xd0\x88", #0x0408 CYRILLIC CAPITAL LETTER JE
"\xa4"=>"\xc2\xa4", #0x00a4 CURRENCY SIGN
"\xa5"=>"\xd2\x90", #0x0490 CYRILLIC CAPITAL LETTER GHE WITH UPTURN
"\xa6"=>"\xc2\xa6", #0x00a6 BROKEN BAR
"\xa7"=>"\xc2\xa7", #0x00a7 SECTION SIGN
"\xa8"=>"\xd0\x81", #0x0401 CYRILLIC CAPITAL LETTER IO
"\xa9"=>"\xc2\xa9", #0x00a9 COPYRIGHT SIGN
"\xaa"=>"\xd0\x84", #0x0404 CYRILLIC CAPITAL LETTER UKRAINIAN IE
"\xab"=>"\xc2\xab", #0x00ab LEFT-POINTING DOUBLE ANGLE QUOTATION MARK
"\xac"=>"\xc2\xac", #0x00ac NOT SIGN
"\xad"=>"\xc2\xad", #0x00ad SOFT HYPHEN
"\xae"=>"\xc2\xae", #0x00ae REGISTERED SIGN
"\xaf"=>"\xd0\x87", #0x0407 CYRILLIC CAPITAL LETTER YI
"\xb0"=>"\xc2\xb0", #0x00b0 DEGREE SIGN
"\xb1"=>"\xc2\xb1", #0x00b1 PLUS-MINUS SIGN
"\xb2"=>"\xd0\x86", #0x0406 CYRILLIC CAPITAL LETTER BYELORUSSIAN-UKRAINIAN I
"\xb3"=>"\xd1\x96", #0x0456 CYRILLIC SMALL LETTER BYELORUSSIAN-UKRAINIAN I
"\xb4"=>"\xd2\x91", #0x0491 CYRILLIC SMALL LETTER GHE WITH UPTURN
"\xb5"=>"\xc2\xb5", #0x00b5 MICRO SIGN
"\xb6"=>"\xc2\xb6", #0x00b6 PILCROW SIGN
"\xb7"=>"\xc2\xb7", #0x00b7 MIDDLE DOT
"\xb8"=>"\xd1\x91", #0x0451 CYRILLIC SMALL LETTER IO
"\xb9"=>"\xe2\x84\x96", #0x2116 NUMERO SIGN
"\xba"=>"\xd1\x94", #0x0454 CYRILLIC SMALL LETTER UKRAINIAN IE
"\xbb"=>"\xc2\xbb", #0x00bb RIGHT-POINTING DOUBLE ANGLE QUOTATION MARK
"\xbc"=>"\xd1\x98", #0x0458 CYRILLIC SMALL LETTER JE
"\xbd"=>"\xd0\x85", #0x0405 CYRILLIC CAPITAL LETTER DZE
"\xbe"=>"\xd1\x95", #0x0455 CYRILLIC SMALL LETTER DZE
"\xbf"=>"\xd1\x97", #0x0457 CYRILLIC SMALL LETTER YI
"\xc0"=>"\xd0\x90", #0x0410 CYRILLIC CAPITAL LETTER A
"\xc1"=>"\xd0\x91", #0x0411 CYRILLIC CAPITAL LETTER BE
"\xc2"=>"\xd0\x92", #0x0412 CYRILLIC CAPITAL LETTER VE
"\xc3"=>"\xd0\x93", #0x0413 CYRILLIC CAPITAL LETTER GHE
"\xc4"=>"\xd0\x94", #0x0414 CYRILLIC CAPITAL LETTER DE
"\xc5"=>"\xd0\x95", #0x0415 CYRILLIC CAPITAL LETTER IE
"\xc6"=>"\xd0\x96", #0x0416 CYRILLIC CAPITAL LETTER ZHE
"\xc7"=>"\xd0\x97", #0x0417 CYRILLIC CAPITAL LETTER ZE
"\xc8"=>"\xd0\x98", #0x0418 CYRILLIC CAPITAL LETTER I
"\xc9"=>"\xd0\x99", #0x0419 CYRILLIC CAPITAL LETTER SHORT I
"\xca"=>"\xd0\x9a", #0x041a CYRILLIC CAPITAL LETTER KA
"\xcb"=>"\xd0\x9b", #0x041b CYRILLIC CAPITAL LETTER EL
"\xcc"=>"\xd0\x9c", #0x041c CYRILLIC CAPITAL LETTER EM
"\xcd"=>"\xd0\x9d", #0x041d CYRILLIC CAPITAL LETTER EN
"\xce"=>"\xd0\x9e", #0x041e CYRILLIC CAPITAL LETTER O
"\xcf"=>"\xd0\x9f", #0x041f CYRILLIC CAPITAL LETTER PE
"\xd0"=>"\xd0\xa0", #0x0420 CYRILLIC CAPITAL LETTER ER
"\xd1"=>"\xd0\xa1", #0x0421 CYRILLIC CAPITAL LETTER ES
"\xd2"=>"\xd0\xa2", #0x0422 CYRILLIC CAPITAL LETTER TE
"\xd3"=>"\xd0\xa3", #0x0423 CYRILLIC CAPITAL LETTER U
"\xd4"=>"\xd0\xa4", #0x0424 CYRILLIC CAPITAL LETTER EF
"\xd5"=>"\xd0\xa5", #0x0425 CYRILLIC CAPITAL LETTER HA
"\xd6"=>"\xd0\xa6", #0x0426 CYRILLIC CAPITAL LETTER TSE
"\xd7"=>"\xd0\xa7", #0x0427 CYRILLIC CAPITAL LETTER CHE
"\xd8"=>"\xd0\xa8", #0x0428 CYRILLIC CAPITAL LETTER SHA
"\xd9"=>"\xd0\xa9", #0x0429 CYRILLIC CAPITAL LETTER SHCHA
"\xda"=>"\xd0\xaa", #0x042a CYRILLIC CAPITAL LETTER HARD SIGN
"\xdb"=>"\xd0\xab", #0x042b CYRILLIC CAPITAL LETTER YERU
"\xdc"=>"\xd0\xac", #0x042c CYRILLIC CAPITAL LETTER SOFT SIGN
"\xdd"=>"\xd0\xad", #0x042d CYRILLIC CAPITAL LETTER E
"\xde"=>"\xd0\xae", #0x042e CYRILLIC CAPITAL LETTER YU
"\xdf"=>"\xd0\xaf", #0x042f CYRILLIC CAPITAL LETTER YA
"\xe0"=>"\xd0\xb0", #0x0430 CYRILLIC SMALL LETTER A
"\xe1"=>"\xd0\xb1", #0x0431 CYRILLIC SMALL LETTER BE
"\xe2"=>"\xd0\xb2", #0x0432 CYRILLIC SMALL LETTER VE
"\xe3"=>"\xd0\xb3", #0x0433 CYRILLIC SMALL LETTER GHE
"\xe4"=>"\xd0\xb4", #0x0434 CYRILLIC SMALL LETTER DE
"\xe5"=>"\xd0\xb5", #0x0435 CYRILLIC SMALL LETTER IE
"\xe6"=>"\xd0\xb6", #0x0436 CYRILLIC SMALL LETTER ZHE
"\xe7"=>"\xd0\xb7", #0x0437 CYRILLIC SMALL LETTER ZE
"\xe8"=>"\xd0\xb8", #0x0438 CYRILLIC SMALL LETTER I
"\xe9"=>"\xd0\xb9", #0x0439 CYRILLIC SMALL LETTER SHORT I
"\xea"=>"\xd0\xba", #0x043a CYRILLIC SMALL LETTER KA
"\xeb"=>"\xd0\xbb", #0x043b CYRILLIC SMALL LETTER EL
"\xec"=>"\xd0\xbc", #0x043c CYRILLIC SMALL LETTER EM
"\xed"=>"\xd0\xbd", #0x043d CYRILLIC SMALL LETTER EN
"\xee"=>"\xd0\xbe", #0x043e CYRILLIC SMALL LETTER O
"\xef"=>"\xd0\xbf", #0x043f CYRILLIC SMALL LETTER PE
"\xf0"=>"\xd1\x80", #0x0440 CYRILLIC SMALL LETTER ER
"\xf1"=>"\xd1\x81", #0x0441 CYRILLIC SMALL LETTER ES
"\xf2"=>"\xd1\x82", #0x0442 CYRILLIC SMALL LETTER TE
"\xf3"=>"\xd1\x83", #0x0443 CYRILLIC SMALL LETTER U
"\xf4"=>"\xd1\x84", #0x0444 CYRILLIC SMALL LETTER EF
"\xf5"=>"\xd1\x85", #0x0445 CYRILLIC SMALL LETTER HA
"\xf6"=>"\xd1\x86", #0x0446 CYRILLIC SMALL LETTER TSE
"\xf7"=>"\xd1\x87", #0x0447 CYRILLIC SMALL LETTER CHE
"\xf8"=>"\xd1\x88", #0x0448 CYRILLIC SMALL LETTER SHA
"\xf9"=>"\xd1\x89", #0x0449 CYRILLIC SMALL LETTER SHCHA
"\xfa"=>"\xd1\x8a", #0x044a CYRILLIC SMALL LETTER HARD SIGN
"\xfb"=>"\xd1\x8b", #0x044b CYRILLIC SMALL LETTER YERU
"\xfc"=>"\xd1\x8c", #0x044c CYRILLIC SMALL LETTER SOFT SIGN
"\xfd"=>"\xd1\x8d", #0x044d CYRILLIC SMALL LETTER E
"\xfe"=>"\xd1\x8e", #0x044e CYRILLIC SMALL LETTER YU
"\xff"=>"\xd1\x8f", #0x044f CYRILLIC SMALL LETTER YA
);
return strtr($str, $trans);
}
$cut_pattern=array("DROP","DELETE","UNION","OUTFILE","SELECT","ALTER","INSERT","UPDATE","CMD","JOIN","TRUNCATE","CONCAT","MODIFY","<[^>]*script*\"?[^>]*>","<[^>]*object*\"?[^>]*>","<[^>]*iframe*\"?[^>]*>","<[^>]*frame*\"?[^>]*>","<[^>]*applet*\"?[^>]*>","--");
function safe_request($arr,$pat){
if(is_array($arr)){
foreach($arr as $n=>$v){
if(is_array($n))safe_request($n);
else{
if(is_utf8($arr[$n]))$arr[$n]=trim(is_utf8($arr[$n]));
else $arr[$n]=cp1259_to_utf8(trim($arr[$n]));
foreach($pat as $value){$arr[$n]=preg_replace("/$value/imu","",$arr[$n]);}
}}}}
safe_request($_POST,$cut_pattern);
safe_request($_GET,$cut_pattern);
ob_start();

Function cutstring( $stranb, $lengthanb, $theend="..." )
{
if (strlen($stranb)>30) {$text2=substr($stranb, 0, $lengthanb);
$words=explode(" ", $text2);
$words=array_reverse($words);
array_splice($words,0,1);
$alexxnb=0;
for ($i=0; $i<count($words); $i++) {
$i=$i-$alexxnb;
if (substr($words[$i], -1)=="," OR substr($words[$i], -1)=="." OR substr($words[$i], -1)==":" OR substr($words[$i], -1)==";") {$words[$i]=substr($words[$i],0, strlen($words[$i])-1);};
$wrd=$words[$i];
if (strlen($wrd)<4){
array_splice($words,$i,1);$alexxnb=1;
} else {
$i=1985;};
};
$mwords="";
$words=array_reverse($words);
for ($i=0; $i<count($words); $i++) {
 $mwords=$mwords.$words[$i];
 if ($i!=count($words)-1) {$mwords=$mwords." ";} else {$mwords=$mwords.$theend;};
};
} else {$mwords=$stranb;};
return $mwords;
}
?>