<?php

	session_start();

	if(isset($_SESSION['myid']))
	{	echo $_SESSION['myid'];
		
		$con = mysqli_connect("localhost","root","","chat_db");
		
		if($r = $con->query("SELECT * FROM messages"))
			$con->query("DELETE FROM messages WHERE source_ID = '{$_SESSION['myid']}' OR dest_ID = '{$_SESSION['myid']}'");

		if($r = $con->query("SELECT * FROM invitations"))

			$con->query("DELETE FROM invitations WHERE source_ID = '{$_SESSION['myid']}' OR dest_ID = '{$_SESSION['myid']}'");
		header('Location: ../index.html');
		exit;
	}
	else
	{
		header('Location: ../index.html');
	}

	unset($_SESSION);
	session_destroy();
?>