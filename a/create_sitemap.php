<?
#!/usr/local/bin/php
define('SITE',true);
include("../admin/conf.php");
$sitemapindex=array();
$start="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<urlset xmlns=\"https://www.sitemaps.org/schemas/sitemap/0.9\">";
$end="</urlset>";
$itog=$start;
$itog.="<url><loc>".$h."</loc><changefreq>daily</changefreq><priority>0.5</priority></url>\r\n";
$itog.="<url><loc>".$h."newlist.html</loc><changefreq>daily</changefreq><priority>1.0</priority></url>\r\n";
writeData("../upload/sitemap_index.xml",$itog.$end);
$sitemapindex[]="sitemap_index.xml";
$itog=$start;
$query=mysql_query("SELECT id FROM jb_board_cat WHERE child_category=0");  
while($d=mysql_fetch_assoc($query)){
	$countads=mysql_num_rows(mysql_query("SELECT id FROM jb_board WHERE id_category='".$d['id']."' LIMIT 1"));
	if(@$countads)$itog.="<url><loc>".$h."c".$d['id'].".html</loc><changefreq>daily</changefreq><priority>0.5</priority></url>";
}
writeData("../upload/sitemap_cat.xml",$itog.$end);
$sitemapindex[]="sitemap_cat.xml";
$itog=$start;
$query=mysql_query("SELECT id, id_category FROM jb_board WHERE old_mess='old'");  
$countads=mysql_num_rows($query);
if(@$countads){
	$number=0;$number_name=0;
	while($d=mysql_fetch_assoc($query)){
		$itog.="<url><loc>".$h."c".$d['id_category']."-".$d['id'].".html</loc><changefreq>weekly</changefreq><priority>0.2</priority></url>";
		$number++;
		if($number>=1000){
			writeData("../upload/sitemap_board_".$number_name.".xml",$itog.$end);
			$sitemapindex[]="sitemap_board_".$number_name.".xml";
			$number_name++;$number=0;$itog=$start;
		}
	}
	writeData("../upload/sitemap_board_".$number_name.".xml",$itog.$end);
	$sitemapindex[]="sitemap_board_".$number_name.".xml";
}
$itog=$start;
$query=mysql_query("SELECT id,translit FROM jb_news ORDER by id DESC");  
if(mysql_num_rows($query)){
	while($d=mysql_fetch_assoc($query)){
		$itog.="<url><loc>".$h."n".$d['id']."-".rawurlencode($d['translit']).".html</loc><changefreq>monthly</changefreq><priority>0.2</priority></url>";
	}
	writeData("../upload/sitemap_news.xml",$itog.$end);
	$sitemapindex[]="sitemap_news.xml";
}
$itog=$start;
$query=mysql_query("SELECT id FROM jb_page");
if(mysql_num_rows($query)){
	while($d=mysql_fetch_assoc($query)){
		$itog.="<url><loc>".$h."p".$d['id'].".html</loc><changefreq>monthly</changefreq><priority>0.2</priority></url>";
	}
	writeData("../upload/sitemap_page.xml",$itog.$end);
	$sitemapindex[]="sitemap_page.xml";
}
$sitemapindex_itog="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<sitemapindex xmlns=\"https://www.sitemaps.org/schemas/sitemap/0.9\">";
foreach($sitemapindex as $k=>$v){
	if(file_exists("../upload/".$v)){
		$lm=date('c',filemtime("../upload/".$v));
		$sitemapindex_itog.="<sitemap><loc>".$u.$v."</loc><lastmod>".$lm."</lastmod></sitemap>";
}}
$sitemapindex_itog.="</sitemapindex>";
writeData("../upload/sitemap.xml",$sitemapindex_itog);
echo $lang[400];
mysql_query("UPDATE jb_maintenance SET create_sitemap=NOW() WHERE id=0");
?>