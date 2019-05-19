<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Zapisy cisco</title>
        <link href='http://fonts.googleapis.com/css?family=Chela+One&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Cinzel&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Comfortaa&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Alegreya&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div class="strona">
            
        <div class="naglowek">
            <a class="logo2" href="index.php"><div class="logo"></div></a>
            <div class="logowanie">      
                <?php
    session_start();
                if(!empty($_SESSION['email']) && $_SESSION['HTTP_USER_AGENT']==md5($_SERVER['HTTP_USER_AGENT']))
                {
    echo '<div class="wyloguj"><span class="a">Zalogowany jako '.$_SESSION['imie'].' '.$_SESSION['nazwisko'].'</span>
    <form method="post" action="">
    <button class="zaloguj button_wyloguj" name="a" value="wyloguj">wyloguj</button>
    </form></div>';
    if(@$_POST['a']=='wyloguj')
    {
        session_destroy();
        header("location:index.php");
    }
                ?>
            </div>
        </div>
        <div class="tresc_moje_kursy">
        <?php
        if (@include_once("db_config.php"))
                {
                $baza = new mysqli($host_db, $login_db, $haslo_db, $dbname_db);
                $baza->set_charset("utf8");
                $sql_id_u ='SELECT Id_uzytkownika from uzytkownicy where `email`="'.$_SESSION['email'].'";';
                $wynik = $baza->query($sql_id_u);
                $wiersz = $wynik->fetch_array();
                $id = $wiersz['Id_uzytkownika'];
                $sql_kursy='SELECT uczniowie_zapisy.Id_grupy, uczniowie_zapisy.Id_zapisy, Nazwa_kursu, Opis FROM kursy_propozycje JOIN kursy_cisco on kursy_cisco.Id_kursu=kursy_propozycje.Id_kursu JOIN uczniowie_zapisy on uczniowie_zapisy.Id_propozycje=kursy_propozycje.Id_propozycje WHERE Id_uzytkownika='.$id.';';
                $wynik = $baza->query($sql_kursy);
            ?>
            <div class="tabele">
            <div class="moje_kursy">
                <div class="h"><h1>Moje kursy</h1></div>
                <table class="tabela">
                    <thead><tr><th>Nazwa kursu</th><th>Opis</th><th>Wybrane dni</th><th></th></tr></thead>
                    <tbody>
                <?php
                if($wynik->num_rows >= 1)
                            {
                while($wiersz=$wynik->fetch_array())
                {
                    $id_zap =$wiersz['Id_zapisy'];
                    $sql_dni='SELECT Nazwa FROM dni_uczniowie JOIN dni_tygodnia ON dni_tygodnia.Id_dniatyg=dni_uczniowie.Id_dniatyg WHERE Id_zapisy='.$id_zap.';';
                    $wynik_dni = $baza->query($sql_dni);
                    $liczba=$wynik_dni->num_rows;
                    $licznik=0;
                    $tabela='<tr><td>'.$wiersz['Nazwa_kursu'].'</td><td>'.$wiersz['Opis'].'</td><td>';
                    echo $tabela;
                    while($wiersz_dni = $wynik_dni->fetch_array())
                    {
                        $licznik++;
                        if($licznik==$liczba)
                        {
                            echo $wiersz_dni['Nazwa'];
                        }
                        else
                        {
                            echo $wiersz_dni['Nazwa'].', ';
                        }
                    }
                    if(is_null($wiersz['Id_grupy']))
                    {
                        echo '</td><td><form action="usun.php" method="post"><button name="id_zapisu" class="zaloguj" value="'.$id_zap.'">usuń</button></form></td></tr>';
                    }
                    else
                    {
                        echo '</td><td style="text-align:center;"><strong>zostałeś przydzielony do grupy</strong></td></tr>';
                    }
                }
                }
                else
                    {
                        echo '<tr><td colspan="3">Nie jesteś zapisany na żaden kurs</td></tr>';
                    }
                ?>
                    </tbody>
                </table>
                <div class="przycisk"><a href="zapisy.php"><button class="zaloguj zapisz_sie">Zapisz się na kurs</button></a></div>
                </div>
            <div class="moje_grupy">
                <?php
                $sql_id_u ='SELECT Id_uzytkownika from uzytkownicy where `email`="'.$_SESSION['email'].'";';
                $wynik = $baza->query($sql_id_u);
                $wiersz = $wynik->fetch_array();
                $id = $wiersz['Id_uzytkownika'];
                $sql_grupy='SELECT Nazwa_kursu, Godzina, dni_tygodnia.Nazwa, grupy.Opis, Data_rozpoczecia FROM kursy_propozycje JOIN kursy_cisco on kursy_cisco.Id_kursu=kursy_propozycje.Id_kursu JOIN uczniowie_zapisy on uczniowie_zapisy.Id_propozycje=kursy_propozycje.Id_propozycje JOIN grupy on grupy.Id_propozycje=kursy_propozycje.Id_propozycje JOIN dni_tygodnia on dni_tygodnia.Id_dniatyg=grupy.Id_dniatyg WHERE Id_uzytkownika='.$id.' and uczniowie_zapisy.Id_grupy Is not null;';
                $wynik = $baza->query($sql_grupy);
                ?>
                <table class="tabela">
                    <div class="h"><h1>Moje grupy</h1></div>
                    <thead class="grupy"><tr><th>Nazwa kursu</th><th>Opis</th><th>Data rozpoczęcia</th><th>Dzień zajęć</th><th>Godzina</th></tr></thead>
                    <tbody>
                        <?php
                            if($wynik->num_rows >= 1)
                            {
                            while($wiersz=$wynik->fetch_array())
                            {
                                $tabela='<tr><td>'.$wiersz['Nazwa_kursu'].'</td><td>'.$wiersz['Opis'].'</td><td>'.$wiersz['Data_rozpoczecia'].'</td><td>'.$wiersz['Nazwa'].'</td><td>'.$wiersz['Godzina'].'</td></tr>';
                                echo $tabela;
                            }
                            }
                            else
                            {
                               echo '<tr><td colspan="3">Nie zaostałeś przydzielony do żadnej grupy</td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
                
                </div>
            </div>
        <?php
        }
                }
                else
                {
                    header("location:index.php"); 
                }
        ?>
            
        </div>
        <div class="stopka">
            <span class="d">Projekt cisco 2017</span>
        </div>
            
        </div>
    </body>
</html>