<?php

session_start();
require_once "connect.php";

if(!isset($_SESSION['id']))
{
    $_SESSION['id'][0]=0;
}

if(!isset($_SESSION['counter']))
{
    $_SESSION['counter']=0;
}

$tab=NULL;
$depart=NULL;
$fac=NULL;

function tablica(&$tablica,$rezultat) //zapelniamy tablice danymi
{
  $licz=0; 
			
		while($row = mysqli_fetch_assoc($rezultat)) //specjalizacje
		{
			 
			$tablica[$licz]=$row;
			$licz++;
			  
		}  
}

function wyszukaj($n)
{
    $k=-1;
    for($i=0;$i<$_SESSION['counter'];$i++)
    {
        if($_SESSION['id'][$i]==$n)
        {
        $k=$i;
        break;
        }
    }
    
    if($k>-1)
    {
        for($i=$k;$i<$_SESSION['counter']-1;$i++)
        {
            $_SESSION['id'][$i]=$_SESSION['id'][$i+1];
        }
        //$tab[count($tab)]=NULL;
        $_SESSION['counter']--;
    }

     return $k;
}

function wypisz_k($t,$id2)
{
	if($t==NULL)
		$number2=0;
	else
		$number2=count($t);
    for($k=0;$k<$number2;$k++)
		{	
		//echo $t[$k]['ID'].' '.$t[$k]['wydzial_id'].'</br>';
		if($t[$k]['wydzial_id']==$id2)
		{
		    
		    
		    ?>
		       <a href="faculty.php?k=<?php echo $t[$k]['ID']?>">
	        	<div class="blok2">
		         <div class="text2">
	        	<?php	echo $t[$k]['kierunek']."</br></br>";?> 
		         </div>
	        	</div> </a>
		
		<?php
		}
		}
}

function wypisz($ile0,$tab,$name,$id,$fac)
{
    $i=0;
	while($i<$ile0)
		{	
		 
		?>
		<div class="b0"> </div>
		<a href="?v=<?php echo $tab[$i]['ID']?>">
		<div class="blok">
		    <div class="text">
	    	<?php echo $tab[$i][$name]."</br></br>";?> 
		    </div>
		    
		</div> </a>
		
		
		
		<?php
			  for($j=0;$j<$_SESSION['counter'];$j++)
                {
                  if($_SESSION['id'][$j]==$tab[$i]['ID'])
                    {
                    wypisz_k($fac,$tab[$i]['ID']);
                    }
                }
			
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

$ID=0;
if(isset($_GET['v']) )
{
	$ID = $_GET['v'];
	
}
else
{
    //header('Location:zaloguj_doctor_layout.php');
    //exit();
}


$zap1="SELECT *FROM  wydzialy";
$zap2="SELECT * FROM kierunki";

	
	
requires($depart,$zap1,$servername,$username,$password,$database);

if($ID>0)
{
    requires($fac,$zap2,$servername,$username,$password,$database);
    $w=wyszukaj($ID);
    
    if($w==-1)
    {
         $_SESSION['id'][$_SESSION['counter']]=$ID;
         $_SESSION['counter']++;
    }
    
  //  for($i=0;$i<$_SESSION['counter'];$i++)
     //   echo $_SESSION['id'][$i];
    
      //  echo '</br>'.$_SESSION['counter'];
}

		
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8"/>
<body>
    
<a href="index.php">    
    <div id="back">
        STRONA GŁÓWNA
    </div> </a>

    <div id="w">
        WYDZIAŁY
    </div
	
	
<?php 
if($depart==null)
	$nubmer=0;
else
	$number=count($depart);
wypisz($number,$depart,"wydzial",$ID,$fac);
?>


</body>
</html>




<style>

.blok
    {
   	width:50%;
	background:#75c0e0;
	padding: 20px;
	text-align:center;
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
}
  
  
.blok2
    {
   	width:50%;
	background:grey;
	padding: 20px;
	text-align:center;
	color:white;
	margin-left:auto;
	margin-right:auto;
    }
    
  
  
a:link
{
    text-decoration:none;
}

#back:hover
{
   background:#FF7F50; 
}


.blok:hover
{
   background-color: #FF7F50; 
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


