<?php
require_once "connect.php";

session_start();

//jezeli nie jestesmy zalogowani
if(!isset($_SESSION['student']) && !isset($_SESSION['prowdz']) && !isset($_SESSION['prac']) )
{
	header('location:index.php');
}


$data;
$number=NULL;
$number2=NULL;



if(isset($_SESSION['student']))
{
	$id=$_SESSION['student_id'];
	$who="studenci";
}
else if(isset($_SESSION['prowdz']))
{
	$id=$_SESSION['prowdz_id'];
	$who="prowadzacy";
}

else if(isset($_SESSION['prac']))
{
	$id=$_SESSION['prac_id'];
	$who="pracownicy";
}



//funkcje

function requires(&$tab,$zapyt,$s,$u,$p,$d,$operation)
{
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
	    
		$con= new mysqli($s,$u,$p,$d);
		if($con->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
		    $rez=$con->query($zapyt);
    		if(!$rez) throw new Exception($con->error);
			
			if($operation=="r")
			{
				 $rez=$con->query($zapyt);
    			if(!$rez) throw new Exception($con->error);
				
				$ile=$rez->num_rows;
				//echo $ile."</br>";
    			tablica($tab,$rez);
				
			}
    		$con->close();
		}
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}  
}


function tablica(&$tablica,$rezultat) //zapelniamy tablice danymi
{
  $licz=0; 
			
		while($row = mysqli_fetch_assoc($rezultat))
		{
			 
			$tablica[$licz]=$row;
			$licz++;
			  
		}  
}



function wypisz($tab)
{
    
		?>
		<div class="blok2"> 
	    	<?php echo $tab[0]['imie']."</br></br>";?> 
		</div>

		<div class="blok2"> 
	    	<?php echo $tab[0]['nazwisko']."</br></br>";?> 
		</div>

<?php

if(isset($_SESSION['student']))
{ ?>
		<div class="blok"> 
	    	<?php echo $tab[0]['telefon']."</br></br>";?> 
		</div>

		<div class="blok2"> 
	    	<?php echo $tab[0]['nr_album']."</br></br>";?> 
		</div> <?php
}
?>
		<div class="blok"> 
	    	<?php echo $tab[0]['mail']."</br></br>";?> 
		</div>

		<?php	  
}


/////////////////////////////////////// main /////////////////////////////////////////////

require_once "menu_panel.php";
menu();


$zap4="SELECT * from ".$who." WHERE ID=".$id;



$ok=1;
if(isset($_POST['name']))
{
	//dodac kiedyszabezpiezczenie przed wstrzykiwaniem mysql, dodac weryfikacje imion, nazwisk, telefonu
	$Mail=$_POST['mail'];
	
	// sprawdzamy czy istnieje juz taki email
	
	$zap3="SELECT * from ".$who." WHERE mail='$Mail'";
	
    requires($number,$zap3,$servername,$username,$password,$database,"r");
	
	//sprawdzamy mail
	$mailb=filter_var($Mail,FILTER_SANITIZE_EMAIL);	
	
	if((filter_var($mailb,FILTER_VALIDATE_EMAIL)==false) || ($mailb!=$Mail))
	{
		$ok=0;
		$_SESSION['E_mail']="mail jest niepoprawny";
	}
	if($number!=NULL && $number[0]['ID']!=$id)
	{
		$ok=0;
		$_SESSION['E_mail']="mail juz istnieje";
	}
	
	//update
	
	if($ok==1)
	{
		if(isset($_SESSION['student']))
		{
			$zap2="UPDATE ".$who." SET imie= '".$_POST['name']."',  nazwisko='".$_POST['surname']."', telefon=".$_POST['phone'].", mail='".$_POST['mail']."' WHERE ID=".$id;
		}
		else
		{
			$zap2="UPDATE ".$who." SET imie= '".$_POST['name']."',  nazwisko='".$_POST['surname']."', mail='".$_POST['mail']."' WHERE ID=".$id;

		}
		requires($data,$zap2,$servername,$username,$password,$database,"u");
	}
}

if(isset($_POST['old']))
{
	//spraawdzamy hasla
	$Pas1=$_POST['new'];
	$Pas2=$_POST['new2'];
	$pass=$_POST['old'];

	
	requires($number2,$zap4,$servername,$username,$password,$database,"r");
	
	if(!password_verify($pass,$number2[0]['haslo']))
	{
		$ok=0;
		$_SESSION['E_Pas']="hasło nieprawidłowe";
		
	}
	

	if((strlen($Pas1)<8) || (strlen($Pas2)>20)) //na ten monet nie sprawdamy znakow innych niz alfanumeryczne
	{
		$ok=false;
		$_SESSION['E_Pas']="Haslo powinno posiadać od 8 do 20 znaków!";
	}
	if($Pas1!=$Pas2)
	{
		$ok=false;
		$_SESSION['E_Pas']="Hasła nie są identyczne";
	}
	
	
	
	$haslo_hash=password_hash($Pas1,PASSWORD_DEFAULT);
	
	if($ok==1)
	{
		$zap5="UPDATE ".$who." SET haslo='$haslo_hash' WHERE ID='$id'";
		requires($data,$zap5,$servername,$username,$password,$database,"u");
		header("Location:logout.php");
	}


}

$zap1="SELECT * from ".$who." WHERE ID=".$id;


