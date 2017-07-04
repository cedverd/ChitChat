<?php
	session_start();

	if (isset($_SESSION['myid'])) 
	{		
		if( !empty($_SESSION['myid']))
		{
			$con = mysqli_connect("localhost","root","","chat_db");
			$result = $con->query("select * from invitations where dest_ID = '{$_SESSION['myid']}'");
			
			if($row = $result->fetch_assoc())
			{
				$_SESSION['dest'] = $row['source_ID'];
				$_SESSION['inviteid'] = $row['Invi_ID'];
				
				$query = "update  invitations set status  = 'y' where Invi_ID='".$row['Invi_ID']."'";
				$con->query($query);
				header('Location: startchat.php');
			}
			else
				header('Location:../invite.html');
		} 
		else
		{
			header('Location:..//index.html');
		}
	}
	else
		header('Location:..//index.html');
?>