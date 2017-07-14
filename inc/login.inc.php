<div class="form-wrapper">
<? 
if (@$_GET['act'] == "logout"){
	$_COOKIE['email']="";$_COOKIE['id_user']="";setcookie('email','1',1,'/');setcookie('id_user','1',1,'/');
	if(@$_SESSION['email'] && @$_SESSION['id_user']){
		unset($_SESSION['email'],$_SESSION['id_user']);
		session_unset(@$_SESSION['email']);
		session_unset(@$_SESSION['id_user']);
		setcookie('email','1',1,"/");
		setcookie('id_user','1',1,"/");
	}
	setcookie('jbnocache','1',time()+60,"/");
	setcookie('PHPSESSID','',time()+1,"/");
	header ("location: ".$h);
}
else if (@$_GET['act'] == "newpass"){
	if (@$_GET['accept'] == "yes" && ctype_digit(@$_GET['usid']) && @$_GET['hash'] != ""){
		$query_user = mysql_query("SELECT * FROM jb_user WHERE id_user='".$_GET['usid']."' LIMIT 1");
		if (mysql_num_rows($query_user)){
			$query_user_data = mysql_fetch_assoc($query_user);
			$key = md5($h.$query_user_data['email']);
			if (urldecode(base64_decode($_GET['hash']))==$key.utf8_substr($key,0,16)){
				$_COOKIE['email']="";$_COOKIE['id_user']="";
				setcookie('email','1',1,'/');setcookie('id_user','1',1,'/');
				$tt=time();$pass=$tt.$query_user_data['email'].rand(1,999999999);$pass=md5($pass);$pass=utf8_substr($pass,0,5);
				$query = mysql_query("UPDATE jb_user SET pass='".md5($pass)."' WHERE email='".$query_user_data['email']."' LIMIT 1");
				$msg = $lang[559]."\n\n".$lang[863].": ".$pass."\n".$lang[864].": ".$h."profile.html\n\n ----------------------------------- \n".$lang[865]." ".$h;
				sendmailer($query_user_data['email'],"<".$c['admin_mail'].">",$lang[863],$msg);				
				echo "<div class=\"alcenter\" style=\"margin:50px;\"><br /><br />".$lang[866]."<br /><br /><a href=\"".$h."\">".$lang[84]."</a></div>";
			} else { header('https/1.0 404 Not Found'); die(); }
		} else { header('https/1.0 404 Not Found'); die(); }
	}else{
		if (@$_POST['email'] != ""){
			$_POST['email'] = trim($_POST['email']);
			if (!preg_match('/^[-0-9\.a-z_]+@([-0-9\.a-z]+\.)+[a-z]{2,6}$/iu',$_POST['email'])) echo "<div class=\"alcenter\" style=\"margin:20px;\">".$lang[96].".<br /><a href='javascript:history.back(1)'> &larr; ".$lang[401]."</a></div>";
			else{
				$query_user = mysql_query("SELECT * FROM jb_user WHERE email='".$_POST['email']."' LIMIT 1");
				if (mysql_num_rows($query_user)){
					$query_user_data = mysql_fetch_assoc ($query_user);
					$key=md5($h.$query_user_data['email']);$hash=urlencode(base64_encode($key.utf8_substr($key,0,16)));
					$msg=$lang[867]." ".$h." .\n".$lang[868].": ".$h."accept,".$query_user_data['id_user'].",".$hash." \n\n".$lang[869]."\n".$h;
					sendmailer($_POST['email'],"<".$c['admin_mail'].">",$lang[870],$msg);
				}
				echo "<div class=\"alcenter\" style=\"margin:50px;\"><br /><br />".$lang[871]."<br /><br /><a href=\"".$h."\">".$lang[84]."</a></div>";
			}
		} else echo "<div class=\"alcenter\" style=\"margin:50px;\">".$lang[872].":<br /><br /><form action=\"".$h."newpass.html\" method=\"post\"><input name=\"email\" type=\"text\"><br /><br /><input type=\"submit\" value=\"".$lang[199]."\"></form></div>";
	}
}
else if (@$_GET['act'] == "register"){
	$form = "<div class=\"alcenter\" style=\"margin:50px;\"><form action=\"".$h."register.html\" method=post><h1>".$lang[147]."</h1><br />".$lang[873].":<br /><br /><input name=\"email\" type=\"text\"><br /><br /><input type=\"submit\" value=\"".$lang[199]."\"></form></div>";
	if (@$_POST['email']){
		$email = trim($_POST['email']);
		if (!preg_match('/^[-0-9\.a-z_]+@([-0-9\.a-z]+\.)+[a-z]{2,6}$/iu',$email)) echo "<br /><br /><div class=\"alcenter\" style=\"margin:20px;\">".$lang[582]."</div>".$form;
		else{
			if (mysql_num_rows(mysql_query ("SELECT id_user FROM jb_user WHERE email='".$email ."'"))) echo "<div class=\"alcenter\" style=\"margin:20px;\">".$lang[874]."</div>".$form;
			else{
				$tt=time();$pass=$tt.$email.rand(1,999999999);$pass=md5($pass);$pass=utf8_substr($pass,0,5);		
				$query = mysql_query("INSERT jb_user SET pass='".md5($pass)."', email='".$email."'");
				$msg=$lang[559].".\n".$lang[875].": ".$pass."\n".$lang[876]." ".$h."login.html ".$lang[877]." ".$h.".\n\n".$lang[865]." ".$h;
				if (sendmailer($email,"<".$c['admin_mail'].">",$lang[881]." ".$h,$msg)) echo "<div class=\"alcenter\" style=\"margin:50px;\">".$lang[878].".<br /><br /><br /><br /><br /><a href=\"".$h."\">".$lang[84]."</a></div>";
				else echo "<div class=\"alcenter\" style=\"margin:50px;\">".$lang[86]."<br /><br /><br /><a href=\"".$h."\">".$lang[84]."</a></div>";
			}
		}
	}else echo $form;
}else{
	if(defined('USER')){setcookie('jbnocache','1',time()+60,"/");header("location: ".$h."cpanel.html");}
	else{
		$form="<div class=\"alcenter\" style=\"margin:50px;\"><form action=\"".$h."login.html\" method=post><h1>".$lang[843]."</h1><br />E-mail: <input name=\"email\" type=\"text\"><br /><br />".$lang[152].": <input name=\"password\" type=\"password\"><br /><br /><input type=\"checkbox\" name=\"setcookie\"> ".$lang[844]."<br /><br /><input type=\"submit\" name=\"submit\" value=\"".$lang[845]."\"><br /><br /><a href=\"".$h."register.html\">".$lang[147]."</a> <a href=\"".$h."newpass.html\">".$lang[846]."</a></form></div>";
		if (@$_POST['email'] && @$_POST['password']){
			$host=parse_url(@$_SERVER['HTTP_REFERER']);if(@$host['host']!=$_SERVER['HTTP_HOST'])die();
			$_POST['email'] = trim($_POST['email']);
			if (!preg_match('/^[-0-9\.a-z_]+@([-0-9\.a-z]+\.)+[a-z]{2,6}$/iu',$_POST['email'])) echo "<div class=\"red alcenter\" style=\"margin:50px;\"><h1>".$lang[96]."</h1></div>".$form;
			else{
				$query = mysql_query("SELECT id_user, pass FROM jb_user WHERE email='".$_POST['email']."'"); cq();
				if(@mysql_num_rows($query)){
					$data = mysql_fetch_assoc($query);
					$_POST['password'] = trim($_POST['password']);
					if ($data['pass'] == md5($_POST['password'])){
						$_SESSION['email'] = $_POST['email'];
						$_SESSION['id_user'] = $data['pass'];
						if (@$_POST['setcookie']){
							setcookie("email", $_SESSION['email'], time() + 77760000, "/");
							setcookie("id_user", $data['pass'], time() + 77760000, "/");
						}
						setcookie('jbnocache','1',time()+60,"/");					
						header ("location: ".$h."cpanel.html");
					}else echo "<div class=\"red alcenter\" style=\"margin:50px;\">".$lang[852]."</div>".$form;
				}else echo "<div class=\"red alcenter\" style=\"margin:50px;\">".$lang[879]."</div>".$form;
			}
		}else echo $form;
	} 
}
?>
</div>