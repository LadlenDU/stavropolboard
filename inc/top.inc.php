<nav>
	<ul id="nav">
		<li><a href='<?=$h?>'><img src="images/logo_merge/logo.png" width="150px" height="101px"></a></li>
		<li><a href="<?=$h?>"><?=$lang[1148]?></a></li>
		<li><a href="<?=$h?>new.html"><?=$lang[595]?>&nbsp <img src="images/new.gif"></a></li> 
		<li><a href="<?=$h?>newlist.html"><?=$lang[658]?></a></li>
		<li><a href="<?=$h?>informers.html"><?=$lang[1014]?></a></li>
		<li><a href="<?=$h?>rss.html"><?=$lang[1155]?></a></li>
		<li><a href="<?=$h?>contacts.html"><?=$lang[139]?></a></li>
	</ul>
<select id="nav_v2" onchange="window.location.href = this.options[this.selectedIndex].value">
    <option selected="selected" value="">Меню сайта</option>
    <option value="<?=$h?>"><?=$lang[1148]?></option>
    <option value="<?=$h?>new.html"><?=$lang[595]?></option>
    <option value="<?=$h?>newlist.html"><?=$lang[658]?></option>
    <option value="<?=$h?>informers.html"><?=$lang[1014]?></option>
	<option value="<?=$h?>rss.html"><?=$lang[1155]?></option>
    <option value="<?=$h?>contacts.html"><?=$lang[139]?></option>
</select>
</nav>
<header>
<article>
<center>
<script type="text/javascript">var servername='<?=$h?>';</script><?=$stylecss?><?=$mainjs?>
</center>
</article>
<aside>
</br>
<div class="header">
<div class="topmenu_ac">
<div class="topmenu_acl">
<div class="topmenu_acr">
<div class="topmenu_area">

<?
if(@$_SESSION['login']&& @$_SESSION['password']) echo "<a href=\"".$h."a/\">".$lang[209]."</a><p></p>";
if (defined('USER_CITY_TITLE')) echo "<a href=\"#\" title=\"".$lang[15]."\" onclick=\"window.openCenteredWindow('".$h."city.html');\">".USER_CITY_TITLE."</a><p></p>";
if(defined('COUNT_USER_NOTES'))echo "<a title=\"".$lang[501]."\" href=\"".$h."note.html\">".$lang[501]." (".COUNT_USER_NOTES.")</a><p></p>";
if (defined('USER')) echo "<a href=\"".$h."cpanel.html\">".$lang[841]."</a><p></p><a href=\"".$h."profile.html\">".$lang[880]."</a><p></p><a href=\"".$h."logout.html\">".$lang[4]."</a><p></p>";
else echo "<a href=\"".$h."login.html\">".$lang[1003]."</a><p></p><a href=\"".$h."register.html\">".$lang[1004]."</a>";
?>
</div>
</div>
</div>
</div>
</div>
</aside>
</header>