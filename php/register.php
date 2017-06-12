<?php
	if(isset($_POST['uname']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['cpass']))
	{
		$uname = $_POST['uname'];
		$email = $_POST['email'];
		$password = $_POST['pass'];
		$cpass = $_POST['cpass'];
		if(!empty($uname) && !empty($cpass) && !empty($email) && isset($password))
		{
			if($cpass == $password)
			{
				$con = mysqli_connect("locaLhost","root","","chat_db");
				$qry = " insert into user_info (uname,pass,email) values ('$uname','$password','$email')";
				if($con->query($qry))
				{
					echo "registration successful";
					header('Location:../index.html');
				}
			}
			else
			{
				echo "password mismatch";
				header('Location:../index.html');
			}
		}
		else
		{
			echo "Fill all the fields properly";
			header('Location:../index.html');
		}
	}
	else
		header('Location:index.html');
?>