<?php

session_start();
require_once "connect.php";


unset($_SESSION['id'][0]);
unset($_SESSION['counter']);


$tab=NULL;
$faculty=NULL;

function tablica(&$tablica,$rezultat) //zapelniamy tablice danymi
{
  $licz=0; 
			
		while($row = mysqli_fetch_assoc($rezultat)) //specjalizacje
		{
			 
			$tablica[$licz]=$row;
			$licz++;
			  
		}  
}

function wypisz($ile0,$tab,$name)
{
    ?>
    <div id="bo"> </div>
    <div class="con"> 
       
    		     <div class="blokt">
    	        	PRZEDMIOT
    		     </div>
    		     
    		      <div class="blok2t">
    	        	SEM
    		     </div>
    		 
    		     <div class="blok2t">
    	        	ETC
    		     </div>
    		    
    		     <div class="blokt">
    	        	PROWADZACY
    		     </div>
    		     
       </div> 
       
    <?php
     $i=0;
	while($i<$ile0)
		{	
		 
		?>
	   <div id="bo"> </div>

       <div class="con"> 
       
    		     <div class="blok">
    	        	<?php echo $tab[$i][$name];?> 
    		     </div>
    		     
    		      <div class="blok2">
    	        	<?php echo $tab[$i]['semestr'];?> 
    		     </div>
    		 
    		     <div class="blok2">
    	        	<?php echo $tab[$i]['ETC'];?> 
    		     </div>
    		    
    		     <div class="blok">
    	        	<?php echo $tab[$i]['imie']." ".$tab[$i]['nazwisko'];?> 
    		     </div>
    		     
    		      <?php  
    	if($i!=0 && $i<$ile0-1 && $tab[$i]['semestr']!=$tab[$i+1]['semestr'])
    	{
    	    
        	
    	}
    	?>	     
       </div> 
      
        <?php	
		$i++;
		}
		
}




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

///////////////////////////////////////////////////////////////


if(isset($_GET['k']) )
{
	$ID = $_GET['k'];
}
else
{
    header('Location:offert.php');
    exit();
}

/*
$zap1="SELECT kierunki.ID,kierunki.kierunek,przedmioty.nazwa,kierunki_przedmioty.ETC,kierunki_przedmioty.semestr,prowadzacy.imie,prowadzacy.nazwisko 
FROM przedmioty inner join (kierunki inner join (prowadzacy inner join kierunki_przedmioty on prowadzacy.ID=kierunki_przedmioty.prowadzacy_id) 
on kierunki_przedmioty.kierunek_id) on przedmioty.ID=kierunki_przedmioty.przedmiot_id WHERE kierunki.ID=".$ID."
AND kierunki_przedmioty.kierunek_id=".$ID." order by kierunki_przedmioty.semestr";
*/

$zap1="
SELECT przedmioty.nazwa,kierunki_przedmioty.kierunek_id,kierunki_przedmioty.ETC,kierunki_przedmioty.semestr, 
kierunki_przedmioty_prowadzacy.prowadzacy_id,prowadzacy.imie,prowadzacy.nazwisko,kierunki.kierunek
from kierunki_przedmioty
inner join kierunki_przedmioty_prowadzacy on
kierunki_przedmioty_prowadzacy.kierunki_przedmioty_id=kierunki_przedmioty.ID
inner join przedmioty on przedmioty.ID=kierunki_przedmioty.przedmiot_id 
INNER join prowadzacy on prowadzacy.ID=kierunki_przedmioty_prowadzacy.prowadzacy_id 
inner join kierunki ON kierunki.ID=kierunki_przedmioty.kierunek_id 
where kierunki_przedmioty.kierunek_id='$ID' order by kierunki_przedmioty.semestr";

requires($faculty,$zap1,$servername,$username,$password,$database);

		
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8"/>
<body>
    
<a href="index.php">    
    <div id="back">
        STRONA GŁÓWNA
    </div> </a>
    
<a href="offert.php">    
    <div id="back">
        KIERUNKI
    </div> </a>  
    

    <div id="w">
        <?php echo $faculty[0]['kierunek'];?>
    </div
	
	
<?php 
wypisz(count($faculty),$faculty,"nazwa");
?>


</body>
</html>




<style>

.con
    {
    position:relative;
   	width:490px;
	background:#75c0e0;
	margin-top:5px;
	color:white;
	margin-left:auto;
	margin-right:auto;
    }
    
#back
{
 text-align:center;  
 width:20%;
 background:#75c0e0;
 padding: 20px;
 text-align:center;
 margin-left:auto;
 margin-right:auto;
 color:black;
 margin-bottom:5px;
}
  
  
.blok, .blokt
    {
    position:relative;
   	width:160px;
   	height:30px;
	background:grey;
	padding: 10px;
	text-align:center;
	color:white;
	float:left;
	border: solid 1px;
    }
    
.blok2, .blok2t
    {
    position:relative;
   	width:40px;
   	height:30px;
	background:grey;
	padding: 10px;
	text-align:center;
	color:white;
	float:left;
	border: solid 1px;
    }

.blokt, .blok2t
{
  background:blue;  
}
  
  
a:link
{
    text-decoration:none;
}

#back:hover
{
   background:#FF7F50; 
   
}



#w
{
    
font-family: Monospace; 
text-align:center;
 margin-left:auto;
 margin-right:auto;
 color:black;
 padding:20px;
 margin-top:10px;
   
}
 

 
</style>


