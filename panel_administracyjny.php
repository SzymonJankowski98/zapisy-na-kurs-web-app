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
            <a class="logo2" href="panel_administracyjny.php"><div class="logo"></div></a>
            <div class="logowanie">      
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
    echo '<div class="wyloguj"><span class="a">Zalogowany jako '.$_SESSION['email'].'</span>
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
        <div class="tresc_panel">
            <div class="panel">
            <?php
                if(@$_GET['menu']=='dodaj_kurs')
                {
                    ?>
                    <div class="panel_lewy">
                    <div class="h"><h1>Kursy</h1></div>
                    <div class="scroll">
                    <?php
                    $sql_kursy ='SELECT `Nazwa`, `Opis`, `Nazwa_kursu`, `Id_propozycje` FROM `kursy_propozycje` JOIN kursy_cisco on kursy_cisco.Id_kursu=kursy_propozycje.Id_kursu';
                    $wynik = $baza->query($sql_kursy);
                    echo'<table class="tabela">
                    <thead><tr><th>Rodzaj</th><th>Opis</th><th>Nazwa</th><th>Wybrane dni</th><th></th></tr></thead>
                    <tbody><div>';
                    if($wynik->num_rows >= 1)
                            {
                            while($wiersz=$wynik->fetch_array())
                            {
                                $tabela='<tr><td>'.$wiersz['Nazwa'].'</td><td>'.$wiersz['Opis'].'</td><td>'.$wiersz['Nazwa_kursu'].'</td><td>';
                                echo $tabela;
                                $sql_dni='SELECT Nazwa FROM dni_kursu JOIN dni_tygodnia ON dni_tygodnia.Id_dniatyg=dni_kursu.Id_dniatyg WHERE Id_propozycje='.$wiersz['Id_propozycje'].';';
                                $wynik_dni = $baza->query($sql_dni);
                                $liczba=$wynik_dni->num_rows;
                                $licznik=0;
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
                                echo '</td><td><form action="usun_kurs.php" method="post"><button name="id_kursu" class="zaloguj" value="'.$wiersz['Id_propozycje'].'">usuń</button></form></td></tr>';
                            }
                            }
                            else
                            {
                               echo '<tr><td colspan="3">Nie zaostał stworzony żadne kurs</td></tr>';
                            }
                    
                    echo'</div></tbody>
                </table>'; 
                ?>
                        </div>
                        </div>
                        <div class="panel_prawy">
                            <h1>Dodaj kurs</h1><br>
                            <form class="form_dodaj" method="post" action="dodaj_kurs.php">
                        <input placeholder="Nazwa kursu" name="nazwa" class="formularz" type="text"><br>
                        <span class="b">Rodzaj: </span><select name="rodzaj" class="select">
                        <?php
                        $sql_logowanie = 'SELECT * FROM `kursy_cisco`;';
                        $wynik = $baza->query($sql_logowanie);
                            while($wiersz=$wynik->fetch_array())
                        {
                            echo '<option value="'.$wiersz['Id_kursu'].'">'.$wiersz['Nazwa'].'</option>';   
                        }
                        ?>
                        </select>
                                <h2>Wybierz dni:</h2>
                                <?php
                        $sql_dni='SELECT Id_dniatyg, Nazwa from dni_tygodnia;';
                        $wynik = $baza->query($sql_dni);
                        while($wiersz=$wynik->fetch_array())
                        {
                        $dni = '<label class="container3">'.$wiersz['Nazwa'].'
                                <input name="dni[]" value="'.$wiersz['Id_dniatyg'].'" type="checkbox">
                                <span class="checkmark3"></span>
                                </label>';
                        echo $dni;
                        }
                                ?>
                        <button name="operacja" value="dodaj_kurs" class="zaloguj button_dodaj">dodaj</button>
                    </form>
                </div>
                <?php
                }
                    if(@$_GET['menu']=='dodaj_grupe')
                {
                    ?>
                <div class="tresc_grupy">
                    
                    <?php
                    $sql_kursy ='SELECT `grupy`.`Id_grupy`, `Nazwa_kursu`, `Opis` , `Nazwa`, `Ilosc_uczniow`, `Id_grupy`, `Godzina`, `Data_rozpoczecia` FROM `grupy` JOIN kursy_propozycje on kursy_propozycje.Id_propozycje=grupy.Id_propozycje JOIN dni_tygodnia on dni_tygodnia.Id_dniatyg=grupy.Id_dniatyg';
                    $wynik = $baza->query($sql_kursy);
                    echo'<table class="tabela2">
                    <div class="h"><h1>Grupy</h1></div>
                    <thead class="grupy"><tr><th>Id grupy</th><th>Nazwa kursu</th><th>Wybrany dzień</th><th>Opis</th><th>Ilość uczniów</th><th>Data rozpoczecia</th><th>Godzina</th><th></th></tr></thead>
                    <tbody><div>';
                    if($wynik->num_rows >= 1)
                            {
                            while($wiersz=$wynik->fetch_array())
                            {
                                $tabela='<tr><td>'.$wiersz['Id_grupy'].'</td><td>'.$wiersz['Nazwa_kursu'].'</td><td>'.$wiersz['Nazwa'].'</td><td>'.$wiersz['Opis'].'</td><td>'.$wiersz['Ilosc_uczniow'].'</td><td>'.$wiersz['Data_rozpoczecia'].'</td><td>'.$wiersz['Godzina'].'</td><td><form action="usun_grupe.php" method="post"><button name="id_grupy" class="zaloguj" value="'.$wiersz['Id_grupy'].'">usuń</button></form></td></tr>';
                                echo $tabela;
                            }
                            }
                            else
                            {
                               echo '<tr><td colspan="3">Nie zaostał stworzona żadna grupa</td></tr>';
                            }
                    
                    echo'</div></tbody>
                </table>'; 
                ?>
                       
                        
                            
                            <form class="form_dodaj2" method="post" action="dodaj_grupe.php">
                                <h1>Dodaj grupę</h1>
                        <span class="b">Data rozpoczęcia: </span> <input name="data" class="formularz" type="date"><br>
                        <span class="b">Godzina rozpoczęcia: </span><input name="godzina" class="formularz" type="time"><br><br>
                        <input placeholder="ilość uczniów" name="ilosc" class="formularz" type="number"><br><br>
                        <textarea name="opis" placeholder="Opis" class="text"></textarea>
                        <span class="b">Kurs: </span><select name="kurs" class="select">
                        <?php
                        $sql_dni='SELECT Nazwa FROM dni_kursu JOIN dni_tygodnia ON dni_tygodnia.Id_dniatyg=dni_kursu.Id_dniatyg WHERE Id_propozycje='.$wiersz['Id_propozycje'].';';
                        $sql_logowanie = 'SELECT * FROM `kursy_propozycje`;';
                        $wynik = $baza->query($sql_logowanie);
                            while($wiersz=$wynik->fetch_array())
                        {
                            echo '<option value="'.$wiersz['Id_propozycje'].'">'.$wiersz['Nazwa_kursu'].'</option>';   
                        }
                        ?>
                        </select>
                                <h2>Wybierz dzień:</h2>
                                <?php
                        $sql_dni='SELECT Id_dniatyg, Nazwa from dni_tygodnia;';
                        $wynik = $baza->query($sql_dni);
                        while($wiersz=$wynik->fetch_array())
                        {
                        $dni = '<label class="container3">'.$wiersz['Nazwa'].'
                                <input name="dzien" value="'.$wiersz['Id_dniatyg'].'" type="radio">
                                <span class="checkmark3"></span>
                                </label>';
                        echo $dni;
                        }
                                ?>
                        <button name="operacja" value="dodaj_grupe" class="zaloguj button_dodaj">dodaj</button>
                                </form>
                </div>
                <?php
                }
                    ?>
                <?php
                if(@$_GET['menu']=='dodaj_do_grupy')
                {
                    ?>
                <?php
                if(isset($_POST['dodaj']))
                {
                    $sql_dodaj = 'UPDATE `uczniowie_zapisy` SET `Id_grupy` ='.$_SESSION['grupy'].' WHERE `id_uzytkownika`='.$_POST['dodaj'].' and Id_propozycje='.$_SESSION['kurs'].';';
                    $wynik3 = $baza->query($sql_dodaj);
                }
                    if(isset($_POST['usun']))
                {
                    $sql_usun = 'UPDATE `uczniowie_zapisy` SET `Id_grupy` =NULL WHERE `id_uzytkownika`='.$_POST['usun'].' and `Id_grupy` ='.$_SESSION['grupy'].';';
                    $wynik3 = $baza->query($sql_usun);
                }
                ?>
                <div class="tresc_grupy">
                    
                    <?php
                    echo'<table class="tabela2">
                    <div class="h"><h1>Użytkownicy nieprzypisani</h1></div>';
                    ?>
                    <div class="z"><form method="post" action=""><span class="b">Kursy: </span><select name="kurs" class="select2">
                    <?php
                        $sql_logowanie = 'SELECT * FROM `kursy_propozycje`;';
                        $wynik2 = $baza->query($sql_logowanie);
                            while($wiersz=$wynik2->fetch_array())
                        {
                                if(isset($_POST['kurs']))
                                {
                                    if($_POST['kurs']==$wiersz['Id_propozycje'])
                                    {
                                        echo '<option selected value="'.$wiersz['Id_propozycje'].'">'.$wiersz['Nazwa_kursu'].'</option>';
                                    }
                                    else
                                    {
                                        echo '<option value="'.$wiersz['Id_propozycje'].'">'.$wiersz['Nazwa_kursu'].'</option>';
                                    }
                                }
                                else if(!empty($_SESSION['kurs']))
                                {
                                    if($_SESSION['kurs']==$wiersz['Id_propozycje'])
                                    {
                                        echo '<option selected value="'.$wiersz['Id_propozycje'].'">'.$wiersz['Nazwa_kursu'].'</option>';
                                    }
                                    else
                                    {
                                        echo '<option value="'.$wiersz['Id_propozycje'].'">'.$wiersz['Nazwa_kursu'].'</option>';
                                    }
                                }
                                else
                                {
                                    echo '<option value="'.$wiersz['Id_propozycje'].'">'.$wiersz['Nazwa_kursu'].'</option>';
                                }
                                
                        }
                    ?>
                        </select><button name="id_propozycje" class="zaloguj" value="">wybierz</button></form></div>
                    <div class="z"><form method="post" action=""><span class="b">sortowanie: </span><select name="sortowanie" class="select2">
                        <?php
                        
                        if($_POST['sortowanie']=='klasa')
                        {
                        echo'<option selected value="klasa">klasa</option>
                        <option value="imie">imię</option>
                        <option value="nazwisko">Nazwisko</option>';
                        }
                        else if($_POST['sortowanie']=='imie')
                        {
                        echo'<option value="klasa">klasa</option>
                        <option selected value="imie">imię</option>
                        <option value="nazwisko">Nazwisko</option>';
                        }
                        else if($_POST['sortowanie']=='nazwisko')
                        {
                        echo'<option selected value="klasa">klasa</option>
                        <option value="imie">imię</option>
                        <option selected value="nazwisko">Nazwisko</option>';
                        }
                        else if($_SESSION['sortowanie']=='klasa')
                        {
                        echo'<option selected value="klasa">klasa</option>
                        <option value="imie">imię</option>
                        <option value="nazwisko">Nazwisko</option>';
                        }
                        else if($_SESSION['sortowanie']=='imie')
                        {
                        echo'<option value="klasa">klasa</option>
                        <option selected value="imie">imię</option>
                        <option value="nazwisko">Nazwisko</option>';
                        }
                        else if($_SESSION['sortowanie']=='nazwisko')
                        {
                        echo'<option selected value="klasa">klasa</option>
                        <option value="imie">imię</option>
                        <option selected value="nazwisko">Nazwisko</option>';
                        }
                        else
                        {
                        echo'<option value="klasa">klasa</option>
                        <option value="imie">imię</option>
                        <option value="nazwisko">Nazwisko</option>';
                        }
                        ?>
                        </select><button name="id_propozycje" class="zaloguj" value="">sortuj</button></form></div>
                    <?php
                    echo '<thead><tr><th>Imię</th><th>Nazwisko</th><th>Email</th><th>Klasa</th><th>Wybrane dni</th><th></th></tr></thead>
                    <tbody><div>';
                    if(isset($_POST['kurs']) || !empty($_SESSION['kurs']))
                       {
                        
                            if(isset($_POST['kurs']))
                               {
                                   $_SESSION['kurs']=$_POST['kurs'];
                               }
                        
                        if(isset($_POST['sortowanie']) || !empty($_SESSION['sortowanie']))
                        {
                            if(isset($_POST['sortowanie']))
                               {
                                   $_SESSION['sortowanie'] = $_POST['sortowanie'];
                               }
                            if($_SESSION['sortowanie']=='klasa')
                            {
                            $sql_uzytkownicy ='SELECT uzytkownicy.Id_uzytkownika , Imie, Nazwisko, Email, klasy.Nazwa, Id_zapisy FROM uzytkownicy JOIN klasy on klasy.Id_klasy=uzytkownicy.Id_klasy JOIN uczniowie_zapisy on uczniowie_zapisy.Id_uzytkownika=uzytkownicy.Id_uzytkownika WHERE uczniowie_zapisy.Id_grupy is NULL and uczniowie_zapisy.Id_propozycje='.$_SESSION['kurs'].' ORDER BY klasy.Nazwa;';
                            }
                            if($_SESSION['sortowanie']=='imie')
                            {
                            $sql_uzytkownicy ='SELECT uzytkownicy.Id_uzytkownika , Imie, Nazwisko, Email, klasy.Nazwa, Id_zapisy FROM uzytkownicy JOIN klasy on klasy.Id_klasy=uzytkownicy.Id_klasy JOIN uczniowie_zapisy on uczniowie_zapisy.Id_uzytkownika=uzytkownicy.Id_uzytkownika WHERE uczniowie_zapisy.Id_grupy is NULL and uczniowie_zapisy.Id_propozycje='.$_SESSION['kurs'].' ORDER BY Imie;';
                            }
                            if($_SESSION['sortowanie']=='nazwisko')
                            {
                            $sql_uzytkownicy ='SELECT uzytkownicy.Id_uzytkownika , Imie, Nazwisko, Email, klasy.Nazwa, Id_zapisy FROM uzytkownicy JOIN klasy on klasy.Id_klasy=uzytkownicy.Id_klasy JOIN uczniowie_zapisy on uczniowie_zapisy.Id_uzytkownika=uzytkownicy.Id_uzytkownika WHERE uczniowie_zapisy.Id_grupy is NULL and uczniowie_zapisy.Id_propozycje='.$_SESSION['kurs'].' ORDER BY Nazwisko;';
                            }
                        }
                        else
                        {
                    $sql_uzytkownicy ='SELECT uzytkownicy.Id_uzytkownika , Imie, Nazwisko, Email, klasy.Nazwa, Id_zapisy FROM uzytkownicy JOIN klasy on klasy.Id_klasy=uzytkownicy.Id_klasy JOIN uczniowie_zapisy on uczniowie_zapisy.Id_uzytkownika=uzytkownicy.Id_uzytkownika WHERE uczniowie_zapisy.Id_grupy is NULL and uczniowie_zapisy.Id_propozycje='.$_SESSION['kurs'].';';
                        }
                    $wynik = $baza->query($sql_uzytkownicy);
                    if($wynik->num_rows >= 1)
                            {
                            while($wiersz=$wynik->fetch_array())
                            {
                                echo '<tr><td>'.$wiersz['Imie'].'</td><td>'.$wiersz['Nazwisko'].'</td><td>'.$wiersz['Email'].'</td><td>'.$wiersz['Nazwa'].'</td><td>';
                                $sql_id_zapisu='SELECT Id_zapisy FROM `uczniowie_zapisy` WHERE Id_uzytkownika='.$wiersz['Id_uzytkownika'].' AND Id_propozycje ='.$_SESSION['kurs'].';';
                                $wynik_id = $baza->query($sql_id_zapisu);
                                $wiersz_id=$wynik_id->fetch_array();
                                $sql_dni='SELECT Nazwa FROM dni_uczniowie JOIN dni_tygodnia ON dni_tygodnia.Id_dniatyg=dni_uczniowie.Id_dniatyg WHERE Id_zapisy='.$wiersz_id['Id_zapisy'].';';
                                $wynik_dni = $baza->query($sql_dni);
                                $liczba=$wynik_dni->num_rows;
                                $licznik=0;
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
                                
                                echo '</td><td>';
                                if(!empty($_SESSION['grupy']) || isset($_POST['grupy']))
                                {
                                echo'<form action="" method="post"><button name="dodaj" class="zaloguj" value="'.$wiersz['Id_uzytkownika'].'">dodaj</button></form>';
                                }
                                    echo'</td></tr>';
                                
                            }
                            }
                            else
                            {
                               echo '<tr><td colspan="3">Wszyscy użytkownicy zostali przypisani do grup lub żaden nie zapisał się na ten kurs</td></tr>';
                            }
                       }
                       else
                       {
                             echo '<tr><td colspan="3">Wybierz kurs</td></tr>';
                       }
                    
                    echo'</div></tbody>
                </table>'; 
                ?>
                  
                    
                    
                        
                    
                    
                    
                    
                    
                    
                    <?php
                    echo'<table class="tabela2">
                    <div class="h"><h1>Użytkownicy przypisani</h1></div>';
                    ?>
                    <div class="z"><form method="post" action=""><span class="b">grupy: </span><select name="grupy" class="select2">
                    <?php
                        if(isset($_POST['kurs']))
                        {
                            $sql_grupy = 'SELECT * FROM `grupy` where Id_propozycje='.$_POST['kurs'].';';
                        }
                        else if(!empty($_SESSION['kurs']))
                        {
                        $sql_grupy = 'SELECT * FROM `grupy` where Id_propozycje='.$_SESSION['kurs'].';';
                        }
                        else
                        {
                            $sql_grupy = 'SELECT * FROM `grupy` where 2=1;';
                        }
                        $wynik2 = $baza->query($sql_grupy);
                            while($wiersz=$wynik2->fetch_array())
                        {
                                if(isset($_POST['grupy']))
                                {
                                    if($_POST['grupy']==$wiersz['Id_grupy'])
                                    {
                                        echo '<option selected value="'.$wiersz['Id_grupy'].'">'.$wiersz['Id_grupy'].'</option>';
                                    }
                                    else
                                    {
                                        echo '<option value="'.$wiersz['Id_grupy'].'">'.$wiersz['Id_grupy'].'</option>';
                                    }
                                }
                                else if(!empty($_SESSION['grupy']))
                                {
                                    if($_SESSION['grupy']==$wiersz['grupy'])
                                    {
                                        echo '<option selected value="'.$wiersz['Id_grupy'].'">'.$wiersz['Id_grupy'].'</option>';
                                    }
                                    else
                                    {
                                        echo '<option value="'.$wiersz['Id_grupy'].'">'.$wiersz['Id_grupy'].'</option>';
                                    }
                                }
                                else
                                {
                                    echo '<option value="'.$wiersz['Id_grupy'].'">'.$wiersz['Id_grupy'].'</option>';
                                }
                        }
                    ?>
                        </select><button name="id_propozycje" class="zaloguj" value="">wybierz</button></form></div>
                    <div class="z"><form method="post" action=""><span class="b">sortowanie: </span><select name="sortowanie_g" class="select2">
                        <?php
                        
                        if($_POST['sortowanie_g']=='klasa')
                        {
                        echo'<option selected value="klasa">klasa</option>
                        <option value="imie">imię</option>
                        <option value="nazwisko">Nazwisko</option>';
                        }
                        else if($_POST['sortowanie_g']=='imie')
                        {
                        echo'<option value="klasa">klasa</option>
                        <option selected value="imie">imię</option>
                        <option value="nazwisko">Nazwisko</option>';
                        }
                        else if($_POST['sortowanie_g']=='nazwisko')
                        {
                        echo'<option selected value="klasa">klasa</option>
                        <option value="imie">imię</option>
                        <option selected value="nazwisko">Nazwisko</option>';
                        }
                        else if($_SESSION['sortowanie_g']=='klasa')
                        {
                        echo'<option selected value="klasa">klasa</option>
                        <option value="imie">imię</option>
                        <option value="nazwisko">Nazwisko</option>';
                        }
                        else if($_SESSION['sortowanie_g']=='imie')
                        {
                        echo'<option value="klasa">klasa</option>
                        <option selected value="imie">imię</option>
                        <option value="nazwisko">Nazwisko</option>';
                        }
                        else if($_SESSION['sortowanie_g']=='nazwisko')
                        {
                        echo'<option selected value="klasa">klasa</option>
                        <option value="imie">imię</option>
                        <option selected value="nazwisko">Nazwisko</option>';
                        }
                        else
                        {
                        echo'<option value="klasa">klasa</option>
                        <option value="imie">imię</option>
                        <option value="nazwisko">Nazwisko</option>';
                        }
                        ?>
                        </select><button name="id_propozycje" class="zaloguj" value="">sortuj</button></form></div>
                    <?php
                    echo '<thead><tr><th>Imię</th><th>Nazwisko</th><th>Email</th><th>Klasa</th><th>Wybrane dni</th><th>';
                    if(!empty($_SESSION['ilosc']))
                    {
                        //echo $_SESSION['ilosc'];
                    }
                    echo '</th></tr></thead>
                    <tbody><div>';
                    if(isset($_POST['grupy']) || !empty($_SESSION['grupy']))
                       {
                        
                            if(isset($_POST['grupy']))
                               {
                                   $_SESSION['grupy']=$_POST['grupy'];
                               }
                        
                        if(isset($_POST['sortowanie_g']) || !empty($_SESSION['sortowanie_g']))
                        {
                            if(isset($_POST['sortowanie_g']))
                               {
                                   $_SESSION['sortowanie_g'] = $_POST['sortowanie_g'];
                               }
                            if($_SESSION['sortowanie_g']=='klasa')
                            {
                            $sql_uzytkownicy_g ='SELECT grupy.Ilosc_uczniow, kursy_propozycje.Nazwa_kursu, uzytkownicy.Id_uzytkownika , Imie, Nazwisko, Email, klasy.Nazwa, Id_zapisy FROM uzytkownicy JOIN klasy on klasy.Id_klasy=uzytkownicy.Id_klasy JOIN uczniowie_zapisy on uczniowie_zapisy.Id_uzytkownika=uzytkownicy.Id_uzytkownika JOIN kursy_propozycje on kursy_propozycje.Id_propozycje=uczniowie_zapisy.Id_propozycje JOIN grupy on grupy.Id_grupy=uczniowie_zapisy.Id_grupy WHERE uczniowie_zapisy.Id_grupy='.$_SESSION['grupy'].' ORDER BY klasy.Nazwa;';
                            }
                            if($_SESSION['sortowanie_g']=='imie')
                            {
                            $sql_uzytkownicy_g ='SELECT grupy.Ilosc_uczniow, kursy_propozycje.Nazwa_kursu, uzytkownicy.Id_uzytkownika , Imie, Nazwisko, Email, klasy.Nazwa, Id_zapisy FROM uzytkownicy JOIN klasy on klasy.Id_klasy=uzytkownicy.Id_klasy JOIN uczniowie_zapisy on uczniowie_zapisy.Id_uzytkownika=uzytkownicy.Id_uzytkownika JOIN kursy_propozycje on kursy_propozycje.Id_propozycje=uczniowie_zapisy.Id_propozycje JOIN grupy on grupy.Id_grupy=uczniowie_zapisy.Id_grupy WHERE uczniowie_zapisy.Id_grupy='.$_SESSION['grupy'].' ORDER BY Imie;';
                            }
                            if($_SESSION['sortowanie_g']=='nazwisko')
                            {
                            $sql_uzytkownicy_g ='SELECT grupy.Ilosc_uczniow, kursy_propozycje.Nazwa_kursu, uzytkownicy.Id_uzytkownika , Imie, Nazwisko, Email, klasy.Nazwa, Id_zapisy FROM uzytkownicy JOIN klasy on klasy.Id_klasy=uzytkownicy.Id_klasy JOIN uczniowie_zapisy on uczniowie_zapisy.Id_uzytkownika=uzytkownicy.Id_uzytkownika JOIN kursy_propozycje on kursy_propozycje.Id_propozycje=uczniowie_zapisy.Id_propozycje JOIN grupy on grupy.Id_grupy=uczniowie_zapisy.Id_grupy WHERE uczniowie_zapisy.Id_grupy='.$_SESSION['grupy'].' ORDER BY Nazwisko;';
                            }
                        }
                        else
                        {
                    $sql_uzytkownicy_g ='SELECT grupy.Ilosc_uczniow, kursy_propozycje.Nazwa_kursu, uzytkownicy.Id_uzytkownika , Imie, Nazwisko, Email, klasy.Nazwa, Id_zapisy FROM uzytkownicy JOIN klasy on klasy.Id_klasy=uzytkownicy.Id_klasy JOIN uczniowie_zapisy on uczniowie_zapisy.Id_uzytkownika=uzytkownicy.Id_uzytkownika JOIN kursy_propozycje on kursy_propozycje.Id_propozycje=uczniowie_zapisy.Id_propozycje JOIN grupy on grupy.Id_grupy=uczniowie_zapisy.Id_grupy WHERE uczniowie_zapisy.Id_grupy='.$_SESSION['grupy'].';';
                        }
                    $wynik = $baza->query($sql_uzytkownicy_g);
                    if($wynik->num_rows >= 1)
                            {
                            $sql_test = 'SELECT * FROM uczniowie_zapisy WHERE Id_grupy is not null and Id_propozycje='.$_SESSION['kurs'];
                            $wyniktest = $baza->query($sql_test);
                            if($wyniktest->num_rows ==0)
                            {
                                echo '<tr><td colspan="3">Nie stworzono żadnej grupy</td></tr>';
                            }
                            else
                            {
                            while($wiersz=$wynik->fetch_array())
                            {
                                $_SESSION['ilosc']=$wiersz['Ilosc_uczniow'];
                                echo '<tr><td>'.$wiersz['Imie'].'</td><td>'.$wiersz['Nazwisko'].'</td><td>'.$wiersz['Email'].'</td><td>'.$wiersz['Nazwa'].'</td><td>';
                                $sql_id_zapisu='SELECT Id_zapisy FROM `uczniowie_zapisy` WHERE Id_uzytkownika='.$wiersz['Id_uzytkownika'].' AND Id_propozycje ='.$_SESSION['kurs'].';';
                                $wynik_id = $baza->query($sql_id_zapisu);
                                $wiersz_id=$wynik_id->fetch_array();
                                $sql_dni='SELECT Nazwa FROM dni_uczniowie JOIN dni_tygodnia ON dni_tygodnia.Id_dniatyg=dni_uczniowie.Id_dniatyg WHERE Id_zapisy='.$wiersz_id['Id_zapisy'].';';
                                $wynik_dni = $baza->query($sql_dni);
                                $liczba=$wynik_dni->num_rows;
                                $licznik=0;
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
                                
                                echo '</td><td><form action="" method="post"><button name="usun" class="zaloguj" value="'.$wiersz['Id_uzytkownika'].'">usuń</button></form></td></tr>';
                                
                            }
                            }
                            }
                            else
                            {
                               echo '<tr><td colspan="3">żaden użytkownik nie został przydzielony do tej grupy</td></tr>';
                            }
                       }
                       else
                       {
                             echo '<tr><td colspan="3">Wybierz kurs</td></tr>';
                       }
                    
                    echo'</div></tbody>
                </table>'; 
                ?>
                    
                    
                    
                        
                </div>
                <?php
                }
                    ?>
                
            </div>
            <div class="nawigacja">
                <?php
                echo '<form method="get" action=""><button class="menu" name="menu" value="dodaj_kurs">Dodaj kurs</button></form>';
                echo '<form method="get" action=""><button class="menu" name="menu" value="dodaj_grupe">Dodaj grupę</button></form>';
                echo '<form method="get" action=""><button class="menu" name="menu" value="dodaj_do_grupy">Dodawanie do grup</button></form>';
                ?>
            </div>
        </div>
        <div class="stopka">
            <span class="d">Projekt cisco 2017</span>
        </div>
            <?php
                    }
                }
                ?>
        </div>
    </body>
</html>