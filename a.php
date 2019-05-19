<?php
    session_start();
    echo "Zalogowałeś się jako ".$_SESSION['imie'].' '.$_SESSION['nazwisko'];
    echo'<form method="post" action="">
    <button name="a" value="wyloguj"></button>
    </form>';
    if(@$_POST['a']=='wyloguj')
    {
        session_destroy();
        header("location:index.php");
    }
?>