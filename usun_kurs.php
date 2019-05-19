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
            $id_kursu=$_POST['id_kursu'];
            $sql_id_zapisu = 'SELECT Id_zapisy FROM `uczniowie_zapisy` WHERE Id_propozycje='.$id_kursu.';';
            $wynik_id_zapisu = $baza->query($sql_id_zapisu);
            
            while($wiersz=$wynik_id_zapisu->fetch_array())
            {
                $sql_usun_uczniowie_zapisy_dni = 'DELETE FROM `dni_uczniowie` WHERE Id_zapisy='.$wiersz['Id_zapisy'].';';
                $baza->query($sql_usun_uczniowie_zapisy_dni);
                $sql_usun_uczniowie_zapisy = 'DELETE FROM `uczniowie_zapisy` WHERE Id_zapisy='.$wiersz['Id_zapisy'].';';
                $baza->query($sql_usun_uczniowie_zapisy);
            }
            $sql_usun_grupy='DELETE FROM `grupy` WHERE id_propozycje='.$id_kursu.';';
            $baza->query($sql_usun_grupy);
            $sql_usun_dni_kursu='DELETE FROM `dni_kursu` WHERE id_propozycje='.$id_kursu.';';
            $baza->query($sql_usun_dni_kursu);
            $sql_usun_kursy_propozycje = 'DELETE FROM `kursy_propozycje` WHERE id_propozycje='.$id_kursu.';';
            $baza->query($sql_usun_kursy_propozycje);
            header("location:panel_administracyjny.php?menu=dodaj_kurs");
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