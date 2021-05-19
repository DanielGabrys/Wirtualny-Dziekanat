<?php
require_once "connect.php";

session_start();

//jezeli nie jestesmy zalogowani
if(!isset($_SESSION['prac']))
{
	header('location:index.php');
}

$k=0;
if(isset($_GET['k']) )
{
	$k = $_GET['k'];
	
}
else
{
    //header('Location:zaloguj_doctor_layout.php');
    //exit();
}


$faculty=NULL;
$marks=NULL;
$semesters=NULL;
$subjects=NULL;
$teachers=NULL;

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


function tablica(&$tablica,$rezultat) //zapelniamy tablice danymi
{
  $licz=0; 
			
		while($row = mysqli_fetch_assoc($rezultat)) //specjalizacje
		{
			 
			$tablica[$licz]=$row;
			$licz++;
			  
		}  
}



function wypisz($ile0,$tab)
{
    $j=0;
	while($j<$ile0)
		{	
		
		?>

		
		<div class="blok"> 
	    	<?php echo $tab[$j]['kierunek']."</br></br>";?> 
		</div>

		<div class="blok"> 
	    	<?php echo $tab[$j]['nazwa']."</br></br>";?> 
		</div>
			

		<div class="blok"> 
	    	<?php echo $tab[$j]['imie']." ".$tab[$j]['nazwisko']."</br></br>";?> 
		</div>


		<div class="blok2"> 
	    	<?php echo $tab[$j]['semestr']."</br></br>";?> 
		</div>

		<div class="blok2"> 
	    	<?php echo $tab[$j]['ETC']."</br></br>";?> 
		</div>

	
		<form action="prowadzacy_edit_cd.php?i=<?php echo $tab[$j]['ID']?>" id="f2" method="POST" id="f2">
	 		 <input type="submit" name="mark" class="input"  value="EDYTUJ"/> <br/> </input>
  			
		</form>	


		<?php	  
		$j++;
		}
}

function wypisz2($tab,$j,$name)
{
    ?>
	<div class="none"> </div>
	<a href="?k=<?php echo $tab[$j]['ID']?>">	
	<div class="blok0">
		<?php echo $name."</br></br>"; ?>
	</div> </a>

	<?php	  
		
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

	

$zap1="SELECT kierunki.ID,kierunki.kierunek FROM studenci_kierunki inner join kierunki on kierunki.ID=studenci_kierunki.kierunek_id";
	

$przed="all";
$kier="all";
$prow="all";

if(isset($_POST['przedmiot']))
	$przed=$_POST['przedmiot'];
	
if(isset($_POST['kierunek']))
	$kier=$_POST['kierunek'];
	
if(isset($_POST['prow']))
	$prow=$_POST['prow'];

	
$zap2="CALL prowadzacy_lista('$prow', '$przed', '$kier')";


$zap3="SELECT DISTINCT semestr from kierunki_przedmioty";
	
$zap4="SELECT * FROM przedmioty ORDER BY nazwa";
	
$zap5="SELECT * FROM prowadzacy ORDER BY nazwisko,imie";
	
$zap6="SELECT * FROM kierunki ORDER BY kierunek";

	
	
	
if(isset($_GET['i']) && isset($_POST['mark']))
{
	$ocena_id = $_GET['i'];
	$ocena=$_POST['mark'];
	$zap7="UPDATE studenci_oceny SET ocena ='$ocena' WHERE studenci_oceny.ID ='$ocena_id'";
	//requires2($zap7,$servername,$username,$password,$database);
	
	unset($_GET['i']);


	
}
	

requires($semesters,$zap3,$servername,$username,$password,$database); //semestry
requires($subjects,$zap4,$servername,$username,$password,$database); // przedmioty
requires($teachers,$zap5,$servername,$username,$password,$database); //prowadzacy
requires($faculty,$zap6,$servername,$username,$password,$database); //kierunki

	
?> 


<div id="space"> </div> 
<div id="line"> </div> 



<div class="con"> 
	
	
	
	
<form action="" id="f1" method="POST">
	
 
	
  <label for="cars">PRZEDMIOT:</label>	
  <select name="przedmiot" id="przedmiot">
	<option value="all">wszystkie</option>
	<?php
	  
	  for($i=0;$i<count($subjects);$i++)
	  { ?>
    <option value="<?php echo $subjects[$i]['ID'];?>">	<?php echo $subjects[$i]['nazwa'];?> </option> <?php
	  } ?>
  </select>
	
	
  <label for="cars">KIERUNEK:</label>
  <select name="kierunek" id="kierunek">
	<option value="all">wszystkie</option>
	<?php
	  
	  for($i=0;$i<count($faculty);$i++)
	  { ?>
    <option value="<?php echo $faculty[$i]['ID'];?>">	<?php echo $faculty[$i]['kierunek'];?> </option> <?php
	  } ?>
	</select>
	
	
	
	<label for="cars">PROWADZACY:</label>
  <select name="prow" id="prow">
	<option value="all">wszystkie</option>
	<?php
	  
	  for($i=0;$i<count($teachers);$i++)
	  { ?>
    <option value="<?php echo $teachers[$i]['ID'];?>">	<?php echo $teachers[$i]['imie']." ".$teachers[$i]['nazwisko'];?> </option> <?php
	  } ?>
	</select>
	
	
	
	 <label for="cars">SEMESTR:</label>
  <select name="semestr" id="semestr">
	<option value="all">wszystkie</option>
	<?php
	  
	  for($i=0;$i<count($semesters);$i++)
	  { ?>
    <option value="<?php echo $semesters[$i]['semestr'];?>">	<?php echo $semesters[$i]['semestr'];?> </option> <?php
	  } ?>
	</select>
	
	
	</br></br>
	<input type="submit" value="POKAŻ"> </input>
	
</form>
	
	
	<div class="info"> 
		 <?php echo "KIERUNEK"."</br></br>";?> 
	</div>
	
	<div class="info"> 
		 <?php echo "PRZEDMIOT"."</br></br>";?> 
	</div>	

	<div class="info"> 
		 <?php echo "PROWADZACY"."</br></br>";?> 
	</div>	
	
	<div class="info2"> 
		 <?php echo "SEMESTR"."</br></br>";?> 
	</div>	
	
	<div class="info2"> 
		 <?php echo "ETC"."</br></br>";?> 
	</div>

	
	<div class="info2"> 
		 <?php echo "EDYTUJ"."</br></br>";?> 
	</div>
	
	
<?php

	requires($marks,$zap2,$servername,$username,$password,$database);
	
	if($marks==NULL)
		  $index=0;
	else
		  $index=count($marks);
	
	wypisz($index,$marks);	
   // echo $zap2;
?>

</div>


</body>
</html>

<style>
	
table, th, td 
{
  border: 1px solid black;
}
	
.blok, .blok2, .info, .info2, .input
{
	position:relative;
   	width:250px;
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

.info,.info2
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
	
.blok2,.info2, .input
	{
	width:60px;
	}

.input
	{
		background:#FF7F50;
		height:92px;
		width:102px;
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
	width:90%;
	margin-bottom:20px;
	float:left;
	}
.con
	{
		position:relative;
		width:1300px;
	}
	
#f1
	{
		width:1200px;
		float:left;
		margin-left:10px;
		text-align:left;
	}
</style>




