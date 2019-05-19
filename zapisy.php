<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>Zapisy cisco</title>
        <link href='http://fonts.googleapis.com/css?family=Chela+One&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Cinzel&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Comfortaa&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
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
        <div class="tresc_zapisy">
            <div class="kurs"><h1>Wybierz kurs</h1></div>
            <div class="kursy_propozycje">
                <form class="form_zapisy" action="zapisy_dni.php" method="post">
                    <?php
                    if (@include_once("db_config.php"))
                    {
                        $baza = new mysqli($host_db, $login_db, $haslo_db, $dbname_db);
                        $baza->set_charset("utf8");
                        
                        $sql_zapisy='SELECT `Nazwa_kursu`, `Id_propozycje`, `Opis` from `kursy_propozycje` join `kursy_cisco` on kursy_cisco.Id_kursu = kursy_propozycje.Id_kursu ';
                        $wynik = $baza->query($sql_zapisy);
                        while($wiersz=$wynik->fetch_array())
                        {
                        $propozycja = '<div class="propozycja"><label class="container">
                        <input value="'.$wiersz['Id_propozycje'].'" type="radio" name="kurs">
                        <span class="checkmark"></span>
                        </label><p class="podpis">'.$wiersz['Nazwa_kursu'].'</p><p class="opis">'.$wiersz['Opis'].'</p></div>';
                            echo $propozycja;
                        }
                    }
                    }
                    else
                        {
                        header("location:index.php"); 
                        }
                    ?>
                    <button class="zaloguj button_dalej" >Dalej</button>
                </form>
            </div>
            <div class="kursy_dni">
                <form>
                
                </form>
            </div>
            
        </div>
        <div class="stopka">
            <span class="d">Projekt cisco 2017</span>
        </div>
            
        </div>
    </body>
</html>