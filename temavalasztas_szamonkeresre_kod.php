<?php

include 'beallitas.php';

$ossz = mysqli_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
mysqli_select_db(DB_NEV, $ossz);
$sql = "SELECT * FROM temak where latszik=1 Order By Tema_nev";
$eredmeny = mysqli_query($sql, $ossz) or die(mysqli_error());
?>


<html>

<head>
    <title>Témaválasztás</title>

    <head>

    <body>
        <center>
            <form method="post" action="szamonkeres_keret.php">
                <select name="Tema">
                    <?php
                    while ($ujTomb = mysqli_fetch_array($eredmeny)) {
                        $Tema_nev = $ujTomb['Tema_nev'];
                        print "\t<option>$Tema_nev\n";
                    }
                    ?>
                </select>
                <input type="submit" value="A kérdéssor betöltése!">
            </form>
        </center>
    </body>

</html>