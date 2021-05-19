<?php
    session_start();
    if ((isset($_SESSION['ifLoginP'])) && ($_SESSION['ifLoginP']==true))
	{
		header('Location: main_patient.php');
		exit();
	}
	
unset($_SESSION['id']);
unset($_SESSION['counter']);	
unset($_SESSION['student']);
unset($_SESSION['blad']);
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8"/>
        <title>E-Przychodnia</title>
        <meta name="description" content=""/>
        <meta nane="keywords" content=""/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>

        <link rel="stylesheet" href="css/style.css" type="text/css"/>
        <link rel="stylesheet" href="css/index.css" type="text/css"/>
        <link rel="stylesheet" href="css/fontello.css" type="text/css" />
        <link href="https://fonts.googleapis.com/css2?family=Merriweather+Sans:wght@300&display=swap" rel="stylesheet"> 
        

        
    </head>

    <body>

        <header>
            <p class="main_header">WIRTUALNY DZIEKANAT</p>
        </header>

       <main class="container">
           <div id="hello_page">

                    <a href="zaloguj_student_layout.php">
                        <div class="hello_img">
                            <i class="icon-graduation-cap"></i><br/>
                            <p class="hello_choice">STUDENT</p>
                        </div>
                    </a>

                    <a href="zaloguj_prowdz_layout.php">
                        <div class="hello_img">
                            <i class="icon-book-open"></i><br/>
                            <p class="hello_choice">PROWADZĄCY </p>
                        </div>
                    </a>

                     <a href="zaloguj_prac_layout.php">
                        <div class="hello_img">
                            <i class="icon-user"></i><br/>
                            <p class="hello_choice">PRACOWNIK </p>
                        </div>
                    </a>

		  
                        

                <div style="clear:both";></div>

		  <a href="offert.php">
                        <div class="offert">
                            OFERTA UCZELNI
                        </div>
                    </a>

           </div>
       </main>

       <footer>
            Wszystkie prawa zastrzeżone &copy; 2021 </br>
            Daniel Gabryś
       </footer>
    
       
    </body>
</html>

<style>
#page
{
    margin-left: auto;
    margin-right: auto;
	
    
}
</style>


