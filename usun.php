<?php
session_start();
if(!empty($_SESSION['email']) && $_SESSION['HTTP_USER_AGENT']==md5($_SERVER['HTTP_USER_AGENT']))
{
if (@include_once("db_config.php"))
{
$baza = new mysqli($host_db, $login_db, $haslo_db, $dbname_db);
$baza->set_charset("utf8");
$id_zapisu=$_POST['id_zapisu'];
$sql_id_u ='SELECT Id_uzytkownika from uzytkownicy where `email`="'.$_SESSION['email'].'";';
$wynik = $baza->query($sql_id_u);
$wiersz = $wynik->fetch_array();
$id = $wiersz['Id_uzytkownika'];
$sql_usun='DELETE FROM `uczniowie_zapisy` WHERE `uczniowie_zapisy`.`Id_zapisy` = '.$id_zapisu.' and Id_uzytkownika='.$id.' and Id_grupy is null;';
$sql_usun_dni='DELETE FROM `dni_uczniowie`  WHERE `Id_zapisy` = '.$id_zapisu.';';
$sql_id='SELECT Id_uzytkownika from uczniowie_zapisy WHERE Id_zapisy='.$id_zapisu.' and Id_uzytkownika='.$id.';';
if($baza->query($sql_id)->num_rows==1)
{
    $baza->query($sql_usun_dni);
    $baza->query($sql_usun);
    header("location:index.php");
}
}
    else
    {
        header("location:index.php"); 
    }
}
else
{
   header("location:index.php"); 
}
?>