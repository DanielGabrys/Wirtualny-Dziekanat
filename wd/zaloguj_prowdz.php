<?php
session_start();

if((!isset($_POST['login_pw'])) || (!isset($_POST['haslo_pw'])))
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
    $login=$_POST['login_pw'];
	$haslo=$_POST['haslo_pw'];
	
 	
	$login=htmlentities($login,ENT_QUOTES,"UTF-8"); //ochrona przed wstrzykiwaniem sql
 	
	$sql="SELECT* FROM prowadzacy WHERE mail='%s'";
	if($rezultat=$con->query(sprintf($sql,mysqli_real_escape_string($con,$login))))
	{
	$ilu=$rezultat->num_rows;
        
        
		if($ilu>0)
		{
			$wiersz=$rezultat->fetch_assoc();
			if(password_verify($haslo,$wiersz['haslo']))
			{

				$_SESSION['prowdz_id']=$wiersz['ID'];
				$_SESSION['dane']=$wiersz['prowadzacy'];

				$_SESSION['prowdz']=true;
				unset($_SESSION['blad']);

				$rezultat->close();

				header('Location:main_prowdz.php');
				exit();
			}
			else
			{
			$_SESSION['blad']='<span style ="color:red"> Nieprawidlowy login lub haslo </span>';
			header('Location:zaloguj_prowdz_layout.php');
			}
		 }
		else
		{
		$_SESSION['blad']='<span style ="color:red"> Nieprawidlowy login lub haslo </span>';
		header('Location:zaloguj_prowdz_layout.php');
	
		}
		$con->close();

	}
}

?>
