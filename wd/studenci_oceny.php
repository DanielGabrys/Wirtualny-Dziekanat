<?php
require_once "connect.php";

session_start();

//jezeli nie jestesmy zalogowani
if(!isset($_SESSION['prowdz']))
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
$students=NULL;
$o;
$id=$_SESSION['prowdz_id'];


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
		 if(!$tab[$j]['ocena'])
			{
			$tab[$j]['ocena']="-";
			}
		?>

		<div class="blok2"> 
	    	<?php echo $tab[$j]['nr_album']."</br></br>";?> 
		</div>

		
		<div class="blok"> 
	    	<?php echo $tab[$j]['kierunek']."</br></br>";?> 
		</div>

		<div class="blok"> 
	    	<?php echo $tab[$j]['nazwa']."</br></br>";?> 
		</div>
		
		<form action="?i=<?php echo $tab[$j]['ID']?>" id="f2" method="POST" id="f2">
	 		 <input type="text" name="mark" class="input"  value="<?php echo $tab[$j]['ocena'] ?>"/> <br/> </input>
  			
		</form>	
		
	   <!--
		<div class="blok2"> 
	    	<?php echo $tab[$j]['ocena']."</br></br>";?> 
		</div>
        -->

		<div class="blok2"> 
	    	<?php echo $tab[$j]['semestr']."</br></br>";?> 
		</div>

		<div class="blok2"> 
	    	<?php echo $tab[$j]['ETC']."</br></br>";?> 
		</div>

	

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

	
	

$zap1="SELECT kierunki.ID,kierunki.kierunek FROM studenci_kierunki inner join kierunki on kierunki.ID=studenci_kierunki.kierunek_id where studenci_kierunki.student_id=".$id;
/*
$zap2="SELECT studenci_oceny.*,przedmioty.nazwa, kierunki_przedmioty.ETC,kierunki_przedmioty.semestr From studenci_oceny 
inner join przedmioty on przedmioty.ID=studenci_oceny.przedmiot_id inner join kierunki_przedmioty on przedmioty.ID=kierunki_przedmioty.przedmiot_id 
where studenci_oceny.student_id=".$id." and studenci_oceny.kierunek_id="; //malo optymalne, ale dziala, do poprawy kiedyś
*/


$zap2="
SELECT studenci.nr_album,studenci.imie,studenci.nazwisko, kierunki.kierunek,studenci_oceny.*,przedmioty.nazwa,kierunki_przedmioty.kierunek_id,kierunki_przedmioty.ETC,kierunki_przedmioty.semestr, kierunki_przedmioty_prowadzacy.prowadzacy_id
from kierunki_przedmioty inner join kierunki_przedmioty_prowadzacy on kierunki_przedmioty_prowadzacy.kierunki_przedmioty_id=kierunki_przedmioty.ID
inner join przedmioty on przedmioty.ID=kierunki_przedmioty.przedmiot_id 
inner join studenci_oceny ON studenci_oceny.kier_przed_prow=kierunki_przedmioty_prowadzacy.ID 
inner join studenci on studenci.ID=studenci_oceny.student_id
inner join kierunki on kierunki.ID=kierunki_przedmioty.kierunek_id
where kierunki_przedmioty_prowadzacy.prowadzacy_id='$id'";

	
if(isset($_POST['semestr']) && $_POST['semestr']!="all")
	$zap2.=" and kierunki_przedmioty.semestr=".$_POST['semestr'];

if(isset($_POST['przedmiot']) && $_POST['przedmiot']!="all")
	$zap2.=" and przedmioty.ID=".$_POST['przedmiot'];
	
if(isset($_POST['kierunek']) && $_POST['kierunek']!="all")
	$zap2.=" and kierunki.ID=".$_POST['kierunek'];
	
$zap2.=" order by studenci.nr_album";




$zap3="SELECT DISTINCT semestr from kierunki_przedmioty";
	
$zap4="SELECT DISTINCT przedmioty.nazwa, przedmioty.ID
from kierunki_przedmioty inner join kierunki_przedmioty_prowadzacy on kierunki_przedmioty_prowadzacy.kierunki_przedmioty_id=kierunki_przedmioty.ID
inner join przedmioty on przedmioty.ID=kierunki_przedmioty.przedmiot_id 
inner join kierunki on kierunki.ID=kierunki_przedmioty.kierunek_id
where kierunki_przedmioty_prowadzacy.prowadzacy_id='$id' order by nazwa";
	
$zap5="select * from studenci order by nr_album";

$zap6="SELECT DISTINCT kierunki.ID,kierunki.kierunek from kierunki_przedmioty
inner join kierunki_przedmioty_prowadzacy on kierunki_przedmioty_prowadzacy.kierunki_przedmioty_id=kierunki_przedmioty.ID 
inner join przedmioty on przedmioty.ID=kierunki_przedmioty.przedmiot_id inner
join kierunki on kierunki.ID=kierunki_przedmioty.kierunek_id where kierunki_przedmioty_prowadzacy.prowadzacy_id='$id'";

	
	
	
if(isset($_GET['i']) && isset($_POST['mark']))
{
	$ocena_id = $_GET['i'];
	$ocena=$_POST['mark'];
	$zap7="UPDATE studenci_oceny SET ocena ='$ocena' WHERE studenci_oceny.ID ='$ocena_id'";
	requires2($zap7,$servername,$username,$password,$database);
	
	unset($_GET['i']);


	
}
	

requires($semesters,$zap3,$servername,$username,$password,$database);
requires($subjects,$zap4,$servername,$username,$password,$database);
requires($students,$zap5,$servername,$username,$password,$database);
requires($faculty,$zap6,$servername,$username,$password,$database);

	
?> 


<div id="space"> </div> 
<div id="line"> </div> 



<div class="con"> 
	
	
	
	
<form action="" id="f1" method="POST">
	
  <label for="cars">SEMESTR:</label>
  <select name="semestr" id="semestr">
	<option value="all">wszystkie</option>
	<?php
	  
	  for($i=0;$i<count($semesters);$i++)
	  { ?>
    <option value="<?php echo $semesters[$i]['semestr'];?>">	<?php echo $semesters[$i]['semestr'];?> </option> <?php
	  } ?>
</select>
	
	
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
	
		
  <label for="cars">STUDENT NR ALBUMU:</label>	
  <select name="student" id="student">
	<option value="all">wszystkie</option>
	<?php
	  
	  for($i=0;$i<count($students);$i++)
	  { ?>
    <option value="<?php echo $students[$i]['ID'];?>">	<?php echo $students[$i]['nr_album'];?> </option> <?php
	  } ?>
  </select>
	
	</br></br>
	<input type="submit" value="POKAŻ"> </input>
	
</form>
	
	
	<div class="info2"> 
		 <?php echo "NR ALBUMU"."</br></br>";?> 
	</div>	
	
	<div class="info"> 
		 <?php echo "KIERUNEK"."</br></br>";?> 
	</div>
	
	<div class="info"> 
		 <?php echo "PRZEDMIOT"."</br></br>";?> 
	</div>	

	<div class="info2"> 
		 <?php echo "OCENA"."</br></br>";?> 
	</div>	
	
	<div class="info2"> 
		 <?php echo "SEMESTR"."</br></br>";?> 
	</div>	
	
	<div class="info2"> 
		 <?php echo "ETC"."</br></br>";?> 
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
   	width:320px;
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
	width:70px;
	}

.input
	{
		background:#FF7F50;
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
		width:1300px;
	}
	
#f1
	{
		width:100%;
		float:left;
		margin-left:10px;
		text-align:left;
	}
</style>



