<?php

include 'beallitas.php';

?>

<?php
$Temanev = $_POST[Tema_neve_box];

$ossz = mysqli_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
mysqli_select_db(DB_NEV, $ossz);
$sql_temaid = "SELECT idTema FROM temak where Tema_nev='$Temanev'";
$eredmeny = mysqli_query($sql_temaid, $ossz) or die(mysqli_error());
$mezok = mysqli_fetch_array($eredmeny);
$tema_azon = $mezok['idTema'];

$sql_torles1 = "delete from temak where idTema='$tema_azon'";
$sql_torles2 = "delete from kerd_val where idTema='$tema_azon'";
$sql_torles3 = "delete from naplo where idTema='$tema_azon'";
$eredmeny = mysqli_query($sql_torles1, $ossz) or die("Hiba a téma törlésében a temak táblában " . mysqli_error());
$eredmeny = mysqli_query($sql_torles2, $ossz) or die("Hiba a téma törlésében a kerd_val táblában " . mysqli_error());
$eredmeny = mysqli_query($sql_torles3, $ossz) or die("Hiba a téma törlésében a naplo táblában " . mysqli_error());

print "<b> $Temanev </b> törlődött";
$felh_nev = $_POST[felh_nev_mezo];
$jelszo = $_POST[jelszo];
?>

<form method="POST" action="user_ell_keret.php">
    <input type="hidden" name="felh_nev_mezo" value="<?php print $felh_nev ?>">
    <input type="hidden" name="jelszo" value="<?php print $jelszo ?>">
    <input type="submit" value="Vissza a Tesztépítőbe">
</form>