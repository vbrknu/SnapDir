
<html><head><title>Setting up the database</title></head><body>
<h3>Setting up the tables...</h3>
<?php
include_once 'dbwork.php';
createTable('members',
			'user 	VARCHAR(16),
			pass 	VARCHAR(123),
			INDEX(user(6))');

createTable('messages',
			'messageId INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			author 		VARCHAR(16),
			recipient 	VARCHAR(16),
			timestamp 	INT UNSIGNED,
			text VARCHAR(4096),
			attachment 	VARCHAR(64),
			INDEX(author(6)),
			INDEX(recipient(6))');

createTable('friends',
			'user 	VARCHAR(16),
			friend 	VARCHAR(16),
			INDEX(user(6)),
			INDEX(friend(6))');

createTable('profiles',
			'user 	VARCHAR(16),
			text 	VARCHAR(4096),
			INDEX(user(6))');

createTable('interests',
			'interest VARCHAR(32),
			user VARCHAR(16) UNIQUE,
			INDEX(interest(4))');

createTable('visualizations',
			'filename VARCHAR(64),
			user VARCHAR(16),
			domain VARCHAR(32),
			template VARCHAR(32),
			INDEX(filename(4))');

createTable('lastLogin',
			'user VARCHAR(16),
			lastlogin TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
			);

?>

<br />...finished.
</body></html>
