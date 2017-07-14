<div class="form-wrapper">
<div class="form-wrap1">
<div class="form-wrapper">
<h3>Новое на форуме</h3>
</div>
<?
$f_encode=fopen("https://zoomwo.pp.ua/forum/extern.php?action=feed&show=5", r);
$r_encode=fread($f_encode, 1500);
fclose($f_encode);
$encode=iconv("UTF-8", "UTF-8", "$r_encode");
echo "<li>".$encode."</li>";
?>
</div>
</div>
<b class="gde">pageinc</b>