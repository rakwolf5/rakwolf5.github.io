<!DOCTYPE html>
<html lang="lt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Hostingas</title>
  <link rel="stylesheet" href="style.css" media="screen">
	<script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
	<script language="JavaScript" type="text/javascript">
		function checkDelete(){
		  return confirm('Are you sure?');
		}
	</script>
</head>
	<body>

		<div id="upload" style="visibility:hidden;">
			<form action="upload.php" method="post" enctype="multipart/form-data">
				<input style="font-size: 30px;" type="file" name="fileToUpload" id="fileToUpload">
				<input style="font-size: 30px;" type="submit" value="Upload File" name="submit">
			</form>
		</div>

		<div id="viewfiles" style="visibility:hidden;">
			<?php

			$db = mysqli_connect('localhost', 'root', 'usbw', 'main');

			$dir = 'uploads/' . $_SESSION['username'];

			function FileSizeConvert($bytes)
			{
				$bytes = floatval($bytes);
				$arBytes = array(0 => array("UNIT" => "TB", "VALUE" => pow(1024, 4)),
					            	 1 => array("UNIT" => "GB", "VALUE" => pow(1024, 3)),
					            	 2 => array("UNIT" => "MB", "VALUE" => pow(1024, 2)),
					            	 3 => array("UNIT" => "KB", "VALUE" => 1024),
					            	 4 => array("UNIT" => "B", "VALUE" => 1),);

				foreach($arBytes as $arItem)
				{
					if($bytes >= $arItem["VALUE"])
					{
					  $result = $bytes / $arItem["VALUE"];
					  $result = str_replace(".", "," , strval(round($result, 1))).$arItem["UNIT"];
					  break;
					}
				}

				return $result;
			}

			$sql1 = "SELECT user_id 
							FROM users
							WHERE username=" ."'". $_SESSION['username'] ."'";

			$resultid = mysqli_query($db, $sql1);

			while($row = mysqli_fetch_assoc($resultid))
			{
				$id = $row["user_id"];
			}

			$sql2 = "SELECT file_id, filename, size
							FROM files
							WHERE user_id=" ."'". $id ."'";

			$result = mysqli_query($db, $sql2); //make another querry to check user id

			if (mysqli_num_rows($result) > 0)
			{
				echo "<table class='center'>
								<tr>
									<th>Name</th>
									<th>Size</th>
									<th></th>
									<th></th>
								</tr>
								<tr>";
				while($row = mysqli_fetch_assoc($result)) 
				{
					echo "<td>" . $row["filename"] . "</td><td>" . FileSizeConvert($row["size"]) . "</td>";
					echo "<td><a style='color: black;' href='" . $dir . "/" . $row["filename"] . "'>
								<i class='fa fa-download'></i></a></td>";
					echo "<td><a style='color: black;' href='delete.php?delete=" . $row["filename"] . " " . $row["file_id"] .
							 "' onclick='return checkDelete()'><i class='fa fa-trash'></i></a></td></tr>"; 
				}
			}
			else echo 'No files...';

			?>
			</table>
		</div>

		<div id="about" style="visibility:hidden;">
			<span class="text">About</span>
		</div>

		<div class="wrapper hover_collapse">
			<div class="top_navbar">
				<div class="logo">Hostingas</div>
			</div>
			<div class="sidebar">
				<div class="sidebar_inner">
				<ul>
					<li>
						<a onclick="document.getElementById('selected').innerHTML=document.getElementById('viewfiles').innerHTML;">
							<span class="icon"><i class="fa fa-list"></i></span>
							<span class="text">Files</span>
						</a>
					</li>
					<li>
						<a onclick="document.getElementById('selected').innerHTML=document.getElementById('upload').innerHTML;">
							<span class="icon"><i class="fa fa-upload"></i></span>
							<span class="text">Upload</span>
						</a>
					</li>
					<li>
						<a onclick="document.getElementById('selected').innerHTML=document.getElementById('about').innerHTML;">
							<span class="icon"><i class="fa fa-question-circle"></i></span>
							<span class="text">About</span>
						</a>
					</li>
		      <li>
						<a href="#">
							<span class="icon"><i class="fa fa-user"></i></span>
					   	<span style="text-align: center;" class="text">Current user:<br>

					   	<?php
					   		if (isset($_SESSION['username'])) :
					   			echo "<p class='user'>" . $_SESSION['username'] . "</p>";
					   		endif
					   	?>

						  <form method="post" action="index.php?logout='1'">
	    				 	<input class="button" type="submit" value="logout" />
							</form>
						</a>
					</li>
				</ul>
				</div>
			</div>
		</div>

		<div class="content" id="selected"></div>

		<script type="text/javascript">

		document.getElementById('selected').innerHTML=document.getElementById('viewfiles').innerHTML;

		 var li_items = document.querySelectorAll(".sidebar ul li");
		var wrapper = document.querySelector(".wrapper");


		li_items.forEach((li_item)=>{
			li_item.addEventListener("mouseenter", ()=>{
				li_item.closest(".wrapper").classList.remove("hover_collapse");

			})
		})

		li_items.forEach((li_item)=>{
			li_item.addEventListener("mouseleave", ()=>{

					li_item.closest(".wrapper").classList.add("hover_collapse");

			})
		})
		</script>
	</body>
</html>
