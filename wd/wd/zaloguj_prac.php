<?php
session_start();

if((!isset($_POST['login_p'])) || (!isset($_POST['haslo_p'])))
{
header('Location:index.php');
exit();
}
require_once"connect.php";

$con =new mysqli($servername, $username, $password, $database);
if($con->connect_errno!=0)
{
	echo "Error: Unable to connect to MySQL. <br>" . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() ."<br>". PHP_EOL;
    
}

else
{ 
    $login=$_POST['login_p'];
	$haslo=$_POST['haslo_p'];
	
 	
	$login=htmlentities($login,ENT_QUOTES,"UTF-8"); //ochrona przed wstrzykiwaniem sql
 	
	$sql="SELECT* FROM pracownicy WHERE mail='%s'";
	if($rezultat=$con->query(sprintf($sql,mysqli_real_escape_string($con,$login))))
	{
	$ilu=$rezultat->num_rows;
        
        
		if($ilu>0)
		{
			$wiersz=$rezultat->fetch_assoc();
			if(password_verify($haslo,$wiersz['haslo']))
			{

				$_SESSION['prac_id']=$wiersz['ID'];
				$_SESSION['dane']=$wiersz['imie']." ".$wiersz['nazwisko'];

				$_SESSION['prac']=true;
				unset($_SESSION['blad']);

				$rezultat->close();

				header('Location:main_prac.php');
				exit();
			}
			else
			{
			$_SESSION['blad']='<span style ="color:red"> Nieprawidlowy login lub haslo </span>';
			header('Location:zaloguj_prac_layout.php');
			}
		 }
		else
		{
		$_SESSION['blad']='<span style ="color:red"> Nieprawidlowy login lub haslo </span>';
		header('Location:zaloguj_prac_layout.php');
	
		}
		$con->close();

	}
}

?>
