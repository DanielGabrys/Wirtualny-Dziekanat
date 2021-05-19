<?php
session_start();

if((!isset($_POST['login_s'])) || (!isset($_POST['haslo_s'])))
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
    $login=$_POST['login_s'];
	$haslo=$_POST['haslo_s'];
	
 	
	$login=htmlentities($login,ENT_QUOTES,"UTF-8"); //ochrona przed wstrzykiwaniem sql
 	
	$sql="SELECT* FROM studenci WHERE nr_album='%s'";
	if($rezultat=$con->query(sprintf($sql,mysqli_real_escape_string($con,$login))))
	{
	$ilu=$rezultat->num_rows;
        
        
		if($ilu>0)
		{
			$wiersz=$rezultat->fetch_assoc();
			if(password_verify($haslo,$wiersz['haslo']))
			{

				$_SESSION['student_id']=$wiersz['ID'];
				$_SESSION['dane']=$wiersz['imie']." ".$wiersz['nazwisko'];

				$_SESSION['student']=true;
				unset($_SESSION['blad']);

				$rezultat->close();

				header('Location:main_student.php');
				exit();
			}
			else
			{
			$_SESSION['blad']='<span style ="color:red"> Nieprawidlowy login lub haslo </span>';
			header('Location:zaloguj_student_layout.php');
			}
		 }
		else
		{
		$_SESSION['blad']='<span style ="color:red"> Nieprawidlowy login lub haslo </span>';
		header('Location:zaloguj_student_layout.php');
	
		}
		$con->close();

	}
}

?>
