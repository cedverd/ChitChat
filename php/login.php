<?php
	session_start();
	if(isset($_POST['uname']) && isset($_POST['pass']))
	{
		$uname = $_POST['uname'];
		$password = $_POST['pass'];
		if(!empty($uname) && isset($password))
		{
			$con = mysqli_connect("locaLhost","root","","chat_db");
			$qry = " select * from user_info where uname = '$uname' and pass = '$password'";
			$result = $con->query($qry);
			if( $row = $result->fetch_assoc())
			{
				$_SESSION['myid'] = $row['Uid'];
				header('Location:../invite.html');
			}
		}
		else
			header('Location:../index.html');
	}
	else
		header('Location:../index.html');
?>