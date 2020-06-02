<?php
	function cleanup_text ($value="", $preserve="", $allowed_tags="")
	{
		if(empty($preserve))
		{
			$value = strip_tags($value,$allowed_tags);
		}
		$value= htmlspecialchars($value);
		return $value;
	}
//////////////////////////////////////
///////SCRATCH USER DETAILS STARTS///////////////////
/////////////////////////////////////
	function get_name($username)
	{
		$username = str_replace(' ', '', $username); 
		$sql="SELECT * FROM scratch_users WHERE username = '$username'";
		$result=mysql_query($sql) or die("Query failed");
		while($row=mysql_fetch_assoc($result))
		{
			return $row['name'];
		}
	}
	function get_siteurl($username="")
	{
		$sql="SELECT * FROM scratch_users ";
		$result=mysql_query($sql) or die("Query failed");
		while($row=mysql_fetch_assoc($result))
		{
			return $row['siteurl'];
		}
	}
		function get_password($username="")
		{
			$username = str_replace(' ', '', $username); 
			$sql="SELECT * FROM scratch_users WHERE username = '$username'";
			$result=mysql_query($sql) or die("Query failed");
			while($row=mysql_fetch_assoc($result))
			{
				return $row['password'];
			}
		}
		function get_email($username="")
		{
					$username = str_replace(' ', '', $username); 
			$sql="SELECT * FROM scratch_users WHERE username = '$username'";
			$result=mysql_query($sql) or die("Query failed");
			while($row=mysql_fetch_assoc($result))
			{
				return $row['email'];
			}
		}
		function get_key($username="")
		{
				$username = str_replace(' ', '', $username); 
			$sql="SELECT * FROM scratch_users WHERE username = '$username'";
			$result=mysql_query($sql) or die("Query failed");
			while($row=mysql_fetch_assoc($result))
			{
				return $row['key'];
			}
		}
		function users() // returns all the users
		{
			$sql="SELECT * FROM scratch_users ";
			$result=mysql_query($sql) or die("Query failed");
			while($row=mysql_fetch_assoc($result))
			{
				$users=$users." ".$row['username'];
			}
			return $users;
		}
//////////////////////////////////////
///////SCRATCH USER DETAILS ENDS///////////////////
/////////////////////////////////////		
	//function to encrypt the string
	function encode($str)
	{
	  for($i=0; $i<5;$i++) // 7 times
	  {
	    $str=base64_encode(strrev($str)); //apply base64 first and then reverse the string	
	  }
	  return $str;
	}
	
	//function to decrypt the string
	function decode($str)
	{
	  for($i=0; $i<5;$i++)
	  {
		$str=strrev(base64_decode($str)); //apply base64 first and then reverse the string}
	  }
	  return $str;
	}
	function mail_password($username="")
	{
		$sql="SELECT * FROM scratch_users WHERE username='$username'";
		$result=mysql_query($sql) or die("Error:query failed" . mysql_error());
		while($row=mysql_fetch_assoc($result))
		{
			$email= $row['email'];
			$password= decode($row['password']);
			
		}	
			$host=$_SERVER['HTTP_HOST'];
				$to      = $email;
				$subject = $host.':Your Scratch Account Password';
				$message = "Your Scratch Password on $host \r\nUsername: $username \r\n Password: $password \r\n" .
							" Login here http://$host/scratch/admin  \r\n". 
							"Thank You ";
							
							
					
						$headers = 'From: scratch@'. $host . "\r\n" .
							'Reply-To: satyadeep.1991@gmail.com' . "\r\n" .
							'X-Mailer: PHP/' . phpversion();
						
				if(mail($to, $subject, $message, $headers))
				{
					echo "<h2>Password sent to your email address </h2>";
				}
				else
				{
					echo "<h2>Error in sending password to your email</h2>";
				}
	}
	
//GENERATE RANDOM ACTIVATION CODE
	function generate_code($length = 20)
	{
	   $password="";
	   $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	   srand((double)microtime()*1000000);
	   for ($i=0; $i<$length; $i++)
	   {
		  $password .= substr ($chars, rand() % strlen($chars), 1);
	   }
	   return $password;
	} 
	
	function is_valid_email($email){
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
	}

	function is_table_exists($tblname="")
	{
		$sql="SELECT * FROM $tblname";
		$result=mysql_query($sql);
		if (!$result)
			return false;
		else
			return true;

	}

	function fcopy($source="",$target="")
	{	
	 $fp=fopen($target,"w");
	 $content=file_get_contents($source);
	 fwrite($fp,$content);
	 fclose($fp);
	 }
?>