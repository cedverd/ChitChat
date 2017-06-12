<?php
	session_start();
		$myid = $_SESSION['myid'];
		if(isset($_POST['r_id']) && isset($_POST['i_msg']))
		{
			if (!empty($_POST['r_id']) && !empty($_POST['i_msg'])) 
			{
				$r_id = $_POST['r_id'];
				$i_msg = $_POST['i_msg'];
				$con = mysqli_connect("locaLhost","root","","chat_db");
				$qry = "  insert into invitations (source_ID,dest_ID,invite_msg) values ('$myid','$r_id','$i_msg')";
				if($con->query($qry))
				{
					header('Location:../html/invitestatus.html');
				}
			}
			else
			{
				echo "please fill the dialog boxes properly";
				header('Location:../invite.html');
			}
		}
?>