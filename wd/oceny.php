<?php
require_once "connect.php";

session_start();

//jezeli nie jestesmy zalogowani
if(!isset($_SESSION['student']))
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


$faculty;
$marks=NULL;
$semesters;
$id=$_SESSION['student_id'];


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

		<div class="blok"> 
	    	<?php echo $tab[$j]['nazwa']."</br></br>";?> 
		</div>

		<div class="blok2"> 
	    	<?php echo $tab[$j]['ocena']."</br></br>";?> 
		</div>

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

require_once "menu_panel.php";
menu();
$zap1="SELECT kierunki.ID,kierunki.kierunek FROM studenci_kierunki inner join kierunki on kierunki.ID=studenci_kierunki.kierunek_id where studenci_kierunki.student_id=".$id;
/*
$zap2="SELECT studenci_oceny.*,przedmioty.nazwa, kierunki_przedmioty.ETC,kierunki_przedmioty.semestr From studenci_oceny 
inner join przedmioty on przedmioty.ID=studenci_oceny.przedmiot_id inner join kierunki_przedmioty on przedmioty.ID=kierunki_przedmioty.przedmiot_id 
where studenci_oceny.student_id=".$id." and studenci_oceny.kierunek_id="; //malo optymalne, ale dziala, do poprawy kiedyś
*/


$zap2="
SELECT studenci_oceny.*,przedmioty.nazwa,kierunki_przedmioty.kierunek_id,kierunki_przedmioty.ETC,kierunki_przedmioty.semestr 
from kierunki_przedmioty inner join kierunki_przedmioty_prowadzacy on kierunki_przedmioty_prowadzacy.kierunki_przedmioty_id=kierunki_przedmioty.ID
inner join przedmioty on przedmioty.ID=kierunki_przedmioty.przedmiot_id 
inner join studenci_oceny ON studenci_oceny.kier_przed_prow=kierunki_przedmioty_prowadzacy.ID 
where studenci_oceny.student_id='$id' and kierunki_przedmioty.kierunek_id=";


$zap3="SELECT DISTINCT semestr from kierunki_przedmioty";

requires($semesters,$zap3,$servername,$username,$password,$database);
requires($faculty,$zap1,$servername,$username,$password,$database);

for($i=0;$i<count($faculty);$i++)
{
	wypisz2($faculty,$i,$faculty[$i]['kierunek']);
		
}
	
?> 
<div id="space"> </div> 





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
	
  <input type="submit" value="POKAŻ">
</form>


<div class="con"> 
	
	

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
	
	
if(!isset($_GET['k']))
{
	$zap2.=$faculty[0]['ID']." order by kierunki_przedmioty.semestr";
	
	//echo $zap2;
	requires($marks,$zap2,$servername,$username,$password,$database);
	wypisz(count($marks),$marks);
}
else if(correct($k,$faculty)==1)
{
	
	$zap2.=$k;
	if(isset($_POST['semestr']) && $_POST['semestr']!="all")
			 $zap2.=" and kierunki_przedmioty.semestr=".$_POST['semestr'];
	//echo $zap2;
	requires($marks,$zap2,$servername,$username,$password,$database);
	
	if($marks==NULL)
		  $index=0;
	else
		  $index=count($marks);
	
	wypisz($index,$marks);	
}

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
	
table, th, td 
{
  border: 1px solid black;
}
	
.blok, .blok2, .info, .info2
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
   	width:25%;
	background:#FF7F50;
	padding: 20px;
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
	width:70px;
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
		width:900px;
	}
	
#f1
	{
		width:900px;
		float:left;
		margin-left:10px;
		
	}
</style>


