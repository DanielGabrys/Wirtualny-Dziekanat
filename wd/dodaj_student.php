<?php
require_once "connect.php";

session_start();

//jezeli nie jestesmy zalogowani
if(!isset($_SESSION['prac']))
{
	header('location:index.php');
}


$students=NULL;
$faculty=NULL;
$id=$_SESSION['prac_id'];


//funkcje

function requires(&$tab,$zapyt,$s,$u,$p,$d)
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
    		
           	$ile=$rez->num_rows;
			//echo $ile."</br>";
    		tablica($tab,$rez);
    		$con->close();
		}
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}  
	
}


function requires2($zapyt,$s,$u,$p,$d)
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
    
    		$con->close();
			
		}
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}  
	
}



function transaction($s,$u,$p,$d)
{
	$fac=$_POST['kierunek'];
	
	if(isset($_POST['index']))
	{
		$name=$_POST['name'];
		$surname=$_POST['surname'];
		$phone=$_POST['phone'];
		$index=$_POST['index'];
		$mail=$_POST['mail'];
		$password=$_POST['pass'];
		$haslo_hash=password_hash($password,PASSWORD_DEFAULT);
	}
	else
		$index=$_POST['std'];
	
	$today = date("Y");
	
	/*
	$query1="INSERT INTO studenci (ID, imie, nazwisko, telefon, nr_album, haslo, mail) VALUES(NULLL, '$name', '$surname', '$phone', '$index', '$password', '$mail')";
	
	$query2="INSERT INTO `studenci_kierunki(ID, kierunek_id, student_id, semestr, rok_rozpoczęcia) VALUES(NULLL, '$fac', '$surname', '$iden', '1', '$today')";
	
	$query3="select kierunki_przedmioty_prowadzacy.ID, kierunki_przedmioty.przedmiot_id,kierunki_przedmioty.przedmiot_id,studenci_kierunki.student_id 
	from kierunki_przedmioty
	inner join studenci_kierunki on studenci_kierunki.kierunek_id=kierunki_przedmioty.kierunek_id 
	inner join kierunki_przedmioty_prowadzacy on kierunki_przedmioty_prowadzacy.kierunki_przedmioty_id=kierunki_przedmioty.ID
	where studenci_kierunki.student_id =????";
	
	$query4="$con->query("INSERT INTO studenci_oceny(ID, student_id, ocena, kier_przed_prow) VALUES(NULL, '$iden', NULL, '$m')");
	
	*/
	
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{
	    
		$con= new mysqli($s,$u,$p,$d);
		$con->autocommit(false);
		
		$errors=array();
		
		
		$exist=$con->query("SELECT* FROM studenci WHERE nr_album='$index'" );
		if(!$exist) 
		{
			array_push($errors,'problem');
			throw new Exception($con->error);
		}
    	$ile=$exist->num_rows;
		if($ile>0)
		{
			$row = mysqli_fetch_assoc($exist);
			$iden=$row['ID']; //wartosc id nowego studenta
		}
		
	if($ile==1 && isset($_POST['index'])) // jesli dodajemy nowego studenta ale istnieje juz takio danym indeksie
	   {
		   header('Location:dodaj_student.php');
		   $_SESSION['blad']="blad, istnieje juz student o danym indeksie";
		   exit();
	   }
		
		
		
   if($ile==0 && isset($_POST['index'])) // wybralismy opcje dodajemy nowego ale sprawdzamy czy nie dodajemy studenta o istniejacym juz indeksie
   {	
		$a=$con->query("INSERT INTO studenci (ID, imie, nazwisko, telefon, nr_album, haslo, mail) VALUES(NULL, '$name', '$surname', '$phone', '$index', '$haslo_hash', '$mail')");
    	if(!$a) 
		{
			array_push($errors,'problem');
			throw new Exception($con->error);
		}
	    $iden=$con->insert_id; //wartosc id nowego studenta
   } 

	  
	  
	
		$b=$con->query("INSERT INTO studenci_kierunki(ID, kierunek_id, student_id, semestr, rok_rozpoczecia) VALUES(NULL, '$fac', '$iden', '1', '$today')");
    	if(!$b) 
		{
			array_push($errors,'problem');
			throw new Exception($con->error);
		}
		
		$c=$con->query("select kierunki_przedmioty_prowadzacy.ID, kierunki_przedmioty.przedmiot_id,kierunki_przedmioty.przedmiot_id,studenci_kierunki.student_id 
		from kierunki_przedmioty
		inner join studenci_kierunki on studenci_kierunki.kierunek_id=kierunki_przedmioty.kierunek_id 
		inner join kierunki_przedmioty_prowadzacy on kierunki_przedmioty_prowadzacy.kierunki_przedmioty_id=kierunki_przedmioty.ID
		where studenci_kierunki.student_id ='$iden'");
    	if(!$c) 
		{
			array_push($errors,'problem');
			throw new Exception($con->error);
		}
		else
		{
			$marks=NULL;
			tablica($marks,$c);	
			
			for($i=0;$i<count($marks);$i++)
			{
				$m=$marks[$i]['ID'];
				$d=$con->query("INSERT INTO studenci_oceny(ID, student_id, ocena, kier_przed_prow) VALUES(NULL, '$iden', NULL, '$m')");
				
			if(!$d) 
			{
				array_push($errors,'problem');
				throw new Exception($con->error);
			}				
				
			}
			
			
		}
    
		
		if(!empty($errors))
			$con->rollback();
			
		$con->commit();
    	$con->close();
			
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Blad serwera </span>';
		echo '<br/>'.$e;
	}  
	
	header('Location:dodajusun_student.php');
	
}





function tablica(&$tablica,$rezultat) //zapelniamy tablice danymi
{
  $licz=0; 
			
		while($row = mysqli_fetch_assoc($rezultat)) //specjalizacje
		{
			 
			$tablica[$licz]=$row;
			$licz++;
			  
		}  
}



function wypisz($faculty,$students)
{
   
		
?>

		
<div class="con"> 
	
	
	<?php
		
		if(isset($_SESSION['blad']))
		{
			echo $_SESSION['blad'].'</br></br>';
			unset($_SESSION['blad']);
		}
	?>
	
	<form action="?i=2" method="POST" id="f0" id="f0" >
	<div id="box">
		  <input type="checkbox" id="old" name="old"> </input>
		  <label for="old">Student istnieje w bazie</label>
		 <input type="submit" name="mark"  value="OK"/>  </input>
	</div>
	</form>
	
	
	<form action="?i=1" method="POST" id="f2" >
		 
	<div name="kier" > 
	   <select name="kierunek" id="kierunek">
			<?php

			  for($i=0;$i<count($faculty);$i++)
			  { ?>
			<option value="<?php echo $faculty[$i]['ID'];?>">	<?php echo $faculty[$i]['kierunek'];?> </option> <?php
			  } ?>
		</select>
	</div>	
		
		
	<?php if(isset($_POST['old'])) { ?>
		
	<div name="kier" > 
	   <select name="std" id="kierunek">
			<?php

			  for($i=0;$i<count($students);$i++)
			  { ?>
			<option value="<?php echo $students[$i]['nr_album'];?>">	<?php echo $students[$i]['nr_album']." ".$students[$i]['imie']." ".$students[$i]['nazwisko'];;?> </option> <?php
			  } ?>
		</select>
	</div>	
	
	 <input type="submit" name="mark2" class="input"  value="DODAJ"/>  </input>	
		
	<?php }
		
	else {	
		?>
				
	
	<div class="info2"> 
		 <?php echo "NR ALBUMU"."</br></br>";?> 
	</div>	

	<div class="info"> 
		 <?php echo "IMIE"."</br></br>";?> 
	</div>	

	<div class="info"> 
		 <?php echo "NAZWISKO"."</br></br>";?> 
	</div>	

	<div class="info"> 
		 <?php echo "TELEFON"."</br></br>";?> 
	</div>

	<div class="info"> 
		 <?php echo "MAIL"."</br></br>";?> 
	</div>
	
	<div class="info"> 
		 <?php echo "HASŁO"."</br></br>";?> 
	</div>

	<div class="info2"> 
		 <?php echo "POTWIERDŹ"."</br></br>";?> 
	</div>	


		 
		
	    	<input type="text" name="index" class="input3"  /> <br/> </input>
		

		
	    	<input type="text" name="name" class="input2"  /> <br/> </input>
	

		
	    	<input type="text" name="surname" class="input2"  /> <br/> </input>
	

		
	    	<input type="text" name="phone" class="input2"  /> <br/> </input>


		
	    	<input type="text" name="mail" class="input2"  /> <br/> </input>
		


	
	    	<input type="text" name="pass" class="input2"  /> <br/> </input>
		

	 		 <input type="submit" name="mark" class="input"  value="DODAJ"/>  </input>
  			
		</form>	
	
	<?php	
	}
}



function correct($a,$tab)
{
	for($i=0;$i<count($tab);$i++)
	{
		if($tab[$i]['ID']==$a)
			return 1;
	}
	return 0;
}

/////////////////////////////////////// main /////////////////////////////////////////////

?>
<!DOCTYPE html>
<html>
<head>
	<title>WD</title>
</head>

	
<body>

<?php

require_once "menu_panel.php";
menu();

$zap1="SELECT * FROM studenci order by nr_album";
$zap2="SELECT * FROM kierunki order by kierunek";

	
if(isset($_POST['index']) || isset($_POST['std']))
{
	

	transaction($servername,$username,$password,$database);
		
}
		
requires($faculty,$zap2,$servername,$username,$password,$database);
requires($students,$zap1,$servername,$username,$password,$database);


	
?> 


<div id="space"> </div> 
<div id="line"> </div> 


<?php

	
	
	if($students==NULL)
		  $index=0;
	else
		  $index=count($students);
	
	wypisz($faculty,$students);	
 
?>

	
	
</div>


</body>
</html>

<style>
	
table, th, td 
{
  border: 1px solid black;
}
	
.blok, .blok2, .info, .info2, .input, .input2, .input3
{
	position:relative;
   	width:160px;
	background:#75c0e0;
	padding: 20px;
	text-align:center;
	margin-top:10px;
	color:white;
	margin-left:10px;
	border: solid 1px;
	float:left;
	height:50px;
}	

.info, .info2
	{
    background:grey;
	}
	
	
.space2
	{
		margin-top:10px;
		position:relative;
		width:50%;
		padding: 20px;


	}
	
.blok0
{
   	width:22%;
	background:#FF7F50;
	padding: 25px;
	text-align:center;
	margin-top:5px;
	color:white;
	margin-left:10px;
	margin-bottom: 50px;
	border: solid 1px;
	float:left;
	position:relative;
}	
	
.blok2,.info2
	{
	width:80px;
	}

.input
	{
		background:#FF7F50;
		width:122px;
		height:92px;
		margin-top:1px;
	
	}
	
.input3
	{
		width:80px;
		height:50px;
	}	
	
	
.input:hover
	{
		background:#FF5F50;
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
	width:100%;
	margin-bottom:20px;
	float:left;
	}
.con
	{
		position:relative;
		width:1400px;
	}
	
#f1
	{
		width:100%;
		float:left;
		margin-left:10px;
		text-align:left;
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
	
#kierunek
	{
	
		width:250px;
		height:50px;
		background: #FF7F50;
		float:none;
		margin-left:10px;
		margin-bottom:10px;
	}
#kier
	{
		float:none;
		width:100%;
		margin-left:10px;
	}
	
#box
	{
		float:none;
		margin-left:20px;
		margin-top:10px;
		margin-bottom:10px;
		width:250px;
	
		
	}
	
</style>




