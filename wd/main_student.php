<?php
session_start();
if(!isset($_SESSION['student']))
{
header('location:index.php');
}

unset($_SESSION['nowy_mail']);


require_once "menu_panel.php";
menu();

?>

