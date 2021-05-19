<?php
session_start();
if(!isset($_SESSION['prowdz']))
{
header('location:index.php');
}



require_once "menu_panel.php";
menu();

?>

