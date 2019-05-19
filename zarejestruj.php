<?php
if (@!$_SESSION['email'])
{
    if (@include_once("db_config.php"))
    {
        $baza = new mysqli($host_db, $login_db, $haslo_db, $dbname_db);
        $baza->set_charset("utf8");
    }
    else
    {
        header("location:index.php?zarejestruj=blad");
    }
    $imie=$_POST['imie'];
	$nazwisko=$_POST['nazwisko'];
	$email=$_POST['email'];
	$haslo=$_POST['haslo'];
	$haslo2=$_POST['haslo2'];
	$klasa=$_POST['klasa'];
    
    $sql_idklasy = 'SELECT `Id_klasy` from `klasy` where `Nazwa`="'.$_POST['klasa'].'";';
    $sql_email = 'SELECT * from uzytkownicy where `email`="'.$_POST['email'].'";';
    
    $wynik = $baza->query($sql_email);
    if($wynik->num_rows == 1)
    {
        $baza->close();
        header("Location: index.php?zarejestruj=emailjest");
        
    }
    else
    if(preg_match('@^[^;\"\'\/][a-zA-ZąćęłńóśżźĄĆĘŁŃÓŚŻŹ]{2,30}$@',$imie))
    {
        if(preg_match('@^[^;\"\'\/][A-ZĄĘŚĆÓŃŁa-ząęśćóńł]{2,20}(\-[A-ZĄĘĆŚŁŃa-ząęćśłń]{2,20})?$@',$nazwisko))
        {
            if(preg_match('@^[^;\"\'\/][a-zA-Z0-9.-_]+\@[a-zA-Z0-9.-_]+.[a-z]{2,4}$@',$email))
            {
                if(preg_match('@^[^;\"\'\/]((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W\_]).{8,35})$@',$haslo))
                {
                    if($haslo==$haslo2)
                    {
                        $wynik = $baza->query($sql_idklasy);
    if($wynik->num_rows == 1) 
        {
        $wiersz = $wynik->fetch_array();
        $sql_dodaj = 'INSERT INTO `uzytkownicy` (`Imie`, `Nazwisko`, `Email`, `Haslo`,`Id_klasy`) VALUES("'.$imie.'", "'.$nazwisko.'", "'.$email.'", "'.$haslo.'","'.$wiersz['Id_klasy'].'");';
        $zapytanie = $baza->prepare($sql_dodaj);
		if($zapytanie->execute())
		{
			$baza->close();
			header("Location: index.php?zarejestruj=ok");
		}
        }
                    }
                    else
                    {
                        $baza->close();
                        header("Location: index.php?zarejestruj=hasla");
                    }
                }
                else
                {
                    $baza->close();
                header("Location: index.php?zarejestruj=haslo");
                }
            }
            else
            {
                $baza->close();
                header("Location: index.php?zarejestruj=email");
            }
        }
        else
        {
            $baza->close();
        header("Location: index.php?zarejestruj=nazwisko");
        }
    }
    else
    {
        $baza->close();
        header("Location: index.php?zarejestruj=imie");
    }

}
else
{
    header("location:index.php");
}
?>