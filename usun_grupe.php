<?php
session_start();
if(!empty($_SESSION['email']) && $_SESSION['HTTP_USER_AGENT']==md5($_SERVER['HTTP_USER_AGENT']))
{
if (@include_once("db_config.php"))
    {
    $baza = new mysqli($host_db, $login_db, $haslo_db, $dbname_db);
        $baza->set_charset("utf8");
        $sql_logowanie_admin = 'SELECT * FROM `administratorzy` WHERE `Email` LIKE "'.$_SESSION['email'].'"';
        $wynik = $baza->query($sql_logowanie_admin);
        if($wynik->num_rows != 1)
        {
            header("location:index.php");
        }
        else
        {
            $id_grupy=$_POST['id_grupy'];
            $sql_id_NULL = 'UPDATE `uczniowie_zapisy` SET `Id_grupy` = NULL WHERE `id_grupy`='.$id_grupy.';'; 
            $wynik = $baza->query($sql_id_NULL);
            $sql_usun='DELETE FROM `grupy` WHERE Id_grupy='.$id_grupy.';';
            $baza->query($sql_usun);
            header("location:panel_administracyjny.php?menu=dodaj_grupe");
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