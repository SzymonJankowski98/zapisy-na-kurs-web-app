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
            $id_propozycje=$_POST['kurs'];
            $id_dnia=$_POST['dzien'];
            $data=$_POST['data'];
            $godzina=$_POST['godzina'];
            $ilosc=$_POST['ilosc'];
            $opis=$_POST['opis'];
            $sql_dodaj = 'INSERT INTO `grupy` (`Id_propozycje`, `Id_dniatyg`, `Data_rozpoczecia`, `Ilosc_uczniow`, `Godzina`, `Opis`) VALUES("'.$id_propozycje.'", "'.$id_dnia.'", "'.$data.'", "'.$ilosc.'", "'.$godzina.'", "'.$opis.'");';
            $baza->query($sql_dodaj);
            header("Location:panel_administracyjny.php?menu=dodaj_grupe");
		  
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