<?php
	session_start();
	if(isset($_SESSION['myid']))
	{
		$myid = $_SESSION['myid'];
		if(!empty($uid))
		{
			$con = mysqli_connect("locaLhost","root","","chat_db");
			$qry = " select * from invitations where source_ID = '$uid' order by Invi_ID desc";
			$result = $con->query($qry);
			$row = $result->fetch_assoc();

			if( $row)
			{		
				if($row['status'] =='y')
				{
					//redirect to the chat page
					header('Location:startchat.php');
					exit;
				}
				else
				{
					header('Location:../html/invitestatus.html');
				}
			}
		}
		else
			header('Location:..//index.html');
	}
	else
		header('Location:..//index.html');
?>