<?php
	session_start();
	if(isset($_SESSION['myid']))
	{
		$myid = $_SESSION['myid'];
		if(!empty($myid))
		{
			$con = mysqli_connect("locaLhost","root","","chat_db");
			$qry = " select * from invitations where source_ID = '$myid' order by Invi_ID desc";
			$result = $con->query($qry);
			if($row = $result->fetch_assoc())
			{		
				if($row['status'] =='y')
				{
					$_SESSION['inviteid'] = $row['Invi_ID'];
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