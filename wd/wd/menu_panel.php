<?php

function menu()
{
if(isset($_SESSION['student']))
		 {
			$title="OCENY"; 
			$title2="DANE OSOBOWE";
			$titlepage1="oceny.php";
		 }
		 
else if(isset($_SESSION['prac']))
		 {
			$title="STUDENCI"; 
			$title2="PROWADZĄCY";
			$titlepage1="dodajusun_student.php";


		 }
else
		 {
			$title="STUDENCI I OCENY"; 
			$title2="DANE OSOBOWE";
			$titlepage1="studenci_oceny.php";



		 }

	
?>
	
<!DOCTYPE html>
<html>
<head>
	<title>WIRTUALNY DZIEKANAT</title>
</head>

<body>


<div id="imie">
    <div id="text">
    <?php
        echo "</br>".$_SESSION['dane'];
        echo "<br/> <br/>";
    ?>
    </div>
</div>

	
<div id="end"> 
	
<a href="kalendarz.php"> <button id="block2">PLAN ZAJĘĆ</button> </a> 
<a href="	<?php echo $titlepage1 ?>	"> <button id="block2">	<?php echo $title?>	</button> </a> 
	
<?php if(isset($_SESSION['prac']))
{ ?>
		<a href="prowadzacy_edit.php"> <button id="block2">PROWADZĄCY</button> </a> 
		<a href="dane_student.php"> <button id="block2">DANE OSOBOWE</button> </a> 


<?php
} 
else
{ ?>
		<a href="dane_student.php"> <button id="block2">DANE OSOBOWE</button> </a> 
<?php 
} ?>	
	
<a href="wiadomosci.php"> <button id="block2">WIADOMOŚCI</button> </a>
<a href="logout.php"> <button id="block3">WYLOGUJ!</button> </a> </br> 
	
	</div>

 
</body>
</html> 

<style>

A {text-decoration: none;}

#block, #block2, #block3
{
    display: block;
    border: none;
    color: white;
	height: 80px;
    padding: 8px 14px;
    font-size: 18px;
    cursor: pointer;
    text-align: center;
    float:left;
}

#block, #block2
{
	
	<?php 
	if(!isset($_SESSION['prac']))  {?>
    	width: 22.5%; 
	<?php }
	else {?>
		width: 18%; 
	<?php } ?>

}

#block3
{
    	width: 10%; 
}

#block2, #block3
{
  background-color: grey;
}

#block:hover,#block2:hover,#block3:hover
{
  background-color: #ddd;
  color: black;
}

#imie
{
    background-color:#ddd; 
}

#text
{
    margin-left:10px;
	text-align:center;
}

#end
{
height:100px;
	
float:none;
}
	
</style
	
<?php
}
?>