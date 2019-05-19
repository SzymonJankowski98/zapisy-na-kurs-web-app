<?php
session_start();

if (@include_once("db_config.php"))
{
$baza = new mysqli($host_db, $login_db, $haslo_db, $dbname_db);
$baza->set_charset("utf8");
?>
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
            <a class="logo2" href="#"><div class="logo"></div></a>
            <div class="logowanie">
                <?php
    $logowanie = '<form class="form_logowanie" method="post" action="">
                    <div class="login"><span class="a">Email: </span><input name="email" class="formularz" type="text"></div>
                <div class="haslo"><span class="a">Hasło: </span><input name="haslo" class="formularz" type="password"></div>
                <button name="operacja" value="zaloguj" class="zaloguj">zaloguj</button>
                </form>';
    if (empty($_POST))
    {
    $email="";
    $haslo="";
    $operacja="";
        if(!empty($_SESSION['email']) && $_SESSION['HTTP_USER_AGENT']==md5($_SERVER['HTTP_USER_AGENT']))
        {
            $sql_logowanie_admin = 'SELECT * FROM `administratorzy` WHERE `Email` LIKE "'.$_SESSION['email'].'"';
            $wynik = $baza->query($sql_logowanie_admin);
            if($wynik->num_rows == 1)
            {
            header("location:panel_administracyjny.php");
            }
            else
            {
            header("Location:moje_kursy.php");
            }
        }
        else
        {
            echo $logowanie;
        }
    }
    else
    {
    $email = $_POST['email'];
    $haslo = $_POST['haslo'];
    $operacja = $_POST['operacja'];
        
    $sql_logowanie = 'SELECT * FROM `uzytkownicy` WHERE `Email` LIKE "'.$email.'" AND `Haslo` = "'.$haslo.'";';
    $sql_logowanie_admin = 'SELECT * FROM `administratorzy` WHERE `Email` LIKE "'.$email.'" AND `Haslo` = "'.$haslo.'";';
    $wynik = $baza->query($sql_logowanie_admin);
        if($wynik->num_rows == 1) 
        {
        $wiersz = $wynik->fetch_array();
        $_SESSION['email'] = $email;
		$_SESSION['imie'] = $wiersz['Imie'];
		$_SESSION['nazwisko'] = $wiersz['Nazwisko'];
		$_SESSION['HTTP_USER_AGENT']=md5($_SERVER['HTTP_USER_AGENT']);
        
		header("Location:panel_administracyjny.php");
        }
        else
        {
            $wynik = $baza->query($sql_logowanie);
            if($wynik->num_rows == 1) 
            {
            $wiersz = $wynik->fetch_array();
            $_SESSION['email'] = $email;
            $_SESSION['imie'] = $wiersz['Imie'];
		    $_SESSION['nazwisko'] = $wiersz['Nazwisko'];
		    $_SESSION['HTTP_USER_AGENT']=md5($_SERVER['HTTP_USER_AGENT']);
        
		    header("Location:moje_kursy.php");
            }
            else
            {
                echo $logowanie;
            }
        }
    }
    

?>
                
            </div>
        </div>
        <div class="tresc">
            <div class="tekst"><h1>Lorem ipsum</h1><br><div class="c">orem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. PhaselLlus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum. Integer aliquam purus. Quisque lorem tortor fringilla sed, vestibulum id, eleifend justo vel bibendum sapien massa ac turpis faucibus orci luctus non, consectetuer</div></div>
            <div class="rejestracja">
            <form class="form_rejestracja" method="post" action="zarejestruj.php">
                <h1>Zarejstruj się</h1><br>
                <input placeholder="Imię" name="imie" class="formularz" type="text"><br>
                <input placeholder="Nazwisko" name="nazwisko" class="formularz" type="text"><br>
                <input placeholder="Email" name="email" class="formularz" type="text"><br>
                <input placeholder="Hasło" name="haslo" class="formularz" type="password"><br>
                <input placeholder="Powtórz hasło" name="haslo2" class="formularz" type="password"><br>
                <span class="b">Klasa: </span><select name="klasa" class="select">
                <?php
                 $sql_logowanie = 'SELECT * FROM `klasy`;';
                $wynik = $baza->query($sql_logowanie);
                while($wiersz=$wynik->fetch_array())
                {
                    echo '<option>'.$wiersz['Nazwa'].'</option>';   
                }
                ?>
                </select>
                <button name="operacja" value="zarejestruj" class="zaloguj zarejestruj">zarejestruj</button>
                <?php
                    if(@$_GET['zarejestruj']=='emailjest')
                    {
                        echo '<div class="g">Ten email został już podany do innego konta</div>';
                    }
                    if(@$_GET['zarejestruj']=='ok')
                    {
                        echo '<div class="e">Rejestracja powiodła się</div>';
                    }
                    if(@$_GET['zarejestruj']=='imie')
                    {
                        echo '<div class="g">Proszę podać prawidłowe imię</div>';
                    }
                    if(@$_GET['zarejestruj']=='nazwisko')
                    {
                        echo '<div class="g">Proszę podać prawidłowe nazwisko (bez spacji)</div>';
                    }
                    if(@$_GET['zarejestruj']=='email')
                    {
                        echo '<div class="g">Proszę podać prawidłowy email</div>';
                    }
                    if(@$_GET['zarejestruj']=='haslo')
                    {
                        echo '<div class="g">Proszę podać poprawne hasło (minimum: 8 znaków, 1 duża litera, 1 mała litera, 1 znak specjalny)</div>';
                    }
                    if(@$_GET['zarejestruj']=='hasla')
                    {
                        echo '<div class="g">Hasło nie zgadza się z podanym wcześniej</div>';
                    }
                    }
                ?>
            </form>
            </div>
        </div>
        <div class="stopka">
            <span class="d">Projekt cisco 2017</span>
        </div>
            
        </div>
    </body>
</html>