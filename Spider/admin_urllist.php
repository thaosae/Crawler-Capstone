<?php 
	require "header.php";
	$words_sort = $_GET['sort'];
	$order_by = $_GET['by'];
	$action = $_GET['action'];
	$delete_word = $_GET['word'];
	$delete_option = $_GET['act'];
	$option = $_GET['act'];
	
	echo $option;
					
?>

<!DOCTYPE html>
<html>
<head>
	<title>list view | spider</title>
	<style rel="stylesheet" type="text/css">
		.word, .length, .edit{
			border: 1px solid orange;
			border-top: none;
		}
		.list-header{
			border: 1px solid orange;
			color: orange;
		}
		.list-header a{
			color: grey;
			text-decoration: none;
			overflow: hidden;
		}
		table{
			margin-left: auto;
			margin-right:auto;
			text-align: center;
		}
		form{
			display: inline-block;
			text-align: center;
			font-weight: bold;
			padding-top: 10px;
			padding-bottom: 10px;
		}
		#s-form{
			margin-left: 40%;
		}
		span{
			display:block;
			margin-left: auto;
			margin-right: auto;
			color: orange;
			text-align: center;
			font-weight: bold;
			font-size: 14pt;
			padding-top: 10px;
			padding-bottom: 10px;
		}
		a{
			text-decoration: none;
		}
	
	</style>
</head>
</body>
	<span>List of URL in Database</span>
	<form id="s-form" action="<? $_SERVER['PHP_SELF'] ?>" method="post" name="search-word-form">
		<input type="text" id="search-text" name="search-word" placeholder="Enter URL">
		<input id="search-submit" name="search" type="submit" value="Search">
	</form>
	<form id="v-form" action="<? $_SERVER['PHP_SELF'] ?>" method="post" name="view-word-form">
		<input id="view-submit" name="view-all" type="submit" value="View All">
	</form>
	<table id="list-table" width="60%" cellpadding="0" cellspacing="0">
		<tr>
			<th class="list-header"><a href="">&#x25B4;</a>URL<a href="">&#x25BE;</a></th>
			<th class="list-header" width="20%">Action</th>
		</tr>
		<?php
			$dbcn = new mysqli("localhost", "root", "", "crawler");
			$dbcn -> set_charset("utf8");
			$sql = "select * from " . $option . ";";
			$result = $dbcn->query($sql);
			if(!result){
				echo "Cannnot create database";
				exit;
			}
				
			$numRows = $result->num_rows;
				
			for($i = 0; $i < $numRows; $i++){
				$each_row = $result->fetch_assoc();
				print "
					<tr>
						<td></td>
						<td></td>
					</tr>
				";
			}
		?>	
		
	</table>
</body>
</html>