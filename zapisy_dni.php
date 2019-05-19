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
    if(!empty($_POST))
    {
    $_SESSION['kurs']=$_POST['kurs'];
        
    }
    if(empty($_SESSION['kurs']))
    {
        header("location:index.php");
    }
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
            <div class="kurs"><h1>Wybierz dni</h1></div>
            <div class="kursy_dni">
                <form method="post" action="zapisz.php" class="form_zapisy">
                    <?php
                    if (@include_once("db_config.php"))
                    {
                        $baza = new mysqli($host_db, $login_db, $haslo_db, $dbname_db);
                        $baza->set_charset("utf8");
                        
                        $sql_dni='SELECT Id_propozycje ,dni_kursu.Id_dniatyg, Nazwa from dni_kursu join dni_tygodnia on dni_tygodnia.Id_dniatyg=dni_kursu.Id_dniatyg where Id_propozycje="'.$_SESSION['kurs'].'";';
                        $wynik = $baza->query($sql_dni);
                        while($wiersz=$wynik->fetch_array())
                        {
                        $dni = '<div class="propozycja2"><label class="container2">
                        <input value="'.$wiersz['Id_dniatyg'].'" type="checkbox" name="dni[]">
                        <span class="checkmark2" style="background-image: url(grafika/'.$wiersz['Nazwa'].'.png)"></span>
                        </label></div>';
                        echo $dni;
                        }
                    }
                    }
                    else
                    {
                        header("location:index.php"); 
                    }
                    ?>
                    <button class="zaloguj button_dalej zapisz" >Zapisz</button>
                </form>
            </div>
        </div>
        <div class="stopka">
            <span class="d">Projekt cisco 2017</span>
        </div>
            
        </div>
    </body>
</html>