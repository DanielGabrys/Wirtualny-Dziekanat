<?php

session_start();

if(!isset($_SESSION['student']) && !isset($_SESSION['prowdz']) && !isset($_SESSION['prac']) )
{
	header('location:index.php');
}

require_once "menu_panel.php";
menu();
?>

<!DOCTYPE html>
<html>
<body>

<div id = "work" >	

	<h1>We are working on this page</h1>
	<img src="image/work.png" alt="" width="400" height="400">
</div>

</body>
</html>



<style>

#work
	{
		width:500px;
		margin:auto;
		text-align:center;
	}
	
	
</style>