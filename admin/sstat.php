<?

if(!defined('SITE'))die();
$countads=mysql_result(mysql_query("select count(*) from jb_board"),0);cq();
$countimg=mysql_result(mysql_query("select count(*) from jb_photo"),0);cq();
$countcomments=mysql_result(mysql_query("select count(*) from jb_comments"),0);cq();
$countnews=mysql_result(mysql_query("select count(*) from jb_news"),0);cq();
$countuser=mysql_result(mysql_query("select count(*) from jb_user"),0);cq();
?><center><h1><?=$lang[143]?></h1></center><br /><div align="center"><table class="sort" cellspacing="10"><tr bgcolor="#F6F6F6"><td><?=$lang[90]?>:</td><td align="center"><strong><?=$countads?></strong></td></tr><tr bgcolor="#F6F6F6"><td><?=$lang[106]?>:</td><td align="center"><strong><?=$countimg?></strong></td></tr><tr bgcolor="#F6F6F6"><td><?=$lang[423]?>:</td><td align="center"><strong><?=$countcomments?></strong></td></tr><tr bgcolor="#F6F6F6"><td><?=$lang[286]?>:</td><td align="center"><strong><?=$countnews?></strong></td></tr><tr bgcolor="#F6F6F6"><td><?=$lang[1077]?>:</td><td align="center"><strong><?=$countuser?></strong></td></tr></td></tr></table></div>