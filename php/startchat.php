<?php
	session_start();
	$con = mysqli_connect("localhost","root","","chat_db");

	$query = $con->query( 'SELECT * FROM invitations WHERE `status` = \'y\'' );
	$invite = $query->fetch_assoc();

	if( empty( $invite ) )
	{
		header( "Location: ../inivite.html" );
	}

	$destid = $invite['source_ID'];
	$myid = $invite['dest_ID'];
	$message = $_POST['msg'];

	if(isset($_POST['msg']))
	{
		if(!empty($_POST['msg']))
		{
			$qry = "insert into messages (source_ID,dest_ID,message,date) values('$myid','$destid','$message',".time().")";

			if(!$con->query($qry))
			{
				echo $con->error;
			}
		}
	}

	$q = "select * from messages where source_ID = '{$myid}' or source_ID = '{$destid}'";
	$result = $con->query($q);
	if($result)
	{
		$messages = '';
		while ($row = $result->fetch_assoc()) 
		{
			if($_SESSION['myid'] == $row['source_ID'])
			{
				$messages = $messages . '<div class="chat" style="text-align:center" background-color="rgba(52,152,219,1.0)">'.$row['message'].'</div><br />';
			}
			else
			{
				$messages = $messages . '<div class="chat" style="text-align:center" background-color="rgba(46,204,113,1.0)">'.$row['message'].'</div><br />';
			}
		}
	}
	else
	{
		$messages = '<div style="text-align:center max-height:500px;">'.'NO messages yet '.'</div>';
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Wait</title>
	<link rel="stylesheet" type="text/css" href="..//css/style.css">
	<link rel="stylesheet" type="text/css" href="..//css/chat.css">
</head>
<body>
	<a href="main.html">ChatOn</a>

	<h4>Enter the world of <b>Baatein</b> and just forget the world. </h1>
	<h5>Get ready to be lost in the world of <b>Thoughts</b></h2>
	<form method="POST" action="startchat.php">
		<div class="chat-box">
		<?php
			echo $messages;
		?>
			<!--<textarea rows="25" cols="50"></textarea><br>-->
			<span>Message</span><input type="text" name="msg" placeholder="Write your message"><br>
			<button type="Submit">Send</button> 
		</div>
	</form>
</body>
</html>