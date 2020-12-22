<?php

	require "header.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>admin portal | spider</title>
	<style>
		#portal{
			width: 70%;
			margin-right: auto;
			margin-left: auto;
			padding-top: 10px;
			padding-left: 20px
		}
		img{
			display: inline;
		}
	</style>
</head>
<body>
	<div id="portal">
		<a href="list.php?t=english"><img src="enword_logo.PNG" alt="enword" height="120" width="15%"></a>
		<a href="list.php?t=telugu"><img src="telword_log.PNG" alt="telword" height="120" width="15%"></a>
		<a href="list.php?t=crawlurl"><img src="crawlurl_logo.PNG" alt="crawlurl" height="120" width="15%"></a>
		<a href="list.php?t=suggesturl"><img src="suggest_logo.PNG" alt="suggest" height="120" width="15%"></a>
		<a href="list.php?t=users"><img src="useradmin_logo.PNG" alt="useradmin" height="120" width="15%"></a><br />
		<a href=""><img src="configure_logo.PNG" alt="configure" height="120" width="15%"></a>
		<a href=""><img src="import_logo.PNG" alt="import" height="120" width="15%"></a>
		<a href=""><img src="export_logo.PNG" alt="export" height="120" width="15%"></a>
		<a href=""><img src="backup_logo.PNG" alt="backup" height="120" width="15%"></a>
		<a href=""><img src="help_logo.PNG" alt="help" height="120" width="15%"></a>
	</div>
</body>
</html>