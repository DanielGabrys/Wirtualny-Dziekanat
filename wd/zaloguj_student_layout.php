<!DOCTYPE html>
<?php
	session_start();
	if((isset($_SESSION['student'])) && ($_SESSION['student']==true))
	{
	header('Location:main_student.php');
	exit();
	}
?>

<html>
<head>
	<title>WD</title>
</head>

	 <link rel="stylesheet" href="css/style.css" type="text/css"/>
     <link rel="stylesheet" href="css/loginRegistration.css" type="text/css"/>
	
<body>

<a href="index.php"><div class="back" style="margin-left: 10px;" >Powrót do strony głównej</div></a>

<header id="logo_header">
            <p class="main_header">ZALOGUJ SIĘ NA SWOJE KONTO</p>
</header>

<form action="zaloguj_student.php" method="post" class="login_form">
	
	Login: <br/> <input type="text" name="login_s"/> <br/>
 	Hasło: <br/> <input type="password" name="haslo_s"/> <br/>
	
	
<?php
if(isset($_SESSION['blad']))
	echo $_SESSION['blad'];
?>
	<br/>
        <input type="submit" value= "Zaloguj"/>
</form>






</body>
</html> 
