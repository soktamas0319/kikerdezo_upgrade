<?php

include 'beallitas.php';

$felh_nev = $_POST[felh_nev_mezo];
$jelszo = $_POST[jelszo];

$ossz = mysqli_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO) or die(mysqli_error());
mysqli_select_db(DB_NEV, $ossz)  or die(mysqli_error());
$sql_lekerd = "select * from temak where tulajdonos='$felh_nev' order by Tema_nev";
$eredmeny_lekerd = mysqli_query($sql_lekerd, $ossz) or die("Hiba a témák lekérdezésében " . mysqli_error());

$n = 1;

while ($mezo = mysqli_fetch_array($eredmeny_lekerd)) {  //ez csak azért kell, hogy sorba menjünk az eredményeken
  $idTema = $mezo['idTema'];
  $sorsz_latszik = $idTema . "_latszik";         //pl.: 5_latszik
  $latszik = $_POST[$sorsz_latszik];
  $sql_rejtes = "update temak set latszik='$latszik' where (tulajdonos = '$felh_nev') and (idTema='$idTema')";
  $eredmeny = mysqli_query($sql_rejtes, $ossz) or die("Hiba a témák elrejtésében " . mysqli_error());
  $n++;
}

print "<h2>A témák láthatósága módosítva lett!</h2>";

?>

<br><br>
<form method="post" action="user_ell_keret.php">
  <input type="hidden" name="felh_nev_mezo" value="<?php print "$felh_nev" ?>">
  <input type="hidden" name="jelszo" value="<?php print "$jelszo" ?>">
  <input type="submit" value="Ok!">

</form>