<?php

include 'beallitas.php';

//Létrehozzuk az összeköttetést az adatbázissal
$ossz = mysqli_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO) or die(mysqli_error());
mysqli_select_db(DB_NEV,$ossz)  or die(mysqli_error());

//Összeállítjuk és futtatjuk a lekérdezést
$sql = "select azon, vnev, knev from azonositas where azon = '$_POST[felh_nev_mezo]' AND jelszo = '$_POST[jelszo]'";
$eredmeny = mysqli_query($sql,$ossz) or die(mysqli_error());


//Helyes felhasználónév és jelszó esetén 1 sort kellett kapnunk
if (mysqli_num_rows($eredmeny) == 1) {
   //Kiolvassuk a jogosult felhasználó nevét
   $v_nev = mysqli_result($eredmeny, 0, 'vnev');
   $k_nev = mysqli_result($eredmeny, 0, 'knev');
   $felh_nev=mysqli_result($eredmeny, 0, 'azon');
   $jelszo=$_POST[jelszo];

   //Összeállítjuk az üzenetet és a menüt
   $uzenet = "<br><br><h3><p>Üdvözöljük, $v_nev $k_nev!</p> Önnek sikerült belépnie!<h3>";
   $uzenet .= "<P>Mit szeretne tenni?</p><br>";
   $uzenet.="<form method=\"post\" action=\"temafeltoltes_form_keret.php\">";
   $uzenet.="<input type=\"hidden\" name=\"felh_nev_mezo\" value=\"$felh_nev\">";
   $uzenet.="<input type=\"hidden\" name=\"jelszo\" value=\"$jelszo\"> ";
   $uzenet.="<input type=\"submit\" value=\"    Új téma létrehozása    \">";
   $uzenet.="</form>";

  //A témákat ideiglenesen el lehet rejteni pl dolgozatírás előtt
   $rejtes_menu="<form method=\"post\" action=\"tema_rejtes_keret.php\">";
   $rejtes_menu.="<input type=\"hidden\" name=\"felh_nev_mezo\" value=\"$felh_nev\">";
   $rejtes_menu.="<input type=\"hidden\" name=\"jelszo\" value=\"$jelszo\"> ";
   $rejtes_menu.="<input type=\"hidden\" name=\"teljes_nev\" value=\"$v_nev $k_nev\"> ";
   $rejtes_menu.="<input type=\"submit\" value=\"Témák elrejtése/felfedése\">";
   $rejtes_menu.="</form>";

  
  
  //lekérdezzük a felhasználó témáit, csak ezeket szerkesztheti
  $ossz = mysqli_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  mysqli_select_db(DB_NEV, $ossz);
  $sql = "SELECT * FROM temak where tulajdonos='$felh_nev' Order By Tema_nev";
  $eredmeny = mysqli_query($sql, $ossz) or die(mysqli_error());
  
  //A témákat felvesszük egy legördulő menübe, egy formba
  $szerkesztes_menu="<form method=\"post\" action=\"tesztepito_keret.php\">";
  $szerkesztes_menu.="<select name=\"Tema_neve_box\">";              
  while ($ujTomb = mysqli_fetch_array($eredmeny)) {
      $Tema_nev=$ujTomb['Tema_nev'];
      $szerkesztes_menu.= "\t<option>$Tema_nev\n";
  } 
  $szerkesztes_menu.= "</select>";
  $szerkesztes_menu.="<input type=\"hidden\" name=\"felh_nev_mezo\" value=\"$felh_nev\"> ";
  $szerkesztes_menu.="<input type=\"hidden\" name=\"jelszo\" value=\"$jelszo\"> ";
  $szerkesztes_menu.="<input type=\"hidden\" name=\"szerkesztes_e_mezo\" value=\"true\"> ";  //ezeket tovább kell vinni
  $szerkesztes_menu.="<input type=\"submit\" value=\"Téma szerkesztése\">";
  $szerkesztes_menu.="</form>";
  
  //itt kezdődik a törlés legördülő menü felépítése
  $eredmeny = mysqli_query($sql, $ossz) or die(mysqli_error());
  $torles_menu="<form method=\"post\" action=\"biztos_benne_keret.php\">";
  $torles_menu.="<select name=\"Tema_neve_box\">";              
  while ($ujTomb = mysqli_fetch_array($eredmeny)) {
      $Tema_nev=$ujTomb['Tema_nev'];
      $torles_menu.= "\t<option>$Tema_nev\n";
  } 
  $torles_menu.= "</select>";
  $torles_menu.="<input type=\"hidden\" name=\"felh_nev_mezo\" value=\"$felh_nev\"> ";
  $torles_menu.="<input type=\"hidden\" name=\"jelszo\" value=\"$jelszo\"> ";  //ezeket tovább kell vinni
  $torles_menu.="<input type=\"submit\" value=\"Téma törlése\">";
  $torles_menu.="</form>";            
                          
} else {
   //A jogosulatlan felhasználókat visszairányítjuk a belépési oldalra
   //header("Location: tesztepito_kezd.php"); ez sajmos nem működik az fw.hu-n
   $hibauzenet="<br><br><br><h3><font color=\"red\">Érvénytelen felhasználónév, vagy jelszó</font></h3><br>";
   $hibauzenet.="<a href=\"tesztepito_kezd.php\">Vissza a bejelentkezéshez</a><br>";
  //exit;
}
