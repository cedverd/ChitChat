

<?php
	require_once "login.php";
	echo "hi";
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile</title>
	<link rel="stylesheet" type="text/css" href="../css/profile.css">
	
</head>
<body>
	
	<div class="profile-heading">
		<p>Profile</p>	
	</div>
	<hr>
	<div class="profile-info">
		<div class="profile-dp">
			<img class="dp" src="../images/theme1.jpg" width="380px" height="253px">
		</div>
		<div class="profile-details">
			<div class="name1">
				Username<br>
				<input type="text" name="">
			</div>
			<div class="name2">
				Friends<br><input type="text" name="">
			</div>
			<div class="name3">
				Messages<br><input type="text" name="">
			</div>	
		</div>
		<div class="message">
			<button class="profile-btn">Send Message</button>
		</div>
	</div>
</body>
</html>