<?php
	session_start();

	if (isset($_SESSION['myid'])) 
	{		
		if( !empty($_SESSION['myid']))
		{
			$con = mysqli_connect("localhost","root","","chat_db");
			$q = "select * from invitations where dest_ID = '{$_SESSION['uid']}'";
			$result = $con->query($q);
			
			if($row = $result->fetch_assoc())
			{
				$_SESSION['dest'] = $row['source_ID'];
				$query = "update  invitations set status  = 'y' where source_ID = '{$_SESSION['dest']}' and dest_ID = '{$_SESSION['myid']}'";
				$con->query($query);
				header('Location: startchat.php');
			}
			else
				header('Location:..//index.html');
		} 
		else
		{
			header('Location:..//index.html');
		}
	}
	else
		header('Location:..//index.html');
?>