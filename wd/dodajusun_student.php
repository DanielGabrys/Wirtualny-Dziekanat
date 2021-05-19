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


$confirm=-1;
$students=NULL;
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



function wypisz($ile0,$tab,&$c)
{
    $j=0;
	while($j<$ile0)
		{	
		
		?>

		<div class="blok2"> 
	    	<?php echo $tab[$j]['nr_album']."</br></br>";?> 
		</div>

		<div class="blok"> 
	    	<?php echo $tab[$j]['imie']."</br></br>";?> 
		</div>

		<div class="blok"> 
	    	<?php echo $tab[$j]['nazwisko']."</br></br>";?> 
		</div>


		<div class="blok"> 
	    	<?php echo $tab[$j]['kierunek']."</br></br>";?> 
		</div>


		<form action="?i=<?php echo $tab[$j]['ID']?>" onSubmit="return confirm('Napewno chcesz usunąć studenta, zmiany będa nieodwracalne?')" method="POST" id="f2" >
	 		 <input type="submit" name="mark" class="input"  value="USUŃ"/> <br/> </input>
  			
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

	
	

$zap1="SELECT studenci.ID, studenci.nr_album, studenci.imie,studenci.nazwisko, kierunki.kierunek from studenci_kierunki 
inner join studenci on studenci.ID=studenci_kierunki.student_id 
inner join kierunki on kierunki.ID=studenci_kierunki.kierunek_id";

	
if(isset($_POST['student']) && $_POST['student']!="all")
	$zap1.=" and studenci.ID=".$_POST['student'];

$zap1.=" order by studenci.nr_album";



	
	
	
if(isset($_GET['i']) && isset($_POST['mark']))
{
	$student_id = $_GET['i'];
	$zap7="DELETE from studenci WHERE studenci.ID ='$student_id'";
	requires2($zap7,$servername,$username,$password,$database);
	


	
}
	
requires($students,$zap1,$servername,$username,$password,$database);


	
?> 


<div id="space"> </div> 
<div id="line"> </div> 



<div class="con"> 
	
	
	
	
<form action="" id="f1" method="POST">
	
 		
  <label for="cars">STUDENT NR ALBUMU:</label>	
  <select name="student" id="student">
	<option value="all">wszystkie</option>
	<?php
	  
	  for($i=0;$i<count($students);$i++)
	  { ?>
    <option value="<?php echo $students[$i]['ID'];?>">	<?php echo $students[$i]['nr_album']." ".$students[$i]['imie']." ".$students[$i]['nazwisko'];?> </option> <?php
	  } ?>
  </select>
	
	</br></br>
	<input type="submit" value="POKAŻ"> </input>
	
</form>


<form action="dodaj_student.php" class="f1" method="POST">
	  <input type="submit" value="DODAJ STUDENTA" class="sub1" name="edit2">
</form>
	
	
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
		 <?php echo "KIERUNEK"."</br></br>";?> 
	</div>

	<div class="info2"> 
		 USUN
	</div>	
	

<?php

	
	
	if($students==NULL)
		  $index=0;
	else
		  $index=count($students);
	
	wypisz($index,$students,$confirm);	
 
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
   	width:300px;
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
		width:112px;
		height:92px;
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
</style>