requires($data,$zap1,$servername,$username,$password,$database,"r");

?> 
<div id="space"> </div> 

<?php 

?>
<div class="con"> 
	
	
	<div class="info2"> 
		 <?php echo "IMIE"."</br></br>";?> 
	</div>	

	<div class="info2"> 
		 <?php echo "NAZWISKO"."</br></br>";?> 
	</div>	
<?php
{ 
	if(isset($_SESSION['student']))
	{?>
		<div class="info"> 
			 <?php echo "TELEFON"."</br></br>";?> 
		</div>	

		<div class="info2"> 
			 <?php echo "NR ALBUMU"."</br></br>";?> 
		</div>
	<?php
	} 
}
	?>
	
	<div class="info"> 
		 <?php echo "mail"."</br></br>";?> 
	</div>
	
<?php
	wypisz($data);	
?>

	<form action="" class="f1" method="POST">
	  <input type="submit" value="EDYTUJ DANE OSOBOWE" class="sub1" name="edit">
	</form>

	<form action="" class="f1" method="POST">
	  <input type="submit" value="ZMIEŃ HASŁO" class="sub1" name="edit2">
	</form>
	
<div id="space2"> </div>
	
	
	
<?php
	
/// zmiana danych osobowych 
	
if(isset($_POST['edit']) || isset($_SESSION['E_mail']))
	{	 
 ?>
<form action="" id="f2" method="POST" id="f2">
	
	IMIE <br/> <input type="text" name="name" class="input" value="<?php echo $data[0]['imie'] ?>"/> <br/>
 	NAZWISKO <br/> <input type="text" name="surname" class="input" value="<?php echo $data[0]['nazwisko'] ?>"/> <br/>
	
	<?php if(isset($_SESSION['student']))
 	{?>	TELEFON <br/> <input type="text" name="phone" class="input"  value="<?php echo $data[0]['telefon'] ?>"/> <br/> <?php } ?>
 	MAIL<br/> <input type="text" name="mail" class="input"  value="<?php echo $data[0]['mail'] ?>"/> <br/>
			<?php
			if(isset($_SESSION['E_mail']))
			{	
				echo '<div class="error">'.$_SESSION['E_mail'].'</div> <br>';
				unset($_SESSION['E_mail']);
			}
			?>

   <input type="submit" value= "ZAPISZ" id="sub2" name="sub2"/>
	
</form>	

<?php } 
		
//  zmiana hasła	
	
if(isset($_POST['edit2']) || isset($_POST['old']))
{ 
 ?>
<div id="t">
	UWAGA!!! </br>
	Po zmianie hasła nastąpi wylogowanie </br></br>
</div>
	
<form action="" id="f2" method="post" id="f2">
	
	
	AKTUALNE HASŁO <br/> <input type="password" name="old" class="input" value=""/> <br/>
 	NOWE HASŁO <br/> <input type="password" name="new" class="input"/> <br/>
	POWTÓRZ NOWE HASŁO <br/> <input type="password" name="new2" class="input"/> <br/>
			<?php
			if(isset($_SESSION['E_Pas']))
			{	
				echo '<div class="error">'.$_SESSION['E_Pas'].'</div> <br>';
				unset($_SESSION['E_Pas']);
			}
			?>

   <input type="submit" value= "ZAPISZ" id="sub2"/>
	
</form>	

<?php } 

	?>	
	
	
	
</div>

<!DOCTYPE html>
<html>
<head>
	<title>WD</title>
</head>	
<body>
</body>
</html>

<style>

#t
	{
		width:300px;
		color: black;
		text-align:left;
		margin-left:10px
	}
	
#space2
	{
		position:relative;
		width:90%;
		height:100px;
	}
	
.f1
	{
		float:left;
		margin-bottom:10px;
	}
	
	
#f2
	{
	float:none;
	width:300px;
	margin-left:10px;
	}
	
.input
	{
	border: solid 2px;
	border-color:black;
	padding:5px;
	background: grey;
	color:white;
	}
	
.sub1
	{
	float:left;	
	}
#sub2, .sub1
	{
	
		margin-left:10px;
		margin-top:20px;
		width:180px;
		padding: 10px;
		background:#FF7F50;
		border:none;
		text-align:center;
	}
	
#sub2
	{
		margin-left:0px;
	}
#sub2:hover, .sub1:hover
	{
	background:#FF5F50;
	}
	
.blok, .blok2, .info, .info2
{
	position:relative;
   	width:220px;
	background:#75c0e0;
	padding: 20px;
	text-align:center;
	margin-top:10px;
	color:white;
	margin-left:10px;
	border: solid 1px;
	float:left;
}	

.info,.info2
	{
    background:grey;
	}
	
.blok2,.info2
	{
	width:120px;
	}

#space
	{
	position:relative;
	float:none;
	height: 20px;
	margin-top: 85px;
	}

#line
	{
	position:relative;
	height: 5px;
	margin-left:10px;
	background:blue;
	width:75%;
	margin-bottom:20px;
	float:left;
	}
.con
	{
		position:relative;
		
		<?php if(isset($_SESSION['student'])) 
					{?>	width:1100px; <?php }
			  else {?>width:740px;	<?php } ?>
	}
	
.error
{
color:red;
margin-top:10px;
margin-left:10px;
}

</style>

