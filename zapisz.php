<?php
session_start();
if(!empty($_SESSION['email']) && $_SESSION['HTTP_USER_AGENT']==md5($_SERVER['HTTP_USER_AGENT']))
    {
if (@$_SESSION['kurs'])
{
    if (@include_once("db_config.php"))
    {
        $baza = new mysqli($host_db, $login_db, $haslo_db, $dbname_db);
        $baza->set_charset("utf8");
    
        if(!empty($_POST))
        {
            $dni1=$_POST['dni'];
            $sql_id_u ='SELECT Id_uzytkownika from uzytkownicy where `email`="'.$_SESSION['email'].'";';
            $wynik = $baza->query($sql_id_u);
            $wiersz = $wynik->fetch_array();
            $id = $wiersz['Id_uzytkownika'];
            $sql_sprawdz ='SELECT * from uczniowie_zapisy where Id_uzytkownika='.$id.' and Id_propozycje='.$_SESSION['kurs'].';';
            $wynik2 = $baza->query($sql_sprawdz);
            if($wynik2->num_rows >= 1)
            {
                header("location:index.php");
            }
            else
            {
            $sql_zapisz='INSERT INTO `uczniowie_zapisy` (`Id_uzytkownika`, `Id_propozycje`) VALUES("'.$id.'", "'.$_SESSION['kurs'].'");';
            $zapytanie = $baza->prepare($sql_zapisz);
            $zapytanie->execute();
            $sql_id_zapisu ='SELECT Id_zapisy from uczniowie_zapisy where Id_uzytkownika='.$id.' GROUP BY Id_zapisy DESC limit 1 ;';
            $id_z = $baza->query($sql_id_zapisu);
            $wiersz2 = $id_z->fetch_array();
            $id_zapisu = $wiersz2['Id_zapisy'];
            foreach ($dni1 as $dni)
            { 
                $sql_dni='INSERT INTO `dni_uczniowie` (`Id_zapisy`, `Id_dniatyg`) VALUES("'.$id_zapisu.'", "'.$dni.'");';
                $zapytanie2 = $baza->prepare($sql_dni);
                $zapytanie2->execute();
            }
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