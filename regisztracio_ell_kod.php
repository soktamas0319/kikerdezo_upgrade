<?php
function ellenorzes_ok() {
  global $nev, $jelszo, $jelszo2, $rendes_nev, $email, $hiba;
  $rendben = true;
  $hiba = "";
  if ($rendes_nev == '') {
    $rendben = false;
    $hiba .= "Név nincs kitöltve!<br>";
  }
  if ($email == '') {
    $rendben = false;
    $hiba .= "E-mail cím nincs kitöltve!<br>";
  }
  if ($nev == '') {
    $rendben = false;
    $hiba .= "Felhasználónév nincs kitöltve!<br>";
  }
  if ($jelszo == '') {
    $rendben = false;
    $hiba .= "Jelszó nincs kitöltve!<br>";
  }
  if (!($jelszo2 == $jelszo)) {
    $rendben = false;
    $hiba .= "Jelszó és a megerősítése nem egyezik!<br>";
  }
  return $rendben;
}
//
//FŐPROGRAM
$nev = $_POST[nev];
$jelszo = $_POST[jelszo];
$jelszo2 = $_POST[jelszo2];
$rendes_nev = $_POST[rendes_nev];
$email = $_POST[email];
?>

<table width="30%" border="0" cellpadding="2" cellspacing="2">
  <tr align="left">
    <td> <?php print "<b>Név</b>:" ?> </td>
    <td> <?php print "$rendes_nev" ?> </td>
  </tr>
  <tr>
    <td align="left"> <?php print "<b>E_mail:</b>" ?> </td>
    <td><?php print "$email" ?></td>
  </tr>

  <tr align="left">
    <td> <?php print "<b>Felhasználónév</b>:" ?> </td>
    <td> <?php print "$nev" ?> </td>
  </tr>
  <tr>
    <td align="left"> <?php print "<b>Jelszó:</b>" ?> </td>
    <td><?php print "$jelszo" ?></td>
  </tr>

</table>
<br><br>
<?php
//if (!isset($rendes_nev) or !isset($nev) or !isset($jelszo) or !isset($email)){
if (ellenorzes_ok()) {
  $kimenet = "<form method=\"post\" action=\"sikeres_regisztracio_keret.php\"> ";
  $kimenet .= "<input type=\"hidden\" name=\"nev\" value=\"$nev\">";
  $kimenet .= "<input type=\"hidden\" name=\"jelszo\" value=\"$jelszo\">";
  $kimenet .= "<input type=\"hidden\" name=\"rendes_nev\" value=\"$rendes_nev\">";
  $kimenet .= "<input type=\"hidden\" name=\"email\" value=\"$email\">";
  $kimenet .= "<input type=\"submit\" value=\"Rendben!\">";
  $kimenet .= "</form>";
} else {
  $kimenet = "<font color=\"#F35847\">$hiba</font><br><br>";

  $kimenet .= "<form method=\"post\" action=\"regisztracio.php\"> ";
  $kimenet .= "<input type=\"hidden\" name=\"nev\" value=\"$nev\">";
  $kimenet .= "<input type=\"hidden\" name=\"jelszo\" value=\"$jelszo\">";
  $kimenet .= "<input type=\"hidden\" name=\"rendes_nev\" value=\"$rendes_nev\">";
  $kimenet .= "<input type=\"hidden\" name=\"email\" value=\"$email\">";
  $kimenet .= "<input type=\"submit\" value=\" Vissza a regisztrációra!\">";
  $kimenet .= "</form>";
}

print "$kimenet";

?>