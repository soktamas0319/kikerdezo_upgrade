<?php

include 'beallitas.php';

function tabl_sor($n, $temanev, $latszik, $idTema) {   //felépítünk egy táblázat sort

  $sorsz_latszik = $idTema . "_latszik"; //Látszik-e vagy sem a téma (a formhoz kell radio névnek az adott sorba)
  if (($n % 2) == 1) {               //pl.: 5_latszik
    $szin = "white";
  } else {
    $szin = "LightGrey";
  }
  if ($latszik) {
    $jeloles1 = "checked";
  } else {
    $jeloles2 = "checked";
  }
  $sor = "<tr bgcolor=\"$szin\">";
  $sor .= "<td align=\"center\" width=\"5%\"> $n </td>";
  $sor .= "<td width=\"70%\"> $temanev </td>";
  $sor .= "<td align=\"center\" width=\"12%\"> <input type=\"radio\" name=\"$sorsz_latszik\" value=\"1\" $jeloles1>
         </td>";
  $sor .= "<td align=\"center\" width=\"15%\"> <input type=\"radio\" name=\"$sorsz_latszik\" value=\"0\" $jeloles2>
   </td>";
  $sor .= "</tr>";
  return $sor;
}



$felh_nev = $_POST[felh_nev_mezo];
$jelszo = $_POST[jelszo];
$teljes_nev = $_POST[teljes_nev];

$ossz = mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO) or die(mysql_error());
mysql_select_db(DB_NEV, $ossz)  or die(mysql_error());
$sql_temak = "select idTema, tulajdonos, Tema_nev, latszik from temak where tulajdonos = '$_POST[felh_nev_mezo]' order by Tema_nev";
$eredmeny = mysql_query($sql_temak, $ossz) or die("Hiba a témák lekérdezésében " . mysql_error());

print "<h3>$teljes_nev témái</h3><br>";

$hatterszin = "brown";
?>

<form method="post" action="tema_rejtes_kesz_keret.php">

  <table border="0" width="90%" cellspacing="1" cellpadding="4">
    <tr bgcolor="<?php print $hatterszin ?>">
      <th>N</th>
      <th>Téma neve</th>
      <th>Látszik</th>
      <th>Nem látszik</th>
    </tr>

    <?php $n = 1;            //Felépítjük a táblázatot
    while ($mezo = mysql_fetch_array($eredmeny)) {
      $idTema = $mezo['idTema'];
      $temanev = $mezo['Tema_nev'];
      $latszik = $mezo['latszik'];
      print tabl_sor($n, $temanev, $latszik, $idTema);
      $n++;
    }
    ?>

  </table>
  <input type="hidden" name="felh_nev_mezo" value="<?php print "$felh_nev" ?>">
  <input type="hidden" name="jelszo" value="<?php print "$jelszo" ?>">
  <br>
  <input type="submit" value="Beállítás kész!">
</form>