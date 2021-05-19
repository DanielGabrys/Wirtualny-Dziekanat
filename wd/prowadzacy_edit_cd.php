<?php
require_once "connect.php";

session_start();

//jezeli nie jestesmy zalogowani
if(!isset($_SESSION['prac']))
{
	header('location:index.php');
}

$k=0;
if(isset($_GET['i']) )
{
	$p_id = $_GET['i'];
	
}
else
{
    header('Location:prowadzacy_edit.php');
    exit();
}


$faculty=NULL;
$marks=NULL;
$semesters=NULL;
$subjects=NULL;
$teacherss=NULL;

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
	
	header('Location:prowadzacy_edit.php');
    exit();
	
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



function wypisz($ile0,$tab,$teachers)
{
    $j=0;
	while($j<$ile0)
		{	
		
		?>
<form action="" id="f1" method="POST">
		
		<div class="blok"> 
	    	<?php echo $tab[$j]['kierunek'];?> 
		</div>

		<div class="blok"> 
	    	<?php echo $tab[$j]['nazwa'];?> 
		</div>
			
			<select name="prow" id="prow">
				<option value="<?php echo $tab[$j]['ID'];?>">	<?php echo $tab[$j]['imie']." ".$tab[$j]['nazwisko'];?> </option>
				<?php

				  for($i=0;$i<count($teachers);$i++)
				  { ?>
				<option class="op" value="<?php echo $teachers[$i]['ID'];?>">	<?php echo $teachers[$i]['imie']." ".$teachers[$i]['nazwisko'];?> </option> <?php
				  } ?>
			</select>


		<div class="blok2"> 
	    	<?php echo $tab[$j]['semestr'];?> 
		</div>

		<div class="blok2"> 
	    	<?php echo $tab[$j]['ETC'];?> 
		</div>


	 		 <input type="submit" name="mark" class="input"  value="ZAPISZ"/> <br/> </input>
  						
		</form>	


		<?php	  
		$j++;
		}
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

	
	
$zap1="SELECT kierunki_przedmioty_prowadzacy.ID,kierunki.kierunek,przedmioty.nazwa,przedmioty.ID As przedmiot_id,kierunki_przedmioty.kierunek_id,kierunki_przedmioty.ETC,kierunki_przedmioty.semestr, kierunki_przedmioty_prowadzacy.prowadzacy_id,prowadzacy.imie,prowadzacy.nazwisko
from kierunki_przedmioty inner join kierunki_przedmioty_prowadzacy on kierunki_przedmioty_prowadzacy.kierunki_przedmioty_id=kierunki_przedmioty.ID
inner join przedmioty on przedmioty.ID=kierunki_przedmioty.przedmiot_id 
inner join kierunki on kierunki.ID=kierunki_przedmioty.kierunek_id
inner join prowadzacy on prowadzacy.ID=kierunki_przedmioty_prowadzacy.prowadzacy_id
where kierunki_przedmioty_prowadzacy.ID='$p_id'";

	
$zap2="SELECT * FROM prowadzacy ORDER BY nazwisko,imie";
	

requires($teachers,$zap2,$servername,$username,$password,$database); //prowadzacy

	
if(isset($_POST['prow']))
{
	$id2 = $_POST['prow'];
	echo $id2;
	$zap3="UPDATE kierunki_przedmioty_prowadzacy SET prowadzacy_id ='$id2' WHERE kierunki_przedmioty_prowadzacy.ID ='$p_id'";
	requires2($zap3,$servername,$username,$password,$database);	
}
	
?> 


<div id="space"> </div> 
<div id="line"> </div> 


<div class="con"> 
	
	
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
		 <?php echo "ZAPISZ"."</br></br>";?> 
	</div>
	
	
<?php

	requires($marks,$zap1,$servername,$username,$password,$database);
	
	if($marks==NULL)
		  $index=0;
	else
		  $index=count($marks);
	
	wypisz($index,$marks,$teachers);	
 
	
?>

</div>


</body>
</html>

<style>
	
table, th, td 
{
  border: 1px solid black;
}
	
.blok, .blok2, .info, .info2, .input, #prow
{
	position:relative;
   	width:250px;
	background:#75c0e0;
	padding: 20px;
	padding-bottom:40px;
	padding-top:40px;
	text-align:center;
	margin-top:10px;
	color:white;
	margin-left:10px;
	border: solid 1px;
	float:left;
}	
	
#prow	
{
	height:100px;
	font-size:15px;
	margin:none;
	width:292px;
	background:#FF7F50;
	
}
	
select
	{
	text-align-last:center;
	margin-left:auto;
	margin-right:auto;
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
	
.blok2,.info2, .input
	{
	width:60px;
	}

.input
	{
		background:#FF7F50;
		height:100px;
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
		width:1300px;
		float:left;
		text-align:left;
	}
</style>





