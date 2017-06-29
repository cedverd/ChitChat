create  database Chat_DB;


create table user_info
	(
		Uid integer(3) primary key auto_increment,
		uname varchar(30) not null unique,
		pass varchar(15) not null,
		email varchar(30) not null
	);

create table messages
	(
		message_ID integer(3) primary key  auto_increment ,
		message_status varchar(6) default 'unseen',
		source_ID integer(3) not null,
		dest_ID integer(3) not null,
		message varchar(100) not null ,
		Date integer(10) 
	);
create table Invitations
	(
		Invi_ID integer(3) primary key auto_increment,
		source_ID integer(3),
		dest_ID integer(3) not null,
		status varchar (15) default 'n',
	);


	SELECT * FROM messages WHERE (
									( source_ID = '{$_SESSION['myid']}' AND dest_ID = '$destid')
									 OR
									( source_ID = '$destid' AND dest_ID = '{$_SESSION['myid']}') 
								)
	( source_ID = '{$_SESSION['myid']}' AND dest_ID = '{$_SESSION['myid']}' ) ORDER BY `dateline` DESC LIMIT 30";);