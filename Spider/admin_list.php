<?php 
	require "header.php";
	$words_sort = $_GET['sort'];
	$order_by = $_GET['by'];
	$action = $_GET['action'];
	$delete_word = $_GET['word'];
	$delete_option = $_GET['lan'];
	$option = $_GET['language'];
	
	if($words_sort == "")
		$words_sort = "asc";
		
	if($order_by == "")
		$order_by = "word";
		
	if($delete_word != "" && strcasecmp($action, "delete") == 0){
		$connect = new mysqli('localhost', 'root', '', 'crawler');
		$connect -> set_charset("utf8");
		if(mysqli_connect_errno()){
        			echo "<p>Error creating database connection: </p>";
        			exit;
    			}

		$query = "delete from ".  $delete_option . " where word='" . $delete_word . "';";
		$result2 = $connect->query($query);
		if(!$result2){
			echo "Cannot create database";
			exit;
		}
		
		mysqli_close($connect);	
		echo $delete_word . " was successfully deleted";
	}
					
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
	<span>List of words in Database</span>
	<form id="s-form" action="<? $_SERVER['PHP_SELF'] ?>" method="post" name="search-word-form">
		<input type="text" id="search-text" name="search-word" placeholder="Enter word">
		<input id="search-submit" name="search" type="submit" value="Search">
	</form>
	<form id="v-form" action="<? $_SERVER['PHP_SELF'] ?>" method="post" name="view-word-form">
		<input id="view-submit" name="view-all" type="submit" value="View All">
	</form>
	<table id="list-table" width="60%" cellpadding="0" cellspacing="0">
		<tr>
			<th class="list-header"><a href="admin_list.php?sort=desc&by=word&language=<?php echo $option;?>">&#x25B4;</a>Words<a href="admin_list.php?sort=asc&by=word&language=<?php echo $option;?>">&#x25BE;</a></th>
			<th class="list-header" width="15%"><a href="admin_list.php?sort=desc&by=char_len&language=<?php echo $option;?>">&#x25B4;</a>Length<a href="admin_list.php?sort=asc&by=char_len&language=<?php echo $option;?>">&#x25BE;</a></th>
			<th class="list-header" width="20%">Action</th>
		</tr>
		<?php
			
			$dbcn = new mysqli("localhost", "root", "", "crawler");
			$dbcn -> set_charset("utf8");
			 if(mysqli_connect_errno()){
        			echo "<p>Error creating database connection: </p>";
        			exit;
    			}
    			
			$sql = "select * from " . $option . " order by " . $order_by . " " . $words_sort . ";";
			$result = $dbcn->query($sql);
			if(!$result){
				echo "Cannot Create Database";
				exit;
			}
			
			$numRows = $result->num_rows;
			
			if($numRows > 0){
				if(isset($_POST['search'])){
					
					$search_word = trim($_POST['search-word']);

					$sql2 = "select * from " . $option . " where word='" . $search_word. "';";
					$result2 = $dbcn->query($sql2);
					if(!$result){
						echo "Cannot Create Database";
						exit;
					}
					
					$row = $result2->fetch_array();
					$word = strtolower($row['word']);
					$length = $row['char_len'];
					$id = $row['en_id'];
					print "
						<tr>
							<td class='word'>$word</td>
							<td class='length'>$length</td>
							<td class='edit'><a href='edit.php?word=$word&id=$id&lan=$option'>edit</a> | <a href='admlist.php?action=delete&word=$word&lan=$option&language=$option'>delete</a></td>
						</tr>";
										
				}
				else if(isset($_POST['view-all'])){
					for($i = 0; $i < $numRows; $i++)
					{
						$row = $result->fetch_array();
						$word = strtolower($row['word']);
						$length = $row['char_len'];
						$id = $row['en_id'];
						print "
								<tr>
								<td class='word'>$word</td>
								<td class='length'>$length</td>
								<td class='edit'><a href='edit.php?word=$word&id=$id&lan=$option'>edit</a> | <a href='admin_list.php?action=delete&word=$word&lan=$option&language=$option'>delete</a></td>
							</tr>";
					}
				}
				else{
					for($i = 0; $i < $numRows; $i++)
				{
					$row = $result->fetch_array();
					$word = strtolower($row['word']);
					$length = $row['char_len'];
					$id = $row['en_id'];
					print "
						<tr>
							<td class='word'>$word</td>
							<td class='length'>$length</td>
							<td class='edit'><a href='edit.php?word=$word&id=$id&lan=$option'>edit</a> | <a href='admin_list.php?action=delete&word=$word&lan=$option&language=$option'>delete</a></td>
						</tr>";
				}
				}
			}
			else
				echo "Database is Empty";
		
			mysqli_close($con);	
		?>
		
	</table>
</body>
</html>