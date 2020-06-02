<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="images/favicon.gif"> 

<style type="text/css">
a:link {
	color: #0000CC;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #0000CC;
}
a:hover {
	text-decoration: underline;
	color: #FF3333;
}
a:active {
	text-decoration: none;
}

</style>
<script type="text/javascript">
function is_empty(field,alerttxt)
{
with (field){
  if (value==null||value=="")
    {
    alert(alerttxt);
	focus();
	return true;
    }
  else
    {
    return false;
    }
  }
}

function echeck(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    alert("Invalid E-mail ID")
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    alert("Invalid E-mail ID")
		    return false
		 }
		
		 if (str.indexOf(" ")!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

 		 return true					
	}

function validate_form(thisform)
{
with (thisform)
  {

	  if (is_empty(name,"You should enter your name"))
	  {
	  return false;}
	   else if (is_empty(comment,"Comment is blank!"))
	  {
	  return false;}
	  else if(email.value!="" && echeck(email.value)==false)
	  {
	  	email.focus();
		return false;
	  }
	else return true;  
	 
	}
	
	 
}

</script>
</head>
<body background="images/background.jpg">
<center>
  <p>
    <?php
if(isset($_GET['project']))
{
	include 'library/functions.php';
	$code=1;
	$project_id=$_GET['project'];
	include 'admin/db.php';
	$query1="SELECT name FROM scratch_files WHERE id=$project_id";
	$result1=mysql_query($query1) or die('Error, query failed : ' . mysql_error());
	while($row=mysql_fetch_assoc($result1))
	{$project_name=$row['name'];
	$array=explode('.',$project_name);
	$project_name=$array[0];
		echo "<h2>Comments for the Project: $project_name</h2>
		<title>Comments for $project_name </title>";
	}

		if(isset($_POST['submit']))
		{
				$author=$_POST['name'];
				$author_email=$_POST['email'];
				$author_comment=$_POST['comment'];
				$allowed_tags = "<b><i><em>";
				$author_comment = cleanup_text($author_comment,"",$allowed_tags);

//captcha part 
			require_once('admin/recaptchalib.php');
			$privatekey = "6LfuugkAAAAAAHKyzlFFqH-Bc2fs-jxLGSlWW_CF";
			$resp = recaptcha_check_answer ($privatekey,
											$_SERVER["REMOTE_ADDR"],
											$_POST["recaptcha_challenge_field"],
											$_POST["recaptcha_response_field"]);
			
			if (!$resp->is_valid) 
			{
			// CODE IS ENTERED WRONG PLEASE TRY AGAIN
				$code=0;
				echo "<p style=\"font-size:20px;color:red\">Incorrect Code. Please Enter again</p>";
			}

//captcha part ends
			else 
			{
				include 'admin/db.php';
				
				$username=$_SESSION['username'];
				$code=1;
				$author_ip=$_SERVER["REMOTE_ADDR"];
				$date = date("Y-m-d H:i:s", time()); 
				$sql = "INSERT INTO `scratch_comments` (project_id, author, author_email, comment, author_ip, date ) ".
								"VALUES ( '$project_id', '$author', '$author_email', '$author_comment', '$author_ip', '$date')";
				
				mysql_query($sql) or die('Error, query failed : ' . mysql_error());
// Mailing comment to admin
						$host=get_siteurl($username);
						$admin_email=get_email($username);

						
				$to      = $admin_email;
				$subject = "Scratch Comment on $project_name";
				$comment_id=mysql_insert_id();
				$message = "New comment on your project $project_name : \r\n" .
							"$host/projects.php?show=$project_name&id=$project_id \r\n" .
							"Author: $author (IP:$author_ip) \r\n".
							"Email : $author_email \r\n".
							"Comment: \r\n $author_comment \r\n \r\n".
							"You can see all the comments on this project here \r\n" .
							"$host/comments.php?project=$project_id   \r\n \r\n".
							"Delete it : $host/admin/comments.php?project=$project_id";
				if(empty($author_email)||$author_email=="")
				{
					$replyto="<nobody> nobody@$host";
				}
				else
				{
					$replyto=$author_email;
				}
				
							
					
						$headers = "From: $author<scratch@". $_SERVER['HTTP_HOST'] . ">\r\n" .
							"Reply-To: $replyto" . "\r\n" .
							'X-Mailer: PHP/' . phpversion();
						
				mail($to, $subject, $message, $headers);
			
//end of mail				
				
				echo "<h3 style=\"color:orange\">Thanks - comment added</h3>";
			}				
		}
	?><a style="font-size:18px" href="#addcomment">Add comment</a> &nbsp;&nbsp;&nbsp;&nbsp;
    <a style="font-size:18px" href="index.php">Index</a><br><?php
	$query="SELECT * FROM `scratch_comments` WHERE project_id=$project_id ORDER BY date ";
	$result=mysql_query($query) or die('Query failed'.mysql_error());
	if(mysql_num_rows($result)==0)
		echo "<h2>No Comments yet.</h2>";
	else
	{
	
		while($row=mysql_fetch_assoc($result))
		{
		
			$comment=$row['comment'];
			$comment=str_replace("\n","<br>",$comment);
		?>
        
		<table cellspacing="0" bordercolor="#00FFFF" border="1" ><tr><td width="435">	<strong><?php echo $row['author'] ?></strong> said:<strong>  <?php echo $row['date'] ?></strong></td>
		</tr>
        <tr><td style="font-family:'Courier New', Courier, monospace;font-weight:700">
			<?php echo $comment ?>
            </td></tr></table><br>
		<?php }
	}

?>
<a name="addcomment"></a>
<?php
if(isset($_SESSION['flag'])) 
{ 

	$username=$_SESSION['username'];
	
		$admin_author=get_name($username);
		$admin_email=get_email($username);
	
  echo "<b>Logged in as $username</b>";
}

?>
</p>

  <p style="font-size:22px;color: #660000"><strong>Your comment</strong></p>
  <form id="form1" name="form1" onSubmit="return validate_form(this)" method="post" action="">
    <p>
      <textarea name="comment" id="comment" cols="50" rows="5" style="border:groove;" ><?php if($code==0) echo $author_comment; ?></textarea>
    </p>
    <table width="361" border="0">
      <tr>
        <td width="150">Name</td>
        <td width="201"><input name="name" type="text" id="name" size="40" style="border:groove;" maxlength="30" value="<?php if($code==0){ echo $author;} else {echo $admin_author; } ?>" /></td>
      </tr>
      <tr>
        <td>Email (optional)</td>
        <td><input type="text" name="email" id="email" size="40" style="border:groove;" value="<?php if($code==0){ echo $author_email;} else {echo $admin_email; } ?>"/></td>
      </tr>
  
    </table>
    <p>
    Enter the code shown:
	<?php 
	require_once('admin/recaptchalib.php');
	$publickey = "6LfuugkAAAAAADre-k0l43Q6rcLRfZUWtX4mqtix"; // you got this from the signup page
	echo recaptcha_get_html($publickey);
	?>
</p>
    <p>
      <input type="submit" name="submit" id="submit" value="Submit" />
    </p>
<?php
}
?>
  </form>
<a style="font-size:18px;color:#9900CC" href="<?php echo get_siteurl(); ?>">Copyright&copy; <?php echo get_name(users()); ?></a>
</center>
</body>
</html>
