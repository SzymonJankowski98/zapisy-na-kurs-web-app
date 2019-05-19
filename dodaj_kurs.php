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
            $id=$_POST['rodzaj'];
            $nazwa=$_POST['nazwa'];
            $sql_dodaj = 'INSERT INTO `kursy_propozycje` (`Id_kursu`, `Nazwa_kursu`) VALUES("'.$id.'", "'.$nazwa.'");';
            $zapytanie = $baza->prepare($sql_dodaj);
		  if($zapytanie->execute())
		  {
			$dni1=$_POST['dni'];
            $sql_id_prop ='SELECT Id_propozycje FROM `kursy_propozycje` GROUP BY Id_propozycje DESC limit 1 ';
            $id_p = $baza->query($sql_id_prop);
            $wiersz2 = $id_p->fetch_array();
            $id_propozycje = $wiersz2['Id_propozycje'];
            foreach ($dni1 as $dni)
            { 
                $sql_dni='INSERT INTO `dni_kursu` (`Id_propozycje`, `Id_dniatyg`) VALUES("'.$id_propozycje.'", "'.$dni.'");';
                $zapytanie2 = $baza->prepare($sql_dni);
                $zapytanie2->execute();
            }
			header("Location:panel_administracyjny.php?menu=dodaj_kurs");
		  }
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