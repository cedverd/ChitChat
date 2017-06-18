<?php

session_start();

function time2str($ts)
{
    if(!ctype_digit($ts))
        $ts = strtotime($ts);

    $diff = time() - $ts;
    if($diff == 0)
        return 'now';
    elseif($diff > 0)
    {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 60) return 'just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if($day_diff == 1) return 'Yesterday';
        if($day_diff < 7) return $day_diff . ' days ago';
        if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
        if($day_diff < 60) return 'last month';
        return date('F Y', $ts);
    }
    else
    {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 120) return 'in a minute';
            if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
            if($diff < 7200) return 'in an hour';
            if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
        }
        if($day_diff == 1) return 'Tomorrow';
        if($day_diff < 4) return date('l', $ts);
        if($day_diff < 7 + (7 - date('w'))) return 'next week';
        if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
        if(date('n', $ts) == date('n') + 1) return 'next month';
        return date('F Y', $ts);
    }
}

$con = mysqli_connect("localhost","root","","chat_db");

$query = $con->query( 'SELECT * FROM invitations WHERE `status` = \'y\'' );
$invite = $query->fetch_assoc();

if( empty( $invite ) )
{
	header( "Location: ../invite.html" );
}

if($_SESSION['myid'] == $invite['source_ID'])
{
	$destid = $invite['dest_ID'];

}
else
	$destid = $invite['source_ID'];

if(isset($_POST['msg']))
{
	if(!empty($_POST['msg']))
	{
		$msg = $_POST['msg'];
		$qry = "insert into messages (source_ID,dest_ID,message,dateline) values('{$_SESSION['myid']}','$destid','$msg',".time().")";

		if(!$con->query($qry))
		{
			echo $con->error;
		}
	}
}

$q = "select * from messages where ( source_ID = '{$_SESSION['myid']}' or source_ID = '{$destid}' ) ORDER BY `dateline` DESC LIMIT 30";
$result = $con->query($q);
if($result)
{
	$messages = '';
	$_messages = array();
	while ($row = $result->fetch_assoc()) 
	{	
		$_messages[] = $row;	
	}

	$_messages = array_reverse( $_messages );

	$ids = [];
	foreach( $_messages AS $row )
	{
		$date = time2str( $row['dateline'] );

		$ids[] = $row['message_ID'];
		if($_SESSION['myid'] == $row['source_ID'])
		{
			//$messages = $messages.$_SESSION['uname'];
			$messages = $messages. '<div class="chatbubble chatbubble-self">'.$row['message'].'<div class="message-date">' . $date . '</div></div>';
		}
		else
		{
			$messages = $messages . '<div class="chatbubble chatbubble-other">'.$row['message'].'<div class="message-date">' . $date . '</div></div>';
		}
	}
}
else
{
	$messages = '<div style="text-align:center max-height:500px;">'.'No messages yet '.'</div>';
}

if( strtolower($_SERVER['REQUEST_METHOD']) == 'post' )
{
	if( isset( $_GET['action'] ) )
	{
		$action = $_GET['action'];

		if( $action == 'get' )
		{
			$messages = '';
			$q = "SELECT * from messages where ( source_ID <> '{$_SESSION['myid']}' ) AND `message_status` = 'unseen' ORDER BY `dateline` DESC LIMIT 30";
			
			$result = $con->query($q);
			if($result)
			{
				$messages = '';
				$_messages = array();
				while ($row = $result->fetch_assoc()) 
				{	
					$_messages[] = $row;	
				}

				$_messages = array_reverse( $_messages );

				$ids = [];
				foreach( $_messages AS $row )
				{
					$date = time2str( $row['dateline'] );

					$ids[] = $row['message_ID'];
					if($_SESSION['myid'] == $row['source_ID'])
					{
						//$messages = $messages.$_SESSION['uname'];
						$messages = $messages. '<div class="chatbubble chatbubble-self">'.$row['message'].'<div class="message-date">' . $date . '</div></div>';
					}
					else
					{
						$messages = $messages . '<div class="chatbubble chatbubble-other">'.$row['message'].'<div class="message-date">' . $date . '</div></div>';
					}

					$con->query( 'UPDATE messages SET `message_status` = \'seen\' WHERE `message_ID`=\''.$row['message_ID'].'\' AND source_ID<>\''.$_SESSION['myid'].'\'' );
				}
			}

			echo json_encode( array( 'messages' => $messages ) );
			die();
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>ChatOn</title>
	<meta name="viewport" content = "width = device-width, initial-scale = 1.0, minimum-scale = 1, maximum-scale = 1, user-scalable = no" />
	<meta name="theme-color" content="#1a1d26">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<link rel="stylesheet" type="text/css" href="../css/chat.css">

	<link href="https://fonts.googleapis.com/css?family=Roboto|Inconsolata" rel="stylesheet">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<script type="text/javascript">
		jQuery(function(){
			jQuery( '.controls .messagebox' ).focus();
			jQuery("html, body").animate({ scrollTop: jQuery('.messages')[0].scrollHeight });

			var refresh = function() 
			{
				jQuery.ajax({
					url: 'startchat.php?action=get',
					method: "POST",
					dataType: "json",
					success: function(data){
						if(typeof data.messages !== typeof undefined)
						{
							if( data.messages.length )
							{
								jQuery('.messages').append( data.messages );
								jQuery("html, body").animate({ scrollTop: jQuery('.messages')[0].scrollHeight });
							}
						}
					}
				});

				setTimeout( function(){ 
							refresh();
						}, 1000 );
			}

			refresh();

			var messageHTML = '<div class="chatbubble chatbubble-self" style="text-align:right" background-color="red" float="right">';

			jQuery( '.chatbox-form' ).submit(function(e){
				jQuery.ajax({
					url: jQuery(this).attr( 'action' ),
					method: "POST",
					data: {
						msg: jQuery( '.messagebox' ).val(),
					},
					dataType: "html",
					success: function(data){
						jQuery('.messages').append( messageHTML + jQuery( '.messagebox' ).val() + '</div>' );
						jQuery( '.messagebox' ).val( '' );
						jQuery("html, body").animate({ scrollTop: jQuery('.messages')[0].scrollHeight });
					}
				});

				e.preventDefault();
			});
		});
	</script>
</head>
<body>
	<div class="bg">
		<div class="mask"></div>
	</div>
	<div class="content">
		<a class="logo" href="main.html">ChatOn</a>
		<span onclick="javascript:window.location='endchat.php';" class="btn btn-signout">Log Out</span>

		<div class="mascot">
			<p>Enter the world of <b>Baatein</b> and just forget the world.</p>
			<p>Get ready to be lost in the world of <b>Thoughts</b></p>
		</div>
		<form method="POST" action="startchat.php" class="chatbox-form">
			<div class="chatbox">
				<div class="messages">
				<?= $messages; ?>
				</div>
				<div class="controls">
					<input class="messagebox" type="text" name="msg" placeholder="Write your message" autocomplete="off">
					<button class="send" type="submit"><i class="fa fa-location-arrow"></i></button> 
				</div>
			</div>
		</form>
	</div>
</body>
</html>