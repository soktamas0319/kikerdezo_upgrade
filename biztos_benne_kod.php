<?php 
$Temanev=$_POST[Tema_neve_box];
$felh_nev=$_POST[felh_nev_mezo];
$jelszo=$_POST[jelszo];

?>

<H2>Valóban törölni akarja a kérdéssort?</H2>
<br><br>
<form  name="torles" method="POST" action="kerdes_torles_keret.php">
<input type="hidden" name="felh_nev_mezo" value="<?php print $felh_nev ?>">
<input type="hidden" name="jelszo" value="<?php print $jelszo ?>">
<input type="hidden" name="Tema_neve_box" value="<?php print $Temanev ?>">
<input type="submit" value=" Igen, töröljük! ">
</form>

<form  name="vissza" method="POST" action="user_ell_keret.php">
<input type="hidden" name="felh_nev_mezo" value="<?php print $felh_nev ?>">
<input type="hidden" name="jelszo" value="<?php print $jelszo ?>">
<input type="submit" value="Ne, ne töröljük!">
</form>

